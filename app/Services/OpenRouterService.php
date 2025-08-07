<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class OpenRouterService
{
    private $maxRetries = 3;
    private $retryDelay = 2; // seconds
    
    public function generateContent(string $userPrompt): string
    {
        // Check if API is temporarily down
        if (Cache::get('openrouter_api_down', false)) {
            return $this->getFallbackResponse($userPrompt);
        }

        for ($attempt = 1; $attempt <= $this->maxRetries; $attempt++) {
            try {
                Log::info('OpenRouter API Request', [
                    'attempt' => $attempt,
                    'prompt_length' => strlen($userPrompt),
                    'api_key_present' => !empty(config('services.openrouter.key')),
                    'model' => config('services.openrouter.model'),
                    'referer' => config('services.openrouter.referer')
                ]);

                $response = Http::timeout(45) // Reduced from 60 to 45 seconds
                    ->retry(2, 1000) // Laravel's built-in retry mechanism
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . config('services.openrouter.key'),
                        'Content-Type' => 'application/json',
                        'HTTP-Referer' => config('services.openrouter.referer', config('app.url')),
                        'X-Title' => 'Laravel IMS AI Copilot',
                    ])
                    ->post('https://openrouter.ai/api/v1/chat/completions', [
                        'model' => config('services.openrouter.model', 'deepseek/deepseek-chat'),
                        'messages' => [
                            [
                                'role' => 'system', 
                                'content' => 'You are a helpful AI assistant for business operations. Provide professional, clear, and actionable responses. Keep responses concise and practical.'
                            ],
                            [
                                'role' => 'user', 
                                'content' => $userPrompt
                            ],
                        ],
                        'max_tokens' => 600, // Further reduced to speed up responses
                        'temperature' => 0.6, // Slightly more focused responses
                        'stream' => false,
                        'top_p' => 0.9, // Add top_p for more consistent responses
                    ]);

                Log::info('OpenRouter API Response', [
                    'attempt' => $attempt,
                    'status' => $response->status(),
                    'successful' => $response->successful(),
                    'body_length' => strlen($response->body())
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['choices'][0]['message']['content'])) {
                        $content = trim($data['choices'][0]['message']['content']);
                        
                        // Clear any API down flag on success
                        Cache::forget('openrouter_api_down');
                        
                        Log::info('AI Content Generated Successfully', [
                            'attempt' => $attempt,
                            'content_length' => strlen($content)
                        ]);
                        
                        return $content;
                    } else {
                        Log::error('Unexpected API response structure', [
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
                    
                    Log::error('OpenRouter API Error', [
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
                            Cache::put('openrouter_api_down', true, now()->addMinutes(5));
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
                        return 'Authentication error. Please check your API key configuration.';
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
                Log::error('OpenRouter Service Exception', [
                    'attempt' => $attempt,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);

                // Don't retry on certain errors
                if (strpos($e->getMessage(), 'cURL error 6') !== false || 
                    strpos($e->getMessage(), 'Could not resolve host') !== false) {
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
                        Cache::put('openrouter_api_down', true, now()->addMinutes(5));
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

    public function testConnection(): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . config('services.openrouter.key'),
                    'Content-Type' => 'application/json',
                ])
                ->get('https://openrouter.ai/api/v1/models');

            $success = $response->successful();
            
            if ($success) {
                Cache::forget('openrouter_api_down');
            }

            return [
                'success' => $success,
                'status' => $response->status(),
                'message' => $success ? 'Connection successful' : 'Connection failed',
                'details' => $success ? 'API is responding normally' : 'API may be experiencing issues'
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
