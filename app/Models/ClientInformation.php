<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientInformation extends Model
{
    use HasFactory;
    protected $table = 'bill';

    public function billItems() {
        return $this->hasMany(Bill::class, 'bill_id', 'id');
    }
}
