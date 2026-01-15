@extends('layouts.master')

@section('title', $product['name'] . ' - Electro')

@section('content')
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Product Detail</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
        <li class="breadcrumb-item active text-white">{{ $product['name'] }}</li>
    </ol>
</div>
<div class="container-fluid shop py-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-5 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                @include('partials.shop-sidebar')
            </div>

            <div class="col-lg-7 col-xl-9 wow fadeInUp" data-wow-delay="0.1s">
                <div class="row g-4 single-product">
                    <div class="col-xl-6">
                        <div class="single-carousel owl-carousel">
                            {{-- Kiểm tra nếu sản phẩm có danh sách ảnh (gallery) --}}
                            @if(isset($product['images']) && count($product['images']) > 0)

                            @foreach($product['images'] as $img)
                            {{-- data-dot: Tạo thumbnail nhỏ bên dưới slider --}}
                            <div class="single-item" data-dot="<img class='img-fluid' src='{{ asset($img) }}' alt=''>">
                                <div class="single-inner bg-light rounded">
                                    {{-- Hiển thị ảnh lớn --}}
                                    <img src="{{ asset($img) }}" class="img-fluid w-100 rounded" style="height: 450px; object-fit: contain;" alt="Image">
                                </div>
                            </div>
                            @endforeach

                            @else
                            {{-- Trường hợp dự phòng: Nếu chỉ có 1 ảnh chính (không có gallery) --}}
                            <div class="single-item" data-dot="<img class='img-fluid' src='{{ asset($product['image']) }}' alt=''>">
                                <div class="single-inner bg-light rounded">
                                    <img src="{{ asset($product['image']) }}" class="img-fluid w-100 rounded" style="height: 450px; object-fit: contain;" alt="{{ $product['name'] }}">
                                </div>
                            </div>

                            {{-- (Tùy chọn) Thêm ảnh demo để slider không bị trống nếu muốn test --}}
                            <div class="single-item" data-dot="<img class='img-fluid' src='{{ asset('img/product-2.png') }}' alt=''>">
                                <div class="single-inner bg-light rounded">
                                    <img src="{{ asset('img/product-2.png') }}" class="img-fluid w-100 rounded" style="height: 450px; object-fit: contain;" alt="Demo">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <h4 class="fw-bold mb-3">{{ $product['name'] }}</h4>
                        <p class="mb-3">Category: {{ $product['category'] ?? 'Electronics' }}</p>
                        <h5 class="fw-bold mb-3">${{ number_format($product['price']) }}</h5>

                        <div class="d-flex mb-4">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < ($product['rating'] ?? 5))
                                <i class="fa fa-star text-secondary"></i>
                                @else
                                <i class="fa fa-star"></i>
                                @endif
                                @endfor
                        </div>

                        <div class="d-flex flex-column mb-3">
                            <small>Product SKU: <span class="text-muted">SKU-{{ $product['id'] }}</span></small>
                            <small>Available: <strong class="text-primary">In Stock</strong></small>
                        </div>

                        <p class="mb-4">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        </p>

                        <div class="input-group quantity mb-5" style="width: 100px;">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center border-0" value="1">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <a href="{{ route('cart.add', ['id' => $product['id']]) }}" class="btn btn-primary border border-secondary rounded-pill px-4 py-2 mb-4 text-primary">
                            <i class="fa fa-shopping-bag me-2 text-white"></i> Add to cart
                        </a>
                    </div>

                    <div class="col-lg-12">
                        <nav>
                            <div class="nav nav-tabs mb-3">
                                <button class="nav-link active border-white border-bottom-0" type="button" role="tab"
                                    id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                    aria-controls="nav-about" aria-selected="true">Description</button>
                                <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                    id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission"
                                    aria-controls="nav-mission" aria-selected="false">Reviews</button>
                            </div>
                        </nav>
                        <div class="tab-content mb-5">
                            <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                    <p>
                                        Đây là trang chi tiết cho sản phẩm:
                                        {{-- Cách 1: An toàn nhất cho Array --}}
                                        <strong class="text-primary">{{ $product['name'] ?? 'Tên sản phẩm' }}</strong>
                                    </p>

                                    <p>
                                        Mô tả chi tiết: Chiếc {{ $product['name'] ?? 'sản phẩm' }} này là dòng sản phẩm mới nhất thuộc danh mục {{ $product['category'] ?? 'Electronics' }}...
                                    </p>

                                    {{-- Bảng thông số kỹ thuật giả lập --}}
                                    <table class="table table-bordered w-50 mt-3">
                                        <tr>
                                            <th>Tên Model</th>
                                            <td>{{ $product['name'] ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Giá bán</th>
                                            <td>${{ number_format($product['price'] ?? 0) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                                <div class="d-flex">
                                    <img src="{{ asset('img/avatar.jpg') }}" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                    <div class="">
                                        <p class="mb-2" style="font-size: 14px;">April 12, 2024</p>
                                        <div class="d-flex justify-content-between">
                                            <h5>Jason Smith</h5>
                                            <div class="d-flex mb-3">
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                        </div>
                                        <p>Great product! Highly recommended.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid related-product">
    <div class="container">
        <div class="mx-auto text-center pb-5" style="max-width: 700px;">
            <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius wow fadeInUp" data-wow-delay="0.1s">
                Related Products
            </h4>
        </div>
        <div class="related-carousel owl-carousel pt-4">
            {{-- Lặp qua danh sách sản phẩm liên quan --}}
            @foreach($relatedProducts as $related)
            <div class="related-item rounded">
                <div class="related-item-inner border rounded">
                    <div class="related-item-inner-item">
                        {{-- THÊM STYLE TẠI ĐÂY --}}
                        <img src="{{ asset($related['image']) }}"
                            class="img-fluid w-100 rounded-top"
                            style="height: 230px; object-fit: cover;"
                            alt="">

                        @if($related['is_new'] ?? false)
                        <div class="related-new">New</div>
                        @endif
                        <div class="related-details">
                            <a href="{{ route('product.detail', ['id' => $related['id']]) }}"><i class="fa fa-eye fa-1x"></i></a>
                        </div>
                    </div>
                    <div class="text-center rounded-bottom p-4">
                        <a href="#" class="d-block mb-2">{{ $related['category'] ?? 'Category' }}</a>
                        <a href="{{ route('product.detail', ['id' => $related['id']]) }}" class="d-block h4">{{ $related['name'] }}</a>
                        <div class="d-flex justify-content-center">
                            <span class="text-primary fs-5">${{ number_format($related['price']) }}</span>
                        </div>
                    </div>
                </div>
                <div class="related-item-add border border-top-0 rounded-bottom text-center p-4 pt-0">
                    <a href="#" class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-4">
                        <i class="fas fa-shopping-cart me-2"></i> Add To Cart
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection