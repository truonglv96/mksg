<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientInformation;
use App\Models\TatalBillDetail;
use App\Models\Bill;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        // Get query parameters
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $sort = $request->get('sort', 'newest');
        
        // Build query
        $query = ClientInformation::query();
        
        // Search - tìm theo tên, email, phone, code_bill
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%')
                  ->orWhere('phone', 'LIKE', '%' . $search . '%')
                  ->orWhere('code_bill', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Status filter
        if ($status !== '') {
            $query->where('status', $status);
        }
        
        // Sort
        switch ($sort) {
            case 'oldest':
                $query->orderBy('id', 'ASC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'ASC');
                break;
            case 'name_desc':
                $query->orderBy('name', 'DESC');
                break;
            case 'total_asc':
                $query->orderBy('id', 'ASC'); // Will be sorted by total after fetching
                break;
            case 'total_desc':
                $query->orderBy('id', 'DESC'); // Will be sorted by total after fetching
                break;
            default: // newest
                $query->orderBy('id', 'DESC');
        }
        
        // Paginate
        $orders = $query->paginate(20)->withQueryString();
        
        // Get total amounts for each order
        $orderIds = $orders->pluck('id')->toArray();
        $totals = TatalBillDetail::whereIn('bill_id', $orderIds)
            ->pluck('tatal', 'bill_id')
            ->toArray();
        
        // Attach totals to orders
        foreach ($orders as $order) {
            $order->total = $totals[$order->id] ?? 0;
        }
        
        // Sort by total if needed
        if (in_array($sort, ['total_asc', 'total_desc'])) {
            $orders->getCollection()->sortBy(function($order) use ($sort) {
                return $sort === 'total_asc' ? $order->total : -$order->total;
            });
        }
        
        // Get stats
        $stats = ClientInformation::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as processing,
            SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as cancelled
        ')->first();
        
        $totalOrders = $stats->total ?? 0;
        $pendingOrders = $stats->pending ?? 0;
        $processingOrders = $stats->processing ?? 0;
        $completedOrders = $stats->completed ?? 0;
        $cancelledOrders = $stats->cancelled ?? 0;
        
        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'cancelledOrders',
            'search',
            'status',
            'sort'
        ));
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = ClientInformation::with('billItems.product')->findOrFail($id);
        
        // Get total amount
        $total = TatalBillDetail::where('bill_id', $id)->value('tatal') ?? 0;
        $order->total = $total;
        
        // Get status config
        $statusConfig = [
            0 => ['class' => 'status-pending', 'text' => 'Chờ xử lý', 'icon' => 'fa-clock', 'color' => 'yellow'],
            1 => ['class' => 'status-processing', 'text' => 'Đang xử lý', 'icon' => 'fa-spinner', 'color' => 'indigo'],
            2 => ['class' => 'status-completed', 'text' => 'Hoàn thành', 'icon' => 'fa-check-circle', 'color' => 'green'],
            3 => ['class' => 'status-cancelled', 'text' => 'Đã hủy', 'icon' => 'fa-times-circle', 'color' => 'red'],
        ];
        $order->statusConfig = $statusConfig[$order->status] ?? $statusConfig[0];
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|in:0,1,2,3'
        ]);
        
        $order = ClientInformation::findOrFail($id);
        $order->status = $request->status;
        $order->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Trạng thái đơn hàng đã được cập nhật thành công!',
                'status' => $order->status
            ]);
        }
        
        return redirect()->route('admin.orders.index')->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy($id)
    {
        try {
            // Start transaction to ensure data consistency
            DB::beginTransaction();
            
            // Find the order first to get code_bill for success message
            $order = ClientInformation::findOrFail($id);
            $orderCode = $order->code_bill ?? $id;
            
            // Delete related bill details (bill_details table)
            // Note: Bill model uses bill_id as primary key, so we delete by bill_id
            DB::table('bill_details')->where('bill_id', $id)->delete();
            
            // Delete total bill detail (tatal_bill_details table)
            TatalBillDetail::where('bill_id', $id)->delete();
            
            // Delete the order itself (bill table)
            $order->delete();
            
            // Commit transaction
            DB::commit();
            
            return redirect()->route('admin.orders.index')
                ->with('success', 'Đơn hàng #' . $orderCode . ' đã được xóa thành công!');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Order not found
            DB::rollBack();
            return redirect()->route('admin.orders.index')
                ->with('error', 'Không tìm thấy đơn hàng cần xóa.');
                
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            // Log error for debugging
            \Log::error('Error deleting order: ' . $e->getMessage(), [
                'order_id' => $id,
                'exception' => $e
            ]);
            
            return redirect()->route('admin.orders.index')
                ->with('error', 'Không thể xóa đơn hàng. Vui lòng thử lại sau.');
        }
    }
}

