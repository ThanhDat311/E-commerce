<div class="product-item rounded h-100 d-flex flex-column">

    {{-- Khối chứa nội dung chính --}}
    <div class="product-item-inner border rounded flex-grow-1 d-flex flex-column">
        <div class="product-item-inner-item position-relative">

            {{-- ẢNH SẢN PHẨM --}}
            <div class="overflow-hidden rounded-top">
                <a href="{{ route('product.detail', ['id' => $product['id']]) }}">
                    {{-- THAY ĐỔI Ở ĐÂY: Thêm style height và object-fit --}}
                    <img src="{{ asset($product['image']) }}"
                        class="img-fluid w-100 rounded-top"
                        style="height: 230px; object-fit: cover;"
                        alt="{{ $product['name'] }}">
                </a>
            </div>

            {{-- Nhãn New --}}
            @if(isset($product['is_new']) && $product['is_new'])
            <div class="bg-secondary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">New</div>
            @endif

            {{-- Nút xem nhanh --}}
            <div class="product-details position-absolute end-0 top-0 m-4">
                <a href="{{ route('product.detail', ['id' => $product['id']]) }}" class="btn btn-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                    <i class="fa fa-eye text-white"></i>
                </a>
            </div>
        </div>

        {{-- Thông tin sản phẩm --}}
        <div class="text-center p-4">
            <a href="#" class="d-block mb-2 text-muted small text-uppercase">{{ $product['category'] ?? 'Electronics' }}</a>
            <a href="{{ route('product.detail', ['id' => $product['id']]) }}" class="d-block h5 mb-2 text-dark fw-bold text-decoration-none">
                {{ $product['name'] }}
            </a>

            <div class="d-flex justify-content-center align-items-center">
                <span class="text-primary fs-5 fw-bold">${{ number_format($product['price']) }}</span>
                @if(!empty($product['old_price']))
                <span class="text-decoration-line-through text-muted ms-2 small">${{ number_format($product['old_price']) }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Phần nút bấm (Add to cart) --}}
    <div class="product-item-add border border-top-0 rounded-bottom text-center p-4 bg-white mt-auto">
        {{-- THAY ĐỔI Ở ĐÂY: Thêm route('cart.add', ...) --}}
        <a href="{{ route('cart.add', ['id' => $product['id']]) }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-4 text-white">
            <i class="fas fa-shopping-cart me-2"></i> Add To Cart
        </a>

        <div class="d-flex justify-content-between align-items-center">
            {{-- Các phần đánh giá sao & icon giữ nguyên... --}}
            <div class="d-flex text-secondary small">
                @for($i = 0; $i < 5; $i++)
                    @if($i < ($product['rating'] ?? 5))
                    <i class="fas fa-star text-primary"></i>
                    @else
                    <i class="fas fa-star"></i>
                    @endif
                    @endfor
            </div>

            <div class="d-flex">
                <a href="#" class="btn btn-outline-primary rounded-circle p-0 me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fas fa-random small"></i>
                </a>
                <a href="#" class="btn btn-outline-primary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                    <i class="fas fa-heart small"></i>
                </a>
            </div>
        </div>
    </div>
</div>