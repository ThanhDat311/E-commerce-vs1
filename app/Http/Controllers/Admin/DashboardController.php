<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureStore;

class DashboardController extends Controller
{
    protected $orderRepo;

    protected $userRepo;

    public function __construct(
        \App\Repositories\Interfaces\OrderRepositoryInterface $orderRepo,
        \App\Repositories\Interfaces\UserRepositoryInterface $userRepo
    ) {
        $this->orderRepo = $orderRepo;
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        // 1. CÁC CON SỐ TỔNG QUAN
        $totalRevenue = $this->orderRepo->getTotalRevenue();
        $totalOrders = $this->orderRepo->count();
        $pendingOrders = $this->orderRepo->getPendingCount();
        $activeUsers = $this->userRepo->getActiveUsersCount(); // Using User Repo

        // AI Risk Score (Login Risk)
        $fraudBlocked = \App\Models\AuthLog::where('auth_decision', 'block_access')->count();
        $avgRiskScore = \App\Models\AuthLog::avg('risk_score') ?? 0;

        // 2. BIỂU ĐỒ DOANH THU (Line Chart)
        $revenueData = $this->orderRepo->getRevenueData(7);
        $chartLabels = $revenueData->pluck('date')->toArray();
        $chartValues = $revenueData->pluck('total')->toArray();

        // 3. BIỂU ĐỒ RỦI RO ĐĂNG NHẬP (Pie Chart)
        $lowRisk = \App\Models\AuthLog::where('risk_level', 'low')->count();
        $mediumRisk = \App\Models\AuthLog::where('risk_level', 'medium')->count();
        $highRisk = \App\Models\AuthLog::where('risk_level', 'high')->count();
        $riskData = [(int) $lowRisk, (int) $mediumRisk, (int) $highRisk];

        // 4. LATEST ORDERS
        $latestOrders = $this->orderRepo->getLatestOrders(5);

        return view('pages.admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'activeUsers',
            'fraudBlocked',
            'avgRiskScore',
            'chartLabels',
            'chartValues',
            'riskData',
            'latestOrders'
        ));
    }
}
