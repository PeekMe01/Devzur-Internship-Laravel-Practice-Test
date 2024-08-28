@extends('layouts.user')

@section('title', 'Welcome')

@section('content')

<style>
	.featured_categories{
		margin-bottom: 30px;
	}
	.product-item.out-of-stock {
		position: relative;
		opacity: 0.5; /* Greys out the product */
		pointer-events: none; /* Disables clicking */
	}

	.sold_out_overlay {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(255, 255, 255, 0.7); /* Light overlay */
		color: #ff0000; /* Red color for 'Sold Out' text */
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.5em;
		font-weight: bold;
		text-transform: uppercase;
	}
</style>
	<!-- Slider -->

	<div class="main_slider" style="background-image:url(user/images/slider_1.jpg)">
		<div class="container fill_height">
			<div class="row align-items-center fill_height">
				<div class="col">
					<div class="main_slider_content">
						<h6>Spring / Summer Collection 2017</h6>
						<h1>Get up to 30% Off New Arrivals</h1>
						<div class="red_button shop_now_button"><a href="{{ route('shop') }}">shop now</a></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Banner -->

	@if ($featuredCategories && count($featuredCategories) > 0)
		<div class="banner">
			<div class="container">
				<div class="row featured_categories">
					<div class="col text-center">
						<div class="section_title new_arrivals_title">
							@if ($featuredCategories && count($featuredCategories) > 1)
							<h2>Featured Categories</h2>
							@else
								<h2>Featured Category</h2>
							@endif							
						</div>
					</div>
				</div>
				<div class="row">
					@foreach ($featuredCategories as $featuredCategory)
						<div class="col-md-4 p-2">
							<div class="banner_item align-items-center" style="background-image:url({{ $featuredCategory->image }})">
								<div class="banner_category">
									<a href="{{ route('shop', ['category' => $featuredCategory->id]) }}">{{ $featuredCategory->name }}'s</a>
								</div>
							</div>
						</div>
					@endforeach
					{{-- <div class="col-md-4 p-2">
						<div class="banner_item align-items-center" style="background-image:url(user/images/banner_1.jpg)">
							<div class="banner_category">
								<a href="categories.html">women's</a>
							</div>
						</div>
					</div>
					<div class="col-md-4 p-2">
						<div class="banner_item align-items-center" style="background-image:url(user/images/banner_2.jpg)">
							<div class="banner_category">
								<a href="categories.html">accessories's</a>
							</div>
						</div>
					</div>
					<div class="col-md-4 p-2">
						<div class="banner_item align-items-center" style="background-image:url(user/images/banner_3.jpg)">
							<div class="banner_category">
								<a href="categories.html">men's</a>
							</div>
						</div>
					</div> --}}
				</div>
			</div>
		</div>	
	@endif
	

	<!-- New Arrivals -->

	<div class="new_arrivals" id="new-arrivals">
		<div class="container">
			<div class="row">
				<div class="col text-center">
					<div class="section_title new_arrivals_title">
						<h2>New Arrivals</h2>
					</div>
				</div>
			</div>
			<div class="row align-items-center">
				<div class="col text-center">
					<div class="new_arrivals_sorting">
						<ul class="arrivals_grid_sorting clearfix button-group filters-button-group">
							<li class="grid_sorting_button button d-flex flex-column justify-content-center align-items-center active is-checked" data-filter="*">all</li>
							@foreach ($categories as $category)
								<li class="grid_sorting_button button d-flex flex-column justify-content-center align-items-center" data-filter=".{{ $category->name }}">{{ $category->name }}'s</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="product-grid" data-isotope='{ "itemSelector": ".product-item", "layoutMode": "fitRows" }'>

						<!-- Product 1 -->

						@foreach ($products as $product)
							@php
								$discountPercentage = 0;
								if ($product->price > 0 && $product->discounted_price > 0) {
									$discountPercentage = (($product->price - $product->discounted_price) / $product->price) * 100;
								}
							@endphp
							<div class="product-item {{ $product->category->name }}">
								<div class="product discount product_filter">
									<div class="product_image">
										<img src="{{ $product->images[0] }}" alt="Product Image">
									</div>
									{{-- <div class="favorite favorite_left"></div> --}}
									@if($product->discounted_price && $product->price > $product->discounted_price)
										@php
											$discountPercentage = (($product->price - $product->discounted_price) / $product->price) * 100;
										@endphp
										<div class="product_bubble product_bubble_right product_bubble_red d-flex flex-column align-items-center">
											<span>-{{ number_format($discountPercentage, 1) }}%</span>
										</div>
									@endif
									@php
										$currentTime = \Carbon\Carbon::now();
										$creationTime = \Carbon\Carbon::parse($product->created_at);
										$hoursDiff = $creationTime->diffInHours($currentTime);

									@endphp
									@if ($hoursDiff <= 48)
										<div class="product_bubble product_bubble_left product_bubble_green d-flex flex-column align-items-center"><span>new</span></div>
									@endif
									<div class="product_info">
										<h6 class="product_name"><a href="{{ route('viewProductUser', ['category_name' => $product->category->name, 'product_id' => $product->id]) }}">{{ $product->name }}</a></h6>
										{{-- <div class="product_price">$520.00<span>$590.00</span></div> --}}
										@if (isset($product->discounted_price))
											<div class="product_price">${{ $product->discounted_price }}<span>${{ $product->price }}</span></div>
										@else
											<div class="product_price">${{ $product->price }}</div>
										@endif
										<div class="product_price">{{ $product->quantity }} left!</div>
									</div>
								</div>
								@if($product->quantity > 0)
									<form id="addToCartForm_{{ $product->id }}" action="{{ route('cart.add') }}" method="POST">
										@csrf
										<input type="hidden" name="product_id" value="{{ $product->id }}">
										<input type="hidden" name="quantity" id="quantity_value_{{ $product->id }}" value="1">
									
										<div class="red_button add_to_cart_button">
											<a href="#" id="submitButton_{{ $product->id }}">Add to Cart</a>
										</div>
									</form>
								@else
									<div class="sold_out_overlay">Sold Out</div>
								@endif
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Deal of the week -->

	<div class="deal_ofthe_week">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<div class="deal_ofthe_week_img">
						<img src="{{ asset('user/images/deal_ofthe_week.png') }}" alt="">
					</div>
				</div>
				<div class="col-lg-6 text-right deal_ofthe_week_col">
					<div class="deal_ofthe_week_content d-flex flex-column align-items-center float-right">
						<div class="section_title">
							<h2>Deal Of The Week</h2>
						</div>
						<ul class="timer">
							<li class="d-inline-flex flex-column justify-content-center align-items-center">
								<div id="day" class="timer_num">03</div>
								<div class="timer_unit">Day</div>
							</li>
							<li class="d-inline-flex flex-column justify-content-center align-items-center">
								<div id="hour" class="timer_num">15</div>
								<div class="timer_unit">Hours</div>
							</li>
							<li class="d-inline-flex flex-column justify-content-center align-items-center">
								<div id="minute" class="timer_num">45</div>
								<div class="timer_unit">Mins</div>
							</li>
							<li class="d-inline-flex flex-column justify-content-center align-items-center">
								<div id="second" class="timer_num">23</div>
								<div class="timer_unit">Sec</div>
							</li>
						</ul>
						<div class="red_button shop_now_button"><a href="{{ route('shop') }}">shop now</a></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Best Sellers -->
	<div class="best_sellers">
		<div class="container">
			<div class="row">
				<div class="col text-center">
					<div class="section_title new_arrivals_title">
						<h2>Best Sellers</h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="product_slider_container">
						<div class="owl-carousel owl-theme product_slider">

							@foreach ($bestSellers as $product)
								<div class="owl-item product_slider_item">
									<div class="product-item women">
										<div class="product">
											<div class="product_image">
												<img src="{{ $product->images[0] }}" alt="Product Image">
											</div>
											@if($product->discounted_price && $product->price > $product->discounted_price)
												@php
													$discountPercentage = (($product->price - $product->discounted_price) / $product->price) * 100;
												@endphp
												<div class="product_bubble product_bubble_right product_bubble_red d-flex flex-column align-items-center">
													<span>-{{ number_format($discountPercentage, 1) }}%</span>
												</div>
											@endif
											@php
												$currentTime = \Carbon\Carbon::now();
												$creationTime = \Carbon\Carbon::parse($product->created_at);
												$hoursDiff = $creationTime->diffInHours($currentTime);

											@endphp
											@if ($hoursDiff <= 48)
												<div class="product_bubble product_bubble_left product_bubble_green d-flex flex-column align-items-center"><span>new</span></div>
											@endif
											<div class="product_info">
												<h6 class="product_name"><a href="{{ route('viewProductUser', ['category_name' => $product->category->name, 'product_id' => $product->id]) }}">{{ $product->name }}</a></h6>
												@if (isset($product->discounted_price))
													<div class="product_price">${{ $product->discounted_price }}<span>${{ $product->price }}</span></div>
												@else
													<div class="product_price">${{ $product->price }}</div>
												@endif
												<div class="product_price">{{ $product->quantity }} left!</div>
											</div>
											@if($product->quantity > 0)
												
											@else
												<div class="sold_out_overlay">Sold Out</div>
											@endif
										</div>
									</div>
								</div>
							@endforeach

						</div>

						<!-- Slider Navigation -->

						<div class="product_slider_nav_left product_slider_nav d-flex align-items-center justify-content-center flex-column">
							<i class="fa fa-chevron-left" aria-hidden="true"></i>
						</div>
						<div class="product_slider_nav_right product_slider_nav d-flex align-items-center justify-content-center flex-column">
							<i class="fa fa-chevron-right" aria-hidden="true"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
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
							<p>8AM - 9PM</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		var submitButtons = document.querySelectorAll('[id^="submitButton_"]');
		
		submitButtons.forEach(function(button) {
			button.addEventListener('click', function(event) {
				event.preventDefault(); // Prevent the default link behavior
				
				// Extract product ID from button ID
				var productId = button.id.split('_')[1];
				
				// Find the form with the matching ID
				var form = document.getElementById('addToCartForm_' + productId);
				
				if (form) {
					form.submit(); // Submit the form
				}
			});
		});
	});
</script>
@endsection