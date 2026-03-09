<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perte;
use App\Models\DocumentTrouve;
use App\Models\Notification;
use App\Models\TypePiece;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AgentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $agent = Auth::user();
        
        // Récupérer les paramètres de filtre
        $statut = $request->get('statut', 'all');
        $search = $request->get('search', '');
        
        // Construire la requête pour les pertes
        $query = Perte::with(['user', 'typePiece']);
        
        if ($statut && $statut != 'all') {
            $query->where('statut', $statut);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
                })
                ->orWhere('numero_declaration', 'LIKE', "%{$search}%")
                ->orWhere('type_piece', 'LIKE', "%{$search}%");
            });
        }
        
        $pertes = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Statistiques déclarations de perte
        $stats = [
            'total' => Perte::count(),
            'en_attente' => Perte::where('statut', 'en_attente')->count(),
            'validees' => Perte::where('statut', 'validee')->count(),
            'rejetees' => Perte::where('statut', 'rejetee')->count(),
            'traitees_par_moi' => Perte::where('validated_by', $agent->id)->count()
        ];

        // Statistiques documents trouvés
        $statsDocumentsTrouves = [
            'total' => DocumentTrouve::count(),
            'en_attente' => DocumentTrouve::where('statut', 'en_attente')->count(),
            'matches' => DocumentTrouve::where('statut', 'matche')->count(),
            'restitues' => DocumentTrouve::where('statut', 'restitue')->count(),
        ];

        // Derniers documents trouvés (5 derniers)
        $derniersTrouves = DocumentTrouve::with('perteMatchee')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('agent.dashboard', compact(
            'pertes', 
            'stats', 
            'statut', 
            'search',
            'statsDocumentsTrouves',
            'derniersTrouves'
        ));
    }

    /**
     * Valider une déclaration
     */
    public function valider($id)
    {
        $perte = Perte::findOrFail($id);
        $agent = Auth::user();
        
        if ($perte->statut !== 'en_attente') {
            return back()->with('error', 'Cette déclaration ne peut plus être validée.');
        }
        
        $perte->update([
            'statut' => 'validee',
            'validated_by' => $agent->id,
            'validated_at' => now()
        ]);
        
        // Générer le numéro de déclaration si pas encore fait
        if (!$perte->numero_declaration) {
            $perte->numero_declaration = 'DL-' . strtoupper(uniqid()) . '-' . date('Y');
            $perte->save();
        }

        // Vérifier s'il y a des documents trouvés correspondants
        $this->checkMatchingFoundDocuments($perte);
        
        // Créer la notification pour l'utilisateur
        Notification::create([
            'user_id' => $perte->user_id,
            'type' => 'validation',
            'title' => 'Déclaration validée',
            'message' => "Votre déclaration de perte a été validée. Numéro : {$perte->numero_declaration}",
            'perte_id' => $perte->id,
            'is_read' => false
        ]);
        
        return redirect()->route('agent.dashboard')
            ->with('success', '✅ Déclaration validée avec succès. Numéro généré : ' . $perte->numero_declaration);
    }

    /**
     * Vérifier les documents trouvés correspondants après validation
     */
    private function checkMatchingFoundDocuments($perte)
    {
        $documentsTrouves = DocumentTrouve::where('statut', 'en_attente')
            ->where('type_document', $perte->type_piece)
            ->get();

        foreach ($documentsTrouves as $docTrouve) {
            $isMatch = false;

            // Vérifier correspondances
            if ($docTrouve->numero_document && $perte->numero_piece) {
                if (strtolower($docTrouve->numero_document) == strtolower($perte->numero_piece)) {
                    $isMatch = true;
                }
            }

            if ($docTrouve->nom_sur_document && $perte->last_name) {
                if (stripos($perte->last_name, $docTrouve->nom_sur_document) !== false) {
                    $isMatch = true;
                }
            }

            if ($isMatch) {
                // Envoyer notification au propriétaire
                Notification::create([
                    'user_id' => $perte->user_id,
                    'type' => 'document_trouve',
                    'title' => '🎉 Votre document a peut-être été trouvé !',
                    'message' => "Un document correspondant à votre déclaration a été trouvé le " . 
                                 $docTrouve->date_decouverte->format('d/m/Y') . " à " . 
                                 $docTrouve->lieu_decouverte . ". Connectez-vous pour plus de détails.",
                    'perte_id' => $perte->id,
                    'is_read' => false
                ]);

                // Notification au trouveur
                Notification::create([
                    'user_id' => $docTrouve->user_id ?? null,
                    'type' => 'correspondance_trouvee',
                    'title' => '🔍 Correspondance trouvée !',
                    'message' => "Un document correspondant à celui que vous avez trouvé a été déclaré perdu. Un agent va traiter votre déclaration.",
                    'document_trouve_id' => $docTrouve->id,
                    'is_read' => false
                ]);
            }
        }
    }

    /**
     * Rejeter une déclaration avec motif
     */
    public function rejeter(Request $request, $id)
    {
        $request->validate([
            'motif_rejet' => 'required|string|min:10|max:500'
        ], [
            'motif_rejet.required' => 'Le motif du rejet est obligatoire.',
            'motif_rejet.min' => 'Le motif doit contenir au moins 10 caractères.',
            'motif_rejet.max' => 'Le motif ne peut pas dépasser 500 caractères.'
        ]);

        $perte = Perte::findOrFail($id);
        $agent = Auth::user();
        
        if ($perte->statut !== 'en_attente') {
            return back()->with('error', 'Cette déclaration ne peut plus être rejetée.');
        }
        
        $perte->update([
            'statut' => 'rejetee',
            'validated_by' => $agent->id,
            'validated_at' => now(),
            'motif_rejet' => $request->motif_rejet
        ]);
        
        // Créer la notification pour l'utilisateur
        Notification::create([
            'user_id' => $perte->user_id,
            'type' => 'rejet',
            'title' => 'Déclaration rejetée',
            'message' => "Votre déclaration a été rejetée. Motif : {$request->motif_rejet}",
            'perte_id' => $perte->id,
            'is_read' => false
        ]);
        
        return redirect()->route('agent.dashboard')
            ->with('success', '❌ Déclaration rejetée. Une notification a été envoyée au citoyen.');
    }

    /**
     * Voir les détails d'une déclaration
     */
    public function show($id)
    {
        $perte = Perte::with(['user', 'typePiece', 'validator'])->findOrFail($id);
        return view('agent.perte-show', compact('perte'));
    }

    /**
     * Voir la liste des documents trouvés
     */
    public function documentsTrouves(Request $request)
    {
        $statut = $request->get('statut', 'all');
        $search = $request->get('search', '');

        $query = DocumentTrouve::with('perteMatchee');

        if ($statut && $statut != 'all') {
            $query->where('statut', $statut);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('numero_declaration', 'LIKE', "%{$search}%")
                  ->orWhere('nom_declarant', 'LIKE', "%{$search}%")
                  ->orWhere('prenom_declarant', 'LIKE', "%{$search}%")
                  ->orWhere('nom_sur_document', 'LIKE', "%{$search}%")
                  ->orWhere('numero_document', 'LIKE', "%{$search}%");
            });
        }

        $documentsTrouves = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => DocumentTrouve::count(),
            'en_attente' => DocumentTrouve::where('statut', 'en_attente')->count(),
            'matches' => DocumentTrouve::where('statut', 'matche')->count(),
            'restitues' => DocumentTrouve::where('statut', 'restitue')->count(),
        ];

        return view('agent.documents-trouves.index', compact('documentsTrouves', 'stats', 'statut', 'search'));
    }

    /**
     * Voir les détails d'un document trouvé avec correspondances
     */
    public function showDocumentTrouve($id)
    {
        $documentTrouve = DocumentTrouve::with('perteMatchee')->findOrFail($id);
        
        // Chercher les correspondances possibles
        $correspondances = $this->findMatchingPertes($documentTrouve);

        return view('agent.documents-trouves.show', compact('documentTrouve', 'correspondances'));
    }

    /**
     * Trouver les déclarations de perte correspondantes
     */
    private function findMatchingPertes($documentTrouve)
    {
        $query = Perte::where('statut', 'validee')
            ->where('type_piece', $documentTrouve->type_document);

        // Filtrer par numéro si disponible
        if ($documentTrouve->numero_document) {
            $query->where('numero_piece', 'LIKE', '%' . $documentTrouve->numero_document . '%');
        }

        // Filtrer par nom/prénom si disponible
        if ($documentTrouve->nom_sur_document) {
            $query->where('last_name', 'LIKE', '%' . $documentTrouve->nom_sur_document . '%');
        }

        if ($documentTrouve->prenom_sur_document) {
            $query->where('first_name', 'LIKE', '%' . $documentTrouve->prenom_sur_document . '%');
        }

        // Filtrer par date (± 60 jours)
        $query->whereBetween('date_perte', [
            $documentTrouve->date_decouverte->subDays(60),
            $documentTrouve->date_decouverte->addDays(60)
        ]);

        return $query->with('user')->get();
    }

    /**
     * Matcher un document trouvé avec une perte
     */
    public function matcherDocumentTrouve(Request $request, $id)
    {
        $request->validate([
            'perte_id' => 'required|exists:pertes,id'
        ]);

        $documentTrouve = DocumentTrouve::findOrFail($id);
        $perteId = $request->input('perte_id');

        if (!$perteId) {
            return back()->with('error', 'Veuillez sélectionner une déclaration de perte.');
        }

        $perte = Perte::findOrFail($perteId);

        // Mettre à jour le document trouvé
        $documentTrouve->update([
            'perte_matchee_id' => $perteId,
            'statut' => 'matche'
        ]);

        // Mettre à jour la perte
        $perte->update([
            'document_retrouve' => true,
            'date_retrouvaille' => now(),
            'document_trouve_id' => $documentTrouve->id
        ]);

        // Envoyer notification au propriétaire
        Notification::create([
            'user_id' => $perte->user_id,
            'type' => 'document_retrouve',
            'title' => '🎉 Votre document a été retrouvé !',
            'message' => "Votre {$perte->type_piece} a été retrouvé ! Contact du trouveur : {$documentTrouve->nom_declarant} {$documentTrouve->prenom_declarant}",
            'perte_id' => $perte->id,
            'is_read' => false
        ]);

        // Envoyer notification au trouveur
        if ($documentTrouve->user_id) {
            Notification::create([
                'user_id' => $documentTrouve->user_id,
                'type' => 'document_matché',
                'title' => '✅ Document matché avec succès !',
                'message' => "Le document que vous avez trouvé correspond à une déclaration de perte. Un agent va vous contacter pour la restitution.",
                'document_trouve_id' => $documentTrouve->id,
                'is_read' => false
            ]);
        }

        return redirect()->route('agent.documents-trouves.show', $id)
            ->with('success', '✅ Document matché avec succès ! Les deux parties ont été notifiées.');
    }

    /**
     * Marquer un document comme restitué
     */
    public function marquerRestitue($id)
    {
        $documentTrouve = DocumentTrouve::findOrFail($id);

        if ($documentTrouve->statut !== 'matche') {
            return back()->with('error', 'Seuls les documents matchés peuvent être marqués comme restitués.');
        }

        $documentTrouve->update([
            'statut' => 'restitue',
            'date_restitution' => now()
        ]);

        // Notification au propriétaire
        if ($documentTrouve->perte_matchee_id) {
            $perte = $documentTrouve->perteMatchee;
            Notification::create([
                'user_id' => $perte->user_id,
                'type' => 'restitution',
                'title' => '✅ Document restitué',
                'message' => "Votre {$perte->type_piece} a été officiellement restitué. Merci d'utiliser notre plateforme !",
                'perte_id' => $perte->id,
                'is_read' => false
            ]);
        }

        // Notification au trouveur
        if ($documentTrouve->user_id) {
            Notification::create([
                'user_id' => $documentTrouve->user_id,
                'type' => 'restitution_completee',
                'title' => '🎉 Restitution complétée',
                'message' => "Merci d'avoir rapporté ce document ! La restitution a été officiellement enregistrée.",
                'document_trouve_id' => $documentTrouve->id,
                'is_read' => false
            ]);
        }

        return back()->with('success', '✅ Document marqué comme restitué !');
    }

    /**
     * Supprimer un document trouvé (si nécessaire)
     */
    public function supprimerDocumentTrouve($id)
    {
        $documentTrouve = DocumentTrouve::findOrFail($id);
        
        // Vérifier si le document peut être supprimé
        if ($documentTrouve->statut !== 'en_attente') {
            return back()->with('error', 'Seuls les documents en attente peuvent être supprimés.');
        }

        // Supprimer la photo si elle existe
        if ($documentTrouve->photo_document) {
            Storage::disk('public')->delete($documentTrouve->photo_document);
        }

        $documentTrouve->delete();

        return redirect()->route('agent.documents-trouves')
            ->with('success', '✅ Document trouvé supprimé avec succès.');
    }

    /**
     * Exporter les statistiques des documents trouvés
     */
    public function exporterStatsDocumentsTrouves()
    {
        $stats = [
            'total' => DocumentTrouve::count(),
            'par_type' => DocumentTrouve::selectRaw('type_document, count(*) as total')
                ->groupBy('type_document')
                ->get(),
            'par_statut' => DocumentTrouve::selectRaw('statut, count(*) as total')
                ->groupBy('statut')
                ->get(),
            'ce_mois' => DocumentTrouve::whereMonth('created_at', now()->month)->count(),
            'ce_mois_matches' => DocumentTrouve::whereMonth('created_at', now()->month)
                ->where('statut', 'matche')
                ->count(),
        ];

        return response()->json($stats);
    }
}