@extends('layouts.master')

@section('title', 'Đặt hàng thành công - Electro')

@section('content')
<div class="container-fluid py-5">
    <div class="container py-5 text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success display-1"></i>
                    </div>
                    <h1 class="display-6 mb-3 fw-bold">Thank You!</h1>
                    <p class="lead text-muted mb-4">Đơn hàng của bạn đã được tiếp nhận thành công.</p>
                    
                    <div class="alert alert-success bg-light border-success mb-4 text-start">
                        <p class="mb-1"><strong>Mã đơn hàng:</strong> #ORD-{{ rand(1000, 9999) }}</p>
                        <p class="mb-0"><strong>Email xác nhận:</strong> Chúng tôi đã gửi email chi tiết đơn hàng đến hộp thư của bạn.</p>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">
                            <i class="fas fa-home me-2"></i> Trang chủ
                        </a>
                        <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill px-4 py-2 text-white">
                            <i class="fas fa-shopping-bag me-2"></i> Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection