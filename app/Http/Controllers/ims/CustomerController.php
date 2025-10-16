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
        // Custom validation logic
        $existingContactsCount = $request->has('existing_contacts') ? count($request->existing_contacts) : 0;
        $newContactsCount = $request->has('contact_persons') ? count($request->contact_persons) : 0;
        $totalContacts = $existingContactsCount + $newContactsCount;

        if ($totalContacts < 1) {
            return back()->withErrors(['contact_persons' => 'At least one contact person is required.'])->withInput();
        }

        // Validate the input
        $validationRules = [
            'company_name' => 'required|max:100',
            'address' => 'required',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'zip_code' => 'required|max:20',
            'country' => 'required|max:100',
            'gst_number' => 'required|max:50|unique:customers,gst_number,' . $customer->id,
        ];

        // Add validation for existing contacts if they exist
        if ($request->has('existing_contacts')) {
            $validationRules['existing_contacts.*.name'] = 'required|max:100';
            $validationRules['existing_contacts.*.designation'] = 'nullable|max:100';
            $validationRules['existing_contacts.*.phone_no'] = 'required|max:20';
            $validationRules['existing_contacts.*.email'] = 'required|email|max:100';
        }

        // Add validation for new contacts if they exist
        if ($request->has('contact_persons')) {
            $validationRules['contact_persons.*.name'] = 'required|max:100';
            $validationRules['contact_persons.*.designation'] = 'nullable|max:100';
            $validationRules['contact_persons.*.phone_no'] = 'required|max:20';
            $validationRules['contact_persons.*.email'] = 'required|email|max:100';
        }

        $request->validate($validationRules);

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

            // Handle existing contacts updates
            if ($request->has('existing_contacts')) {
                foreach ($request->existing_contacts as $index => $contactData) {
                    if (isset($contactData['id'])) {
                        $contact = ContactPerson::find($contactData['id']);
                        if ($contact && $contact->customer_id == $customer->id) {
                            $contact->update([
                                'name' => $contactData['name'],
                                'designation' => $contactData['designation'] ?? null,
                                'phone_no' => $contactData['phone_no'],
                                'email' => $contactData['email'],
                            ]);
                        }
                    }
                }
            }

            // Handle new contacts
            if ($request->has('contact_persons')) {
                foreach ($request->contact_persons as $person) {
                    ContactPerson::create([
                        'customer_id' => $customer->id,
                        'name' => $person['name'],
                        'designation' => $person['designation'] ?? null,
                        'phone_no' => $person['phone_no'],
                        'email' => $person['email'],
                    ]);
                }
            }

            // Remove contacts that were deleted (not in the request anymore)
            $currentContactIds = collect($request->existing_contacts ?? [])->pluck('id')->filter();
            if ($currentContactIds->isNotEmpty()) {
                $customer->contactPersons()->whereNotIn('id', $currentContactIds)->delete();
            } else if (!$request->has('existing_contacts')) {
                // If no existing contacts in request, it means all were removed except new ones
                $customer->contactPersons()->delete();
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
