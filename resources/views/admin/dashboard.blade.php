@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Today Sale</p>
                        <h6 class="mb-0">${{ $totalSales }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Sale</p>
                        <h6 class="mb-0">${{ $todaySales }}</h6>
                    </div>
                </div>
            </div>

            
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-user-check fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Sign-ups</p>
                        <h6 class="mb-0">{{ $totalSignUps }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-calendar-day fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Today Sign-ups</p>
                        <h6 class="mb-0">{{ $todaySignUps }}</h6>
                    </div>
                </div>
            </div>            
        </div>
    </div>
    <!-- Sale & Revenue End -->

    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Recent Orders</h6>
                <a href="{{ route('adminOrders') }}">Show All</a>
            </div>
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
                @endif
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->


    <!-- Widgets Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-md-6 col-xl-4">
                <div class="h-100 bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Calender</h6>
                    </div>
                    <div id="calender"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Widgets End -->
@endsection