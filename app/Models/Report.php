<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';

    protected $fillable = [
        'user_id',
        'total_sales',
        'report_date',
    ];

    protected $casts = [
        'total_sales' => 'float',
        'report_date' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Accessors
    public function getFormattedTotalSalesAttribute()
    {
        return 'Rp ' . number_format($this->total_sales, 0, ',', '.');
    }
}