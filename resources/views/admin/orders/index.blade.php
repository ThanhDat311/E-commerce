@extends('layouts.master')

@section('title', 'Admin - Order Management')

@section('content')
<div class="container-fluid py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary fw-bold">Order Management</h2>
            <span class="badge bg-primary fs-6">AI Powered</span>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-uppercase text-muted small">
                            <tr>
                                <th class="py-3 px-4">Order ID</th>
                                <th class="py-3">Customer</th>
                                <th class="py-3">Total</th>
                                <th class="py-3">Date</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-center" style="width: 250px;">AI Risk Score</th>
                                <th class="py-3 text-end px-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td class="px-4 fw-bold">#{{ $order->id }}</td>

                                {{-- Thông tin khách hàng --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $order->first_name }} {{ $order->last_name }}</span>
                                        <small class="text-muted">{{ $order->email }}</small>
                                    </div>
                                </td>

                                <td class="fw-bold text-primary">${{ number_format($order->total, 2) }}</td>

                                <td class="text-muted small">{{ $order->created_at->format('d M Y, H:i') }}</td>

                                {{-- Trạng thái đơn hàng --}}
                                <td>
                                    @if($order->order_status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($order->order_status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                    @elseif($order->order_status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                    @else
                                    <span class="badge bg-secondary">{{ $order->order_status }}</span>
                                    @endif
                                </td>

                                {{-- CỘT QUAN TRỌNG NHẤT: AI RISK SCORE --}}
                                <td class="text-center">
                                    @if($order->aiFeature)
                                    @php
                                    $score = $order->aiFeature->risk_score;
                                    // Logic màu sắc
                                    if ($score < 0.3) {
                                        $color='success' ; // Xanh (An toàn)
                                        $label='Safe' ;
                                        } elseif ($score < 0.7) {
                                        $color='warning' ; // Vàng (Cảnh báo)
                                        $label='Suspicious' ;
                                        } else {
                                        $color='danger' ; // Đỏ (Nguy hiểm)
                                        $label='High Risk' ;
                                        }
                                        @endphp

                                        <div class="d-flex flex-column align-items-center">
                                        {{-- Badge Điểm số --}}
                                        <span class="badge bg-{{ $color }} mb-1 w-100 py-2">
                                            <i class="fas fa-shield-alt me-1"></i> {{ $label }} ({{ $score * 100 }}%)
                                        </span>

                                        {{-- Tooltip lý do (Hiển thị text nhỏ) --}}
                                        @if($order->aiFeature->reasons)
                                        <div class="small text-muted text-start w-100 mt-1 fst-italic" style="font-size: 0.75rem; line-height: 1.2;">
                                            @foreach($order->aiFeature->reasons as $reason)
                                            <div class="text-{{ $color }}">• {{ $reason }}</div>
                                            @endforeach
                                        </div>
                                        @endif
                </div>
                @else
                <span class="text-muted fst-italic small">No AI Data</span>
                @endif
                </td>

                <td class="text-end px-4">
                    {{-- Sửa button thành thẻ a --}}
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        Details
                    </a>
                </td>
                </tr>
                @endforeach
                </tbody>
                </table>
            </div>
        </div>

        {{-- Phân trang --}}
        <div class="card-footer bg-white py-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
</div>
@endsection