<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Perte;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class MarquerPretRecuperationAuto extends Command
{
    protected $signature = 'pertes:marquer-pret';
    protected $description = 'Marque automatiquement les documents comme prêts après le délai défini';

    public function handle()
    {
        // Récupérer le délai (priorité à .env, sinon config/delais.php, sinon 7 jours)
        $delaiJours = env('DELAI_RENOUVELLEMENT_JOURS');
        if ($delaiJours === null) {
            $delais = config('delais.délais', ['default' => 7]);
            $delaiJours = $delais['default'] ?? 7;
        } else {
            $delaiJours = (float) $delaiJours;
        }

        $pertes = Perte::where('statut', Perte::STATUT_NON_RETROUVE)
            ->whereNotNull('date_passage_non_retrouve')
            ->get();

        foreach ($pertes as $perte) {
            // S'assurer que date_passage_non_retrouve est un objet Carbon
            $datePassage = $perte->date_passage_non_retrouve;
            if (!$datePassage) {
                continue;
            }
            if (is_string($datePassage)) {
                $datePassage = Carbon::parse($datePassage);
            }
            $dateLimite = $datePassage->copy()->addDays($delaiJours);

            if (now()->greaterThanOrEqualTo($dateLimite)) {
                // 1. Mettre à jour la déclaration
                $perte->statut = Perte::STATUT_PRET_RECUPERATION;
                $perte->lieu_recuperation = 'Commissariat de Lomé – Bureau central (automatique)';
                $perte->date_preparation = now();
                $perte->save();

                // 2. Notifier le citoyen
                Notification::createSystemNotification(
                    $perte->user,
                    'Votre nouveau document est prêt',
                    "Votre document ({$perte->type_piece}) est disponible. Rendez-vous à : {$perte->lieu_recuperation}.",
                    route('perte.suivi', $perte->id),
                    $perte,
                    '📍',
                    'success'
                );

                // 3. Notifier tous les agents
                $agents = User::where('role', 'agent')->get();
                foreach ($agents as $agent) {
                    Notification::createSystemNotification(
                        $agent,
                        '📢 Document prêt à récupérer (automatique)',
                        "Le document ({$perte->type_piece}) du citoyen {$perte->user->name} est prêt. Veuillez valider la restitution.",
                        route('agent.perte.show', $perte->id),
                        $perte,
                        '📌',
                        'info'
                    );
                }

                $this->info("✅ Déclaration #{$perte->id} marquée comme prête. Notifications envoyées.");
            }
        }

        $this->info('Traitement terminé.');
    }
}