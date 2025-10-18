<?php

namespace App\Http\Controllers\ims;

use App\Models\ims\Customer;
use App\Models\ims\ContactPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class CustomerController extends Controller
{
    /** 
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Customer::with('contactPersons'); // Eager load contacts

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('cid', 'like', "%{$search}%") // Add search for CID
                    ->orWhereHas('contactPersons', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $customers = $query->get();
        return view('ims.customers.index', compact('customers'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ims.customers.create');
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
            'contact_persons.*.designation' => 'nullable|max:100',
            'contact_persons.*.phone_no' => 'required|max:20',
            'contact_persons.*.email' => 'required|email|unique:contact_persons,email|max:100',
        ]);

        DB::transaction(function () use ($request) {
            // Generate unique Customer ID (CID)
            $maxCustomerNumber = Customer::whereRaw("cid REGEXP '^SKMC[0-9]+$'")
                ->selectRaw('MAX(CAST(SUBSTRING(cid, 5) AS UNSIGNED)) as max_number')
                ->value('max_number') ?? 0;

            $newCid = 'SKMC' . str_pad($maxCustomerNumber + 1, 2, '0', STR_PAD_LEFT);

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
                    'designation' => $person['designation'] ?? null,
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
        return view('ims.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $customer->load('contactPersons');
        return view('ims.customers.edit', compact('customer'));
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
            'contact_persons' => 'required|array|min:1', // At least one contact person is required
            'contact_persons.*.name' => 'required|max:100',
            'contact_persons.*.designation' => 'nullable|max:100',
            'contact_persons.*.phone_no' => 'required|max:20',
            'contact_persons.*.email' => 'required|email|max:100',
        ]);

        // Custom validation for email uniqueness
        foreach ($request->contact_persons as $index => $contactData) {
            $emailExists = ContactPerson::where('email', $contactData['email'])
                ->where('customer_id', '!=', $customer->id)
                ->exists();

            if ($emailExists) {
                return back()->withErrors([
                    "contact_persons.{$index}.email" => 'This email is already in use by another contact person.'
                ])->withInput();
            }
        }

        DB::transaction(function () use ($request, $customer) {
            // Update customer information
            $customer->update([
                'company_name' => $request->company_name,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
                'gst_number' => $request->gst_number,
            ]);

            // Delete existing contact persons
            $customer->contactPersons()->delete();

            // Create new contact persons
            foreach ($request->contact_persons as $person) {
                ContactPerson::create([
                    'customer_id' => $customer->id,
                    'name' => $person['name'],
                    'designation' => $person['designation'] ?? null,
                    'phone_no' => $person['phone_no'],
                    'email' => $person['email'],
                ]);
            }
        });

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
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
