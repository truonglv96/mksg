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

    /**
     * Accessor để đảm bảo số điện thoại luôn có số 0 đầu
     */
    public function getFormattedPhoneAttribute() {
        if (empty($this->phone)) {
            return '';
        }
        
        $phone = trim($this->phone);
        
        // Nếu số điện thoại không bắt đầu bằng 0, thêm 0 vào đầu
        if (!empty($phone) && substr($phone, 0, 1) !== '0') {
            // Loại bỏ các ký tự không phải số
            $phone = preg_replace('/[^0-9]/', '', $phone);
            // Thêm số 0 nếu chưa có
            if (!empty($phone) && substr($phone, 0, 1) !== '0') {
                $phone = '0' . $phone;
            }
        }
        
        return $phone;
    }
}
