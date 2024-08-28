@extends('layouts.admin')

@section('title', 'Admin Dashboard - Products')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <a href="{{ route('addProductForm') }}" class="text-white btn btn-primary p-4">Add New Product</a>
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Products Table</h6>
                <form method="GET" action="{{ route('adminProducts') }}" class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search by name" value="{{ request()->get('search') }}">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Price</th>
                                <th scope="col">Discounted Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Category</th>
                                <th scope="col">Image</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                @php
                                    $images = json_decode($product->images, true); // Decode the JSON array
                                    $firstImage = $images[0] ?? null; // Get the first image or null if it doesn't exist
                                @endphp
                                <tr>
                                    <th scope="row">{{ $product->id }}</th>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->description }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->discounted_price }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>
                                        @if ($firstImage)
                                            <img src="{{ $firstImage }}" alt="Product Image" style="width: 100px; height: 100px; object-fit: cover;">
                                        @else
                                            <p>No image available</p>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-primary m-1" href="{{ route('viewProduct', ['product_id' => $product->id]) }}">Detail</a>
                                        <a class="btn btn-sm btn-primary m-1" href="{{ route('editProductForm', ['product_id' => $product->id]) }}">Edit</a>
                                        <form action="{{ route('deleteProduct', ['product_id' => $product->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger m-1" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImages(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('imagePreviewContainer');
        previewContainer.innerHTML = '';
    
        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgDiv = document.createElement('div');
                imgDiv.className = 'image-preview';
                imgDiv.style.position = 'relative';
                imgDiv.style.display = 'inline-block';
                imgDiv.style.margin = '5px';
    
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '200px';
                img.style.height = '200px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '5px';
    
                const removeButton = document.createElement('span');
                removeButton.innerHTML = '&times;';
                removeButton.style.position = 'absolute';
                removeButton.style.top = '5px';
                removeButton.style.right = '5px';
                removeButton.style.cursor = 'pointer';
                removeButton.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                removeButton.style.color = 'white';
                removeButton.style.borderRadius = '50%';
                removeButton.style.padding = '2px 5px';
                removeButton.onclick = function() {
                    imgDiv.remove();
                };
    
                imgDiv.appendChild(img);
                imgDiv.appendChild(removeButton);
                previewContainer.appendChild(imgDiv);
            };
            reader.readAsDataURL(file);
        });
    }
</script>

<style>
.image-preview img {
    transition: transform 0.5s ease-in-out;
}

.image-preview:hover img {
    transform: scale(1.05);
}
</style>
<!-- Form End -->
<!-- Table Start -->
<!-- Table End -->
@endsection