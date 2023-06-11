<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function() {
    
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    // Admin protected routes
    Route::middleware(['auth'])->group(function() {
        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');
        Route::get('/', DashboardController::class)->name('home');
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
    });
    
});