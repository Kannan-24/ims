<?php

namespace App\Http\Controllers\ims;
use App\Http\Controllers\Controller;

use App\Models\ims\Stock;
use App\Models\ims\Product;
use App\Models\ims\Supplier;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Fetch individual stock entries instead of grouped data
        $stocksQuery = Stock::with(['product', 'supplier', 'purchase'])
            ->orderBy('created_at', 'desc');

        // Apply search filter if provided
        if ($search) {
            $stocksQuery->whereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('supplier', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhere('batch_code', 'like', '%' . $search . '%');
        }

        $stocks = $stocksQuery->paginate(15);

        return view('ims.stocks.index', compact('stocks', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        
        return view('ims.stocks.create', compact('products', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->manualStore($request);
    }

    /**
     * Show the help page for stock management.
     */
    public function help()
    {
        return view('ims.stocks.help');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        // Load relationships
        $stock->load(['product', 'supplier', 'purchase']);

        // Get related stock entries for the same product
        $relatedStocks = Stock::where('product_id', $stock->product_id)
            ->where('id', '!=', $stock->id)
            ->with(['supplier', 'purchase'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Calculate totals for this product
        $productTotals = Stock::where('product_id', $stock->product_id)
            ->selectRaw('SUM(quantity) as total_quantity, SUM(sold) as total_sold')
            ->first();

        return view('ims.stocks.show', compact('stock', 'relatedStocks', 'productTotals'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        
        return view('ims.stocks.edit', compact('stock', 'products', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'unit_type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            $stock->update([
                'product_id' => $request->product_id,
                'supplier_id' => $request->supplier_id,
                'unit_type' => $request->unit_type,
                'quantity' => $request->quantity,
                'price' => $request->price,
            ]);

            return redirect()->route('stocks.show', $stock)
                ->with('success', 'Stock entry updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update stock entry: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        try {
            // Only allow deletion of manual entries or if no sales have been made
            if ($stock->entry_type === 'purchase' && $stock->sold > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete stock entry with sales records.');
            }

            $stock->delete();

            return redirect()->route('stocks.index')
                ->with('success', 'Stock entry deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete stock entry: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a manual stock entry.
     */
    public function manualCreate()
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        
        return view('ims.stocks.create', compact('products', 'suppliers'));
    }

    /**
     * Store a manually created stock entry.
     */
    public function manualStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'unit_type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'batch_code' => 'nullable|string|max:255|unique:stocks,batch_code',
        ]);

        try {
            // Generate batch code automatically if not provided
            $batchCode = $request->batch_code;
            if (empty($batchCode)) {
                $latestBatch = Stock::latest()->first();
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
            }

            $stock = Stock::create([
                'product_id' => $request->product_id,
                'supplier_id' => $request->supplier_id,
                'unit_type' => $request->unit_type,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'batch_code' => $batchCode,
                'sold' => 0,
                'purchase_id' => null, // Manual entries don't have purchase_id
                'entry_type' => 'manual', // Mark as manual entry
            ]);

            return redirect()->route('stocks.show', $stock)
                ->with('success', 'Manual stock entry created successfully with batch code: ' . $batchCode);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create stock entry: ' . $e->getMessage())
                ->withInput();
        }
    }
}
