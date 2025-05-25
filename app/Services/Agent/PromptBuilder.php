<?php

declare(strict_types=1);

namespace App\Services\Agent;

use App\Models\Agent;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PromptBuilder
{
    public function __construct(protected ChatSessionManager $sessionManager) {}

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function buildPrompt(Agent $agent, string $sessionId): string
    {
        $key = "chat_{$agent->slug}_{$sessionId}";

        $history = session()->get($key, []);

        $messages = collect($history)
            ->map(fn ($msg) => "{$msg['role']}: {$msg['content']}")
            ->join("\n");

        return $agent->personality . "\n" . $messages;
    }
}
