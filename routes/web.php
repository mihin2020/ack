<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes (protected)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::get('/dashboard/export', [\App\Http\Controllers\Admin\DashboardController::class, 'export'])->name('dashboard.export');
    
    Route::get('/inscriptions', function () {
        return view('admin.inscription');
    })->name('inscriptions');
    
    Route::get('/liste-inscrits', function () {
        return view('admin.liste-inscrits');
    })->name('liste-inscrits');
    
    Route::get('/inscrits/{id}', function ($id) {
        return view('admin.details-inscrit', ['id' => $id]);
    })->name('inscrits.details');
    
    Route::get('/statistiques', function () {
        return view('admin.statistiques');
    })->name('statistiques');
    
    Route::get('/parametres', function () {
        return view('admin.parametres');
    })->name('parametres');
    
    Route::get('/profil', function () {
        return view('admin.profil');
    })->name('profil');
});

// Public pages
Route::get('/inscription', function () {
    return view('public.inscription');
})->name('public.inscription');

Route::get('/reservation', function () {
    return view('public.reservation');
})->name('public.reservation');

