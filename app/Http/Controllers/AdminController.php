<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Perte;
use App\Models\TypePiece;

class AdminController extends Controller
{
    /**
     * Tableau de bord Administrateur Général
     */
    public function index()
    {
        // Vérifier si la table pertes existe et a des données
        try {
            $pertesCount = Perte::count();
            $cniCount = Perte::where('type_piece', 'CNI')->count();
            $passeportCount = Perte::where('type_piece', 'Passeport')->count();
            $acteCount = Perte::where('type_piece', 'Acte')->count();
        } catch (\Exception $e) {
            // Si erreur, mettre des valeurs par défaut
            $pertesCount = 0;
            $cniCount = 0;
            $passeportCount = 0;
            $acteCount = 0;
        }

        return view('admin.dashboard', [
            // 🔢 Statistiques globales
            'stats' => [
                'users' => User::count(),
                'types_pieces' => TypePiece::count(),
                'roles' => 3, // admin | agent | citoyen
                'pertes' => $pertesCount,
            ],

            // 👥 Derniers utilisateurs
            'users' => User::latest()->limit(5)->get(),

            // 🪪 Types de pièces
            'typesPieces' => TypePiece::all(),

            // 📊 Données statistiques (exemple)
            'chart' => [
                'labels' => ['CNI', 'Passeport', 'Acte de naissance'],
                'data' => [$cniCount, $passeportCount, $acteCount]
            ]
        ]);
    }
}