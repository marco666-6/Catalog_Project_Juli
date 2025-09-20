<?php

// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()->inStock()->latest()->limit(8)->get();
        $categories = Category::has('products')->get();
        
        return view('home', compact('featuredProducts', 'categories'));
    }

    public function catalog()
    {
        $products = Product::active()->inStock()->with('category')->paginate(12);
        $categories = Category::has('products')->get();
        
        return view('catalog', compact('products', 'categories'));
    }

    public function product($id)
    {
        $product = Product::active()->findOrFail($id);
        $relatedProducts = Product::active()->inStock()
            ->where('category_id', $product->category_id)
            ->where('product_id', '!=', $id)
            ->limit(4)->get();
            
        return view('product-detail', compact('product', 'relatedProducts'));
    }

    // Add this method to your HomeController class
    
    public function contact()
    {
        $contact = \App\Models\Contact::getActive();
        
        // Create default contact if none exists
        if (!$contact) {
            $contact = \App\Models\Contact::create([
                'company_name' => 'PT. Batam General Supplier',
                'address' => 'Jl. Industrial Estate Blok A No. 15, Batam Centre, Batam 29461, Kepulauan Riau, Indonesia',
                'phone_primary' => '0778123456',
                'phone_secondary' => '081234567890',
                'email_primary' => 'info@batamgeneralsupplier.com',
                'email_secondary' => 'sales@batamgeneralsupplier.com',
                'whatsapp' => '6281234567890',
                'operational_hours' => \App\Models\Contact::getDefaultOperationalHours(),
                'is_active' => true
            ]);
        }
        
        return view('contact', compact('contact'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $categoryId = $request->input('category');
        
        $products = Product::active()
            ->when($query, function ($q) use ($query) {
                return $q->where('name', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                return $q->where('category_id', $categoryId);
            })
            ->with('category')
            ->paginate(12);

        $categories = Category::has('products')->get();
        
        return view('catalog', compact('products', 'categories', 'query', 'categoryId'));
    }
}