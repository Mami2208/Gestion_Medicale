<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Routes accessibles uniquement aux invités (non authentifiés)
Route::middleware(['guest'])->group(function () {
    // Connexion
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Mot de passe oublié
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

// Déconnexion - accessible uniquement aux utilisateurs authentifiés
Route::post('logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Route de redirection après authentification
Route::get('/home', function () {
    return redirect()->route('login');
})->name('home');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // Ajoutez ici toutes les routes qui nécessitent une authentification
    // Par exemple :
    // Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
});
