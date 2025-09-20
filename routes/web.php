<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GradeWeightController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute untuk autentikasi (login dan registrasi)
// Gunakan middleware 'guest' untuk rute yang hanya bisa diakses saat belum login
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
});

// Rute untuk logout (harus bisa diakses oleh pengguna yang sudah login)
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Gunakan middleware 'auth' untuk rute yang hanya bisa diakses setelah login
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Students Management
    Route::resource('students', StudentController::class);
    Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');

// Reports
Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
Route::get('reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.exportExcel');
Route::get('reports/student-card/{id}', [ReportController::class, 'studentCard'])->name('reports.studentCard');

// Log
Route::get('log', [LogController::class, 'index'])->name('log.index');
});