<?php

namespace App\Http\Requests;

use App\Models\Abonnement;
use Illuminate\Foundation\Http\FormRequest;

class StoreAbonnementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $rules = [
            'type_abonnement' => ['required', 'string', 'max:255'],
            'type_dechet' => ['required', 'string', 'max:255'],
            'frequence' => ['required', 'in:'.implode(',', Abonnement::FREQUENCIES)],
            'jour_collecte' => ['required', 'string', 'max:255'],
            'poids_estime' => ['required', 'numeric', 'min:0'],
            'montant' => ['nullable', 'numeric', 'min:0'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'],
            // Champs d'adresse
            'rue' => ['nullable', 'string', 'max:255'],
            'quartier' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:255'],
            'repere' => ['nullable', 'string', 'max:500'],
        ];

        if (auth()->user()->role === 'admin') {
            $rules['client_id'] = ['required', 'exists:clients,id'];
        }

        return $rules;
    }

    public function prepareForValidation(): void
    {
        if ($this->filled('jour_collecte')) {
            $this->merge([
                'jour_collecte' => mb_strtolower(trim($this->input('jour_collecte'))),
            ]);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if (! $this->filled('frequence') || ! $this->filled('jour_collecte')) {
                return;
            }

            if (! $this->isValidJourCollecte($this->input('jour_collecte'), $this->input('frequence'))) {
                $validator->errors()->add(
                    'jour_collecte',
                    'Le jour de collecte n’est pas valide pour la fréquence choisie.'
                );
            }
        });
    }

    private function isValidJourCollecte(string $jourCollecte, string $frequence): bool
    {
        if ($frequence === 'hebdomadaire') {
            return in_array(mb_strtolower($jourCollecte), Abonnement::WEEK_DAYS, true);
        }

        if ($frequence === 'mensuelle') {
            return ctype_digit($jourCollecte) && (int) $jourCollecte >= 1 && (int) $jourCollecte <= 28;
        }

        return false;
    }
}
