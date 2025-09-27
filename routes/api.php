<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\StudentAuthController;
use App\Http\Controllers\Api\Auth\RegistrationController;
use App\Http\Controllers\Api\Auth\RegistrationPasswordResetController;

// API untuk calon mahasiswa (pendaftaran PMB)
Route::prefix('registration')->group(function () {
    Route::post('/register', [RegistrationController::class, 'store']); // calon daftar
    Route::post('/login', [RegistrationController::class, 'login']);   // calon login cek status
     Route::post('/forgot-password', [RegistrationPasswordResetController::class, 'sendResetLink']);
    Route::post('/reset-password', [RegistrationPasswordResetController::class, 'reset']);
});

// API untuk mahasiswa resmi (table students)
Route::prefix('student')->group(function () {
    Route::post('/login', [StudentAuthController::class, 'login']);
    Route::middleware('auth:sanctum')->get('/profile', [StudentAuthController::class, 'profile']);
});
