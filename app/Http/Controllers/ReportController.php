<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;
use App\Exports\SupplierExport;
use App\Exports\InvoiceExport;
use App\Exports\QuotationExport;
use Carbon\Carbon;
use App\Exports\StockExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
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
        $pdf = Pdf::loadView('reports.customers_pdf', compact('customers'));
        return $pdf->stream('customers-report.pdf');
    }


    public function customersExcel()
    {
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
        $suppliers = \App\Models\Supplier::all();
        $pdf = Pdf::loadView('reports.suppliers_pdf', compact('suppliers'))->setPaper('a4', 'landscape');
        return $pdf->stream('suppliers-report.pdf');
    }

    public function suppliersExcel()
    {
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

        // Generate readable range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $range = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $range = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $range = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $range = 'Last 30 days';
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $range = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } else {
            $range = 'All Time';
        }

        $generatedAt = Carbon::now()->format('d-m-Y h:i A');

        return Excel::download(
            new InvoiceExport($invoices, $generatedAt, $range),
            'invoices_report.xlsx'
        );
    }

    public function invoicesPdf(Request $request)
    {
        $invoices = $this->filterInvoices($request)->get();

        // Generate readable range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $dateRange = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $dateRange = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $dateRange = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $dateRange = 'Last 30 Days';
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $dateRange = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } else {
            $dateRange = 'All Time';
        }

        // Generate PDF with the date range
        $pdf = Pdf::loadView('reports.invoices_pdf', compact('invoices', 'dateRange'))
            ->setPaper('a4', 'landscape');

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

        // Generate readable range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $range = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $range = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $range = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $range = 'Last 30 Days';
        } elseif ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $range = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } else {
            $range = 'All Time';
        }

        $generatedAt = Carbon::now()->format('d-m-Y h:i A');

        return Excel::download(
            new QuotationExport($quotations, $generatedAt, $range),
            'quotations_report.xlsx'
        );
    }

    public function quotationsPdf(Request $request)
    {
        $quotations = $this->filterQuotations($request)->get();

        // Generate readable range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $dateRange = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } elseif ($request->range === 'last_7_days') {
            $dateRange = 'Last 7 Days';
        } elseif ($request->range === 'last_15_days') {
            $dateRange = 'Last 15 Days';
        } elseif ($request->range === 'last_30_days') {
            $dateRange = 'Last 30 Days';
        } elseif ($request->range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $dateRange = Carbon::parse($request->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->end_date)->format('d-m-Y');
        } else {
            $dateRange = 'All Time';
        }

        // Generate PDF with the date range
        $pdf = Pdf::loadView('reports.quotations_pdf', compact('quotations', 'dateRange'))
            ->setPaper('a4', 'landscape');

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
    // ---------- Stock ----------
    public function stocks()
    {
        $products = Product::all();
        return view('reports.stocks', compact('products'));
    }

    public function stocksPdf()
    {
        $products = Product::all();
        $pdf = Pdf::loadView('reports.stocks_pdf', compact('products'));
        return $pdf->download('stocks-report.pdf');
    }

    public function stocksExcel()
    {
        return Excel::download(new StockExport, 'stocks-report.xlsx');
    }

    public function show($type)
    {
        switch ($type) {
            case 'customers':
                $customers = Customer::all();
                return view('reports.customers', compact('customers'));

            case 'suppliers':
                $suppliers = Supplier::all();
                return view('reports.suppliers', compact('suppliers'));

            case 'invoices':
                $invoices = Invoice::with('customer')->get();
                return view('reports.invoices', compact('invoices'));

            case 'quotations':
                $quotations = Quotation::with('customer')->get();
                return view('reports.quotations', compact('quotations'));

            case 'stocks':
                $products = Product::with('category')->get();
                return view('reports.stocks', compact('products'));

            default:
                abort(404);
        }
    }
}
