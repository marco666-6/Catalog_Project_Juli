<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'detail_id';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price_at_purchase',
        'subtotal',
    ];

    protected $casts = [
        'price_at_purchase' => 'float',
        'subtotal' => 'float',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price_at_purchase, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    // Mutators
    public function setSubtotalAttribute($value)
    {
        $this->attributes['subtotal'] = $this->quantity * $this->price_at_purchase;
    }

    // Methods
    public function calculateSubtotal()
    {
        $this->subtotal = $this->quantity * $this->price_at_purchase;
        $this->save();
        return $this->subtotal;
    }
}