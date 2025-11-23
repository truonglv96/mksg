<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_role',
        'id_customer',
    ];
    public function customerRole()
    {
        return $this->hasOne(Role::class, 'id', 'id_role');
    }
}
