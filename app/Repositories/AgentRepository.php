<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Agent;

class AgentRepository
{
    public function getAll(): array
    {
        return Agent::query()
            ->select('slug', 'name')
            ->get()
            ->toArray();
    }

    public function findOneBySlug(string $slug): ?Agent
    {
        return Agent::query()
            ->where('slug', '=', $slug)
            ->first();
    }
}
