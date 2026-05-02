<?php

namespace App\Http\Requests;

use App\Models\Planification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlanificationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'statut' => ['required', Rule::in(Planification::STATUSES)],
        ];
    }
}
