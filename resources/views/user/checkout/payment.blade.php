@extends('layouts.user')

@section('title', 'Payment')

@section('content')
<style>
    /* General Container Styling */
    .payment_container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    h2 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    #card-element {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #f9f9f9;
    }

    #card-errors {
        color: #fa755a;
        margin-top: 10px;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        text-decoration: none;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background-color: #fe4c50;
        color: #fff;
    }

    .btn-primary {
        background-color: #fe4c50;
    }

    .btn-primary:hover {
        background-color: #FE7C7F;
    }

    .mt-3 {
        margin-top: 1rem;
    }
</style>
<div class="container product_section_container">
    <div class="row">
        <div class="col product_section clearfix">
            <!-- Breadcrumbs -->
            <div class="breadcrumbs d-flex flex-row align-items-center">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active"><a href="{{ route('checkout.index') }}"><i class="fa fa-angle-right" aria-hidden="true"></i>Checkout</a></li>
                </ul>
            </div>
            <div class="payment_container">
                <h2>Complete Your Payment</h2>
                @php
                    $validated = session('checkout_data');
                @endphp

                <form id="payment-form" action="{{ route('checkout.handlePayment') }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="first_name" value="{{ $validated['first_name'] }}">
                    <input type="hidden" name="last_name" value="{{ $validated['last_name'] }}">
                    <input type="hidden" name="email" value="{{ $validated['email'] }}">
                    <input type="hidden" name="phone" value="{{ $validated['phone'] }}">
                    <input type="hidden" name="address" value="{{ $validated['address'] }}">
                    <input type="hidden" name="postal_code" value="{{ $validated['postal_code'] }}">
                    <input type="hidden" name="city" value="{{ $validated['city'] }}">
                    <input type="hidden" name="country" value="{{ $validated['country'] }}">
                    <input type="hidden" name="location_lat" value="{{ $validated['location_lat'] }}">
                    <input type="hidden" name="location_lng" value="{{ $validated['location_lng'] }}">
                    <input type="hidden" name="total_amount" value="{{ $validated['total_amount'] }}">

                    <!-- Stripe Elements Placeholder -->
                    <div id="card-element">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>

                    <!-- Stripe Errors -->
                    <div id="card-errors" role="alert"></div>

                    <button id="submit-button" class="btn btn-primary mt-3">Pay ${{ number_format($validated['total_amount'], 2) }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe
    var stripe = Stripe('{{ config('services.stripe.key') }}');
    var elements = stripe.elements();

    // Create an instance of the card Element
    var card = elements.create('card', {
        hidePostalCode: true,
        style: {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        }
    });

    // Add the card Element to the form
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Inform the user if there was an error
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Send the token to your server
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', result.token.id);
                form.appendChild(hiddenInput);

                // Submit the form
                form.submit();
            }
        });
    });
</script>
@endsection