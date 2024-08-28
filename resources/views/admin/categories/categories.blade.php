@extends('layouts.admin')

@section('title', 'Admin Dashboard - Categories')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <a href="{{ route('addCategoryForm') }}" class="text-white btn btn-primary p-4">Add New Category</a>
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Categories Table</h6>
                <form method="GET" action="{{ route('adminCategories') }}" class="mb-4">
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
                                <th scope="col">Featured</th>
                                <th scope="col">Image</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <th scope="row">{{ $category->id }}</th>
                                    <th scope="row">{{ $category->name }}</th>
                                    <th scope="row">{{ $category->is_featured?'Yes':'No' }}</th>
                                    <td>
                                        <img src="{{ $category->image }}" alt="Product Image" style="width: 100px; height: 100px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-primary m-1" href="{{ route('editCategoryForm', ['category_id' => $category->id]) }}">Edit</a>
                                        <form action="{{ route('deleteCategory', ['category_id' => $category->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection