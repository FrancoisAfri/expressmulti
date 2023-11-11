<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
		
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
	
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

	Route::group(['prefix' => 'contacts', 'middleware' => ['web', 'auth', 'auth.lock', '2fa', 'role:Admin']], function () {

});
