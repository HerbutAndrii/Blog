<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\GitHubController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/', [PostController::class, 'index'])->name('post.index');
    Route::get('/my-posts', [PostController::class, 'userIndex'])->name('post.user.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/posts/store', [PostController::class, 'store'])->name('post.store');
    Route::get('/posts/edit/{post}', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/posts/update/{post}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/posts/delete/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::get('/posts/details/{post}', [PostController::class, 'show'])->name('post.show');
    Route::post('/posts/like/{post}', [PostLikeController::class, 'like'])->name('post.like');
    Route::delete('/posts/unlike/{post}', [PostLikeController::class, 'unlike'])->name('post.unlike');

    Route::post('/comments/store/{post}', [CommentController::class, 'store'])->name('comment.store');
    Route::put('/comments/update/{comment}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/comments/delete/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');
    Route::post('/comments/like/{comment}', [CommentLikeController::class, 'like'])->name('comment.like');
    Route::delete('/comments/unlike/{comment}', [CommentLikeController::class, 'unlike'])->name('comment.unlike');

    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/categories/show/{category}', [CategoryController::class, 'show'])->name('category.show');
    
    Route::get('/tags', [TagController::class, 'index'])->name('tag.index');
    Route::post('/tags/store', [TagController::class, 'store'])->name('tag.store');
    Route::get('/tags/show/{tag}', [TagController::class, 'show'])->name('tag.show');
    
    Route::get('/users/show/{user}', [UserController::class, 'show'])->name('user.show');

    Route::any('/search', SearchController::class)->name('search');
}); 

Route::middleware(['admin', 'auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::get('/users', [AdminController::class, 'users'])->name('user.show');
    Route::delete('users/delete/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    
    Route::get('/posts', [AdminController::class, 'posts'])->name('post.show');
    Route::get('/posts/edit/{post}', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/posts/update/{post}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/posts/delete/{post}', [PostController::class, 'destroy'])->name('post.destroy');

    Route::get('/categories', [AdminController::class, 'categories'])->name('category.show');
    Route::get('/categories/edit/{category}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/categories/update/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/categories/delete/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::get('/tags', [AdminController::class, 'tags'])->name('tag.show');
    Route::get('/tags/edit/{tag}', [TagController::class, 'edit'])->name('tag.edit');
    Route::put('/tags/update/{tag}', [TagController::class, 'update'])->name('tag.update');
    Route::delete('/tags/delete/{tag}', [TagController::class, 'destroy'])->name('tag.destroy');

    Route::get('/comments', [AdminController::class, 'comments'])->name('comment.show');
    Route::get('/comments/edit/{comment}', [CommentController::class, 'edit'])->name('comment.edit');
    Route::put('/comments/update/{comment}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/comments/delete/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');
});

Route::post('/logout', [AuthController::class, 'logout'])
            ->middleware('auth')
            ->name('auth.logout');

Route::get('/login', [AuthController::class, 'loginView'])->name('auth.loginView');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/register', [AuthController::class, 'registerView'])->name('auth.registerView');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

Route::get('/email/verify', [EmailVerificationController::class , 'notice'])
            ->middleware('auth')
            ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
            ->middleware(['auth', 'signed'])
            ->name('verification.verify');
            
Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
            ->middleware(['auth', 'throttle:6,1'])
            ->name('verification.send');

Route::get('/forgot-password', [ResetPasswordController::class, 'request'])->name('password.request');
Route::post('/forgot-password', [ResetPasswordController::class, 'email'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'reset'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'update'])->name('password.update');

Route::get('/auth/redirect', [GitHubController::class, 'gitHubRedirect'])->name('auth.github.redirect');
Route::get('/auth/callback', [GitHubController::class, 'gitHubCallback'])->name('auth.github.callback');