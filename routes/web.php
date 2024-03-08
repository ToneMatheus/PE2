<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DomPDFController;
use App\Http\Controllers\myController;


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
    return view('login');
});

Route::get("/dashboard", [DashboardController::class, 'index']);
Route::controller(InvoiceController::class)->group(function () {
    Route::get('/invoices', 'index')->name('invoice.index');
    Route::get('/invoices/{id}/mail', 'sendMail')->name('invoice.mail');
    Route::get('/invoices/{id}/download', 'download')->name('invoice.download');
    
});

Route::get("/employees", [EmployeeController::class, 'index']);





//          routes Tone
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


Route::controller(LoginController::class)->group(function () {
    //show the login view
    Route::get('/login', 'index');
    //authenticate the loginform and attempt authentication
    Route::post('/login', 'login');
    //logout via laravel Auth
    Route::get('/logout', 'logout');
    
});

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
