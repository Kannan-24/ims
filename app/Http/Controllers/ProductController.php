<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all products from the database and return them.
        $products = Product::all();
        return view('products.index', compact('products')); // Update this to your desired view.
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all(); // Assuming you have a Supplier model
        return view('products.create', compact('suppliers'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request.
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'required',
            'hsn_code' => 'required|max:20',
            'gst' => 'required|numeric',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        // Generate a unique product ID.
        $lastProduct = Product::latest()->first(); // Get the latest product
        $lastId = $lastProduct ? (int) substr($lastProduct->product_id, 3) : 0; // Extract the numeric part of the last ID and increment
        $newProductId = 'PRD' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT); // Create the new product ID

        // Create a new product
        Product::create([
            'product_id' => $newProductId, // Use the generated product ID
            'name' => $request->name,
            'description' => $request->description,
            'hsn_code' => $request->hsn_code,
            'gst' => $request->gst,
            'supplier_id' => $request->supplier_id,
        ]);

        // Redirect back with a success message
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Return the view with the specific product data.
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Retrieve all suppliers and return the edit form for the selected product.
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validate the incoming request.
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'required',
            'hsn_code' => 'required|max:20',
            'gst' => 'required|numeric',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        // Update the product with the new data.
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'hsn_code' => $request->hsn_code,
            'gst' => $request->gst,
            'supplier_id' => $request->supplier_id,
        ]);

        // Redirect back with a success message.
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete the product.
        $product->delete();

        // Redirect back with a success message.
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
