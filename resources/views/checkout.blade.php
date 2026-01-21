@extends('layouts.master')

@section('title', 'Thanh toán - Electro')

@section('content')
<div class="container-fluid page-header py-5 mb-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Thanh toán</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-white">Giỏ hàng</a></li>
        <li class="breadcrumb-item active text-white">Thanh toán</li>
    </ol>
</div>

<div class="container-fluid py-5 bg-light">
    <div class="container">
        <form action="{{ route('checkout.process') }}" method="POST"> 
            @csrf
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h4 class="mb-0 fw-bold"><i class="fas fa-user-circle me-2 text-primary"></i> Thông tin giao hàng</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label required fw-bold">Họ & Tên đệm</label>
                                    <input type="text" class="form-control form-control-lg" name="first_name" value="{{ old('first_name', Auth::user()->first_name ?? '') }}" placeholder="Nhập họ..." required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required fw-bold">Tên</label>
                                    <input type="text" class="form-control form-control-lg" name="last_name" value="{{ old('last_name', Auth::user()->last_name ?? '') }}" placeholder="Nhập tên..." required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label required fw-bold">Email</label>
                                    <input type="email" class="form-control form-control-lg" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" placeholder="example@email.com" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label required fw-bold">Số điện thoại</label>
                                    <input type="tel" class="form-control form-control-lg" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" placeholder="0901234567" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label required fw-bold">Địa chỉ nhận hàng</label>
                                    <input type="text" class="form-control form-control-lg" name="address" value="{{ old('address', Auth::user()->address ?? '') }}" placeholder="Số nhà, đường, phường/xã..." required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Ghi chú đơn hàng (Tùy chọn)</label>
                                    <textarea class="form-control form-control-lg" name="note" rows="3" placeholder="Ví dụ: Giao hàng giờ hành chính...">{{ old('note') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h4 class="mb-0 fw-bold"><i class="fas fa-shopping-cart me-2 text-primary"></i> Đơn hàng của bạn</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive mb-4">
                                <table class="table">
                                    <tbody>
                                        @foreach($cartItems ?? [] as $item)
                                        <tr>
                                            <td>{{ $item['name'] }} <span class="text-muted">x {{ $item['quantity'] }}</span></td>
                                            <td class="text-end">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="fw-bold border-top">
                                            <td>Tổng cộng</td>
                                            <td class="text-end text-primary fs-5">${{ number_format($total ?? 0, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <h4 class="mb-0 fw-bold"><i class="fas fa-credit-card me-2 text-primary"></i> Phương thức thanh toán</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="list-group">
                                
                                <label class="list-group-item d-flex align-items-center cursor-pointer p-3 mb-2 border rounded">
                                    <input class="form-check-input me-3 mt-0" type="radio" name="payment_method" value="cod" id="payment_cod" checked>
                                    <div class="flex-grow-1">
                                        <span class="fw-bold d-block text-dark">Thanh toán khi nhận hàng (COD)</span>
                                        <small class="text-muted">Thanh toán bằng tiền mặt khi shipper giao hàng đến.</small>
                                    </div>
                                    <i class="fas fa-truck text-secondary fs-4 ms-2"></i>
                                </label>

                                <label class="list-group-item d-flex align-items-center cursor-pointer p-3 border rounded bg-light-hover">
                                    <input class="form-check-input me-3 mt-0" type="radio" name="payment_method" value="vnpay" id="payment_vnpay">
                                    <div class="flex-grow-1">
                                        <span class="fw-bold d-block text-primary">Thanh toán Online qua VNPAY</span>
                                        <small class="text-muted">Thẻ ATM, Visa, MasterCard, hoặc quét mã QR.</small>
                                    </div>
                                    <img src="https://vnpay.vn/s1/statics.vnpay.vn/2023/6/0oxhzjmxbksr1686814746087.png" alt="VNPay" style="height: 30px;" class="ms-2">
                                </label>

                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold rounded-pill shadow-sm hover-scale">
                                    <i class="fas fa-check-circle me-2"></i> ĐẶT HÀNG NGAY
                                </button>
                                <p class="text-muted text-center small mt-3 mb-0">
                                    <i class="fas fa-shield-alt me-1"></i> Thông tin của bạn được bảo mật an toàn.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* CSS Tùy chỉnh nhỏ để giao diện đẹp hơn */
    .cursor-pointer { cursor: pointer; }
    .bg-light-hover:hover { background-color: #f8f9fa; }
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.02); }
    .form-check-input:checked { background-color: #0d6efd; border-color: #0d6efd; }
</style>
@endsection