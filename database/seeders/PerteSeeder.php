<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perte;
use App\Models\User;
use App\Models\TypePiece;
use Carbon\Carbon;

class PerteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les utilisateurs et types de pièces existants
        $users = User::where('role', 'user')->get();
        $typesPieces = TypePiece::all();

        if ($users->isEmpty() || $typesPieces->isEmpty()) {
            $this->command->warn('Veuillez d\'abord créer des utilisateurs et types de pièces');
            return;
        }

        $statuts = ['en_attente', 'validee', 'rejetee'];
        $lieux = [
            'Marché de Lomé',
            'Gare routière',
            'Boulevard du 13 Janvier',
            'Avenue de la Libération',
            'Quartier Bè',
            'Quartier Nyékonakpoé',
            'Centre-ville',
            'Aéroport de Lomé',
            'Port autonome de Lomé',
            'Université de Lomé'
        ];

        $descriptions = [
            'Perte constatée lors d\'un déplacement',
            'Document égaré dans les transports en commun',
            'Vol à l\'arraché',
            'Perte lors d\'un déménagement',
            'Document laissé par inadvertance',
            'Perte suite à un accident',
            'Vol de sac contenant les documents',
            'Perte dans un lieu public',
            'Document détruit accidentellement',
            'Perte de cause inconnue'
        ];

        // Générer des déclarations pour les 12 derniers mois
        $this->command->info('Génération de 200 déclarations de perte...');

        for ($i = 0; $i < 200; $i++) {
            // Date aléatoire dans les 12 derniers mois
            $createdAt = Carbon::now()->subDays(rand(0, 365));
            
            // Date de perte entre 1 et 30 jours avant la déclaration
            $datePerte = $createdAt->copy()->subDays(rand(1, 30));

            $statut = $statuts[array_rand($statuts)];
            $validatedBy = null;
            $validatedAt = null;
            $motifRejet = null;

            // Si validée ou rejetée, ajouter les infos
            if ($statut !== 'en_attente') {
                $agents = User::where('role', 'agent')->get();
                if ($agents->isNotEmpty()) {
                    $validatedBy = $agents->random()->id;
                    $validatedAt = $createdAt->copy()->addDays(rand(1, 7));
                }

                if ($statut === 'rejetee') {
                    $motifs = [
                        'Informations insuffisantes',
                        'Document non conforme',
                        'Délai de déclaration dépassé',
                        'Pièce justificative manquante',
                        'Incohérence dans les informations fournies'
                    ];
                    $motifRejet = $motifs[array_rand($motifs)];
                }
            }

            Perte::create([
                'user_id' => $users->random()->id,
                'type_piece_id' => $typesPieces->random()->id,
                'numero_piece' => 'N°' . strtoupper(substr(md5(rand()), 0, 10)),
                'date_perte' => $datePerte,
                'lieu_perte' => $lieux[array_rand($lieux)],
                'description' => $descriptions[array_rand($descriptions)],
                'statut' => $statut,
                'motif_rejet' => $motifRejet,
                'numero_declaration' => null, // Sera généré automatiquement
                'validated_at' => $validatedAt,
                'validated_by' => $validatedBy,
                'created_at' => $createdAt,
                'updated_at' => $validatedAt ?? $createdAt,
            ]);

            if (($i + 1) % 50 == 0) {
                $this->command->info(($i + 1) . ' déclarations créées...');
            }
        }

        $this->command->info('✅ 200 déclarations de perte créées avec succès !');
    }
}