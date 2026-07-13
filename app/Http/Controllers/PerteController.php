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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $enAttenteCount = Perte::where('user_id', $user->id)->where('statut', Perte::STATUT_EN_ATTENTE)->count();
        $enAttenteVerificationCount = Perte::where('user_id', $user->id)->where('statut', Perte::STATUT_EN_ATTENTE_VERIFICATION)->count();
        $enCoursCount = Perte::where('user_id', $user->id)->where('statut', Perte::STATUT_EN_COURS)->count();
        $correspondanceCount = Perte::where('user_id', $user->id)->where('statut', Perte::STATUT_CORRESPONDANCE_TROUVEE)->count();
        $restitueCount = Perte::where('user_id', $user->id)->where('statut', Perte::STATUT_RESTITUE)->count();
        $nonRetrouveCount = Perte::where('user_id', $user->id)->where('statut', Perte::STATUT_NON_RETROUVE)->count();
        $valideeCount = Perte::where('user_id', $user->id)->where('statut', Perte::STATUT_VALIDEE)->count();
        $rejeteeCount = Perte::where('user_id', $user->id)->where('statut', Perte::STATUT_REJETEE)->count();
        
        return view('perte.index', compact(
            'pertes',
            'totalDeclarations',
            'enAttenteCount',
            'enAttenteVerificationCount',
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
                             ->where('statut', Perte::STATUT_NON_RETROUVE)
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
     * Afficher la liste de suivi des déclarations du citoyen.
     */
    public function showSuivi(Request $request)
    {
        $user = Auth::user();
        $query = Perte::where('user_id', $user->id);
        
        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }
        
        $pertes = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('citizen.suivi-list', compact('pertes'));
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

        return back()->with('success', '✅ Vous avez signalé la récupération. L\'agent en a été informé.');
    }

    /**
     * ✅ Le propriétaire confirme avoir récupéré son document
     * (Après avoir contacté le trouveur et récupéré le document)
     */
    public function confirmRecuperation($id)
    {
        $perte = Perte::with('documentTrouve')->where('user_id', auth()->id())->findOrFail($id);
        
        // Vérifier le statut
        if ($perte->statut !== Perte::STATUT_CORRESPONDANCE_TROUVEE) {
            return back()->with('error', 'Cette déclaration ne peut pas être marquée comme restituée (statut actuel : ' . $perte->statut_text . ').');
        }
        
        // Vérifier que la restitution n'a pas déjà été faite
        if ($perte->date_restitution) {
            return back()->with('error', 'Ce document a déjà été restitué.');
        }

        // Vérifier qu'un document trouvé est bien associé
        if (!$perte->document_trouve_id) {
            return back()->with('error', 'Aucun document trouvé associé à cette déclaration.');
        }
        
        DB::beginTransaction();
        try {
            // Mettre à jour la déclaration
            $perte->statut = Perte::STATUT_RESTITUE;
            $perte->date_restitution = now();
            $perte->save();
            
            // Mettre à jour le document trouvé
            $document = $perte->documentTrouve;
            if ($document) {
                $document->statut = 'restitue';
                $document->date_restitution = now();
                $document->save();
            }
            
            // Notification au trouveur (pour le remercier)
            if ($document && $document->user_id) {
                Notification::create([
                    'user_id' => $document->user_id,
                    'type' => 'restitution_confirmee',
                    'title' => '✅ Le propriétaire a récupéré son document !',
                    'content' => "Le propriétaire du document que vous avez trouvé a confirmé l'avoir récupéré. Merci pour votre geste citoyen ! 🙏",
                    'icon' => '🎉',
                    'is_read' => false,
                ]);
            }
            
            // Notification à tous les agents
            $agents = User::where('role', 'agent')->get();
            foreach ($agents as $agent) {
                Notification::create([
                    'user_id' => $agent->id,
                    'type' => 'restitution_agent',
                    'title' => '✅ Restitution confirmée par le propriétaire',
                    'content' => "Le propriétaire {$perte->first_name} {$perte->last_name} a confirmé avoir récupéré son document ({$perte->type_piece}).",
                    'action_url' => route('agent.perte.show', $perte->id),
                    'icon' => '✅',
                    'is_read' => false,
                    'perte_id' => $perte->id,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('perte.index')
                ->with('success', '✅ Félicitations ! Vous avez récupéré votre document. Merci d\'avoir utilisé e-Déclaration TG ! 🇹🇬');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Enregistrer une nouvelle déclaration.
     * ✅ La date d'expiration est OPTIONNELLE
     * ✅ TOUJOURS en attente (pas de notification spéciale)
     * ✅ Redirection vers la liste des déclarations
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
            'date_delivrance'=> 'nullable|date|before_or_equal:today',
            'date_expiration'=> [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value) {
                        $dateDelivrance = $request->input('date_delivrance');
                        $today = Carbon::today();
                        $dateExpiration = Carbon::parse($value);
                        
                        if ($dateExpiration->lt($today)) {
                            $fail('La date d\'expiration doit être dans le futur.');
                        }
                        
                        if ($dateDelivrance && $dateExpiration->lte(Carbon::parse($dateDelivrance))) {
                            $fail('La date d\'expiration doit être postérieure à la date de délivrance.');
                        }
                    }
                },
            ],
            'autorite_delivrance' => 'nullable|string|max:255',
            'date_perte'     => [
                'required',
                'date',
                'before_or_equal:today',
                function ($attribute, $value, $fail) use ($request) {
                    $dateDelivrance = $request->input('date_delivrance');
                    if ($dateDelivrance && $value < $dateDelivrance) {
                        $fail('La date de perte doit être postérieure ou égale à la date de délivrance.');
                    }
                },
            ],
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
            'date_delivrance.before_or_equal' => 'La date de délivrance ne peut pas être dans le futur.',
        ]);
        
        // Ajout des champs par défaut
        $validated['user_id'] = Auth::id();
        $validated['date_declaration'] = now();
        
        // ✅ TOUJOURS en attente, PEU IMPORTE la date d'expiration
        // Le statut_verification indique si c'est auto ou manuelle
        $validated['statut'] = Perte::STATUT_EN_ATTENTE;
        
        // ✅ On garde juste la trace du mode de vérification
        if (!empty($validated['date_expiration'])) {
            $validated['statut_verification'] = Perte::STATUT_VERIFICATION_AUTO;
        } else {
            $validated['statut_verification'] = Perte::STATUT_VERIFICATION_MANUELLE;
        }
        
        // Upload des fichiers
        foreach (['copie_piece', 'declaration_vol', 'document_complementaire'] as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store("pertes/{$field}", 'public');
            }
        }
        
        DB::beginTransaction();
        try {
            $perte = Perte::create($validated);
            
            DB::commit();
            
            // ✅ Redirection vers la liste des déclarations
            return redirect()->route('perte.index')
                ->with('success', '✅ Déclaration soumise avec succès ! Votre numéro de déclaration est : ' . $perte->numero_declaration);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement : ' . $e->getMessage());
        }
    }

    /**
     * Afficher les détails d'une déclaration (pour le citoyen).
     * Enrichi avec les informations de correspondance si un document trouvé est associé.
     */
    public function show($id)
    {
        $perte = Perte::with(['documentTrouve', 'user', 'verifier'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
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
     * ✅ Gestion de la date d'expiration
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
            'date_delivrance'=> 'nullable|date|before_or_equal:today',
            'date_expiration'=> [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value) {
                        $dateDelivrance = $request->input('date_delivrance');
                        $today = Carbon::today();
                        $dateExpiration = Carbon::parse($value);
                        
                        if ($dateExpiration->lt($today)) {
                            $fail('La date d\'expiration doit être dans le futur.');
                        }
                        
                        if ($dateDelivrance && $dateExpiration->lte(Carbon::parse($dateDelivrance))) {
                            $fail('La date d\'expiration doit être postérieure à la date de délivrance.');
                        }
                    }
                },
            ],
            'autorite_delivrance' => 'nullable|string|max:255',
            'date_perte'     => [
                'required',
                'date',
                'before_or_equal:today',
                function ($attribute, $value, $fail) use ($request) {
                    $dateDelivrance = $request->input('date_delivrance');
                    if ($dateDelivrance && $value < $dateDelivrance) {
                        $fail('La date de perte doit être postérieure ou égale à la date de délivrance.');
                    }
                },
            ],
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
            'date_delivrance.before_or_equal' => 'La date de délivrance ne peut pas être dans le futur.',
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
        
        // ✅ Mise à jour du statut_verification en fonction de la date d'expiration
        if (!empty($validated['date_expiration'])) {
            $validated['statut_verification'] = Perte::STATUT_VERIFICATION_AUTO;
        } else {
            $validated['statut_verification'] = Perte::STATUT_VERIFICATION_MANUELLE;
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
     * Télécharger l'attestation de déclaration (PDF)
     */
    public function download($id)
    {
        $perte = Perte::where('user_id', Auth::id())->findOrFail($id);
        
        $pdf = Pdf::loadView('perte.attestation', compact('perte'));
        return $pdf->download('attestation_declaration_' . $perte->id . '.pdf');
    }

    /**
     * Afficher le récépissé HTML (pour aperçu)
     */
    public function viewRecuHtml($id)
    {
        $perte = Perte::where('user_id', Auth::id())->findOrFail($id);
        return view('perte.recu_html', compact('perte'));
    }
}