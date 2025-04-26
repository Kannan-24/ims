<?php

namespace App\Http\Controllers\ims;
use App\Http\Controllers\Controller;

use App\Models\ims\Stock;
use App\Models\ims\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = Product::all();

        // Fetch stocks grouped by product_id and sum quantity and sold
        $stocksQuery = Stock::selectRaw('product_id, supplier_id, unit_type, SUM(quantity) as total_quantity, SUM(sold) as total_sold')
            ->groupBy('product_id', 'supplier_id', 'unit_type')
            ->with(['product', 'supplier']);

        // Apply search filter if provided
        if ($search) {
            $stocksQuery->whereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        $stocks = $stocksQuery->get();

        return view('ims.stocks.index', compact('stocks', 'products', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($product_id)
    {
        // Fetch all stock entries for the given product ID
        $stocks = Stock::where('product_id', $product_id)->with(['product', 'supplier'])->get();

        // Return the view with stock details
        return view('ims.stocks.show', compact('stocks'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
