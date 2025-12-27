<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\TatalBillDetail;
use App\Models\Customer;
use App\Models\Products;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get current month and last month
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        
        // Total Orders
        $totalOrders = DB::table('bill_details')
            ->select(DB::raw('COUNT(DISTINCT bill_id) as total'))
            ->first()->total ?? 0;
        
        $currentMonthOrders = DB::table('bill_details')
            ->select(DB::raw('COUNT(DISTINCT bill_id) as total'))
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [Carbon::now()->format('Y-m')])
            ->first()->total ?? 0;
        
        $lastMonthOrders = DB::table('bill_details')
            ->select(DB::raw('COUNT(DISTINCT bill_id) as total'))
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [Carbon::now()->subMonth()->format('Y-m')])
            ->first()->total ?? 0;
        
        $orderGrowth = $lastMonthOrders > 0 
            ? round((($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1)
            : ($currentMonthOrders > 0 ? 100 : 0);
        
        // Total Revenue
        $totalRevenue = DB::table('bill_details')
            ->select(DB::raw('SUM(price * qty * (1 - COALESCE(sale_off, 0) / 100)) as total'))
            ->first()->total ?? 0;
        
        $currentMonthRevenue = DB::table('bill_details')
            ->select(DB::raw('SUM(price * qty * (1 - COALESCE(sale_off, 0) / 100)) as total'))
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [Carbon::now()->format('Y-m')])
            ->first()->total ?? 0;
        
        $lastMonthRevenue = DB::table('bill_details')
            ->select(DB::raw('SUM(price * qty * (1 - COALESCE(sale_off, 0) / 100)) as total'))
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [Carbon::now()->subMonth()->format('Y-m')])
            ->first()->total ?? 0;
        
        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : ($currentMonthRevenue > 0 ? 100 : 0);
        
        // Total Products
        $totalProducts = Products::count();
        
        // Total Customers
        $totalCustomers = Customer::count();
        $currentMonthCustomers = Customer::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        $lastMonthCustomers = Customer::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
        
        $customerGrowth = $lastMonthCustomers > 0 
            ? round((($currentMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100, 1)
            : ($currentMonthCustomers > 0 ? 100 : 0);
        
        // Recent Orders (last 10 orders)
        $recentOrders = DB::table('bill_details')
            ->select(
                'bill_id',
                DB::raw('MAX(created_at) as order_date'),
                DB::raw('SUM(price * qty * (1 - COALESCE(sale_off, 0) / 100)) as total_amount')
            )
            ->groupBy('bill_id')
            ->orderBy('order_date', 'desc')
            ->limit(10)
            ->get()
            ->map(function($order) {
                // Try to get customer info from tatal_bill_details if table exists
                $customer = null;
                try {
                    if (DB::getSchemaBuilder()->hasTable('tatal_bill_details')) {
                        $customer = DB::table('tatal_bill_details')
                            ->where('bill_id', $order->bill_id)
                            ->first();
                    }
                } catch (\Exception $e) {
                    // Table doesn't exist or error
                }
                
                return [
                    'id' => $order->bill_id,
                    'customer_name' => $customer->customer_name ?? ($customer->name ?? 'Khách vãng lai'),
                    'total' => $order->total_amount ?? 0,
                    'date' => $order->order_date ? Carbon::parse($order->order_date)->format('d/m/Y') : 'N/A',
                    'status' => $customer->status ?? 'pending'
                ];
            });
        
        // Recent Activities
        $recentActivities = collect();
        
        // Recent orders
        $recentOrderActivities = DB::table('bill_details')
            ->select(
                'bill_id',
                DB::raw('MAX(created_at) as created_at'),
                DB::raw('"order" as type')
            )
            ->groupBy('bill_id')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($activity) {
                return [
                    'type' => 'order',
                    'message' => 'Đơn hàng mới',
                    'detail' => '#' . $activity->bill_id . ' vừa được đặt',
                    'time' => Carbon::parse($activity->created_at)->diffForHumans(),
                    'icon' => 'shopping-cart',
                    'color' => 'blue'
                ];
            });
        
        // Recent customers
        $recentCustomerActivities = Customer::orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($customer) {
                return [
                    'type' => 'customer',
                    'message' => 'Khách hàng mới',
                    'detail' => ($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '') . ' đã đăng ký',
                    'time' => $customer->created_at->diffForHumans(),
                    'icon' => 'user',
                    'color' => 'green'
                ];
            });
        
        // Recent products
        $recentProductActivities = Products::orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($product) {
                return [
                    'type' => 'product',
                    'message' => 'Sản phẩm mới',
                    'detail' => $product->name . ' đã được thêm',
                    'time' => $product->created_at->diffForHumans(),
                    'icon' => 'box',
                    'color' => 'purple'
                ];
            });
        
        $recentActivities = $recentOrderActivities
            ->merge($recentCustomerActivities)
            ->merge($recentProductActivities)
            ->sortByDesc(function($activity) {
                return $activity['time'];
            })
            ->take(3);
        
        // Revenue by Month (Last 12 months)
        $revenueByMonth = [];
        $months = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthRevenue = DB::table('bill_details')
                ->select(DB::raw('SUM(price * qty * (1 - COALESCE(sale_off, 0) / 100)) as total'))
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->first()->total ?? 0;
            
            $revenueByMonth[] = (float) $monthRevenue;
            $months[] = $month->format('M Y');
        }
        
        // Revenue by Month (Last 6 months) - for 6 months option
        $revenueByMonth6 = [];
        $months6 = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthRevenue = DB::table('bill_details')
                ->select(DB::raw('SUM(price * qty * (1 - COALESCE(sale_off, 0) / 100)) as total'))
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->first()->total ?? 0;
            
            $revenueByMonth6[] = (float) $monthRevenue;
            $months6[] = $month->format('M Y');
        }
        
        // Revenue by Month (Last 3 months) - for 3 months option
        $revenueByMonth3 = [];
        $months3 = [];
        
        for ($i = 2; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthRevenue = DB::table('bill_details')
                ->select(DB::raw('SUM(price * qty * (1 - COALESCE(sale_off, 0) / 100)) as total'))
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->first()->total ?? 0;
            
            $revenueByMonth3[] = (float) $monthRevenue;
            $months3[] = $month->format('M Y');
        }
        
        return view('admin.dashboard.index', compact(
            'totalOrders',
            'orderGrowth',
            'totalRevenue',
            'revenueGrowth',
            'totalProducts',
            'totalCustomers',
            'customerGrowth',
            'recentOrders',
            'recentActivities',
            'revenueByMonth',
            'months',
            'revenueByMonth6',
            'months6',
            'revenueByMonth3',
            'months3'
        ));
    }
}

