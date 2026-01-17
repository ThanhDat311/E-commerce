@extends('layouts.master')

@section('title', 'My Order History')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 fw-bold text-primary">My Order History</h2>

    <div class="row">
        {{-- Menu bên trái --}}
        <div class="col-lg-3 mb-4">
            <div class="list-group shadow-sm">
                <a href="#" class="list-group-item list-group-item-action active">
                    <i class="fas fa-history me-2"></i> Orders
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <i class="fas fa-user me-2"></i> Profile Info
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="list-group-item list-group-item-action text-danger w-100 text-start">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Nội dung bên phải --}}
        <div class="col-lg-9">
            @if($orders->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3 ps-4">Order ID</th>
                                        <th class="py-3">Date</th>
                                        <th class="py-3">Total</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3 text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary">#{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                                        <td>
                                            @if($order->order_status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($order->order_status == 'completed')
                                                <span class="badge bg-success">Shipped</span>
                                            @elseif($order->order_status == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $order->order_status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="lead text-muted">You haven't placed any orders yet.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill px-4">Shop Now</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection