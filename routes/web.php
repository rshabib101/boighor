<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminAuthorController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminWithdrawalController;
use App\Http\Controllers\Admin\AdminSettingController;

// ========================
// PUBLIC ROUTES
// ========================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Books
Route::prefix('books')->name('books.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    Route::get('/{slug}', [BookController::class, 'show'])->name('show');
    Route::get('/{slug}/read', [BookController::class, 'read'])->name('read');
    Route::get('/{slug}/download', [BookController::class, 'download'])->name('download');
    Route::post('/{slug}/review', [BookController::class, 'storeReview'])->name('review');
    Route::post('/{slug}/favorite', [BookController::class, 'toggleFavorite'])->name('favorite');
    Route::post('/{slug}/bookmark', [BookController::class, 'toggleBookmark'])->name('bookmark');
});

// Categories
Route::prefix('category')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/{slug}', [CategoryController::class, 'show'])->name('show');
});

// Authors
Route::prefix('authors')->name('authors.')->group(function () {
    Route::get('/', [AuthorController::class, 'index'])->name('index');
    Route::get('/{slug}', [AuthorController::class, 'show'])->name('show');
});

// ========================
// AUTH ROUTES
// ========================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ========================
// USER ROUTES (auth required)
// ========================
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [UserController::class, 'changePassword'])->name('password.update');
    Route::get('/downloads', [UserController::class, 'downloadHistory'])->name('history');
    Route::get('/favorites', [UserController::class, 'favorites'])->name('favorites');
    Route::get('/bookmarks', [UserController::class, 'bookmarks'])->name('bookmarks');
    Route::get('/wallet', [UserController::class, 'wallet'])->name('wallet');
    Route::post('/withdraw', [UserController::class, 'submitWithdrawal'])->name('withdraw');
    Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications');
    Route::post('/ad-watch', [UserController::class, 'adWatch'])->name('ad-watch');
});

// ========================
// ADMIN ROUTES
// ========================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Books
    Route::resource('books', AdminBookController::class);

    // Categories
    Route::resource('categories', AdminCategoryController::class);

    // Authors
    Route::resource('authors', AdminAuthorController::class);

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/adjust-points', [AdminUserController::class, 'adjustPoints'])->name('users.adjust-points');
    Route::post('/users/{user}/notify', [AdminUserController::class, 'sendNotification'])->name('users.notify');

    // Withdrawals
    Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');

    // Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::get('/settings/ads', [AdminSettingController::class, 'ads'])->name('settings.ads');
    Route::post('/settings/ads', [AdminSettingController::class, 'storeAd'])->name('settings.ads.store');
    Route::put('/settings/ads/{advertisement}', [AdminSettingController::class, 'updateAd'])->name('settings.ads.update');
    Route::delete('/settings/ads/{advertisement}', [AdminSettingController::class, 'destroyAd'])->name('settings.ads.destroy');
    Route::post('/settings/notify-all', [AdminSettingController::class, 'sendBulkNotification'])->name('settings.notify-all');
});
