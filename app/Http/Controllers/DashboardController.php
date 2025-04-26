<?php

namespace App\Http\Controllers;

use App\Models\ims\Invoice;
use App\Models\ims\Purchase;
use App\Models\ims\Customer;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Fetching counts for various metrics
        $totalCustomers = Customer::count();
        $totalInvoices = Invoice::count();
        $totalPurchases = Purchase::sum('total');
        $totalSales = Invoice::sum('total');
        $profitOrLoss = $totalSales - $totalPurchases;

        // Monthly Sales Calculation
        $monthlyRawSales = Invoice::selectRaw('SUM(total) as total, MONTH(invoice_date) as month')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyRawPurchases = Purchase::selectRaw('SUM(total) as total, MONTH(invoice_date) as month')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlySales = array_fill(0, 12, 0);
        $monthlyPurchases = array_fill(0, 12, 0);

        foreach ($monthlyRawSales as $month => $total) {
            $monthlySales[$month - 1] = $total;
        }

        foreach ($monthlyRawPurchases as $month => $total) {
            $monthlyPurchases[$month - 1] = $total;
        }

        $statisticsData = [
            'sales' => $monthlySales,
            'purchases' => $monthlyPurchases,
        ];

        // Distinct customer demographics
        $districtData = Customer::select('city')->distinct()->pluck('city');
        $stateData = Customer::select('state')->distinct()->pluck('state');
        $countryData = Customer::select('country')->distinct()->pluck('country');

        $districtsCount = $districtData->count();
        $statesCount = $stateData->count();
        $countriesCount = $countryData->count();

        // Base query with eager loading for payments
        $query = Invoice::with('payment')->latest();

        // Apply filters
        if ($request->has('customer') && $request->customer != '') {
            $query->where('customer_id', $request->customer);
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('invoice_date', $request->date);
        }

        // Paginate filtered recent invoices
        $recentInvoicesFiltered = $query->paginate(8);

        // All customers for dropdown
        $customers = Customer::all();

        return view('dashboard', compact(
            'totalCustomers',
            'totalInvoices',
            'totalPurchases',
            'totalSales',
            'profitOrLoss',
            'recentInvoicesFiltered',
            'monthlySales',
            'monthlyPurchases',
            'months',
            'statisticsData',
            'districtsCount',
            'statesCount',
            'countriesCount',
            'totalCustomers',
            'districtData',
            'stateData',
            'countryData',
            'customers'
        ));
    }
}
