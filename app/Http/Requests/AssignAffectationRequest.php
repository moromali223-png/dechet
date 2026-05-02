<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignAffectationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'agent_id' => ['required', 'exists:users,id'],
            'collecteur_id' => ['required', 'exists:collecteurs,id'],
            'ordre_passage' => ['nullable', 'integer', 'min:1'],
            'duree_estimee' => ['nullable', 'integer', 'min:1'],
            'priorite' => ['nullable', 'integer', 'between:1,5'],
        ];
    }
}
