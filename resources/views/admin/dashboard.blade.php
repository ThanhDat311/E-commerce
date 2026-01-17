@extends('layouts.master')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-5">
    <div class="container">
        <h2 class="text-primary fw-bold mb-4">Dashboard Overview</h2>

        {{-- 1. 4 CARD THỐNG KÊ --}}
        <div class="row g-4 mb-5">
            {{-- Card 1: Doanh thu --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-white bg-primary h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2 opacity-75">Total Revenue</h6>
                        <h3 class="fw-bold mb-0">${{ number_format($totalRevenue, 2) }}</h3>
                    </div>
                </div>
            </div>
            {{-- Card 2: Tổng đơn --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-light h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2 text-muted">Total Orders</h6>
                        <h3 class="fw-bold text-dark mb-0">{{ $totalOrders }}</h3>
                    </div>
                </div>
            </div>
            {{-- Card 3: Đơn chờ xử lý --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2 text-dark opacity-75">Pending Action</h6>
                        <h3 class="fw-bold text-dark mb-0">{{ $pendingOrders }}</h3>
                    </div>
                </div>
            </div>
            {{-- Card 4: AI đã chặn (Fraud) --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-danger text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase mb-2 opacity-75">High Risk Detected</h6>
                                <h3 class="fw-bold mb-0">{{ $fraudBlocked }}</h3>
                            </div>
                            <i class="fas fa-robot fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. KHU VỰC BIỂU ĐỒ --}}
        <div class="row g-4">
            {{-- Biểu đồ Doanh thu (Line Chart) --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Revenue Last 7 Days</h5>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 300px; width: 100%;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Biểu đồ AI Risk (Pie Chart) --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between">
                        <h5 class="mb-0 fw-bold">AI Risk Distribution</h5>
                        <span class="badge bg-info text-dark">AI Insights</span>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 250px; width: 100%;">
                            <canvas id="riskChart"></canvas>
                        </div>

                        <div class="mt-3 small text-muted text-center">
                            * Based on AI Feature Store data
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT VẼ BIỂU ĐỒ --}}
{{-- Thư viện Chart.js (Dùng bản ổn định 4.4.1) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. LẤY DỮ LIỆU TỪ LARAVEL
        const labels = {!! json_encode(array_values($chartLabels)) !!};
        const revenueData = {!! json_encode(array_values($chartValues)) !!};
        const riskData = {!! json_encode($riskData) !!};

        // --- DEBUG: Bật F12 -> Console để xem dòng này ---
        console.log("Chart Labels:", labels);
        console.log("Revenue Data:", revenueData);
        console.log("Risk Data:", riskData);

        // 2. VẼ BIỂU ĐỒ DOANH THU (LINE CHART)
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels, 
                    datasets: [{
                        label: 'Revenue ($)',
                        data: revenueData,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // 3. VẼ BIỂU ĐỒ RỦI RO (DOUGHNUT CHART)
        const riskCtx = document.getElementById('riskChart');
        if (riskCtx) {
            // Kiểm tra nếu tất cả dữ liệu đều bằng 0 thì không vẽ (tránh lỗi chart rỗng)
            const totalRisk = riskData.reduce((a, b) => a + b, 0);
            
            if (totalRisk > 0) {
                new Chart(riskCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Safe (Low)', 'Suspicious (Medium)', 'High Risk'],
                        datasets: [{
                            data: riskData,
                            backgroundColor: [
                                '#198754', // Xanh
                                '#ffc107', // Vàng
                                '#dc3545'  // Đỏ
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            } else {
                // Hiển thị thông báo nếu chưa có dữ liệu AI
                riskCtx.parentElement.innerHTML = '<p class="text-center text-muted py-5">No AI data available yet.</p>';
            }
        }
    });
</script>
@endsection