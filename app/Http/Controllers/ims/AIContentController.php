<?php

namespace App\Http\Controllers\ims;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class AIContentController extends Controller
{
    public function show()
    {
        return view('ims.ai.copilot');
    }

    public function generate(Request $request, GeminiService $geminiService)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
        ]);

        try {
            // Log the request for analytics
            Log::info('AI Content Generation Request', [
                'user_id' => Auth::id(),
                'prompt_length' => strlen($request->prompt),
                'timestamp' => now()
            ]);

            // Check rate limiting (optional)
            $cacheKey = 'ai_requests_' . Auth::id() . '_' . now()->format('Y-m-d-H');
            $requestCount = Cache::get($cacheKey, 0);

            if ($requestCount >= 50) { // 50 requests per hour limit
                return response()->json([
                    'error' => 'Rate limit exceeded. Please try again later.',
                    'content' => null
                ], 429);
            }

            Cache::put($cacheKey, $requestCount + 1, 3600); // 1 hour TTL

            // Enhanced prompt for better business context
            $enhancedPrompt = $this->enhancePrompt($request->prompt);

            $content = $geminiService->generateContent($enhancedPrompt);

            // Log successful generation
            Log::info('AI Content Generated Successfully', [
                'user_id' => Auth::id(),
                'content_length' => strlen($content)
            ]);

            return response()->json([
                'content' => $content,
                'timestamp' => now()->toISOString(),
                'model' => 'DeepSeek AI',
                'usage_info' => [
                    'requests_today' => $requestCount + 1,
                    'limit' => 50
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('AI Content Generation Failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'prompt' => $request->prompt
            ]);

            return response()->json([
                'error' => 'Failed to generate content. Please try again.',
                'content' => null
            ], 500);
        }
    }

    private function enhancePrompt(string $userPrompt): string
    {
        // Add business context to improve AI responses
        $businessContext = "You are an AI assistant helping with business operations for an inventory management system. " .
            "Please provide professional, business-appropriate responses. " .
            "Focus on clarity, professionalism, and practical business value.\n\n";

        // Detect prompt type and add specific instructions
        $lowercasePrompt = strtolower($userPrompt);

        if (str_contains($lowercasePrompt, 'email')) {
            $businessContext .= "When writing emails, use professional business tone, proper formatting, and include appropriate greetings and closings.\n\n";
        } elseif (str_contains($lowercasePrompt, 'quotation') || str_contains($lowercasePrompt, 'terms')) {
            $businessContext .= "When creating terms and conditions, ensure they are legally sound, clear, and protect business interests while being fair to customers.\n\n";
        } elseif (str_contains($lowercasePrompt, 'product') || str_contains($lowercasePrompt, 'description')) {
            $businessContext .= "When writing product descriptions, focus on benefits, features, and value proposition. Use persuasive but honest language.\n\n";
        }

        return $businessContext . "User Request: " . $userPrompt;
    }

    public function getUsageStats()
    {
        $userId = Auth::id();
        $today = now()->format('Y-m-d');

        $dailyRequests = Cache::get("ai_daily_requests_{$userId}_{$today}", 0);
        $totalRequests = Cache::get("ai_total_requests_{$userId}", 0);

        return response()->json([
            'daily_requests' => $dailyRequests,
            'total_requests' => $totalRequests,
            'daily_limit' => 50
        ]);
    }

    public function testConnection(GeminiService $geminiService)
    {
        $result = $geminiService->testConnection();
        return response()->json($result);
    }
}
