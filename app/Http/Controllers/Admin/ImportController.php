<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentOfficiel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    /**
     * Importer un fichier CSV
     */
    public function importerCSV(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120'
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        // Lire les en-têtes
        $headers = fgetcsv($handle, 1000, ';');
        
        // Si le séparateur est une virgule, réessayer
        if (count($headers) < 3) {
            rewind($handle);
            $headers = fgetcsv($handle, 1000, ',');
        }

        $importes = 0;
        $erreurs = [];
        $ligne = 1;

        while (($row = fgetcsv($handle, 1000, $headers[0] ? ';' : ',')) !== false) {
            $ligne++;
            
            // Associer les colonnes
            $data = [];
            foreach ($headers as $index => $header) {
                $data[trim($header)] = $row[$index] ?? null;
            }

            try {
                // Vérifier si le numéro existe déjà
                if (DocumentOfficiel::where('numero_document', $data['numero_document'] ?? '')->exists()) {
                    $erreurs[] = "Ligne {$ligne} : Numéro de document déjà existant.";
                    continue;
                }

                // Construire le nom complet
                $nomComplet = trim(($data['nom'] ?? '') . ' ' . ($data['prenom'] ?? ''));
                if (empty($nomComplet) && isset($data['nom_complet'])) {
                    $nomComplet = $data['nom_complet'];
                }

                DocumentOfficiel::create([
                    'type_piece' => $data['type_piece'] ?? $data['type_document'] ?? 'CNI',
                    'numero_document' => $data['numero_document'] ?? '',
                    'nom_complet' => $nomComplet,
                    'nom' => $data['nom'] ?? null,
                    'prenom' => $data['prenom'] ?? null,
                    'date_naissance' => $this->parseDate($data['date_naissance'] ?? null),
                    'lieu_naissance' => $data['lieu_naissance'] ?? null,
                    'date_delivrance' => $this->parseDate($data['date_delivrance'] ?? null),
                    'date_expiration' => $this->parseDate($data['date_expiration'] ?? null),
                    'autorite_delivrance' => $data['autorite_delivrance'] ?? null,
                    'lieu_delivrance' => $data['lieu_delivrance'] ?? null,
                    'est_valide' => $this->parseBoolean($data['est_valide'] ?? true),
                    'est_volé' => $this->parseBoolean($data['est_vole'] ?? false),
                    'est_perdu' => $this->parseBoolean($data['est_perdu'] ?? false),
                    'est_suspendu' => $this->parseBoolean($data['est_suspendu'] ?? false),
                    'remarques' => $data['remarques'] ?? null,
                    'source' => 'csv_import',
                    'derniere_verification' => now(),
                ]);
                
                $importes++;

            } catch (\Exception $e) {
                $erreurs[] = "Ligne {$ligne} : " . $e->getMessage();
            }
        }

        fclose($handle);

        $message = "✅ {$importes} document(s) importé(s) avec succès !";
        if (count($erreurs) > 0) {
            $message .= " ⚠️ " . count($erreurs) . " erreur(s) : " . implode('; ', $erreurs);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Télécharger le modèle CSV
     */
    public function telechargerModele()
    {
        $headers = [
            'type_piece', 'numero_document', 'nom', 'prenom', 
            'date_naissance', 'lieu_naissance', 'date_delivrance', 
            'date_expiration', 'autorite_delivrance', 'lieu_delivrance',
            'est_valide', 'est_vole', 'est_perdu', 'est_suspendu', 'remarques'
        ];

        $filename = 'modele_documents_officiels.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, $headers, ';');

        // Données d'exemple
        fputcsv($output, [
            'CNI', 'CNI-2024-0001', 'Dupont', 'Jean', 
            '1985-06-15', 'Lomé', '2020-01-15', 
            '2030-12-31', 'Mairie de Lomé', 'Lomé',
            '1', '0', '0', '0', 'Document valide'
        ], ';');

        fputcsv($output, [
            'Passeport', 'PAS-2024-0002', 'Koffi', 'Marie', 
            '1990-03-22', 'Atakpamé', '2022-05-10', 
            '2027-05-10', 'Ministère des Affaires Étrangères', 'Lomé',
            '1', '0', '0', '0', 'Passeport valide'
        ], ';');

        fputcsv($output, [
            'Permis', 'PER-2024-0003', 'Mensah', 'Pierre', 
            '1978-11-02', 'Kara', '2021-08-20', 
            '2026-08-20', 'Direction des Transports', 'Kara',
            '1', '0', '0', '0', 'Permis de conduire valide'
        ], ';');

        fclose($output);
        exit;
    }

    private function parseDate($value)
    {
        if (empty($value)) return null;
        try {
            return \Carbon\Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseBoolean($value)
    {
        if (is_bool($value)) return $value;
        if (is_numeric($value)) return (bool) $value;
        if (is_string($value)) {
            $value = strtolower(trim($value));
            return in_array($value, ['1', 'true', 'oui', 'o', 'yes', 'y', 'vrai', 'valide', 'ok']);
        }
        return (bool) $value;
    }
}