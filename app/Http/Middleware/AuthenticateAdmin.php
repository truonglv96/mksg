<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Nếu chưa đăng nhập, redirect đến trang login
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('admin.login'))->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // TODO: Nếu cần kiểm tra role/permission admin, thêm logic ở đây
        // if (!Auth::user()->isAdmin()) {
        //     return redirect()->route('admin.login')->with('error', 'Bạn không có quyền truy cập.');
        // }

        return $next($request);
    }
}
