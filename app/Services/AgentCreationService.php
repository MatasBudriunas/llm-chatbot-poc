<?php

declare(strict_types=1);

namespace App\Services;

use App\Clients\LlmClient;
use App\Models\Agent;
use App\Services\Agent\AgentStoreManager;
use Illuminate\Support\Str;
use RuntimeException;

readonly class AgentCreationService
{
    public function __construct(
        private LlmClient $client,
        private UniqueSlugService $slugService,
        private AgentStoreManager $agentStoreManager,
    ) {}

    public function generate(string $name, string $model, string $prompt): void
    {
        $basePrompt = config('agents.generation_prompt');
        $wrappedPrompt = sprintf($basePrompt, $prompt);

        $response = $this->client->send('agent', [
            'model' => $model,
            'prompt' => $wrappedPrompt,
            'stream' => false,
        ]);

        if (!isset($response['personality'])) {
            throw new RuntimeException("Invalid agent response: 'personality' missing");
        }

        $slug = $this->slugService->generateUniqueSlug(new Agent(), Str::slug($name));

        $this->agentStoreManager->store([
            'name' => $name,
            'slug' => $slug,
            'personality' => $response['personality'],
        ]);
    }
}
