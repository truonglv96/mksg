<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Brand;

class BrandController extends Controller
{
    /**
     * Display a listing of the brands.
     */
    public function index()
    {
        // TODO: Implement brand listing logic
        // $brands = Brand::orderBy('weight', 'asc')->paginate(20);
        
        return view('admin.brands.index');
    }

    /**
     * Show the form for creating a new brand.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created brand in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement brand creation logic
        // Validate request
        // Create brand
        // Handle image upload
        // Redirect with success message
        
        return redirect()->route('admin.brands.index')->with('success', 'Thương hiệu đã được tạo thành công!');
    }

    /**
     * Display the specified brand.
     */
    public function show($id)
    {
        // TODO: Get brand by id
        
        return view('admin.brands.show', compact('id'));
    }

    /**
     * Show the form for editing the specified brand.
     */
    public function edit($id)
    {
        // TODO: Get brand by id
        
        return view('admin.brands.edit', compact('id'));
    }

    /**
     * Update the specified brand in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Implement brand update logic
        // Validate request
        // Update brand
        // Handle image upload if any
        // Redirect with success message
        
        return redirect()->route('admin.brands.index')->with('success', 'Thương hiệu đã được cập nhật thành công!');
    }

    /**
     * Remove the specified brand from storage.
     */
    public function destroy($id)
    {
        // TODO: Implement brand deletion logic
        // Delete brand
        // Delete related image
        // Redirect with success message
        
        return redirect()->route('admin.brands.index')->with('success', 'Thương hiệu đã được xóa thành công!');
    }
}

