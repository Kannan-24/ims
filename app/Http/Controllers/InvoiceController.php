<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Stock;
use App\Models\Customer;
use App\Models\Service;
use App\Models\ContactPerson;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->get();
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::with('stock')->get();
        $services = Service::all();
        return view('invoices.create', compact('customers', 'products', 'services'));
    }

    /**
     * Store a newly created invoice in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer' => 'required|exists:customers,id',
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
        $lastInvoice = Invoice::latest('id')->first();
        $newInvoiceNo = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 3, '0', STR_PAD_LEFT);

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
            $stock = Stock::where('product_id', $product['product_id'])->first();

            if (!$stock || ($stock->quantity - $stock->sold) < $product['quantity']) {
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
            'sub_total' => $request->product_subtotal + $request->service_subtotal,
            'cgst' => $request->product_total_cgst + ($totalServiceGst / 2),
            'sgst' => $request->product_total_sgst + ($totalServiceGst / 2),
            'igst' => $request->product_total_igst,
            'gst' => $request->product_total_cgst + $request->product_total_sgst + $request->product_total_igst + $totalServiceGst,
            'total' => $request->grand_total,
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
                        'quantity' => $product['quantity'],
                        'unit_price' => $product['unit_price'],
                        'unit_type' => $productModel->unit_type,
                        'cgst' => $request->product_total_cgst,
                        'sgst' => $request->product_total_sgst,
                        'igst' => $request->product_total_igst,
                        'gst' => $request->product_total_cgst + $request->product_total_sgst + $request->product_total_igst,
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
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                if (isset($service['service_id'])) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => null,
                        'service_id' => $service['service_id'],
                        'quantity' => $service['quantity'],
                        'unit_price' => $service['unit_price'],
                        'unit_type' => '-',
                        'cgst' => $service['gst_total'] / 2,
                        'sgst' => $service['gst_total'] / 2,
                        'igst' => 0,
                        'gst' => $service['gst_total'],
                        'total' => $service['total'],
                        'type' => 'service',
                    ]);
                }
            }
        }

        // Store payment record
        DB::table('payments')->insert([
            'invoice_id' => $invoice->id,
            'amount' => $request->grand_total,
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
        return view('invoices.show', compact('invoice'));
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
        return view('invoices.edit', compact('invoice', 'customers', 'products', 'services'));
    }

    /**
     * Update the specified invoice in the database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer' => 'required|exists:customers,id',
            'contact_person' => 'required|exists:contact_persons,id',
            'invoice_no' => 'required|unique:invoices,invoice_no,' . $id,
            'invoice_date' => 'required|date',
            'order_date' => 'required|date',
            'order_no' => 'required|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::findOrFail($id);

        // Revert the sold quantities for the old items
        foreach ($invoice->items as $item) {
            $stock = Stock::where('product_id', $item->product_id)->first();
            if ($stock) {
                $stock->decrement('sold', $item->quantity);
            }
        }

        // Check stock availability for new products
        foreach ($request->products as $product) {
            $stock = Stock::where('product_id', $product['product_id'])->first();

            if (!$stock || ($stock->quantity - $stock->sold) < $product['quantity']) {
                $productName = Product::find($product['product_id'])->name ?? 'Unknown Product';
                return redirect()->back()->withErrors([
                    'products' => "Product '{$productName}' is out of stock."
                ])->withInput();
            }
        }

        $invoice->update([
            'customer_id' => $request->customer,
            'contactperson_id' => $request->contact_person,
            'invoice_no' => $request->invoice_no,
            'invoice_date' => $request->invoice_date,
            'order_date' => $request->order_date,
            'order_no' => $request->order_no,
            'sub_total' => $request->subtotal,
            'cgst' => $request->total_cgst,
            'sgst' => $request->total_sgst,
            'igst' => $request->total_igst,
            'gst' => $request->total_cgst + $request->total_sgst + $request->total_igst,
            'total' => $request->grand_total,
        ]);

        // Delete old items and add new ones
        InvoiceItem::where('invoice_id', $invoice->id)->delete();

        foreach ($request->products as $product) {
            $productModel = Product::findOrFail($product['product_id']);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price'],
                'unit_type' => $productModel->unit_type,
                'cgst' => ($product['unit_price'] * $product['cgst']) / 100,
                'sgst' => ($product['unit_price'] * $product['sgst']) / 100,
                'igst' => ($product['unit_price'] * $product['igst']) / 100,
                'total' => $product['total'],
            ]);

            // Update the sold column in the stock table
            $stock = Stock::where('product_id', $product['product_id'])->first();
            if ($stock) {
                $stock->increment('sold', $product['quantity']);
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
}
