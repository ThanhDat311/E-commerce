@extends('layouts.master')

{{-- 1. CHUẨN HÓA: Dùng cú pháp Object ($product->name) --}}
@section('title', $product->name . ' - Electro')

@section('content')
{{-- BREADCRUMB --}}
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Product Detail</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
        <li class="breadcrumb-item active text-white">{{ $product->name }}</li>
    </ol>
</div>

{{-- MAIN PRODUCT DETAIL --}}
<div class="container-fluid shop py-5">
    <div class="container py-5">
        <div class="row g-4">
            {{-- SIDEBAR --}}
            <div class="col-lg-5 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                @include('partials.shop-sidebar')
            </div>

            <div class="col-lg-7 col-xl-9 wow fadeInUp" data-wow-delay="0.1s">
                <div class="row g-4 single-product">
                    {{-- Cột TRÁI: Ảnh sản phẩm --}}
                    <div class="col-xl-6">
                        <div class="single-carousel owl-carousel">
                            <div class="single-item" data-dot="<img class='img-fluid' src='{{ asset($product->image_url ?? 'img/default.png') }}' alt='Thumb'>">
                                <div class="single-inner bg-light rounded">
                                    <img src="{{ asset($product->image_url ?? 'img/default.png') }}"
                                        class="img-fluid w-100 rounded"
                                        style="height: 450px; object-fit: contain;"
                                        alt="{{ $product->name }}">
                                </div>
                            </div>
                            {{-- (Optional) Ảnh demo thêm nếu muốn slider chạy --}}
                            <div class="single-item" data-dot="<img class='img-fluid' src='{{ asset('img/product-1.png') }}' alt='Thumb'>">
                                <div class="single-inner bg-light rounded">
                                    <img src="{{ asset('img/product-1.png') }}" class="img-fluid w-100 rounded" style="height: 450px; object-fit: contain;" alt="Demo">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cột PHẢI: Thông tin chi tiết --}}
                    <div class="col-xl-6">
                        <h4 class="fw-bold mb-3">{{ $product->name }}</h4>
                        <p class="mb-3">Category: <span class="text-primary">{{ $product->category->name ?? 'Uncategorized' }}</span></p>
                        <h5 class="fw-bold mb-3 text-primary">${{ number_format($product->price) }}</h5>

                        {{-- Rating Stars (Dynamic) --}}
                        <div class="d-flex mb-4">
                            @php $rating = $product->averageRating() ?? 5; @endphp
                            @for($i = 0; $i < 5; $i++)
                                <i class="fa fa-star {{ $i < $rating ? 'text-secondary' : '' }}"></i>
                                @endfor
                                <span class="ms-2 text-muted">({{ $product->reviews->count() }} reviews)</span>
                        </div>

                        <div class="d-flex flex-column mb-3">
                            <small>SKU: <span class="text-muted">{{ $product->sku ?? 'N/A' }}</span></small>
                            <small>Stock: <strong class="text-success">In Stock ({{ $product->stock_quantity ?? 'Unchecked' }})</strong></small>
                        </div>

                        <p class="mb-4 text-muted">
                            {{ Str::limit($product->description, 150) }}
                        </p>

                        {{-- FORM ADD TO CART VỚI SỐ LƯỢNG --}}
                        <div class="d-flex align-items-center mb-4">
                            <div class="input-group quantity me-3" style="width: 130px;">
                                {{-- NÚT TRỪ: Giữ class 'btn-minus' để main.js làm việc --}}
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>

                                {{-- INPUT: Đặt ID để lát nữa nút Add Cart lấy giá trị --}}
                                <input type="text"
                                    id="product-quantity"
                                    class="form-control form-control-sm text-center border-0"
                                    value="1">

                                {{-- NÚT CỘNG: Giữ class 'btn-plus' để main.js làm việc --}}
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- NÚT ADD TO CART --}}
                            <a href="javascript:void(0);" onclick="addToCartWithQty({{ $product->id }})"
                                class="btn btn-primary border border-secondary rounded-pill px-4 py-2 text-white">
                                <i class="fa fa-shopping-bag me-2"></i> Add to cart
                            </a>
                        </div>
                    </div>

                    {{-- TABS: Description & Reviews --}}
                    <div class="col-lg-12">
                        <nav>
                            <div class="nav nav-tabs mb-3">
                                <button class="nav-link active border-white border-bottom-0" type="button" role="tab"
                                    id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                    aria-controls="nav-about" aria-selected="true">Description</button>
                                <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                    id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission"
                                    aria-controls="nav-mission" aria-selected="false">Reviews ({{ $product->reviews->count() }})</button>
                            </div>
                        </nav>
                        <div class="tab-content mb-5">
                            {{-- Tab Description --}}
                            <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                <p>{{ $product->description ?? 'No description available.' }}</p>
                                <table class="table table-bordered w-50 mt-3">
                                    <tr>
                                        <th>Model Name</th>
                                        <td>{{ $product->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Price</th>
                                        <td>${{ number_format($product->price) }}</td>
                                    </tr>
                                </table>
                            </div>

                            {{-- Tab Reviews (Dynamic) --}}
                            <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                                @forelse($product->reviews as $review)
                                <div class="d-flex mb-4 border-bottom pb-3">
                                    <img src="{{ asset('img/avatar.jpg') }}" class="img-fluid rounded-circle p-1 border me-3" style="width: 60px; height: 60px;" alt="">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-1">{{ $review->user->name ?? 'Anonymous' }}</h6>
                                            <small class="text-muted">{{ $review->created_at->format('d M, Y') }}</small>
                                        </div>
                                        <div class="mb-2">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="fa fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }} small"></i>
                                                @endfor
                                        </div>
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted text-center py-4">Chưa có đánh giá nào.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- RELATED PRODUCTS (Đã tối ưu Layout Flexbox) --}}
<div class="container-fluid related-product">
    <div class="container">
        <div class="mx-auto text-center pb-5" style="max-width: 700px;">
            <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius wow fadeInUp" data-wow-delay="0.1s">
                You Might Also Like
            </h4>
        </div>
        <div class="related-carousel owl-carousel pt-4">
            @foreach($relatedProducts as $related)
            {{-- Dùng d-flex flex-column h-100 để đảm bảo chiều cao bằng nhau --}}
            <div class="related-item rounded border position-relative h-100 d-flex flex-column">
                <div class="related-item-inner overflow-hidden flex-grow-1">
                    <div class="position-relative overflow-hidden rounded-top">
                        <img src="{{ asset($related->image_url ?? 'img/default.png') }}"
                            class="img-fluid w-100"
                            style="height: 230px; object-fit: cover;"
                            alt="{{ $related->name }}">

                        @if($related->is_new)
                        <div class="bg-secondary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">New</div>
                        @endif

                        <div class="position-absolute end-0 top-0 m-4">
                            <a href="{{ route('product.detail', $related->id) }}" class="btn btn-primary rounded-circle p-2 d-flex align-items-center justify-content-center shadow" style="width: 35px; height: 35px;">
                                <i class="fa fa-eye text-white"></i>
                            </a>
                        </div>
                    </div>

                    <div class="text-center p-4">
                        <a href="#" class="d-block mb-2 text-muted small text-uppercase">{{ $related->category->name ?? 'Product' }}</a>
                        {{-- text-truncate để cắt tên nếu quá dài --}}
                        <a href="{{ route('product.detail', $related->id) }}" class="d-block h5 mb-2 text-dark fw-bold text-decoration-none text-truncate" title="{{ $related->name }}">
                            {{ $related->name }}
                        </a>
                        <span class="text-primary fs-5 fw-bold">${{ number_format($related->price) }}</span>
                    </div>
                </div>

                {{-- Nút Add Cart luôn nằm đáy --}}
                <div class="text-center pb-4 mt-auto">
                    <a href="{{ route('cart.add', $related->id) }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4 text-white">
                        <i class="fas fa-shopping-cart me-2"></i> Add To Cart
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    // Hàm gửi dữ liệu đi (Logic backend)
    function addToCartWithQty(productId) {
        let qty = document.getElementById('product-quantity').value;

        // Tạo URL có kèm tham số quantity
        let url = "{{ route('cart.add', ':id') }}";
        url = url.replace(':id', productId) + "?quantity=" + qty;

        // Chuyển trang
        window.location.href = url;
    }
</script>
@endsection