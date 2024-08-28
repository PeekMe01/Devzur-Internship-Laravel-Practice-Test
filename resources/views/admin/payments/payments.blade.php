@extends('layouts.admin')

@section('title', 'Admin Dashboard - Payments')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Payments Table</h6>
                <form method="GET" action="{{ route('adminPayments') }}" class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search by Payment Number or Invoice" value="{{ request()->get('search') }}">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
                <div class="table-responsive">
                    @if ($payments->isEmpty())
                        <h1>No Payments</h1>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">Payment Number</th>
                                    <th scope="col">Invoice</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <th scope="row">{{ $payment->id }}</th>
                                        <td>{{ $payment->transaction_id }}</td>
                                        <td>{{ $payment->order->invoice }}</td>
                                        <td>{{ $payment->order->total_amount }}</td>
                                        <td>{{ $payment->order_id }}</td>
                                        <td>{{ $payment->user->name }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary m-1" href="{{ route('orderDetails', ['order_id' => $payment->order->id]) }}">View Order</a>
                                            @php
                                                $transactionUrl = 'https://dashboard.stripe.com/payments/' . $payment->transaction_id;
                                            @endphp
                                            <a href="{{ $transactionUrl }}" target="_blank" class="btn btn-sm btn-primary m-1">
                                                View Transaction on Stripe
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center">
                            {{ $payments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection