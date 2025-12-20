<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        // TODO: Implement category listing logic
        // $categories = Category::where('type', 'product')->get();
        
        return view('admin.categories.index');
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        // TODO: Get parent categories for form
        
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement category creation logic
        // Validate request
        // Create category
        // Handle image upload if any
        // Redirect with success message
        
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công!');
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        // TODO: Get category by id
        
        return view('admin.categories.show', compact('id'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        // TODO: Get category by id
        // Get parent categories for form
        
        return view('admin.categories.edit', compact('id'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Implement category update logic
        // Validate request
        // Update category
        // Handle image upload if any
        // Redirect with success message
        
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        // TODO: Implement category deletion logic
        // Check if category has products
        // Delete category
        // Redirect with success message
        
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa thành công!');
    }
}

