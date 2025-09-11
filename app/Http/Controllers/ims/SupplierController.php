<?php

namespace App\Http\Controllers\ims;

use App\Models\ims\Product;
use App\Models\ims\Supplier;
use Illuminate\Http\Request;
use Laravel\Prompts\Prompt;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all suppliers from the database with optional search filtering.
        $query = Supplier::query();

        if ($search = request('search')) {
            $query->where('supplier_id', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('contact_person', 'like', "%{$search}%");
        }

        $suppliers = $query->paginate(10);

        return view('ims.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return the view to create a new supplier.
        return view('ims.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request.
                $request->validate([
            'company_name' => 'required|max:255',
            'contact_person' => 'required|max:100',
            'email' => 'required|email|unique:suppliers|max:100',
            'phone' => 'required|max:20',
            'address' => 'required',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'postal_code' => 'nullable|max:20',
            'country' => 'required|max:100',
            'website' => 'nullable|url|max:255',
            'supplier_id' => 'nullable|max:50',
            'gst' => 'nullable|max:50',
        ]);

        // Generate supplier ID
        $lastSupplier = Supplier::latest('id')->first();
        $lastSupplierId = $lastSupplier ? $lastSupplier->supplier_id : 'SKMS00';

        preg_match('/\d+/', $lastSupplierId, $matches);
        $lastNumber = $matches ? (int) $matches[0] : 0;

        $newSupplierId = $request->supplier_id ?: ('SKMS' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT));

        $supplier = Supplier::create([
            'supplier_id' => $newSupplierId,
            'name' => $request->company_name, // Use company_name for name field
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone_number' => $request->phone, // Map phone to phone_number
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'website' => $request->website,
            'gst' => $request->gst, // Add GST field
        ]);

        // Generate supplier ID
        $lastSupplier = Supplier::latest('id')->first();
        $lastSupplierId = $lastSupplier ? $lastSupplier->supplier_id : 'SKMS00';

        preg_match('/\d+/', $lastSupplierId, $matches);
        $lastNumber = $matches ? (int) $matches[0] : 0;

        $newSupplierId = $request->supplier_id ?: ('SKMS' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT));

        $supplier = Supplier::create([
            'supplier_id' => $newSupplierId,
            'name' => $request->company_name, // Use company_name for name field
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone_number' => $request->phone, // Map phone to phone_number
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'website' => $request->website,
            'gst' => $request->gst, // Add GST field
        ]);

        // Handle new contact persons
        if ($request->has('new_contacts')) {
            foreach ($request->new_contacts as $contact) {
                if (!empty($contact['name']) && !empty($contact['phone'])) {
                    $supplier->contactPersons()->create($contact);
                }
            }
        }

        // Redirect back with a success message
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        // Load assigned products with their stocks from purchases
        $supplier->load(['products.stocks' => function($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        }]);
        
        // Get purchase transactions for this supplier
        $transactions = \App\Models\ims\Purchase::where('supplier_id', $supplier->id)
            ->with(['purchaseItems.product'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('ims.suppliers.show', compact('supplier', 'transactions'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        // Return the edit form for the selected supplier.
        return view('ims.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        // Validate the incoming request.
        $request->validate([
            'supplier_id' => 'nullable|max:50|unique:suppliers,supplier_id,' . $supplier->id,
            'company_name' => 'required|max:100',
            'contact_person' => 'required|max:100',
            'email' => 'required|email|max:100|unique:suppliers,email,' . $supplier->id,
            'phone' => 'required|max:20',
            'address' => 'required',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'postal_code' => 'nullable|max:20',
            'country' => 'required|max:100',
            'website' => 'nullable|url|max:255',
            'gst' => 'nullable|max:50',
            'existing_contacts.*.name' => 'required|max:100',
            'existing_contacts.*.phone' => 'required|max:20',
            'existing_contacts.*.email' => 'nullable|email|max:100',
            'existing_contacts.*.position' => 'nullable|max:100',
            'new_contacts.*.name' => 'required|max:100',
            'new_contacts.*.phone' => 'required|max:20',
            'new_contacts.*.email' => 'nullable|email|max:100',
            'new_contacts.*.position' => 'nullable|max:100',
        ]);

        // Update the supplier with the new data.
        $supplier->update([
            'supplier_id' => $request->supplier_id ?: $supplier->supplier_id,
            'name' => $request->company_name,
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'website' => $request->website,
            'gst' => $request->gst,
        ]);

        // Handle deleted contact persons
        if ($request->has('deleted_contacts')) {
            \App\Models\SupplierContactPerson::whereIn('id', $request->deleted_contacts)->delete();
        }

        // Handle existing contact persons updates
        if ($request->has('existing_contacts')) {
            foreach ($request->existing_contacts as $contactId => $contactData) {
                if (!empty($contactData['name']) && !empty($contactData['phone'])) {
                    \App\Models\SupplierContactPerson::where('id', $contactId)
                        ->update($contactData);
                }
            }
        }

        // Handle new contact persons
        if ($request->has('new_contacts')) {
            foreach ($request->new_contacts as $contact) {
                if (!empty($contact['name']) && !empty($contact['phone'])) {
                    $supplier->contactPersons()->create($contact);
                }
            }
        }

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

    /**
     * Display help page for suppliers.
     */
    public function help()
    {
        return view('ims.suppliers.help');
    }

    /**
     * Get next supplier ID for auto-generation.
     */
    public function getNextId()
    {
        $lastSupplier = Supplier::latest('id')->first();
        $lastSupplierId = $lastSupplier ? $lastSupplier->supplier_id : 'SKMS00';

        preg_match('/\d+/', $lastSupplierId, $matches);
        $lastNumber = $matches ? (int) $matches[0] : 0;

        $nextId = 'SKMS' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);

        return response()->json(['nextId' => $nextId]);
    }
}
