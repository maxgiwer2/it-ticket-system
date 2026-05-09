<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\TicketController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\WorkloadController;

Route::get('/', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/search', [TicketController::class, 'statusSearch'])->name('tickets.search');
Route::get('/tickets/{ticket_number}', [TicketController::class, 'show'])->name('tickets.show');

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes (Protected by auth)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('departments', DepartmentController::class);
    Route::resource('users', UserController::class);
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [AdminTicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [AdminTicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::patch('/tickets/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    Route::get('/tickets/export', [ReportController::class, 'exportCsv'])->name('tickets.export');
    Route::post('/tickets/{ticket}/accept', [AdminTicketController::class, 'accept'])->name('tickets.accept');
    Route::resource('workloads', WorkloadController::class);
});
