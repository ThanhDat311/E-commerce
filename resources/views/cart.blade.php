@extends('layouts.master')

@section('title', 'Giỏ hàng - Electro')

@section('content')
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Cart Page</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item active text-white"><a href="{{ route('cart.index') }}">Cart</a></li>
        <li class="breadcrumb-item active text-white">Cart Page</li>
    </ol>
</div>
<div class="container-fluid py-5">
    <div class="container py-5">

        <div class="row g-5">

            {{-- CỘT TRÁI: DANH SÁCH SẢN PHẨM + COUPON (Chiếm 8/12 phần) --}}
            <div class="{{ count($cartItems) > 0 ? 'col-lg-8' : 'col-12' }}">

                {{-- 1. Bảng danh sách sản phẩm --}}
                <div class="table-responsive mb-5">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Products</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cartItems as $id => $item)
                            <tr>
                                <th scope="row">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($item['image'] ?? 'img/no-image.png') }}" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="{{ $item['name'] ?? 'Product' }}">
                                    </div>
                                </th>
                                <td>
                                    <p class="mb-0 mt-4">{{ $item['name'] }}</p>
                                    <p class="mb-0 text-muted small">{{ $item['model'] ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">${{ number_format($item['price'], 2) }}</p>
                                </td>
                                <td>
                                    <div class="input-group quantity mt-4" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm text-center border-0" value="{{ $item['quantity'] }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                </td>
                                <td>
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <p class="mb-4">Giỏ hàng của bạn đang trống.</p>
                                    <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill px-4 py-2">Tiếp tục mua sắm</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- 2. COUPON CODE (Đã chuyển xuống đây) --}}
                @if(count($cartItems) > 0)
                <div class="row g-4 justify-content-start">
                    <div class="col-md-6">
                        <form action="">
                            <div class="input-group">
                                <input type="text" class="form-control p-3 border-secondary rounded-start" placeholder="Coupon Code">
                                <button class="btn btn-primary text-white rounded-end">Apply Coupon</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

            </div>

            {{-- CỘT PHẢI: THANH TOÁN --}}
            @if(count($cartItems) > 0)
            <div class="col-lg-4">
                <div class="bg-light rounded p-4 sticky-top" style="top: 100px; z-index: 1;">
                    <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>

                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="mb-0 me-4">Subtotal:</h5>
                        <p class="mb-0">${{ number_format($subTotal, 2) }}</p>
                    </div>

                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="mb-0 me-4">Shipping:</h5>
                        <div>
                            <p class="mb-0">Flat rate: ${{ number_format($shipping, 2) }}</p>
                        </div>
                    </div>

                    <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                        <h5 class="mb-0 ps-4 me-4">Total</h5>
                        <p class="mb-0 pe-4 display-6 fw-bold text-primary">${{ number_format($total, 2) }}</p>
                    </div>

                    {{-- THAY ĐỔI TẠI ĐÂY: Chuyển button thành thẻ a và thêm href --}}
                    <a href="{{ route('cart.checkout') }}" class="btn btn-primary rounded-pill px-4 py-3 text-uppercase w-100 text-white fw-bold">
                        Proceed Checkout
                    </a>

                </div>
            </div>
            @endif

        </div> {{-- End Row --}}
    </div>
</div>
@endsection