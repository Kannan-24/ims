<?php

namespace App\Http\Controllers\ims;

use App\Models\ims\Product;
use App\Models\ims\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class ProductController extends Controller
{
    public function index()
    {
        $query = Product::query();

        if ($search = request('search')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('hsn_code', 'like', "%{$search}%");
        }

        $products = $query->get();

        return view('ims.products.index', compact('products'));
    }

    public function create()
    {
        return view('ims.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'required',
            'hsn_code' => 'required|max:20',
            'gst_percentage' => 'required|numeric',
            'unit_type' => 'required|in:kg,ltr,pcs',
            'is_igst' => 'sometimes|boolean',
        ]);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'hsn_code' => $request->hsn_code,
            'gst_percentage' => $request->gst_percentage,
            'unit_type' => $request->unit_type,
            'is_igst' => $request->has('is_igst'), // If checkbox checked, it's IGST
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $suppliers = $product->suppliers;
        return view('ims.products.show', compact('product', 'suppliers'));
    }

    public function edit(Product $product)
    {
        return view('ims.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'required',
            'hsn_code' => 'required|max:20',
            'gst_percentage' => 'required|numeric',
            'unit_type' => 'required|in:kg,ltr,pcs',
            'is_igst' => 'sometimes|boolean',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'hsn_code' => $request->hsn_code,
            'gst_percentage' => $request->gst_percentage,
            'unit_type' => $request->unit_type,
            'is_igst' => $request->has('is_igst'),
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function assignSuppliersForm(Product $product)
    {
        $assignedSupplierIds = $product->suppliers->pluck('id')->toArray();
        $suppliers = Supplier::whereNotIn('id', $assignedSupplierIds)->get();

        return view('ims.products.assignsuppliers', compact('product', 'suppliers'));
    }

    public function assignSupplier(Request $request, Product $product)
    {
        $request->validate([
            'suppliers' => 'required|exists:suppliers,id',
        ]);

        $product->suppliers()->attach($request->suppliers);
        return redirect()->route('products.show', $product)->with('success', 'Supplier(s) assigned successfully.');
    }

    public function removeAssignedSupplier(Product $product, Supplier $supplier)
    {
        // Detach the supplier from the pivot table
        $product->suppliers()->detach($supplier->id);

        return redirect()->route('products.show', $product)->with('success', 'Supplier removed successfully.');
    }
}
