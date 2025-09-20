<?php

// app/Http/Controllers/Customer/CustomerController.php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = $user->orders()->latest()->limit(5)->get();
        $totalOrders = $user->orders()->count();
        $completedOrders = $user->orders()->where('status', 'delivered')->count();
        
        return view('customer.dashboard', compact('recentOrders', 'totalOrders', 'completedOrders'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock < $request->quantity) {
            return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi']);
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] += $request->quantity;
        } else {
            $cart[$request->product_id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'image' => $product->image
            ];
        }
        
        session()->put('cart', $cart);
        
        return response()->json(['success' => true, 'message' => 'Produk ditambahkan ke keranjang']);
    }

    public function showCart()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('customer.cart', compact('cart', 'total'));
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
        
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,credit_card,cash,other',
            'installment_plan' => 'required|in:full_payment,3_months,6_months,12_months',
            'shipaddress' => 'required|string'
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

        DB::beginTransaction();
        
        try {
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_date' => now(),
                'status' => 'pending',
                'installment_plan' => $request->installment_plan,
                'payment_method' => $request->payment_method,
                'total_price' => $total
            ]);

            // Create order details
            foreach ($cart as $productId => $item) {
                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price_at_purchase' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);

                // Update stock
                $product = Product::find($productId);
                $product->decreaseStock($item['quantity']);
            }

            // Update user address
            Auth::user()->update(['shipaddress' => $request->shipaddress]);

            // Clear cart
            session()->forget('cart');

            DB::commit();

            return redirect()->route('customer.orders')->with('success', 'Pesanan berhasil dibuat. Silakan hubungi admin untuk konfirmasi pembayaran.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pesanan');
        }
    }

    public function orders()
    {
        $orders = Auth::user()->orders()->with('orderDetails.product')->latest()->paginate(10);
        
        return view('customer.orders', compact('orders'));
    }

    public function showOrder($id)
    {
        $order = Auth::user()->orders()->with('orderDetails.product')->findOrFail($id);
        
        return view('customer.order-detail', compact('order'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id() . ',user_id',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'shipaddress' => 'nullable|string'
        ]);

        Auth::user()->update($request->only([
            'fullname', 'email', 'firstname', 'lastname', 'phone', 'shipaddress'
        ]));

        return redirect()->back()->with('success', 'Profile berhasil diupdate');
    }

    // Payment Methods for CustomerController
    public function submitPayment(Request $request, $orderId)
    {
        $order = Auth::user()->orders()->findOrFail($orderId);
        
        $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120' // 5MB max
        ]);

        $totalPaid = $order->payments()->approved()->sum('amount_paid');
        $remainingBalance = max(0, $order->total_price - $totalPaid - $request->amount_paid);

        if ($request->amount_paid > ($order->total_price - $totalPaid)) {
            return redirect()->back()->with('error', 'Jumlah pembayaran melebihi sisa tagihan');
        }

        // Handle file upload
        $fileName = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof')->store('payments', 'public');;
            $fileName = basename($file);
        }

        Payment::create([
            'order_id' => $order->order_id,
            'amount_paid' => $request->amount_paid,
            'payment_date' => now(),
            'remaining_balance' => $remainingBalance,
            'payment_proof' => $fileName,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil dikirim, menunggu verifikasi admin');
    }
}