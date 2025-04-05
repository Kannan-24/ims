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

        return view('purchases.create', [
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
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

        // Find or create the purchase
        $purchase = Purchase::updateOrCreate(
            [
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
            ]
        );

        $latestBatch = \App\Models\Stock::latest()->first();
        $nextLetter = 'A';
        $nextNumber = 1;

        if ($latestBatch && preg_match('/Batch_([A-Z])(\d{3})/', $latestBatch->batch_code, $matches)) {
            $currentLetter = $matches[1];
            $currentNumber = (int)$matches[2];

            if ($currentNumber >= 999) {
                $nextLetter = chr(ord($currentLetter) + 1); // Move to next letter
                $nextNumber = 1;
            } else {
                $nextLetter = $currentLetter;
                $nextNumber = $currentNumber + 1;
            }
        }

        $batchCode = "Batch_{$nextLetter}" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // **Loop through products and use the SAME batch code**
        foreach ($request->products as $product) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'unit_type' => Product::find($product['product_id'])->unit_type ?? 'unit',
                'unit_price' => $product['unit_price'],
                'cgst' => $request->total_cgst,
                'sgst' => $request->total_sgst,
                'igst' => $request->total_igst,
                'total' => $product['total'],
            ]);

            Stock::create([
                'product_id' => $product['product_id'],
                'purchase_id' => $purchase->id,
                'supplier_id' => $request->supplier_id,
                'unit_type' => Product::find($product['product_id'])->unit_type ?? 'unit',
                'quantity' => $product['quantity'],
                'batch_code' => $batchCode, // ✅ SAME batch code for all products
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
    public function show($id)
    {
        $purchase = Purchase::with(['supplier', 'items.product'])->findOrFail($id);

        return view('purchases.show', compact('purchase'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        $purchaseItems = $purchase->purchaseItems;

        return view('purchases.edit', [
            'purchase' => $purchase,
            'suppliers' => $suppliers,
            'products' => $products,
            'purchaseItems' => $purchaseItems,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
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

        $purchase->update([
            'supplier_id' => $request->supplier_id,
            'invoice_date' => $request->purchase_date,
            'invoice_no' => $request->invoice_no,
            'sub_total' => $request->subtotal,
            'cgst' => $request->total_cgst,
            'sgst' => $request->total_sgst,
            'igst' => $request->total_igst,
            'gst' => $request->total_cgst + $request->total_sgst + $request->total_igst,
            'total' => $request->grand_total,
        ]);

        // Delete old purchase items and stocks
        $purchase->purchaseItems()->delete();
        $purchase->stocks()->delete();

        // Generate new batch code
        $latestBatch = \App\Models\Stock::latest()->first();
        $nextLetter = 'A';
        $nextNumber = 1;

        if ($latestBatch && preg_match('/Batch_([A-Z])(\d{3})/', $latestBatch->batch_code, $matches)) {
            $currentLetter = $matches[1];
            $currentNumber = (int)$matches[2];

            if ($currentNumber >= 999) {
                $nextLetter = chr(ord($currentLetter) + 1);
                $nextNumber = 1;
            } else {
                $nextLetter = $currentLetter;
                $nextNumber = $currentNumber + 1;
            }
        }

        $batchCode = "Batch_{$nextLetter}" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Loop through products and use the SAME batch code
        foreach ($request->products as $product) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'unit_type' => Product::find($product['product_id'])->unit_type ?? 'unit',
                'unit_price' => $product['unit_price'],
                'cgst' => $request->total_cgst,
                'sgst' => $request->total_sgst,
                'igst' => $request->total_igst,
                'total' => $product['total'],
            ]);

            Stock::create([
                'product_id' => $product['product_id'],
                'purchase_id' => $purchase->id,
                'supplier_id' => $request->supplier_id,
                'unit_type' => Product::find($product['product_id'])->unit_type ?? 'unit',
                'quantity' => $product['quantity'],
                'batch_code' => $batchCode,
            ]);
        }

        return redirect()->route('purchases.index')->with('response', [
            'status' => 'success',
            'message' => 'Purchase updated successfully!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->stocks()->delete();  // Delete related stocks
        $purchase->purchaseItems()->delete(); // Delete purchase items
        $purchase->delete(); // Delete the purchase itself

        return redirect()->route('purchases.index')->with('response', [
            'status' => 'success',
            'message' => 'Purchase deleted successfully!',
        ]);
    }
}
