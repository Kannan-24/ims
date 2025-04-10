<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ContactPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /** 
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::with('contactPersons')->get(); // Eager load contacts
        return view('customers.index', compact('customers'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'company_name' => 'required|max:100',
            'address' => 'required',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'zip_code' => 'required|max:20',
            'country' => 'required|max:100',
            'gst_number' => 'required|unique:customers,gst_number|max:50',
            'contact_persons' => 'required|array|min:1', // At least one contact person is required
            'contact_persons.*.name' => 'required|max:100',
            'contact_persons.*.phone_no' => 'required|max:20',
            'contact_persons.*.email' => 'required|email|unique:contact_persons,email|max:100',
        ]);

        DB::transaction(function () use ($request) {
            // Generate unique Customer ID (CID)
            $lastCustomer = Customer::latest('id')->first();
            $lastCid = $lastCustomer ? $lastCustomer->cid : 'SKMC00';
            preg_match('/\d+/', $lastCid, $matches);
            $lastNumber = $matches ? (int) $matches[0] : 0;
            $newCid = 'SKMC' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);

            // Create new customer
            $customer = Customer::create([
                'cid' => $newCid,
                'company_name' => $request->company_name,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
                'gst_number' => $request->gst_number,
            ]);

            // Store multiple contact persons
            foreach ($request->contact_persons as $person) {
                ContactPerson::create([
                    'customer_id' => $customer->id,
                    'name' => $person['name'],
                    'phone_no' => $person['phone_no'],
                    'email' => $person['email'],
                ]);
            }
        });

        return redirect()->route('customers.index')->with('success', 'Customer and contacts created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer->load('contactPersons');
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $customer->load('contactPersons');
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        // Validate the input
        $request->validate([
            'company_name' => 'required|max:100',
            'address' => 'required',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'zip_code' => 'required|max:20',
            'country' => 'required|max:100',
            'gst_number' => 'required|max:50|unique:customers,gst_number,' . $customer->id,
            'contact_persons' => 'required|array|min:1',
            'contact_persons.*.name' => 'required|max:100',
            'contact_persons.*.phone_no' => 'required|max:20',
            'contact_persons.*.email' => function ($attribute, $value, $fail) use ($customer) {
                $contactPersonId = explode('.', $attribute)[1]; // Extract index from attribute
                $existingEmail = $customer->contactPersons[$contactPersonId]->email ?? null;
                if ($value !== $existingEmail && ContactPerson::where('email', $value)->exists()) {
                    $fail('The ' . $attribute . ' has already been taken.');
                }
            },
        ]);

        DB::transaction(function () use ($request, $customer) {
            // Update customer details
            $customer->update([
                'company_name' => $request->company_name,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
                'gst_number' => $request->gst_number,
            ]);

            // Delete existing contact persons and insert new ones
            $customer->contactPersons()->delete();
            foreach ($request->contact_persons as $person) {
                ContactPerson::create([
                    'customer_id' => $customer->id,
                    'name' => $person['name'],
                    'phone_no' => $person['phone_no'],
                    'email' => $person['email'],
                ]);
            }
        });

        return redirect()->route('customers.show', $customer)->with('success', 'Customer and contacts updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
