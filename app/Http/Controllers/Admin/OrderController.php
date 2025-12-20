<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Bill;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        // TODO: Implement order listing logic
        // $orders = Bill::with('customer')->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.orders.index');
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        // TODO: Get order by id with relations
        // $order = Bill::with('customer', 'details')->findOrFail($id);
        
        return view('admin.orders.show', compact('id'));
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, $id)
    {
        // TODO: Implement order status update logic
        // Validate request
        // Update order status
        // Redirect with success message
        
        return redirect()->route('admin.orders.show', $id)->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy($id)
    {
        // TODO: Implement order deletion logic
        // Delete order and related data
        // Redirect with success message
        
        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa thành công!');
    }
}

