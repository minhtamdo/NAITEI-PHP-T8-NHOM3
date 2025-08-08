<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route mặc định của master
Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// Group route có middleware auth, verified
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard layout-user
    Route::get('/dashboard-layout-user', function () {
        return Inertia::render('dashboard');
    })->name('dashboard.layout');

    // Các route layout-user
    Route::get('/home-layout-user', [HomeController::class, 'index'])->name('home.layout');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/products/{id}/review', [ProductController::class, 'addReview'])->name('products.review');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{index}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{index}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout.process');

    Route::get('/orders/track', [OrderController::class, 'trackOrders'])->name('orders.track');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
}); // <- đóng group tại đây

// Route mặc định master cho trang Welcome
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard của master
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes của master
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
