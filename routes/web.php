<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticlesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ArticlesController::class, 'index'])->middleware(['auth'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/articles/show/{id}', [ArticlesController::class, 'show'])->name('articles.show');

    // Cart 
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

    // Payment
    Route::post('/payment/checkout', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');

    // Order
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [\App\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/orders/{id}', [\App\Http\Controllers\OrderController::class, 'destroy'])->name('orders.destroy');

    // Admin - Users
    Route::get('/admin/users', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.users.index');
    Route::patch('/admin/users/{id}/role', [\App\Http\Controllers\UserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/admin/users/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('admin.users.destroy');

    // Categorie admin
    Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [\App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories/create', [\App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/edit/{id}', [\App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('/categories/update', [\App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::get('/categories/delete/{id}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/articles/create', [ArticlesController::class, 'create'])->name('articles.create');
    Route::post('/articles/create', [ArticlesController::class, 'post'])->name('articles.post');
    Route::get('/articles/edit/{id}', [ArticlesController::class, 'edit'])->name('articles.edit');
    Route::post('/articles/update', [ArticlesController::class, 'update'])->name('articles.update');
    Route::get('/articles/delete/{id}', [ArticlesController::class, 'delete'])->name('articles.delete');

});

require __DIR__.'/auth.php';
