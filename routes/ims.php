<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
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

    // User Routes (Admin and Super Admin only)
    Route::middleware(['permission:view-users'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Role and Permission Routes (Admin only)
    Route::middleware(['role:Super Admin,Admin'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
    });

    // Customer Routes
    Route::middleware(['permission:view-customers'])->group(function () {
        Route::resource('customers', CustomerController::class)->except(['store', 'update', 'destroy']);
    });
    Route::middleware(['permission:create-customers'])->group(function () {
        Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
    });
    Route::middleware(['permission:edit-customers'])->group(function () {
        Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    });
    Route::middleware(['permission:delete-customers'])->group(function () {
        Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });

    // Supplier Routes
    Route::middleware(['permission:view-suppliers'])->group(function () {
        Route::resource('suppliers', SupplierController::class)->except(['store', 'update', 'destroy']);
    });
    Route::middleware(['permission:create-suppliers'])->group(function () {
        Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    });
    Route::middleware(['permission:edit-suppliers'])->group(function () {
        Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    });
    Route::middleware(['permission:delete-suppliers'])->group(function () {
        Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    });

    // Product Routes
    Route::middleware(['permission:view-products'])->group(function () {
        Route::resource('products', ProductController::class)->except(['store', 'update', 'destroy']);
    });
    Route::middleware(['permission:create-products'])->group(function () {
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
    });
    Route::middleware(['permission:edit-products'])->group(function () {
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    });
    Route::middleware(['permission:delete-products'])->group(function () {
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Service Routes
    Route::middleware(['permission:view-services'])->group(function () {
        Route::resource('services', ServiceController::class)->except(['store', 'update', 'destroy']);
    });
    Route::middleware(['permission:create-services'])->group(function () {
        Route::post('services', [ServiceController::class, 'store'])->name('services.store');
    });
    Route::middleware(['permission:edit-services'])->group(function () {
        Route::put('services/{service}', [ServiceController::class, 'update'])->name('services.update');
    });
    Route::middleware(['permission:delete-services'])->group(function () {
        Route::delete('services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    });

    // Purchase Routes
    Route::middleware(['permission:view-purchases'])->group(function () {
        Route::resource('purchases', PurchaseController::class)->except(['store', 'update', 'destroy']);
    });
    Route::middleware(['permission:create-purchases'])->group(function () {
        Route::post('purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    });
    Route::middleware(['permission:edit-purchases'])->group(function () {
        Route::put('purchases/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
    });
    Route::middleware(['permission:delete-purchases'])->group(function () {
        Route::delete('purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');
    });

    // Stock Routes
    Route::middleware(['permission:view-stocks'])->group(function () {
        Route::resource('stocks', StockController::class)->except(['store', 'update', 'destroy']);
    });
    Route::middleware(['permission:create-stocks'])->group(function () {
        Route::post('stocks', [StockController::class, 'store'])->name('stocks.store');
    });
    Route::middleware(['permission:edit-stocks'])->group(function () {
        Route::put('stocks/{stock}', [StockController::class, 'update'])->name('stocks.update');
    });
    Route::middleware(['permission:delete-stocks'])->group(function () {
        Route::delete('stocks/{stock}', [StockController::class, 'destroy'])->name('stocks.destroy');
    });

    // Quotation Routes
    Route::middleware(['permission:view-quotations'])->group(function () {
        Route::resource('quotations', QuotationController::class)->except(['store', 'update', 'destroy']);
    });
    Route::middleware(['permission:create-quotations'])->group(function () {
        Route::post('quotations', [QuotationController::class, 'store'])->name('quotations.store');
    });
    Route::middleware(['permission:edit-quotations'])->group(function () {
        Route::put('quotations/{quotation}', [QuotationController::class, 'update'])->name('quotations.update');
    });
    Route::middleware(['permission:delete-quotations'])->group(function () {
        Route::delete('quotations/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.destroy');
    });

    // Invoice Routes
    Route::middleware(['permission:view-invoices'])->group(function () {
        Route::resource('invoices', InvoiceController::class)->except(['store', 'update', 'destroy']);
    });
    Route::middleware(['permission:create-invoices'])->group(function () {
        Route::post('invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    });
    Route::middleware(['permission:edit-invoices'])->group(function () {
        Route::put('invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    });
    Route::middleware(['permission:delete-invoices'])->group(function () {
        Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    });



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
