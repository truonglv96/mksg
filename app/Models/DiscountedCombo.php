<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountedCombo extends Model
{
    use HasFactory;

    protected $table = 'discounted_combo';

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'product_id',
        'weight',
    ];

    protected $casts = [
        'price' => 'integer',
        'status' => 'integer', // Giữ nguyên integer để query dễ hơn
        'product_id' => 'integer',
        'weight' => 'integer',
    ];

    /**
     * Relationship với Products
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}

