<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all customers from the database and return them.
        $customers = Customer::all();
        return view('customers.index', compact('customers')); // Update this to your desired view.
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return the view to create a new customer.
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request.
        $request->validate([
            'name' => 'required|max:100',
            'contact_person' => 'required|max:100',
            'email' => 'required|email|unique:customers|max:100',
            'phone' => 'required|max:20',
            'address' => 'required',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'zip' => 'required|max:20',
            'country' => 'required|max:100',
            'gstno' => 'required|max:50',
        ]);

        // Generate the CID (Customer ID)
        $lastCustomer = Customer::latest('id')->first();
        $lastCid = $lastCustomer ? $lastCustomer->cid : 'SKMC00'; // Default if no customers are found

        // Extract the numeric part and increment it
        preg_match('/\d+/', $lastCid, $matches);
        $lastNumber = $matches ? (int) $matches[0] : 0; // Extract number from the CID and cast it to an integer

        // Create the new CID
        $newCid = 'SKMC' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT); // Increment and pad the number

        // Create a new customer
        Customer::create([
            'cid' => $newCid,
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $request->country,
            'gstno' => $request->gstno,
        ]);

        // Redirect back with a success message
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        // Return the view with the specific customer data.
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        // Return the edit form for the selected customer.
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        // Validate the incoming request.
        $request->validate([
            'cid' => 'required|max:50|unique:customers,cid,' . $customer->id,
            'name' => 'required|max:100',
            'contact_person' => 'required|max:100',
            'email' => 'required|email|max:100|unique:customers,email,' . $customer->id,
            'phone' => 'required|max:20',
            'address' => 'required',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'zip' => 'required|max:20',
            'country' => 'required|max:100',
            'gstno' => 'required|max:50',
        ]);

        // Update the customer with the new data.
        $customer->update([
            'cid' => $request->cid,
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $request->country,
            'gstno' => $request->gstno,
        ]);

        // Redirect back with a success message.
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // Delete the customer.
        $customer->delete();

        // Redirect back with a success message.
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
