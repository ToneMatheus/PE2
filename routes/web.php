<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DomPDFController;
use App\Http\Controllers\myController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;

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
Route::get('/managerPage', [myController::class, 'manager'])->name('managerPage');
Route::get('/employeeList', [myController::class, 'employeeList'])->name('employeeList');

Route::get('/welcome', function() {
    return view('welcome');
}) -> name('welcome');
Route::get('/roles', function () {
    return view('roleOverview');
});

Route::get('/test', function () {
    return view('test');
});


//routes for custmer data for customer
Route::get('/Customer/Manage', [CustomerController::class,'Manage'])->name('Manage');
Route::get('/Customer/Create', function () { return view('Customer.CreateAccount');})->name('createUser');

Route::post('/Customer/Manage/Change/User', function () { return view('Customer.ManageChangeUser');})->name('ChangeUser');
Route::get('/Customer/Manage/Change/User', function () { return view('Customer.ManageChangeUser');});

// Validation route's to change customer info by customer
Route::post('/Customer/Manage/Change/User/post/email', [CustomerController::class, 'emailValidationChangeUserInfo']) ->name('postEmail');
Route::post('/Customer/Manage/Change/User/post/profile', [CustomerController::class, 'profileValidationChangeUserInfo']) ->name('postProfile');
Route::post('/Customer/Manage/Change/User/post/passwd', [CustomerController::class, 'passwdValidationChangeUserInfo']) ->name('postPasswd');

// Validation route's to create a customer account by customer
Route::post('/Customer/Create/validate', [CustomerController::class, 'profileValidationCreateAccount']) ->name('postCreateAccountValidate');
