<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GradeWeightController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

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