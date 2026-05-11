<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectAbonnementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'motif_rejet' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'motif_rejet.required' => 'Le motif de rejet est obligatoire.',
            'motif_rejet.min' => 'Le motif de rejet doit contenir au moins :min caractères.',
            'motif_rejet.max' => 'Le motif de rejet ne peut pas dépasser :max caractères.',
        ];
    }
}
