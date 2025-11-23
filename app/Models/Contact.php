<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $table = 'contacts';


    public static function getListContactAdmin() {
        return Contact::orderBy('weight', 'asc')->get();
    }

    public static function getContact() {
        return Contact::where('status', 1)->orderBy('weight', 'asc')->get();
    }
}
