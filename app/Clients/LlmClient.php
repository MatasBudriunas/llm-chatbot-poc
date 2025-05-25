<?php

declare(strict_types=1);

namespace App\Clients;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class LlmClient
{
    public function send(string $endpoint, array $payload): array
    {
        $url = rtrim(env('LLM_CLIENT_WRAPPER_URL'), '/') . '/' . ltrim($endpoint, '/');

        $response = Http::withHeaders([
            'X-API-KEY' => env('LLM_CLIENT_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post($url, array_merge($payload, [
            'api_key' => env('LLM_CLIENT_API_KEY'),
        ]));

        if (!$response->successful()) {
            throw new RuntimeException("LLM wrapper error: " . $response->body());
        }

        return $response->json();
    }
}
