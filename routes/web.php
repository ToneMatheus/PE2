<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DomPDFController;
use App\Http\Controllers\myController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\invoice_query_controller;
use App\Http\Controllers\unpaid_invoice_query_controller;

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

Route::get('/tariff', [EmployeeController::class, 'showTariff'])->name('tariff');
Route::get('/tariff/delete/{pID}/{tID}', [EmployeeController::class, 'inactivateTariff']);
Route::post('/tariff', [EmployeeController::class, 'processTariff']);
Route::post('/tariff/edit/{pID}/{tID}', [EmployeeController::class, 'editTariff'])->name('tariff.edit');

Route::get('/contractProduct/{cpID}', [EmployeeController::class, 'showContractProduct'])->name('contractProduct');
Route::post('/contractProduct/{cpID}/{ccID}/{pID}', [EmployeeController::class, 'addDiscount'])->name('cp.discount');
Route::post('/contractProduct/{cpID}', [EmployeeController::class, 'editContractProduct'])->name('cp.edit');

Route::get('/products/{type}', [EmployeeController::class, 'getProductsByType']);

//invoice query routes
Route::get('/invoice_query', [invoice_query_controller::class, 'contracts'])->name("invoice_query");
Route::get('/unpaid_invoice_query', [unpaid_invoice_query_controller::class, 'unpaidInvoices'])->name("unpaid_invoice_query");

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