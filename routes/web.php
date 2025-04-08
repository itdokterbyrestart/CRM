<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
	DashboardController,
	OrderController,
	OrderStatusController,
	CustomerController,
	ProductController,
	HourTypeController,
	PermissionController,
	RoleController,
	QuoteStatusController,
	QuoteController,
	ServiceController,
	ProductGroupController,
	TaxTypeController,
	InvoiceController,
	InvoiceStatusController,
	MollieWebhookController,
	SettingController,
	UserController,
};
use App\Http\Controllers\Auth\ProfileController;


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

// Auth::routes();
Auth::routes([
	'register' => false,
	'reset' => false,
	'verify' => false,
]);

Route::post('webhooks/mollie', [MollieWebhookController::class, 'handleWebhookNotification'])
	->name('webhooks.mollie');

Route::get('/offerte/{id}', [QuoteController::class, 'show_frontend'])
	->name('quote.customer.show');

Route::get('/factuur/{id}', [InvoiceController::class, 'show_frontend'])
	->name('invoice.customer.show');

Route::get('/factuur/{id}/betalen', [InvoiceController::class, 'preparePayment'])
	->name('invoice.customer.prepare_payment');

Route::get('/factuur/{id}/download_pdf', [InvoiceController::class, 'downloadPdf'])
	->name('invoice.customer.download_pdf');


Route::group(['middleware' => ['auth']], function () {
	// Dashboard
	Route::get('/', [DashboardController::class, 'index'])
		->name('dashboard');
	Route::get('dashboard', [DashboardController::class, 'index'])
		->name('dashboard');
	Route::post('dashboard_date_change',[DashboardController::class, 'date_change'])
		->name('dashboard.date_change');

	// Run factories
	// Route::get('factories', [DashboardController::class, 'factories'])
	// 	->name('factories');

	// Route::get('info', [InfoController::class, 'index'])
	// 	->name('info');

	// OrderController
	Route::get('order', [OrderController::class, 'index'])
		->name('order.index');
	Route::get('order/{order_id}/edit', [OrderController::class, 'edit'])
		->name('order.edit');
	Route::get('order/create', [OrderController::class, 'create'])
		->name('order.create');
	Route::get('order/{order_id}', [OrderController::class, 'show'])
		->name('order.show');

	// QuoteController
	Route::get('quote', [QuoteController::class, 'index'])
		->name('quote.index');
	Route::get('quote/{quote_id}/edit', [QuoteController::class, 'edit'])
		->name('quote.edit');
	Route::get('quote/create', [QuoteController::class, 'create'])
		->name('quote.create');
	Route::get('quote/{quote_id}', [QuoteController::class, 'show'])
		->name('quote.show');

	// InvoiceController
	Route::get('invoice', [InvoiceController::class, 'index'])
		->name('invoice.index');
	Route::get('invoice/{invoice_id}/edit', [InvoiceController::class, 'edit'])
		->name('invoice.edit');
	Route::get('invoice/create', [InvoiceController::class, 'create'])
		->name('invoice.create');
	Route::get('invoice/{invoice_id}', [InvoiceController::class, 'show'])
		->name('invoice.show');

	Route::get('/invoice/{invoice_id}/mail_preview',[InvoiceController::class, 'email_preview'])
		->name('invoice.preview.mail');

	Route::get('/invoice/{invoice_id}/mail_reminder_preview',[InvoiceController::class, 'email_reminder_preview'])
		->name('invoice.preview.mail_reminder');


	// CustomerController
	Route::get('customer', [CustomerController::class, 'index'])
		->name('customer.index');
	Route::get('customer/{customer_id}/edit', [CustomerController::class, 'edit'])
		->name('customer.edit');
	Route::get('customer/create', [CustomerController::class, 'create'])
		->name('customer.create');
	Route::get('customer/{customer_id}', [CustomerController::class, 'show'])
		->name('customer.show');

	// ProductController
	Route::get('product', [ProductController::class, 'index'])
		->name('product.index');
	Route::get('product/{product_id}/edit', [ProductController::class, 'edit'])
		->name('product.edit');
	Route::get('product/create', [ProductController::class, 'create'])
		->name('product.create');
	Route::get('product/{product_id}', [ProductController::class, 'show'])
		->name('product.show');

	// ServiceController
	Route::get('service', [ServiceController::class, 'index'])
		->name('service.index');
	Route::get('service/users', [ServiceController::class, 'users'])
		->name('service.users');
	Route::get('service/{service_id}/edit', [ServiceController::class, 'edit'])
		->name('service.edit');
	Route::get('service/create', [ServiceController::class, 'create'])
		->name('service.create');
	Route::get('service/{service_id}', [ServiceController::class, 'show'])
		->name('service.show');

	

	//ProfileController
	Route::get('profile', [ProfileController::class, 'show'])
		->name('profile.show');

	Route::get('profile/edit', [ProfileController::class, 'edit'])
		->name('profile.edit');

	Route::put('profile/edit', [ProfileController::class, 'update'])
		->name('profile.update');

	// Admin routes
	Route::group(['middleware' => ['role:admin']], function () {
		// OrderStatusController
		Route::get('orderstatus', [OrderStatusController::class, 'index'])
			->name('orderstatus.index');
		Route::get('orderstatus/{orderstatus_id}/edit', [OrderStatusController::class, 'edit'])
			->name('orderstatus.edit');
		Route::get('orderstatus/create', [OrderStatusController::class, 'create'])
			->name('orderstatus.create');
		Route::get('orderstatus/{orderstatus_id}', [OrderStatusController::class, 'show'])
			->name('orderstatus.show');

		// QuoteStatusController
		Route::get('quotestatus', [QuoteStatusController::class, 'index'])
			->name('quotestatus.index');
		Route::get('quotestatus/{quotestatus_id}/edit', [QuoteStatusController::class, 'edit'])
			->name('quotestatus.edit');
		Route::get('quotestatus/create', [QuoteStatusController::class, 'create'])
			->name('quotestatus.create');
		Route::get('quotestatus/{quotestatus_id}', [QuoteStatusController::class, 'show'])
			->name('quotestatus.show');

		// InvoiceStatusController
		Route::get('invoicestatus', [InvoiceStatusController::class, 'index'])
			->name('invoicestatus.index');
		Route::get('invoicestatus/{invoicestatus_id}/edit', [InvoiceStatusController::class, 'edit'])
			->name('invoicestatus.edit');
		Route::get('invoicestatus/create', [InvoiceStatusController::class, 'create'])
			->name('invoicestatus.create');
		Route::get('invoicestatus/{invoicestatus_id}', [InvoiceStatusController::class, 'show'])
			->name('invoicestatus.show');

		// PermissionController
		Route::get('permission', [PermissionController::class, 'index'])
			->name('permission.index');
		Route::get('permission/{permission_id}/edit', [PermissionController::class, 'edit'])
			->name('permission.edit');
		Route::get('permission/create', [PermissionController::class, 'create'])
			->name('permission.create');
		Route::get('permission/{permission_id}', [PermissionController::class, 'show'])
			->name('permission.show');

		// RoleController
		Route::get('role', [RoleController::class, 'index'])
			->name('role.index');
		Route::get('role/{role_id}/edit', [RoleController::class, 'edit'])
			->name('role.edit');
		Route::get('role/create', [RoleController::class, 'create'])
			->name('role.create');
		Route::get('role/{role_id}', [RoleController::class, 'show'])
			->name('role.show');

		// HourTypeController
		Route::get('hourtype', [HourTypeController::class, 'index'])
			->name('hourtype.index');
		Route::get('hourtype/{hourtype_id}/edit', [HourTypeController::class, 'edit'])
			->name('hourtype.edit');
		Route::get('hourtype/create', [HourTypeController::class, 'create'])
			->name('hourtype.create');
		Route::get('hourtype/{hourtype_id}', [HourTypeController::class, 'show'])
			->name('hourtype.show');

		// ProductGroupController
		Route::get('productgroup', [ProductGroupController::class, 'index'])
			->name('productgroup.index');
		Route::get('productgroup/{productgroup_id}/edit', [ProductGroupController::class, 'edit'])
			->name('productgroup.edit');
		Route::get('productgroup/create', [ProductGroupController::class, 'create'])
			->name('productgroup.create');
		Route::get('productgroup/{productgroup_id}', [ProductGroupController::class, 'show'])
			->name('productgroup.show');

		// TaxTypeController
		Route::get('taxtype', [TaxTypeController::class, 'index'])
			->name('taxtype.index');
		Route::get('taxtype/{taxtype_id}/edit', [TaxTypeController::class, 'edit'])
			->name('taxtype.edit');
		Route::get('taxtype/create', [TaxTypeController::class, 'create'])
			->name('taxtype.create');
		Route::get('taxtype/{taxtype_id}', [TaxTypeController::class, 'show'])
			->name('taxtype.show');

		// SettingController
		Route::get('setting', [SettingController::class, 'index'])
			->name('setting.index');
		Route::get('setting/{setting_id}/edit', [SettingController::class, 'edit'])
			->name('setting.edit');
		Route::get('setting/create', [SettingController::class, 'create'])
			->name('setting.create');
		Route::get('setting/{setting_id}', [SettingController::class, 'show'])
			->name('setting.show');

		// UserController
		Route::get('user', [UserController::class, 'index'])
			->name('user.index');
		Route::get('user/{user_id}/edit', [UserController::class, 'edit'])
			->name('user.edit');
		Route::get('user/create', [UserController::class, 'create'])
			->name('user.create');
		Route::get('user/{user_id}', [UserController::class, 'show'])
			->name('user.show');
	});

	// Route::get('/quote/{quote_id}/mail_preview',[QuoteController::class, 'email_preview']);
	// Route::get('/order_hours_user_report_email/{user_id}', [EmailController::class, 'order_hours_user_report_email']);
	// Route::get('/quote_mail_confirmation/{quote_id}', [EmailController::class, 'quote_email_confirmation']);
	// Route::get('/quote_mail_accepted/{quote_id}', [EmailController::class, 'quote_email_accepted']);
	// Route::get('/quote_mail_refused/{quote_id}', [EmailController::class, 'quote_email_refused']);
	// Route::get('/quote_mail/{quote_id}', [EmailController::class, 'quote_mail']);
	// Route::get('/apk_invitation_mail/{customer_id}', [EmailController::class, 'apk_invitation_email']);
	// Route::get('/services_reminder_email', [EmailController::class, 'services_reminder']);
	// Route::get('/invoice_mail/{id}', [EmailController::class, 'invoice_mail']);
	// Route::get('/invoice_paid_mail/{invoice_id}/{payment_id}', [EmailController::class, 'invoice_paid_mail']);
	// Route::get('/invoice_reminder_mail/{invoice_id}', [EmailController::class, 'invoice_reminder_mail']);
	// Route::get('/quote_reminder_mail/{quote_id}', [EmailController::class, 'quote_reminder_mail']);
	// Route::get('/schedule_appointment_mail/{id}/{reminder}/{appointment_type}', [EmailController::class, 'schedule_appointment_mail']);
	// Route::get('/apk_reminder_mail/{customer_id}/{final_reminder}', [EmailController::class, 'apk_reminder_mail']);
});
