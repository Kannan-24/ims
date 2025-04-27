<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;

use App\Models\ims\Customer;
use App\Models\ims\Supplier;
use App\Models\ims\Invoice;
use App\Models\ims\Quotation;
use App\Models\ims\Product;
use App\Models\ims\Purchase;
use App\Models\ims\Stock;
use App\Models\ims\Payment;
use App\Exports\CustomerExport;
use App\Exports\SupplierExport;
use App\Exports\InvoiceExport;
use App\Exports\QuotationExport;
use App\Exports\StockExport;
use App\Exports\PurchaseExport;
use App\Exports\PaymentExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\ActivityLogger;

class ReportController extends Controller
{

    public function show($type)
    {
        switch ($type) {
            case 'customers':
                $customers = Customer::all();
                return view('ims.reports.customers', compact('customers'));

            case 'suppliers':
                $suppliers = Supplier::all();
                return view('ims.reports.suppliers', compact('suppliers'));

            case 'invoices':
                $invoices = Invoice::with('customer')->get();
                return view('ims.reports.invoices', compact('invoices'));

            case 'quotations':
                $quotations = Quotation::with('customer')->get();
                return view('ims.reports.quotations', compact('quotations'));

            case 'purchases':
                $purchases = Purchase::with('supplier')->get();
                return view('ims.reports.purchases', compact('purchases'));

            case 'stocks':
                $stocks = Stock::all();
                $suppliers = Supplier::all();
                $products = Product::all();
                return view('ims.reports.stocks', compact('stocks', 'suppliers', 'products'));

            case 'payments':
                $payments = Payment::with('customer')->get();
                return view('ims.reports.payments', compact('payments'));

            default:
                abort(404);
        }
    }

    // ---------- Index ----------
    public function index()
    {
        return view('ims.reports.index');
    }

    // ---------- Customer ----------
    public function customers()
    {
        $customers = Customer::with('contactPersons')->get();
        return view('reports.customers', compact('customers'));
    }

    public function customersPdf()
    {
        $customers = Customer::all();
        $pdf = Pdf::loadView('ims.reports.customers_pdf', compact('customers'));

        ActivityLogger::log(
            'PDF Generated',
            'Customers',
            'Customer PDF report generated'
        );

        return $pdf->stream('customers-report.pdf');
    }

    public function customersExcel()
    {
        ActivityLogger::log(
            'Excel Generated',
            'Customers',
            'Customer Excel report generated'
        );

        return Excel::download(new CustomerExport, 'customers-report.xlsx');
    }

    // ---------- Supplier ----------
    public function suppliers()
    {
        $suppliers = Supplier::all();
        return view('reports.suppliers', compact('suppliers'));
    }

    public function suppliersPdf()
    {
        $suppliers = \App\Models\ims\Supplier::all();
        $pdf = Pdf::loadView('ims.reports.suppliers_pdf', compact('suppliers'))->setPaper('a4', 'landscape');

        ActivityLogger::log(
            'PDF Generated',
            'Suppliers',
            'Supplier PDF report generated'
        );

        return $pdf->stream('suppliers-report.pdf');
    }

    public function suppliersExcel()
    {
        ActivityLogger::log(
            'Excel Generated',
            'Suppliers',
            'Supplier Excel report generated'
        );

        return Excel::download(new SupplierExport, 'suppliers-report.xlsx');
    }

    // ---------- Invoice ----------
    public function invoices(Request $request)
    {
        $invoices = $this->filterInvoices($request)->latest()->get();

        return view('reports.invoices', compact('invoices'));
    }

    public function invoicesExcel(Request $request)
    {
        $invoices = $this->filterInvoices($request)->get();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $range = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $range = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $range = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $range = 'Last 30 days';
        } else {
            $range = 'All Data';
        }

        $generatedAt = Carbon::now()->format('d-m-Y h:i A');

        ActivityLogger::log(
            'Excel Generated',
            'Invoices',
            'Invoice Excel report generated'
        );

        return Excel::download(
            new InvoiceExport($invoices, $generatedAt, $range),
            'invoices_report.xlsx'
        );
    }

    public function invoicesPdf(Request $request)
    {
        $invoices = $this->filterInvoices($request)->get();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $dateRange = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $dateRange = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $dateRange = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $dateRange = 'Last 30 Days';
        } else {
            $dateRange = 'All Data';
        }

        $pdf = Pdf::loadView('ims.reports.invoices_pdf', compact('invoices', 'dateRange'))
            ->setPaper('a4', 'landscape');

        ActivityLogger::log(
            'PDF Generated',
            'Invoices',
            'Invoice PDF report generated'
        );

        return $pdf->stream('invoices_report.pdf');
    }

    private function filterInvoices(Request $request)
    {
        $query = Invoice::with('customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%$search%")
                    ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%$search%"));
            });
        }

        if ($request->range === 'last_7_days') {
            $query->whereDate('invoice_date', '>=', Carbon::now()->subDays(7));
        } elseif ($request->range === 'last_15_days') {
            $query->whereDate('invoice_date', '>=', Carbon::now()->subDays(15));
        } elseif ($request->range === 'last_30_days') {
            $query->whereDate('invoice_date', '>=', Carbon::now()->subDays(30));
        } elseif ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('invoice_date', [
                Carbon::parse($request->start_date),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        return $query;
    }

    // ---------- Quotation ----------
    public function quotations(Request $request)
    {
        $quotations = $this->filterQuotations($request)->latest()->get();

        return view('reports.quotations', compact('quotations'));
    }

    public function quotationsExcel(Request $request)
    {
        $quotations = $this->filterQuotations($request)->get();

        if ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $range = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $range = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $range = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $range = 'Last 30 Days';
        } else {
            $range = 'All Data';
        }

        $generatedAt = Carbon::now()->format('d-m-Y h:i A');

        ActivityLogger::log(
            'Excel Generated',
            'Quotations',
            'Quotation Excel report generated'
        );

        return Excel::download(
            new QuotationExport($quotations, $generatedAt, $range),
            'quotations_report.xlsx'
        );
    }

    public function quotationsPdf(Request $request)
    {
        $quotations = $this->filterQuotations($request)->get();

        if ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $dateRange = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $dateRange = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $dateRange = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $dateRange = 'Last 30 Days';
        } else {
            $dateRange = 'All Data';
        }

        $pdf = Pdf::loadView('ims.reports.quotations_pdf', compact('quotations', 'dateRange'))
            ->setPaper('a4', 'landscape');

        ActivityLogger::log(
            'PDF Generated',
            'Quotations',
            'Quotation PDF report generated'
        );

        return $pdf->stream('quotations_report.pdf');
    }

    private function filterQuotations(Request $request)
    {
        $query = Quotation::with('customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%$search%")
                    ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%$search%"));
            });
        }

        if ($request->range === 'last_7_days') {
            $query->whereDate('quotation_date', '>=', Carbon::now()->subDays(7));
        } elseif ($request->range === 'last_15_days') {
            $query->whereDate('quotation_date', '>=', Carbon::now()->subDays(15));
        } elseif ($request->range === 'last_30_days') {
            $query->whereDate('quotation_date', '>=', Carbon::now()->subDays(30));
        } elseif ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('quotation_date', [
                Carbon::parse($request->start_date),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        return $query;
    }

    // ---------- Purchase ----------
    public function purchases(Request $request)
    {
        $purchases = $this->filterPurchases($request)->latest()->get();

        return view('reports.purchases', compact('purchases'));
    }

    public function purchasesExcel(Request $request)
    {
        $purchases = $this->filterPurchases($request)->get();

        if ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $range = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $range = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $range = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $range = 'Last 30 Days';
        } else {
            $range = 'All Data';
        }

        $generatedAt = Carbon::now()->format('d-m-Y h:i A');

        ActivityLogger::log(
            'Excel Generated',
            'Purchases',
            'Purchase Excel report generated'
        );

        return Excel::download(
            new PurchaseExport($purchases, $generatedAt, $range),
            'purchases_report.xlsx'
        );
    }

    public function purchasesPdf(Request $request)
    {
        $purchases = $this->filterPurchases($request)->get();

        if ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $dateRange = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $dateRange = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $dateRange = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $dateRange = 'Last 30 Days';
        } else {
            $dateRange = 'All Data';
        }

        $pdf = Pdf::loadView('ims.reports.purchases_pdf', compact('purchases', 'dateRange'))
            ->setPaper('a4', 'landscape');

        ActivityLogger::log(
            'PDF Generated',
            'Purchases',
            'Purchase PDF report generated'
        );

        return $pdf->stream('purchases_report.pdf');
    }

    private function filterPurchases(Request $request)
    {
        $query = \App\Models\ims\Purchase::with('supplier');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%$search%")
                    ->orWhereHas('supplier', fn($q2) => $q2->where('name', 'like', "%$search%"));
            });
        }

        if ($request->range === 'last_7_days') {
            $query->whereDate('invoice_date', '>=', Carbon::now()->subDays(7));
        } elseif ($request->range === 'last_15_days') {
            $query->whereDate('invoice_date', '>=', Carbon::now()->subDays(15));
        } elseif ($request->range === 'last_30_days') {
            $query->whereDate('invoice_date', '>=', Carbon::now()->subDays(30));
        } elseif ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('invoice_date', [
                Carbon::parse($request->start_date),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        return $query;
    }

    // ---------- Stock ----------
    public function stocks(Request $request)
    {
        $stocks = $this->filterStocks($request)->latest()->get();

        return view('reports.stocks', compact('stocks'));
    }

    public function stocksExcel(Request $request)
    {
        $stocks = $this->filterStocks($request)->get();

        if ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $range = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $range = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $range = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $range = 'Last 30 Days';
        } else {
            $range = 'All Data';
        }

        $generatedAt = Carbon::now()->format('d-m-Y h:i A');

        ActivityLogger::log(
            'Excel Generated',
            'Stocks',
            'Stock Excel report generated'
        );

        return Excel::download(
            new StockExport($stocks, $generatedAt, $range),
            'stocks_report.xlsx'
        );
    }

    public function stocksPdf(Request $request)
    {
        $stocks = $this->filterStocks($request)->get();

        if ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $dateRange = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $dateRange = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $dateRange = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $dateRange = 'Last 30 Days';
        } else {
            $dateRange = 'All Data';
        }

        $pdf = Pdf::loadView('ims.reports.stocks_pdf', compact('stocks', 'dateRange'))
            ->setPaper('a4', 'landscape');

        ActivityLogger::log(
            'PDF Generated',
            'Stocks',
            'Stock PDF report generated'
        );

        return $pdf->stream('stocks_report.pdf');
    }

    private function filterStocks(Request $request)
    {
        $query = Stock::with(['product', 'supplier']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('batch_code', 'like', "%$search%")
                    ->orWhereHas('product', fn($q2) => $q2->where('name', 'like', "%$search%"))
                    ->orWhereHas('supplier', fn($q3) => $q3->where('name', 'like', "%$search%"));
            });
        }

        // Filter by suppliers
        if ($request->filled('supplier_ids')) {
            $query->whereIn('supplier_id', $request->supplier_ids);
        }

        // Filter by products
        if ($request->filled('product_ids')) {
            $query->whereIn('product_id', $request->product_ids);
        }

        // Filter by date range
        if ($request->range) {
            $now = now();

            switch ($request->range) {
                case 'last_7_days':
                    $query->where('created_at', '>=', $now->copy()->subDays(7));
                    break;
                case 'last_15_days':
                    $query->where('created_at', '>=', $now->copy()->subDays(15));
                    break;
                case 'last_30_days':
                    $query->where('created_at', '>=', $now->copy()->subDays(30));
                    break;
                case 'custom':
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        $startDate = Carbon::parse($request->start_date)->startOfDay();
                        $endDate = Carbon::parse($request->end_date)->endOfDay();
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    }
                    break;
            }
        }
        return $query;
    }

    // ---------- Payment ----------
    public function payments(Request $request)
    {
        $payments = $this->filterPayments($request)->latest()->get();

        return view('reports.payments', compact('payments'));
    }

    public function paymentsExcel(Request $request)
    {
        $payments = $this->filterPayments($request)->get();

        if ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $range = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $range = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $range = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $range = 'Last 30 Days';
        } else {
            $range = 'All Data';
        }

        $generatedAt = Carbon::now()->format('d-m-Y h:i A');

        ActivityLogger::log(
            'Excel Generated',
            'Payments',
            'Payment Excel report generated'
        );

        return Excel::download(
            new PaymentExport($payments, $generatedAt, $range),
            'payments_report.xlsx'
        );
    }
    public function paymentsPdf(Request $request)
    {
        // Get the filtered payments along with their paymentItems
        $payments = $this->filterPayments($request)->with('paymentItems')->get();

        // Date range logic for report
        if ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $dateRange = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $dateRange = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $dateRange = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $dateRange = 'Last 30 Days';
        } else {
            $dateRange = 'All Data';
        }

        $pdf = Pdf::loadView('ims.reports.payments_pdf', compact('payments', 'dateRange'))
            ->setPaper('a4', 'landscape');

        ActivityLogger::log(
            'PDF Generated',
            'Payments',
            'Payment PDF report generated'
        );

        return $pdf->stream('payments_report.pdf');
    }


    private function filterPayments(Request $request)
    {
        $query = Payment::with('customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_number', 'like', "%$search%")
                    ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%$search%"));
            });
        }

        if ($request->range === 'last_7_days') {
            $query->whereDate('payment_date', '>=', Carbon::now()->subDays(7));
        } elseif ($request->range === 'last_15_days') {
            $query->whereDate('payment_date', '>=', Carbon::now()->subDays(15));
        } elseif ($request->range === 'last_30_days') {
            $query->whereDate('payment_date', '>=', Carbon::now()->subDays(30));
        } elseif ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('payment_date', [
                Carbon::parse($request->start_date),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        return $query;
    }
}
