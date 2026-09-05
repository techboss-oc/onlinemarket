<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\PaymentSettingsController;
use App\Http\Controllers\Admin\MonetizationAdminController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ───────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ads (public)
Route::get('/ads/search',   [AdController::class, 'search'])->name('ads.search');
Route::get('/ads/{id}',     [AdController::class, 'show'])->name('ads.show')->where('id', '[0-9]+');

// Categories
Route::get('/categories',        [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Static Pages
Route::get('/about',        [StaticController::class, 'about'])->name('about');
Route::get('/contact',      [StaticController::class, 'contact'])->name('contact');
Route::get('/faq',          [StaticController::class, 'faq'])->name('faq');
Route::get('/safety-tips',  [StaticController::class, 'safetyTips'])->name('safety-tips');
Route::get('/privacy',      [StaticController::class, 'privacy'])->name('privacy');
Route::get('/terms',        [StaticController::class, 'terms'])->name('terms');
Route::get('/billing',      [StaticController::class, 'billing'])->name('billing');

// Payments Webhooks
Route::get('/payment/callback/{provider}', [PaymentController::class, 'callback'])->name('payment.callback');

// ─── Authenticated Routes ─────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    
    // Payment Checkout
    Route::post('/payment/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');

    // Buyer Dashboard
    Route::get('/dashboard', [BuyerController::class, 'dashboard'])->name('buyer.dashboard');

    // Marketplace Interactions
    Route::post('/offers', [OfferController::class, 'store'])->name('offers.store');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::post('/blocks', [BlockController::class, 'store'])->name('blocks.store');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Profile
    Route::get('/profile',           [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::get('/notifications', [StaticController::class, 'notifications'])->name('notifications');

    // Favorites
    Route::get('/favorites',         [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Chat
    Route::get('/chat',              [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{chatId}',     [ChatController::class, 'show'])->name('chat.show')->where('chatId', '[0-9]+');
    Route::post('/chat/start',       [ChatController::class, 'start'])->name('chat.start');
    Route::post('/chat/{chatId}/send', [ChatController::class, 'send'])->name('chat.send')->where('chatId', '[0-9]+');

    // Post Ad
    Route::get('/ads/create',  [AdController::class, 'create'])->name('ads.create');
    Route::post('/ads',        [AdController::class, 'store'])->name('ads.store');
    Route::delete('/ads/{id}', [AdController::class, 'destroy'])->name('ads.destroy')->where('id', '[0-9]+');

    // ── Seller Routes ─────────────────────────────────────────────────────────
    Route::middleware('role:seller,admin')->group(function () {
        Route::get('/seller/dashboard', [SellerController::class, 'dashboard'])->name('seller.dashboard');
        Route::get('/seller/ads',       [SellerController::class, 'myAds'])->name('seller.ads');
        
        // Monetization
        Route::get('/seller/ads/{id}/promote', [SellerController::class, 'promote'])->name('seller.promote');
        Route::post('/seller/ads/{id}/promote', [SellerController::class, 'initPromotion'])->name('seller.promote.init');
        
        // PPC Campaigns
        Route::get('/seller/campaigns', [SellerController::class, 'campaigns'])->name('seller.campaigns');
        Route::post('/seller/campaigns', [SellerController::class, 'storeCampaign'])->name('seller.campaigns.store');
    });

    // ── Admin Routes ──────────────────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/',                        [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users',                   [AdminController::class, 'users'])->name('users');
        Route::post('/users/{id}/role',        [AdminController::class, 'updateRole'])->name('users.role');
        Route::post('/users/{id}/verify',      [AdminController::class, 'toggleVerify'])->name('users.verify');
        Route::delete('/users/{id}',           [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::get('/ads',                     [AdminController::class, 'ads'])->name('ads');
        Route::post('/ads/{id}/status',        [AdminController::class, 'updateAdStatus'])->name('ads.status');
        Route::get('/categories',              [AdminController::class, 'categories'])->name('categories');
        Route::get('/locations',               [AdminController::class, 'locations'])->name('locations');
        Route::get('/promotions',              [AdminController::class, 'promotions'])->name('promotions');
        
        // Monetization & Settings
        Route::get('/settings/payments', [PaymentSettingsController::class, 'index'])->name('settings.payments');
        Route::post('/settings/payments', [PaymentSettingsController::class, 'update'])->name('settings.payments.update');
        
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
        
        Route::get('/monetization/packages', [MonetizationAdminController::class, 'packages'])->name('monetization.packages');
        Route::post('/monetization/packages', [MonetizationAdminController::class, 'storePackage'])->name('monetization.packages.store');
        Route::put('/monetization/packages/{id}', [MonetizationAdminController::class, 'updatePackage'])->name('monetization.packages.update');
        Route::post('/monetization/packages/{id}/toggle', [MonetizationAdminController::class, 'togglePackage'])->name('monetization.packages.toggle');
    });
});
