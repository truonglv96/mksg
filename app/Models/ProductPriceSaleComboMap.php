<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriceSaleComboMap extends Model
{
    use HasFactory;

    protected $table = 'product_price_sale_combo_maps';

    protected $fillable = [
        'product_id',
        'price_sale_id',
        'combo_id',
        'price',
        'weight',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'price_sale_id' => 'integer',
        'combo_id' => 'integer',
        'price' => 'integer',
        'weight' => 'integer',
    ];

    public function priceSale()
    {
        return $this->belongsTo(ProductPriceSale::class, 'price_sale_id', 'id');
    }

    public function combo()
    {
        return $this->belongsTo(DiscountedCombo::class, 'combo_id', 'id');
    }
}
