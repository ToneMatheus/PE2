<?php

use App\Http\Controllers\SimpleUserOverViewController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DomPDFController;
use App\Http\Controllers\myController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\invoice_query_controller;
use App\Http\Controllers\unpaid_invoice_query_controller;
use App\Http\Controllers\CustomerGridViewController;
use App\Http\Controllers\advancemailcontroller;
use App\Http\Controllers\CreditNotaController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\MeterController;
use App\Http\Controllers\CronJobController;


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
Route::get('/tariff/delete/{pID}/{tID}', [EmployeeController::class, 'inactivateTariff'])->name('tariff.delete');
Route::post('/tariff/add', [EmployeeController::class, 'processTariff'])->name('tariff.add'); 
Route::post('/tariff/edit/{pID}/{tID}', [EmployeeController::class, 'editTariff'])->name('tariff.edit');

//invoice query routes
Route::get('/invoice_query', [invoice_query_controller::class, 'contracts'])->name("invoice_query");
Route::get('/unpaid_invoice_query', [unpaid_invoice_query_controller::class, 'unpaidInvoices'])->name("unpaid_invoice_query");

//preview advance reminder mail for testing
Route::get('/advance', [advancemailcontroller::class, 'index'])->name("advance_mail");
// Meters branch

Route::get('/dashboard', function () {
    return view('Meters/employeeDashboard');
});
Route::get('meters', [MeterController::class,'showMeters']);
Route::get('/consumption', function () {
    return view('Meters/consumption');
});
Route::get('/consumption1', function () {
    return view('Meters/consumption1');
});
Route::get('/indexvalues', function () {
    return view('Meters/indexvalues');
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

Route::get('/welcome', function () {
    return view('welcome');
});
Route::get('/roles', function () {
    return view('roleOverview');
});

//cronjobs
Route::get('/cron-jobs', [CronJobController::class, 'index'])->name('index-cron-job');
Route::get('/cron-jobs/edit/{job}', [CronJobController::class, 'edit'])->name('edit-cron-job');
Route::put('/cron-jobs/update/{job}', [CronJobController::class, 'update'])->name('update-cron-job');
Route::post('/cron-jobs/run/{job}', [CronJobController::class, 'run'])->name('run-cron-job');

Route::get('/customer/invoices', [InvoiceController::class, 'showInvoices'])->name('invoices.show');;


Route::get('/contract_overview', [myController::class, 'contractOverview'])->name('contractOverview');

Route::get('/test', function () {
    return view('test');
});


Route::get('/customerGridView', [CustomerGridViewController::class, 'index'])->name('customerGridView');
Route::get('/customer/{id}/edit', [CustomerGridViewController::class, 'edit'])->name('customer.edit');
Route::put('/customer/{id}/{cpID}', [CustomerGridViewController::class, 'update'])->name('customer.update');
Route::post('/customer/discount/{cpID}/{id}', [CustomerGridViewController::class, 'addDiscount'])->name('customer.discount');

Route::get('/products/{type}', [CustomerGridViewController::class, 'getProductsByType']);

Route::get('/', function () {
    return view('welcome');
});
// Ticket page | Accessible by everyone
Route::controller(TicketController::class)->group(function () {
    Route::get('/create-ticket', 'showForm')->name('create-ticket');
    Route::post('/submitted-ticket', 'store')->name('submitted-ticket');
    Route::get('/submitted-ticket', 'showSubmittedTicket')->name('show-ticket');
});
Route::get('/faq', [FAQController::class, 'showFAQ'])->name('faq');

Route::get('/customer/overview', [SimpleUserOverViewController::class, 'overview'])->name('overview');


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

Route::controller(InvoiceController::class)->group(function () {
    Route::get('/invoices/{id}/mail', 'sendMail')->name('invoice.mail');
    Route::get('/invoices/{id}/download', 'download')->name('invoice.download');
});

Route::controller(InvoiceController::class)->group(function () {
    Route::get('/invoices/{id}/mail', 'sendMail')->name('invoice.mail');
});
//All routes for credit notes
Route::get('/credit-notes', [CreditNoteController::class, 'index'])->name('credit-notes.index');
Route::get('/credit-notes/create', [CreditNoteController::class, 'create'])->name('credit-notes.create');
Route::post('/credit-notes', [CreditNoteController::class, 'store'])->name('credit-notes.store');
