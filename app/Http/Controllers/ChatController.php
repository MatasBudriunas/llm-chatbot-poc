<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ChatAgentRequest;
use App\Services\Agent\ChatAgentService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ChatController extends Controller
{
    public function __construct(private readonly ChatAgentService $chatAgentService) {}

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ConnectionException
     */
    public function handle(ChatAgentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $response = $this->chatAgentService->respond(
            $validated['input'],
            $validated['session_id'],
            $validated['agent']
        );

        return response()->json($response);
    }
}
