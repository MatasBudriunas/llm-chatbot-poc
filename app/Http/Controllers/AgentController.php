<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AgentCreateRequest;
use App\Repositories\AgentRepository;
use App\Services\AgentCreationService;
use Illuminate\Http\JsonResponse;

class AgentController extends Controller
{
    public function __construct(
        private readonly AgentCreationService $service,
        private readonly AgentRepository $repository,
    ) {}

    public function index(): JsonResponse
    {
        $agents = $this->repository->getAll();

        return response()->json($agents);
    }

    public function create(AgentCreateRequest $request): JsonResponse
    {
        $this->service->generate(
            $request->validated('name'),
            $request->validated('model'),
            $request->validated('prompt'),
        );

        return response()->json(['success' => true]);
    }
}
