<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Products;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        // TODO: Implement product listing logic
        // $products = Products::paginate(20);
        
        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        // TODO: Get categories, brands, etc. for form
        
        return view('admin.products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement product creation logic
        // Validate request
        // Create product
        // Handle images upload
        // Redirect with success message
        
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        // TODO: Get product by id
        
        return view('admin.products.show', compact('id'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        // TODO: Get product by id
        // Get categories, brands, etc. for form
        
        return view('admin.products.edit', compact('id'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Implement product update logic
        // Validate request
        // Update product
        // Handle images upload if any
        // Redirect with success message
        
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        // TODO: Implement product deletion logic
        // Delete product
        // Delete related images
        // Redirect with success message
        
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa thành công!');
    }
}

