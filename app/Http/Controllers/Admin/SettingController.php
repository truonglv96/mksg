<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        // TODO: Get all settings
        // $settings = Setting::all()->pluck('value', 'key');
        
        return view('admin.settings.index');
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        // TODO: Implement settings update logic
        // Validate request
        // Update settings
        // Redirect with success message
        
        return redirect()->route('admin.settings.index')->with('success', 'Cài đặt đã được lưu thành công!');
    }
}

