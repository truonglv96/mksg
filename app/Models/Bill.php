<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'bill_details';

    protected $primaryKey = 'bill_id';
    
    public $incrementing = false;
    
    protected $fillable = [
        'bill_id',
        'product_id',
        'category_name',
        'sale_off',
        'price',
        'qty',
        'color_id'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
