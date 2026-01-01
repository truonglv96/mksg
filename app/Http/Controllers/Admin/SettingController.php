<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        // Get all settings as key-value pairs
        $settings = Setting::first();
        
        // If no settings exist, create default
        if (!$settings) {
            $settings = new Setting();
        }
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'facebook' => 'nullable|string|max:500',
                'youtube' => 'nullable|string|max:500',
                'google_plus' => 'nullable|string|max:500',
                'hotline' => 'nullable|string|max:100',
                'zalo' => 'nullable|string|max:100',
                'email' => 'nullable|email|max:255',
                'meta_title' => 'nullable|string|max:255',
                'meta_keyword' => 'nullable|string|max:500',
                'meta_description' => 'nullable|string|max:500',
                'google_analytic' => 'nullable|string',
                'copyright' => 'nullable|string|max:500',
                'legal_regulations' => 'nullable|string',
                'facebook_fb' => 'nullable|string|max:500',
                'map' => 'nullable|string',
                'order_success' => 'nullable|string',
                'work_time' => 'nullable|string|max:255',
                'branch_address' => 'nullable|string|max:500',
                'about' => 'nullable|string',
                'address' => 'nullable|string|max:500',
                'info' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
                'icon_fb' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',
                'icon_youtube' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',
                'icon_zalo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',
                'icon_email' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',
                'icon_time' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',
            ]);
            
            // Get or create settings
            $settings = Setting::first();
            if (!$settings) {
                $settings = new Setting();
            }
            
            // Handle image uploads
            $imageFields = ['logo', 'icon_fb', 'icon_youtube', 'icon_zalo', 'icon_email', 'icon_time'];
            $imagePath = public_path('img/setting');
            
            // Create directory if not exists
            if (!File::exists($imagePath)) {
                File::makeDirectory($imagePath, 0755, true);
            }
            
            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    // Delete old image if exists
                    if ($settings->$field && File::exists($imagePath . '/' . $settings->$field)) {
                        File::delete($imagePath . '/' . $settings->$field);
                    }
                    
                    $image = $request->file($field);
                    $imageName = time() . '_' . $field . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $image->move($imagePath, $imageName);
                    $validated[$field] = $imageName;
                } else {
                    // Keep existing value if no new image uploaded
                    if ($settings->$field) {
                        $validated[$field] = $settings->$field;
                    }
                }
            }
            
            // Update or create settings
            $settings->fill($validated);
            $settings->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Cài đặt đã được cập nhật thành công!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật cài đặt!',
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
