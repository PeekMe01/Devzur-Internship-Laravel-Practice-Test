@extends('layouts.user')

@section('title', 'Cart')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('user/plugins/themify-icons/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/styles/single_styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/styles/single_responsive.css') }}">
</head>

<style>
    .submit_button_quantity{
        background-color: transparent;
        border-width: 0;
        padding: 10px;
    }
    .checkout_button{
        display: -webkit-inline-box;
        display: -moz-inline-box;
        display: -ms-inline-flexbox;
        display: -webkit-inline-flex;
        display: inline-flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: auto;
        padding: 10px;
        height: 40px;
        background: #fe4c50;
        color: #FFFFFF;
        border-width: 0;
        border-radius: 3px;
        -webkit-transition: all 0.3s ease;
        -moz-transition: all 0.3s ease;
        -ms-transition: all 0.3s ease;
        -o-transition: all 0.3s ease;
        transition: all 0.3s ease;
    }
    .checkout_button:hover{
        background: #FE7C7F !important;
        color: #FFFFFF;
    }
</style>
<div class="container product_section_container">
    <div class="row">
        <div class="col product_section clearfix">

            <!-- Breadcrumbs -->
            <div class="breadcrumbs d-flex flex-row align-items-center">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active"><a href="{{ route('cart.view') }}"><i class="fa fa-angle-right" aria-hidden="true"></i>Cart</a></li>
                </ul>
            </div>

            <!-- Cart Table -->
            <div class="cart_table">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart->products as $product)
                        @php
                            $images = json_decode($product->images, true);
                            $price = $product->discounted_price ?? $product->price;
                        @endphp
                        <tr>
                            <td>
                                <img src="{{ $images[0] }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 100px;">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>${{ number_format($price, 2) }}</td>
                            <td>
                                <div class="quantity d-flex flex-column flex-sm-row align-items-sm-center" data-max-quantity="{{ $product->quantity }}">
                                    <div class="quantity_selector">
                                        <form action="{{ route('cart.decrease', ['product_id' => $product->id]) }}" method="POST" style="display:inline;" class="decrease-form" data-current-quantity="{{ $product->pivot->quantity }}">
                                            @csrf
                                            <span class="minus">
                                                <button class="submit_button_quantity" type="submit"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                            </span>    
                                        </form>
                                        <span id="quantity_value_{{ $product->id }}">{{ $product->pivot->quantity }}</span>
                                        <form action="{{ route('cart.increase', ['product_id' => $product->id]) }}" method="POST" style="display:inline;" class="increase-form" data-max-quantity="{{ $product->quantity }}" data-product-id="{{ $product->id }}">
                                            @csrf
                                            <span class="plus">
                                                <button class="submit_button_quantity" type="submit"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                            </span>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td>${{ number_format($price * $product->pivot->quantity, 2) }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $product->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Cart Summary -->
            @if (count(Auth::user()->cart->products) > 0)
                <div class="cart_summary">
                    <h3>Total: ${{ number_format($cart->products->sum(fn($product) => ($product->discounted_price ?? $product->price) * $product->pivot->quantity), 2) }}</h3>
                    <a href="{{ route('checkout.index') }}" class="checkout_button">Proceed to Checkout</a>
                </div>    
            @else
                <div class="cart_summary">
                    <h3>Total: ${{ number_format($cart->products->sum(fn($product) => ($product->discounted_price ?? $product->price) * $product->pivot->quantity), 2) }}</h3>
                    <a href="{{ route('shop') }}" class="checkout_button">Continue Shopping</a>
                </div> 
            @endif
            

        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const minQuantity = 1; // Minimum quantity limit

        // Handle decrease form submission
        document.querySelectorAll('.decrease-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                const currentQuantity = parseInt(form.getAttribute('data-current-quantity'));
                if (currentQuantity <= minQuantity) {
                    event.preventDefault(); // Prevent form submission
                    // alert('Cannot decrease quantity below the minimum limit.');
                }
            });
        });

        // Handle increase form submission
        document.querySelectorAll('.increase-form').forEach(form => {
            const maxQuantity = parseInt(form.getAttribute('data-max-quantity'));
            const productId = form.getAttribute('data-product-id');
            const quantityElement = document.getElementById(`quantity_value_${productId}`);

            form.addEventListener('submit', function(event) {
                const currentQuantity = parseInt(quantityElement.textContent);
                if (currentQuantity >= maxQuantity) {
                    event.preventDefault(); // Prevent form submission
                    // alert('Cannot increase quantity above the maximum limit.');
                }
            });
        });
    });
</script>
@endsection