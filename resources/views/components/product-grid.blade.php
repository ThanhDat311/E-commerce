{{-- Khai báo props để nhận dữ liệu từ bên ngoài vào --}}
@props(['products'])

<div class="row g-4">
    {{-- Kiểm tra nếu có sản phẩm thì mới lặp --}}
    @if(isset($products) && count($products) > 0)
    @foreach($products as $product)
    {{-- Class col-... có thể tùy chỉnh hoặc truyền vào nếu muốn linh hoạt hơn --}}
    <div class="col-md-6 col-lg-4 col-xl-3">
        @include('partials.product-item', ['product' => $product])
    </div>
    @endforeach
    @else
    <div class="col-12 text-center">
        <p>Không có sản phẩm nào để hiển thị.</p>
    </div>
    @endif
</div>