{{-- Bắt đầu Form Lọc --}}
<form action="{{ route('shop') }}" method="GET">
    
    {{-- 1. Categories (Giữ nguyên hoặc chuyển thành checkbox nếu muốn) --}}
    <div class="product-categories mb-4">
        <h4>Products Categories</h4>
        <ul class="list-unstyled">
            @if(isset($categories) && count($categories) > 0)
                @foreach($categories as $category)
                <li>
                    <div class="categories-item">
                        <a href="{{ route('shop', ['category' => $category['id'] ?? $category['name']]) }}" class="text-dark">
                            <i class="fas fa-apple-alt text-secondary me-2"></i> {{ $category['name'] }}
                        </a>
                        <span>({{ $category['count'] }})</span>
                    </div>
                </li>
                @endforeach
            @endif
        </ul>
    </div>

    {{-- 2. Price Filter (Giữ nguyên) --}}
    <div class="price mb-4">
        <h4 class="mb-2">Price</h4>
        <input type="range" class="form-range w-100" id="rangeInput" name="price" min="0" max="2000" 
               value="{{ request('price', 0) }}" oninput="amount.value=rangeInput.value">
        <div class="d-flex justify-content-between">
            <output id="amount" name="amount" for="rangeInput">{{ request('price', 0) }}</output>
            <span>$2000</span>
        </div>
    </div>

    {{-- 3. Select By Color (Đã chuyển thành CHECKBOX) --}}
    <div class="product-color mb-3">
        <h4>Select By Color</h4>
        <ul class="list-unstyled">
            @if(isset($colors) && count($colors) > 0)
                @foreach($colors as $index => $color)
                <li class="mb-2">
                    {{-- Dùng class d-flex để căn chỉnh thẳng hàng --}}
                    <div class="product-color-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            {{-- Checkbox Input --}}
                            {{-- name="colors[]": Để PHP nhận được mảng nhiều màu --}}
                            <input type="checkbox" 
                                   class="form-check-input me-2" 
                                   id="color-{{ $index }}" 
                                   name="colors[]" 
                                   value="{{ $color['name'] }}"
                                   {{-- Logic: Nếu màu này đang có trong URL thì tự động check --}}
                                   {{ in_array($color['name'], request('colors', [])) ? 'checked' : '' }}>
                            
                            {{-- Label (Bấm vào chữ cũng check được) --}}
                            <label for="color-{{ $index }}" class="text-dark mb-0" style="cursor: pointer;">
                                {{ $color['name'] }}
                            </label>
                        </div>
                        <span>({{ $color['count'] }})</span>
                    </div>
                </li>
                @endforeach
            @else
                <li><span class="text-muted">Không có màu sắc.</span></li>
            @endif
        </ul>
    </div>

    {{-- Nút Submit (Bắt buộc phải có nút này để gửi Form đi) --}}
    <div class="mb-4">
        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">
            <i class="fa fa-filter me-2"></i> Lọc sản phẩm
        </button>
    </div>

</form>
{{-- Kết thúc Form --}}


{{-- 4. Featured Products (Giữ nguyên - Nằm ngoài Form vì không cần submit) --}}
<div class="featured-product mb-4">
    <h4 class="mb-3">Featured products</h4>
    @if(isset($featuredProducts) && count($featuredProducts) > 0)
        @foreach($featuredProducts as $fProduct)
        <div class="featured-product-item d-flex align-items-center mb-3">
            <div class="rounded me-4" style="width: 100px; height: 100px;">
                <a href="{{ route('product.detail', ['id' => $fProduct['id']]) }}">
                    <img src="{{ asset($fProduct['image']) }}" class="img-fluid rounded w-100 h-100" style="object-fit: cover;" alt="">
                </a>
            </div>
            <div>
                <a href="{{ route('product.detail', ['id' => $fProduct['id']]) }}" class="d-block h6 mb-2 text-dark text-decoration-none">
                    {{ $fProduct['name'] }}
                </a>
                <div class="d-flex mb-2 small text-secondary">
                    @for($i = 0; $i < 5; $i++)
                        <i class="fas fa-star {{ $i < ($fProduct['rating'] ?? 5) ? 'text-primary' : '' }}"></i>
                    @endfor
                </div>
                <div class="d-flex mb-2">
                    <h5 class="fw-bold me-2">${{ number_format($fProduct['price']) }}</h5>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>