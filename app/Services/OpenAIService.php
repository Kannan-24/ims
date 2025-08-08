<?php

namespace App\Services;

use OpenAI;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class OpenAIService
{
    protected $client;
    protected $model;
    protected $maxTokens;
    protected $temperature;

    public function __construct()
    {
        $baseUrl = config('openai.base_url', 'https://api.openai.com/v1');
        
        // Create client with custom base URL for OpenRouter support
        $this->client = OpenAI::factory()
            ->withApiKey(config('openai.api_key'))
            ->withBaseUri($baseUrl)
            ->make();
            
        $this->model = config('openai.model', 'gpt-4o-mini');
        $this->maxTokens = config('openai.max_tokens', 1500);
        $this->temperature = config('openai.temperature', 0.7);
    }

    /**
     * Generate professional email body content
     *
     * @param array $emailData
     * @return string|null
     */
    public function generateEmailBody(array $emailData): ?string
    {
        try {
            $cacheKey = 'openai_email_' . md5(serialize($emailData));
            
            // Check cache first
            if (config('openai.cache_enabled', true)) {
                $cached = Cache::get($cacheKey);
                if ($cached) {
                    return $cached;
                }
            }

            $prompt = $this->buildEmailPrompt($emailData);
            
            $response = $this->client->chat()->create([
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional business email writer. Create formal, polite, and clear email content. Always include appropriate greetings and professional closing. Focus on clarity and professionalism.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => $this->maxTokens,
                'temperature' => $this->temperature,
            ]);

            $content = $response->choices[0]->message->content ?? null;

            // Cache the result
            if ($content && config('openai.cache_enabled', true)) {
                Cache::put($cacheKey, $content, config('openai.cache_duration', 3600));
            }

            return $content;

        } catch (Exception $e) {
            Log::error('OpenAI Email Generation Error: ' . $e->getMessage());
            return $this->getEmailFallback($emailData);
        }
    }

    /**
     * Generate quotation terms and conditions
     *
     * @param array $quotationData
     * @return string|null
     */
    public function generateQuotationTerms(array $quotationData): ?string
    {
        try {
            $cacheKey = 'openai_quotation_' . md5(serialize($quotationData));
            
            // Check cache first
            if (config('openai.cache_enabled', true)) {
                $cached = Cache::get($cacheKey);
                if ($cached) {
                    return $cached;
                }
            }

            $prompt = $this->buildQuotationPrompt($quotationData);
            
            $response = $this->client->chat()->create([
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a legal and business expert specializing in creating comprehensive terms and conditions for quotations. Create professional, legally sound, and business-appropriate terms. Be concise but thorough.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => $this->maxTokens,
                'temperature' => 0.3, // Lower temperature for more consistent legal content
            ]);

            $content = $response->choices[0]->message->content ?? null;

            // Cache the result
            if ($content && config('openai.cache_enabled', true)) {
                Cache::put($cacheKey, $content, config('openai.cache_duration', 3600));
            }

            return $content;

        } catch (Exception $e) {
            Log::error('OpenAI Quotation Terms Generation Error: ' . $e->getMessage());
            return $this->getQuotationFallback($quotationData);
        }
    }

    /**
     * Generate custom content based on user prompt
     *
     * @param string $prompt
     * @param array $context
     * @return string|null
     */
    public function generateCustomContent(string $prompt, array $context = []): ?string
    {
        try {
            $cacheKey = 'openai_custom_' . md5($prompt . serialize($context));
            
            // Check cache first
            if (config('openai.cache_enabled', true)) {
                $cached = Cache::get($cacheKey);
                if ($cached) {
                    return $cached;
                }
            }

            $enhancedPrompt = $this->buildCustomPrompt($prompt, $context);
            
            $response = $this->client->chat()->create([
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional business content writer. Create high-quality, professional content based on the user\'s requirements. Maintain a business-appropriate tone and format. Be clear and concise.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $enhancedPrompt
                    ]
                ],
                'max_tokens' => $this->maxTokens,
                'temperature' => $this->temperature,
            ]);

            $content = $response->choices[0]->message->content ?? null;

            // Cache the result
            if ($content && config('openai.cache_enabled', true)) {
                Cache::put($cacheKey, $content, config('openai.cache_duration', 3600));
            }

            return $content;

        } catch (Exception $e) {
            Log::error('OpenAI Custom Content Generation Error: ' . $e->getMessage());
            return "I apologize, but I'm unable to generate the requested content at this moment. Please try again later or contact support if the issue persists.";
        }
    }

    /**
     * Test OpenAI connection
     *
     * @return array
     */
    public function testConnection(): array
    {
        try {
            // Check if API key is configured
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'OpenAI API key is not configured. Please set your API key in the .env file.',
                    'response' => null,
                    'model' => $this->model
                ];
            }

            $response = $this->client->chat()->create([
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => 'Hello! Please respond with "DeepSeek AI connection successful!" to confirm the integration is working.'
                    ]
                ],
                'max_tokens' => 50,
                'temperature' => 0.1,
            ]);

            $content = $response->choices[0]->message->content ?? '';

            return [
                'success' => true,
                'message' => 'DeepSeek AI connection successful via OpenRouter',
                'response' => $content,
                'model' => $this->model
            ];

        } catch (Exception $e) {
            Log::error('OpenAI Connection Test Error: ' . $e->getMessage());
            
            // Check for specific quota error
            if (strpos($e->getMessage(), 'exceeded your current quota') !== false) {
                return [
                    'success' => false,
                    'message' => 'OpenAI API quota exceeded. Please check your billing and usage limits at https://platform.openai.com/usage',
                    'response' => null,
                    'model' => $this->model,
                    'error_type' => 'quota_exceeded'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
                'response' => null,
                'model' => $this->model
            ];
        }
    }

    /**
     * Build email generation prompt
     */
    private function buildEmailPrompt(array $data): string
    {
        $type = $data['type'] ?? 'general';
        $recipientName = $data['recipient_name'] ?? 'Valued Customer';
        $companyName = $data['company_name'] ?? 'Customer';
        $subject = $data['subject'] ?? '';
        $invoiceNumber = $data['invoice_number'] ?? '';
        $amount = $data['amount'] ?? '';
        $dueDate = $data['due_date'] ?? '';
        $context = $data['context'] ?? '';

        $prompt = "Generate a professional {$type} email with the following details:\n\n";
        $prompt .= "Recipient: {$recipientName}\n";
        $prompt .= "Company: {$companyName}\n";
        
        if ($subject) $prompt .= "Subject Context: {$subject}\n";
        if ($invoiceNumber) $prompt .= "Invoice/Quote Number: {$invoiceNumber}\n";
        if ($amount) $prompt .= "Amount: \${$amount}\n";
        if ($dueDate) $prompt .= "Due Date: {$dueDate}\n";
        if ($context) $prompt .= "Additional Context: {$context}\n";

        $prompt .= "\nPlease create a complete email body that is:\n";
        $prompt .= "- Professional and courteous\n";
        $prompt .= "- Clear and concise\n";
        $prompt .= "- Includes proper greeting and closing\n";
        $prompt .= "- Appropriate for business communication\n";
        
        if ($type === 'invoice') {
            $prompt .= "- Clearly states the invoice details and payment instructions\n";
        } elseif ($type === 'quotation') {
            $prompt .= "- Professionally presents the quotation and next steps\n";
        } elseif ($type === 'payment_reminder') {
            $prompt .= "- Politely reminds about the payment due\n";
        }

        return $prompt;
    }

    /**
     * Build quotation terms prompt
     */
    private function buildQuotationPrompt(array $data): string
    {
        $companyName = $data['company_name'] ?? 'Our Company';
        $businessType = $data['business_type'] ?? 'Business Services';
        $amount = $data['quotation_amount'] ?? '';
        $validity = $data['validity_period'] ?? '30';
        $paymentTerms = $data['payment_terms'] ?? '';
        $deliveryTerms = $data['delivery_terms'] ?? '';
        $itemsDescription = $data['items_description'] ?? '';
        $specialConditions = $data['special_conditions'] ?? '';

        $prompt = "Generate comprehensive terms and conditions for a quotation with these details:\n\n";
        $prompt .= "Company: {$companyName}\n";
        $prompt .= "Business Type: {$businessType}\n";
        
        if ($amount) $prompt .= "Quotation Amount: \${$amount}\n";
        $prompt .= "Validity Period: {$validity} days\n";
        if ($paymentTerms) $prompt .= "Payment Terms: {$paymentTerms}\n";
        if ($deliveryTerms) $prompt .= "Delivery Terms: {$deliveryTerms}\n";
        if ($itemsDescription) $prompt .= "Items/Services: {$itemsDescription}\n";
        if ($specialConditions) $prompt .= "Special Conditions: {$specialConditions}\n";

        $prompt .= "\nCreate professional terms and conditions that include:\n";
        $prompt .= "1. Quotation validity and acceptance terms\n";
        $prompt .= "2. Payment terms and conditions\n";
        $prompt .= "3. Delivery/service terms\n";
        $prompt .= "4. Warranty and liability clauses\n";
        $prompt .= "5. Cancellation and modification policies\n";
        $prompt .= "6. General terms applicable to the business\n";
        $prompt .= "\nEnsure the terms are legally appropriate and business-friendly.";

        return $prompt;
    }

    /**
     * Build custom content prompt
     */
    private function buildCustomPrompt(string $userPrompt, array $context): string
    {
        $prompt = $userPrompt . "\n\n";

        if (!empty($context)) {
            $prompt .= "Context Information:\n";
            foreach ($context as $key => $value) {
                if (!empty($value)) {
                    $formattedKey = ucwords(str_replace('_', ' ', $key));
                    $prompt .= "- {$formattedKey}: {$value}\n";
                }
            }
            $prompt .= "\n";
        }

        $prompt .= "Please use the provided context to create personalized, professional content.";

        return $prompt;
    }

    /**
     * Get fallback email content
     */
    private function getEmailFallback(array $data): string
    {
        $type = $data['type'] ?? 'general';
        $recipientName = $data['recipient_name'] ?? 'Valued Customer';
        
        $fallbacks = [
            'invoice' => "Dear {$recipientName},\n\nI hope this email finds you well. Please find attached your invoice for our recent services. We appreciate your business and look forward to your prompt payment.\n\nIf you have any questions, please don't hesitate to contact us.\n\nBest regards,\n[Your Name]",
            'quotation' => "Dear {$recipientName},\n\nThank you for your interest in our services. Please find attached our quotation for your review. We believe this proposal meets your requirements and offers excellent value.\n\nWe look forward to the opportunity to work with you.\n\nBest regards,\n[Your Name]",
            'general' => "Dear {$recipientName},\n\nThank you for your inquiry. We appreciate your interest in our services and will respond to your request as soon as possible.\n\nBest regards,\n[Your Name]"
        ];

        return $fallbacks[$type] ?? $fallbacks['general'];
    }

    /**
     * Get fallback quotation terms
     */
    private function getQuotationFallback(array $data): string
    {
        $validity = $data['validity_period'] ?? '30';
        
        return "TERMS AND CONDITIONS\n\n" .
               "1. This quotation is valid for {$validity} days from the date of issue.\n" .
               "2. Payment terms as agreed upon acceptance of quotation.\n" .
               "3. Delivery terms as specified in the quotation.\n" .
               "4. All work will be performed in accordance with industry standards.\n" .
               "5. Changes to the scope of work may result in additional charges.\n" .
               "6. These terms are subject to our standard business conditions.";
    }

    /**
     * Check if OpenAI is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty(config('openai.api_key')) && config('openai.api_key') !== 'your_openai_api_key_here';
    }

    /**
     * Get service status information
     */
    public function getStatus(): array
    {
        return [
            'configured' => $this->isConfigured(),
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
            'temperature' => $this->temperature,
            'cache_enabled' => config('openai.cache_enabled', true),
            'api_key_set' => !empty(config('openai.api_key'))
        ];
    }
}
