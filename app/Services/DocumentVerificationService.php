<?php

namespace App\Services;

use App\Models\DocumentOfficiel;

class DocumentVerificationService
{
    /**
     * Vérifier si un document existe dans la base officielle
     */
    public function verifierDocument($typePiece, $numeroDocument)
    {
        // Si pas de numéro, on ne peut pas vérifier
        if (empty($numeroDocument)) {
            return [
                'valide' => false,
                'message' => 'Numéro de document non renseigné, impossible de vérifier.'
            ];
        }
        
        // 📌 RECHERCHE DANS LA BASE LOCALE
        $document = DocumentOfficiel::where('numero_document', $numeroDocument)
            ->where('type_piece', $typePiece)
            ->where('est_valide', true)
            ->first();
        
        if ($document) {
            return [
                'valide' => true,
                'nom' => $document->nom_complet,
                'date_delivrance' => $document->date_delivrance,
                'date_expiration' => $document->date_expiration,
                'autorite' => $document->autorite_delivrance,
                'source' => 'base_locale'
            ];
        }
        
        // 📌 VÉRIFICATION PAR API (si configurée)
        // return $this->verifierParAPI($typePiece, $numeroDocument);
        
        // 📌 SIMULATION POUR LA DÉMO
        return $this->simulerVerification($typePiece, $numeroDocument);
    }
    
    /**
     * Simulation de vérification pour la démo
     */
    private function simulerVerification($typePiece, $numeroDocument)
    {
        // Documents valides prédéfinis pour les tests
        $documentsValides = [
            'CNI-2024-0001' => ['nom' => 'Jean Dupont', 'date_expiration' => '2030-12-31'],
            'CNI-2024-0002' => ['nom' => 'Marie Koffi', 'date_expiration' => '2029-06-15'],
            'CNI-2025-0001' => ['nom' => 'Pierre Mensah', 'date_expiration' => '2031-01-01'],
            'PAS-2024-0001' => ['nom' => 'Amélie Togola', 'date_expiration' => '2029-08-20'],
            'PER-2024-0001' => ['nom' => 'Kofi Aheto', 'date_expiration' => '2028-11-30'],
            'PER-2025-0001' => ['nom' => 'Yao Adjei', 'date_expiration' => '2030-03-15'],
        ];
        
        // Vérifier si le document est dans la liste des valides
        if (isset($documentsValides[$numeroDocument])) {
            $info = $documentsValides[$numeroDocument];
            return [
                'valide' => true,
                'nom' => $info['nom'],
                'date_expiration' => $info['date_expiration'],
                'source' => 'simulation',
                'message' => 'Document trouvé dans la base de test.'
            ];
        }
        
        // 80% de chance d'être valide (pour la démo)
        $estValide = rand(1, 100) > 20;
        
        return [
            'valide' => $estValide,
            'nom' => $estValide ? 'Propriétaire vérifié' : null,
            'source' => 'simulation',
            'message' => $estValide 
                ? 'Document valide (simulation).' 
                : 'Document non trouvé dans la base de test. Vérifiez le numéro.'
        ];
    }
    
    /**
     * Vérification par API réelle (à implémenter plus tard)
     */
    private function verifierParAPI($typePiece, $numeroDocument)
    {
        // TODO: Appel API vers le service officiel
        // return [
        //     'valide' => $response->valid,
        //     'nom' => $response->nom_complet,
        //     'date_expiration' => $response->date_expiration,
        //     'source' => 'api_officielle'
        // ];
        
        // Pour l'instant, on utilise la simulation
        return $this->simulerVerification($typePiece, $numeroDocument);
    }
}