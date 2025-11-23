<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Customer extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'created_user_id',
        'id_user',
        'first_name',
        'birthday',
        'last_name',
        'email_customer',
        'phone',
        'avatar',
        'status',
    ];
    const AVATAR = 'uploads/customers/';
    
    public function scopeOfType($query, $type)
    {
        return $query->where('status', $type);
    }

    public function userLogin()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
    public function customerRoles()
    {
        return $this->belongsToMany(
            Role::class,
            CustomerRole::class,
            'id_customer',
            'id_role',
            'id');
    }
}
