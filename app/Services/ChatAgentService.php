<?php

declare(strict_types=1);

namespace App\Services;

use App\Clients\LlmClient;
use Illuminate\Http\Client\ConnectionException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ChatAgentService
{
    public function __construct(protected LlmClient $client) {}

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ConnectionException
     */
    public function respond(string $userPrompt, string $sessionId, string $agent = 'bitsy'): array
    {
        $config = config("agents.$agent");

        $sessionKey = "chat_{$agent}_$sessionId";

        $history = session()->get($sessionKey, []);
        $history[] = ['role' => 'user', 'content' => $userPrompt];

        $messages = collect($history)
            ->map(fn ($msg) => "{$msg['role']}: {$msg['content']}")
            ->join("\n");

        $prompt = $config['persona'] . "\n" . $messages;

        session()->put($sessionKey, [...$history, ['role' => 'assistant', 'content' => '<<pending>>']]);

        $response = $this->client->sendPrompt($prompt, $sessionId);

        $finalHistory = session()->get($sessionKey);
        $finalHistory[count($finalHistory) - 1]['content'] = $response['reply'];

        session()->put($sessionKey, $finalHistory);

        return $response;
    }
}
