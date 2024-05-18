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
use App\Http\Controllers\InvoiceRemindersController;
use App\Http\Controllers\InvoiceMatchingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerGridViewController;
use App\Http\Controllers\advancemailcontroller;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\RelationsController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TariffController;
use App\Http\Controllers\LeaveRequestController;

use App\Http\Controllers\EvaluationController;

use App\Http\Controllers\FAQController;
use App\Http\Controllers\MeterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\EstimationController;
use App\Http\Controllers\FlowchartAscaladeTicketController;

use App\Http\Controllers\meterreading;
use App\Models\MeterReading as ModelsMeterReading;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SimpleUserOverViewController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\GasElectricityController;
use App\Http\Controllers\PayoutsController;
use App\Models\ElectricityConnection;
use App\Http\Controllers\IndexValueController;
use App\Http\Controllers\ManualInvoiceController;
use App\Http\Controllers\NewEmployeeController;
use App\Http\Controllers\holidayRequest;
use App\Http\Controllers\UploadController;


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
Route::get('/temp', function () {
    return view('welcome_temp');
});


Route::get('/dashboard', function () {
    return view('dashboard');
    //$invoicesQuery->whereYear('invoice_date', $selectedYear);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update.profile');
    Route::patch('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.update.address');
    Route::patch('/profile/address/billing', [ProfileController::class, 'updateBillingAddress'])->name('profile.update.address.billing');
    Route::patch('/profile/address/add', [ProfileController::class, 'addAddress'])->name('profile.add.address');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['checkUserRole:' . config('roles.MANAGER')])->group(function() {
    //cronjobs
    //Route::get('/cron-jobs', [CronJobController::class, 'index'])->name('index-cron-job');
    Route::get('/cron-jobs/schedule/edit/{job}', [CronJobController::class, 'edit_schedule'])->name('edit-schedule-cron-job');
    Route::post('/cron-jobs/schedule/store{job}', [CronJobController::class, 'store_schedule'])->name('store-schedule-cron-job');
    Route::post('/cron-jobs/schedule/toggle{job}', [CronJobController::class, 'toggle_schedule'])->name('toggle-schedule-cron-job');
    // Route::post('/cron-jobs/run/{job}', [CronJobController::class, 'run'])->name('run-cron-job');
    Route::get('/cron-jobs/history', [CronJobController::class, 'showHistory'])->name('job.history');
    Route::get('/cron-jobs/get-job-runs', [CronJobController::class, 'getJobRuns'])->name('get.job.runs');
    Route::get('/cron-jobs/get-job-run-logs', [CronJobController::class, 'getJobRunLogs'])->name('get.job.run.logs');
    Route::post('/cron-jobs/update-log-level/{jobName}', [CronJobController::class, 'updateLogLevel'])->name('update.log.level');
    

    Route::get('/payouts', [PayoutsController::class, 'showPayouts'])->name('payouts');
    Route::get('/payouts/{id}', [PayoutsController::class, 'processPayout'])->name('payouts.pay');

    Route::get('/manualInvoice', [ManualInvoiceController::class, 'showManualInvoice'])->name('manualInvoice');
    Route::post('/manualInvoice', [ManualInvoiceController::class, 'processManualInvoice'])->name('manualInvoice.process');

    //payments management
    Route::get('/pay/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/pay', [PaymentController::class, 'add'])->name('payment.add');
    Route::get('/invoice-matching', [InvoiceMatchingController::class, 'startMatching'])->name("invoice_matching");
    Route::get('/invoice-matching/filter', [InvoiceMatchingController::class, 'filter'])->name('filter-invoice-matching');
    Route::get('/tariff', [TariffController::class, 'showTariff'])->name('tariff');
    Route::get('/tariff/delete/{pID}/{tID}', [TariffController::class, 'inactivateTariff'])->name('tariff.delete');
    Route::post('/tariff/add', [TariffController::class, 'processTariff'])->name('tariff.add');
    Route::post('/tariff/edit/{pID}/{tID}', [TariffController::class, 'editTariff'])->name('tariff.edit');

    Route::get('/tariff/products/{type}', [TariffController::class, 'getProductByType']);
});

Route::middleware(['checkUserRole:' . config('roles.BOSS')])->group(function() {

});

Route::middleware(['checkUserRole:' . config('roles.FINANCE_ANALYST')])->group(function() {
});

Route::middleware(['checkUserRole:' . config('roles.EXECUTIVE_MANAGER')])->group(function() {

});

Route::middleware(['checkUserRole:' . config('roles.CUSTOMER_SERVICE')])->group(function() {
    Route::get('/ticket/Flowchart', [FlowchartAscaladeTicketController::class, 'index'])->name('Support_Pages.flowchart.Flowchart-ascalade-ticket');

});

Route::middleware(['checkUserRole:' . config('roles.CUSTOMER')])->group(function() {
    Route::get('/customer/invoiceStatus', [CustomerPortalController::class, 'invoiceView'])->name('customer.invoiceStatus');
    Route::post('/customer/change-locale', [CustomerPortalController::class, 'changeLocale'])->name('customer.change-locale');
    Route::post('/customer/chatbot', [CustomerPortalController::class, 'chatbot'])->name('customer.chatbot');
    Route::get('/contract_overview', [ContractController::class, 'index'])->name('contract_overview');
    Route::get('/contract_overview/{id}/download', [ContractController::class, 'download'])->name('contract.download');
    //Route::get('/contract_overview', [myController::class, 'contractOverview'])->name('contractOverview');

    Route::get('/pay/{id}/{hash}', [PaymentController::class, 'show'])->name("payment.show");
    Route::post('/pay/invoice/{id}', [PaymentController::class, 'pay'])->name('payment.pay');
});

Route::middleware(['checkUserRole:' . config('roles.FIELD_TECHNICIAN')])->group(function() {


});

Route::middleware(['checkUserRole:' . config('roles.EMPLOYEE')])->group(function() {
    
});

Route::middleware(['checkUserRole:' . config('roles.EMPLOYEE')])->group(function() {
    Route::get('/cron-jobs', [CronJobController::class, 'index'])->name('index-cron-job');
    Route::post('/cron-jobs/run/{job}', [CronJobController::class, 'run'])->name('run-cron-job');

    Route::get('/tariff', [TariffController::class, 'showTariff'])->name('tariff');
    Route::get('/tariff/delete/{pID}/{tID}', [TariffController::class, 'inactivateTariff'])->name('tariff.delete');
    Route::post('/tariff/add', [TariffController::class, 'processTariff'])->name('tariff.add');
    Route::post('/tariff/edit/{pID}/{tID}', [TariffController::class, 'editTariff'])->name('tariff.edit');

    Route::get('/tariff/products/{type}', [TariffController::class, 'getProductByType']);
});

// EVERYTHING THAT IS ALLOWED TO BE ACCESSED BY EVERYONE (INCLUDING GUESTS) SHOULD BE PLACED UNDER HERE

//
Route::get('/employeeOverview', [EmployeeController::class, 'showEmployees'])->name('employees');
Route::post('/employeeOverview/add', [EmployeeController::class, 'processEmployee'])->name('employees.add');
Route::get('/editEmployee/{eID}', [EmployeeController::class, 'editEmployee'])->name('employees.edit');
Route::post('/editEmployee/{eID}/personal', [EmployeeController::class, 'editPersonalEmployee'])->name('employees.edit.personal');
Route::post('/editEmployee/{eID}/{aID}/{uID}/address', [EmployeeController::class, 'editAddressEmployee'])->name('employees.edit.address');
Route::post('/editEmployee/{eID}/{uID}/contract', [EmployeeController::class, 'editContractEmployee'])->name('employees.edit.contract');
Route::get('/evaluations', [EvaluationController::class, 'evaluations'])->name('evaluations');

//Route::get('/evaluations', [EvaluationController::class, 'managerTicketPage'])->name('manager-tickets');


//invoice query routes: DEPRECATED
Route::get('/invoice_query', [invoice_query_controller::class, 'contracts'])->name("invoice_query");
Route::get('/unpaid_invoice_query', [unpaid_invoice_query_controller::class, 'unpaidInvoices'])->name("unpaid_invoice_query");

//view invoice reminder mails for testing
Route::get('/advance', [advancemailcontroller::class, 'index'])->name("advance_mail");
Route::get('/reminders', [InvoiceRemindersController::class, 'index'])->name("invoice_reminder");
Route::get('/test-qr-monthly', [InvoiceRemindersController::class, 'monthly'])->name("qr-monthly");

//invoice payment
/*Route::get('/pay/create', [PaymentController::class, 'create'])->name('payment.create');
Route::post('/pay', [PaymentController::class, 'add'])->name('payment.add');
Route::get('/pay/{id}/{hash}', [PaymentController::class, 'show'])->name("payment.show");
Route::post('/pay/invoice/{id}', [PaymentController::class, 'pay'])->name('payment.pay');

Route::get('/invoice-matching', [InvoiceMatchingController::class, 'startMatching'])->name("invoice_matching");
Route::get('/invoice-matching/filter', [InvoiceMatchingController::class, 'filter'])->name('filter-invoice-matching');*/


//QR code test
Route::get('/code', function () {
    return view('Invoices/QRcodeTest');
});

// Meters branch


//Meters Group

//employee-specific dashboard
Route::get('/meter_dashboard', [MeterController::class, 'viewScheduledMeters']);

//all meters dashboard
Route::controller(MeterController::class)->group(function () {
    Route::get('/all_meters_dashboard', 'all_meters_index')->name("viewAllMeters");
    Route::get('/all_meters_dashboard_search', 'search')->name("search");
    Route::post('/assignment_change', 'assignment');
    Route::post('/bulk_assignment_change', 'bulk_assignment');
});

//page for employees to enter index values
Route::controller(MeterController::class)->group(function () {
    Route::get('/enter_index_employee', function() {return view('Meters/enterIndexEmployee');});
    Route::get('/enter_index_employee_search', 'searchIndex')->name("searchIndex");
    Route::get('/fetchEAN/{meterID}', 'fetchEAN');
    Route::post('/index_value_entered','submitIndex')->name("submitIndex");

    Route::get('/enter_index_paper', function() {return view('Meters/enterIndexPaper');});
    Route::get('/enter_index_paper_search', 'searchIndexPaper')->name("searchIndexPaper");
    Route::get('/fetchEAN/{meterID}', 'fetchEAN');

    Route::get('/fetchIndex/{meterID}', 'fetchIndex');
    Route::post('/index_value_entered_customer','submitIndexCustomer')->name("submitIndexCustomer");
});

Route::get('/meter_group_dashboard', function() {
    return view('Meters/MeterGroupDashboard');
});

Route::get('meters', [MeterController::class,'showMeters']);
Route::get('/meters/add', function () {
    return view('Meters/addmeter');
});
Route::post('meters/add', [MeterController::class,'addMeters']);
Route::get('/consumption', function () {
    return view('Meters/consumption');
});
//aryan
Route::controller(MeterController::class)->group(function () {
    Route::get('/Consumption_Dashboard', 'showConsumptionDashboard');
    Route::get('/Meter_History', 'GasElectricity');
});


// Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('login', [LoginController::class, 'login']);

//to download the pdf of the contract and salary pages
Route::get('/downloadPayslip', [DomPDFController::class, 'getPaySlipPDF'])->name('downloadPayslip');
Route::get('/downloadContract', [DomPDFController::class, 'getContractPDF'])->name('downloadContract');
Route::get('/downloadBenefits', [DomPDFController::class, 'getBenefitsPDF'])->name('downloadBenefits');

//the routes to most of the hr pages
Route::get('/payslip', [myController::class, 'payslip'])->name('payslip');
Route::get('/payList', [myController::class, 'payList'])->name('payList');
Route::get('/contract', [myController::class, 'contract'])->name('contract');
Route::get('/profileEmployee/{id?}', [myController::class, 'profile'])->name('profile');
Route::get('/managerPage', [myController::class, 'manager'])->name('managerPage');
Route::get('/managerList', [myController::class, 'managerList'])->name('managerList');
Route::get('/employeeList', [myController::class, 'employeeList'])->name('employeeList');
Route::get('/employeeBenefits', [myController::class, 'benefits'])->name('employeeBenefits');
//Route::post('/profileEmployee/{id}', [myController::class, 'store'])->name('storeTaskData');
Route::get('/hiringManger', [myController::class, 'hiringManager'])->name('hiringManager');
Route::get('/jobOffers', [myController::class, 'jobs'])->name('jobs');
Route::get('/jobDescription', [myController::class, 'jobDescription'])->name('jobDescription');
Route::get('/jobApply', [myController::class, 'jobApply'])->name('jobApply');
Route::get('/documents', [myController::class, 'documents'])->name('documents');
Route::get('/financialAnalyst', [myController::class, 'finance'])->name('financialAnalyst');
Route::get('/weeklyActivity', [myController::class, 'weeklyActivity'])->name('weeklyActivity');
Route::get('/teamBenefits', [myController::class, 'teamBenefits'])->name('teamBenefits');
Route::get('/teamWeeklyReports', [myController::class, 'weeklyReport'])->name('teamWeeklyReports');
// Route::get('/report', function () {
//     return view('report', ['weekStartDate' => now()->startOfWeek()->toDateString(), 'weekEndDate' => now()->endOfWeek()->toDateString()]);
// });
Route::post('/submit-report', [myController::class, 'storeWeeklyReports']);

// Route::get('/sickLeaveReason', [myController::class, 'sickLeave'])->name('sickLeaveReason');
// Route::get('/profileHR', [myController::class, 'profileHR'])->name('profileHR');
// Route::get('/profileInvoice', [myController::class, 'profileInvoice'])->name('profileInvoice');
// Route::get('/profileCustomers', [myController::class, 'profileCustomers'])->name('profileCustomers');
// Route::get('/profileMeters', [myController::class, 'profileMeters'])->name('profileMeters');

// routes for relations controlelr
Route::get('/relations', [RelationsController::class, 'fetchRelations']);
Route::post('/relations/update', [RelationsController::class, 'updateRelation'])->name('relations.update');


//routing to decide whether the manager accepted or rejected holiday request and performing actions based on that


//Route::get('/holidayRequest', [myController::class, 'holiday'])->name('request');

//Route::get('/holidayRequest', function() {  return view('holidayRequest');  })->name('request');

Route::get('/holidayRequest', [holidayRequest::class, 'index'])->name('request');
Route::post('/upload', [UploadController::class, 'uploadFile'])->name('upload.file'); 
// Route::get('/holidayRequest', function(){
//     return view('holidayRequestPage');
// })->name('request');

Route::get('/welcome', function() {
    return view('welcome');
}) -> name('welcome');
Route::get('/roles', function () {
    return view('roleOverview');
});
Route::get('/teamOverview', [TeamController::class, 'index']);
Route::post('/add-team', [TeamController::class, 'addTeam'])->name('add.team');
Route::get('/teams', [TeamController::class, 'showTeams'])->name('teams.show');
Route::get('/teams/members/{teamId}', [App\Http\Controllers\TeamController::class, 'getTeamMembers'])->name('team.members');
Route::get('/users/not-in-team', [TeamController::class, 'getUsersNotInTeam'])->name('users.not-in-team');
Route::post('/teams/add-member', [TeamController::class, 'addMemberToTeam'])->name('teams.add-member');

Route::get('/employee/invoices', [InvoiceController::class, 'showAllInvoices'])->name('invoices.show');;
Route::post('/employee/invoices', [InvoiceController::class, 'rerunValidation'])->name('invoices.rerunValidation');;

//Route::get('/contract_overview', [myController::class, 'contractOverview'])->name('contractOverview');
Route::get('/contract_overview', [ContractController::class, 'index'])->name('contract_overview');
Route::get('/contract_overview/{id}/download', [ContractController::class, 'download'])->name('contract.download');


Route::get('/test', function () {
    return view('test');
});
Route::get('/roleOverview', function () {
    return view('roleOverview');
});



Route::get('/customerGridView', [CustomerGridViewController::class, 'index'])->name('customerGridView');
Route::get('/customer/{id}/edit', [CustomerGridViewController::class, 'edit'])->name('customer.edit');
Route::put('/customer/{id}', [CustomerGridViewController::class, 'update'])->name('customer.update');
Route::post('/customer/{id}/{oldCpID}/{cID}/{mID}', [CustomerGridViewController::class, 'updateContractProduct'])->name('customer.contractProduct');
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

Route::controller(InvoiceController::class)->group(function () {
    Route::get('/invoices/{id}/mail', 'sendMail')->name('invoice.mail');
    Route::get('/invoices/{id}/download', 'download')->name('invoice.download');
    Route::get('/invoices/run', 'run')->name('invoice.run');
});

/*JOREN*/
// Set active user when email confirm
Route::get('/confirm-email/{encryptedUserID}/{email}', [ProfileController::class, 'confirmEmail'])->name('activate.account');
Route::get('/confirm-emailTEST/{token}/{email}', [RegisteredUserController::class, 'confirmEmail'])->name('email-confirmation-registration');

// verify email
Route::get('/profile/email-changed', [ProfileController::class, 'emailChanged'])->name('profile.emailChanged');

Route::get('/holidays', [HolidayController::class, 'index']);
Route::controller(InvoiceController::class)->group(function () {
Route::get('/invoices/{id}/mail', 'sendMail')->name('invoice.mail');});
Route::get('/invoices/{id}/download', [InvoiceController::class, 'download'])->name('invoice.download');
//All routes for credit notes
Route::get('/credit-notes', [CreditNoteController::class, 'index'])->name('credit-notes.index');
Route::get('/credit-notes/create', [CreditNoteController::class, 'create'])->name('credit-notes.create');
Route::post('/credit-notes', [CreditNoteController::class, 'store'])->name('credit-notes.store');

Route::get('/customer/invoice/search', [CreditNoteController::class, 'show']);
Route::post('/customer/invoice/search', [CreditNoteController::class, 'search'])->name('credit-notes.search');

Route::post('/refund', [CreditNoteController::class, 'refund']);

//Customer Portal
Route::get('/customer/invoices/{customerContractId}', [CustomerPortalController::class, 'invoiceView'])->name('customer.invoices');
Route::get('/customer/consumption-history', [CustomerPortalController::class, 'showConsumptionPage'])->name('customer.consumption-history');
Route::get('/customer/consumption-history/{timeframe}', [CustomerPortalController::class, 'showConsumptionHistory']);

//Customer Portal
Route::get('/customer/invoices/{customerContractId}', [CustomerPortalController::class, 'invoiceView'])->name('customer.invoices');
Route::get('/customer/consumption-history', [CustomerPortalController::class, 'showConsumptionPage'])->name('customer.consumption-history');
Route::get('/customer/consumption-history/{timeframe}', [CustomerPortalController::class, 'showConsumptionHistory']);

//Guest routes for estimations
Route::get('/EstimationGuestForm', [EstimationController::class, 'showGuestForm'])->name('EstimationGuestForm');
Route::post('/EstimationGuestForm', [EstimationController::class, 'ShowGuestEnergyEstimate'])->name('EstimationGuestResult');

Route::get('/CreateInvoice', [EstimationController::class, 'showButton'])->name('EstimationPage');
Route::post('/CreateInvoice', [EstimationController::class, 'generateOneInvoice'])->name('CalculateEstimation');

Route::post('/addInvoiceExtraForm', [InvoiceController::class, 'AddInvoiceExtra'])->name('addInvoiceExtraForm');



//test route
/*Route::get('/TestUserList', [InvoiceController::class, 'showTestUserList'])->name('TestUserList');
Route::get('/addInvoiceExtraForm', [InvoiceController::class, 'showAddInvoiceExtraForm'])->name('addInvoiceExtraForm');
Route::get('/TestEmployeeList', [InvoiceController::class, 'showTestEmployeeList'])->name('TestEmployeeList');*/


//Customer Portal
Route::get('/customer/invoices/{customerContractId}', [CustomerPortalController::class, 'invoiceView'])->name('customer.invoices');
Route::get('/customer/consumption-history', [CustomerPortalController::class, 'showConsumptionPage'])->name('customer.consumption-history');
Route::get('/customer/consumption-history/{timeframe}', [CustomerPortalController::class, 'showConsumptionHistory']);
Route::post('/CreateInvoice', [EstimationController::class, 'generateOneInvoice'])->name('CalculateEstimation');
