<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\KtmController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;

Route::get('/welcome', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

Route::get('/ktm-live-schedule', [App\Http\Controllers\KtmController::class, 'show'])->name('ktm.live_schedule');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');



Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth')->name('profile');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/updateProfile', [ProfileController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/updateUsername', [ProfileController::class, 'updateUsername'])->name('updateUsername');
    Route::post('/updatePassword', [ProfileController::class, 'updatePassword'])->name('updatePassword');
    Route::post('/submitReview', [ProfileController::class, 'submitReview'])->name('submitReview');
});




