<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Security fallback routes for crawlers/browsers/assets
Route::get('/robots.txt', fn () => response("User-agent: *\nDisallow:\n", 200, ['Content-Type' => 'text/plain']));
Route::get('/favicon.ico', fn () => response('', 204));
Route::get('/sitemap.xml', fn () => response('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>', 200, ['Content-Type' => 'text/xml']));
Route::get('/css/app.css', fn () => response(file_get_contents(resource_path('css/app.css')), 200, [
    'Content-Type' => 'text/css; charset=UTF-8',
    'X-Content-Type-Options' => 'nosniff',
]));

// Public Project Listing and Details
Route::get('/', [ProjectController::class, 'index'])->name('home');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated General Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Klien-only Routes
    Route::middleware('role:klien')->group(function () {
        Route::get('/project/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::post('/projects/{project}/bids/{bid}/pick', [BidController::class, 'pickWinner'])->name('bids.pick');
    });

    // Developer-only Routes
    Route::middleware('role:developer')->group(function () {
        Route::post('/projects/{project}/bids', [BidController::class, 'store'])->name('bids.store');
    });

    // Admin-only Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::delete('/projects/{project}', [AdminController::class, 'destroyProject'])->name('projects.destroy');
    });
});

// penugasan [2] The Phantom Vulnerability
// NIM: 252410102059
// NAMA: Arlando A'la Putra Raharjo
