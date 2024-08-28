@extends('layouts.admin')

@section('title', 'Admin Dashboard - Orders')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Orders Table</h6>
                <form method="GET" action="{{ route('adminOrders') }}" class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search by invoice" value="{{ request()->get('search') }}">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
                <div class="table-responsive">
                    @if ($orders->isEmpty())
                        <h1>No Orders</h1>
                    @else
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
                                @foreach ($orders as $order)
                                    <tr>
                                        <th scope="row">{{ $order->id }}</th>
                                        <td>{{ $order->total_amount }}</td>
                                        <td>{{ $order->invoice }}</td>
                                        @if ($order->payment_type=='credit_card')
                                            <td>Card</td>
                                        @else
                                            <td>Cash On Delivery</td>
                                        @endif
                                        <td>{{ $order->payment_status }}</td>
                                        <td>{{ $order->order_status }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary m-1" href="{{ route('orderDetails', ['order_id' => $order->id]) }}">Detail</a>
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
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection