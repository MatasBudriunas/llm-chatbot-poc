<?php

declare(strict_types=1);

namespace App\Services\Agent;

use App\Models\Agent;

class AgentFactory
{
    public function create(array $data): Agent
    {
        $agent = new Agent();

        $agent->slug = $data['slug'];
        $agent->name = $data['name'];
        $agent->personality = $data['personality'];

        return $agent;
    }
}
