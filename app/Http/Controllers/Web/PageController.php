<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Hiển thị chi tiết page
     */
    public function detail($alias, Request $request)
    {
        // Tìm page theo link - có thể là alias hoặc URL đầy đủ
        $page = Page::where('link', $alias)
            ->orWhere('link', 'like', '%/' . $alias)
            ->orWhere('link', 'like', $alias . '%')
            ->first();
        
        if (!$page) {
            abort(404);
        }
        
        // Chỉ kiểm tra hidden nếu có field hidden
        if (isset($page->hidden) && $page->hidden != Page::IS_ACTIVE) {
            abort(404);
        }

        return view('web.page.policy.detail', [
            'title' => ($page->title ?? $page->name) . ' - Mắt Kính Sài Gòn',
            'page' => $page,
        ]);
    }
}

