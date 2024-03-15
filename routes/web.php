<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\EditController;
use App\Http\Controllers\TicketController;


use App\Http\Controllers\cont;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('test');
})->name('test');

use App\Http\Controllers\CustomerTicketController;

Route::get('/customertickets', [CustomerTicketController::class, 'index'])->name('customertickets.index');

Route::get('/history', [HistoryController::class, 'index'])->name('customertickets.history');

Route::get('/EditTickets', [EditController::class, 'index'])->name('customertickets.Edit');

Route::get('/tarrifs', function () {
    return view('tarrifs');
})->name('tarrifs');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Route::put('/tickets/{id}', [TicketController::class, 'update'])->name('tickets.update');
