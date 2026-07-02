<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Perte;
use App\Models\TypePiece;
use App\Models\DocumentTrouve;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerteController extends Controller
{
    /**
     * Afficher la liste des déclarations de l'utilisateur.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Perte::where('user_id', $user->id);
        
        // Filtre par statut (intègre les nouveaux statuts)
        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }
        
        // Recherche
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_piece', 'LIKE', "%{$search}%")
                  ->orWhere('lieu_perte', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('type_piece', 'LIKE', "%{$search}%")
                  ->orWhere('numero_declaration', 'LIKE', "%{$search}%");
            });
        }
        
        $pertes = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Statistiques enrichies
        $totalDeclarations = Perte::where('user_id', $user->id)->count();
        $enAttenteCount = Perte::where('user_id', $user->id)->where('statut', 'en_attente')->count();
        $enCoursCount = Perte::where('user_id', $user->id)->where('statut', 'en_cours')->count();
        $correspondanceCount = Perte::where('user_id', $user->id)->where('statut', 'correspondance_trouvee')->count();
        $restitueCount = Perte::where('user_id', $user->id)->where('statut', 'restitue')->count();
        $nonRetrouveCount = Perte::where('user_id', $user->id)->where('statut', 'non_retrouve')->count();
        $valideeCount = Perte::where('user_id', $user->id)->where('statut', 'validee')->count();
        $rejeteeCount = Perte::where('user_id', $user->id)->where('statut', 'rejetee')->count();
        
        return view('perte.index', compact(
            'pertes',
            'totalDeclarations',
            'enAttenteCount',
            'enCoursCount',
            'correspondanceCount',
            'restitueCount',
            'nonRetrouveCount',
            'valideeCount',
            'rejeteeCount'
        ));
    }

    /**
     * Afficher le formulaire de création d'une déclaration.
     * Si un paramètre 'copy_from' est fourni, pré‑remplit les champs
     * avec les données de l'ancienne déclaration (pour les cas non retrouvés).
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $typesPieces = TypePiece::all();
        $oldPerte = null;
        
        if ($request->has('copy_from')) {
            $oldPerte = Perte::where('user_id', $user->id)
                             ->where('statut', 'non_retrouve')
                             ->find($request->copy_from);
        }
        
        return view('perte.create', compact('user', 'typesPieces', 'oldPerte'));
    }

    /**
     * Télécharger le récépissé de non‑retrouvé.
     */
    public function downloadRecu($id)
    {
        $perte = Perte::findOrFail($id);
        // Vérifier que l'utilisateur est bien le déclarant ou un agent
        if (auth()->id() != $perte->user_id && !auth()->user()->isAgent()) {
            abort(403);
        }
        if (!$perte->pdf_recu || !Storage::disk('public')->exists($perte->pdf_recu)) {
            abort(404, 'Récépissé non disponible.');
        }
        return Storage::disk('public')->download($perte->pdf_recu, 'recu_perte_'.$perte->id.'.pdf');
    }

    /**
     * Afficher la page de suivi d'une déclaration pour le citoyen.
     */
    public function suivi($id)
    {
        $perte = Perte::with('user')->findOrFail($id);
        if (auth()->id() != $perte->user_id) {
            abort(403);
        }
        
        // Récupérer les notifications liées à cette déclaration
        $notifications = Notification::where('perte_id', $perte->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('citizen.suivi', compact('perte', 'notifications'));
    }

    /**
     * Le citoyen signale qu'il a récupéré son document (pour la démo).
     * Envoie une notification à tous les agents.
     */
    public function signalerRecuperation($perteId)
    {
        $perte = Perte::where('user_id', auth()->id())->findOrFail($perteId);
        
        if ($perte->statut !== Perte::STATUT_PRET_RECUPERATION) {
            return back()->with('error', 'Ce document n\'est pas encore prêt à être récupéré.');
        }

        // Notifier tous les agents (ou un agent spécifique selon votre besoin)
        $agents = User::where('role', 'agent')->get();
        foreach ($agents as $agent) {
            Notification::createSystemNotification(
                $agent,
                '📢 Le citoyen a récupéré son document',
                "Le citoyen {$perte->user->name} a signalé avoir récupéré son document ({$perte->type_piece}). Veuillez valider la restitution.",
                route('agent.perte.show', $perte->id),
                $perte,
                '📥',
                'info'
            );
        }

        return back()->with('success', '✅ Vous avez signalé la récupération. L’agent en a été informé.');
    }

    /**
     * Enregistrer une nouvelle déclaration.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name'      => 'required|string|max:255',
            'first_name'     => 'required|string|max:255',
            'contact'        => 'required|string|max:30',
            'email'          => 'required|email|max:255',
            'type_piece'     => 'required|string|max:100',
            'numero_piece'   => 'nullable|string|max:100',
            'date_delivrance'=> 'nullable|date',
            'autorite_delivrance' => 'nullable|string|max:255',
            'date_perte'     => 'required|date|before_or_equal:today',
            'lieu_perte'     => 'required|string|max:255',
            'circonstances'  => 'nullable|string',
            'copie_piece'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'declaration_vol'=> 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_complementaire' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'last_name.required' => 'Le nom est obligatoire.',
            'first_name.required' => 'Le prénom est obligatoire.',
            'contact.required' => 'Le numéro de téléphone est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'type_piece.required' => 'Le type de pièce est obligatoire.',
            'date_perte.required' => 'La date de perte est obligatoire.',
            'date_perte.before_or_equal' => 'La date de perte ne peut pas être dans le futur.',
            'lieu_perte.required' => 'Le lieu de perte est obligatoire.',
        ]);
        
        $validated['user_id'] = Auth::id();
        $validated['statut'] = Perte::STATUT_EN_ATTENTE;
        $validated['date_declaration'] = now();
        
        // Upload des fichiers
        foreach (['copie_piece', 'declaration_vol', 'document_complementaire'] as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store("pertes/{$field}", 'public');
            }
        }
        
        $perte = Perte::create($validated);
        
        return redirect()->route('perte.index')->with('success', '✅ Déclaration soumise avec succès ! Votre numéro de déclaration est : ' . $perte->numero_declaration);
    }

    /**
     * Afficher les détails d'une déclaration (pour le citoyen).
     * Enrichi avec les informations de correspondance si un document trouvé est associé.
     */
    public function show($id)
    {
        $perte = Perte::where('user_id', Auth::id())->findOrFail($id);
        $perte->load('documentTrouve');
        return view('perte.show', compact('perte'));
    }

    /**
     * Afficher le formulaire d'édition (uniquement si le statut est 'en_attente').
     */
    public function edit($id)
    {
        $perte = Perte::where('user_id', Auth::id())
                      ->where('statut', Perte::STATUT_EN_ATTENTE)
                      ->findOrFail($id);
        
        $typesPieces = TypePiece::all();
        
        return view('perte.edit', compact('perte', 'typesPieces'));
    }

    /**
     * Mettre à jour une déclaration (uniquement si 'en_attente').
     */
    public function update(Request $request, $id)
    {
        $perte = Perte::where('user_id', Auth::id())
                      ->where('statut', Perte::STATUT_EN_ATTENTE)
                      ->findOrFail($id);
        
        $validated = $request->validate([
            'last_name'      => 'required|string|max:255',
            'first_name'     => 'required|string|max:255',
            'contact'        => 'required|string|max:30',
            'email'          => 'required|email|max:255',
            'type_piece'     => 'required|string|max:100',
            'numero_piece'   => 'nullable|string|max:100',
            'date_delivrance'=> 'nullable|date',
            'autorite_delivrance' => 'nullable|string|max:255',
            'date_perte'     => 'required|date|before_or_equal:today',
            'lieu_perte'     => 'required|string|max:255',
            'circonstances'  => 'nullable|string',
            'copie_piece'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'declaration_vol'=> 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_complementaire' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        
        // Gestion des fichiers (remplacement)
        foreach (['copie_piece', 'declaration_vol', 'document_complementaire'] as $field) {
            if ($request->hasFile($field)) {
                if ($perte->$field) {
                    Storage::disk('public')->delete($perte->$field);
                }
                $validated[$field] = $request->file($field)->store("pertes/{$field}", 'public');
            }
        }
        
        $perte->update($validated);
        
        return redirect()->route('perte.index')->with('success', '✅ Déclaration mise à jour avec succès.');
    }

    /**
     * Supprimer une déclaration (uniquement si 'en_attente').
     */
    public function destroy($id)
    {
        $perte = Perte::where('user_id', Auth::id())
                      ->where('statut', Perte::STATUT_EN_ATTENTE)
                      ->findOrFail($id);
        
        // Supprimer les fichiers associés
        foreach (['copie_piece', 'declaration_vol', 'document_complementaire'] as $field) {
            if ($perte->$field) {
                Storage::disk('public')->delete($perte->$field);
            }
        }
        
        $perte->delete();
        
        return redirect()->route('perte.index')->with('success', '✅ Déclaration supprimée avec succès.');
    }

    /**
     * Télécharger l'attestation de déclaration (PDF) – à implémenter selon vos besoins.
     */
    public function download($id)
    {
        $perte = Perte::where('user_id', Auth::id())->findOrFail($id);
        // Génération du PDF
        // ...
        return response()->download(storage_path('app/attestations/declaration_' . $perte->id . '.pdf'));
    }
}