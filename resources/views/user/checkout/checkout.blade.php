@extends('layouts.user')

@section('title', 'Checkout')

@section('content')
<style>
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
    #map {
        height: 400px;
        width: 100%;
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

            <h1>Checkout</h1>
            
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                
                <!-- Cart Summary -->
                <div class="cart_summary mb-4">
                    <h3>Cart Summary</h3>
                    <ul>
                        @foreach($cart->products as $product)
                        <li>{{ $product->name }}: {{ $product->pivot->quantity }} x ${{ number_format($product->price, 2) }}</li>
                        @endforeach
                    </ul>
                    <p>Total: ${{ number_format($cart->products->sum(fn($product) => $product->price * $product->pivot->quantity), 2) }}</p>
                </div>
                
                <!-- Shipping Information -->
                <h3>Shipping Information</h3>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input placeholder="First Name..." type="text" name="first_name" id="first_name" class="form-control" required value="{{ old('first_name', $latestOrder->first_name ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input placeholder="Last Name..." type="text" name="last_name" id="last_name" class="form-control" required value="{{ old('last_name', $latestOrder->last_name ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input placeholder="Email..." disabled type="email" name="email" id="email" class="form-control" value="{{ Auth::user()->email }}" required>
                    <input hidden placeholder="Email..." type="email" name="email" id="email" class="form-control" value="{{ Auth::user()->email }}" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input placeholder="Phone Number..." type="tel" name="phone" id="phone" class="form-control" required value="{{ old('phone', $latestOrder->phone ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="address">Shipping Address</label>
                    <textarea placeholder="Shipping Address..." name="address" id="address" class="form-control" required>{{ old('address', $latestOrder->address ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="postal_code">Postal Code</label>
                    <input placeholder="Postal Code..." type="text" name="postal_code" id="postal_code" class="form-control" required value="{{ old('postal_code', $latestOrder->postal_code ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input placeholder="City..." type="text" name="city" id="city" class="form-control" required value="{{ old('city', $latestOrder->city ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input placeholder="Country..." type="text" name="country" id="country" class="form-control" required value="{{ old('country', $latestOrder->country ?? '') }}">
                </div>

                <!-- Location -->
                <div class="form-group">
                    <label for="location">Select Location</label>
                    <div id="map"></div>
                    <input type="hidden" name="location_lat" id="location_lat" value="{{ old('location_lat', $latestOrder->location_lat ?? '') }}">
                    <input type="hidden" name="location_lng" id="location_lng" value="{{ old('location_lng', $latestOrder->location_lng ?? '') }}">
                    <button type="button" id="get-location" class="btn btn-primary mt-2" style="background-color: #fe4c50; border-width:0;">Get My Location</button>
                </div>
                
                <!-- Payment Method -->
                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-control" required>
                        <option value="cash_on_delivery" {{ (old('payment_method', $latestOrder->payment_method ?? '') == 'cash_on_delivery') ? 'selected' : '' }}>Cash On Delivery</option>
                        <option value="credit_card" {{ (old('payment_method', $latestOrder->payment_method ?? '') == 'credit_card') ? 'selected' : '' }}>Credit Card</option>
                        <!-- Add other payment methods as needed -->
                    </select>
                </div>
                
                <button type="submit" class="checkout_button">Place Order</button>
            </form>
        </div>
    </div>
</div>
{{-- <script>
    let map;
    let marker;
    let geocoder;

    function initMap() {
        geocoder = new google.maps.Geocoder();
        const initialLocation = { lat: {{ $latestOrder->location_lat }}, lng: {{ $latestOrder->location_lng }} }; // Default location (e.g., Sydney)

        map = new google.maps.Map(document.getElementById("map"), {
            center: initialLocation,
            zoom: 15,
            mapId: 'DEMO_MAP_ID',
        });

        marker = new google.maps.marker.AdvancedMarkerElement({
            position: initialLocation,
            map: map,
            draggable: true,
        });

        marker.addListener('position_changed', () => {
            const position = marker.position;
            document.getElementById('location_lat').value = position.lat();
            document.getElementById('location_lng').value = position.lng();
        });
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const location = { lat, lng };

                map.setCenter(location);
                marker.position = location;
                document.getElementById('location_lat').value = lat;
                document.getElementById('location_lng').value = lng;
            }, function() {
                alert("Geolocation service failed.");
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    document.getElementById('get-location').addEventListener('click', getLocation);

    window.onload = initMap;
</script> --}}
<script>
    let map;
    let marker;
    let geocoder;

    function initMap() {
        geocoder = new google.maps.Geocoder();
        // Set default values for latitude and longitude
        const defaultLat = 37.7749; // Default latitude (e.g., San Francisco)
        const defaultLng = -122.4194; // Default longitude (e.g., San Francisco)

        // Get values from server-side variables or use defaults
        const initialLat = {{ $latestOrder->location_lat ?? 'defaultLat' }};
        const initialLng = {{ $latestOrder->location_lng ?? 'defaultLng' }};

        const initialLocation = {
            lat: initialLat || defaultLat,
            lng: initialLng || defaultLng
        };
        
        map = new google.maps.Map(document.getElementById("map"), {
            center: initialLocation,
            zoom: 15,
            mapId: 'DEMO_MAP_ID',
        });

        marker = new google.maps.Marker({
            position: initialLocation,
            map: map,
            draggable: true, // Make the marker draggable
        });

        // Update latitude and longitude inputs when the marker is dragged
        marker.addListener('dragend', () => {
            const position = marker.getPosition();
            document.getElementById('location_lat').value = position.lat();
            document.getElementById('location_lng').value = position.lng();
        });
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const location = { lat, lng };

                map.setCenter(location);
                marker.setPosition(location); // Update marker position

                document.getElementById('location_lat').value = lat;
                document.getElementById('location_lng').value = lng;
            }, function() {
                alert("Geolocation service failed.");
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    document.getElementById('get-location').addEventListener('click', getLocation);

    window.onload = initMap;
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxWvBQQy-KxHaFkQXbo0dwPiyRy-rHTP8&libraries=places,marker&callback=initMap&solution_channel=GMP_QB_addressselection_v2_cABC" async defer></script>
@endsection