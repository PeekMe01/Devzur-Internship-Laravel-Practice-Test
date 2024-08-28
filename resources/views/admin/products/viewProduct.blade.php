@extends('layouts.admin')

@section('title', 'Admin Dashboard - Products - View Product')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div>
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Product</h6>
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input disabled type="text" class="form-control" name="product_name" id="product_name" value="{{ $product->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="product_description" class="form-label">Product Description</label>
                        <input disabled type="text" class="form-control" name="product_description" id="product_description" value="{{ $product->description }}">
                    </div>
                    <div class="mb-3">
                        <label for="product_price" class="form-label">Product Price</label>
                        <input disabled type="number" min="0" class="form-control" name="product_price" id="product_price" value="{{ $product->price }}">
                    </div>
                    <div class="mb-3">
                        <label for="product_price" class="form-label">Discounted Price</label>
                        <input disabled type="number" min="0" class="form-control" name="discounted_product_price" id="discounted_product_price" value="{{ $product->discounted_price }}">
                    </div>
                    <div class="mb-3">
                        <label for="product_quantity" class="form-label">Product Quantity</label>
                        <input disabled type="number" min="0" class="form-control" name="product_quantity" id="product_quantity" value="{{ $product->quantity }}">
                    </div>
                    <div class="mb-3">
                        <label for="product_photos[]" class="form-label">Product Photos</label>
                    </div>
                    <div class="mb-3 flex flex-row gap-4 flex-wrap" id="imagePreviewContainer">
                            <!-- Display product images -->
                    @if ($product->images)
                            @php
                                // Decode JSON string to PHP array
                                $images = $product->images;
                            @endphp
                            @foreach ($images as $image)
                                <div class="product-image">
                                    <img src="{{ $image }}" alt="Product Image" style="max-width: 200px; max-height: 200px; margin-bottom: 10px;">
                                </div>
                            @endforeach
                    @else
                        <p>No images available for this product.</p>
                    @endif
                    </div>
                    <div class="mb-3">
                        <label for="product_category" class="form-label">Product Category</label>
                        <input disabled type="text" class="form-control" name="product_category" id="product_category" value="{{ $product->category->name }}">
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection