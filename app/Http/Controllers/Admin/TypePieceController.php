<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypePiece;
use Illuminate\Http\Request;

class TypePieceController extends Controller
{
    /**
     * Afficher la liste des types de pièces
     */
    public function index(Request $request)
    {
        $query = TypePiece::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('categorie', 'like', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $typesPieces = $query->orderBy('created_at', 'desc')->get();

        return view('admin.types-pieces.index', compact('typesPieces'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.types-pieces.create');
    }

    /**
     * Enregistrer un nouveau type de pièce
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:types_pieces,code',
            'categorie' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'delai_traitement' => 'nullable|integer|min:1',
            'prix' => 'nullable|numeric|min:0',
            'documents_requis' => 'nullable|string',
        ]);

        // Définir is_active par défaut à true si non fourni
        $validated['is_active'] = $request->input('is_active', true);

        TypePiece::create($validated);

        return redirect()
            ->route('admin.types-pieces.index')
            ->with('success', 'Type de pièce créé avec succès !');
    }

    /**
     * Afficher les détails d'un type de pièce
     */
    public function show(TypePiece $typesPiece)
    {
        return view('admin.types-pieces.show', compact('typesPiece'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(TypePiece $typesPiece)
    {
        return view('admin.types-pieces.edit', compact('typesPiece'));
    }

    /**
     * Mettre à jour un type de pièce
     */
    public function update(Request $request, TypePiece $typesPiece)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:types_pieces,code,' . $typesPiece->id,
            'categorie' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'delai_traitement' => 'nullable|integer|min:1',
            'prix' => 'nullable|numeric|min:0',
            'documents_requis' => 'nullable|string',
        ]);

        $typesPiece->update($validated);

        return redirect()
            ->route('admin.types-pieces.index')
            ->with('success', 'Type de pièce modifié avec succès !');
    }

    /**
     * Supprimer un type de pièce
     */
    public function destroy(TypePiece $typesPiece)
    {
        // Vérifier si le type de pièce est utilisé dans des déclarations
        // Si oui, empêcher la suppression
        // if ($typesPiece->pertes()->count() > 0) {
        //     return redirect()
        //         ->route('admin.types-pieces.index')
        //         ->with('error', 'Impossible de supprimer ce type de pièce car il est utilisé dans des déclarations.');
        // }

        $typesPiece->delete();

        return redirect()
            ->route('admin.types-pieces.index')
            ->with('success', 'Type de pièce supprimé avec succès !');
    }
}