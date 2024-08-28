<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\UserProductController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckCartCount;
use App\Http\Middleware\CheckCheckoutData;
use App\Http\Middleware\CheckLoggedIn;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);

// NO LOGIN NEEDED // NO MIDDLEWARE
// Home Page
Route::get('/index', [HomeController::class, 'index'])->name('home');

// Shop Page
Route::get('/shop', [UserProductController::class, 'index'])->name('shop');

// Promotion Page
Route::get('/promotion', [PromotionController::class, 'index'])->name('promotion');

// Contect Page
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/send', [ContactController::class, 'sendContactForm'])->name('contact.send');

// Product Details Page
Route::get('/products/{category_name}/{product_id}', [UserProductController::class, 'viewProduct'])->name('viewProductUser');


// Authentication REQUIRED!
Route::middleware([CheckLoggedIn::class])->group(function () {
    // View all orders
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.view');
    // View order details
    Route::get('/order/{order_id}', [UserOrderController::class, 'showOrderDetails'])->name('order.details');
    // Delete order
    Route::delete('/order/{order_id}', [UserOrderController::class, 'cancelOrder'])->name('order.cancel');

    // Add to cart
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    // View cart
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
    // Increase / Decrease product quantity in cart
    Route::post('/cart/increase/{product_id}', [CartController::class, 'increaseQuantity'])->name('cart.increase');
    Route::post('/cart/decrease/{product_id}', [CartController::class, 'decreaseQuantity'])->name('cart.decrease');
    // Remove product from cart
    Route::delete('/cart/remove/{product_id}', [CartController::class, 'remove'])->name('cart.remove');

    // Go to checkout page with everything in the current cart // can't be bypassed, CheckCartCount MIDDLEWARE (if nothing in cart go back to shopping)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware(CheckCartCount::class);
    // Process the checkout
    Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process')->middleware(CheckCartCount::class);

    // Go to the stripe payment // can't be bypassed, CheckCheckoutData MIDDLEWARE (if session doesn't have "checkout data" you go back to checkout page)
    Route::post('/checkout/payment', [CheckoutController::class, 'handlePayment'])->name('checkout.handlePayment')->middleware(CheckCheckoutData::class);
});

Route::middleware([CheckAdmin::class])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('adminDashboard');

    // Users
    Route::get('/admin/users', [UserController::class, 'index'])->name('adminUsers');

    // products CRUD
    Route::get('/admin/products', [ProductController::class, 'index'])->name('adminProducts');
    Route::get('/admin/products/view_product/{product_id}', [ProductController::class, 'viewProduct'])->name('viewProduct');

    Route::get('/admin/products/add', [ProductController::class, 'addProductForm'])->name('addProductForm');
    Route::post('/admin/products/add', [ProductController::class, 'addProduct'])->name('addProduct');

    Route::get('/admin/products/edit_product/{product_id}', [ProductController::class, 'editProductForm'])->name('editProductForm');
    Route::put('/admin/products/edit_product/{product_id}', [ProductController::class, 'editProduct'])->name('editProduct');
    
    Route::delete('/admin/products/delete/{product_id}', [ProductController::class, 'deleteProduct'])->name('deleteProduct');

    // categories CRUD
    Route::get('/admin/categories/add', [CategoryController::class, 'addCategoryForm'])->name('addCategoryForm');
    Route::post('/admin/categories/add', [CategoryController::class, 'addCategory'])->name('addCategory');

    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('adminCategories');

    Route::get('/admin/categories/edit_category/{category_id}', [CategoryController::class, 'editCategoryForm'])->name('editCategoryForm');
    Route::put('/admin/categories/edit_category/{category_id}', [CategoryController::class, 'editCategory'])->name('editCategory');

    Route::delete('/admin/categories/delete/{category_id}', [CategoryController::class, 'deleteCategory'])->name('deleteCategory');

    // order CRUD
    // Create "USER SIDE"

    Route::get('/admin/orders', [OrderController::class, 'index'])->name('adminOrders');
    Route::get('/admin/orders/{order_id}', [OrderController::class, 'orderDetails'])->name('orderDetails');

    Route::put('/admin/orders/{order_id}/markOrderDelivered', [OrderController::class, 'markOrderDelivered'])->name('markOrderDelivered');
    Route::put('/admin/orders/{order_id}/markOrderPending', [OrderController::class, 'markOrderPending'])->name('markOrderPending');
    Route::put('/admin/orders/{order_id}/markPaymentPaid', [OrderController::class, 'markPaymentPaid'])->name('markPaymentPaid');
    Route::put('/admin/orders/{order_id}/markPaymentPending', [OrderController::class, 'markPaymentPending'])->name('markPaymentPending');

    Route::delete('/admin/orders/{order_id}/deleteOrder', [OrderController::class, 'cancelOrder'])->name('cancelOrder');

    // Payment
    Route::get('/admin/payments', [PaymentController::class, 'index'])->name('adminPayments');

});