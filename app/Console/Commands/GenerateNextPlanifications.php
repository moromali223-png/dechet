<?php

namespace App\Console\Commands;

use App\Models\Abonnement;
use App\Services\PlanificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateNextPlanifications extends Command
{
    protected $signature = 'eco:generate-next-planifications';
    protected $description = 'Génère automatiquement les prochaines planifications pour les abonnements actifs';

    public function handle(PlanificationService $service)
{
    $this->info('🚀 Génération des prochaines planifications...');

    $abonnements = Abonnement::active()->with(['planifications'])->get();
    $created = 0;

    foreach ($abonnements as $abonnement) {
        if ($service->shouldGenerateNextPlanification($abonnement)) {
            DB::transaction(function () use ($service, $abonnement, &$created) {
                $service->createNextPlanification($abonnement);
                $created++;
            });
            $this->info("✓ Abonnement #{$abonnement->id} → Planification créée");
        }
    }

    $this->info("✅ {$created} nouvelles planifications créées.");
}
}