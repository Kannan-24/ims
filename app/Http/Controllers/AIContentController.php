<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenRouterService;

class AIContentController extends Controller
{
    public function generate(Request $request, OpenRouterService $openRouter)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $content = $openRouter->generateContent($request->prompt);

        return response()->json(['content' => $content]);
    }
}
