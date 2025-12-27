<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $table = 'area';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'parent_id',
        'weight',
    ];

    /**
     * Lấy tất cả quận/huyện thuộc thành phố (theo CityID), kèm theo danh sách phường/xã nếu có.
     *
     * @param int $cityId
     * @return \Illuminate\Support\Collection
     */
    public static function getAllCity($cityId)
    {
        $cities = self::where('parent_id', $cityId)
            ->orderByDesc('weight')
            ->get();

        foreach ($cities as $city) {
            $city->districts = self::where('parent_id', $city->id)
                ->orderByDesc('weight')
                ->get();
        }

        return $cities;
    }
}
