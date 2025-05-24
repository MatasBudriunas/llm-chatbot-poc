<?php

declare(strict_types=1);

namespace App\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class LlmClient
{
    /**
     * @throws ConnectionException
     */
    public function sendPrompt(string $prompt, string $sessionId): array
    {
        $response = Http::withHeaders([
            'X-API-KEY' => env('LLM_CLIENT_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post(env('LLM_CLIENT_WRAPPER_URL'), [
            'model' => 'llama3',
            'prompt' => $prompt,
            'session_id' => $sessionId,
            'stream' => false,
            'api_key' => env('LLM_CLIENT_API_KEY'),
        ]);

        if (!$response->successful()) {
            throw new RuntimeException('Failed to contact LLM wrapper.');
        }

        return $response->json();
    }
}
