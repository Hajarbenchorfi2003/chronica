<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
});
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
use App\Http\Controllers\AdminController;

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.createUser');
    Route::post('/admin/users/create', [AdminController::class, 'createUser']);
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.editUser');
    Route::post('/admin/users/{id}/edit', [AdminController::class, 'editUser']);
    Route::get('/admin/users/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::get('/admin/articles', [AdminController::class, 'articles'])->name('admin.articles');
    Route::get('/admin/comments', [AdminController::class, 'comments'])->name('admin.comments');
});
