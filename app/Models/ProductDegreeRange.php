<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDegreeRange extends Model
{
    use HasFactory;
    
    protected $table = 'product_degree_ranges';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'price_sale',
        'weight',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'price_sale' => 'decimal:2',
        'weight' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the product that owns this degree range.
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}

