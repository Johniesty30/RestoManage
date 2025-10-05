<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $salesData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total_sales, COUNT(*) as total_orders')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $totalRevenue = $salesData->sum('total_sales');
        $totalOrders = $salesData->sum('total_orders');

        return view('admin.reports.sales', compact('salesData', 'startDate', 'endDate', 'totalRevenue', 'totalOrders'));
    }
}