<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'order_id',
        'amount_paid',
        'payment_date',
        'remaining_balance',
        'payment_proof',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'amount_paid' => 'float',
        'remaining_balance' => 'float',
        'payment_date' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    // Accessors
    public function getFormattedAmountPaidAttribute()
    {
        return 'Rp ' . number_format($this->amount_paid, 0, ',', '.');
    }

    public function getFormattedRemainingBalanceAttribute()
    {
        return 'Rp ' . number_format($this->remaining_balance, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getPaymentProofUrlAttribute()
    {
        return $this->payment_proof ? asset('storage/payments/' . $this->payment_proof) : null;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Methods
    public function isFullyPaid()
    {
        return $this->remaining_balance <= 0;
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}