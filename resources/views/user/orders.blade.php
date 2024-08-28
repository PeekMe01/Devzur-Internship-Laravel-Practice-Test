@extends('layouts.user')

@section('title', 'My Orders')

@section('content')
<div class="container product_section_container">
    <div class="row">
        <div class="col product_section clearfix">

            <!-- Breadcrumbs -->
            <div class="breadcrumbs d-flex flex-row align-items-center">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active"><a href="{{ route('orders.view') }}"><i class="fa fa-angle-right" aria-hidden="true"></i>My Orders</a></li>
                </ul>
            </div>
            <!-- Orders Table -->
            <div class="orders_table">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Invoice</th>
                            <th>Total Amount</th>
                            <th>Payment Type</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->invoice }}</td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                            <td>{{ $order->payment_type }}</td>
                            <td>{{ $order->payment_status }}</td>
                            <td>{{ $order->order_status }}</td>
                            <td>
                                <a href="{{ route('order.details', ['order_id' => $order->id]) }}" class="btn btn-primary">View Details</a>
                                <form action="{{ route('order.cancel', ['order_id' => $order->id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Cancel Order</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection