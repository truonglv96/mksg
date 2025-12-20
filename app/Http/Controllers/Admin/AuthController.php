<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form.
     * Middleware 'admin.guest' đã xử lý redirect nếu đã đăng nhập
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }
    
    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|min:3',
            'password' => 'required|string|min:6',
        ], [
            'login.required' => 'Vui lòng nhập tên đăng nhập hoặc email',
            'login.min' => 'Tên đăng nhập hoặc email phải có ít nhất 3 ký tự',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('login', 'remember'));
        }
        
        $remember = $request->filled('remember');
        $login = trim($request->login);
        $password = $request->password;
        
        // Tự động detect xem là email hay username và tìm user
        $user = null;
        
        // Kiểm tra xem input có phải là email format không
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);
        
        if ($isEmail) {
            // Nếu là email format, ưu tiên tìm bằng email
            $user = \App\Models\User::where('email', $login)->first();
            
            // Nếu không tìm thấy bằng email, thử tìm bằng username (trường hợp username giống email format)
            if (!$user) {
                $user = \App\Models\User::where('username', $login)->first();
            }
        } else {
            // Nếu không phải email format, ưu tiên tìm bằng username
            $user = \App\Models\User::where('username', $login)->first();
            
            // Nếu không tìm thấy bằng username, thử tìm bằng email (trường hợp email không có @ ở đầu)
            if (!$user) {
                $user = \App\Models\User::where('email', $login)->first();
            }
        }
        
        // Kiểm tra password và đăng nhập
        if ($user && \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            Auth::login($user, $remember);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Đăng nhập thành công!');
        }
        
        // Đăng nhập thất bại
        return back()->withErrors([
            'login' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput($request->only('login', 'remember'));
    }
    
    /**
     * Handle a logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')->with('success', 'Đăng xuất thành công!');
    }
}

