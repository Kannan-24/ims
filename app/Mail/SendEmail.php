<?php

namespace App\Mail;

use App\Models\ims\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Email $email
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->subject($this->email->subject)
            ->from('skmandcompany@yahoo.in');

        // Convert string to arrays
        $to = array_filter(array_map('trim', explode(',', $this->email->to)));
        $cc = $this->email->cc ? array_filter(array_map('trim', explode(',', $this->email->cc))) : [];
        $bcc = $this->email->bcc ? array_filter(array_map('trim', explode(',', $this->email->bcc))) : [];

        // Apply recipients
        $email->to($to)->cc($cc)->bcc($bcc);

        // Set HTML body
        $email->html($this->email->body);

        // Attach files
        $attachments = json_decode($this->email->attachments);
        if ($attachments) {
            foreach ($attachments as $path) {
                $fullPath = storage_path('app/public/' . $path);
                
                // Check if file exists before trying to attach
                if (file_exists($fullPath)) {
                    $email->attach($fullPath);
                } else {
                    \Log::error('Attachment file not found', [
                        'path' => $path,
                        'email_id' => $this->email->id
                    ]);
                }
            }
        }

        return $email;
    }
}
