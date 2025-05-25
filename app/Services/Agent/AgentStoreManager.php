<?php

declare(strict_types=1);

namespace App\Services\Agent;

readonly class AgentStoreManager
{
    public function __construct(private AgentFactory $agentFactory) {
    }

    public function store(array $data): bool
    {
        $agent = $this->agentFactory->create($data);

        return $agent->save();
    }
}
