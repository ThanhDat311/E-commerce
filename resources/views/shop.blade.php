@extends('layouts.master')

@section('title', 'Cửa hàng - Electro')

@section('content')

<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Cửa hàng</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item active text-white">Cửa hàng</li>
    </ol>
</div>

<div class="container-fluid shop py-5">
    <div class="container py-5">
        <div class="row g-4">

            <div class="col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                @include('partials.shop-sidebar')
            </div>

            <div class="col-lg-9 wow fadeInUp" data-wow-delay="0.1s">
                
                {{-- Banner Quảng cáo --}}
                <div class="rounded mb-5 position-relative">
                    <img src="{{ asset('img/product-banner-3.jpg') }}" class="img-fluid rounded w-100" style="height: 300px; object-fit: cover;" alt="Sale Banner">
                    <div class="position-absolute top-50 start-0 translate-middle-y p-4 ms-3 bg-white bg-opacity-75 rounded">
                        <h4 class="text-primary fw-bold mb-2">Siêu Sale Mùa Hè</h4>
                        <h2 class="display-6 fw-bold mb-3">Giảm đến 50%</h2>
                        <a href="#" class="btn btn-primary rounded-pill py-2 px-4">Mua ngay</a>
                    </div>
                </div>

                <div class="product">
                    <div class="row g-4">
                        @forelse($products as $product)
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            @include('partials.product-item', ['product' => $product])
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <div class="bg-light rounded p-5">
                                <h4>Không tìm thấy sản phẩm nào!</h4>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- GỌI FILE PHÂN TRANG TẠI ĐÂY --}}
                <div class="col-12 mt-5">
                    <div class="d-flex justify-content-center">
                        {{ $products->appends(request()->query())->links('partials.pagination') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('partials.bottom-banner')

@endsection