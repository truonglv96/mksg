<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Hiển thị danh sách tất cả đối tác
     */
    public function index(Request $request)
    {
        $partners = Partner::getAllPartner();
        
        return view('web.page.partner.index', [
            'title' => 'Đối Tác - Mắt Kính Sài Gòn',
            'partners' => $partners,
        ]);
    }

    /**
     * Hiển thị chi tiết đối tác
     */
    public function detail($alias, Request $request)
    {
        $partner = Partner::getDetailPartner($alias);
        
        if (!$partner) {
            abort(404);
        }

        return view('web.page.partner.detail', [
            'title' => $partner->name . ' - Đối Tác - Mắt Kính Sài Gòn',
            'partner' => $partner,
        ]);
    }
}

