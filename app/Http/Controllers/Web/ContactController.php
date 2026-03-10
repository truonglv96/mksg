<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Hiển thị danh sách tất cả địa chỉ cửa hàng
     */
    public function index(Request $request)
    {
        $contacts = Contact::getContact();
        
        return view('web.page.contact.index', [
            'title' => 'Hệ Thống Cửa Hàng - Mắt Kính Sài Gòn',
            'contacts' => $contacts,
        ]);
    }
}
