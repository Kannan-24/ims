<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\ims\Invoice;
use App\Models\ims\InvoiceItem;
use App\Models\ims\Stock;
use App\Models\ims\Customer;
use App\Models\ims\Service;
use App\Models\ims\ContactPerson;
use App\Models\ims\Product;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'customer.contactPersons']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->from) {
            $query->whereDate('invoice_date', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('invoice_date', '<=', $request->to);
        }

        $invoices = $query->latest()->get();

        return view('ims.invoices.index', compact('invoices'));
    }



    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::with('stock')->get();
        $services = Service::all();
        return view('ims.invoices.create', compact('customers', 'products', 'services'));
    }

    /**
     * Store a newly created invoice in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer' => 'required|exists:customers,id',
            'contact_person' => 'required|exists:contact_persons,id',
            'invoice_date' => 'required|date',
            'order_date' => 'required|date',
            'order_no' => 'required|string',
            'order_no_text' => 'nullable|string',
            'terms_condition' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*.product_id' => 'nullable|exists:products,id',
            'products.*.quantity' => 'required_with:products.*.product_id|integer|min:1',
            'products.*.unit_price' => 'required_with:products.*.product_id|numeric|min:0',
            'products.*.cgst' => 'nullable|numeric|min:0',
            'products.*.sgst' => 'nullable|numeric|min:0',
            'products.*.igst' => 'nullable|numeric|min:0',
            'products.*.total' => 'nullable|numeric|min:0',
            'services' => 'nullable|array',
            'services.*.service_id' => 'nullable|exists:services,id',
            'services.*.quantity' => 'required_with:services.*.service_id|integer|min:1',
            'services.*.unit_price' => 'required_with:services.*.service_id|numeric|min:0',
            'services.*.gst_total' => 'nullable|numeric|min:0',
            'services.*.total' => 'nullable|numeric|min:0',
        ]);

        $orderNo = $request->order_no === 'other' ? $request->order_no_text : $request->order_no;

        // Generate the invoice number automatically
        $currentYear = date('Y');
        $nextYear = date('y', strtotime('+1 year'));
        $previousYear = date('y', strtotime('-1 year'));
        $financialYear = (date('m') >= 4) ? $currentYear . '-' . $nextYear : $previousYear . '-' . date('y');

        $lastInvoice = Invoice::where('invoice_no', 'like', 'INV/' . $financialYear . '/%')->latest('id')->first();
        $lastInvoiceNumber = $lastInvoice ? (int)explode('/', $lastInvoice->invoice_no)[2] : 0;
        $newInvoiceNo = 'INV/' . $financialYear . '/' . ($lastInvoiceNumber + 1);

        $totalServiceGst = 0;

        // Calculate total GST from services
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                if (isset($service['gst_total'])) {
                    $totalServiceGst += $service['gst_total'];
                }
            }
        }

        foreach ($request->products as $product) {
            $stock = Stock::where('product_id', $product['product_id'])->get();

            if ($stock->isEmpty() || $stock->sum('quantity') - $stock->sum('sold') < $product['quantity']) {
                $productName = Product::find($product['product_id'])->name ?? 'Unknown Product';
                return redirect()->back()->withErrors([
                    'products' => "Product '{$productName}' is out of stock."
                ])->withInput();
            }
        }

        $invoice = Invoice::create([
            'customer_id' => $request->customer,
            'contactperson_id' => $request->contact_person,
            'invoice_no' => $newInvoiceNo,
            'invoice_date' => $request->invoice_date,
            'order_date' => $request->order_date,
            'order_no' => $orderNo,
            'order_no_text' => $request->order_no_text,
            'terms_condition' => $request->terms_condition,
            'sub_total' => (float)str_replace(',', '', $request->product_subtotal ?? 0) + (float)str_replace(',', '', $request->service_subtotal ?? 0),
            'cgst' => (float)str_replace(',', '', $request->product_total_cgst ?? 0) + ($totalServiceGst / 2),
            'sgst' => (float)str_replace(',', '', $request->product_total_sgst ?? 0) + ($totalServiceGst / 2),
            'igst' => (float)str_replace(',', '', $request->product_total_igst ?? 0),
            'gst' => (float)str_replace(',', '', $request->product_total_cgst ?? 0) + (float)str_replace(',', '', $request->product_total_sgst ?? 0) + (float)str_replace(',', '', $request->product_total_igst ?? 0) + $totalServiceGst,
            'total' => (float)str_replace(',', '', $request->grand_total ?? 0),
        ]);

        // Store products
        if ($request->has('products')) {
            foreach ($request->products as $product) {
                if (isset($product['product_id'])) {
                    $productModel = Product::findOrFail($product['product_id']);
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $product['product_id'],
                        'service_id' => null,
                        'quantity' => (int)$product['quantity'],
                        'unit_price' => (float)str_replace(',', '', $product['unit_price']),
                        'unit_type' => $productModel->unit_type,
                        'cgst' => (float)str_replace(',', '', $request->product_total_cgst ?? 0),
                        'sgst' => (float)str_replace(',', '', $request->product_total_sgst ?? 0),
                        'igst' => (float)str_replace(',', '', $request->product_total_igst ?? 0),
                        'gst' => (float)str_replace(',', '', ($request->product_total_cgst ?? 0) + ($request->product_total_sgst ?? 0) + ($request->product_total_igst ?? 0)),
                        'total' => (float)str_replace(',', '', $product['total']),
                        'type' => 'product',
                    ]);

                    // Update the sold column in the stock table
                    $stock = Stock::where('product_id', $product['product_id'])->first();
                    if ($stock) {
                        $stock->increment('sold', $product['quantity']);
                    }
                }
            }
        }

        // Store services
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                if (isset($service['service_id'])) {
                    $gstTotal = (float)str_replace(',', '', $service['gst_total'] ?? 0);
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => null,
                        'service_id' => $service['service_id'],
                        'quantity' => (int)$service['quantity'],
                        'unit_price' => (float)str_replace(',', '', $service['unit_price']),
                        'unit_type' => '-',
                        'cgst' => $gstTotal / 2,
                        'sgst' => $gstTotal / 2,
                        'igst' => 0,
                        'gst' => $gstTotal,
                        'total' => (float)str_replace(',', '', $service['total']),
                        'type' => 'service',
                    ]);
                }
            }
        }

        // Store payment record
        DB::table('payments')->insert([
            'invoice_id' => $invoice->id,
            'total_amount' => (float)str_replace(',', '', $request->grand_total ?? 0),
            'pending_amount' => (float)str_replace(',', '', $request->grand_total ?? 0),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified invoice.
     */
    public function show($id)
    {
        $invoice = Invoice::with(['customer', 'items.product', 'items.service'])->findOrFail($id);
        return view('ims.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $customers = Customer::all();
        $products = Product::all();
        $services = Service::all();
        return view('ims.invoices.edit', compact('invoice', 'customers', 'products', 'services'));
    }

    /**
     * Update the specified invoice in the database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer' => 'required|exists:customers,id',
            'contact_person' => 'required|exists:contact_persons,id',
            'invoice_date' => 'required|date',
            'order_date' => 'required|date',
            'order_no' => 'required|string',
            'grand_sub_total' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'product_total_cgst' => 'nullable|numeric|min:0',
            'product_total_sgst' => 'nullable|numeric|min:0',
            'product_total_igst' => 'nullable|numeric|min:0',
            'service_total_cgst' => 'nullable|numeric|min:0',
            'service_total_sgst' => 'nullable|numeric|min:0',
            'service_total_igst' => 'nullable|numeric|min:0',
            'products' => 'nullable|array',
            'products.*.product_id' => 'nullable|exists:products,id',
            'products.*.quantity' => 'required_with:products.*.product_id|integer|min:1',
            'products.*.unit_price' => 'required_with:products.*.product_id|numeric|min:0',
            'services' => 'nullable|array',
            'services.*.service_id' => 'nullable|exists:services,id',
            'services.*.quantity' => 'required_with:services.*.service_id|integer|min:1',
            'services.*.unit_price' => 'required_with:services.*.service_id|numeric|min:0',
        ]);

        $invoice = Invoice::findOrFail($id);

        // Revert the sold quantities for the old items
        foreach ($invoice->items as $item) {
            if ($item->product_id) { // Only revert stock for products, not services
                $stock = Stock::where('product_id', $item->product_id)->first();
                if ($stock) {
                    $stock->decrement('sold', $item->quantity);
                }
            }
        }

        // Check stock availability for new products
        if ($request->has('products') && is_array($request->products)) {
            foreach ($request->products as $product) {
                if (isset($product['product_id']) && !empty($product['product_id'])) {
                    $stock = Stock::where('product_id', $product['product_id'])->first();

                    if (!$stock || ($stock->quantity - $stock->sold) < $product['quantity']) {
                        $productName = Product::find($product['product_id'])->name ?? 'Unknown Product';
                        return redirect()->back()->withErrors([
                            'products' => "Product '{$productName}' is out of stock."
                        ])->withInput();
                    }
                }
            }
        }

        $invoice->update([
            'customer_id' => $request->customer,
            'contactperson_id' => $request->contact_person,
            'invoice_no' => $request->invoice_no,
            'invoice_date' => $request->invoice_date,
            'order_date' => $request->order_date,
            'order_no' => $request->order_no,
            'sub_total' => $request->grand_sub_total,
            'cgst' => ($request->product_total_cgst ?? 0) + ($request->service_total_cgst ?? 0),
            'sgst' => ($request->product_total_sgst ?? 0) + ($request->service_total_sgst ?? 0),
            'igst' => ($request->product_total_igst ?? 0) + ($request->service_total_igst ?? 0),
            'gst' => (($request->product_total_cgst ?? 0) + ($request->service_total_cgst ?? 0)) +
                (($request->product_total_sgst ?? 0) + ($request->service_total_sgst ?? 0)) +
                (($request->product_total_igst ?? 0) + ($request->service_total_igst ?? 0)),
            'total' => $request->grand_total,
        ]);

        // Delete old items and add new ones
        InvoiceItem::where('invoice_id', $invoice->id)->delete();

        // Store products
        if ($request->has('products') && is_array($request->products)) {
            foreach ($request->products as $product) {
                if (isset($product['product_id']) && !empty($product['product_id'])) {
                    $productModel = Product::findOrFail($product['product_id']);

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $product['product_id'],
                        'service_id' => null,
                        'quantity' => $product['quantity'],
                        'unit_price' => $product['unit_price'],
                        'unit_type' => $productModel->unit_type,
                        'cgst' => $product['cgst'] ?? 0,
                        'sgst' => $product['sgst'] ?? 0,
                        'igst' => $product['igst'] ?? 0,
                        'gst' => ($product['cgst'] ?? 0) + ($product['sgst'] ?? 0) + ($product['igst'] ?? 0),
                        'total' => $product['total'],
                        'type' => 'product',
                    ]);

                    // Update the sold column in the stock table
                    $stock = Stock::where('product_id', $product['product_id'])->first();
                    if ($stock) {
                        $stock->increment('sold', $product['quantity']);
                    }
                }
            }
        }

        // Store services
        if ($request->has('services') && is_array($request->services)) {
            foreach ($request->services as $service) {
                if (isset($service['service_id']) && !empty($service['service_id'])) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => null,
                        'service_id' => $service['service_id'],
                        'quantity' => $service['quantity'],
                        'unit_price' => $service['unit_price'],
                        'unit_type' => '-',
                        'cgst' => $service['cgst'] ?? 0,
                        'sgst' => $service['sgst'] ?? 0,
                        'igst' => $service['igst'] ?? 0,
                        'gst' => ($service['cgst'] ?? 0) + ($service['sgst'] ?? 0) + ($service['igst'] ?? 0),
                        'total' => $service['total'],
                        'type' => 'service',
                    ]);
                }
            }
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified invoice from the database.
     */
    public function destroy($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);

        // Revert the sold quantities for the items in the invoice
        foreach ($invoice->items as $item) {
            $stock = Stock::where('product_id', $item->product_id)->first();
            if ($stock) {
                $stock->decrement('sold', $item->quantity);
            }
        }

        // Delete the invoice and its items
        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function generatePDF($id)
    {
        $invoice = Invoice::with(['items', 'customer'])->findOrFail($id);

        $pdf = Pdf::loadView('ims.invoices.pdf', compact('invoice'))->setPaper('a4', 'portrait');

        return $pdf->stream('Invoice_' . $invoice->invoice_no . '.pdf');
    }
}
