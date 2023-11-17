<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('auth/login', 'welcome')->name('welcome');

Route::middleware('auth')->group(function () {
    
    Route::get('/', [PostController::class, 'index'])->name('post.index');
    Route::get('/my-posts', [PostController::class, 'userIndex'])->name('post.user.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/posts/create', [PostController::class, 'store'])->name('post.store');
    Route::get('/posts/edit/{post}', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/posts/edit/{post}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/posts/delete/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::get('/posts/details/{post}', [PostController::class, 'show'])->name('post.show');
    Route::post('/categories/create', [CategoryController::class, 'store'])->name('category.store');
    
    Route::post('/comments/create/{post}', [CommentController::class, 'store'])->name('comment.store');
    Route::get('/comments/edit/{comment}', [CommentController::class, 'edit'])->name('comment.edit');
    Route::put('/comments/edit/{comment}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/comments/delete/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::get('/login', [AuthController::class, 'loginView'])->name('auth.loginView');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/register', [AuthController::class, 'registerView'])->name('auth.registerView');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
