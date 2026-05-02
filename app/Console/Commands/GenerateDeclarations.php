<?php

namespace App\Console\Commands;

use App\Models\Abonnement;
use App\Models\Declaration;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GenerateDeclarations extends Command
{
    protected $signature = 'abonnements:generate-declarations';

    protected $description = 'Génère automatiquement les déclarations récurrentes depuis les abonnements actifs.';

    public function handle(): int
    {
        $today = Carbon::today();
        $abonnements = Abonnement::active()->with('user')->get();

        $generated = 0;

        foreach ($abonnements as $abonnement) {
            if (! $abonnement->isDueOn($today)) {
                continue;
            }

            $alreadyCreated = Declaration::where('abonnement_id', $abonnement->id)
                ->whereDate('created_at', $today)
                ->exists();

            if ($alreadyCreated) {
                continue;
            }

            Declaration::create([
                'type_dechet' => $abonnement->type_dechet,
                'poids_estime' => $abonnement->poids_estime,
                'photo' => null,
                'description' => sprintf('Déclaration générée automatiquement par abonnement %s.', $abonnement->id),
                'statut' => 'planifiee',
                'user_id' => $abonnement->user_id,
                'abonnement_id' => $abonnement->id,
            ]);

            $generated++;
        }

        $this->info("{$generated} déclarations générées pour la journée.");

        return self::SUCCESS;
    }
}
