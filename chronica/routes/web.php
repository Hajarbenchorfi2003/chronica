<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AdminController;
 
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', function () {
    return view('contact');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
}); 
 


Route::get('/articles/search', [ArticleController::class, 'search'])->name('articles.search');
Route::get('/blog', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::middleware(['auth'])->group(function () {
    Route::get('/blog/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/blog', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/blog/{slug}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/blog/{slug}', [ArticleController::class, 'update'])->name('articles.update');
});


Route::get('/category/{slug}', [ArticleController::class, 'articlesByCategory'])->name('articles.byCategory');

Route::get('/tag/{slug}', [ArticleController::class, 'articlesByTag'])->name('articles.byTag');


Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');

Route::post('/articles/{article}/like', [ArticleController::class, 'like'])->name('articles.like');


/* Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.createUser');
    Route::post('/admin/users/create', [AdminController::class, 'createUser']);
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.editUser');
    Route::post('/admin/users/{id}/edit', [AdminController::class, 'editUser']);
    Route::get('/admin/users/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::get('/admin/articles', [AdminController::class, 'articles'])->name('admin.articles');
    Route::get('/admin/comments', [AdminController::class, 'comments'])->name('admin.comments');
}); */
// Route::get('/articles', [ArticleController::class, 'index'])->name('admin.articles.index');
// Route::get('/articles/create', [ArticleController::class, 'create'])->name('admin.articles.create');
// Route::post('/articles', [ArticleController::class, 'store'])->name('admin.articles.store');
// Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('admin.articles.edit');
// Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('admin.articles.update');
// Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('admin.articles.destroy');


// ✅ Vérifie si cette route existe
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('comments', CommentController::class);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    // Gestion des utilisateurs
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.createUser');
    Route::post('/admin/users/create', [AdminController::class, 'createUser']);
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.editUser');
    Route::post('/admin/users/{id}/edit', [AdminController::class, 'editUser']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');

    // Gestion des articles
    Route::get('/admin/articles', [AdminController::class, 'articles'])->name('admin.articles');

    // Gestion des commentaires
    Route::get('/admin/comments', [AdminController::class, 'comments'])->name('admin.comments');
    

});
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('categories', CategoryController::class);
});


use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
 
 

 
