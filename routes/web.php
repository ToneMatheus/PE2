<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\DomPDFController;
use App\Http\Controllers\myController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\invoice_query_controller;
use App\Http\Controllers\unpaid_invoice_query_controller;
use App\Http\Controllers\CustomerGridViewController;
use App\Http\Controllers\advancemailcontroller;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HolidayController;

use App\Http\Controllers\FAQController;
use App\Http\Controllers\MeterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\CronJobController;

use App\Http\Controllers\meterreading;
use App\Models\MeterReading as ModelsMeterReading;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SimpleUserOverViewController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\RelationsController;




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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update.profile');
    Route::patch('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.update.address');
    Route::patch('/profile/address/billing', [ProfileController::class, 'updateBillingAddress'])->name('profile.update.address.billing');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['checkUserRole:' . config('roles.MANAGER')])->group(function() {
    
});

Route::middleware(['checkUserRole:' . config('roles.BOSS')])->group(function() {

});

Route::middleware(['checkUserRole:' . config('roles.FINANCE_ANALYST')])->group(function() {
    
});

Route::middleware(['checkUserRole:' . config('roles.EXECUTIVE_MANAGER')])->group(function() {
    
});

Route::middleware(['checkUserRole:' . config('roles.CUSTOMER_SERVICE')])->group(function() {
    
});

Route::middleware(['checkUserRole:' . config('roles.CUSTOMER')])->group(function() {
    Route::get('/customer/invoiceStatus', [CustomerPortalController::class, 'invoiceView'])->name('customer.invoiceStatus');
    Route::post('/customer/change-locale', [CustomerPortalController::class, 'changeLocale'])->name('customer.change-locale');
    Route::post('/customer/chatbot', [CustomerPortalController::class, 'chatbot'])->name('customer.chatbot');
    Route::get('/contract_overview', [ContractController::class, 'index'])->name('contract_overview');
    Route::get('/contract_overview/{id}/download', [ContractController::class, 'download'])->name('contract.download');
    //Route::get('/contract_overview', [myController::class, 'contractOverview'])->name('contractOverview');
});

Route::middleware(['checkUserRole:' . config('roles.FIELD_TECHNICIAN')])->group(function() {
    
});

// EVERYTHING THAT IS ALLOWED TO BE ACCESSED BY EVERYONE (INCLUDING GUESTS) SHOULD BE PLACED UNDER HERE


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


//Meters Group
Route::get('/meters_dashboard/meters', [MeterController::class, 'viewScheduledMeters']);
Route::get('/all_meters_dashboard', [MeterController::class, 'viewAllMeters']);
Route::put('/all_meters_dashboard', [MeterController::class, 'assignment'])->name("assignment_change");

Route::get('/enterIndexEmployee', [MeterController::class, 'enterIndex']);
Route::post('/enterIndexEmployee', [MeterController::class, 'submitIndex'])->name("submitIndex");
Route::get('/dashboardEmployee', function () {
    return view('Meters/employeeDashboard');
});

Route::get('meters', [MeterController::class,'showMeters']);
Route::get('/meters/add', function () {
    return view('Meters/addmeter');
});
Route::post('meters/add', [MeterController::class,'addMeters']);
Route::get('/consumption', function () {
    return view('Meters/consumption');
});

Route::get('/Meter_History', [MeterController::class, 'showMeterHistory'])->name('Meter_History');

Route::get('/Consumption_Readings', [MeterController::class, 'showConsumptionReading'])->name('Consumption_Reading');


// Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('login', [LoginController::class, 'login']);

//to download the pdf of the contract and salary pages
Route::get('/downloadPayslip', [DomPDFController::class, 'getPaySlipPDF'])->name('downloadPayslip');
Route::get('/downloadContract', [DomPDFController::class, 'getContractPDF'])->name('downloadContract');

//the routes to the pages
Route::get('/payslip', [myController::class, 'payslip'])->name('payslip');
Route::get('/payList', [myController::class, 'payList'])->name('payList');
Route::get('/contract', [myController::class, 'contract'])->name('contract');
Route::get('/profileEmployee', [myController::class, 'profile'])->name('profile');
Route::get('/managerPage', [myController::class, 'manager'])->name('managerPage');
Route::get('/managerList', [myController::class, 'managerList'])->name('managerList');
Route::get('/employeeList', [myController::class, 'employeeList'])->name('employeeList');

// routes for relations controlelr
Route::get('/relations', [RelationsController::class, 'fetchRelations']);
Route::post('/relations/update', [RelationsController::class, 'updateRelation'])->name('relations.update');


//routing to decide whether the manager accepted or rejected holiday request and performing actions based on that


//Route::get('/holidayRequest', [myController::class, 'holiday'])->name('request');

//Route::get('/holidayRequest', function() {  return view('holidayRequest');  })->name('request');

Route::get('/holidayRequest', function(){
    return view('holidayRequestPage');
})->name('request');

Route::get('/welcome', function() {
    return view('welcome');
}) -> name('welcome');
Route::get('/roles', function () {
    return view('roleOverview');
});

//cronjobs
Route::get('/cron-jobs', [CronJobController::class, 'index'])->name('index-cron-job');
Route::get('/cron-jobs/edit/{job}', [CronJobController::class, 'edit'])->name('edit-cron-job');
Route::put('/cron-jobs/update/{job}', [CronJobController::class, 'update'])->name('update-cron-job');
Route::post('/cron-jobs/run/{job}', [CronJobController::class, 'run'])->name('run-cron-job');

Route::get('/customer/invoices', [InvoiceController::class, 'showInvoices'])->name('invoices.show');;




Route::get('/test', function () {
    return view('test');
});
Route::get('/roleOverview', function () {
    return view('roleOverview');
});



Route::get('/customerGridView', [CustomerGridViewController::class, 'index'])->name('customerGridView');
Route::get('/customer/{id}/edit', [CustomerGridViewController::class, 'edit'])->name('customer.edit');
Route::put('/customer/{id}/{cpID}', [CustomerGridViewController::class, 'update'])->name('customer.update');
Route::post('/customer/discount/{cpID}/{id}', [CustomerGridViewController::class, 'addDiscount'])->name('customer.discount');

Route::get('/products/{type}', [CustomerGridViewController::class, 'getProductsByType']);

// Ticket/FAQ page | Accessible by everyone
Route::controller(TicketController::class)->group(function () {
    Route::get('/create-ticket', 'showForm')->name('create-ticket');
    Route::post('/submitted-ticket', 'store')->name('submitted-ticket');
    Route::get('/submitted-ticket', 'showSubmittedTicket')->name('show-ticket');
});
Route::get('/faq', [FAQController::class, 'showFAQ'])->name('faq');

Route::get('/customer/overview', [SimpleUserOverViewController::class, 'overview'])->name('overview');

/*JOREN*/
// Set active user when email confirm
Route::get('/confirm-email/{encryptedUserID}/{email}', [ProfileController::class, 'confirmEmail'])->name('activate.account');
Route::get('/confirm-emailTEST/{token}/{email}', [RegisteredUserController::class, 'confirmEmail'])->name('email-confirmation-registration');



Route::get('/holidays', [HolidayController::class, 'index']);
Route::controller(InvoiceController::class)->group(function () {
Route::get('/invoices/{id}/mail', 'sendMail')->name('invoice.mail');});
Route::get('/invoices/{id}/download', [InvoiceController::class, 'download'])->name('invoice.download');
//All routes for credit notes
Route::get('/credit-notes', [CreditNoteController::class, 'index'])->name('credit-notes.index');
Route::get('/credit-notes/create', [CreditNoteController::class, 'create'])->name('credit-notes.create');
Route::post('/credit-notes', [CreditNoteController::class, 'store'])->name('credit-notes.store');
