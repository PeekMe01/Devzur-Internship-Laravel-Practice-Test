@extends('layouts.user')

@section('title', 'Shop')

@section('content')
<style>
.search_button
{
	width: 100px;
	height: 40px;
	background: #1e1e27;
	text-align: center;
	cursor: pointer;
	-webkit-transition: all 0.3s ease;
	-moz-transition: all 0.3s ease;
	-ms-transition: all 0.3s ease;
	-o-transition: all 0.3s ease;
	transition: all 0.3s ease;
}
.search_button:hover
{
	background: #34343f;
}
.search_button span
{
	font-size: 12px;
	font-weight: 600;
	text-transform: uppercase;
	line-height: 30px;
	color: #FFFFFF;
}
.no-results-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 12rem; /* Adjust height as needed */
    margin-top: 2rem; /* Adjust top margin as needed */
}

.no-results-heading {
    font-size: 1.5rem; /* Adjust size as needed */
    font-weight: 500; /* Adjust weight as needed */
    color: #4a4a4a; /* Adjust text color as needed */
}

.highlight-text {
    color: #e53e3e; /* Red color for highlighting */
}

.no-results-message {
    font-size: 0.875rem; /* Smaller font size for the message */
    color: #6b7280; /* Gray color for the message text */
    margin-top: 0.5rem; /* Margin between heading and message */
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
<div class="container product_section_container">
    <div class="row">
        <div class="col product_section clearfix">

            <!-- Breadcrumbs -->
            <div class="breadcrumbs d-flex flex-row align-items-center">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active"><a href="{{ route('shop', ['category' => $category]) }}"><i class="fa fa-angle-right" aria-hidden="true"></i>{{ $categoryName }}{{ $categoryName !== "All"?"'s":'' }}</a></li>
                </ul>
            </div>

            <!-- Search Filter (Outside the main filter form) -->
            <div class="mb-4">
                <form action="{{ route('shop') }}" method="GET" class="flex items-center">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search Products..."
                           class="border border-gray-300 rounded-l-lg py-2 px-4 w-full md:w-72 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                            class="search_button">
                            <span>Search</span>
                    </button>
                    <!-- Hidden Inputs -->
                    <input type="hidden" name="category" value="{{ $category }}">
                    <input type="hidden" name="min_price" value="{{ $minPrice }}">
                    <input type="hidden" name="max_price" value="{{ $maxPrice }}">
                </form>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <form action="{{ route('shop') }}" method="GET">
                    <div class="sidebar_section">
                        <div class="sidebar_title">
                            <h5>Product Category</h5>
                        </div>
                        <ul class="sidebar_categories">
                            <li class="{{ is_null($category) ? 'active' : '' }}">
                                <a href="{{ route('shop', ['min_price' => $minPrice, 'max_price' => $maxPrice, 'search' => $search]) }}">
                                    <span><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>All
                                </a>
                            </li>
                            @foreach($categories as $cat)
                                <li class="{{ $category == $cat->id ? 'active' : '' }}">
                                    <a href="{{ route('shop', ['category' => $cat->id, 'min_price' => $minPrice, 'max_price' => $maxPrice, 'search' => $search]) }}">
                                        <span><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>{{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>                    

                    <!-- Price Range Filtering -->
                    <div class="sidebar_section">
                        <div class="sidebar_title">
                            <h5>Filter by Price</h5>
                        </div>
                        <p>
                            <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
                        </p>
                        <div id="slider-range"></div>
                        <form method="GET" action="{{ route('shop') }}">
                            <input type="hidden" name="category" value="{{ $category }}">
                            <input type="hidden" name="search" value="{{ $search }}">
                            <input type="hidden" name="min_price" id="min_price" value="{{ $minPrice }}">
                            <input type="hidden" name="max_price" id="max_price" value="{{ $maxPrice }}">
                            <button type="submit" class="filter_button"><span>filter</span></button>
                        </form>
                    </div>
                </form>
            </div>

            <!-- Main Content -->
            <div class="main_content">
                <div class="product_sorting_container product_sorting_container_top">
                    <ul class="product_sorting">
                        <li>
                            <span class="type_sorting_text">Default Sorting</span>
                            <i class="fa fa-angle-down"></i>
                            <ul class="sorting_type">
                                <li class="type_sorting_btn" data-isotope-option='{ "sortBy": "original-order" }'><span>Default Sorting</span></li>
                                <li class="type_sorting_btn" data-isotope-option='{ "sortBy": "price" }'><span>Price</span></li>
                                <li class="type_sorting_btn" data-isotope-option='{ "sortBy": "name" }'><span>Product Name</span></li>
                            </ul>
                        </li>
                        <li>
                            <span>Show</span>
                            <span class="num_sorting_text">6</span>
                            <i class="fa fa-angle-down"></i>
                            <ul class="sorting_num">
                                <li class="num_sorting_btn"><span>6</span></li>
                                <li class="num_sorting_btn"><span>12</span></li>
                                <li class="num_sorting_btn"><span>24</span></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="pages d-flex flex-row align-items-center">
                        <div class="page_current">
                            <span>{{ $products->currentPage() }}</span>
                            <ul class="page_selection">
                                @for ($page = 1; $page <= $products->lastPage(); $page++)
                                    <li><a href="{{ $products->url($page) }}">{{ $page }}</a></li>
                                @endfor
                            </ul>
                        </div>
                        <div class="page_total"><span>of</span> {{ $products->lastPage() }}</div>
                        
                        @if ($products->hasMorePages())
                            <div id="next_page" class="page_next">
                                <a href="{{ $products->nextPageUrl() }}"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        @else
                            <div id="next_page" class="page_next disabled">
                                <a href="#"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Products -->
                @if (count($products)>0)
                    <div class="products_iso">
                        <div class="row">
                            <div class="col">
                                <div class="product-grid">
                                    @foreach($products as $product)
                                        @php
                                        $discountPercentage = 0;
                                        if ($product->price > 0 && $product->discounted_price > 0) {
                                            $discountPercentage = (($product->price - $product->discounted_price) / $product->price) * 100;
                                        }
                                        @endphp
                                        <div class="product-item {{ $product->category->name }} {{ $product->quantity <= 0 ? 'out-of-stock' : '' }}">
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

                                <div class="pages d-flex flex-row align-items-center">
                                    <div class="page_current">
                                        <span>{{ $products->currentPage() }}</span>
                                        <ul class="page_selection">
                                            @for ($page = 1; $page <= $products->lastPage(); $page++)
                                                <li><a href="{{ $products->url($page) }}">{{ $page }}</a></li>
                                            @endfor
                                        </ul>
                                    </div>
                                    <div class="page_total"><span>of</span> {{ $products->lastPage() }}</div>
                                
                                    @if ($products->hasMorePages())
                                        <div id="next_page_1" class="page_next">
                                            <a href="{{ $products->nextPageUrl() }}"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                        </div>
                                    @else
                                        <div id="next_page_1" class="page_next disabled">
                                            <a href="#"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                        </div>
                                    @endif
                                </div>                            
                            </div>
                        </div>
                    </div>
                @else
                    <div class="no-results-container">
                        <h3 class="no-results-heading">
                            No results found for 
                            <span class="highlight-text">'{{ $search }}'</span>
                        </h3>
                        <p class="no-results-message">
                            Try adjusting your search or filter to find what you're looking for.
                        </p>
                    </div>                                       
                @endif
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

    window.minPrice = @json($minPrice ?? 0);
    window.maxPrice = @json($maxPrice ?? 580);
</script>
@endsection