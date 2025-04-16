<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.update.photo');

    // Account Settings Routes
    Route::get('/account-settings', [AccountSettingsController::class, 'index'])->name('account.settings');
    Route::patch('/account-settings/password', [AccountSettingsController::class, 'updatePassword'])->name('account.update.password');
    Route::delete('/account-settings/delete', [AccountSettingsController::class, 'destroy'])->name('account.destroy');
});

// Authentication Routes
Route::middleware(['auth', 'verified'])->group(function () {


    Route::get('/payments/{paymentId}/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments/{paymentId}/store', [PaymentController::class, 'store'])->name('payments.store');

    Route::get('/quotations/{id}/pdf', [QuotationController::class, 'generatePDF'])->name('quotations.pdf');
    Route::get('/products/{product}/assign-suppliers', [ProductController::class, 'assignSuppliersForm'])->name('products.assignSuppliersForm');
    Route::post('/products/{product}/assign-suppliers', [ProductController::class, 'assignSupplier'])->name('products.assignSupplier');
    Route::get('/suppliers/assign/{supplier}', [SupplierController::class, 'supplierAssign'])->name('suppliers.assignSupplier');
    Route::delete('/products/{product}/suppliers/{supplier}/remove', [ProductController::class, 'removeAssignedSupplier'])->name('suppliers.remove');
    Route::get('/purchases/get-products/{supplier}', [PurchaseController::class, 'getProductsBySupplier']);

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{id}', [ReportController::class, 'show']);



    // Customer Reports
    Route::get('/reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
    Route::get('/reports/customers/pdf', [ReportController::class, 'customersPdf'])->name('reports.customer.pdf');
    Route::get('/reports/customers/excel', [ReportController::class, 'customersExcel'])->name('reports.customer.excel');


    // Supplier Reports
    Route::get('reports/suppliers', [ReportController::class, 'supplier'])->name('reports.suppliers');
    Route::get('reports/suppliers/pdf', [ReportController::class, 'suppliersPdf'])->name('reports.supplier.pdf');
    Route::get('reports/suppliers/excel', [ReportController::class, 'suppliersExcel'])->name('reports.supplier.excel');

    // Invoice Reports
    Route::get('reports/invoices', [ReportController::class, 'invoice'])->name('reports.invoices');
    Route::get('reports/invoices/excel', [ReportController::class, 'invoicesExcel'])->name('reports.invoices.excel');
    Route::get('reports/invoices/pdf', [ReportController::class, 'invoicesPdf'])->name('reports.invoices.pdf');

    // Quotation Reports
    Route::get('reports/quotations', [ReportController::class, 'quotation'])->name('reports.quotations');
    Route::get('reports/quotations/pdf', [ReportController::class, 'quotationsPdf'])->name('reports.quotations.pdf');
    Route::get('reports/quotations/excel', [ReportController::class, 'quotationsExcel'])->name('reports.quotations.excel');

    // Stock Reports
    Route::get('reports/stocks', [ReportController::class, 'stock'])->name('reports.stocks');
    Route::get('reports/stocks/pdf', [ReportController::class, 'stocksPdf'])->name('reports.stocks.pdf');
    Route::get('reports/stocks/excel', [ReportController::class, 'stocksExcel'])->name('reports.stocks.excel');

    //Purchase Reports
    // Purchase Reports
    Route::get('reports/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
    Route::get('reports/purchases/pdf', [ReportController::class, 'purchasesPdf'])->name('reports.purchases.pdf');
    Route::get('reports/purchases/excel', [ReportController::class, 'purchasesExcel'])->name('reports.purchases.excel');

    Route::resource('users', UserController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('stocks', StockController::class);
    Route::resource('quotations', QuotationController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('emails', EmailController::class);
    Route::resource('activity-logs', ActivityLogController::class);
});



require __DIR__ . '/auth.php';
