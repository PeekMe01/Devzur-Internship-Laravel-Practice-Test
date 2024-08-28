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

// Route::get('/index', function () {return view('user/index');})->name('home');
Route::get('/index', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [UserProductController::class, 'index'])->name('shop');
Route::get('/promotion', [PromotionController::class, 'index'])->name('promotion');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/send', [ContactController::class, 'sendContactForm'])->name('contact.send');
Route::get('/products/{category_name}/{product_id}', [UserProductController::class, 'viewProduct'])->name('viewProductUser');

Route::middleware([CheckLoggedIn::class])->group(function () {
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.view');
    Route::get('/order/{order_id}', [UserOrderController::class, 'showOrderDetails'])->name('order.details');
    Route::delete('/order/{order_id}', [UserOrderController::class, 'cancelOrder'])->name('order.cancel');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
    Route::post('/cart/increase/{product_id}', [CartController::class, 'increaseQuantity'])->name('cart.increase');
    Route::post('/cart/decrease/{product_id}', [CartController::class, 'decreaseQuantity'])->name('cart.decrease');
    Route::delete('/cart/remove/{product_id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware(CheckCartCount::class);
    Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process')->middleware(CheckCartCount::class);
    Route::post('/checkout/payment', [CheckoutController::class, 'handlePayment'])->name('checkout.handlePayment')->middleware(CheckCheckoutData::class);
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/index', [HomeController::class, 'index'])->name('home');
// });

Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);

Route::middleware([CheckAdmin::class])->group(function () {
    // basic view getters
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('adminDashboard');

    // Users
    // Read
    Route::get('/admin/users', [UserController::class, 'index'])->name('adminUsers');

    // products CRUD
    // Create
    Route::get('/admin/products/add', [ProductController::class, 'addProductForm'])->name('addProductForm');
    Route::post('/admin/products/add', [ProductController::class, 'addProduct'])->name('addProduct');

    // Read
    Route::get('/admin/products', [ProductController::class, 'index'])->name('adminProducts');
    Route::get('/admin/products/view_product/{product_id}', [ProductController::class, 'viewProduct'])->name('viewProduct');

    // Update
    Route::get('/admin/products/edit_product/{product_id}', [ProductController::class, 'editProductForm'])->name('editProductForm');
    Route::put('/admin/products/edit_product/{product_id}', [ProductController::class, 'editProduct'])->name('editProduct');

    // Delete
    Route::delete('/admin/products/delete/{product_id}', [ProductController::class, 'deleteProduct'])->name('deleteProduct');

    // categories CRUD
    // Create
    Route::get('/admin/categories/add', [CategoryController::class, 'addCategoryForm'])->name('addCategoryForm');
    Route::post('/admin/categories/add', [CategoryController::class, 'addCategory'])->name('addCategory');

    //Read
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('adminCategories');

    // Update
    Route::get('/admin/categories/edit_category/{category_id}', [CategoryController::class, 'editCategoryForm'])->name('editCategoryForm');
    Route::put('/admin/categories/edit_category/{category_id}', [CategoryController::class, 'editCategory'])->name('editCategory');

    // Delete
    Route::delete('/admin/categories/delete/{category_id}', [CategoryController::class, 'deleteCategory'])->name('deleteCategory');

    // order CRUD
    // Create
    // USER SIDE

    // Read
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('adminOrders');
    Route::get('/admin/orders/{order_id}', [OrderController::class, 'orderDetails'])->name('orderDetails');

    // Update
    Route::put('/admin/orders/{order_id}/markOrderDelivered', [OrderController::class, 'markOrderDelivered'])->name('markOrderDelivered');
    Route::put('/admin/orders/{order_id}/markOrderPending', [OrderController::class, 'markOrderPending'])->name('markOrderPending');
    Route::put('/admin/orders/{order_id}/markPaymentPaid', [OrderController::class, 'markPaymentPaid'])->name('markPaymentPaid');
    Route::put('/admin/orders/{order_id}/markPaymentPending', [OrderController::class, 'markPaymentPending'])->name('markPaymentPending');

    // Delete
    Route::delete('/admin/orders/{order_id}/deleteOrder', [OrderController::class, 'cancelOrder'])->name('cancelOrder');

    // Payment
    // Read
    Route::get('/admin/payments', [PaymentController::class, 'index'])->name('adminPayments');

});