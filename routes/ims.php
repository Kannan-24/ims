<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ims\CustomerController;
use App\Http\Controllers\ims\SupplierController;
use App\Http\Controllers\ims\ProductController;
use App\Http\Controllers\ims\ServiceController;
use App\Http\Controllers\ims\PurchaseController;
use App\Http\Controllers\ims\StockController;
use App\Http\Controllers\ims\QuotationController;
use App\Http\Controllers\ims\InvoiceController;
use App\Http\Controllers\ims\PaymentController;
use App\Http\Controllers\ims\EmailController;
use App\Http\Controllers\ims\ReportController;
use App\Http\Controllers\ims\ActivityLogController;
use Illuminate\Support\Facades\Route;


// Authentication Routes
Route::middleware(['auth', 'verified'])->prefix('ims')->group(function () {


    // Payment Routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{paymentId}/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments/{paymentId}/store', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{paymentId}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{paymentId}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{paymentId}/update', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{paymentId}/delete', [PaymentController::class, 'destroy'])->name('payments.destroy');

    // Quotation Payment Routes
    Route::get('/quotations/{id}/pdf', [QuotationController::class, 'generatePDF'])->name('quotations.pdf');

    Route::get('/products/{product}/assign-suppliers', [ProductController::class, 'assignSuppliersForm'])->name('products.assignSuppliersForm');
    Route::post('/products/{product}/assign-suppliers', [ProductController::class, 'assignSupplier'])->name('products.assignSupplier');
    Route::get('/suppliers/assign/{supplier}', [SupplierController::class, 'supplierAssign'])->name('suppliers.assignSupplier');
    Route::delete('/products/{product}/suppliers/{supplier}/remove', [ProductController::class, 'removeAssignedSupplier'])->name('suppliers.remove');
    Route::get('/purchases/get-products/{supplier}', [PurchaseController::class, 'getProductsBySupplier']);

    // Reports Routes
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{id}', [ReportController::class, 'show']);

    // Customer Reports Routes
    Route::get('/reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
    Route::get('/reports/customers/pdf', [ReportController::class, 'customersPdf'])->name('reports.customer.pdf');
    Route::get('/reports/customers/excel', [ReportController::class, 'customersExcel'])->name('reports.customer.excel');

    // Supplier Reports Routes
    Route::get('reports/suppliers', [ReportController::class, 'supplier'])->name('reports.suppliers');
    Route::get('reports/suppliers/pdf', [ReportController::class, 'suppliersPdf'])->name('reports.supplier.pdf');
    Route::get('reports/suppliers/excel', [ReportController::class, 'suppliersExcel'])->name('reports.supplier.excel');

    // Invoice Reports Routes
    Route::get('reports/invoices', [ReportController::class, 'invoice'])->name('reports.invoices');
    Route::get('reports/invoices/excel', [ReportController::class, 'invoicesExcel'])->name('reports.invoices.excel');
    Route::get('reports/invoices/pdf', [ReportController::class, 'invoicesPdf'])->name('reports.invoices.pdf');

    // Quotation Reports Routes
    Route::get('reports/quotations', [ReportController::class, 'quotation'])->name('reports.quotations');
    Route::get('reports/quotations/pdf', [ReportController::class, 'quotationsPdf'])->name('reports.quotations.pdf');
    Route::get('reports/quotations/excel', [ReportController::class, 'quotationsExcel'])->name('reports.quotations.excel');

    // Stock Reports Routes
    Route::get('reports/stocks', [ReportController::class, 'stock'])->name('reports.stocks');
    Route::get('reports/stocks/pdf', [ReportController::class, 'stocksPdf'])->name('reports.stocks.pdf');
    Route::get('reports/stocks/excel', [ReportController::class, 'stocksExcel'])->name('reports.stocks.excel');

    // Purchase Reports Routes
    Route::get('reports/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
    Route::get('reports/purchases/pdf', [ReportController::class, 'purchasesPdf'])->name('reports.purchases.pdf');
    Route::get('reports/purchases/excel', [ReportController::class, 'purchasesExcel'])->name('reports.purchases.excel');

    // Payment Reports Routes
    Route::get('reports/payments', [ReportController::class, 'payments'])->name('reports.payments');
    Route::get('reports/payments/pdf', [ReportController::class, 'paymentsPdf'])->name('reports.payments.pdf');
    Route::get('reports/payments/excel', [ReportController::class, 'paymentsExcel'])->name('reports.payments.excel');

    // User Routes
    Route::resource('users', UserController::class);

    // Customer Routes
    Route::resource('customers', CustomerController::class);

    // Supplier Routes
    Route::resource('suppliers', SupplierController::class);

    // Product Routes
    Route::resource('products', ProductController::class);

    // Service Routes
    Route::resource('services', ServiceController::class);

    // Purchase Routes
    Route::resource('purchases', PurchaseController::class);

    // Stock Routes
    Route::resource('stocks', StockController::class);

    // Quotation Routes
    Route::resource('quotations', QuotationController::class);
    Route::post('quotations/{quotation}/convert-to-invoice', [QuotationController::class, 'convertToInvoice'])->name('quotations.convert-to-invoice');

    // Invoice Routes
    Route::resource('invoices', InvoiceController::class);



    // Email Routes
    Route::resource('emails', EmailController::class);

    // Activity Log Routes
    Route::get('/activity-logs/clear', [ActivityLogController::class, 'destroyAll'])->name('activity-logs.destroyAll');
    Route::delete('/activity-logs/module/{module}', [ActivityLogController::class, 'destroyModule'])->name('activity-logs.destroyModule');
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::delete('/activity-logs/{id}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
    Route::get('/activity-logs/{id}/delete', [ActivityLogController::class, 'destroy'])->name('activity-logs.delete');
});
