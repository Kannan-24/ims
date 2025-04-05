<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Customer;
use App\Models\ContactPerson;
use App\Models\Product;
use PDF; // Import PDF class from the package


class QuotationController extends Controller
{
    /**
     * Display a listing of the quotations.
     */
    public function index()
    {
        $quotations = Quotation::with('customer')->latest()->get();
        return view('quotations.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new quotation.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('quotations.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created quotation in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'quotation_code' => 'required|unique:quotations,quotation_code',
            'quotation_date' => 'required|date',
            'terms_condition' => 'nullable|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Create the quotation
        $quotation = Quotation::create([
            'customer_id' => $request->customer_id,
            'quotation_code' => $request->quotation_code,
            'quotation_date' => $request->quotation_date,
            'terms_condition' => $request->terms_condition,
            'sub_total' => $request->sub_total,
            'cgst' => $request->total_cgst,
            'sgst' => $request->total_sgst,
            'igst' => $request->total_igst,
            'gst' => $request->total_cgst + $request->total_sgst + $request->total_igst,
            'total' => $request->total,
        ]);

        // Save quotation items
        foreach ($request->products as $product) {
            $productModel = Product::findOrFail($product['product_id']);

            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price'],
                'unit_type' => $productModel->unit_type,
                'cgst' => $request->total_cgst,
                'sgst' => $request->total_sgst,
                'igst' => $request->total_igst,
                'total' => $product['total'],
            ]);
        }

        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully.');
    }

    /**
     * Display the specified quotation.
     */
    public function show($id)
    {
        $quotation = Quotation::with('customer', 'items.product')->findOrFail($id);
        return view('quotations.show', compact('quotation'));
    }

    /**
     * Show the form for editing the specified quotation.
     */
    public function edit($id)
    {
        $quotation = Quotation::with('items')->findOrFail($id);
        $customers = Customer::all();
        $products = Product::all();
        return view('quotations.edit', compact('quotation', 'customers', 'products'));
    }

    /**
     * Update the specified quotation in the database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'quotation_date' => 'required|date',
            'terms_condition' => 'nullable|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        $quotation = Quotation::findOrFail($id);
        $quotation->update([
            'customer_id' => $request->customer_id,
            'quotation_code' => $request->quotation_code,
            'quotation_date' => $request->quotation_date,
            'terms_condition' => $request->terms_condition,
            'sub_total' => $request->subtotal,
            'cgst' => $request->total_cgst,
            'sgst' => $request->total_sgst,
            'igst' => $request->total_igst,
            'gst' => $request->total_cgst + $request->total_sgst + $request->total_igst,
            'total' => $request->grand_total,
        ]);

        // Delete old items and add new ones
        QuotationItem::where('quotation_id', $quotation->id)->delete();

        foreach ($request->products as $product) {
            $productModel = Product::findOrFail($product['product_id']);
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price'],
                'unit_type' => $productModel->unit_type, 
                'cgst' => $request->total_cgst,
                'sgst' => $request->total_sgst,
                'igst' => $request->total_igst,
                'total' => $product['total'],
            ]);
        }

        return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully.');
    }

    /**
     * Remove the specified quotation from the database.
     */
    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->delete();

        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }


    public function generatePDF($id)
    {
        $quotation = Quotation::with('customer', 'items.product')->findOrFail($id);

        $pdf = PDF::loadView('quotations.pdf', compact('quotation'));

        return $pdf->download('quotation_' . $quotation->quotation_code . '.pdf');
    }
}
