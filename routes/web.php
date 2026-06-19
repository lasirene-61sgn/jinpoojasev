<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\SuperAdmin\SuperAdminAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('guest:superadmin')->group(function () {
    Route::get('/superadmin/login', [SuperAdminAuthController::class, 'showLoginForm'])->name('superadmin.login');
    Route::post('/superadmin/login', [SuperAdminAuthController::class, 'login']);
});

// --- Superadmin Protected Routes ---
Route::middleware('auth:superadmin')->prefix('superadmin')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('superadmin.dashboard');
    })->name('superadmin.dashboard');

    // Admin CRUD Panel Routes
    Route::get('/admins', [AdminController::class, 'index'])->name('superadmin.admins.index');
    Route::get('/admins/create', [AdminController::class, 'create'])->name('superadmin.admins.create');
    Route::post('/admins', [AdminController::class, 'store'])->name('superadmin.admins.store');
    Route::get('/admins/{id}/edit', [AdminController::class, 'edit'])->name('superadmin.admins.edit');
    Route::put('/admins/{id}', [AdminController::class, 'update'])->name('superadmin.admins.update');
    Route::delete('/admins/{id}', [AdminController::class, 'destroy'])->name('superadmin.admins.destroy');
    // Route to log out
    Route::post('/logout', [SuperAdminAuthController::class, 'logout'])->name('superadmin.logout');
    
    // Future routes to manage admins go here...
});

Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'login']);
});

// --- Regular Admin Protected Routes ---
Route::middleware('auth:admin')->prefix('admin')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    

    // Full Student Module Configuration
    Route::post('/students/bulk-upload', [StudentController::class, 'bulkUpload'])->name('admin.students.bulk-upload');
    Route::get('/students', [StudentController::class, 'index'])->name('admin.students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('admin.students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('admin.students.store');
    
    // Add these two lines for Edit/Update
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('admin.students.update');
    
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('admin.students.destroy');


    Route::get('/attendance', [AttendanceController::class, 'index'])->name('admin.attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('admin.attendance.store');
    // Future Admin features (Students, Attendance, etc.) will go here!

    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});