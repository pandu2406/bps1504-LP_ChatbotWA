<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiChatbotService;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    protected $aiService;

    public function __construct(AiChatbotService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Handle incoming webhook from WhatsApp Gateway (e.g., Fonnte, Twilio)
     */
    public function handleWebhook(Request $request)
    {
        // Example handling for generic gateways (usually send 'message' and 'sender' fields)
        $message = $request->input('message') ?? $request->input('body');
        $sender = $request->input('sender') ?? $request->input('from');

        Log::info("WhatsApp Incoming: $sender - $message");

        if (!$message || !$sender) {
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
        }

        // --- PERSONAL NUMBER PROTECTION ---
        // Only activate AI if user has started session with specific keyword
        $cacheKey = "ai_bot_active_{$sender}";
        $isActive = \Illuminate\Support\Facades\Cache::has($cacheKey);

        // Keyword to activate (must match welcome.blade.php)
        $activationKeyword = 'Layanan Chatbot AI Statistik';

        if (stripos($message, $activationKeyword) !== false) {
            // New user activating the bot
            \Illuminate\Support\Facades\Cache::put($cacheKey, true, 60 * 60 * 24); // Active for 24 hours
            $isActive = true;
            // Proceed to generate response for the first message
        }

        if (!$isActive) {
            // Ignore personal messages
            Log::info("Ignored non-bot message from $sender");
            return response()->json([
                'status' => 'ignored',
                'reply' => null // Gateway should handle null reply by doing nothing
            ]);
        }

        // Generate AI Response
        $reply = $this->aiService->generateResponse($message);

        // TODO: Send reply back via Gateway API (Wait for user to provide Gateway details)
        // For now, we return the reply in JSON (some gateways accept response directly)
        return response()->json([
            'status' => 'success',
            'reply' => $reply
        ]);
    }
}
