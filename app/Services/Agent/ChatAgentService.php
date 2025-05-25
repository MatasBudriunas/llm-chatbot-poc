<?php

declare(strict_types=1);

namespace App\Services\Agent;

use App\Clients\LlmClient;
use App\Repositories\AgentRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

class ChatAgentService
{
    public function __construct(
        protected LlmClient $client,
        protected PromptBuilder $promptBuilder,
        protected ChatSessionManager $sessionManager,
        protected AgentRepository $agentRepository,
    ) {}

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function respond(string $userInput, string $sessionId, string $agentSlug): array
    {
        $agent = $this->agentRepository->findOneBySlug($agentSlug);

        if (!$agent) {
            throw new RuntimeException("Agent not found: {$agentSlug}");
        }

        $this->sessionManager->appendUserMessage($sessionId, $agent->slug, $userInput);

        $prompt = $this->promptBuilder->buildPrompt($agent, $sessionId);

        $response = $this->client->send('chat', [
            'model' => 'llama3',
            'prompt' => $prompt,
            'session_id' => $sessionId,
            'agent' => $agent->slug,
            'stream' => false,
        ]);

        $this->sessionManager->appendAssistantMessage($sessionId, $agent->slug, $response['reply']);

        return $response;
    }
}
