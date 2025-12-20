<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the admin profile.
     */
    public function index()
    {
        // TODO: Get current admin user
        // $user = Auth::user();
        
        return view('admin.profile.index');
    }

    /**
     * Show the form for editing the admin profile.
     */
    public function edit()
    {
        // TODO: Get current admin user
        // $user = Auth::user();
        
        return view('admin.profile.edit');
    }

    /**
     * Update the admin profile.
     */
    public function update(Request $request)
    {
        // TODO: Implement profile update logic
        // Validate request
        // Update user profile
        // Handle avatar upload if any
        // Redirect with success message
        
        return redirect()->route('admin.profile')->with('success', 'Thông tin đã được cập nhật thành công!');
    }
}

