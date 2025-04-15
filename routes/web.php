<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MapController;
use App\Http\Controllers\CrimeReportController;
use App\Http\Controllers\Admin\CrimeReportCrudController;

Route::get('/', [MapController::class, 'index'])->name('home');
Route::get('/reports/{id}', [MapController::class, 'show'])->name('reports.show');

// Crime report routes
Route::get('/report/create', [CrimeReportController::class, 'create'])->name('reports.create');
Route::post('/report', [CrimeReportController::class, 'store'])->name('reports.store')->middleware('auth');

// Auth routes (included with Laravel's auth scaffolding)
Auth::routes();

// Admin routes for crime report approval/rejection
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'admin'], function () {
    Route::get('crime-report/{id}/approve', [CrimeReportCrudController::class, 'approve'])->name('crime-report.approve');
    Route::post('crime-report/{id}/reject', [CrimeReportCrudController::class, 'reject'])->name('crime-report.reject');
});
