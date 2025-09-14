<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class GeminiService
{
    private $maxRetries = 3;
    private $retryDelay = 2; // seconds

    public function generateContent(string $userPrompt): string
    {
        // Check if API is temporarily down
        if (Cache::get('gemini_api_down', false)) {
            return $this->getFallbackResponse($userPrompt);
        }

        for ($attempt = 1; $attempt <= $this->maxRetries; $attempt++) {
            try {
                Log::info('Gemini API Request', [
                    'attempt' => $attempt,
                    'prompt_length' => strlen($userPrompt),
                    'api_key_present' => !empty(config('services.gemini.api_key')),
                    'model' => config('services.gemini.model'),
                ]);

                $apiKey = config('services.gemini.api_key');
                $model = config('services.gemini.model', 'gemini-1.5-flash');
                $baseUrl = config('services.gemini.base_url');

                $response = Http::timeout(45)
                    ->retry(2, 1000) // Laravel's built-in retry mechanism
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post("{$baseUrl}/models/{$model}:generateContent?key={$apiKey}", [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => "You are a helpful AI assistant for business operations. Provide professional, clear, and actionable responses. Keep responses concise and practical.\n\nUser request: {$userPrompt}"
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.6,
                            'topK' => 40,
                            'topP' => 0.9,
                            'maxOutputTokens' => 600,
                        ],
                        'safetySettings' => [
                            [
                                'category' => 'HARM_CATEGORY_HARASSMENT',
                                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                            ],
                            [
                                'category' => 'HARM_CATEGORY_HATE_SPEECH',
                                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                            ],
                            [
                                'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                            ],
                            [
                                'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                            ]
                        ]
                    ]);

                Log::info('Gemini API Response', [
                    'attempt' => $attempt,
                    'status' => $response->status(),
                    'successful' => $response->successful(),
                    'body_length' => strlen($response->body())
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                        $content = trim($data['candidates'][0]['content']['parts'][0]['text']);

                        // Clear any API down flag on success
                        Cache::forget('gemini_api_down');

                        Log::info('Gemini Content Generated Successfully', [
                            'attempt' => $attempt,
                            'content_length' => strlen($content)
                        ]);

                        return $content;
                    } else {
                        Log::error('Unexpected Gemini API response structure', [
                            'attempt' => $attempt,
                            'data' => $data
                        ]);

                        if ($attempt === $this->maxRetries) {
                            return 'I received an unexpected response format. Please try rephrasing your question.';
                        }
                        continue;
                    }
                } else {
                    $errorBody = $response->body();
                    $statusCode = $response->status();

                    Log::error('Gemini API Error', [
                        'attempt' => $attempt,
                        'status' => $statusCode,
                        'body' => $errorBody
                    ]);

                    // Handle specific error codes
                    if ($statusCode >= 500) {
                        // Server errors - retry
                        if ($attempt < $this->maxRetries) {
                            Log::info("Server error {$statusCode}, retrying in {$this->retryDelay} seconds...");
                            sleep($this->retryDelay);
                            continue;
                        } else {
                            // Mark API as down temporarily
                            Cache::put('gemini_api_down', true, now()->addMinutes(5));
                            return $this->getFallbackResponse($userPrompt);
                        }
                    } elseif ($statusCode === 429) {
                        // Rate limit - wait longer and retry
                        if ($attempt < $this->maxRetries) {
                            Log::info("Rate limited, waiting longer before retry...");
                            sleep($this->retryDelay * 2);
                            continue;
                        }
                    } elseif ($statusCode === 401 || $statusCode === 403) {
                        // Authentication errors - don't retry
                        return 'Authentication error. Please check your Gemini API key configuration.';
                    }

                    // Parse error message for final attempt
                    if ($attempt === $this->maxRetries) {
                        $errorData = $response->json();
                        if (isset($errorData['error']['message'])) {
                            return 'API Error: ' . $errorData['error']['message'] . '. Please try again later.';
                        }
                        return "Service temporarily unavailable (Error {$statusCode}). Please try again in a few minutes.";
                    }
                }
            } catch (Exception $e) {
                Log::error('Gemini Service Exception', [
                    'attempt' => $attempt,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);

                // Don't retry on certain errors
                if (
                    strpos($e->getMessage(), 'cURL error 6') !== false ||
                    strpos($e->getMessage(), 'Could not resolve host') !== false
                ) {
                    return 'Network connection error. Please check your internet connection and try again.';
                }

                // Retry on timeout and other recoverable errors
                if ($attempt < $this->maxRetries) {
                    if (strpos($e->getMessage(), 'timeout') !== false) {
                        Log::info("Timeout error, retrying in {$this->retryDelay} seconds...");
                        sleep($this->retryDelay);
                        continue;
                    }
                }

                // Final attempt failed
                if ($attempt === $this->maxRetries) {
                    if (strpos($e->getMessage(), 'timeout') !== false) {
                        Cache::put('gemini_api_down', true, now()->addMinutes(5));
                        return $this->getFallbackResponse($userPrompt);
                    }
                    return 'Service temporarily unavailable. Please try again in a moment. Error: ' . $e->getMessage();
                }
            }

            // Wait before retry
            if ($attempt < $this->maxRetries) {
                sleep($this->retryDelay);
            }
        }

        // This should never be reached, but just in case
        return $this->getFallbackResponse($userPrompt);
    }

    private function getFallbackResponse(string $userPrompt): string
    {
        Log::info('Providing fallback response for prompt', ['prompt_length' => strlen($userPrompt)]);

        // Basic keyword matching for common business requests
        $prompt = strtolower($userPrompt);

        if (strpos($prompt, 'email') !== false && strpos($prompt, 'welcome') !== false) {
            return "**Welcome Email Template**\n\nSubject: Welcome to [Company Name]!\n\nDear [Customer Name],\n\nThank you for choosing [Company Name]. We're excited to have you as our valued customer.\n\nOur team is committed to providing you with excellent service and support. If you have any questions, please don't hesitate to contact us.\n\nBest regards,\n[Your Name]\n[Company Name]";
        }

        if (strpos($prompt, 'quotation') !== false || strpos($prompt, 'terms') !== false) {
            return "**Standard Terms & Conditions for Quotation**\n\n1. Validity: This quotation is valid for 30 days from the date issued.\n2. Payment: 50% advance, balance upon completion.\n3. Delivery: As per agreed timeline.\n4. Changes: Any modifications may affect pricing and delivery.\n5. Warranty: Standard warranty terms apply.\n\nPlease contact us for any clarifications.";
        }

        if (strpos($prompt, 'invoice') !== false) {
            return "**Invoice Guidelines**\n\n• Ensure all product details are accurate\n• Include proper GST calculations\n• Verify customer information\n• Add payment terms and due date\n• Include company logo and contact details\n\nFor assistance, contact your accounts team.";
        }

        return "I apologize, but the AI service is currently experiencing issues. Here are some general business tips:\n\n• Always maintain professional communication\n• Keep accurate records of all transactions\n• Follow up with customers promptly\n• Ensure compliance with business regulations\n\nThe AI service should be back online shortly. Please try again in a few minutes.";
    }

    public function generateEmailContent(array $documentData, string $emailType = 'standard'): string
    {
        try {
            $documentType = $documentData['document_type'] ?? 'document';
            $documentNumber = $documentData['document_number'] ?? 'N/A';
            $customerName = $documentData['customer_name'] ?? 'Valued Customer';
            $companyName = $documentData['company_name'] ?? 'Customer';
            $totalAmount = $documentData['total_amount'] ?? '0.00';
            $documentDate = $documentData['document_date'] ?? date('Y-m-d');
            $contactPerson = $documentData['contact_person'] ?? 'Sir/Madam';

            $prompt = "Generate a professional business email for sending a {$documentType} to a customer. 

Document Details:
- Document Type: {$documentType}
- Document Number: {$documentNumber}
- Customer Company: {$companyName}
- Contact Person: {$contactPerson}
- Document Date: {$documentDate}
- Total Amount: ₹{$totalAmount}
- Email Type: {$emailType}

Requirements:
1. Write a professional, warm, and business-appropriate email
2. Include proper greeting using the contact person's name
3. Reference the specific document number and details
4. Mention the attachment
5. Include a call-to-action appropriate for the document type
6. End with professional closing and company signature
7. Keep the tone professional but friendly
8. Make it suitable for Indian business context

The email should be ready to send with minimal editing needed.";

            return $this->generateContent($prompt);
        } catch (Exception $e) {
            Log::error('Error generating email content', [
                'error' => $e->getMessage(),
                'document_data' => $documentData
            ]);

            return $this->getFallbackEmailContent($documentData, $emailType);
        }
    }

    // New enhanced method for AI email generation with user and company details
    public function generateEmailContentWithDetails($documentType, $documentId, $emailType = 'standard', $customPrompt = '', $userDetails = null, $companyDetails = null)
    {
        try {
            // Get document data
            $documentData = $this->getDocumentData($documentType, $documentId);

            if (!$documentData) {
                return $this->getFallbackEmailContentWithDetails($emailType, $userDetails, $companyDetails);
            }

            // Build context-aware prompt
            $prompt = $this->buildEmailPrompt($documentData, $emailType, $customPrompt, $userDetails, $companyDetails);

            return $this->generateContent($prompt);
        } catch (\Exception $e) {
            Log::error('Gemini AI Email Generation Error: ' . $e->getMessage());
            return $this->getFallbackEmailContentWithDetails($emailType, $userDetails, $companyDetails);
        }
    }

    private function getDocumentData($documentType, $documentId)
    {
        try {
            if ($documentType === 'invoice') {
                $invoice = \App\Models\ims\Invoice::with(['customer', 'contactPerson', 'items'])->find($documentId);
                if (!$invoice) return null;

                $customer = $invoice->customer;
                $contactPerson = $invoice->contactPerson;

                return [
                    'type' => 'Invoice',
                    'number' => $invoice->invoice_no,
                    'date' => $invoice->invoice_date,
                    'customer_name' => $customer->company_name ?? 'Valued Customer',
                    'customer_company' => $customer->company_name ?? 'Company',
                    'customer_address' => $customer->address ?? '',
                    'customer_city' => $customer->city ?? '',
                    'customer_state' => $customer->state ?? '',
                    'customer_gst' => $customer->gst_number ?? '',
                    'contact_person_name' => $contactPerson->name ?? 'Sir/Madam',
                    'contact_person_email' => $contactPerson->email ?? '',
                    'contact_person_phone' => $contactPerson->phone_no ?? '',
                    'company_email' => $customer->company_name ? $this->generateCompanyEmail($customer->company_name) : '',
                    'total_amount' => $invoice->total,
                    'due_date' => $invoice->invoice_date ?? $invoice->created_at->format('Y-m-d'),
                    'status' => 'Active',
                    'items_count' => $invoice->items ? $invoice->items->count() : 0
                ];
            } elseif ($documentType === 'quotation') {
                $quotation = \App\Models\ims\Quotation::with(['customer', 'contactPerson', 'items'])->find($documentId);
                if (!$quotation) return null;

                $customer = $quotation->customer;
                $contactPerson = $quotation->contactPerson;

                return [
                    'type' => 'Quotation',
                    'number' => $quotation->quotation_code,
                    'date' => $quotation->quotation_date,
                    'customer_name' => $customer->company_name ?? 'Valued Customer',
                    'customer_company' => $customer->company_name ?? 'Company',
                    'customer_address' => $customer->address ?? '',
                    'customer_city' => $customer->city ?? '',
                    'customer_state' => $customer->state ?? '',
                    'customer_gst' => $customer->gst_number ?? '',
                    'contact_person_name' => $contactPerson->name ?? 'Sir/Madam',
                    'contact_person_email' => $contactPerson->email ?? '',
                    'contact_person_phone' => $contactPerson->phone_no ?? '',
                    'company_email' => $customer->company_name ? $this->generateCompanyEmail($customer->company_name) : '',
                    'total_amount' => $quotation->total,
                    'valid_until' => $quotation->quotation_date ?? $quotation->created_at->format('Y-m-d'),
                    'status' => 'Active',
                    'items_count' => $quotation->items ? $quotation->items->count() : 0
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error fetching document data: ' . $e->getMessage());
            return null;
        }
    }

    private function generateCompanyEmail($companyName)
    {
        // Generate a potential company email based on company name
        $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $companyName));
        return $cleanName . '@gmail.com';
    }

    private function buildEmailPrompt($documentData, $emailType, $customPrompt, $userDetails, $companyDetails)
    {
        $senderName = $userDetails['name'] ?? 'Team Member';
        $senderRole = $userDetails['designation'] ?? $userDetails['role'] ?? 'Representative';
        $senderPhone = $userDetails['phone'] ?? '';
        $senderEmail = $userDetails['email'] ?? '';

        $companyName = $companyDetails['name'] ?? config('app.name', 'Company');
        $companyEmail = $companyDetails['email'] ?? config('mail.from.address', '');

        $prompt = "Generate a professional business email for sending a {$documentData['type']} to a customer.

Document Details:
- Document Type: {$documentData['type']}
- Document Number: {$documentData['number']}
- Document Date: {$documentData['date']}
- Total Amount: ₹{$documentData['total_amount']}
- Email Type: {$emailType}";

        if (isset($documentData['due_date'])) {
            $prompt .= "\n- Due Date: {$documentData['due_date']}";
        }
        if (isset($documentData['valid_until'])) {
            $prompt .= "\n- Valid Until: {$documentData['valid_until']}";
        }

        $prompt .= "\n\nCustomer Details:
- Company Name: {$documentData['customer_company']}
- Contact Person: {$documentData['contact_person_name']}
- Phone: {$documentData['contact_person_phone']}
- Email: {$documentData['contact_person_email']}";

        if ($documentData['customer_address']) {
            $prompt .= "\n- Address: {$documentData['customer_address']}";
        }
        if ($documentData['customer_city']) {
            $prompt .= "\n- City: {$documentData['customer_city']}";
        }
        if ($documentData['customer_state']) {
            $prompt .= "\n- State: {$documentData['customer_state']}";
        }
        if ($documentData['customer_gst']) {
            $prompt .= "\n- GST Number: {$documentData['customer_gst']}";
        }

        $prompt .= "\n\nSender Details:
- Name: {$senderName}
- Role: {$senderRole}
- Company: {$companyName}";

        if ($senderPhone) {
            $prompt .= "\n- Phone: {$senderPhone}";
        }
        if ($senderEmail) {
            $prompt .= "\n- Email: {$senderEmail}";
        }

        if ($customPrompt) {
            $prompt .= "\n\nAdditional Instructions: {$customPrompt}";
        }

        $prompt .= "\n\nRequirements:
1. Write a professional, warm, and business-appropriate email
2. Address the contact person by name: {$documentData['contact_person_name']}
3. Reference the specific document number and details
4. Include customer company name: {$documentData['customer_company']}
5. Mention the attachment
6. Include complete sender's signature with name, role, and contact details
7. Keep the tone professional but friendly
8. Make it suitable for Indian business context
9. Add appropriate call-to-action based on document type
10. Include customer address details if relevant
11. Generate ONLY the email body content, no subject line

The email should be ready to send with minimal editing needed.";

        return $prompt;
    }

    private function getFallbackEmailContentWithDetails($emailType, $userDetails, $companyDetails)
    {
        // Use the company's standard email template
        return "Dear Sir,

Good Afternoon,

As discussed, please find the attached quotation for your requirements.

We kindly request you to confirm your valuable order with us at your earliest convenience.

We assure you of our best service and support at all times.

Thank you and regards,

R. Radhika
Partner
SKM and Company
8870820449
skmandcompany@yahoo.in";
    }

    private function getFallbackEmailContent(array $documentData, string $emailType): string
    {
        $documentType = $documentData['document_type'] ?? 'document';
        $documentNumber = $documentData['document_number'] ?? 'N/A';
        $companyName = $documentData['company_name'] ?? 'Customer';
        $totalAmount = $documentData['total_amount'] ?? '0.00';
        $contactPerson = $documentData['contact_person'] ?? 'Sir/Madam';

        return "Dear {$contactPerson},

Good afternoon,

We hope this email finds you well.

Please find attached the {$documentType} ({$documentNumber}) for {$companyName} with a total amount of ₹{$totalAmount}.

We have prepared this document as per your requirements and request you to review the details. Should you have any questions or require any modifications, please feel free to contact us.

We look forward to your confirmation and continuing our business relationship.

Thank you for choosing our services.

Best regards,

SKM & Company
Contact: +91 9876543210
Email: info@skmcompany.com

Note: This email was generated using AI assistance. Please review before sending.";
    }

    public function testConnection(): array
    {
        try {
            $apiKey = config('services.gemini.api_key');
            $baseUrl = config('services.gemini.base_url');

            $response = Http::timeout(10)
                ->get("{$baseUrl}/models?key={$apiKey}");

            $success = $response->successful();

            if ($success) {
                Cache::forget('gemini_api_down');
            }

            return [
                'success' => $success,
                'status' => $response->status(),
                'message' => $success ? 'Connection successful' : 'Connection failed',
                'details' => $success ? 'Gemini API is responding normally' : 'Gemini API may be experiencing issues'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'status' => 0,
                'message' => 'Connection error: ' . $e->getMessage(),
                'details' => 'Please check your internet connection and API configuration'
            ];
        }
    }
}
