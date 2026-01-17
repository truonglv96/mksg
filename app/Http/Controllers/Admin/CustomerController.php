<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index()
    {
        // TODO: Implement customer listing logic
        // $customers = Customer::orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.customers.index');
    }

    /**
     * Display the specified customer.
     */
    public function show($id)
    {
        // TODO: Get customer by id with orders
        // $customer = Customer::with('orders')->findOrFail($id);
        
        return view('admin.customers.show', compact('id'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit($id)
    {
        // TODO: Get customer by id
        
        return view('admin.customers.edit', compact('id'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Implement customer update logic
        // Validate request
        // Update customer
        // Redirect with success message
        
        return redirect()->route('admin.customers.show', $id)->with('success', 'Thông tin khách hàng đã được cập nhật!');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Request $request, $id)
    {
        // TODO: Implement customer deletion logic
        // Check if customer has orders
        // Delete customer
        // Redirect with success message
        $successMessage = 'Khách hàng đã được xóa thành công!';
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage
            ]);
        }

        return redirect()->route('admin.customers.index')->with('success', $successMessage);
    }
}

