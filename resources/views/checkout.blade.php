@extends('layouts.master')

@section('title', 'Thanh toán - Electro')

@section('content')
<div class="container-fluid page-header py-5 mb-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Checkout</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-white">Cart</a></li>
        <li class="breadcrumb-item active text-white">Checkout</li>
    </ol>
</div>

<div class="container-fluid py-5 bg-light">
    <div class="container">
        <form action="{{ route('cart.placeOrder') }}" method="POST">
            @csrf
            <div class="row g-5">
                <div class="col-lg-7 or-xl-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h4 class="mb-0 fw-bold"><i class="fas fa-shipping-fast me-2 text-primary"></i> Billing Details</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" name="first_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" name="last_name" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" name="address" placeholder="House number and street name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Town / City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" name="city" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Postcode / ZIP (Optional)</label>
                                    <input type="text" class="form-control form-control-lg" name="postcode">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control form-control-lg" name="phone" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control form-control-lg" name="email" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Order Notes (Optional)</label>
                                    <textarea class="form-control form-control-lg" name="note" rows="4" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-xl-4">
                    <div class="checkout-summary-sticky">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="mb-0 fw-bold">Order Summary</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    @foreach($cartItems as $item)
                                    <li class="list-group-item d-flex align-items-center px-4 py-3">
                                        <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="checkout-product-img border me-3 shadow-sm rounded">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h6 class="mb-1 text-truncate">{{ $item['name'] }}</h6>
                                            <small class="text-muted">Qty: {{ $item['quantity'] }}</small>
                                        </div>
                                        <div class="text-end ms-2 fw-semibold">
                                            ${{ number_format($item['price'] * $item['quantity']) }}
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-footer bg-white p-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-semibold">${{ number_format($subTotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                    <span class="text-muted">Shipping</span>
                                    <span class="fw-semibold">${{ number_format($shipping, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fw-bold">Total</h5>
                                    <h3 class="mb-0 fw-bold text-primary">${{ number_format($total, 2) }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="mb-0 fw-bold">Payment Method</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="list-group payment-option-group">
                                    <input type="radio" class="btn-check" name="payment_method" id="payment_cod" value="cod" checked>
                                    <label class="list-group-item d-flex align-items-center" for="payment_cod">
                                        <i class="fas fa-hand-holding-usd me-3 payment-icon"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">Cash On Delivery (COD)</div>
                                            <small class="text-muted">Pay with cash upon delivery.</small>
                                        </div>
                                        <i class="fas fa-check-circle text-primary fs-5 check-mark d-none d-md-block"></i>
                                    </label>

                                    <input type="radio" class="btn-check" name="payment_method" id="payment_bank" value="bank">
                                    <label class="list-group-item d-flex align-items-center" for="payment_bank">
                                        <i class="fas fa-university me-3 payment-icon"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">Direct Bank Transfer</div>
                                            <small class="text-muted">Make your payment directly into our bank account.</small>
                                        </div>
                                        <i class="fas fa-check-circle text-primary fs-5 check-mark d-none d-md-block"></i>
                                    </label>

                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold rounded-pill shadow-sm">
                                        <i class="fas fa-lock me-2"></i> PLACE ORDER NOW
                                    </button>
                                    <p class="text-mutedtext-center small mt-3 mb-0">
                                        <i class="fas fa-shield-alt me-1"></i> Secure checkout process.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection