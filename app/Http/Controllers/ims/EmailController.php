<?php

namespace App\Http\Controllers\ims;

use App\Mail\SendEmail;
use App\Models\ims\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class EmailController extends Controller
{
    public function index()
    {
        $emails = Email::latest()->get();
        $drafts = Email::drafts()->count();
        return view('ims.emails.index', compact('emails', 'drafts'));
    }

    public function drafts()
    {
        $emails = Email::drafts()->latest()->get();
        return view('ims.emails.drafts', compact('emails'));
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

    public function create()
    {
        return view('ims.emails.create');
    }

    public function createDraft(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:invoice,quotation',
            'document_id' => 'required|integer',
        ]);

        $documentType = $request->document_type;
        $documentId = $request->document_id;

        // Get document details
        if ($documentType === 'invoice') {
            $document = \App\Models\ims\Invoice::with(['customer', 'customer.contactPersons', 'items'])->findOrFail($documentId);
            $documentNumber = $document->invoice_no;
            $documentDate = $document->invoice_date;
            $totalAmount = $document->total;
        } else {
            $document = \App\Models\ims\Quotation::with(['customer', 'customer.contactPersons', 'items'])->findOrFail($documentId);
            $documentNumber = $document->quotation_code;
            $documentDate = $document->quotation_date;
            $totalAmount = $document->total;
        }

        $customer = $document->customer;
        $contactPerson = $customer->contactPersons->first();

        // Company Information
        $companyInfo = [
            'name' => 'SKM&COMPANY',
            'email' => 'info@skmcompany.com',
            'phone' => '+91 9876543210',
            'address' => '123 Business Street, City, State - 123456'
        ];

        // Generate PDF and save it temporarily
        $pdfContent = $this->generatePDFContent($documentType, $document);
        $fileName = ucfirst($documentType) . '_' . $documentNumber . '.pdf';
        $filePath = 'email_attachments/' . $fileName;

        // Ensure the directory exists
        if (!Storage::disk('public')->exists('email_attachments')) {
            Storage::disk('public')->makeDirectory('email_attachments');
        }

        // Store the PDF file
        Storage::disk('public')->put($filePath, $pdfContent);

        // Generate email body
        $emailBody = $this->generateEmailBody(
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

        return redirect()->route('emails.edit', $email->id)->with('success', 'Email draft created successfully! You can now edit and send it.');
    }

    private function generatePDFContent($documentType, $document)
    {
        if ($documentType === 'invoice') {
            $pdf = Pdf::loadView('ims.invoices.pdf', ['invoice' => $document])->setPaper('a4', 'portrait');
        } else {
            $pdf = Pdf::loadView('ims.quotations.pdf', ['quotation' => $document])->setPaper('a4', 'portrait');
        }

        return $pdf->output();
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

        // Handle auto-attachment (PDF)
        if ($request->has('auto_attachment')) {
            $attachments[] = $request->auto_attachment;
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
}
