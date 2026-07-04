<?php

namespace App\Http\Requests;

use App\Models\Planification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlanificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $planificationId = $this->route('planification')->id ?? null;

        return [
            'code_planification' => ['required', 'string', 'max:255', Rule::unique('planifications', 'code_planification')->ignore($planificationId)],
            'nom_tournee' => ['nullable', 'string', 'max:255'],
            'jour_semaine' => ['nullable', 'string', 'max:100'],
            'date_prevue' => ['nullable', 'date'],
            'periode' => ['required', 'string', 'max:100'],
            'type_collecte' => ['required', 'string', 'max:255'],
            'statut' => ['required', Rule::in(Planification::STATUSES)],
            'zone_id' => ['required', 'exists:zones,id'],
            'collecteur_id' => ['nullable', 'exists:users,id'],
            'declaration_id' => ['nullable', 'exists:declarations,id'],
            'abonnement_id' => ['nullable', 'exists:abonnements,id'],
            'agent_id' => ['nullable', 'exists:users,id'],
            'ordre_passage' => ['nullable', 'integer', 'min:1'],
            'duree_estimee' => ['nullable', 'integer', 'min:1'],
            'priorite' => ['nullable', 'integer', 'between:1,5'],
        ];
    }
}
