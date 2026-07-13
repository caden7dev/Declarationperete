<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentOfficiel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentOfficielController extends Controller
{
    /**
     * Afficher la liste des documents officiels
     */
    public function index(Request $request)
    {
        $query = DocumentOfficiel::query();
        
        // Filtrer par type de pièce
        if ($request->has('type_piece') && $request->type_piece != '') {
            $query->where('type_piece', $request->type_piece);
        }
        
        // Filtrer par statut
        if ($request->has('statut') && $request->statut != '') {
            switch ($request->statut) {
                case 'valide':
                    $query->valides();
                    break;
                case 'expire':
                    $query->expires();
                    break;
                case 'vole':
                    $query->voles();
                    break;
                case 'perdu':
                    $query->perdus();
                    break;
            }
        }
        
        // Recherche
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_document', 'LIKE', "%{$search}%")
                  ->orWhere('nom_complet', 'LIKE', "%{$search}%")
                  ->orWhere('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('type_piece', 'LIKE', "%{$search}%");
            });
        }
        
        $documents = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Statistiques
        $stats = [
            'total' => DocumentOfficiel::count(),
            'valides' => DocumentOfficiel::valides()->count(),
            'expires' => DocumentOfficiel::expires()->count(),
            'voles' => DocumentOfficiel::voles()->count(),
            'perdus' => DocumentOfficiel::perdus()->count(),
        ];
        
        $typesPieces = DocumentOfficiel::distinct('type_piece')->pluck('type_piece');
        
        return view('admin.documents-officiels.index', compact(
            'documents',
            'stats',
            'typesPieces',
            'request'
        ));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.documents-officiels.create');
    }

    /**
     * Enregistrer un nouveau document
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_piece' => 'required|string|max:100',
            'numero_document' => 'required|string|max:100|unique:documents_officiels',
            'nom_complet' => 'nullable|string|max:255',
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'date_delivrance' => 'nullable|date',
            'date_expiration' => 'nullable|date|after:date_delivrance',
            'autorite_delivrance' => 'nullable|string|max:255',
            'lieu_delivrance' => 'nullable|string|max:255',
            'est_valide' => 'boolean',
            'est_volé' => 'boolean',
            'est_perdu' => 'boolean',
            'est_suspendu' => 'boolean',
            'remarques' => 'nullable|string',
            'photo_url' => 'nullable|url',
        ]);

        $validated['est_valide'] = $request->has('est_valide') ? true : false;
        $validated['est_volé'] = $request->has('est_volé') ? true : false;
        $validated['est_perdu'] = $request->has('est_perdu') ? true : false;
        $validated['est_suspendu'] = $request->has('est_suspendu') ? true : false;
        
        // Si le document est volé ou perdu, il n'est plus valide
        if ($validated['est_volé'] || $validated['est_perdu'] || $validated['est_suspendu']) {
            $validated['est_valide'] = false;
        }

        $document = DocumentOfficiel::create($validated);

        return redirect()->route('admin.documents-officiels.index')
            ->with('success', '✅ Document officiel ajouté avec succès !');
    }

    /**
     * Afficher les détails d'un document
     */
    public function show($id)
    {
        $document = DocumentOfficiel::findOrFail($id);
        return view('admin.documents-officiels.show', compact('document'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $document = DocumentOfficiel::findOrFail($id);
        return view('admin.documents-officiels.edit', compact('document'));
    }

    /**
     * Mettre à jour un document
     */
    public function update(Request $request, $id)
    {
        $document = DocumentOfficiel::findOrFail($id);
        
        $validated = $request->validate([
            'type_piece' => 'required|string|max:100',
            'numero_document' => 'required|string|max:100|unique:documents_officiels,numero_document,' . $id,
            'nom_complet' => 'nullable|string|max:255',
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'date_delivrance' => 'nullable|date',
            'date_expiration' => 'nullable|date|after:date_delivrance',
            'autorite_delivrance' => 'nullable|string|max:255',
            'lieu_delivrance' => 'nullable|string|max:255',
            'est_valide' => 'boolean',
            'est_volé' => 'boolean',
            'est_perdu' => 'boolean',
            'est_suspendu' => 'boolean',
            'remarques' => 'nullable|string',
            'photo_url' => 'nullable|url',
        ]);

        $validated['est_valide'] = $request->has('est_valide') ? true : false;
        $validated['est_volé'] = $request->has('est_volé') ? true : false;
        $validated['est_perdu'] = $request->has('est_perdu') ? true : false;
        $validated['est_suspendu'] = $request->has('est_suspendu') ? true : false;
        
        if ($validated['est_volé'] || $validated['est_perdu'] || $validated['est_suspendu']) {
            $validated['est_valide'] = false;
        }

        $document->update($validated);

        return redirect()->route('admin.documents-officiels.index')
            ->with('success', '✅ Document officiel mis à jour avec succès !');
    }

    /**
     * Supprimer un document
     */
    public function destroy($id)
    {
        $document = DocumentOfficiel::findOrFail($id);
        $document->delete();

        return redirect()->route('admin.documents-officiels.index')
            ->with('success', '🗑️ Document officiel supprimé avec succès !');
    }

    /**
     * Vérifier un document spécifique (API interne)
     */
    public function verifier(Request $request)
    {
        $request->validate([
            'type_piece' => 'required|string',
            'numero_document' => 'required|string'
        ]);

        $resultat = DocumentOfficiel::verifierDocument(
            $request->type_piece,
            $request->numero_document
        );

        return response()->json($resultat);
    }

    /**
     * Marquer un document comme volé
     */
    public function marquerVole($id)
    {
        $document = DocumentOfficiel::findOrFail($id);
        $document->update([
            'est_volé' => true,
            'est_valide' => false,
        ]);

        return redirect()->back()
            ->with('success', '🚨 Document marqué comme VOLÉ !');
    }

    /**
     * Marquer un document comme perdu
     */
    public function marquerPerdu($id)
    {
        $document = DocumentOfficiel::findOrFail($id);
        $document->update([
            'est_perdu' => true,
            'est_valide' => false,
        ]);

        return redirect()->back()
            ->with('success', '❌ Document marqué comme PERDU !');
    }

    /**
     * Marquer un document comme retrouvé (réactiver)
     */
    public function marquerRetrouve($id)
    {
        $document = DocumentOfficiel::findOrFail($id);
        $document->update([
            'est_volé' => false,
            'est_perdu' => false,
            'est_valide' => true,
        ]);

        return redirect()->back()
            ->with('success', '✅ Document marqué comme RETROUVÉ et réactivé !');
    }

    /**
     * Statistiques pour le dashboard admin
     */
    public function statistiques()
    {
        $stats = [
            'total' => DocumentOfficiel::count(),
            'valides' => DocumentOfficiel::valides()->count(),
            'expires' => DocumentOfficiel::expires()->count(),
            'voles' => DocumentOfficiel::voles()->count(),
            'perdus' => DocumentOfficiel::perdus()->count(),
            'par_type' => DocumentOfficiel::selectRaw('type_piece, count(*) as total')
                ->groupBy('type_piece')
                ->get(),
            'ajouts_mois' => DocumentOfficiel::whereMonth('created_at', now()->month)->count(),
        ];

        return response()->json($stats);
    }
}