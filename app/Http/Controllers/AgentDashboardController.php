<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perte;
use App\Models\DocumentTrouve;
use App\Models\Notification;
use App\Models\TypePiece;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\MatchScoreService;
use Illuminate\Support\Facades\Log;

class AgentDashboardController extends Controller
{
    /**
     * Afficher le tableau de bord agent
     * ✅ CORRECTION 1 : Filtrer par défaut sur "en_attente"
     */
    public function index(Request $request)
    {
        $agent = Auth::user();
        // ✅ Par défaut, afficher les déclarations en attente
        $statut = $request->get('statut', 'en_attente');
        $search = $request->get('search', '');
        
        $query = Perte::with(['user', 'typePiece']);
        
        // ✅ Filtrer par statut (sauf si 'tous' ou 'all')
        if ($statut && $statut != 'tous' && $statut != 'all') {
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
        
        $stats = [
            'total' => Perte::count(),
            'en_attente' => Perte::where('statut', Perte::STATUT_EN_ATTENTE)->count(),
            'en_cours' => Perte::where('statut', Perte::STATUT_EN_COURS)->count(),
            'correspondance_trouvee' => Perte::where('statut', Perte::STATUT_CORRESPONDANCE_TROUVEE)->count(),
            'restitue' => Perte::where('statut', Perte::STATUT_RESTITUE)->count(),
            'non_retrouve' => Perte::where('statut', Perte::STATUT_NON_RETROUVE)->count(),
            'validees' => Perte::where('statut', Perte::STATUT_VALIDEE)->count(),
            'rejetees' => Perte::where('statut', Perte::STATUT_REJETEE)->count(),
            'traitees_par_moi' => Perte::where('validated_by', $agent->id)->count()
        ];

        $statsDocumentsTrouves = [
            'total' => DocumentTrouve::count(),
            'en_attente' => DocumentTrouve::where('statut', DocumentTrouve::STATUT_EN_ATTENTE)->count(),
            'matches' => DocumentTrouve::where('statut', DocumentTrouve::STATUT_MATCHE)->count(),
            'restitues' => DocumentTrouve::where('statut', DocumentTrouve::STATUT_RESTITUE)->count(),
        ];

        $derniersTrouves = DocumentTrouve::with('perteMatchee')->orderBy('created_at', 'desc')->take(5)->get();
        
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
     * Prendre en charge une déclaration (passage en en_cours)
     * ✅ CORRECTION 2 : Vérification du verrouillage renforcée
     * ✅ Version avec redirection (AJAX compatible)
     */
    public function prendreEnCharge($id)
    {
        // Vérifier si la requête attend du JSON (AJAX)
        $isAjax = request()->ajax() || request()->wantsJson();
        
        try {
            $perte = Perte::with('assignedAgent')->findOrFail($id);
            
            // ============================================================
            // ✅ VÉRIFICATION DU VERROUILLAGE - RENFORCÉE
            // ============================================================
            
            // Vérifier si déjà pris par UN AUTRE agent
            if ($perte->is_locked && $perte->assigned_to != auth()->id()) {
                $agentNom = $perte->assignedAgent 
                    ? $perte->assignedAgent->name 
                    : 'un autre agent';
                
                $message = "⛔ Ce dossier est déjà verrouillé et traité par {$agentNom}. Vous ne pouvez pas intervenir sur ce dossier.";
                
                if ($isAjax) {
                    return response()->json(['success' => false, 'message' => $message], 403);
                }
                return redirect()->back()->with('error', $message);
            }
            
            // Si déjà pris par MOI, permettre de continuer
            if ($perte->is_locked && $perte->assigned_to == auth()->id()) {
                if ($isAjax) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Vous avez déjà pris ce dossier en charge.',
                        'redirect' => route('agent.perte.recherche', $perte->id)
                    ]);
                }
                return redirect()->route('agent.perte.recherche', $perte->id)
                    ->with('info', 'Vous avez déjà pris ce dossier en charge.');
            }
            
            // Vérifier si en attente
            if ($perte->statut !== Perte::STATUT_EN_ATTENTE) {
                $message = 'Cette déclaration ne peut pas être prise en charge (statut actuel : ' . $perte->statut_text . ').';
                
                if ($isAjax) {
                    return response()->json(['success' => false, 'message' => $message], 403);
                }
                return redirect()->back()->with('error', $message);
            }
            
            // 🔒 Verrouiller le dossier
            $perte->assigned_to = auth()->id();
            $perte->assigned_at = now();
            $perte->is_locked = true;
            $perte->statut = Perte::STATUT_EN_COURS;
            $perte->validated_by = auth()->id();
            $perte->save();
            
            // Notification au citoyen (si utilisateur existe)
            if ($perte->user) {
                Notification::createSystemNotification(
                    $perte->user,
                    'Prise en charge de votre déclaration',
                    "Votre déclaration de perte ({$perte->type_piece}) a été prise en charge par l'agent " . auth()->user()->name,
                    route('perte.suivi', $perte->id),
                    $perte,
                    '📌',
                    'info'
                );
            }
            
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ Déclaration prise en charge avec succès.',
                    'redirect' => route('agent.perte.recherche', $perte->id)
                ]);
            }
            
            return redirect()->route('agent.perte.recherche', $perte->id)
                ->with('success', '✅ Déclaration prise en charge avec succès.');
            
        } catch (\Exception $e) {
            $message = 'Erreur : ' . $e->getMessage();
            
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Léguer un dossier à un autre agent
     * ✅ Version JSON pour AJAX
     */
    public function leguer(Request $request, $id)
    {
        try {
            $perte = Perte::findOrFail($id);
            
            // Vérifier que l'agent actuel est le titulaire
            if ($perte->assigned_to !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à léguer ce dossier.'
                ], 403);
            }
            
            $request->validate([
                'agent_id' => 'required|exists:users,id|different:assigned_to'
            ]);
            
            $nouvelAgent = User::findOrFail($request->agent_id);
            $ancienAgent = auth()->user();
            
            // Transfert
            $perte->assigned_to = $nouvelAgent->id;
            $perte->assigned_at = now();
            $perte->save();
            
            // Notification à l'ancien agent
            Notification::createSystemNotification(
                $ancienAgent,
                '📤 Dossier légué',
                "Vous avez légué la déclaration #{$perte->id} à {$nouvelAgent->name}.",
                route('agent.perte.show', $perte->id),
                $perte,
                '📤',
                'info'
            );
            
            // Notification au nouvel agent
            Notification::createSystemNotification(
                $nouvelAgent,
                '📥 Nouveau dossier légué',
                "L'agent {$ancienAgent->name} vous a légué la déclaration #{$perte->id}.",
                route('agent.perte.show', $perte->id),
                $perte,
                '📥',
                'info'
            );
            
            return response()->json([
                'success' => true,
                'message' => '✅ Dossier légué avec succès à ' . $nouvelAgent->name
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Libérer un dossier (remettre en attente)
     * ✅ Version JSON pour AJAX
     */
    public function liberer($id)
    {
        try {
            $perte = Perte::findOrFail($id);
            
            // Vérifier que l'agent actuel est le titulaire
            if ($perte->assigned_to !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à libérer ce dossier.'
                ], 403);
            }
            
            // Libérer le dossier
            $perte->assigned_to = null;
            $perte->assigned_at = null;
            $perte->is_locked = false;
            $perte->statut = Perte::STATUT_EN_ATTENTE;
            $perte->save();
            
            return response()->json([
                'success' => true,
                'message' => '✅ Dossier libéré avec succès. Il est à nouveau disponible.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher la page de recherche de correspondances avec filtres et score
     * - Prise en charge automatique si la déclaration est en attente
     * - Normalisation du statut pour éviter les incohérences d'espace
     * - Correction si le statut est vide
     */
    public function rechercheCorrespondances($id, Request $request, MatchScoreService $scoreService)
    {
        // Récupération explicite du modèle avec la relation user
        $perte = Perte::with('user')->findOrFail($id);

        // 🔧 Si le statut est vide ou null, on le met en 'en_attente'
        if (empty($perte->statut)) {
            $perte->statut = Perte::STATUT_EN_ATTENTE;
            $perte->save();
            return redirect()->route('agent.perte.recherche', ['id' => $perte->id])
                ->with('info', 'Statut corrigé : la déclaration a été mise en attente. Veuillez rafraîchir.');
        }

        // Normalisation du statut
        $statutNormalise = str_replace(' ', '_', trim($perte->statut));

        // Prise en charge automatique si en attente
        if ($statutNormalise === Perte::STATUT_EN_ATTENTE) {
            $agent = Auth::user();
            $perte->statut = Perte::STATUT_EN_COURS;
            $perte->validated_by = $agent->id;
            $perte->save();

            // Notification uniquement si le propriétaire existe
            if ($perte->user) {
                Notification::createSystemNotification(
                    $perte->user,
                    'Prise en charge de votre déclaration',
                    "Votre déclaration de perte ({$perte->type_piece}) a été prise en charge par un agent. Nous allons procéder aux vérifications.",
                    route('perte.suivi', $perte->id),
                    $perte,
                    '📌',
                    'info'
                );
            } else {
                Log::warning('Notification non envoyée (prise en charge auto) : pas de propriétaire', ['perte_id' => $perte->id]);
            }

            return redirect()->route('agent.perte.recherche', ['id' => $perte->id])
                ->with('success', '✅ Déclaration prise en charge automatiquement.');
        }

        // Vérifier que le statut normalisé est autorisé
        if (!in_array($statutNormalise, [Perte::STATUT_EN_COURS, Perte::STATUT_CORRESPONDANCE_TROUVEE])) {
            return redirect()->route('agent.dashboard')
                ->with('error', "Action non autorisée pour ce statut (actuel : '{$perte->statut}' / normalisé : '{$statutNormalise}').");
        }

        // Récupérer les filtres
        $typeDocument = $request->input('type_document');
        $nom = $request->input('nom');
        $numero = $request->input('numero');
        $dateDecouverte = $request->input('date_decouverte');
        $lieu = $request->input('lieu');

        $query = DocumentTrouve::where('statut', DocumentTrouve::STATUT_EN_ATTENTE);

        if ($typeDocument) $query->where('type_document', $typeDocument);
        if ($nom) $query->where('nom_sur_document', 'LIKE', "%{$nom}%");
        if ($numero) $query->where('numero_document', 'LIKE', "%{$numero}%");
        if ($dateDecouverte) $query->whereDate('date_decouverte', $dateDecouverte);
        if ($lieu) $query->where('lieu_decouverte', 'LIKE', "%{$lieu}%");

        $documents = $query->orderBy('date_decouverte', 'desc')->paginate(15);

        $documents->getCollection()->transform(function ($doc) use ($perte, $scoreService) {
            $doc->score = $scoreService->calculate($perte, $doc);
            $doc->scoreLevel = $scoreService->getLevel($doc->score);
            $doc->scoreLabel = $scoreService->getLabel($doc->scoreLevel);
            return $doc;
        });

        $highMatchCount = $documents->filter(fn($d) => $d->scoreLevel === 'high')->count();
        $mediumMatchCount = $documents->filter(fn($d) => $d->scoreLevel === 'medium')->count();
        $lowMatchCount = $documents->filter(fn($d) => $d->scoreLevel === 'low')->count();
        $typesDocuments = DocumentTrouve::distinct('type_document')->pluck('type_document');

        return view('agent.matching', compact(
            'perte',
            'documents',
            'typesDocuments',
            'highMatchCount',
            'mediumMatchCount',
            'lowMatchCount'
        ));
    }

    /**
     * Associer un ou plusieurs documents trouvés à la déclaration (match)
     * - Si plusieurs sont sélectionnés, seul le premier est associé
     */
    public function associerDocument(Request $request, $perteId)
    {
        $ids = $request->input('document_ids', []);
        if (empty($ids)) {
            $singleId = $request->input('document_trouve_id');
            if ($singleId) {
                $ids = [$singleId];
            }
        }

        $ids = array_filter($ids);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Aucun document sélectionné.');
        }

        $perte = Perte::with('user')->findOrFail($perteId);
        $agent = Auth::user();

        if (!in_array($perte->statut, [Perte::STATUT_EN_COURS, Perte::STATUT_CORRESPONDANCE_TROUVEE])) {
            return redirect()->back()->with('error', 'Impossible d\'associer : statut de la déclaration non adapté.');
        }

        $documents = DocumentTrouve::whereIn('id', $ids)->get();
        $invalidDocs = $documents->filter(fn($doc) => $doc->statut !== DocumentTrouve::STATUT_EN_ATTENTE);
        if ($invalidDocs->count() > 0) {
            return redirect()->back()->with('error', 'Certains documents ne sont plus disponibles (déjà associés).');
        }

        $documentTrouve = $documents->first();

        DB::beginTransaction();
        try {
            $perte->statut = Perte::STATUT_CORRESPONDANCE_TROUVEE;
            $perte->document_trouve_id = $documentTrouve->id;
            $perte->save();

            $documentTrouve->statut = DocumentTrouve::STATUT_MATCHE;
            $documentTrouve->perte_matchee_id = $perte->id;
            $documentTrouve->save();

            // Notifications uniquement si les utilisateurs existent
            if ($perte->user) {
                Notification::createDocumentRetrouveNotification($perte, $documentTrouve, $agent);
            } else {
                Log::warning('Notification de document retrouvé non envoyée : pas de propriétaire', ['perte_id' => $perte->id]);
            }

            if ($documentTrouve->user_id) {
                Notification::createMatchNotificationForFinder($documentTrouve, $agent);
            }

            DB::commit();

            $message = 'Association effectuée avec succès. Le citoyen a été notifié.';
            if ($documents->count() > 1) {
                $message = '✅ Seul le premier document sélectionné a été associé (un seul peut l\'être par déclaration). Les autres restent disponibles.';
            }
            return redirect()->route('agent.perte.show', $perte->id)->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de l\'association : ' . $e->getMessage());
        }
    }

    /**
     * Déclarer qu'aucun document n'a été retrouvé (avec génération du récépissé PDF)
     */
    public function declarerNonRetrouve($id)
    {
        $perte = Perte::with('user')->findOrFail($id);
        $agent = Auth::user();

        if (!in_array($perte->statut, [Perte::STATUT_EN_COURS, Perte::STATUT_CORRESPONDANCE_TROUVEE])) {
            return redirect()->back()->with('error', 'Action non autorisée pour ce statut.');
        }

        $pdf = PDF::loadView('agent.recu_non_retrouve', compact('perte'));
        $pdfPath = 'recus/recu_' . $perte->id . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        $perte->statut = Perte::STATUT_NON_RETROUVE;
        $perte->pdf_recu = $pdfPath;
        $perte->date_passage_non_retrouve = now();
        $perte->save();

        $downloadLink = route('perte.download-recu', $perte->id);
        if ($perte->user) {
            Notification::createSystemNotification(
                $perte->user,
                'Document non retrouvé – Récépissé disponible',
                "Après recherche approfondie, votre document ({$perte->type_piece}) n'a pas été retrouvé. Téléchargez le récépissé pour refaire votre titre.",
                $downloadLink,
                $perte,
                '📄',
                'warning'
            );
        } else {
            Log::warning('Notification de non-retrouvé non envoyée : pas de propriétaire', ['perte_id' => $perte->id]);
        }

        return redirect()->route('agent.dashboard')
            ->with('success', 'Déclaration marquée comme non retrouvée. Le citoyen a reçu le récépissé.');
    }

    /**
     * Afficher les notifications de l'agent
     */
    public function notifications()
    {
        $agent = Auth::user();
        
        $notifications = Notification::where('user_id', $agent->id)
            ->where('type', 'system')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $unreadCount = Notification::where('user_id', $agent->id)
            ->where('type', 'system')
            ->where('is_read', false)
            ->count();
        
        return view('agent.notifications', compact('notifications', 'unreadCount'));
    }

    /**
     * Marquer la déclaration comme restituée (après restitution physique)
     */
    public function restituer($id)
    {
        $perte = Perte::with('user')->findOrFail($id);
        $agent = Auth::user();
        
        if (!in_array($perte->statut, [Perte::STATUT_CORRESPONDANCE_TROUVEE, Perte::STATUT_PRET_RECUPERATION])) {
            return redirect()->back()->with('error', 'Seules les déclarations avec correspondance trouvée ou prêtes à récupérer peuvent être restituées.');
        }
        
        $perte->statut = Perte::STATUT_RESTITUE;
        $perte->date_restitution = now();
        $perte->save();
        
        if ($perte->document_trouve_id) {
            $documentTrouve = $perte->documentTrouve;
            if ($documentTrouve) {
                $documentTrouve->statut = DocumentTrouve::STATUT_RESTITUE;
                $documentTrouve->date_restitution = now();
                $documentTrouve->save();
                Notification::createRestitutionCompleteeNotification($documentTrouve, $agent);
            }
        }
        
        if ($perte->user) {
            Notification::createRestitutionNotification($perte, $agent);
        } else {
            Log::warning('Notification de restitution non envoyée : pas de propriétaire', ['perte_id' => $perte->id]);
        }
        
        return redirect()->route('agent.dashboard')
            ->with('success', 'Déclaration marquée comme restituée. Le citoyen a été notifié.');
    }

    /**
     * Marquer le document comme prêt à récupérer (après renouvellement)
     */
    public function marquerPretRecuperation(Request $request, $id)
    {
        $perte = Perte::with('user')->findOrFail($id);
        if ($perte->statut !== Perte::STATUT_NON_RETROUVE) {
            return back()->with('error', 'Seules les déclarations non retrouvées peuvent être préparées.');
        }

        $request->validate([
            'lieu_recuperation' => 'required|string|max:255'
        ]);

        $perte->statut = Perte::STATUT_PRET_RECUPERATION;
        $perte->lieu_recuperation = $request->lieu_recuperation;
        $perte->date_preparation = now();
        $perte->save();

        if ($perte->user) {
            Notification::createSystemNotification(
                $perte->user,
                'Nouveau document prêt à récupérer',
                "Votre nouveau document ({$perte->type_piece}) est prêt. Rendez-vous à : {$perte->lieu_recuperation}.",
                route('perte.suivi', $perte->id),
                $perte,
                '📍',
                'success'
            );
        } else {
            Log::warning('Notification de prêt à récupérer non envoyée : pas de propriétaire', ['perte_id' => $perte->id]);
        }

        return redirect()->route('agent.dashboard')->with('success', 'Le citoyen a été notifié pour la récupération.');
    }

    /**
     * Rejeter une déclaration (avec motif)
     */
    public function rejeter(Request $request, $id)
    {
        $request->validate([
            'motif_rejet' => 'required|string|min:10|max:500'
        ], [
            'motif_rejet.required' => 'Le motif du rejet est obligatoire.',
            'motif_rejet.min' => 'Le motif doit contenir au moins 10 caractères.'
        ]);
        
        $perte = Perte::with('user')->findOrFail($id);
        $agent = Auth::user();
        
        if (!in_array($perte->statut, [Perte::STATUT_EN_ATTENTE, Perte::STATUT_EN_COURS])) {
            return redirect()->back()->with('error', 'Impossible de rejeter cette déclaration (statut non compatible).');
        }
        
        $perte->statut = Perte::STATUT_REJETEE;
        $perte->motif_rejet = $request->motif_rejet;
        $perte->validated_by = $agent->id;
        $perte->validated_at = now();
        $perte->save();
        
        if ($perte->user) {
            Notification::createRejectionNotification($perte, $agent, $request->motif_rejet);
        } else {
            Log::warning('Notification de rejet non envoyée : pas de propriétaire', ['perte_id' => $perte->id]);
        }
        
        return redirect()->route('agent.dashboard')
            ->with('success', 'Déclaration rejetée et motif envoyé.');
    }

    /**
     * Voir les détails d'une déclaration (pour agent)
     */
    public function show($id)
    {
        $perte = Perte::with(['user', 'typePiece', 'validator', 'documentTrouve', 'assignedAgent'])->findOrFail($id);
        return view('agent.perte-show', compact('perte'));
    }

    /**
     * Valider une déclaration (validation simple)
     */
    public function valider($id)
    {
        $perte = Perte::with('user')->findOrFail($id);
        $agent = Auth::user();
        
        if ($perte->statut !== Perte::STATUT_EN_ATTENTE) {
            return redirect()->back()->with('error', 'Cette déclaration ne peut plus être validée.');
        }
        
        $perte->statut = Perte::STATUT_VALIDEE;
        $perte->validated_by = $agent->id;
        $perte->validated_at = now();
        if (!$perte->numero_declaration) {
            $perte->numero_declaration = Perte::generateNumeroDeclaration();
        }
        $perte->save();
        
        if ($perte->user) {
            Notification::createValidationNotification($perte, $agent);
        } else {
            Log::warning('Notification de validation non envoyée : pas de propriétaire', ['perte_id' => $perte->id]);
        }
        
        return redirect()->route('agent.dashboard')
            ->with('success', '✅ Déclaration validée avec succès. Numéro généré : ' . $perte->numero_declaration);
    }

    /**
     * Annuler une validation (remettre en attente)
     */
    public function annuler($id)
    {
        $perte = Perte::with('user')->findOrFail($id);
        
        if ($perte->statut === Perte::STATUT_EN_ATTENTE) {
            return redirect()->back()->with('error', 'Cette déclaration est déjà en attente.');
        }
        
        $perte->statut = Perte::STATUT_EN_ATTENTE;
        $perte->validated_by = null;
        $perte->validated_at = null;
        $perte->motif_rejet = null;
        $perte->save();
        
        if ($perte->user) {
            Notification::createAnnulationNotification($perte, Auth::user());
        } else {
            Log::warning('Notification d\'annulation non envoyée : pas de propriétaire', ['perte_id' => $perte->id]);
        }
        
        return redirect()->route('agent.dashboard')
            ->with('success', 'La déclaration a été remise en attente.');
    }

    // ========== GESTION DES DOCUMENTS TROUVÉS ==========

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
            'en_attente' => DocumentTrouve::where('statut', DocumentTrouve::STATUT_EN_ATTENTE)->count(),
            'matches' => DocumentTrouve::where('statut', DocumentTrouve::STATUT_MATCHE)->count(),
            'restitues' => DocumentTrouve::where('statut', DocumentTrouve::STATUT_RESTITUE)->count(),
        ];
        
        return view('agent.documents-trouves.index', compact('documentsTrouves', 'stats', 'statut', 'search'));
    }

    /**
     * Afficher les détails d'un document trouvé
     * ✅ CORRECTION : Utilise 'action_url' au lieu de 'link'
     */
    public function showDocumentTrouve($id)
    {
        $documentTrouve = DocumentTrouve::with('perteMatchee')->findOrFail($id);
        $pertesPotentielles = Perte::where('statut', Perte::STATUT_EN_ATTENTE)
            ->where('type_piece', $documentTrouve->type_document)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // ✅ Récupérer la notification non lue liée à ce document
        // Utilise 'action_url' au lieu de 'link' (colonne inexistante)
        $notification = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->where(function($q) use ($id) {
                $q->where('document_trouve_id', $id)
                  ->orWhere('content', 'LIKE', "%document #{$id}%")
                  ->orWhere('action_url', 'LIKE', "%{$id}%");
            })
            ->first();
        
        return view('agent.documents-trouves.show', compact('documentTrouve', 'pertesPotentielles', 'notification'));
    }

    /**
     * Marquer la notification d'un document trouvé comme lue
     * ✅ Action simple : marque la notification comme lue
     */
    public function marquerLuDocumentTrouve($id)
    {
        try {
            // Récupérer la notification non lue liée à ce document
            $notification = Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->where(function($q) use ($id) {
                    $q->where('document_trouve_id', $id)
                      ->orWhere('action_url', 'LIKE', "%documents-trouves/{$id}%")
                      ->orWhere('action_url', 'LIKE', "%documents-trouves%{$id}%");
                })
                ->first();
            
            if ($notification) {
                $notification->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);
                
                return redirect()->back()->with('success', '✅ Notification marquée comme lue !');
            }
            
            return redirect()->back()->with('info', 'Aucune notification non lue pour ce document.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function matcherDocumentTrouve(Request $request, $id)
    {
        $request->validate([
            'perte_id' => 'required|exists:pertes,id',
            'confirmation' => 'accepted'
        ]);
        
        DB::beginTransaction();
        try {
            $documentTrouve = DocumentTrouve::findOrFail($id);
            $perte = Perte::with('user')->findOrFail($request->perte_id);
            $agent = Auth::user();
            
            if ($documentTrouve->statut !== DocumentTrouve::STATUT_EN_ATTENTE) {
                throw new \Exception('Ce document a déjà été traité.');
            }
            if ($perte->statut !== Perte::STATUT_EN_ATTENTE && $perte->statut !== Perte::STATUT_EN_COURS) {
                throw new \Exception('Cette déclaration ne peut pas être associée (statut non compatible).');
            }
            
            $documentTrouve->statut = DocumentTrouve::STATUT_MATCHE;
            $documentTrouve->perte_matchee_id = $perte->id;
            $documentTrouve->save();
            
            $perte->statut = Perte::STATUT_CORRESPONDANCE_TROUVEE;
            $perte->document_trouve_id = $documentTrouve->id;
            if (!$perte->numero_declaration) {
                $perte->numero_declaration = Perte::generateNumeroDeclaration();
            }
            $perte->save();
            
            if ($perte->user) {
                Notification::createDocumentRetrouveNotification($perte, $documentTrouve, $agent);
            } else {
                Log::warning('Notification de match (depuis documents-trouves) non envoyée : pas de propriétaire', ['perte_id' => $perte->id]);
            }
            if ($documentTrouve->user_id) {
                Notification::createMatchNotificationForFinder($documentTrouve, $agent);
            }
            // Marquer la notification liée comme lue
            Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->where(function($q) use ($id) {
                    $q->where('action_url', 'like', "%documents-trouves/{$id}%")
                      ->orWhere('perte_id', $documentTrouve->perte_id ?? null);
                })
                ->update(['is_read' => true, 'read_at' => now()]);
            
            DB::commit();
            return redirect()->route('agent.documents-trouves.index')
                ->with('success', "✅ Document associé avec succès à la déclaration de {$perte->first_name} {$perte->last_name}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function marquerRestitue($id)
    {
        $documentTrouve = DocumentTrouve::findOrFail($id);
        if ($documentTrouve->statut !== DocumentTrouve::STATUT_MATCHE) {
            return back()->with('error', 'Seuls les documents matchés peuvent être marqués comme restitués.');
        }
        
        $documentTrouve->statut = DocumentTrouve::STATUT_RESTITUE;
        $documentTrouve->date_restitution = now();
        $documentTrouve->save();
        
        if ($documentTrouve->perte_matchee_id) {
            $perte = $documentTrouve->perteMatchee;
            if ($perte && $perte->statut !== Perte::STATUT_RESTITUE) {
                $perte->statut = Perte::STATUT_RESTITUE;
                $perte->date_restitution = now();
                $perte->save();
                if ($perte->user) {
                    Notification::createRestitutionNotification($perte, Auth::user());
                }
            }
        }
        
        if ($documentTrouve->user_id) {
            Notification::createRestitutionCompleteeNotification($documentTrouve, Auth::user());
        }
        
        return redirect()->route('agent.documents-trouves.index')
            ->with('success', '✅ Document marqué comme restitué.');
    }

    public function supprimerDocumentTrouve($id)
    {
        $documentTrouve = DocumentTrouve::findOrFail($id);
        if ($documentTrouve->statut !== DocumentTrouve::STATUT_EN_ATTENTE) {
            return back()->with('error', 'Seuls les documents en attente peuvent être supprimés.');
        }
        if ($documentTrouve->photo_document) {
            Storage::disk('public')->delete($documentTrouve->photo_document);
        }
        $documentTrouve->delete();
        return redirect()->route('agent.documents-trouves.index')->with('success', '🗑️ Document trouvé supprimé.');
    }

    public function exporterStatsDocumentsTrouves()
    {
        $stats = [
            'total' => DocumentTrouve::count(),
            'par_type' => DocumentTrouve::selectRaw('type_document, count(*) as total')->groupBy('type_document')->get(),
            'par_statut' => DocumentTrouve::selectRaw('statut, count(*) as total')->groupBy('statut')->get(),
            'ce_mois' => DocumentTrouve::whereMonth('created_at', now()->month)->count(),
            'ce_mois_matches' => DocumentTrouve::whereMonth('created_at', now()->month)->where('statut', DocumentTrouve::STATUT_MATCHE)->count(),
        ];
        return response()->json($stats);
    }

    // ========== AUTRES ACTIONS DASHBOARD ==========

    public function traiterSuivant()
    {
        $next = Perte::where('statut', Perte::STATUT_EN_ATTENTE)
                     ->orderBy('created_at')
                     ->first();
        if (!$next) {
            return redirect()->route('agent.dashboard')
                   ->with('info', 'Aucune déclaration en attente.');
        }
        return redirect()->route('agent.perte.show', $next->id);
    }

    public function validationGroupee(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Aucune déclaration sélectionnée.');
        }
        $count = 0;
        foreach ($ids as $id) {
            $perte = Perte::find($id);
            if ($perte && $perte->statut === Perte::STATUT_EN_ATTENTE) {
                $perte->statut = Perte::STATUT_VALIDEE;
                $perte->validated_by = Auth::id();
                $perte->validated_at = now();
                $perte->save();
                $count++;
            }
        }
        return redirect()->back()->with('success', "$count déclaration(s) validée(s).");
    }

    public function statistiques()
    {
        $pertesParMois = Perte::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mois, count(*) as total')
                              ->groupBy('mois')
                              ->orderBy('mois')
                              ->get();
        $statuts = [
            'en_attente' => Perte::where('statut', Perte::STATUT_EN_ATTENTE)->count(),
            'en_cours' => Perte::where('statut', Perte::STATUT_EN_COURS)->count(),
            'correspondance_trouvee' => Perte::where('statut', Perte::STATUT_CORRESPONDANCE_TROUVEE)->count(),
            'restitue' => Perte::where('statut', Perte::STATUT_RESTITUE)->count(),
            'non_retrouve' => Perte::where('statut', Perte::STATUT_NON_RETROUVE)->count(),
        ];
        return view('agent.statistiques', compact('pertesParMois', 'statuts'));
    }

    public function rapportPDF()
    {
        $pertes = Perte::with('user')->orderBy('created_at', 'desc')->get();
        $pdf = PDF::loadView('agent.rapport_pdf', compact('pertes'));
        return $pdf->download('rapport_declarations.pdf');
    }

    public function rapports()
    {
        $pertes = Perte::with('user')->orderBy('created_at', 'desc')->get();
        $statuts = [
            'en_attente' => Perte::where('statut', Perte::STATUT_EN_ATTENTE)->count(),
            'validees' => Perte::where('statut', Perte::STATUT_VALIDEE)->count(),
            'restitues' => Perte::where('statut', Perte::STATUT_RESTITUE)->count(),
            'non_retrouves' => Perte::where('statut', Perte::STATUT_NON_RETROUVE)->count(),
        ];
        return view('agent.rapports', compact('pertes', 'statuts'));
    }

    // ========== MESSAGERIE (Vue + AJAX) ==========

    public function messages()
    {
        $agent = Auth::user();
        $citizens = User::whereIn('role', ['citoyen', 'user'])->orderBy('name')->get();

        foreach ($citizens as $citizen) {
            $lastMessage = Notification::where(function($q) use ($agent, $citizen) {
                                            $q->where('from_user_id', $agent->id)
                                              ->where('user_id', $citizen->id);
                                        })
                                        ->orWhere(function($q) use ($agent, $citizen) {
                                            $q->where('from_user_id', $citizen->id)
                                              ->where('user_id', $agent->id);
                                        })
                                        ->where('type', 'agent_message')
                                        ->orderBy('created_at', 'desc')
                                        ->first();
            $citizen->last_message = $lastMessage ? $lastMessage->content : null;
            $citizen->last_message_time = $lastMessage ? $lastMessage->created_at->format('H:i') : null;
            $citizen->is_online = $this->isUserOnline($citizen->id);
            $citizen->cases_count = Perte::where('user_id', $citizen->id)
                                         ->whereIn('statut', [Perte::STATUT_EN_COURS, Perte::STATUT_CORRESPONDANCE_TROUVEE])
                                         ->count();
        }

        return view('agent.messages', compact('citizens'));
    }

    public function getCitizensList(Request $request)
    {
        $search = $request->get('search', '');
        $agent = Auth::user();
        $query = User::whereIn('role', ['citoyen', 'user']);
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        $citizens = $query->orderBy('name')->get();

        $data = [];
        foreach ($citizens as $citizen) {
            $lastMessage = Notification::where(function($q) use ($agent, $citizen) {
                                            $q->where('from_user_id', $agent->id)->where('user_id', $citizen->id);
                                        })
                                        ->orWhere(function($q) use ($agent, $citizen) {
                                            $q->where('from_user_id', $citizen->id)->where('user_id', $agent->id);
                                        })
                                        ->where('type', 'agent_message')
                                        ->orderBy('created_at', 'desc')
                                        ->first();

            $data[] = [
                'id' => $citizen->id,
                'name' => $citizen->name,
                'avatarInitials' => $this->getInitials($citizen->name),
                'status' => $this->isUserOnline($citizen->id) ? 'online' : 'offline',
                'extraBadge' => null,
                'joined' => $citizen->created_at->format('Y'),
                'casesInfo' => Perte::where('user_id', $citizen->id)->count(),
                'lastMessagePreview' => $lastMessage ? (strlen($lastMessage->content) > 42 ? substr($lastMessage->content, 0, 42).'…' : $lastMessage->content) : 'Aucun message',
                'lastMessageTime' => $lastMessage ? $lastMessage->created_at->format('H:i') : null,
            ];
        }
        return response()->json($data);
    }

    public function getMessages($userId)
    {
        $agentId = Auth::id();
        $messages = Notification::where(function($q) use ($agentId, $userId) {
                                    $q->where('from_user_id', $agentId)->where('user_id', $userId);
                                })
                                ->orWhere(function($q) use ($agentId, $userId) {
                                    $q->where('from_user_id', $userId)->where('user_id', $agentId);
                                })
                                ->where('type', 'agent_message')
                                ->orderBy('created_at', 'asc')
                                ->get(['id', 'from_user_id', 'user_id', 'content', 'created_at', 'is_read']);

        Notification::where('user_id', $agentId)
                    ->where('from_user_id', $userId)
                    ->where('type', 'agent_message')
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

        $formatted = [];
        foreach ($messages as $msg) {
            $formatted[] = [
                'id' => $msg->id,
                'direction' => ($msg->from_user_id == $agentId) ? 'outgoing' : 'incoming',
                'text' => $msg->content,
                'statusText' => $msg->is_read ? 'Lu' : 'Envoyé',
                'timestamp' => $msg->created_at->format('H:i'),
            ];
        }
        return response()->json($formatted);
    }

    public function sendMessageAjax(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|min:1|max:1000'
        ]);

        $citizen = User::findOrFail($request->user_id);
        $agent = Auth::user();

        $notification = Notification::createMessageNotification($citizen, $agent, $request->message);

        return response()->json([
            'success' => true,
            'message' => 'Message envoyé',
            'data' => [
                'id' => $notification->id,
                'direction' => 'outgoing',
                'text' => $request->message,
                'statusText' => 'Envoyé',
                'timestamp' => now()->format('H:i'),
            ]
        ]);
    }

    public function unreadCount()
    {
        $agentId = Auth::id();
        $count = Notification::where('user_id', $agentId)
                    ->where('type', 'agent_message')
                    ->where('is_read', false)
                    ->count();

        return response()->json(['count' => $count]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|min:3|max:1000'
        ]);
        $user = User::findOrFail($request->user_id);
        Notification::createMessageNotification($user, Auth::user(), $request->message);
        return redirect()->back()->with('success', '✅ Message envoyé avec succès !');
    }

    public function envoyerMessage(Request $request)
    {
        return $this->sendMessageAjax($request);
    }

    public function simulerPretRecuperation($id)
    {
        $perte = Perte::with('user')->findOrFail($id);
        if ($perte->statut !== Perte::STATUT_NON_RETROUVE) {
            return back()->with('error', 'Seules les déclarations non retrouvées peuvent être simulées.');
        }

        $perte->statut = Perte::STATUT_PRET_RECUPERATION;
        $perte->lieu_recuperation = 'Commissariat de Lomé – Bureau 5 (SIMULATION)';
        $perte->date_preparation = now();
        $perte->save();

        if ($perte->user) {
            Notification::createSystemNotification(
                $perte->user,
                'Nouveau document prêt à récupérer (SIMULATION)',
                "Votre nouveau document ({$perte->type_piece}) est prêt. Rendez-vous à : {$perte->lieu_recuperation}.",
                route('perte.suivi', $perte->id),
                $perte,
                '📍',
                'success'
            );
        } else {
            Log::warning('Notification de simulation non envoyée : pas de propriétaire', ['perte_id' => $perte->id]);
        }

        return back()->with('success', '✅ Simulation : le document est marqué comme prêt. Le citoyen a été notifié.');
    }

    public function messageHistory($userId)
    {
        return $this->getMessages($userId);
    }

    /**
     * Aperçu rapide d'un document trouvé (AJAX)
     */
    public function previewDocumentTrouve(DocumentTrouve $document)
    {
        if (request()->ajax()) {
            $html = view('agent.partials.document-preview', compact('document'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        }
        abort(404);
    }

    // ========== HELPERS ==========

    private function getInitials($name)
    {
        $parts = explode(' ', trim($name));
        $initials = '';
        foreach ($parts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper(substr($part, 0, 1));
            }
        }
        return substr($initials, 0, 2);
    }

    private function isUserOnline($userId)
    {
        return cache()->has('user_online_' . $userId);
    }
}