@extends('layouts.master')

@section('title', 'Order #' . $order->id . ' Details')

@section('content')
<div class="container-fluid py-5">
    <div class="container">
        {{-- Header & Nút Back --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.orders.index') }}" class="text-muted text-decoration-none mb-2 d-inline-block">
                    <i class="fa fa-arrow-left me-1"></i> Back to List
                </a>
                <h2 class="text-primary fw-bold mb-0">Order Details #{{ $order->id }}</h2>
            </div>
            
            {{-- Hiển thị trạng thái hiện tại --}}
            <div>
                @php
                    $statusColors = [
                        'pending' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger'
                    ];
                    $color = $statusColors[$order->order_status] ?? 'secondary';
                @endphp
                <span class="badge bg-{{ $color }} fs-5 px-4 py-2 text-uppercase">{{ $order->order_status }}</span>
            </div>
        </div>

        <div class="row g-4">
            {{-- CỘT TRÁI: THÔNG TIN CHI TIẾT --}}
            <div class="col-lg-8">
                {{-- 1. Danh sách sản phẩm --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Items Ordered</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            {{-- Ảnh nhỏ --}}
                                            <img src="{{ asset($item->product->image_url ?? 'img/default.png') }}" 
                                                 class="rounded border me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0 text-dark">{{ $item->product_name }}</h6>
                                                <small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">${{ number_format($item->price, 2) }}</td>
                                    <td class="text-center align-middle">x{{ $item->quantity }}</td>
                                    <td class="text-end pe-4 align-middle fw-bold">${{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold pt-3">Subtotal:</td>
                                    <td class="text-end pe-4 pt-3 fw-bold">${{ number_format($order->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- 2. Thông tin khách hàng & Vận chuyển --}}
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold">Customer Info</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                                <p class="mb-1"><strong>Email:</strong> <a href="mailto:{{ $order->email }}">{{ $order->email }}</a></p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $order->phone }}</p>
                                <p class="mb-0"><strong>Account:</strong> {{ $order->user ? 'Registered User' : 'Guest Checkout' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold">Shipping Address</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">{{ $order->address }}</p>
                                @if($order->note)
                                    <div class="alert alert-warning mb-0 py-2 small">
                                        <strong>Note:</strong> {{ $order->note }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: AI ANALYSIS & ACTION --}}
            <div class="col-lg-4">
                
                {{-- 1. AI RISK PANEL --}}
                @if($order->aiFeature)
                    @php
                        $score = $order->aiFeature->risk_score;
                        $isHighRisk = $score >= 0.7;
                        $cardClass = $isHighRisk ? 'border-danger' : ($score >= 0.3 ? 'border-warning' : 'border-success');
                        $headerClass = $isHighRisk ? 'bg-danger text-white' : ($score >= 0.3 ? 'bg-warning text-dark' : 'bg-success text-white');
                    @endphp
                    <div class="card shadow-sm mb-4 border-2 {{ $cardClass }}">
                        <div class="card-header {{ $headerClass }} py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-robot me-2"></i>AI Analysis</h5>
                            <span class="badge bg-white text-dark">{{ $score * 100 }}% Risk</span>
                        </div>
                        <div class="card-body">
                            <h6 class="fw-bold">Risk Factors Detected:</h6>
                            @if($order->aiFeature->reasons)
                                <ul class="list-group list-group-flush mb-3">
                                    @foreach($order->aiFeature->reasons as $reason)
                                        <li class="list-group-item px-0 py-1 text-danger">
                                            <i class="fas fa-exclamation-circle me-2"></i> {{ $reason }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-success"><i class="fas fa-check-circle me-1"></i> No suspicious activity detected.</p>
                            @endif
                            
                            <hr>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>IP Address:</span>
                                <span class="font-monospace">{{ $order->aiFeature->ip_address }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- 2. ADMIN ACTIONS --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold">Admin Actions</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            <label class="form-label fw-bold">Update Order Status:</label>
                            
                            <select name="status" class="form-select mb-3">
                                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing (Approved)</option>
                                <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Completed (Shipped)</option>
                                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled (Reject)</option>
                            </select>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection