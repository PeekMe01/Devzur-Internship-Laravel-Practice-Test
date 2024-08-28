<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="index.html" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>Real</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="{{ Auth::user()->profile_photo_path ? Auth::user()->profile_photo_path : asset('photos/1724069346-nopfp.png') }}" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                <span>Admin</span>
            </div>
        </div>
        <div class="navbar-nav w-100 gap-1">
            <a href="{{ route('adminDashboard') }}" class="nav-item nav-link {{ in_array(Route::currentRouteName(), ['adminDashboard']) ? 'active' : '' }}">
                <i class="fa fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a href="{{ route('adminUsers') }}" class="nav-item nav-link {{ in_array(Route::currentRouteName(), ['adminUsers']) ? 'active' : '' }}">
                <i class="fa fa-user me-2"></i>Users
            </a>
            <a href="{{ route('adminProducts') }}" class="nav-item nav-link {{ in_array(Route::currentRouteName(), ['adminProducts', 'addProductForm','viewProduct', 'editProductForm']) ? 'active' : '' }}">
                <i class="fa fa-box me-2"></i>Products
            </a>
            <a href="{{ route('adminCategories') }}" class="nav-item nav-link {{ in_array(Route::currentRouteName(), ['adminCategories', 'addCategoryForm', 'editCategoryForm']) ? 'active' : '' }}">
                <i class="fa fa-list-alt me-2"></i>Categories
            </a>
            <a href="{{ route('adminOrders') }}" class="nav-item nav-link {{ in_array(Route::currentRouteName(), ['adminOrders', 'orderDetails']) ? 'active' : '' }}">
                <i class="fa fa-shopping-cart me-2"></i>Orders
            </a>
            <a href="{{ route('adminPayments') }}" class="nav-item nav-link {{ in_array(Route::currentRouteName(), ['adminPayments']) ? 'active' : '' }}">
                <i class="fa fa-credit-card me-2"></i>Payments
            </a>
        </div>                  
    </nav>
</div>
