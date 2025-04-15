<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ActivityLogger;

class EmailController extends Controller
{
    public function index()
    {
        $emails = Email::latest()->get();
        return view('emails.index', compact('emails'));
    }

    public function show($id)
    {
        $email = Email::findOrFail($id);
        return view('emails.show', compact('email'));
    }

    public function create()
    {
        return view('emails.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'subject' => 'required|string',
            'body' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,xls|max:2048',
        ]);

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
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('attachments', 'public');
            }
        }

        $email = Email::create([
            'to' => implode(',', $to),
            'cc' => implode(',', $cc),
            'bcc' => implode(',', $bcc),
            'subject' => $request->subject,
            'body' => $request->body,
            'attachments' => json_encode($attachments),
        ]);

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
            \Log::error('Mail send error: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
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

        // ✅ Log the email delete activity
        ActivityLogger::log(
            'Email Deleted',
            'Email Module',
            'Deleted email with subject "' . $emailSubject . '"'
        );

        return redirect()->route('emails.index')->with('success', 'Email deleted successfully.');
    }
}
