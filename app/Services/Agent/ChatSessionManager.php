<?php

declare(strict_types=1);

namespace App\Services\Agent;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ChatSessionManager
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function appendUserMessage(string $sessionId, string $agent, string $message): void
    {
        $this->append($sessionId, $agent, 'user', $message);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function appendAssistantMessage(string $sessionId, string $agent, string $message): void
    {
        $sessionKey = $this->sessionKey($sessionId, $agent);

        $history = session()->get($sessionKey);
        $history[count($history) - 1]['content'] = $message;

        session()->put($sessionKey, $history);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getHistory(string $sessionId, string $agent): array
    {
        return session()->get($this->sessionKey($sessionId, $agent), []);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function append(string $sessionId, string $agent, string $role, string $message): void
    {
        $sessionKey = $this->sessionKey($sessionId, $agent);
        $history = session()->get($sessionKey, []);
        $history[] = ['role' => $role, 'content' => $message];

        if ($role === 'user') {
            $history[] = ['role' => 'assistant', 'content' => '<<pending>>'];
        }

        session()->put($sessionKey, $history);
    }

    protected function sessionKey(string $sessionId, string $agent): string
    {
        return "chat_{$agent}_$sessionId";
    }
}
