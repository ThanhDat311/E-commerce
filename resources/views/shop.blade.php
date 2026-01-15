@extends('layouts.master')

@section('title', 'Cửa hàng - Electro')

@section('content')
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Shop Page</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active text-white">Shop</li>
    </ol>
</div>

@include('partials.services')
{{-- @include('partials.offers') --}} {{-- Tùy chọn: Có thể ẩn bớt offer ở trang shop cho gọn --}}

<div class="container-fluid shop py-5">
    <div class="container py-5">
        <div class="row g-4">

            <div class="col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                @include('partials.shop-sidebar')
            </div>

            <div class="col-lg-9 wow fadeInUp" data-wow-delay="0.1s">

                {{-- Banner quảng cáo (Giữ nguyên tĩnh hoặc làm động sau) --}}
                <div class="rounded mb-4 position-relative">
                    <img src="{{ asset('img/product-banner-3.jpg') }}" class="img-fluid rounded w-100" style="height: 250px; object-fit: cover;" alt="Image">
                    <div class="position-absolute rounded d-flex flex-column align-items-center justify-content-center text-center" style="width: 100%; height: 250px; top: 0; left: 0; background: rgba(242, 139, 0, 0.3);">
                        <h4 class="display-5 text-primary">SALE</h4>
                        <h3 class="display-4 text-white mb-4">Get UP To 50% Off</h3>
                        <a href="#" class="btn btn-primary rounded-pill">Shop Now</a>
                    </div>
                </div>

                {{-- THANH CÔNG CỤ: TÌM KIẾM & SẮP XẾP --}}
                <div class="row g-4 mb-4">
                    <div class="col-xl-7">
                        {{-- FORM TÌM KIẾM --}}
                        <form action="{{ route('shop') }}" method="GET">
                            <div class="input-group w-100 mx-auto d-flex">
                                {{-- Input tìm kiếm: Giữ lại từ khóa cũ bằng value="{{ request('keyword') }}" --}}
                                <input type="search" name="keyword" class="form-control p-3"
                                    placeholder="Tìm kiếm..."
                                    value="{{ request('keyword') }}"
                                    aria-describedby="search-icon-1">

                                {{-- MẸO HAY: Giữ lại lựa chọn Sắp xếp nếu đang có --}}
                                @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif

                                <button type="submit" id="search-icon-1" class="input-group-text p-3 border-0">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-xl-5 text-end">
                        <form action="{{ route('shop') }}" method="GET" class="bg-light ps-3 py-3 rounded d-flex justify-content-between">

                            {{-- MẸO HAY: Giữ lại từ khóa Tìm kiếm nếu đang có --}}
                            @if(request('keyword'))
                            <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                            @endif

                            <label for="sort">Sort By:</label>
                            {{-- onchange="this.form.submit()": Tự động gửi form khi chọn xong --}}
                            <select id="sort" name="sort" class="border-0 form-select-sm bg-light me-3" onchange="this.form.submit()">
                                {{-- Kiểm tra xem option nào đang được chọn để thêm thuộc tính selected --}}
                                <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Mặc định</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                            </select>
                        </form>
                    </div>
                </div>

                {{-- DANH SÁCH SẢN PHẨM --}}
                <div class="product">
                    <div class="row g-4">
                        @forelse($products as $product)
                        <div class="col-lg-4 col-md-6">
                            @include('partials.product-item', ['product' => $product])
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <div class="alert alert-warning">
                                <i class="fa fa-search me-2"></i> Không tìm thấy sản phẩm nào phù hợp với từ khóa "{{ request('keyword') }}".
                            </div>
                            <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill px-4">Xem tất cả sản phẩm</a>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- PHÂN TRANG (PAGINATION) CỦA LARAVEL --}}
                <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="d-flex justify-content-center mt-5">
                        {{-- Sử dụng phân trang mặc định của Bootstrap 5 --}}
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('partials.bottom-banner')

@endsection