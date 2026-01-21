<div class="shop-sidebar">
    <div class="mb-4 pb-4 border-bottom">
        <h4 class="mb-3">Bộ lọc & Tìm kiếm</h4>
        
        <form action="{{ route('shop.index') }}" method="GET">
            {{-- Giữ lại category_id nếu đang chọn (để không bị mất khi lọc giá) --}}
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <div class="mb-3">
                <label class="form-label fw-bold">Tìm kiếm:</label>
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Tên sản phẩm..." value="{{ request('keyword') }}">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Sắp xếp theo:</label>
                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Khoảng giá ($):</label>
                <div class="d-flex align-items-center mb-2">
                    <input type="number" name="min_price" class="form-control me-2" placeholder="Min" value="{{ request('min_price') }}" min="0">
                    <span>-</span>
                    <input type="number" name="max_price" class="form-control ms-2" placeholder="Max" value="{{ request('max_price') }}" min="0">
                </div>
            </div>

            <button type="submit" class="btn btn-dark w-100 rounded-pill">Áp dụng bộ lọc</button>
            
            @if(request()->hasAny(['keyword', 'sort', 'min_price', 'max_price', 'category']))
                <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary w-100 mt-2 rounded-pill btn-sm">Xóa bộ lọc</a>
            @endif
        </form>
    </div>

    <div class="mb-4">
        <h4 class="mb-3">Danh mục</h4>
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                <a href="{{ route('shop.index') }}" class="text-decoration-none {{ !request('category') ? 'text-primary fw-bold' : 'text-dark' }}">
                    Tất cả sản phẩm
                </a>
            </li>

            @foreach($categories as $cate)
            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                {{-- array_merge: Giữ lại search/sort khi bấm chuyển danh mục --}}
                <a href="{{ route('shop.index', array_merge(request()->query(), ['category' => $cate->id, 'page' => 1])) }}" 
                   class="text-decoration-none {{ request('category') == $cate->id ? 'text-primary fw-bold' : 'text-dark' }}">
                    {{ $cate->name }}
                </a>
                <span class="badge bg-secondary rounded-pill">{{ $cate->products_count }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</div>