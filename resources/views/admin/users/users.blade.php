@extends('layouts.admin')

@section('title', 'Admin Dashboard - Users')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Users Table</h6>
                <form method="GET" action="{{ route('adminUsers') }}" class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search by name" value="{{ request()->get('search') }}">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
                <div class="table-responsive">
                    @if ($users->isEmpty())
                        <h1>No Users</h1>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">User Type</th>
                                    <th scope="col">Profile Picture</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <th scope="row">{{ $user->id }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->user_type }}</td>
                                        <td>
                                            <img src="{{ $user->profile_photo_path ? $user->profile_photo_path : 'img/nopfp.png' }}" alt="Product Image" style="width: 100px; height: 100px; object-fit: cover;">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection