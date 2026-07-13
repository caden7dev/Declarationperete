<?php

namespace App\Http\Controllers;

use App\Models\DocumentTrouve;
use App\Models\Perte;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentTrouveController extends Controller
{
    /**
     * Formulaire de déclaration (accessible à tous)
     */
    public function create()
    {
        $user = Auth::user();
        return view('documents-trouves.create', compact('user'));
    }

    /**
     * Enregistrer une nouvelle déclaration
     * ✅ REDIRECTION VERS LE DASHBOARD CITOYEN
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            // Informations du déclarant
            'nom_declarant' => 'required|string|max:255',
            'prenom_declarant' => 'required|string|max:255',
            'email_declarant' => 'required|email|max:255',
            'telephone_declarant' => 'required|string|max:30',
            
            // Informations du document
            'type_document' => 'required|string|max:100',
            'nom_sur_document' => 'nullable|string|max:255',
            'prenom_sur_document' => 'nullable|string|max:255',
            'numero_document' => 'nullable|string|max:100',
            
            // Circonstances
            'date_decouverte' => 'required|date|before_or_equal:today',
            'lieu_decouverte' => 'required|string|max:255',
            'description' => 'nullable|string',
            'circonstances' => 'nullable|string',
            
            // Photo
            'photo_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'nom_declarant.required' => 'Votre nom est obligatoire.',
            'prenom_declarant.required' => 'Votre prénom est obligatoire.',
            'email_declarant.required' => 'Votre email est obligatoire.',
            'email_declarant.email' => 'L\'email n\'est pas valide.',
            'telephone_declarant.required' => 'Votre téléphone est obligatoire.',
            'type_document.required' => 'Le type de document est obligatoire.',
            'date_decouverte.required' => 'La date de découverte est obligatoire.',
            'date_decouverte.before_or_equal' => 'La date ne peut pas être dans le futur.',
            'lieu_decouverte.required' => 'Le lieu de découverte est obligatoire.',
        ]);

        // Ajouter l'ID utilisateur si connecté
        if (Auth::check()) {
            $validated['user_id'] = Auth::id();
        }

        // Upload de la photo
        if ($request->hasFile('photo_document')) {
            $validated['photo_document'] = $request->file('photo_document')->store('documents-trouves/photos', 'public');
        }

        // Générer un numéro de déclaration unique
        $validated['numero_declaration'] = 'DT-' . date('Y') . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);

        // Créer la déclaration
        $documentTrouve = DocumentTrouve::create($validated);

        // 🔥 Chercher des correspondances et notifier AUTOMATIQUEMENT
        $correspondances = $this->chercherEtNotifierCorrespondances($documentTrouve);

        // 🔔 Notification à TOUS les agents
        $this->notifierAgents($documentTrouve);

        // Message de succès
        $message = '✅ Merci ! Votre signalement a bien été enregistré. Un agent va vérifier et contactera le propriétaire si le document correspond à une déclaration de perte.';
        
        if ($correspondances->count() > 0) {
            $message .= ' 🎉 ' . $correspondances->count() . ' propriétaire(s) potentiel(s) ont été notifié(s) !';
        }

        // ✅ REDIRECTION VERS LE DASHBOARD CITOYEN
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('success', $message);
        } else {
            return redirect()->route('home')->with('success', $message . ' Merci pour votre geste citoyen ! 🙏');
        }
    }

    /**
     * 🔥 Chercher correspondances et envoyer notifications AUTOMATIQUEMENT
     */
    private function chercherEtNotifierCorrespondances($documentTrouve)
    {
        // Chercher les déclarations de perte correspondantes
        $query = Perte::where('statut', 'validee')
            ->where('type_piece', $documentTrouve->type_document);

        // Filtrer par numéro si disponible (critère fort)
        if ($documentTrouve->numero_document) {
            $query->where(function($q) use ($documentTrouve) {
                $q->where('numero_piece', 'LIKE', '%' . $documentTrouve->numero_document . '%');
            });
        }

        // Filtrer par nom/prénom si disponible (critère moyen)
        if ($documentTrouve->nom_sur_document || $documentTrouve->prenom_sur_document) {
            $query->where(function($q) use ($documentTrouve) {
                if ($documentTrouve->nom_sur_document) {
                    $q->where('last_name', 'LIKE', '%' . $documentTrouve->nom_sur_document . '%');
                }
                if ($documentTrouve->prenom_sur_document) {
                    $q->orWhere('first_name', 'LIKE', '%' . $documentTrouve->prenom_sur_document . '%');
                }
            });
        }

        // Filtrer par date (± 60 jours de la date de perte)
        $dateDebut = \Carbon\Carbon::parse($documentTrouve->date_decouverte)->subDays(60);
        $dateFin = \Carbon\Carbon::parse($documentTrouve->date_decouverte)->addDays(60);
        
        $query->whereBetween('date_perte', [$dateDebut, $dateFin]);

        $correspondances = $query->with('user')->get();

        // 🔥 ENVOYER NOTIFICATIONS AUTOMATIQUES à chaque propriétaire potentiel
        foreach ($correspondances as $perte) {
            Notification::create([
                'user_id' => $perte->user_id,
                'type' => 'document_trouve',
                'title' => '🎉 Votre document a peut-être été trouvé !',
                'content' => "Un {$documentTrouve->type_document} correspondant à votre déclaration a été trouvé le " . 
                             \Carbon\Carbon::parse($documentTrouve->date_decouverte)->format('d/m/Y') . 
                             " à {$documentTrouve->lieu_decouverte}. " . 
                             ($documentTrouve->numero_document ? "Numéro : {$documentTrouve->numero_document}. " : "") .
                             "Un agent va vérifier et vous contactera si c'est bien votre document.",
                'perte_id' => $perte->id,
                'is_read' => false,
                'icon' => '🎉',
                'action_url' => route('perte.show', $perte->id)
            ]);
        }

        return $correspondances;
    }

    /**
     * 🔔 Notifier TOUS les agents d'un nouveau document trouvé
     */
    private function notifierAgents($documentTrouve)
    {
        $agents = User::where('role', 'agent')->get();
        
        foreach ($agents as $agent) {
            Notification::create([
                'user_id' => $agent->id,
                'type' => 'system',
                'title' => '📦 Nouveau document trouvé signalé',
                'content' => "Un document ({$documentTrouve->type_document}) a été signalé par {$documentTrouve->nom_declarant} {$documentTrouve->prenom_declarant}.",
                'action_url' => route('agent.documents-trouves.show', $documentTrouve->id),
                'icon' => '📦',
                'is_read' => false,
                'color' => 'info'
            ]);
        }
    }

    /**
     * ⚠️ SUPPRIMÉE : La page show pour les citoyens n'est plus nécessaire
     * Les agents utilisent agent.documents-trouves.show
     * Les citoyens sont redirigés vers le dashboard
     */

    /**
     * Chercher correspondances (sans notification) - utilisée par l'agent
     */
    private function chercherCorrespondances($documentTrouve)
    {
        $query = Perte::where('statut', 'validee')
            ->where('type_piece', $documentTrouve->type_document);

        if ($documentTrouve->numero_document) {
            $query->where('numero_piece', 'LIKE', '%' . $documentTrouve->numero_document . '%');
        }

        if ($documentTrouve->nom_sur_document) {
            $query->where('last_name', 'LIKE', '%' . $documentTrouve->nom_sur_document . '%');
        }

        if ($documentTrouve->prenom_sur_document) {
            $query->where('first_name', 'LIKE', '%' . $documentTrouve->prenom_sur_document . '%');
        }

        $dateDebut = \Carbon\Carbon::parse($documentTrouve->date_decouverte)->subDays(60);
        $dateFin = \Carbon\Carbon::parse($documentTrouve->date_decouverte)->addDays(60);
        
        $query->whereBetween('date_perte', [$dateDebut, $dateFin]);

        return $query->with('user')->get();
    }

    /**
     * Matcher un document trouvé avec une perte (côté agent)
     */
    public function matcher(Request $request, $id)
    {
        $documentTrouve = DocumentTrouve::findOrFail($id);
        $perteId = $request->input('perte_id');

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

        // 🔥 NOTIFICATION COMPLÈTE AU PROPRIÉTAIRE avec coordonnées du trouveur
        Notification::create([
            'user_id' => $perte->user_id,
            'type' => 'document_retrouve_confirme',
            'title' => '🎉 Votre document a été officiellement retrouvé !',
            'content' => "Excellente nouvelle ! Votre {$perte->type_piece} a été confirmé retrouvé.\n\n" .
                         "📞 Contact du trouveur :\n" .
                         "Nom : {$documentTrouve->nom_declarant} {$documentTrouve->prenom_declarant}\n" .
                         "Email : {$documentTrouve->email_declarant}\n" .
                         "Téléphone : {$documentTrouve->telephone_declarant}\n\n" .
                         "Vous pouvez le contacter directement pour récupérer votre document.",
            'perte_id' => $perte->id,
            'is_read' => false,
            'icon' => '🎉',
            'action_url' => route('perte.show', $perte->id)
        ]);

        return redirect()->route('agent.documents-trouves.show', $id)
            ->with('success', '✅ Document matché avec succès ! Le propriétaire a été notifié avec vos coordonnées.');
    }

    /**
     * Marquer comme restitué (côté agent)
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

        // Notification finale au propriétaire
        if ($documentTrouve->perte_matchee_id) {
            $perte = $documentTrouve->perteMatchee;
            Notification::create([
                'user_id' => $perte->user_id,
                'type' => 'restitution',
                'title' => '✅ Document officiellement restitué',
                'content' => "Votre {$perte->type_piece} a été officiellement marqué comme restitué. Merci d'avoir utilisé e-Déclaration TG ! 🇹🇬",
                'perte_id' => $perte->id,
                'is_read' => false,
                'icon' => '✅',
                'action_url' => route('perte.show', $perte->id)
            ]);
        }

        return back()->with('success', '✅ Document marqué comme restitué !');
    }

    /**
     * ✅ Marquer la notification d'un document trouvé comme lue
     */
    public function marquerLu($id)
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

    /**
     * Recherche publique de documents trouvés
     */
    public function search(Request $request)
    {
        $query = DocumentTrouve::where('statut', '!=', 'archive');

        if ($request->has('type') && $request->type != '') {
            $query->where('type_document', $request->type);
        }

        if ($request->has('numero') && $request->numero != '') {
            $query->where('numero_document', 'LIKE', '%' . $request->numero . '%');
        }

        if ($request->has('nom') && $request->nom != '') {
            $query->where('nom_sur_document', 'LIKE', '%' . $request->nom . '%');
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('documents-trouves.search', compact('documents'));
    }
}