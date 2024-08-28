@extends('layouts.user')

@section('title', 'Product')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('user/plugins/themify-icons/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/styles/single_styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('user/styles/single_responsive.css') }}">
</head>
<style>
.red_button_single, .red_button_single button
{
	display: -webkit-inline-box;
	display: -moz-inline-box;
	display: -ms-inline-flexbox;
	display: -webkit-inline-flex;
	display: inline-flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	width: auto;
	height: 40px;
	background: #fe4c50;
    border-width: 0;
	border-radius: 3px;
	-webkit-transition: all 0.3s ease;
	-moz-transition: all 0.3s ease;
	-ms-transition: all 0.3s ease;
	-o-transition: all 0.3s ease;
	transition: all 0.3s ease;
}
.red_button_single:hover, .red_button_single button:hover
{
	background: #FE7C7F !important;
}
.red_button_single a, .red_button_single button
{
	display: block;
	color: #FFFFFF;
	text-transform: uppercase;
	font-size: 12px;
	font-weight: 500;
	text-align: center;
	line-height: 40px;
	width: 100%;
}
.add_to_cart_button_single
{
	width: 100%;
	font-size: 12px !important;
    margin-top: 30px;
}
.quantity_left_container{
    display: flex;
    flex-direction: row;
}
.quantity_left{
    display: inline-block;
	font-size: 24px;
	font-weight: 500;
	line-height: 30px;
	margin-top: 2px;
    margin-left: 6px;
}
.single_product_thumbnails {
    position: relative;
}

.thumbnail_carousel {
    max-height: 450px; /* Adjust height as needed */
    overflow: hidden;
    position: relative;
}

.thumbnail_carousel li {
    margin-bottom: 5px;
}

.carousel_controls {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.carousel_up,
.carousel_down {
    cursor: pointer;
    padding: 5px;
    font-size: 40px;
    color: #fe4c50;
}

.carousel_up {
    margin-bottom: 5px;
}

.add_button{
    
}

.sold_out{
    margin-top: 30px;
}

.sold_out, .sold_out button
{
	display: -webkit-inline-box;
	display: -moz-inline-box;
	display: -ms-inline-flexbox;
	display: -webkit-inline-flex;
	display: inline-flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	width: auto;
	height: 40px;
	background: gray;
    border-width: 0;
	border-radius: 3px;
	-webkit-transition: all 0.3s ease;
	-moz-transition: all 0.3s ease;
	-ms-transition: all 0.3s ease;
	-o-transition: all 0.3s ease;
	transition: all 0.3s ease;
    width: 100%;
	font-size: 12px !important;
}

.sold_out a, .sold_out button
{
	display: block;
	color: #FFFFFF;
	text-transform: uppercase;
	font-size: 12px;
	font-weight: 500;
	text-align: center;
	width: 100%;
}


</style>
<div class="container single_product_container">
    <div class="row">
        <div class="col">

            <!-- Breadcrumbs -->

            <div class="breadcrumbs d-flex flex-row align-items-center">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('shop', ['category' => $category]) }}"><i class="fa fa-angle-right" aria-hidden="true"></i>{{ $category->name }}</a></li>
                    <li class="active"><a href="{{ route('viewProductUser', ['category_name' => $category->name, 'product_id' => $product->id]) }}"><i class="fa fa-angle-right" aria-hidden="true"></i>{{ $product->name }}</a></li>
                </ul>
            </div>

        </div>
    </div>

    <form action="{{ route('cart.add') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" id="quantity_input" name="quantity" value="1">

        <div class="row">
            <div class="col-lg-7">
                <div class="single_product_pics">
                    <div class="row">
                        <div class="col-lg-3 thumbnails_col order-lg-1 order-2">
                            <div class="single_product_thumbnails">
                                @if(count($product->images) > 3)
                                    <div class="carousel_controls">
                                        <span class="carousel_up"><i class="fa fa-angle-up"></i></span>
                                    </div>
                                @endif
                                <ul class="{{ count($product->images) > 3 ? 'thumbnail_carousel' : '' }}">
                                    @foreach($product->images as $index => $image)
                                        <li class="{{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ $image }}" alt="" data-image="{{ $image }}">
                                        </li>
                                    @endforeach
                                </ul>
                                @if(count($product->images) > 3)
                                    <div class="carousel_controls">
                                        <span class="carousel_down"><i class="fa fa-angle-down"></i></span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-9 image_col order-lg-2 order-1">
                            <div class="single_product_image">
                                <div class="single_product_image_background" style="background-image:url({{ $product->images[0] }})"></div>
                            </div>
                        </div>
                    </div>                                                         
                </div>
            </div>
            <div class="col-lg-5">
                <div class="product_details">
                    <div class="product_details_title">
                        <h2>{{ $product->name }}</h2>
                        <p>{{ $product->description }}</p>
                    </div>
                    <div class="free_delivery d-flex flex-row align-items-center justify-content-center">
                        <span class="ti-truck"></span><span>free delivery</span>
                    </div>
                    <div class="original_price">${{ $product->price }}</div>
                    @if (isset($product->discounted_price))
                        <div class="product_price">${{ $product->discounted_price }}</div>
                    @else
                    @endif
                    <div class="quantity_left_container">
                        <div class="product_price">{{ $product->quantity }}</div>
                        <p class="quantity_left">Left!</p>
                    </div>                
                    <div class="quantity d-flex flex-column flex-sm-row align-items-sm-center" data-max-quantity="{{ $product->quantity }}">
                        <span>Quantity:</span>
                        <div class="quantity_selector">
                            <span class="minus"><i class="fa fa-minus" aria-hidden="true"></i></span>
                            <span id="quantity_value">1</span>
                            <span class="plus"><i class="fa fa-plus" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>
                @if($product->quantity > 0)
                    <div class="red_button_single add_to_cart_button_single">
                        <button class="add_button">add to cart</button>
                    </div>
                @else
                <div class="sold_out">
                    <button class="add_button" type="button">Sold Out</button>
                </div>
                @endif
            </div>
        </div>
    </form>

</div>

<!-- Benefit -->

<div class="benefit">
    <div class="container">
        <div class="row benefit_row">
            <div class="col-lg-3 benefit_col">
                <div class="benefit_item d-flex flex-row align-items-center">
                    <div class="benefit_icon"><i class="fa fa-truck" aria-hidden="true"></i></div>
                    <div class="benefit_content">
                        <h6>free shipping</h6>
                        <p>Suffered Alteration in Some Form</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 benefit_col">
                <div class="benefit_item d-flex flex-row align-items-center">
                    <div class="benefit_icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                    <div class="benefit_content">
                        <h6>cach on delivery</h6>
                        <p>The Internet Tend To Repeat</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 benefit_col">
                <div class="benefit_item d-flex flex-row align-items-center">
                    <div class="benefit_icon"><i class="fa fa-undo" aria-hidden="true"></i></div>
                    <div class="benefit_content">
                        <h6>45 days return</h6>
                        <p>Making it Look Like Readable</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 benefit_col">
                <div class="benefit_item d-flex flex-row align-items-center">
                    <div class="benefit_icon"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
                    <div class="benefit_content">
                        <h6>opening all week</h6>
                        <p>8AM - 09PM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var plus = document.querySelector('.plus');
        var minus = document.querySelector('.minus');
        var quantityDisplay = document.getElementById('quantity_value');
        var quantityInput = document.getElementById('quantity_input');
        var maxQuantity = parseInt('{{ $product->quantity }}', 10); // Ensure maxQuantity is an integer

        plus.addEventListener('click', function() {
            var currentQuantity = parseInt(quantityDisplay.textContent, 10);
            if (currentQuantity < maxQuantity) {
                quantityDisplay.textContent = currentQuantity + 1;
                quantityInput.value = quantityDisplay.textContent; // Update hidden input
            }
        });

        minus.addEventListener('click', function() {
            var currentQuantity = parseInt(quantityDisplay.textContent, 10);
            if (currentQuantity > 1) {
                quantityDisplay.textContent = currentQuantity - 1;
                quantityInput.value = quantityDisplay.textContent; // Update hidden input
            }
        });
    });
</script>
@endsection