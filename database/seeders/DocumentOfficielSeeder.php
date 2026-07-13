<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentOfficiel;

class DocumentOfficielSeeder extends Seeder
{
    public function run()
    {
        // ✅ Nettoyer la table avant d'insérer
        DocumentOfficiel::truncate();

        $documents = [
            [
                'type_piece' => 'Carte Nationale d\'Identité',
                'numero_document' => '123456789',
                'nom_complet' => 'emmanuel KATE',
                'nom' => 'KATE',
                'prenom' => 'emmanuel',
                'date_naissance' => '1985-06-15',
                'date_delivrance' => '2020-10-10',
                'date_expiration' => '2030-12-31',
                'autorite_delivrance' => 'Mairie de Lomé',
                'est_valide' => true,
                'est_volé' => false,
                'est_perdu' => false,
                'est_suspendu' => false,
            ],
            [
                'type_piece' => 'Carte Nationale d\'Identité',
                'numero_document' => '1234567890',
                'nom_complet' => 'reineKOGOE',
                'nom' => 'KOGOE',
                'prenom' => 'reine',
                'date_naissance' => '1990-03-22',
                'date_delivrance' => '2020-10-10',
                'date_expiration' => '2031-05-10',
                'autorite_delivrance' => 'Mairie de Lomé',
                'est_valide' => true,
                'est_volé' => false,
                'est_perdu' => false,
                'est_suspendu' => false,
            ],
            [
                'type_piece' => 'Passeport',
                'numero_document' => '1234567891',
                'nom_complet' => 'Pierre LOLO',
                'nom' => 'LOLO',
                'prenom' => 'Pierre',
                'date_naissance' => '1978-11-02',
                'date_delivrance' => '2020-10-10',
                'date_expiration' => '2027-08-20',
                'autorite_delivrance' => 'Mairie de Lomé',
                'est_valide' => true,
                'est_volé' => false,
                'est_perdu' => false,
                'est_suspendu' => false,
            ],
            [
                'type_piece' => 'Permis de conduire',
                'numero_document' => '1234567892',
                'nom_complet' => 'Pierre LOLO',
                'nom' => 'LOLO',
                'prenom' => 'Pierre',
                'date_naissance' => '1982-07-14',
                'date_delivrance' => '2020-10-10',
                'date_expiration' => '2026-11-15',
                'autorite_delivrance' => 'Mairie de Lomé',
                'est_valide' => true,
                'est_volé' => false,
                'est_perdu' => false,
                'est_suspendu' => false,
            ],
            [
                'type_piece' => 'Carte Nationale d\'Identité',
                'numero_document' => '1234567893',
                'nom_complet' => 'Kofi Aheto',
                'nom' => 'Aheto',
                'prenom' => 'Kofi',
                'date_naissance' => '1995-09-28',
                'date_delivrance' => '2023-03-01',
                'date_expiration' => '2033-03-01',
                'autorite_delivrance' => 'Mairie de Kara',
                'est_valide' => true,
                'est_volé' => false,
                'est_perdu' => false,
                'est_suspendu' => false,
            ],
        ];

        foreach ($documents as $doc) {
            DocumentOfficiel::create($doc);
        }

        $this->command->info('✅ ' . count($documents) . ' documents officiels insérés !');
    }
}