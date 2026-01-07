<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientInformation extends Model
{
    use HasFactory;
    protected $table = 'bill';

    protected $fillable = [
        'name',
        'address',
        'email',
        'phone',
        'sex',
        'city',
        'district',
        'ship',
        'note',
        'code_bill',
        'payment',
        'status'
    ];

    public function billItems() {
        return $this->hasMany(Bill::class, 'bill_id', 'id');
    }

    public function cityArea() {
        return $this->belongsTo(Area::class, 'city', 'id');
    }

    public function districtArea() {
        return $this->belongsTo(Area::class, 'district', 'id');
    }
}
