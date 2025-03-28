<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Laravel\Prompts\Prompt;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all suppliers from the database and return them.
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers')); // Update this to your desired view.
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return the view to create a new supplier.
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request.
        $request->validate([
            'supplier_name' => 'required|max:100',
            'contact_person' => 'required|max:100',
            'email' => 'required|email|unique:suppliers|max:100',
            'phone_number' => 'required|max:20',
            'address' => 'required',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'postal_code' => 'required|max:20',
            'country' => 'required|max:100',
            'gst' => 'required|max:50',
        ]);

        // Generate the supplier_id
        $lastSupplier = Supplier::latest('id')->first();
        $lastSupplierId = $lastSupplier ? $lastSupplier->supplier_id : 'SUP00'; // Default if no suppliers are found

        // Extract the numeric part and increment it
        preg_match('/\d+/', $lastSupplierId, $matches);
        $lastNumber = $matches ? (int) $matches[0] : 0; // Extract number from the supplier_id and cast it to an integer

        // Create the new supplier_id
        $newSupplierId = 'SUP' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT); // Increment and pad the number

        // Create a new supplier
        Supplier::create([
            'supplier_id' => $newSupplierId,
            'name' => $request->supplier_name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'gst' => $request->gst,
        ]);

        // Redirect back with a success message
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        // Load assigned products
        $supplier->load('products');

        return view('suppliers.show', compact('supplier'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        // Return the edit form for the selected supplier.
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        // Validate the incoming request.
        $request->validate([
            'supplier_id' => 'required|max:50|unique:suppliers,supplier_id,' . $supplier->id,
            'supplier_name' => 'required|max:100',
            'contact_person' => 'required|max:100',
            'email' => 'required|email|max:100|unique:suppliers,email,' . $supplier->id,
            'phone_number' => 'required|max:20',
            'address' => 'required',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'postal_code' => 'required|max:20',
            'country' => 'required|max:100',
            'gst' => 'required|max:50',
        ]);

        // Update the supplier with the new data.
        $supplier->update([
            'supplier_id' => $request->supplier_id,
            'name' => $request->supplier_name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'gst' => $request->gst,
        ]);

        // Redirect back with a success message.
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        // Delete the supplier.
        $supplier->delete();

        // Redirect back with a success message.
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    /**
     * Show the form for assigning products to a supplier.
     */
    public function supplierAssign(Supplier $supplier)
    {
        return response()->json([
            'supplier' => [
                'supplier_id' => $supplier->id,
                'name' => $supplier->supplier_name,
                'contact_person' => $supplier->contact_person,
                'email' => $supplier->email,
                'phone' => $supplier->phone_number,
            ],
            'address' => [
                'address' => $supplier->address,
                'city' => $supplier->city,
                'state' => $supplier->state,
                'postal_code' => $supplier->postal_code,
                'country' => $supplier->country,
            ],
            'gst' => $supplier->gst,
        ]);
    }
}
