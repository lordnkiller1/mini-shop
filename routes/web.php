<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashbordController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {

    route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:view_dashboard');
    Route::get('categories/trash', [CategoryController::class, 'trash'])
        ->name('categories.trash');


    Route::delete('/cart/item/{cartItem}', [CartController::class, 'remove'])
        ->name('cart.remove');

    Route::put('/cart/item/{cartItem}', [CartController::class, 'update'])
        ->name('cart.update');
    Route::delete('/cart/clear', [CartController::class, 'clear'])
        ->name('cart.clear');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])
        ->name('cart.add');
    Route::post('/checkout', [OrderController::class, 'checkout'])
        ->name('checkout');
    Route::patch('categories/{category}/restore', [CategoryController::class, 'restore'])
        ->withTrashed()
        ->name('categories.restore');

    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('products', ProductController::class)->except('show');
    Route::resource('users', UserController::class)->except('show');
    Route::resource('products.comments', CommentController::class)->except('show')->scoped()->only(['index', 'store', 'update', 'destroy']);
});

require __DIR__ . '/auth.php';
