<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserActions;
use App\Http\Controllers\fallbackController;
use App\Http\Controllers\Views;


Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup');


Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/home', [Views::class, 'home'])->name('home');

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/profile/{id}', [Views::class, 'profile'])->name('profile');
Route::get('/people', [Views::class, 'people'])->name('people');
Route::get('/saved', [Views::class, 'saved'])->name('saved');
Route::get('/messages', [Views::class, 'messages'])->name('messages');
Route::get('/post/{id}', [Views::class, 'post'])->name('post');
Route::get('/settings',[Views::class, 'settings'])->name('settings');
Route::get('/notifications',[Views::class, 'notifications'])->name('notifications');


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::prefix('/auth')->group(function(){
    Route::post('signup', [AuthController::class, 'signup'])->name('auth/sign-up');
    Route::post('login', [AuthController::class, 'login'])->name('auth/login');
});

Route::prefix('/api')->group(function(){
    Route::post('follow', [UserActions::class, 'follow'])->name('api/follow');
    Route::post('unfollow', [UserActions::class, 'unfollow'])->name('api/unfollow');
    Route::post('block', [UserActions::class, 'block'])->name('api/block');
    Route::post('save-user-changes', [UserActions::class, 'saveChanges'])->name('api/save-user-changes');
    Route::put('make-post', [UserActions::class, 'makePost'])->name('api/make-post');
    Route::post('like-post', [UserActions::class, 'likePost'])->name('api/like-post');
    Route::post('save-post', [UserActions::class, 'savePost'])->name('api/save-post');
    Route::post('comment', [UserActions::class, 'comment'])->name('api/comment');
    Route::post('delete-post', [UserActions::class, 'deletePost'])->name('api/delete-post');
    Route::post('delete-comment', [UserActions::class, 'deleteComment'])->name('api/delete-comment');
    Route::post('like-comment', [UserActions::class, 'likeComment'])->name('api/like-comment');
});

Route::prefix('/user')->group(function(){
    Route::post('getFollowers', [UserActions::class, 'getFollowers'])->name('user/getFollowers');
});

Route::get('/search', [Views::class, 'search'])->name('search');
Route::get('/search/{term}', [Views::class, 'search'])->name('main-page-search');



Route::fallback(fallbackController::class);


