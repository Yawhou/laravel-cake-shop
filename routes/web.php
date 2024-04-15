<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'loginProcess'])->name('login');
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'loginPage'])->name('loginClick');
    Route::get('/register', [App\Http\Controllers\Auth\LoginController::class, 'registerPage'])->name('registerClick');
    Route::post('/register', [App\Http\Controllers\Auth\LoginController::class, 'registerProcess'])->name('register');
});
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::get('/welcome', [App\Http\Controllers\Auth\LoginController::class, 'showDash'])->name('dashboard');
Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showDash'])->name('dashboard2');


/*
|--------------------------------------------------------------------------
| About Us Route
|--------------------------------------------------------------------------
*/
Route::get('/about-us', [App\Http\Controllers\Auth\LoginController::class, 'aboutus'])->name('aboutus');

/*
|--------------------------------------------------------------------------
| User Activation Route
|--------------------------------------------------------------------------
*/
Route::get('/activate/{token}', [App\Http\Controllers\Auth\LoginController::class, 'activate'])->name('activate');

/*
|--------------------------------------------------------------------------
| Category Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/categories/add', [App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');
});
Route::get('/categories',[App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{id}',[App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/add', [App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
});
Route::get('/shop',[App\Http\Controllers\ProductController::class, 'productShop'])->name('products.productshop');
Route::get('/products/{id}',[App\Http\Controllers\ProductController::class, 'show'])->name('products.show');


/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/cart', [App\Http\Controllers\Cart\CartController::class, 'show'])->name('cart.show');
    Route::post('/cart/buynow', [App\Http\Controllers\Cart\CartController::class, 'buynow'])->name('cart.buynow');
    Route::get('/cart/remove', [App\Http\Controllers\Cart\CartController::class, 'update'])->name('cart.remove');
    Route::get('/checkout', [App\Http\Controllers\Cart\CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart', [App\Http\Controllers\Cart\CartController::class, 'addToCart'])->name('cart.addToCart');
});


/*
|--------------------------------------------------------------------------
| Order Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/order/history', [App\Http\Controllers\Order\OrderController::class, 'index'])->name('orders.index');
    Route::delete('/order/{id}', [App\Http\Controllers\Order\OrderController::class, 'destroy'])->name('orders.destroy');
});
Route::post('/order', [App\Http\Controllers\Cart\CartController::class, 'processOrder'])->middleware('auth')->name('order');

