<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedCategory extends Model
{
    use HasFactory;
    protected $table = 'featured_category';
    const IMAGE = 'img/featured-category/';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'link',
        'color',
        'status',
        'weight',
        'image',
    ];

    public function getImage() {
        return asset('img/featured-category/'. $this->image);
    }

    /**
     * Lấy màu nền, đảm bảo format đúng (có # nếu chưa có)
     */
    public function getBackgroundColor() {
        if (empty($this->color)) {
            return '#f3f4f6'; // Màu mặc định
        }
        
        $color = trim($this->color);
        
        // Nếu đã có # thì giữ nguyên
        if (strpos($color, '#') === 0) {
            return $color;
        }
        
        // Nếu chưa có # thì thêm vào
        return '#' . $color;
    }

    /**
     * Lấy danh sách featured categories active
     */
    public static function getAllActive($limit = 4) {
        return self::where('status', 1)
            ->orderBy('weight', 'asc')
            ->limit($limit)
            ->get();
    }
}
