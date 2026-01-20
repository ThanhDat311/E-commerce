@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800" style="color: var(--sidebar-text); font-weight: 400;">Dashboard</h1>
    </div>

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-dashboard border-left-primary h-100 py-2" style="border-left: 4px solid var(--brand-primary);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--brand-primary);">
                                Earnings (Monthly)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300" style="color: #dddfeb;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-dashboard border-left-success h-100 py-2" style="border-left: 4px solid var(--brand-success);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--brand-success);">
                                Earnings (Annual)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300" style="color: #dddfeb;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-dashboard border-left-info h-100 py-2" style="border-left: 4px solid var(--brand-info);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--brand-info);">
                                Total Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $productCount ?? 150 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300" style="color: #dddfeb;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-dashboard border-left-warning h-100 py-2" style="border-left: 4px solid var(--brand-warning);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--brand-warning);">
                                Pending Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300" style="color: #dddfeb;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
             <div class="card card-dashboard mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold" style="color: var(--brand-primary);">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <p>Khu vực này có thể để bảng đơn hàng mới nhất hoặc biểu đồ...</p>
                </div>
            </div>
        </div>
    </div>

@endsection