<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'material';

    public static function getMaterialAdmin() {
        return Material::orderBy('weight', 'asc')->get();
    }
}
