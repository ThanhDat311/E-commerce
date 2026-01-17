<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\AiFeatureStore;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. CÁC CON SỐ TỔNG QUAN
        $totalRevenue = Order::where('order_status', '!=', 'cancelled')->sum('total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $fraudBlocked = AiFeatureStore::where('risk_score', '>=', 0.7)->count();

        // 2. BIỂU ĐỒ DOANH THU (Line Chart)
        $revenueData = Order::select(
                            DB::raw('DATE(created_at) as date'), 
                            DB::raw('SUM(total) as total')
                        )
                        ->where('order_status', '!=', 'cancelled')
                        ->where('created_at', '>=', Carbon::now()->subDays(7))
                        ->groupBy('date')
                        ->orderBy('date', 'ASC')
                        ->get();

        // Ép kiểu về mảng thuần cho JS
        $chartLabels = $revenueData->pluck('date')->toArray();
        $chartValues = $revenueData->pluck('total')->toArray();

        // 3. BIỂU ĐỒ RỦI RO (Pie Chart)
        $lowRisk = AiFeatureStore::where('risk_score', '<', 0.3)->count();
        $mediumRisk = AiFeatureStore::whereBetween('risk_score', [0.3, 0.7])->count();
        $highRisk = AiFeatureStore::where('risk_score', '>=', 0.7)->count();

        // --- QUAN TRỌNG: GOM DỮ LIỆU LẠI THÀNH MẢNG $riskData ---
        $riskData = [
            (int) $lowRisk,
            (int) $mediumRisk,
            (int) $highRisk
        ];

        // --- TRUYỀN BIẾN SANG VIEW ---
        // Phải có 'riskData' trong compact() thì bên View mới hiểu
        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'pendingOrders', 'fraudBlocked',
            'chartLabels', 'chartValues', 
            'riskData' 
        ));
    }
}