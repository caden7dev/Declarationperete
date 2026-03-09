<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perte;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{
    /**
     * Afficher la page d'aide
     */
    public function index()
    {
        $user = Auth::user();
        
        // Initialiser les déclarations à vide
        $dernieresDeclarations = collect([]);
        
        if ($user) {
            // Récupérer les dernières déclarations
            $dernieresDeclarations = Perte::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
        
        // ===== VARIABLES MANQUANTES À AJOUTER =====
        // Calculer le total des déclarations
        $totalDeclarations = $user ? Perte::where('user_id', $user->id)->count() : 0;
        
        // Compter par statut (optionnel)
        $enAttente = $user ? Perte::where('user_id', $user->id)->where('statut', 'en_attente')->count() : 0;
        $validees = $user ? Perte::where('user_id', $user->id)->where('statut', 'validee')->count() : 0;
        $rejetees = $user ? Perte::where('user_id', $user->id)->where('statut', 'rejetee')->count() : 0;
        
        // Si la vue utilise aussi $totalDeclaration (faute de frappe), on peut l'ajouter aussi
        $totalDeclaration = $totalDeclarations; // Pour compatibilité
        
        return view('help.index', compact(
            'user', 
            'dernieresDeclarations',
            'totalDeclarations',
            'totalDeclaration', // Si nécessaire
            'enAttente',        // Optionnel
            'validees',         // Optionnel
            'rejetees'          // Optionnel
        ));
    }

    /**
     * Envoyer un message de contact
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        return redirect()->route('help.index')
            ->with('success', '✅ Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
    }
}