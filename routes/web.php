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
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\EnrollmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute untuk autentikasi (hanya login, tanpa register untuk admin dashboard)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Rute untuk logout
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Gunakan middleware 'auth' untuk rute yang hanya bisa diakses setelah login
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Students Management (Custom routes di atas resource)
    Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('students/success', [StudentController::class, 'success'])->name('students.success');
    Route::get('api/student-classes/{year}', [StudentController::class, 'getStudentClasses'])->name('api.student-classes');
    Route::resource('students', StudentController::class);

    // Grades Management
    Route::prefix('grades')->name('grades.')->group(function () {
        Route::get('/', [GradeController::class, 'index'])->name('index');
        Route::post('/', [GradeController::class, 'store'])->name('store');
        Route::put('/{grade}', [GradeController::class, 'update'])->name('update');
        Route::post('/bulk-update', [GradeController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/{id}', [GradeController::class, 'show'])->name('show');
    });

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

    // Course Management (rename ke plural 'courses' untuk konsistensi)
    Route::resource('courses', CourseController::class);

    // Period Management
    Route::patch('periods/{period}/activate', [PeriodController::class, 'activate'])->name('periods.activate');
    Route::get('periods/{period}/courses', [PeriodController::class, 'getCourses'])->name('periods.courses');
    Route::get('periods/{period}/stats', [PeriodController::class, 'getEnrollmentStats'])->name('periods.stats');
    Route::resource('periods', PeriodController::class);

    // Enrollment Management
    Route::post('enrollments/bulk', [EnrollmentController::class, 'bulkStore'])->name('enrollments.bulk');
    Route::patch('enrollments/{enrollment}/drop', [EnrollmentController::class, 'drop'])->name('enrollments.drop');
    Route::patch('enrollments/{enrollment}/reactivate', [EnrollmentController::class, 'reactivate'])->name('enrollments.reactivate');
    Route::get('enrollments/export', [EnrollmentController::class, 'export'])->name('enrollments.export');
    Route::get('api/enrolled-students', [EnrollmentController::class, 'getEnrolledStudents'])->name('api.enrolled-students');
    Route::resource('enrollments', EnrollmentController::class);
});