@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h3>My Orders</h3>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>${{ number_format($order->total_price ?? $order->total, 2) }}</td>
                <td>
                    <span class="badge bg-secondary">{{ ucfirst($order->order_status ?? $order->status) }}</span>
                </td>
                <td>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">You have no orders yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $orders->links() }}
</div>
@endsection