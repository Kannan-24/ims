<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Stock;
use App\Models\Customer;
use App\Models\ContactPerson;
use App\Models\Product;

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
        $products = Product::all();
        return view('invoices.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created invoice in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer' => 'required|exists:customers,id',
            'contact_person' => 'required|exists:contact_persons,id',
            'invoice_no' => 'required|unique:invoices,invoice_no',
            'invoice_date' => 'required|date',
            'order_date' => 'required|date',
            'order_no' => 'required|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Create the invoice
        $invoice = Invoice::create([
            'customer_id' => $request->customer,
            'contactperson_id' => $request->contact_person,
            'invoice_no' => $request->invoice_no,
            'invoice_date' => $request->invoice_date,
            'order_date' => $request->order_date,
            'order_no' => $request->order_no,
            'sub_total' => $request->sub_total,
            'cgst' => $request->total_cgst,
            'sgst' => $request->total_sgst,
            'igst' => $request->total_igst,
            'gst' => $request->total_cgst + $request->total_sgst + $request->total_igst,
            'total' => $request->total,
        ]);

        // Save invoice items and update stock
        foreach ($request->products as $product) {
            $productModel = Product::findOrFail($product['product_id']);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price'],
                'unit_type' => $productModel->unit_type,
                'cgst' => $product['cgst'],
                'sgst' => $product['sgst'],
                'igst' => $product['igst'],
                'total' => $product['total'],
            ]);

            // Update the sold column in the stock table
            $stock = Stock::where('product_id', $product['product_id'])->first();
            if ($stock) {
                $stock->increment('sold', $product['quantity']);
            }
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified invoice.
     */
    public function show($id)
    {
        $invoice = Invoice::with('customer', 'items.product')->findOrFail($id);
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
        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    /**
     * Update the specified invoice in the database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'contactperson_id' => 'required|exists:contact_persons,id',
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

        $invoice->update([
            'customer_id' => $request->customer_id,
            'contactperson_id' => $request->contactperson_id,
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
                'cgst' => $product['cgst'],
                'sgst' => $product['sgst'],
                'igst' => $product['igst'],
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
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
