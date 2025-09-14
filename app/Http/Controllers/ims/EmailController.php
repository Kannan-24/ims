<?php

namespace App\Http\Controllers\ims;

use App\Mail\SendEmail;
use App\Models\ims\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Services\GeminiService;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EmailController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }
    public function index()
    {
        $emails = Email::latest()->paginate(20);
        $drafts = Email::drafts()->count();
        return view('ims.emails.index', compact('emails', 'drafts'));
    }

    public function drafts()
    {
        $emails = Email::drafts()->latest()->get();
        return view('ims.emails.drafts', compact('emails'));
    }

    public function help()
    {
        ActivityLogger::log('Email Help Accessed', 'help', null, 'Accessed email management help documentation');
        return view('ims.emails.help');
    }

    public function show($id)
    {
        $email = Email::findOrFail($id);
        return view('ims.emails.show', compact('email'));
    }

    public function edit($id)
    {
        $email = Email::findOrFail($id);
        return view('ims.emails.edit', compact('email'));
    }

    public function create(Request $request)
    {
        $emailData = [];
        
        // Check if invoice_id is provided for pre-filling email
        if ($request->has('invoice_id')) {
            $invoice = \App\Models\ims\Invoice::with(['customer', 'customer.contactPersons', 'contactPerson'])->findOrFail($request->invoice_id);
            $contactPerson = $invoice->contactPerson ?: $invoice->customer->contactPersons->first();
            
            $emailData = [
                'to' => $contactPerson ? $contactPerson->email : '',
                'subject' => "Invoice #{$invoice->invoice_no} - SKM&COMPANY",
                'body' => $this->generateInvoiceEmailBody($invoice, $contactPerson),
                'invoice' => $invoice,
                'customer' => $invoice->customer,
                'contactPerson' => $contactPerson
            ];
        }
        
        return view('ims.emails.create', compact('emailData'));
    }
    
    private function generateInvoiceEmailBody($invoice, $contactPerson)
    {
        $customerName = $contactPerson ? $contactPerson->name : $invoice->customer->company_name;
        $companyName = $invoice->customer->company_name;
        
        return "Dear {$customerName},

I hope this email finds you well.

Please find attached the invoice #{$invoice->invoice_no} for your recent order with {$companyName}.

Invoice Details:
• Invoice Number: {$invoice->invoice_no}
• Invoice Date: {$invoice->invoice_date}
• Order Number: {$invoice->order_no}
• Total Amount: ₹" . number_format($invoice->total, 2) . "

Please review the attached invoice and let us know if you have any questions or concerns.

Thank you for your business!

Best regards,
SKM&COMPANY Team";
    }

    public function createDraft(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:invoice,quotation',
            'document_id' => 'required|integer',
        ]);

        $documentType = $request->document_type;
        $documentId = $request->document_id;

        // Get document details with proper relationship loading
        if ($documentType === 'invoice') {
            $document = \App\Models\ims\Invoice::with(['customer', 'customer.contactPersons', 'contactPerson', 'items'])->findOrFail($documentId);
            $documentNumber = $document->invoice_no;
            $documentDate = $document->invoice_date;
            $totalAmount = $document->total;
        } else {
            $document = \App\Models\ims\Quotation::with(['customer', 'customer.contactPersons', 'contactPerson', 'items'])->findOrFail($documentId);
            $documentNumber = $document->quotation_code;
            $documentDate = $document->quotation_date;
            $totalAmount = $document->total;
        }

        $customer = $document->customer;
        // Use the specific contact person associated with the document, not just the first one
        $contactPerson = $document->contactPerson ?: $customer->contactPersons->first();

        // Company Information
        $companyInfo = [
            'name' => 'SKM&COMPANY',
            'email' => 'info@skmcompany.com',
            'phone' => '+91 9876543210',
            'address' => '123 Business Street, City, State - 123456'
        ];

        // Generate PDF and save it temporarily
        try {
            $pdfContent = $this->generatePDFContent($documentType, $document);
            $fileName = ucfirst($documentType) . '_' . $documentNumber . '.pdf';
            $filePath = 'email_attachments/' . $fileName;

            // Ensure the directory exists
            if (!Storage::disk('public')->exists('email_attachments')) {
                Storage::disk('public')->makeDirectory('email_attachments');
            }

            // Store the PDF file
            $saved = Storage::disk('public')->put($filePath, $pdfContent);
            
            // Verify file was saved properly
            if (!$saved || !Storage::disk('public')->exists($filePath)) {
                throw new \Exception("Failed to save PDF file");
            }
        } catch (\Exception $e) {
            Log::error("PDF generation failed: " . $e->getMessage());
            $filePath = null;
        }

        // Generate AI-powered email body
        $documentData = [
            'document_type' => $documentType,
            'document_number' => $documentNumber,
            'customer_name' => $customer->company_name,
            'company_name' => $customer->company_name,
            'total_amount' => number_format($totalAmount, 2),
            'document_date' => $documentDate,
            'contact_person' => $contactPerson ? $contactPerson->name : 'Sir/Madam'
        ];

        $aiEmailBody = $this->geminiService->generateEmailContent($documentData, 'business');

        // Fallback to default if AI fails
        $emailBody = $aiEmailBody ?: $this->generateEmailBody(
            $contactPerson ? $contactPerson->name : 'Dear Sir/Madam',
            $documentType,
            $documentNumber,
            $documentDate,
            $customer->company_name,
            number_format($totalAmount, 2),
            $companyInfo
        );

        // Save as draft
        $email = Email::create([
            'to' => $contactPerson ? $contactPerson->email : '',
            'cc' => '',
            'bcc' => '',
            'subject' => ucfirst($documentType) . ' ' . $documentNumber . ' - ' . $customer->company_name,
            'body' => $emailBody,
            'attachments' => json_encode([$filePath]),
            'status' => 'draft'
        ]);

        return redirect()->route('emails.edit', $email->id)->with('success', 'Email draft created successfully with AI-generated content! You can now edit and send it.');
    }

    public function generateAIContent(Request $request)
    {
        try {
            $request->validate([
                'document_type' => 'required|in:invoice,quotation',
                'document_id' => 'required|integer',
                'email_type' => 'required|string',
                'custom_prompt' => 'nullable|string'
            ]);

            // Get current user details
            $user = Auth::user();
            $userDetails = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'designation' => $user->designation,
            ];

            // Get company details from environment
            $companyDetails = [
                'name' => config('app.name'),
                'email' => config('mail.from.address'),
            ];

            $content = $this->geminiService->generateEmailContentWithDetails(
                $request->document_type,
                $request->document_id,
                $request->email_type,
                $request->custom_prompt,
                $userDetails,
                $companyDetails
            );

            // Get document details for email field auto-population
            $documentData = $this->getDocumentDetailsForEmail($request->document_type, $request->document_id);

            return response()->json([
                'success' => true,
                'content' => $content,
                'user_details' => $userDetails,
                'company_details' => $companyDetails,
                'document_data' => $documentData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate content: ' . $e->getMessage()
            ], 500);
        }
    }

    public function regenerateEmailContent(Request $request)
    {
        try {
            // Check if it's the simple regenerate (improve existing content)
            if ($request->has('prompt') && $request->has('context')) {
                $request->validate([
                    'prompt' => 'required|string|max:2000',
                    'context' => 'sometimes|string'
                ]);

                $customPrompt = $request->prompt;
                $context = $request->context ?? '';

                if (!empty($context)) {
                    $customPrompt = "Context: {$context}\n\nRequest: {$customPrompt}";
                }

                $aiContent = $this->geminiService->generateContent($customPrompt);

                return response()->json([
                    'success' => true,
                    'content' => $aiContent
                ]);
            }

            // Handle the enhanced regenerate with document details
            $request->validate([
                'document_type' => 'required|in:invoice,quotation',
                'document_id' => 'required|integer',
                'email_type' => 'required|string',
                'custom_prompt' => 'nullable|string'
            ]);

            // Get current user details
            $user = Auth::user();
            $userDetails = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'designation' => $user->designation,
            ];

            // Get company details from environment
            $companyDetails = [
                'name' => config('app.name'),
                'email' => config('mail.from.address'),
            ];

            $content = $this->geminiService->generateEmailContentWithDetails(
                $request->document_type,
                $request->document_id,
                $request->email_type,
                $request->custom_prompt . ' Please provide a different variation of the email content.',
                $userDetails,
                $companyDetails
            );

            // Get document details for email field auto-population
            $documentData = $this->getDocumentDetailsForEmail($request->document_type, $request->document_id);

            return response()->json([
                'success' => true,
                'content' => $content,
                'user_details' => $userDetails,
                'company_details' => $companyDetails,
                'document_data' => $documentData
            ]);
        } catch (\Exception $e) {
            Log::error('AI Email Regeneration Error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to regenerate content: ' . $e->getMessage()
            ], 500);
        }
    }

    public function improveEmailContent(Request $request)
    {
        try {
            $request->validate([
                'content' => 'required|string|max:5000',
                'context' => 'nullable|string'
            ]);

            $currentContent = $request->content;
            $context = $request->context ?? 'email improvement';

            $prompt = "Please improve and enhance the following email content. Make it more professional, clear, and engaging while maintaining the original message and tone. Fix any grammar or formatting issues and ensure proper business email etiquette:\n\n" . $currentContent;

            if ($context) {
                $prompt = "Context: {$context}\n\n" . $prompt;
            }

            $improvedContent = $this->geminiService->generateContent($prompt);

            ActivityLogger::log(
                'AI Email Content Improved',
                'Email Module',
                'Improved existing email content using AI'
            );

            return response()->json([
                'success' => true,
                'content' => $improvedContent
            ]);
        } catch (\Exception $e) {
            ActivityLogger::log(
                'AI Email Content Improvement Failed',
                'Email Module',
                'Failed to improve email content: ' . $e->getMessage()
            );

            return response()->json([
                'success' => false,
                'error' => 'Failed to improve content: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getDocumentDetailsForEmail($documentType, $documentId)
    {
        try {
            if ($documentType === 'invoice') {
                $invoice = \App\Models\ims\Invoice::with(['customer.contactPersons', 'contactPerson'])->find($documentId);
                if (!$invoice) return null;

                $customer = $invoice->customer;
                $contactPerson = $invoice->contactPerson;

                // Get all contact emails for CC field
                $ccEmails = $customer && $customer->contactPersons ? 
                    $customer->contactPersons->pluck('email')->filter()->unique()->implode(', ') : '';

                return [
                    'customer_company' => $customer->company_name ?? '',
                    'company_email' => $customer->company_name ? $this->generateCompanyEmail($customer->company_name) : '',
                    'contact_person_name' => $contactPerson->name ?? '',
                    'contact_person_email' => $contactPerson->email ?? '',
                    'cc_emails' => $ccEmails,
                ];
            } elseif ($documentType === 'quotation') {
                $quotation = \App\Models\ims\Quotation::with(['customer.contactPersons', 'contactPerson'])->find($documentId);
                if (!$quotation) return null;

                $customer = $quotation->customer;
                $contactPerson = $quotation->contactPerson;

                // Get all contact emails for CC field
                $ccEmails = $customer && $customer->contactPersons ? 
                    $customer->contactPersons->pluck('email')->filter()->unique()->implode(', ') : '';

                return [
                    'customer_company' => $customer->company_name ?? '',
                    'company_email' => $customer->company_name ? $this->generateCompanyEmail($customer->company_name) : '',
                    'contact_person_name' => $contactPerson->name ?? '',
                    'contact_person_email' => $contactPerson->email ?? '',
                    'cc_emails' => $ccEmails,
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error fetching document details for email: ' . $e->getMessage());
            return null;
        }
    }

    private function generateCompanyEmail($companyName)
    {
        // Generate a potential company email based on company name
        $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $companyName));
        return $cleanName . '@gmail.com';
    }

    public function getAvailableDocuments(Request $request)
    {
        try {
            $documentType = $request->get('type', 'all');
            $search = $request->get('search', '');
            $limit = $request->get('limit', 20);

            $result = [];

            if ($documentType === 'invoice' || $documentType === 'all') {
                $invoicesQuery = \App\Models\ims\Invoice::with(['customer'])
                    ->select('id', 'invoice_no', 'invoice_date', 'total', 'customer_id')
                    ->orderBy('created_at', 'desc');

                if ($search) {
                    $invoicesQuery->where(function ($q) use ($search) {
                        $q->where('invoice_no', 'like', "%{$search}%")
                            ->orWhereHas('customer', function ($q2) use ($search) {
                                $q2->where('company_name', 'like', "%{$search}%");
                            });
                    });
                }

                $invoices = $invoicesQuery->limit($limit)->get()->map(function ($invoice) {
                    $customerName = $invoice->customer ? $invoice->customer->company_name : 'Unknown';

                    return [
                        'id' => $invoice->id,
                        'type' => 'invoice',
                        'number' => $invoice->invoice_no,
                        'date' => $invoice->invoice_date,
                        'customer' => $customerName,
                        'amount' => $invoice->total,
                        'display_text' => "Invoice {$invoice->invoice_no} - {$customerName} (₹" . number_format($invoice->total, 2) . ")"
                    ];
                });

                $result['invoices'] = $invoices;
            }

            if ($documentType === 'quotation' || $documentType === 'all') {
                $quotationsQuery = \App\Models\ims\Quotation::with(['customer'])
                    ->select('id', 'quotation_code', 'quotation_date', 'total', 'customer_id')
                    ->orderBy('created_at', 'desc');

                if ($search) {
                    $quotationsQuery->where(function ($q) use ($search) {
                        $q->where('quotation_code', 'like', "%{$search}%")
                            ->orWhereHas('customer', function ($q2) use ($search) {
                                $q2->where('company_name', 'like', "%{$search}%");
                            });
                    });
                }

                $quotations = $quotationsQuery->limit($limit)->get()->map(function ($quotation) {
                    $customerName = $quotation->customer ? $quotation->customer->company_name : 'Unknown';

                    return [
                        'id' => $quotation->id,
                        'type' => 'quotation',
                        'number' => $quotation->quotation_code,
                        'date' => $quotation->quotation_date,
                        'customer' => $customerName,
                        'amount' => $quotation->total,
                        'display_text' => "Quotation {$quotation->quotation_code} - {$customerName} (₹" . number_format($quotation->total, 2) . ")"
                    ];
                });

                $result['quotations'] = $quotations;
            }

            if ($documentType === 'all') {
                $allDocuments = collect($result['invoices'] ?? [])
                    ->merge($result['quotations'] ?? [])
                    ->sortByDesc('date')
                    ->take($limit)
                    ->values();

                return response()->json([
                    'success' => true,
                    'documents' => $allDocuments
                ]);
            } else {
                // For specific document type, return the documents directly
                $documents = $result['invoices'] ?? $result['quotations'] ?? [];

                return response()->json([
                    'success' => true,
                    'documents' => $documents
                ]);
            }

            ActivityLogger::log(
                'Documents Retrieved',
                'Email Module',
                'Fetched documents type=' . $documentType . ' search=' . $search
            );
        } catch (\Exception $e) {
            Log::error('Error fetching documents', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch documents: ' . $e->getMessage()
            ], 500);
        }
    }

    public function fillEmailFromDocument(Request $request)
    {
        try {
            $request->validate([
                'document_type' => 'required|in:invoice,quotation',
                'document_id' => 'required|integer'
            ]);

            $documentType = $request->document_type;
            $documentId = $request->document_id;

            if ($documentType === 'invoice') {
                $document = \App\Models\ims\Invoice::with(['customer', 'customer.contactPersons', 'contactPerson'])
                    ->findOrFail($documentId);
                $documentNumber = $document->invoice_no;
                $documentDate = $document->invoice_date;
                $totalAmount = $document->total;
            } else {
                $document = \App\Models\ims\Quotation::with(['customer', 'customer.contactPersons', 'contactPerson'])
                    ->findOrFail($documentId);
                $documentNumber = $document->quotation_code;
                $documentDate = $document->quotation_date;
                $totalAmount = $document->total;
            }

            $customer = $document->customer;
            $contactPerson = $document->contactPerson; // This is the specific contact person for this document
            $specificContactPerson = $contactPerson; // Alias for clarity
            
            Log::info('Document contact person details:', [
                'document_type' => $documentType,
                'document_id' => $documentId,
                'contact_person_id' => $document->contactperson_id ?? 'NULL',
                'contact_person_name' => $contactPerson ? $contactPerson->name : 'NULL',
                'contact_person_email' => $contactPerson ? $contactPerson->email : 'NULL',
                'customer_name' => $customer ? $customer->company_name : 'NULL'
            ]);

            // Generate PDF attachment FIRST before setting email data
            try {
                $pdfContent = $this->generatePDFContent($documentType, $document);
                $fileName = ucfirst($documentType) . '_' . str_replace('/', '_', $documentNumber) . '.pdf';
                $filePath = 'email_attachments/' . $fileName;

                if (!Storage::disk('public')->exists('email_attachments')) {
                    Storage::disk('public')->makeDirectory('email_attachments');
                }

                // Save the PDF file
                $saved = Storage::disk('public')->put($filePath, $pdfContent);
                
                if (!$saved || !Storage::disk('public')->exists($filePath)) {
                    throw new \Exception('Failed to save PDF attachment');
                }
            } catch (\Exception $e) {
                Log::error('PDF generation/storage failed: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to generate PDF attachment: ' . $e->getMessage()
                ], 500);
            }

            // Prepare email data - Use specific contact person email as primary
            $primaryEmail = '';
            if ($specificContactPerson && $specificContactPerson->email) {
                $primaryEmail = $specificContactPerson->email;
            } elseif ($customer && $customer->email) {
                $primaryEmail = $customer->email;
            } else {
                $primaryEmail = $customer && $customer->company_name ? $this->generateCompanyEmail($customer->company_name) : '';
            }

            // BUILD CC LIST - Get REAL contact persons from database
            $ccEmails = [];
            // Get ALL contact persons for this customer from database
            if ($customer) {
                $allContactPersons = $customer->contactPersons;
                
                // Add ALL contact person emails to CC (except the one used as primary)
                if ($allContactPersons && $allContactPersons->count() > 0) {
                    foreach ($allContactPersons as $cp) {
                        if ($cp->email && $cp->email !== $primaryEmail) {
                            $ccEmails[] = $cp->email;
                        }
                    }
                }
                
                // If no other contact persons, generate one company email for CC
                if (empty($ccEmails) && $customer->company_name) {
                    $companyName = $customer->company_name;
                    $cleanCompanyName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $companyName));
                    $companyEmail = 'info@' . $cleanCompanyName . '.com';
                    if ($companyEmail !== $primaryEmail) {
                        $ccEmails[] = $companyEmail;
                    }
                }
            }
            
            // Remove duplicates and empty values
            $ccEmails = array_values(array_unique(array_filter($ccEmails)));

            $documentData = [
                'document_type' => $documentType,
                'document_number' => $documentNumber,
                'customer_name' => $customer ? $customer->company_name : '',
                'total_amount' => number_format($totalAmount, 2),
                'document_date' => $documentDate,
                'contact_person' => $contactPerson ? $contactPerson->name : 'Sir/Madam'
            ];

            // Generate AI content with error handling
            try {
                // Generate AI content
                $user = Auth::user();
                $userDetails = [
                    'name' => $user ? $user->name : 'System User',
                    'email' => $user ? $user->email : 'system@example.com',
                    'phone' => $user ? ($user->phone ?? '') : '',
                    'role' => $user ? ($user->role ?? '') : '',
                    'designation' => $user ? ($user->designation ?? '') : '',
                ];

                $companyDetails = [
                    'name' => config('app.name', 'SKM and Company'),
                    'email' => config('mail.from.address', 'skmandcompany@yahoo.in'),
                ];

                $aiEmailBody = $this->geminiService->generateEmailContentWithDetails(
                    $documentType,
                    $documentId,
                    'standard',
                    '',
                    $userDetails,
                    $companyDetails
                );
            } catch (\Exception $e) {
                Log::error('AI content generation failed: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                
                // Fallback to basic email body
                $aiEmailBody = "Dear " . ($contactPerson ? $contactPerson->name : 'Sir/Madam') . ",\n\n";
                $aiEmailBody .= "Please find attached the " . $documentType . " details for your reference.\n\n";
                $aiEmailBody .= ucfirst($documentType) . " Number: " . $documentNumber . "\n";
                $aiEmailBody .= "Date: " . $documentDate . "\n";
                $aiEmailBody .= "Amount: ₹" . number_format($totalAmount, 2) . "\n\n";
                $aiEmailBody .= "Thank you for your business.\n\nBest regards,\nSKM and Company";
            }

            $result = [
                'success' => true,
                'email_data' => [
                    'to' => $primaryEmail,
                    'cc' => implode(', ', array_unique(array_filter($ccEmails))),
                    'subject' => ucfirst($documentType) . ' ' . $documentNumber . ' - ' . ($customer ? $customer->company_name : ''),
                    'body' => $aiEmailBody,
                    'attachment_path' => $filePath,
                    'attachment_name' => $fileName,
                    'customer_name' => $customer ? $customer->company_name : '',
                    'contact_person' => $contactPerson ? $contactPerson->name : '',
                    'document_info' => $documentData
                ]
            ];

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to prepare email: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generatePDFContent($documentType, $document)
    {
        try {
            if ($documentType === 'invoice') {
                // Generate QR code for invoice verification
                $qrUrl = url('/ims/invoices/' . $document->id . '/qr-view');
                
                // Generate QR code with error handling
                try {
                    $qrCodeSvg = QrCode::format('svg')
                        ->size(80)
                        ->errorCorrection('M')
                        ->generate($qrUrl);
                    
                    $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
                } catch (\Exception $e) {
                    // Fallback: create a simple placeholder for QR code
                    Log::warning('QR Code generation failed: ' . $e->getMessage());
                    $qrCode = 'data:image/svg+xml;base64,' . base64_encode('<svg width="80" height="80" xmlns="http://www.w3.org/2000/svg"><rect width="80" height="80" fill="#f0f0f0" stroke="#ccc"/><text x="40" y="40" text-anchor="middle" font-size="8" fill="#666">QR Code</text><text x="40" y="50" text-anchor="middle" font-size="6" fill="#999">Not Available</text></svg>');
                }
                
                // Load invoice with required relationships
                $invoiceWithData = \App\Models\ims\Invoice::with(['customer', 'contactPerson', 'items', 'items.product', 'items.service'])->find($document->id);
                
                $pdf = Pdf::loadView('ims.invoices.pdf', ['invoice' => $invoiceWithData, 'qrCode' => $qrCode])
                    ->setPaper('a4', 'portrait');
            } else {
                // Load quotation with required relationships
                $quotationWithData = \App\Models\ims\Quotation::with(['customer', 'contactPerson', 'items', 'items.product', 'items.service'])->find($document->id);
                
                $pdf = Pdf::loadView('ims.quotations.pdf', ['quotation' => $quotationWithData])
                    ->setPaper('a4', 'portrait');
            }

            $pdfOutput = $pdf->output();
            
            return $pdfOutput;
        } catch (\Exception $e) {
            Log::error("PDF generation failed for {$documentType} ID: {$document->id} - Error: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    private function generateEmailBody($contactName, $documentType, $documentNumber, $documentDate, $companyName, $totalAmount, $companyInfo)
    {
        return "Dear {$contactName},

We hope this email finds you well.

Please find attached the {$documentType} details for your reference:

" . ucfirst($documentType) . " Details:
• " . ucfirst($documentType) . " Number: {$documentNumber}
• Date: {$documentDate}
• Company: {$companyName}
• Total Amount: ₹{$totalAmount}

If you have any questions or require any clarification regarding this {$documentType}, please feel free to contact us. We value your business and look forward to continuing our partnership.

Thank you for choosing {$companyInfo['name']}.

Best regards,

{$companyInfo['name']}
Email: {$companyInfo['email']}
Phone: {$companyInfo['phone']}
Address: {$companyInfo['address']}

---
This is a system-generated email. Please verify all details before processing.";
    }

    public function store(Request $request)
    {
        // Different validation rules based on whether we're saving as draft or sending
        $isDraft = $request->has('save_draft') && $request->input('save_draft') == '1';

        $validationRules = [
            'to' => 'required|string',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'subject' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,xls|max:2048',
        ];

        // Only require body if sending email (not saving as draft)
        if ($isDraft) {
            $validationRules['body'] = 'nullable|string';
        } else {
            $validationRules['body'] = 'required|string';
        }

        $request->validate($validationRules);

        $to = array_filter(array_map('trim', explode(',', $request->to)));
        $cc = $request->cc ? array_filter(array_map('trim', explode(',', $request->cc))) : [];
        $bcc = $request->bcc ? array_filter(array_map('trim', explode(',', $request->bcc))) : [];

        $allEmails = array_merge($to, $cc, $bcc);
        foreach ($allEmails as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', "Invalid email address: $email")->withInput();
            }
        }

        $attachments = [];

        // Handle auto-attachment (PDF) - with regeneration fallback
        if ($request->has('auto_attachment')) {
            $autoAttachmentPath = $request->auto_attachment;

            if (!Storage::disk('public')->exists($autoAttachmentPath) && $request->filled('auto_document_type') && $request->filled('auto_document_id')) {
                // Regenerate PDF on the fly
                try {
                    $docType = $request->input('auto_document_type');
                    $docId = (int)$request->input('auto_document_id');
                    if ($docType === 'invoice') {
                        $doc = \App\Models\ims\Invoice::with(['customer','contactPerson','items'])->find($docId);
                        $docNumber = $doc ? str_replace('/', '_', $doc->invoice_no) : 'UNKNOWN';
                    } else {
                        $doc = \App\Models\ims\Quotation::with(['customer','contactPerson','items'])->find($docId);
                        $docNumber = $doc ? str_replace('/', '_', $doc->quotation_code) : 'UNKNOWN';
                    }
                    if ($doc) {
                        $pdfContent = $this->generatePDFContent($docType, $doc);
                        // Ensure directory
                        if (!Storage::disk('public')->exists(dirname($autoAttachmentPath))) {
                            Storage::disk('public')->makeDirectory(dirname($autoAttachmentPath));
                        }
                        Storage::disk('public')->put($autoAttachmentPath, $pdfContent);
                        Log::info('Regenerated PDF saved', [
                            'path' => $autoAttachmentPath,
                            'size' => Storage::disk('public')->size($autoAttachmentPath)
                        ]);
                    } else {
                        Log::warning('Document not found for regeneration', ['type' => $docType, 'id' => $docId]);
                    }
                } catch (\Exception $e) {
                    Log::error('PDF regeneration failed: ' . $e->getMessage());
                }
            }

            if (Storage::disk('public')->exists($autoAttachmentPath)) {
                $attachments[] = $autoAttachmentPath;
                Log::info('Auto-attachment added', ['path' => $autoAttachmentPath]);
            } else {
                Log::warning('Auto-attachment still missing after regeneration attempt', ['path' => $autoAttachmentPath]);
            }
        } else {
            Log::info('No auto_attachment field found in request. Available fields: ' . implode(', ', array_keys($request->all())));
        }

        // Handle manual attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = 'attachments/' . $file->getClientOriginalName();

                // Ensure the directory exists
                if (!Storage::disk('public')->exists('attachments')) {
                    Storage::disk('public')->makeDirectory('attachments');
                }

                Storage::disk('public')->put($path, file_get_contents($file));
                $attachments[] = $path;
            }
        }

        // Handle generated attachments (from document selection feature)
        if ($request->has('generated_attachments')) {
            $generatedAttachments = $request->input('generated_attachments', []);
            Log::info('Processing generated attachments', [
                'count' => count($generatedAttachments),
                'paths' => $generatedAttachments
            ]);
            
            foreach ($generatedAttachments as $attachmentPath) {
                $fullPath = storage_path('app/public/' . $attachmentPath);
                
                if (Storage::disk('public')->exists($attachmentPath)) {
                    $attachments[] = $attachmentPath;
                    Log::info('Generated attachment added', [
                        'path' => $attachmentPath,
                        'full_path' => $fullPath,
                        'file_size' => Storage::disk('public')->size($attachmentPath)
                    ]);
                } else {
                    Log::warning('Generated attachment not found', [
                        'path' => $attachmentPath,
                        'full_path' => $fullPath,
                        'storage_disk_exists' => Storage::disk('public')->exists($attachmentPath)
                    ]);
                }
            }
        } else {
            Log::info('No generated_attachments field found in request');
        }

        $email = Email::create([
            'to' => implode(',', $to),
            'cc' => implode(',', $cc),
            'bcc' => implode(',', $bcc),
            'subject' => $request->subject,
            'body' => $request->body,
            'attachments' => json_encode($attachments),
            'status' => $isDraft ? 'draft' : 'sent',
            'sent_at' => $isDraft ? null : now()
        ]);

        Log::info('Email record created', [
            'email_id' => $email->id,
            'attachments_count' => count($attachments),
            'attachments' => $attachments,
            'is_draft' => $isDraft
        ]);

        if (!$isDraft) {
            try {
                Log::info('Attempting to send email', [
                    'email_id' => $email->id,
                    'to' => $to,
                    'attachments' => $attachments
                ]);
                
                Mail::to($to)
                    ->cc($cc)
                    ->bcc($bcc)
                    ->send(new SendEmail($email));

                // ✅ Log the email sent activity
                ActivityLogger::log(
                    'Mail Sent',
                    'Email Module',
                    'Email with subject "' . $email->subject . '" sent to ' . implode(', ', $to)
                );

                return redirect()->route('emails.index')->with('success', 'Email sent successfully.');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to send email: ' . $e->getMessage());
            }
        } else {
            return redirect()->route('emails.drafts')->with('success', 'Email saved as draft.');
        }
    }

    public function update(Request $request, $id)
    {
        $email = Email::findOrFail($id);

        // Different validation rules based on whether we're saving as draft or sending
        $isDraft = $request->has('save_draft') && $request->input('save_draft') == '1';

        $validationRules = [
            'to' => 'required|string',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'subject' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,xls|max:2048',
        ];

        // Only require body if sending email (not saving as draft)
        if ($isDraft) {
            $validationRules['body'] = 'nullable|string';
        } else {
            $validationRules['body'] = 'required|string';
        }

        $request->validate($validationRules);

        $to = array_filter(array_map('trim', explode(',', $request->to)));
        $cc = $request->cc ? array_filter(array_map('trim', explode(',', $request->cc))) : [];
        $bcc = $request->bcc ? array_filter(array_map('trim', explode(',', $request->bcc))) : [];

        $allEmails = array_merge($to, $cc, $bcc);
        foreach ($allEmails as $emailAddress) {
            if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', "Invalid email address: $emailAddress")->withInput();
            }
        }

        $attachments = json_decode($email->attachments, true) ?? [];

        // Handle manual attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = 'attachments/' . $file->getClientOriginalName();

                // Ensure the directory exists
                if (!Storage::disk('public')->exists('attachments')) {
                    Storage::disk('public')->makeDirectory('attachments');
                }

                Storage::disk('public')->put($path, file_get_contents($file));
                $attachments[] = $path;
            }
        }

        $email->update([
            'to' => implode(',', $to),
            'cc' => implode(',', $cc),
            'bcc' => implode(',', $bcc),
            'subject' => $request->subject,
            'body' => $request->body,
            'attachments' => json_encode($attachments),
            'status' => $isDraft ? 'draft' : 'sent',
            'sent_at' => $isDraft ? null : now()
        ]);

        if (!$isDraft) {
            try {
                Mail::to($to)
                    ->cc($cc)
                    ->bcc($bcc)
                    ->send(new SendEmail($email));

                // ✅ Log the email sent activity
                ActivityLogger::log(
                    'Mail Sent',
                    'Email Module',
                    'Email with subject "' . $email->subject . '" sent to ' . implode(', ', $to)
                );

                return redirect()->route('emails.index')->with('success', 'Email sent successfully.');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to send email: ' . $e->getMessage());
            }
        } else {
            return redirect()->route('emails.drafts')->with('success', 'Email draft updated.');
        }
    }

    public function destroy($id)
    {
        $email = Email::findOrFail($id);
        $emailSubject = $email->subject;

        // Delete attachments
        if ($email->attachments) {
            $attachments = json_decode($email->attachments, true);
            foreach ($attachments as $attachment) {
                Storage::disk('public')->delete($attachment);
            }
        }

        $email->delete();

        ActivityLogger::log(
            'Email Deleted',
            'Email Module',
            'Deleted email with subject "' . $emailSubject . '"'
        );

        return redirect()->route('emails.index')->with('success', 'Email deleted successfully.');
    }

    public function testAi(Request $request)
    {
        try {
            // Test 1: Check if GeminiService is available
            $serviceAvailable = $this->geminiService ? 'Yes' : 'No';

            // Test 2: Try to generate simple content
            $testContent = $this->geminiService->generateEmailContentWithDetails(
                'quotation',
                ['name' => 'Test User', 'email' => 'test@example.com'],
                ['name' => 'Test Company'],
                [
                    'document_type' => 'quotation',
                    'document_number' => 'Q001',
                    'customer_name' => 'Test Customer',
                    'total_amount' => '10000.00',
                    'document_date' => now()->format('Y-m-d'),
                    'contact_person' => 'Test Person'
                ]
            );

            return response()->json([
                'success' => true,
                'service_available' => $serviceAvailable,
                'test_content' => $testContent,
                'message' => 'AI test completed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function generateAndAttachDocument(Request $request)
    {
        try {
            $request->validate([
                'document_type' => 'required|in:invoice,quotation',
                'document_id' => 'required|integer'
            ]);

            $documentType = $request->document_type;
            $documentId = $request->document_id;

            // Get document details
            if ($documentType === 'invoice') {
                $document = \App\Models\ims\Invoice::with(['customer'])->findOrFail($documentId);
                $documentNumber = $document->invoice_no;
            } else {
                $document = \App\Models\ims\Quotation::with(['customer'])->findOrFail($documentId);
                $documentNumber = $document->quotation_code;
            }

            // Generate PDF
            $pdfContent = $this->generatePDFContent($documentType, $document);
            $fileName = ucfirst($documentType) . '_' . str_replace('/', '_', $documentNumber) . '.pdf';
            $filePath = 'email_attachments/' . $fileName;

            // Ensure directory exists
            if (!Storage::disk('public')->exists('email_attachments')) {
                Storage::disk('public')->makeDirectory('email_attachments');
            }

            // Save the PDF file
            $saved = Storage::disk('public')->put($filePath, $pdfContent);
            
            if (!$saved) {
                throw new \Exception('Failed to save PDF attachment');
            }

            $customer = $document->customer;
            $customerName = $customer ? $customer->company_name : 'Unknown';

            return response()->json([
                'success' => true,
                'attachment' => [
                    'filename' => $fileName,
                    'path' => $filePath,
                    'url' => asset('storage/' . $filePath)
                ],
                'document_info' => [
                    'type' => $documentType,
                    'number' => $documentNumber,
                    'customer' => $customerName
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error generating document attachment', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to generate document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getEmailAddresses(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $emails = [];

            // Get suppliers (they have email directly)
            $suppliers = \App\Models\ims\Supplier::select('id', 'name', 'company_name', 'email')
                ->when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}%")
                                 ->orWhere('company_name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                })
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->limit(20)
                ->get();

            foreach ($suppliers as $supplier) {
                $emails[] = [
                    'id' => 'supplier_' . $supplier->id,
                    'name' => $supplier->name . ' (' . $supplier->company_name . ')',
                    'email' => $supplier->email,
                    'type' => 'Supplier'
                ];
            }

            // Get contact persons (they have emails and are linked to customers)
            $contactPersons = \App\Models\ims\ContactPerson::with('customer')
                ->select('id', 'name', 'email', 'customer_id')
                ->when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%")
                                 ->orWhereHas('customer', function ($q) use ($search) {
                                     $q->where('company_name', 'like', "%{$search}%");
                                 });
                })
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->limit(30)
                ->get();

            foreach ($contactPersons as $contact) {
                $customerName = $contact->customer ? $contact->customer->company_name : 'Unknown Company';
                $emails[] = [
                    'id' => 'contact_' . $contact->id,
                    'name' => $contact->name . ' (' . $customerName . ')',
                    'email' => $contact->email,
                    'type' => 'Contact Person'
                ];
            }

            return response()->json([
                'success' => true,
                'emails' => $emails
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching email addresses', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch email addresses: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete emails
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'email_ids' => 'required|array',
                'email_ids.*' => 'integer|exists:emails,id'
            ]);

            $emailIds = $request->input('email_ids');
            $deletedCount = Email::whereIn('id', $emailIds)->delete();

            ActivityLogger::log(
                'Bulk Email Deletion',
                'emails',
                null,
                "Deleted {$deletedCount} emails",
                ['email_ids' => $emailIds]
            );

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} email(s)",
                'deleted_count' => $deletedCount
            ]);

        } catch (\Exception $e) {
            Log::error('Error in bulk delete emails', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to delete emails: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark emails as read
     */
    public function markAsRead(Request $request)
    {
        try {
            $request->validate([
                'email_ids' => 'required|array',
                'email_ids.*' => 'integer|exists:emails,id'
            ]);

            $emailIds = $request->input('email_ids');
            
            // Assuming you have a 'read_at' or 'is_read' column
            // If not, you might need to add this column to your emails table
            $updatedCount = Email::whereIn('id', $emailIds)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            ActivityLogger::log(
                'Bulk Mark as Read',
                'emails',
                null,
                "Marked {$updatedCount} emails as read",
                ['email_ids' => $emailIds]
            );

            return response()->json([
                'success' => true,
                'message' => "Successfully marked {$updatedCount} email(s) as read",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            Log::error('Error marking emails as read', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to mark emails as read: ' . $e->getMessage()
            ], 500);
        }
    }
}
