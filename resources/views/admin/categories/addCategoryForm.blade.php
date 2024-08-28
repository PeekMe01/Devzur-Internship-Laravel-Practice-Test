@extends('layouts.admin')

@section('title', 'Admin Dashboard - Categories')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div>
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Category Form</h6>
                <form action="{{ route('addCategory') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input required type="text" class="form-control" name="category_name" id="category_name">
                    </div>
                    <div class="mb-3">
                        <label for="category_photo" class="form-label">Category Photo</label>
                        <input required class="form-control" type="file" name="category_photo" id="category_photo" onchange="previewImages(event)">
                    </div>
                    <div class="mb-3" id="imagePreviewContainer">
                        <!-- Selected images will be displayed here -->
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="feature_category" id="feature_category">
                        <label class="form-check-label" for="feature_category">
                            Feature Category?
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
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
    
                // const removeButton = document.createElement('span');
                // removeButton.innerHTML = '&times;';
                // removeButton.style.position = 'absolute';
                // removeButton.style.top = '5px';
                // removeButton.style.right = '5px';
                // removeButton.style.cursor = 'pointer';
                // removeButton.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                // removeButton.style.color = 'white';
                // removeButton.style.borderRadius = '50%';
                // removeButton.style.padding = '2px 5px';
                // removeButton.onclick = function() {
                //     imgDiv.remove();
                // };
    
                imgDiv.appendChild(img);
                // imgDiv.appendChild(removeButton);
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
@endsection