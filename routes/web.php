<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AgentProfileController;
use App\Http\Controllers\MarketplaceListingController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify')->middleware('signed');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->name('verification.send');
    
    // Phone Verification Routes
    Route::get('/phone/verify', [VerificationController::class, 'showPhoneVerification'])->name('phone.verify.notice');
    Route::post('/phone/verify', [VerificationController::class, 'verifyPhone'])->name('phone.verify');
    Route::post('/phone/verification-notification', [VerificationController::class, 'resendPhoneVerification'])->name('phone.verify.send');
});

// Contact Page
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Health check endpoint for monitoring
Route::get('/health', function() {
    try {
        // Check database connection
        DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'disconnected';
    }

    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'environment' => app()->environment(),
        'database' => $dbStatus,
        'cache_driver' => config('cache.default'),
        'queue_driver' => config('queue.default'),
        'app_version' => '1.0.0',
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
    ]);
});

// Agent Routes
Route::get('/agents', [AgentProfileController::class, 'index'])->name('agents.index');
Route::get('/agents/directory', [AgentProfileController::class, 'directory'])->name('agents.directory');
Route::get('/agents/{id}', [AgentProfileController::class, 'show'])->name('agents.show');

// Agent Profile Routes (for agents themselves)
Route::prefix('agent')->name('agent.')->middleware('auth')->group(function () {
    Route::get('/profile', [AgentProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/create', [AgentProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [AgentProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [AgentProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AgentProfileController::class, 'update'])->name('profile.update');
});

// Admin Routes for Agent Management
Route::prefix('admin')->name('admin.')->middleware(['auth', 'can:admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');
    
    // User Management
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit', [AdminDashboardController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');
    
    // Agent Management
    Route::get('/agents', [AdminDashboardController::class, 'agents'])->name('agents');
    Route::get('/agents/pending', [AgentProfileController::class, 'pending'])->name('agents.pending');
    Route::post('/agents/{id}/approve', [AgentProfileController::class, 'approve'])->name('agents.approve');
    Route::get('/agents/{id}/documents/{documentType}', [AgentProfileController::class, 'downloadDocument'])->name('agents.download-document');
    
    // Service Management
    Route::get('/services', [AdminDashboardController::class, 'services'])->name('services');
    
    // Transaction Management
    Route::get('/transactions', [AdminDashboardController::class, 'transactions'])->name('transactions');
    
    // Dispute Management
    Route::get('/disputes', [AdminDashboardController::class, 'disputes'])->name('disputes');
});

// Marketplace Routes
Route::get('/marketplace', [MarketplaceListingController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/search', [MarketplaceListingController::class, 'search'])->name('marketplace.search');
Route::get('/marketplace/create', [MarketplaceListingController::class, 'create'])->name('marketplace.create')->middleware('auth');
Route::post('/marketplace', [MarketplaceListingController::class, 'store'])->name('marketplace.store')->middleware('auth');
Route::get('/marketplace/{marketplaceListing}', [MarketplaceListingController::class, 'show'])->name('marketplace.show');
Route::get('/marketplace/{marketplaceListing}/edit', [MarketplaceListingController::class, 'edit'])->name('marketplace.edit')->middleware('auth');
Route::put('/marketplace/{marketplaceListing}', [MarketplaceListingController::class, 'update'])->name('marketplace.update')->middleware('auth');
Route::delete('/marketplace/{marketplaceListing}', [MarketplaceListingController::class, 'destroy'])->name('marketplace.destroy')->middleware('auth');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
