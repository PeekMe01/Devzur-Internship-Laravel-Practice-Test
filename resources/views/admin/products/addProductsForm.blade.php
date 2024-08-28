@extends('layouts.admin')

@section('title', 'Admin Dashboard - Products - Add Form')

@section('content')
<!-- Form Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div>
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Product Form</h6>
                <form action="{{ route('addProduct') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input required type="text" class="form-control" name="product_name" id="product_name">
                    </div>
                    <div class="mb-3">
                        <label for="product_description" class="form-label">Product Description</label>
                        <input required type="text" class="form-control" name="product_description" id="product_description">
                    </div>
                    <div class="mb-3">
                        <label for="product_price" class="form-label">Product Price</label>
                        <input required type="number" min="0" class="form-control" name="product_price" id="product_price">
                    </div>
                    <div class="mb-3">
                        <label for="product_price" class="form-label">Discounted Price</label>
                        <input type="number" min="0" class="form-control" name="discounted_product_price" id="discounted_product_price">
                    </div>
                    <div class="mb-3">
                        <label for="product_quantity" class="form-label">Product Quantity</label>
                        <input required type="number" min="0" class="form-control" name="product_quantity" id="product_quantity">
                    </div>
                    <div class="mb-3">
                        <label for="product_photos[]" class="form-label">Product Photos</label>
                        <input required class="form-control" type="file" multiple name="product_photos[]" id="product_photos[]" onchange="previewImages(event)">
                    </div>
                    <div class="mb-3" id="imagePreviewContainer">
                        <!-- Selected images will be displayed here -->
                    </div>
                    <div class="mb-3">
                        <label for="product_category" class="form-label">Product Category</label>
                        <select required name="product_category" id="product_category" class="form-select mb-3">
                            <option value="" disabled selected>Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('product_photos[]');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const removedNewImagesInput = document.getElementById('removed_new_images');
        
        let removedFiles = [];
    
        // Function to handle file preview
        function previewImages(event) {
            const files = event.target.files;
            previewContainer.innerHTML = '';
            removedFiles = []; // Reset removed files list
    
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
                        removedFiles.push(file); // Track the removed file object
                        updateFileInput();
                    };
    
                    imgDiv.appendChild(img);
                    imgDiv.appendChild(removeButton);
                    previewContainer.appendChild(imgDiv);
                };
                reader.readAsDataURL(file);
            });
        }
    
        // Function to update file input
        function updateFileInput() {
            const files = Array.from(fileInput.files);
            const remainingFiles = files.filter(file => !removedFiles.includes(file));
    
            const dataTransfer = new DataTransfer();
            remainingFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }
    
        // Attach the previewImages function to file input change event
        fileInput.addEventListener('change', previewImages);
    });
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