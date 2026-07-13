<?php

namespace App\Http\Requests;

use App\Models\Abonnement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
        

'frequence' => [
    'required',
    Rule::in(array_keys(Abonnement::FREQUENCIES)),
],

        'poids_estime' => ['required', 'numeric', 'min:0'],
        'montant' => ['nullable', 'numeric', 'min:0'],

        'date_debut' => ['required', 'date'],
        'date_fin' => ['required', 'date', 'after_or_equal:date_debut'],

        // Adresse
        'rue' => ['nullable', 'string', 'max:255'],
        'quartier' => ['nullable', 'string', 'max:255'],
        'porte' => ['nullable', 'string', 'max:255'],
        'repere' => ['nullable', 'string', 'max:500'],
    ];


    if ($this->input('frequence') === 'hebdomadaire') {

        $rules['jour_collecte'] = [
            'required',
            'in:' . implode(',', Abonnement::WEEK_DAYS)
        ];

    }


    if ($this->input('frequence') === 'mensuelle') {

        $rules['jour_collecte'] = [
            'required',
            'integer',
            'between:1,28'
        ];

    }


    if (auth()->user()->role === 'admin') {
        $rules['client_id'] = [
            'required',
            'exists:users,id'
        ];
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
            $frequence = $this->input('frequence');
            $jour      = $this->input('jour_collecte');

            // Si la fréquence ou le jour n'est pas présent → on arrête
            if (!$frequence || !$jour) {
                $validator->errors()->add('jour_collecte', 'Le jour de collecte est obligatoire.');
                return;
            }

            if (!$this->isValidJourCollecte($jour, $frequence)) {
                $message = $frequence === 'mensuelle'
                    ? 'Pour une fréquence mensuelle, veuillez entrer un jour du mois entre 1 et 28.'
                    : 'Veuillez choisir un jour valide de la semaine.';

                $validator->errors()->add('jour_collecte', $message);
            }
        });
    }

    private function isValidJourCollecte(string $jourCollecte, string $frequence): bool
    {
        if ($frequence === 'hebdomadaire') {
            return in_array(mb_strtolower($jourCollecte), Abonnement::WEEK_DAYS, true);
        }

        if ($frequence === 'mensuelle') {
            return ctype_digit($jourCollecte) 
                && (int) $jourCollecte >= 1 
                && (int) $jourCollecte <= 28;
        }

        return false;
    }
}