<?php

use App\Http\Controllers\SimpleUserOverViewController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DomPDFController;
use App\Http\Controllers\myController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\LoginController;

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

//Role system
//Customer
Route::middleware(['auth', 'role:Customer'])->group(function (){

});

//Employee
Route::middleware(['auth', 'notrole:Customer'])->group(function (){
    //Only Finance
    Route::middleware(['auth', 'role:Finance analyst'])->group(function () {
        Route::get('/tariff', [EmployeeController::class, 'tariff'])->name('tariff');
    });
    
    //Only Manager
    Route::middleware(['auth', 'role:Manager'])->group(function (){
    
    });
    
    //Only Executive Manager
    Route::middleware(['auth', 'role:Executive Manager'])->group(function (){
    
    });

    //Every Employee
    
    //Route::get('/profile', [myController::class, 'profile'])->name('profile');
});


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

//to download the pdf of the contract and salary pages
Route::get('/downloadPayslip', [DomPDFController::class, 'getPaySlipPDF'])->name('downloadPayslip');
Route::get('/downloadContract', [DomPDFController::class, 'getContractPDF'])->name('downloadContract');

//the routes to the pages
Route::get('/payslip', [myController::class, 'payslip'])->name('payslip');
Route::get('/payList', [myController::class, 'payList'])->name('payList');
Route::get('/contract', [myController::class, 'contract'])->name('contract');
Route::get('/profile', [myController::class, 'profile'])->name('profile');

Route::get('/test', function () {
    return view('test');
});

// Ticket page | Accessible by everyone
Route::controller(TicketController::class)->group(function () {
    Route::get('/create-ticket', 'showForm')->name('create-ticket');
    Route::post('/submitted-ticket', 'store')->name('submitted-ticket');
    Route::get('/submitted-ticket', 'showSubmittedTicket')->name('show-ticket');
});

Route::get('/customer/overview', [SimpleUserOverViewController::class, 'overview'])->name('overview');
