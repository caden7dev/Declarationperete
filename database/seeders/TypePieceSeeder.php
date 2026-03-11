<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypePiece;

class TypePieceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typesPieces = [
            [
                'nom' => 'Carte d\'identité nationale',
                'code' => 'CIN',
                'categorie' => 'Identité',
                'description' => 'Carte d\'identité nationale togolaise pour les citoyens',
                'is_active' => true,
                'delai_traitement' => 15,
                'prix' => 5000,
                'documents_requis' => 'Acte de naissance, Photo d\'identité récente, Certificat de résidence',
            ],
            [
                'nom' => 'Passeport',
                'code' => 'PASS',
                'categorie' => 'Identité',
                'description' => 'Passeport ordinaire pour voyages internationaux',
                'is_active' => true,
                'delai_traitement' => 30,
                'prix' => 50000,
                'documents_requis' => 'Carte d\'identité, Acte de naissance, 4 photos, Certificat de nationalité',
            ],
            [
                'nom' => 'Permis de conduire',
                'code' => 'PC',
                'categorie' => 'Véhicule',
                'description' => 'Permis de conduire catégories A, B, C',
                'is_active' => true,
                'delai_traitement' => 45,
                'prix' => 35000,
                'documents_requis' => 'Carte d\'identité, Certificat médical, Attestation de formation, 4 photos',
            ],
            [
                'nom' => 'Carte grise',
                'code' => 'CG',
                'categorie' => 'Véhicule',
                'description' => 'Certificat d\'immatriculation pour véhicules',
                'is_active' => true,
                'delai_traitement' => 7,
                'prix' => 15000,
                'documents_requis' => 'Facture d\'achat, Carte d\'identité, Attestation d\'assurance',
            ],
            [
                'nom' => 'Extrait de naissance',
                'code' => 'EN',
                'categorie' => 'Identité',
                'description' => 'Copie intégrale de l\'acte de naissance',
                'is_active' => true,
                'delai_traitement' => 3,
                'prix' => 1000,
                'documents_requis' => 'Demande écrite, Carte d\'identité du demandeur',
            ],
            [
                'nom' => 'Diplôme BAC',
                'code' => 'BAC',
                'categorie' => 'Académique',
                'description' => 'Diplôme du baccalauréat',
                'is_active' => true,
                'delai_traitement' => 60,
                'prix' => 10000,
                'documents_requis' => 'Attestation de réussite, Bulletin de notes, Carte d\'identité',
            ],
            [
                'nom' => 'Attestation de travail',
                'code' => 'ATT',
                'categorie' => 'Professionnel',
                'description' => 'Attestation de travail pour employés',
                'is_active' => true,
                'delai_traitement' => 2,
                'prix' => 0,
                'documents_requis' => 'Demande écrite à l\'employeur',
            ],
            [
                'nom' => 'Carte de séjour',
                'code' => 'CS',
                'categorie' => 'Identité',
                'description' => 'Carte de séjour pour étrangers résidents',
                'is_active' => true,
                'delai_traitement' => 30,
                'prix' => 25000,
                'documents_requis' => 'Passeport, Contrat de travail ou attestation d\'hébergement, 4 photos',
            ],
            [
                'nom' => 'Certificat de vie',
                'code' => 'CV',
                'categorie' => 'Identité',
                'description' => 'Certificat attestant qu\'une personne est en vie',
                'is_active' => true,
                'delai_traitement' => 1,
                'prix' => 500,
                'documents_requis' => 'Carte d\'identité',
            ],
            [
                'nom' => 'Quitus fiscal',
                'code' => 'QF',
                'categorie' => 'Professionnel',
                'description' => 'Certificat de régularité fiscale',
                'is_active' => true,
                'delai_traitement' => 15,
                'prix' => 5000,
                'documents_requis' => 'IFU, Dernières déclarations fiscales, Carte d\'identité',
            ],
        ];

        foreach ($typesPieces as $type) {
            TypePiece::create($type);
        }
    }
}