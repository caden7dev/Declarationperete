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
use Illuminate\Support\Facades\DB;

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
                ->orWhere('type_piece', 'LIKE', "%{$search}%")
                ->orWhere('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%");
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
            'title' => '✅ Déclaration validée',
            'content' => "Votre déclaration de perte pour {$perte->type_piece} a été validée avec succès. Votre numéro de déclaration est : {$perte->numero_declaration}",
            'perte_id' => $perte->id,
            'is_read' => false,
            'action_url' => route('perte.show', $perte->id)
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
                    'content' => "Un document correspondant à votre déclaration (perte de {$perte->type_piece}) a été trouvé le " . 
                                 $docTrouve->date_decouverte->format('d/m/Y') . " à " . 
                                 $docTrouve->lieu_decouverte . ". Notre équipe va vérifier et vous contactera.",
                    'perte_id' => $perte->id,
                    'document_trouve_id' => $docTrouve->id,
                    'is_read' => false
                ]);

                // Notification au trouveur
                if ($docTrouve->user_id) {
                    Notification::create([
                        'user_id' => $docTrouve->user_id,
                        'type' => 'correspondance_trouvee',
                        'title' => '🔍 Correspondance trouvée !',
                        'content' => "Un document correspondant à celui que vous avez trouvé a été déclaré perdu. Un agent va traiter votre déclaration.",
                        'document_trouve_id' => $docTrouve->id,
                        'is_read' => false
                    ]);
                }
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
        
        // Créer la notification pour l'utilisateur avec content
        Notification::create([
            'user_id' => $perte->user_id,
            'type' => 'rejet',
            'title' => '❌ Déclaration rejetée',
            'content' => "Votre déclaration de perte pour {$perte->type_piece} a été rejetée.\n\nMotif : {$request->motif_rejet}\n\nVous pouvez faire une nouvelle déclaration en corrigeant les informations.",
            'perte_id' => $perte->id,
            'is_read' => false,
            'action_url' => route('perte.show', $perte->id)
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
                  ->orWhere('prenom_sur_document', 'LIKE', "%{$search}%")
                  ->orWhere('numero_document', 'LIKE', "%{$search}%")
                  ->orWhere('type_document', 'LIKE', "%{$search}%");
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
        
        // Récupérer les déclarations de perte potentielles (même type de document, non encore résolues)
        $pertesPotentielles = Perte::where('statut', 'en_attente')
            ->where('type_piece', $documentTrouve->type_document)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('agent.documents-trouves.show', compact('documentTrouve', 'pertesPotentielles'));
    }

    /**
     * Matcher un document trouvé avec une perte
     */
    public function matcherDocumentTrouve(Request $request, $id)
    {
        $request->validate([
            'perte_id' => 'required|exists:pertes,id',
            'confirmation' => 'accepted'
        ], [
            'perte_id.required' => 'Veuillez sélectionner une déclaration de perte à associer.',
            'perte_id.exists' => 'La déclaration de perte sélectionnée n\'existe pas.',
            'confirmation.accepted' => 'Veuillez confirmer la correspondance.'
        ]);

        try {
            DB::beginTransaction();

            $documentTrouve = DocumentTrouve::findOrFail($id);
            $perte = Perte::findOrFail($request->perte_id);

            // Vérifier que le document n'est pas déjà associé
            if ($documentTrouve->statut !== 'en_attente') {
                return back()->with('error', 'Ce document a déjà été traité.');
            }

            // Vérifier que la déclaration n'est pas déjà validée ou rejetée
            if ($perte->statut !== 'en_attente') {
                return back()->with('error', 'Cette déclaration a déjà été traitée.');
            }

            // Mettre à jour le document trouvé
            $documentTrouve->update([
                'statut' => 'matche',
                'perte_matchee_id' => $perte->id,
                'date_traitement' => now()
            ]);

            // Mettre à jour la déclaration de perte
            $perte->update([
                'statut' => 'validee',
                'validated_by' => Auth::id(),
                'validated_at' => now(),
                'document_retrouve' => true,
                'date_retrouvaille' => now(),
                'document_trouve_id' => $documentTrouve->id
            ]);

            // Générer le numéro de déclaration si nécessaire
            if (!$perte->numero_declaration) {
                $perte->numero_declaration = 'DL-' . strtoupper(uniqid()) . '-' . date('Y');
                $perte->save();
            }

            // Créer une notification pour l'utilisateur (propriétaire)
            Notification::create([
                'user_id' => $perte->user_id,
                'type' => 'document_retrouve',
                'title' => '🎉 Bonne nouvelle ! Votre document a été retrouvé',
                'content' => "Votre {$perte->type_piece} perdu(e) a été retrouvé(e) à {$documentTrouve->lieu_decouverte}. Veuillez contacter l'agent pour récupérer votre document.",
                'perte_id' => $perte->id,
                'document_trouve_id' => $documentTrouve->id,
                'is_read' => false,
                'action_url' => route('perte.show', $perte->id)
            ]);

            // Notification au trouveur
            if ($documentTrouve->user_id) {
                Notification::create([
                    'user_id' => $documentTrouve->user_id,
                    'type' => 'document_matché',
                    'title' => '✅ Votre signalement a permis de retrouver un propriétaire !',
                    'content' => "Le document que vous avez trouvé correspond à une déclaration de perte. Merci pour votre geste citoyen !",
                    'document_trouve_id' => $documentTrouve->id,
                    'is_read' => false
                ]);
            }

            DB::commit();

            return redirect()->route('agent.documents-trouves.index')
                ->with('success', "✅ Document associé avec succès à la déclaration de {$perte->first_name} {$perte->last_name}. Les notifications ont été envoyées.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
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
            if ($perte) {
                Notification::create([
                    'user_id' => $perte->user_id,
                    'type' => 'restitution',
                    'title' => '✅ Document restitué',
                    'content' => "Votre {$perte->type_piece} a été officiellement restitué. Merci d'utiliser notre plateforme !",
                    'perte_id' => $perte->id,
                    'is_read' => false,
                    'action_url' => route('perte.show', $perte->id)
                ]);
            }
        }

        // Notification au trouveur
        if ($documentTrouve->user_id) {
            Notification::create([
                'user_id' => $documentTrouve->user_id,
                'type' => 'restitution_completee',
                'title' => '🎉 Restitution complétée',
                'content' => "Merci d'avoir rapporté ce document ! La restitution a été officiellement enregistrée.",
                'document_trouve_id' => $documentTrouve->id,
                'is_read' => false
            ]);
        }

        return redirect()->route('agent.documents-trouves.index')
            ->with('success', '✅ Document marqué comme restitué avec succès !');
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

        return redirect()->route('agent.documents-trouves.index')
            ->with('success', '🗑️ Document trouvé supprimé avec succès.');
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

    /**
     * Envoyer un message à un utilisateur
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|min:3|max:1000'
        ]);

        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'agent_message',
            'title' => '📩 Message de l\'agent',
            'content' => $request->message,
            'is_read' => false
        ]);

        return back()->with('success', '✅ Message envoyé avec succès !');
    }

    /**
     * Annuler une validation (pour admin)
     */
    public function annuler($id)
    {
        $perte = Perte::findOrFail($id);
        
        if ($perte->statut === 'en_attente') {
            return back()->with('error', 'Cette déclaration est déjà en attente.');
        }

        $perte->update([
            'statut' => 'en_attente',
            'validated_by' => null,
            'validated_at' => null,
            'motif_rejet' => null
        ]);

        Notification::create([
            'user_id' => $perte->user_id,
            'type' => 'annulation',
            'title' => '🔄 Annulation de traitement',
            'content' => 'Votre déclaration a été remise en attente pour réexamen.',
            'perte_id' => $perte->id,
            'is_read' => false,
            'action_url' => route('perte.show', $perte->id)
        ]);

        return redirect()->route('agent.dashboard')
            ->with('success', '✅ La déclaration a été remise en attente.');
    }
}