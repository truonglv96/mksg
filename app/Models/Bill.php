<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'bill_details';

    // Bảng bill_details có composite primary key (bill_id, product_id)
    // Không thể dùng $primaryKey vì Laravel không hỗ trợ composite key trực tiếp
    // Sử dụng DB::table() để insert thay vì Model::create()
    protected $primaryKey = null;
    
    public $incrementing = false;
    
    protected $fillable = [
        'bill_id',
        'product_id',
        'category_name',
        'sale_off',
        'price',
        'qty',
        'color_id',
        'brand',
        'unit',
        'color_text',
        'refractive_index',
        'degree_range',
        'lens_package'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
