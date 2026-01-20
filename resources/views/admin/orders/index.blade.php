@extends('layouts.admin')
@section('title', 'Order Management')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Order List</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>
                        {{ $order->first_name }} {{ $order->last_name }}<br>
                        <small class="text-muted">{{ $order->phone }}</small>
                    </td>
                    <td class="fw-bold">${{ number_format($order->total_price, 2) }}</td>
                    <td>
                        @if($order->status == 'completed') <span class="badge bg-success">Completed</span>
                        @elseif($order->status == 'pending') <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($order->status == 'cancelled') <span class="badge bg-danger">Cancelled</span>
                        @else <span class="badge bg-info">{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                            View Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection