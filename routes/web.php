<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GradeWeightController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GenerateStudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute untuk autentikasi (login dan registrasi)
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

    // Grades Management
    Route::get('grades', [GradeController::class, 'index'])->name('grades.index');
    Route::put('grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
    Route::post('grades/bulk-update', [GradeController::class, 'bulkUpdate'])->name('grades.bulkUpdate');

    // Grade Weights Management
    Route::get('grade-weights', [GradeWeightController::class, 'index'])->name('grade-weights.index');
    Route::put('grade-weights', [GradeWeightController::class, 'update'])->name('grade-weights.update');
    Route::post('grade-weights/reset', [GradeWeightController::class, 'reset'])->name('grade-weights.reset');
    
    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
    Route::get('reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.exportExcel');
    Route::get('reports/student-card/{id}', [ReportController::class, 'studentCard'])->name('reports.studentCard');

    // Log
    Route::get('log', [LogController::class, 'index'])->name('log.index');

    // Years Management
    Route::resource('years', YearController::class);

    // Student Classes Management
    Route::resource('student_classes', StudentClassController::class);

    // Course Management
    Route::resource('course', CourseController::class); 

    Route::prefix('grades')->name('grades.')->group(function () {
        Route::get('/', [GradeController::class, 'index'])->name('index');
        Route::post('/', [GradeController::class, 'store'])->name('store');
        Route::put('/{grade}', [GradeController::class, 'update'])->name('update');
        Route::post('/bulk-update', [GradeController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/{id}', [GradeController::class, 'show'])->name('show');
    });

    // generate student
    // Route::get('/students', [GenerateStudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [GenerateStudentController::class, 'create'])->name('students.create');
    Route::post('/students', [GenerateStudentController::class, 'store'])->name('students.store');
    // Route::get('/students/success', [GenerateStudentController::class, 'success'])->name('students.success');
    Route::get('/api/student-classes/{year}', [GenerateStudentController::class, 'getStudentClasses'])->name('api.student-classes');

});
