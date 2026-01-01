<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display the admin profile.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập lại.');
        }
        
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Show the form for editing the admin profile.
     */
    public function edit()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập lại.');
        }
        
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the admin profile.
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập lại.'
                ], 401);
            }
            
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6|confirmed',
                'password_confirmation' => 'nullable|string|min:6',
                'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            ]);
            
            // Update username and email
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            
            // Update password if provided
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatarPath = public_path('img/avatars');
                
                // Create directory if not exists
                if (!File::exists($avatarPath)) {
                    File::makeDirectory($avatarPath, 0755, true);
                }
                
                // Delete old avatar if exists
                if ($user->avatar && File::exists($avatarPath . '/' . $user->avatar)) {
                    File::delete($avatarPath . '/' . $user->avatar);
                }
                
                $avatar = $request->file('avatar');
                $avatarName = time() . '_' . Str::random(10) . '.' . $avatar->getClientOriginalExtension();
                $avatar->move($avatarPath, $avatarName);
                $user->avatar = $avatarName;
            }
            
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Thông tin đã được cập nhật thành công!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thông tin!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
