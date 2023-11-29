<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AccountsController;
use App\Http\Controllers\Auth\TwoFactorAuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Bookings\BookingsController;
use App\Http\Controllers\Bookings\ReminderController;
use App\Http\Controllers\Bookings\ReportController;
use App\Http\Controllers\Billing\BillingSetupController;
use App\Http\Controllers\Billing\BillingController;
use App\Http\Controllers\Billing\InvoiceController;
use App\Http\Controllers\Billing\ReportsController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Modules\ModuleAccessController;
use App\Http\Controllers\Modules\ModuleController;
use App\Http\Controllers\Modules\RibbonController;
use App\Http\Controllers\SetUp\ManageUserController;
use App\Http\Controllers\SetUp\SetupController;
use App\Http\Controllers\SetUp\ContactControllere;
use App\Http\Controllers\SetUp\UserProfileController;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Security\AuditController;
use App\Http\Controllers\Patients\PatientControlle;
use App\Http\Controllers\Restaurant\RestaurantController;
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


Auth::routes(['register' => false]);

Route::get('/new_client_registration', 'App\Http\Controllers\ClientRegistrationGuestController@index');
Route::post('client/client_registration', 'App\Http\Controllers\ClientRegistrationGuestController@store');

	
Route::get('2fa', [TwoFactorAuthController::class, 'index'])
    ->name('2fa.index');


Route::post('2fa', [TwoFactorAuthController::class, 'store'])
    ->name('2fa.post');

Route::get('2fa/reset', [TwoFactorAuthController::class, 'resend'])
    ->name('2fa.resend');

Route::get('login/locked', [LoginController::class, 'locked'])
    ->middleware('auth')
    ->name('login.locked');

Route::post('login/locked', [LoginController::class, 'unlock'])
    ->name('login.unlock');

Route::get('auth/google', [LoginController::class, 'redirectToGoogle']);

Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::get('auth/facebook', [LoginController::class, 'redirectToFacebook'])
    ->name('auth.facebook');

Route::get('auth/facebook/callback', [LoginController::class, 'handleFacebookCallback']);

Route::get('email/verify', [VerificationController::class, 'show'])
    ->name('verification.notice');


Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])
    ->name('forget.password.get');

Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])
    ->name('forget.password.post');

Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])
    ->name('reset.password.get');

Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])
    ->name('reset.password.post');


Route::post('reset_password_without_token', [AccountsController::class, 'validatePasswordRequest'])
    ->name('reset.no_token');

Route::post('reset_password_with_token', [AccountsController::class, 'resetPassword'])
    ->name('reset.token');


Route::group(['middleware' => ['web', 'auth', 'auth.lock', '2fa']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home')
        ->middleware(['auth', 'prevent-back-history']);

    #Notification mark as Read

    Route::post('/mark-as-read', [DashboardController::class, 'markNotification'])
        ->name('markNotification');
});

Route::resource('roles', RoleController::class)
    ->middleware(['role:Admin']);


Route::resource('user_profile', UserProfileController::class)
    ->middleware(['auth', 'auth.lock', '2fa']);


Route::group(['prefix' => 'users', 'middleware' => [
    'web', 'auth', 'auth.lock', '2fa', 'role:Admin']], function () {

    Route::resource('module', ModuleController::class);

    Route::get('module/act/{mod}', [ModuleController::class, 'activateModule'])
        ->name('module.activate');

    Route::resource('users-access', ModuleAccessController::class);

    Route::resource('ribbons', RibbonController::class);

    Route::get('ribbon/act/{ribbon}', [RibbonController::class, 'activateRibbon'])
        ->name('ribbon.activate');

    Route::resource('setup', SetupController::class);

    Route::get('public-holiday', [SetupController::class, 'publicHolidaysIndex'])
        ->name('public-holidays.index');

    Route::post('persist_holiday', [SetupController::class, 'createPublicHoliday'])
        ->name('public-holidays.store');

    Route::patch('/holiday/{holiday}', [SetupController::class, 'editPublicHoliday']);

    Route::delete('delete_holiday/{holiday}', [SetupController::class, 'destroyHoliday'])
        ->name('public-holidays.destroy');

    Route::resource('manage', ManageUserController::class);

    Route::get('manageUsers/act/{manage}', [ManageUserController::class, 'activateUsers'])
        ->name('manageUsers.activate');

    Route::resource('audits', AuditController::class);

    Route::resource('sms_settings', ContactControllere::class);

    Route::post('add_patient_sms', [ContactControllere::class, 'addPatientSms'])
    ->name('add.PatientSms');

    Route::post('edit_patient_sms', [ContactControllere::class, 'editPatientSms'])
        ->name('edit.PatientSms');

}) ;
Route::get('test', fn () => phpinfo());
Route::group(['prefix' => 'clients', 'middleware' => ['web', 'auth', 'auth.lock', '2fa']], function () {

    Route::resource('client_details', PatientControlle::class);
    Route::get('profile_management', [PatientControlle::class, 'clientslist'])
        ->name('clientManagement.index');
	Route::PATCH('update_client/{id}', [PatientControlle::class, 'update'])
        ->name('client_details.update');
    Route::PATCH('patient_details/guest/{patient_detail}', [PatientControlle::class, 'patientManagementGuest'])
        ->name('patientManagement.guest.update');
    Route::get('profile_management/act/{client}', [PatientControlle::class, 'activateClient'])
        ->name('clientManagement.activate'); 
	Route::get('client_details/show/{patient}', [PatientControlle::class, 'show'])
        ->name('client_details.show');
    Route::get('send-message', [ContactControllere::class, 'sendMessages'])
        ->name('sendMessages.view');
	Route::post('send-message', [ContactControllere::class, 'sendSmsMessages'])
        ->name('sendMessages.sendSms');
    Route::post('send-email', [ContactControllere::class, 'sendEmailCommunication'])
        ->name('sendMessages.sendEmail');
	Route::post('add_contact_person', [PatientControlle::class, 'storeContactPerson'])
        ->name('contact_person.store');
    Route::get('destroy_contact_person/{contact}', [PatientControlle::class, 'destroyContactPerson'])
        ->name('contact_person.destroy');
	Route::post('company/delete/{id}', [PatientControlle::class, 'destroy'])
        ->name('company_details.destroy');
	Route::delete('destroy_package/{package}', [PatientControlle::class, 'destroyPackage'])
        ->name('package.destroy');
	Route::get('packages', [PatientControlle::class, 'packagesView'])
        ->name('packages.view');
	Route::post('add_package', [PatientControlle::class, 'storePackage'])
        ->name('package.store');
	Route::get('package/act/{package}', [PatientControlle::class, 'activatePackage'])
        ->name('package.activate');
	Route::PATCH('update/package/{package}', [PatientControlle::class, 'packageUpdate'])
        ->name('package.update');
});

Route::group(['prefix' => 'restaurant', 'middleware' => ['web', 'auth', 'auth.lock', '2fa']], function () {

    //Route::resource('category', RestaurantController::class);
	Route::PATCH('update_client/{id}', [PatientControlle::class, 'update'])
        ->name('client_details.update');
    Route::PATCH('patient_details/guest/{patient_detail}', [PatientControlle::class, 'patientManagementGuest'])
        ->name('patientManagement.guest.update');
    Route::get('profile_management/act/{client}', [PatientControlle::class, 'activateClient'])
        ->name('clientManagement.activate'); 
	Route::get('client_details/show/{patient}', [PatientControlle::class, 'show'])
        ->name('client_details.show');
    Route::get('send-message', [ContactControllere::class, 'sendMessages'])
        ->name('sendMessages.view');
	Route::post('send-message', [ContactControllere::class, 'sendSmsMessages'])
        ->name('sendMessages.sendSms');
    Route::post('send-email', [ContactControllere::class, 'sendEmailCommunication'])
        ->name('sendMessages.sendEmail');
	Route::post('add_contact_person', [PatientControlle::class, 'storeContactPerson'])
        ->name('contact_person.store');
    Route::get('destroy_contact_person/{contact}', [PatientControlle::class, 'destroyContactPerson'])
        ->name('contact_person.destroy');
	Route::post('company/delete/{id}', [PatientControlle::class, 'destroy'])
        ->name('company_details.destroy');
    Route::get('category', [RestaurantController::class, 'categories'])
        ->name('categories.view');
	Route::delete('destroy_category/{category}', [RestaurantController::class, 'destroyCategory'])
        ->name('category.destroy');
	Route::post('add_category', [RestaurantController::class, 'storeCategory'])
        ->name('category.store');
	Route::get('category/act/{category}', [RestaurantController::class, 'activateCategory'])
        ->name('category.activate');
	Route::PATCH('update/category/{category}', [RestaurantController::class, 'categoryUpdate'])
        ->name('category.update');
	Route::get('menu', [RestaurantController::class, 'menus'])
        ->name('menus.view');
	Route::delete('destroy_menu/{menu}', [RestaurantController::class, 'destroyMenu'])
        ->name('menu.destroy');
	Route::post('add_menu', [RestaurantController::class, 'storeMenu'])
        ->name('menu.store');
	Route::get('menu/act/{menu}', [RestaurantController::class, 'activateMenu'])
        ->name('menu.activate');
	Route::PATCH('update/menu/{menu}', [RestaurantController::class, 'menuUpdate'])
        ->name('menu.update');
	Route::get('seating_plan', [RestaurantController::class, 'plans'])
        ->name('tables.view');
	Route::delete('destroy_plan/{plan}', [RestaurantController::class, 'destroyPlan'])
        ->name('table.destroy');
	Route::post('add_plan', [RestaurantController::class, 'storePlan'])
        ->name('table.store');
	Route::get('plan/act/{plan}', [RestaurantController::class, 'activatePlan'])
        ->name('table.activate');
	Route::PATCH('update/table/{table}', [RestaurantController::class, 'planUpdate'])
        ->name('table.update');
	Route::get('service_type', [RestaurantController::class, 'services'])
        ->name('services.view');
	Route::delete('destroy_service/{service}', [RestaurantController::class, 'destroyService'])
        ->name('service.destroy');
	Route::post('add_service', [RestaurantController::class, 'storeService'])
        ->name('service.store');
	Route::get('service/act/{service}', [RestaurantController::class, 'activateService'])
        ->name('service.activate');
	Route::PATCH('update/service/{service}', [RestaurantController::class, 'serviceUpdate'])
        ->name('service.update');
	Route::get('qr_code/print/{table}', [RestaurantController::class, 'printQrCode'])
        ->name('print.qr_code');
	Route::post('assign/employee/{table}', [RestaurantController::class, 'assignEmployee'])
        ->name('assign.employee');
});
Route::group(['prefix' => 'contacts', 'middleware' => ['web', 'auth', 'auth.lock', '2fa', 'role:Admin']], function () {

});
