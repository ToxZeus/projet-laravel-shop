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

    Route::get('/articles/create', [ArticlesController::class, 'create'])->name('articles.create');
    Route::post('/articles/create', [ArticlesController::class, 'post'])->name('articles.post');
    Route::get('/articles/edit/{id}', [ArticlesController::class, 'edit'])->name('articles.edit');
    Route::post('/articles/update', [ArticlesController::class, 'update'])->name('articles.update');
    Route::get('/articles/delete/{id}', [ArticlesController::class, 'delete'])->name('articles.delete');

});

require __DIR__.'/auth.php';
