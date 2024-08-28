<!-- Header -->

<header class="header trans_300">

    <!-- Top Navigation -->

    <div class="top_nav">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="top_nav_left">free shipping on all u.s orders over $50</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->

    <div class="main_nav_container">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-right">
                    <div class="logo_container">
                        <a href="#">colo<span>shop</span></a>
                    </div>
                    <nav class="navbar">
                        <ul class="navbar_menu">
                            <li><a href="{{ route('home') }}">home</a></li>
                            <li><a href="{{ route('shop') }}">shop</a></li>
                            <li><a href="{{ route('promotion') }}">promotion</a></li>
                            <li><a href="{{ route('orders.view') }}">orders</a></li>
                            <li><a href="{{ route('contact') }}">contact</a></li>
                            @auth
                                <li><a href="{{ route('profile.show') }}">My Profile</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                                    </form>
                                </li>
                            @else
                                <li><a href="{{ route('login') }}">Login</a></li>
                            @endauth                         
                        </ul>                        
                        <ul class="navbar_user">
                            <li><a href="{{ route('shop') }}"><i class="fa fa-search" aria-hidden="true"></i></a></li>
                            <li class="checkout">
                                <a href="{{ route('cart.view') }}">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    <span id="checkout_items" class="checkout_items">
                                        @auth
                                            {{ Auth::user()->cart->products->count() }}
                                        @else
                                            0
                                        @endauth
                                    </span>
                                </a>                                
                            </li>
                        </ul>
                        <div class="hamburger_container">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

</header>

<div class="fs_menu_overlay"></div>
<div class="hamburger_menu">
    <div class="hamburger_close"><i class="fa fa-times" aria-hidden="true"></i></div>
    <div class="hamburger_menu_content text-right">
        <ul class="menu_top_nav">
            <li class="menu_item"><a href="{{ route('home') }}">home</a></li>
            <li class="menu_item"><a href="{{ route('shop') }}">shop</a></li>
            <li class="menu_item"><a href="{{ route('promotion') }}">promotion</a></li>
            <li class="menu_item"><a href="{{ route('orders.view') }}">orders</a></li>
            <li class="menu_item"><a href="{{ route('contact') }}">contact</a></li>
            @auth
            @else
                <li class="menu_item">
                    <a href="#">
                        
                            Guest
                        @endauth
                    </a> 
                </li>
            @auth
                <li class="menu_item has-children">
                    <a href="#">
                        {{ Auth::user()->name }}
                        <i class="fa fa-angle-down"></i>
                    </a>              
                    <ul class="menu_selection">
                        <li><a href="{{ route('profile.show') }}" class="dropdown-item">My Profile</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="route('logout')" class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
            @endauth
        </ul>
    </div>
</div>