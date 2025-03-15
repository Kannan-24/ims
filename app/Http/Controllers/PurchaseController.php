<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseItem;
use App\Models\Stock;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::all();
        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'invoice_no' => 'required|string',
            'subtotal' => 'required|numeric',
            'total_cgst' => 'required|numeric',
            'total_sgst' => 'required|numeric',
            'total_igst' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric',
            'products.*.unit_price' => 'required|numeric',
            'products.*.cgst' => 'required|numeric',
            'products.*.sgst' => 'required|numeric',
            'products.*.igst' => 'required|numeric',
            'products.*.total' => 'required|numeric',
        ]);

        $purchase = Purchase::updateOrCreate([
            'supplier_id' => $request->supplier_id,
            'invoice_date' => $request->purchase_date,
            'invoice_no' => $request->invoice_no,
        ],
        [
            'sub_total' => $request->subtotal,
            'cgst' => $request->total_cgst,
            'sgst' => $request->total_sgst,
            'igst' => $request->total_igst,
            'gst' => $request->total_cgst + $request->total_sgst + $request->total_igst,
            'total' => $request->grand_total,
        ]);

        foreach ($request->products as $product) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'unit_type' => Product::find($product['product_id'])->unit_type ?? 'unit',
                'unit_price' => $product['unit_price'],
                'cgst' => $product['cgst'],
                'sgst' => $product['sgst'],
                'igst' => $product['igst'],
                'total' => $product['total'],
            ]);

            Stock::create([
                'product_id' => $product['product_id'],
                'supplier_id' => $request->supplier_id,
                'unit_type' => Product::find($product['product_id'])->unit_type ?? 'unit',
                'quantity' => $product['quantity'],
                'batch_code' => $request->invoice_no,
            ]);
        }

        return redirect()->route('purchases.index')->with('response', [
            'status' => 'success',
            'message' => 'Purchase added successfully!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
