<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $table = 'contacts';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'address',
        'strart_time',
        'end_time',
        'status',
        'weight',
    ];


    public static function getListContactAdmin() {
        return Contact::orderBy('weight', 'asc')->get();
    }

    public static function getContact() {
        return Contact::where('status', 1)->orderBy('weight', 'asc')->get();
    }
}
