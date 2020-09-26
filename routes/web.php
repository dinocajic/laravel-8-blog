<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::post('/posts',         [PostController::class, 'store'])->name('posts.store');
Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
