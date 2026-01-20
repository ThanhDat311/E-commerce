@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Product: {{ $product->name }}</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Bắt buộc cho update --}}
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Product Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>SKU</label>
                        <input type="text" name="sku" class="form-control" value="{{ $product->sku }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Category</label>
                        <select name="category_id" class="form-control">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Stock</label>
                        <input type="number" name="stock_quantity" class="form-control" value="{{ $product->stock_quantity }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Current Image</label><br>
                    <img src="{{ asset($product->image_url) }}" width="100" class="mb-2 rounded">
                    <input type="file" name="image" class="form-control">
                    <small class="text-muted">Leave empty if you don't want to change image</small>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_featured" class="form-check-input" id="featured" {{ $product->is_featured ? 'checked' : '' }}>
                    <label class="form-check-label" for="featured">Is Featured?</label>
                </div>

                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection