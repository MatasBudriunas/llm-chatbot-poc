<?php

declare(strict_types=1);

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AgentController;

Route::get('/', function () {
    logger('Hitting home route...');
    return Inertia::render('Home');
});

Route::post('/chat', [ChatController::class, 'handle']);
Route::get('/agents', [AgentController::class, 'index']);
Route::post('/agent', [AgentController::class, 'create']);

require __DIR__.'/auth.php';
