@extends('layouts.admin')

@section('title', 'Admin Dashboard - Orders')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Order {{ $order->id }}</h6>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Invoice</th>
                                <th scope="col">Payment Type</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col">Order Status</th>
                                <th scope="col">Username</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">{{ $order->id }}</th>
                                <td>{{ $order->total_amount }}</td>
                                <td>{{ $order->invoice }}</td>
                                <td>{{ $order->payment_type }}</td>
                                <td>{{ $order->payment_status }}</td>
                                <td>{{ $order->order_status }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                    <a class="btn btn-sm btn-primary m-1" href="https://www.google.com/maps?q={{ $order->location_lat }},{{ $order->location_lng }}" target="_blank">View on Maps</a>
                                    @if ($order->order_status == 'Delivered')
                                    <form action="{{ route('markOrderPending', ['order_id' => $order->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-primary m-1">Mark Order Pending</button>
                                    </form>
                                    @else
                                        <form action="{{ route('markOrderDelivered', ['order_id' => $order->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-primary m-1">Mark Order Delivered</button>
                                        </form>
                                    @endif
                                    
                                    @if ($order->payment_status == 'Paid')
                                        <form action="{{ route('markPaymentPending', ['order_id' => $order->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-primary m-1">Mark Payment Pending</button>
                                        </form>
                                    @else
                                        <form action="{{ route('markPaymentPaid', ['order_id' => $order->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-primary m-1">Mark Payment Paid</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('cancelOrder', ['order_id' => $order->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger m-1">Cancel Order</button>
                                    </form>                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Billing Info</h6>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Address</th>
                                <th scope="col">Postal Code</th>
                                <th scope="col">City</th>
                                <th scope="col">Country</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">{{ $order->first_name }}</th>
                                <td>{{ $order->last_name }}</td>
                                <td>{{ $order->email }}</td>
                                <td>{{ $order->phone }}</td>
                                <td>{{ $order->address }}</td>
                                <td>{{ $order->postal_code }}</td>
                                <td>{{ $order->city }}</td>
                                <td>{{ $order->country }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Products Table</h6>
                <div class="table-responsive">
                    @if (!$order->products->isEmpty())
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->products as $product)
                                    @php
                                        $images = json_decode($product->images, true); // Decode the JSON array for product images
                                        $firstImage = $images[0] ?? null; // Get the first image or null if it doesn't exist

                                        $quantity = $product->pivot->quantity; // Access the quantity from the pivot table
                                        $total = number_format($product->price * $quantity, 2); // Calculate total
                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $product->id }}</th>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->description }}</td>
                                        <td>{{ $product->price }}</td> <!-- Display price from Product -->
                                        <td>{{ $quantity }}</td> <!-- Display quantity from OrderProduct pivot table -->
                                        <td>{{ $product->category->name }}</td>
                                        <td>
                                            @if ($firstImage)
                                                <img src="{{ $firstImage }}" alt="Product Image" style="width: 100px; height: 100px; object-fit: cover;">
                                            @else
                                                <p>No image available</p>
                                            @endif
                                        </td>
                                        <td>{{ $total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <h1>No Products</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection