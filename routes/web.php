<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
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

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/', [PostController::class, 'index'])->name('post.index');
    Route::get('/my-posts', [PostController::class, 'userIndex'])->name('post.user.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/posts/store', [PostController::class, 'store'])->name('post.store');
    Route::get('/posts/edit/{post}', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/posts/update/{post}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/posts/delete/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::get('/posts/details/{post}', [PostController::class, 'show'])->name('post.show');
   
    Route::post('/posts/like/{post}', [LikeController::class, 'like'])->name('post.like');
    Route::delete('/posts/unlike/{post}', [LikeController::class, 'unlike'])->name('post.unlike');

    Route::any('/categories/store', [CategoryController::class, 'store'])->name('category.store');
    Route::any('/tags/store', [TagController::class, 'store'])->name('tag.store');
    
    Route::post('/comments/store/{post}', [CommentController::class, 'store'])->name('comment.store');
    Route::get('/comments/edit/{comment}', [CommentController::class, 'edit'])->name('comment.edit');
    Route::put('/comments/update/{comment}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/comments/delete/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');

    Route::get('/tags/show/{tag}', [TagController::class, 'show'])->name('tag.show');
    Route::get('/categories/show/{category}', [CategoryController::class, 'show'])->name('category.show');

    Route::post('/search', SearchController::class)->name('search');
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