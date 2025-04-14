<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Purchase::query();

        if ($search = request('search')) {
            $query->where('invoice_no', 'like', "%{$search}%")
              ->orWhereHas('supplier', function ($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
        }

        if ($from = request('from')) {
            $query->whereDate('invoice_date', '>=', $from);
        }

        if ($to = request('to')) {
            $query->whereDate('invoice_date', '<=', $to);
        }

        $purchases = $query->get();

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
            'purchase_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Save or update the purchase
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

        // Batch code logic
        $latestBatch = Stock::latest()->first();
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

        // Create purchase items and stock entries
        foreach ($request->products as $product) {
            $unitType = Product::find($product['product_id'])->unit_type ?? 'unit';

            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'unit_type' => $unitType,
                'unit_price' => $product['unit_price'],
                'gst' => $product['gst_percentage'],
                'cgst' => $product['cgst_value'],
                'sgst' => $product['sgst_value'],
                'igst' => $product['igst_value'],
                'total' => $product['total'],
            ]);

            Stock::create([
                'product_id' => $product['product_id'],
                'purchase_id' => $purchase->id,
                'supplier_id' => $request->supplier_id,
                'unit_type' => $unitType,
                'quantity' => $product['quantity'],
                'batch_code' => $batchCode,
            ]);
        }

        if ($request->hasFile('purchase_file')) {
            $file = $request->file('purchase_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = $request->invoice_no . '.' . $extension;
            $folder = 'purchase_files';

            // ✅ Ensure the folder exists
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder, 0755, true);
            }

            // ✅ Store the file with the invoice number as the name
            $file->storeAs($folder, $fileName, 'public');
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

        // File name is expected to be invoice_no.pdf/jpg/png stored in 'public/purchase_files'
        $fileName = $purchase->invoice_no . '.pdf';
        $imageExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        $filePath = null;
        if (Storage::disk('public')->exists("purchase_files/$fileName")) {
            $filePath = asset("storage/purchase_files/$fileName");
        } else {
            // Check if it's an image file instead of PDF
            foreach ($imageExtensions as $ext) {
                $imageFile = $purchase->invoice_no . '.' . $ext;
                if (Storage::disk('public')->exists("purchase_files/$imageFile")) {
                    $filePath = asset("storage/purchase_files/$imageFile");
                    break;
                }
            }
        }

        return view('purchases.show', compact('purchase', 'filePath'));
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
            'purchase_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
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

        // 🔁 Delete old purchase items and stocks
        $purchase->purchaseItems()->delete();
        $purchase->stocks()->delete();

        // 🔁 Generate new batch code
        $latestBatch = \App\Models\Stock::latest()->first();
        $nextLetter = 'A';
        $nextNumber = 1;

        if ($latestBatch && preg_match('/Batch_([A-Z])(\d{3})/', $latestBatch->batch_code, $matches)) {
            $currentLetter = $matches[1];
            $currentNumber = (int) $matches[2];

            if ($currentNumber >= 999) {
                $nextLetter = chr(ord($currentLetter) + 1);
                $nextNumber = 1;
            } else {
                $nextLetter = $currentLetter;
                $nextNumber = $currentNumber + 1;
            }
        }

        $batchCode = "Batch_{$nextLetter}" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // 🔁 Loop through products and add entries
        foreach ($request->products as $product) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'unit_type' => Product::find($product['product_id'])->unit_type ?? 'unit',
                'unit_price' => $product['unit_price'],
                'cgst' => ($product['unit_price'] * $product['cgst']) / 100,
                'sgst' => ($product['unit_price'] * $product['sgst']) / 100,
                'igst' => ($product['unit_price'] * $product['igst']) / 100,
                'gst'  => $product['cgst'] + $product['sgst'] + $product['igst'],
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

        // 📦 Handle file upload if exists
        if ($request->hasFile('purchase_file')) {
            $file = $request->file('purchase_file');
            $invoiceFolder = 'purchases/' . $request->invoice_no;

            // Ensure folder exists
            if (!Storage::exists($invoiceFolder)) {
                Storage::makeDirectory($invoiceFolder);
            }

            $filename = 'invoice_' . Str::slug($request->invoice_no) . '.' . $file->getClientOriginalExtension();
            $file->storeAs($invoiceFolder, $filename);
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
