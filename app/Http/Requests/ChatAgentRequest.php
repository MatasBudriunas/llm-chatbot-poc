<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatAgentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input' => 'required|string|max:250',
            'session_id' => 'required|string|uuid',
            'agent' => 'required|string|exists:agents,slug',
        ];
    }
}
