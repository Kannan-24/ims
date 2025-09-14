<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;
use App\Models\ims\Customer;
use App\Models\ims\Supplier;
use App\Models\ims\ContactPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ContactBookController extends Controller
{
    /**
     * Display the unified contact book
     */
    public function index(Request $request)
    {
        $contacts = $this->getUnifiedContacts($request->search);
        
        $stats = [
            'total' => $contacts->count(),
            'customers' => $contacts->where('type', 'Customer')->count(),
            'suppliers' => $contacts->where('type', 'Supplier')->count(),
            'contact_persons' => $contacts->where('type', 'Contact Person')->count(),
        ];

        // Filter by type if requested
        if ($request->filled('type') && $request->type !== 'all') {
            $contacts = $contacts->where('type', $request->type);
        }

        // Paginate results
        $perPage = 25;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        $paginatedContacts = $contacts->slice($offset, $perPage)->values();
        $totalPages = ceil($contacts->count() / $perPage);

        return view('ims.contact-book.index', compact('paginatedContacts', 'stats', 'totalPages', 'currentPage'));
    }

    /**
     * Get contacts for API/AJAX requests
     */
    public function getContacts(Request $request)
    {
        $contacts = $this->getUnifiedContacts($request->search);
        
        if ($request->filled('type') && $request->type !== 'all') {
            $contacts = $contacts->where('type', $request->type);
        }

        return response()->json([
            'contacts' => $contacts->take(50)->values(), // Limit to 50 for performance
            'total' => $contacts->count()
        ]);
    }

    /**
     * Export contacts to email format
     */
    public function exportToEmail(Request $request)
    {
        $contactIds = $request->input('contact_ids', []);
        if (empty($contactIds)) {
            return response()->json(['error' => 'No contacts selected'], 400);
        }

        $allContacts = $this->getUnifiedContacts();
        $selectedContacts = $allContacts->whereIn('id', $contactIds);

        $emails = $selectedContacts->pluck('email')->filter()->unique()->values();
        $names = $selectedContacts->pluck('name')->filter()->unique()->values();

        return response()->json([
            'emails' => $emails,
            'names' => $names,
            'formatted_emails' => $emails->implode(', '),
            'count' => $emails->count()
        ]);
    }

    /**
     * Get unified contacts from all sources
     */
    private function getUnifiedContacts($search = null): Collection
    {
        $contacts = new Collection();

        // Get customers (note: customers don't have direct email/phone, they have contact persons)
        $customersQuery = Customer::select('id', 'company_name as name', 'city', 'state', 'created_at');
        if ($search) {
            $customersQuery->where('company_name', 'LIKE', "%{$search}%");
        }
        
        $customers = $customersQuery->get()->map(function($customer) {
            return [
                'id' => 'customer_' . $customer->id,
                'original_id' => $customer->id,
                'name' => $customer->name,
                'email' => null, // Customers don't have direct email
                'phone' => null, // Customers don't have direct phone
                'location' => $customer->city . ($customer->state ? ', ' . $customer->state : ''),
                'type' => 'Customer',
                'type_icon' => '🏢',
                'created_at' => $customer->created_at,
                'source_table' => 'customers'
            ];
        });

        // Get suppliers
        $suppliersQuery = Supplier::select('id', 'name', 'email', 'phone', 'city', 'state', 'created_at');
        if ($search) {
            $suppliersQuery->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        $suppliers = $suppliersQuery->get()->map(function($supplier) {
            return [
                'id' => 'supplier_' . $supplier->id,
                'original_id' => $supplier->id,
                'name' => $supplier->name,
                'email' => $supplier->email,
                'phone' => $supplier->phone ?: $supplier->phone_number, // Use phone or fallback to phone_number
                'location' => $supplier->city . ($supplier->state ? ', ' . $supplier->state : ''),
                'type' => 'Supplier',
                'type_icon' => '🏭',
                'created_at' => $supplier->created_at,
                'source_table' => 'suppliers'
            ];
        });

        // Get customer contact persons
        $customerContactsQuery = ContactPerson::with('customer')
            ->select('id', 'name', 'email', 'phone_no as phone', 'customer_id', 'created_at');
        if ($search) {
            $customerContactsQuery->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        $customerContacts = $customerContactsQuery->get()->map(function($contact) {
            return [
                'id' => 'contact_person_' . $contact->id,
                'original_id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'location' => $contact->customer ? $contact->customer->city : '',
                'type' => 'Contact Person',
                'type_icon' => '👤',
                'parent' => $contact->customer ? $contact->customer->company_name : 'Unknown Customer',
                'created_at' => $contact->created_at,
                'source_table' => 'contact_persons'
            ];
        });

        // Merge all contacts (suppliers don't have separate contact persons table)
        $contacts = $customers->concat($suppliers)->concat($customerContacts);

        // Remove duplicates based on email (prioritize suppliers, then contacts since customers don't have direct emails)
        $uniqueContacts = new Collection();
        $seenEmails = [];

        // First add all customers (they don't have emails so no conflict)
        $uniqueContacts = $uniqueContacts->concat($customers);

        // Then add suppliers and contacts, avoiding email duplicates
        foreach (['Supplier', 'Contact Person'] as $priorityType) {
            $typeContacts = $contacts->where('type', $priorityType);
            foreach ($typeContacts as $contact) {
                if ($contact['email'] && !in_array(strtolower($contact['email']), $seenEmails)) {
                    $seenEmails[] = strtolower($contact['email']);
                    $uniqueContacts->push($contact);
                } elseif (!$contact['email']) {
                    // Include contacts without email
                    $uniqueContacts->push($contact);
                }
            }
        }

        return $uniqueContacts->sortBy('name')->values();
    }
}
