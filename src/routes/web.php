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

Route::get('/', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/search', [TicketController::class, 'statusSearch'])->name('tickets.search');
Route::get('/tickets/{ticket_number}', [TicketController::class, 'show'])->name('tickets.show');

// Admin Routes (To be protected by auth later)
Route::prefix('admin')->name('admin.')->group(function() {
    Route::resource('departments', DepartmentController::class);
});
