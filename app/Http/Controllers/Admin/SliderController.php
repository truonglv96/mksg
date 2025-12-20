<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Slider;

class SliderController extends Controller
{
    /**
     * Display a listing of the sliders.
     */
    public function index()
    {
        // TODO: Implement slider listing logic
        // $sliders = Slider::orderBy('weight', 'asc')->get();
        
        return view('admin.sliders.index');
    }

    /**
     * Show the form for creating a new slider.
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created slider in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement slider creation logic
        // Validate request
        // Create slider
        // Handle image upload
        // Redirect with success message
        
        return redirect()->route('admin.sliders.index')->with('success', 'Slider đã được tạo thành công!');
    }

    /**
     * Show the form for editing the specified slider.
     */
    public function edit($id)
    {
        // TODO: Get slider by id
        
        return view('admin.sliders.edit', compact('id'));
    }

    /**
     * Update the specified slider in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Implement slider update logic
        // Validate request
        // Update slider
        // Handle image upload if any
        // Redirect with success message
        
        return redirect()->route('admin.sliders.index')->with('success', 'Slider đã được cập nhật thành công!');
    }

    /**
     * Remove the specified slider from storage.
     */
    public function destroy($id)
    {
        // TODO: Implement slider deletion logic
        // Delete slider
        // Delete related image
        // Redirect with success message
        
        return redirect()->route('admin.sliders.index')->with('success', 'Slider đã được xóa thành công!');
    }

    /**
     * Toggle the status of the specified slider.
     */
    public function toggleStatus($id)
    {
        // TODO: Implement slider status toggle logic
        // Get slider by id
        // Toggle hidden status
        // Redirect with success message
        
        return redirect()->route('admin.sliders.index')->with('success', 'Trạng thái slider đã được cập nhật!');
    }
}

