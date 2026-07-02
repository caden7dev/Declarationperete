<?php

namespace App\Services;

use App\Models\Perte;
use App\Models\DocumentTrouve;

class MatchScoreService
{
    public function calculate(Perte $perte, DocumentTrouve $document): int
    {
        $score = 0;

        // 1. Type identique : 30 points
        if ($document->type_document === $perte->type_piece) {
            $score += 30;
        }

        // 2. Numéro de pièce (similarité)
        if ($perte->numero_piece && $document->numero_document) {
            similar_text($perte->numero_piece, $document->numero_document, $percent);
            if ($percent > 80) {
                $score += 40;
            } elseif ($percent > 50) {
                $score += 20;
            }
        }

        // 3. Nom de famille
        if ($perte->last_name && $document->nom_sur_document) {
            similar_text(strtoupper($perte->last_name), strtoupper($document->nom_sur_document), $percent);
            if ($percent > 80) {
                $score += 30;
            } elseif ($percent > 50) {
                $score += 15;
            }
        }

        // 4. Prénom (bonus)
        if ($perte->first_name && $document->prenom_sur_document) {
            similar_text(strtoupper($perte->first_name), strtoupper($document->prenom_sur_document), $percent);
            if ($percent > 80) {
                $score += 10;
            } elseif ($percent > 50) {
                $score += 5;
            }
        }

        return min($score, 100);
    }

    public function getLevel(int $score): string
    {
        if ($score >= 70) return 'high';
        if ($score >= 40) return 'medium';
        return 'low';
    }

    public function getLabel(string $level): string
    {
        return [
            'high' => 'Élevée',
            'medium' => 'Moyenne',
            'low' => 'Faible',
        ][$level] ?? 'Inconnue';
    }
}