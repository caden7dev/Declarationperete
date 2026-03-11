<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perte;
use App\Models\TypePiece;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerteController extends Controller
{
    /**
     * Display a listing of user's declarations.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Perte::where('user_id', $user->id);
        
        // Filtre par statut
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
        
        // Statistiques
        $totalDeclarations = Perte::where('user_id', $user->id)->count();
        $enAttenteCount = Perte::where('user_id', $user->id)->where('statut', 'en_attente')->count();
        $valideeCount = Perte::where('user_id', $user->id)->where('statut', 'validee')->count();
        $rejeteeCount = Perte::where('user_id', $user->id)->where('statut', 'rejetee')->count();
        
        return view('perte.index', compact('pertes', 'totalDeclarations', 'enAttenteCount', 'valideeCount', 'rejeteeCount'));
    }

    /**
     * Show the form for creating a new declaration.
     */
    public function create()
    {
        $user = Auth::user();
        $typesPieces = TypePiece::all();
        
        return view('perte.create', compact('user', 'typesPieces'));
    }

    /**
     * Store a newly created declaration in storage.
     */
    public function store(Request $request)
    {
        // Validation avec les règles combinées
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'contact' => 'required|string|max:30',
            'email' => 'required|email|max:255',
            'type_piece' => 'required|string|max:100',
            'numero_piece' => 'nullable|string|max:100',
            'date_delivrance' => 'nullable|date',
            'autorite_delivrance' => 'nullable|string|max:255',
            'date_perte' => 'required|date|before_or_equal:today',
            'lieu_perte' => 'required|string|max:255', // Gardé required car c'est important
            'circonstances' => 'nullable|string',
            'copie_piece' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'declaration_vol' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
            'copie_piece.mimes' => 'La copie de la pièce doit être un fichier PDF, JPG ou PNG.',
            'copie_piece.max' => 'La copie de la pièce ne doit pas dépasser 2 Mo.',
            'declaration_vol.mimes' => 'La déclaration de vol doit être un fichier PDF, JPG ou PNG.',
            'declaration_vol.max' => 'La déclaration de vol ne doit pas dépasser 2 Mo.',
            'document_complementaire.mimes' => 'Le document complémentaire doit être un fichier PDF, JPG ou PNG.',
            'document_complementaire.max' => 'Le document complémentaire ne doit pas dépasser 2 Mo.',
        ]);
        
        // Ajouter l'ID utilisateur et le statut initial
        $validated['user_id'] = Auth::id();
        $validated['statut'] = 'en_attente';
        $validated['date_declaration'] = now();

        // Upload des fichiers
        if ($request->hasFile('copie_piece')) {
            $validated['copie_piece'] = $request->file('copie_piece')->store('pertes/copie_piece', 'public');
        }
        
        if ($request->hasFile('declaration_vol')) {
            $validated['declaration_vol'] = $request->file('declaration_vol')->store('pertes/declaration_vol', 'public');
        }
        
        if ($request->hasFile('document_complementaire')) {
            $validated['document_complementaire'] = $request->file('document_complementaire')->store('pertes/documents', 'public');
        }

        // Créer la déclaration
        $perte = Perte::create($validated);
        
        return redirect()->route('perte.index')->with('success', '✅ Déclaration soumise avec succès ! Votre numéro de déclaration est : ' . $perte->numero_declaration);
    }

    /**
     * Display the specified declaration.
     */
    public function show($id)
    {
        $perte = Perte::where('user_id', Auth::id())->findOrFail($id);
        return view('perte.show', compact('perte'));
    }

    /**
     * Show the form for editing the specified declaration.
     */
    public function edit($id)
    {
        $perte = Perte::where('user_id', Auth::id())
                      ->where('statut', 'en_attente')
                      ->findOrFail($id);
        
        $typesPieces = TypePiece::all();
        
        return view('perte.edit', compact('perte', 'typesPieces'));
    }

    /**
     * Update the specified declaration in storage.
     */
    public function update(Request $request, $id)
    {
        $perte = Perte::where('user_id', Auth::id())
                      ->where('statut', 'en_attente')
                      ->findOrFail($id);
        
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'contact' => 'required|string|max:30',
            'email' => 'required|email|max:255',
            'type_piece' => 'required|string|max:100',
            'numero_piece' => 'nullable|string|max:100',
            'date_delivrance' => 'nullable|date',
            'autorite_delivrance' => 'nullable|string|max:255',
            'date_perte' => 'required|date|before_or_equal:today',
            'lieu_perte' => 'required|string|max:255',
            'circonstances' => 'nullable|string',
            'copie_piece' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'declaration_vol' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_complementaire' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        
        // Upload des nouveaux fichiers (et suppression des anciens)
        foreach (['copie_piece', 'declaration_vol', 'document_complementaire'] as $field) {
            if ($request->hasFile($field)) {
                // Supprimer l'ancien fichier si existe
                if ($perte->$field) {
                    Storage::disk('public')->delete($perte->$field);
                }
                // Uploader le nouveau
                $validated[$field] = $request->file($field)->store("pertes/{$field}", 'public');
            }
        }
        
        $perte->update($validated);
        
        return redirect()->route('perte.index')->with('success', '✅ Déclaration mise à jour avec succès.');
    }

    /**
     * Remove the specified declaration from storage.
     */
    public function destroy($id)
    {
        $perte = Perte::where('user_id', Auth::id())
                      ->where('statut', 'en_attente')
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
}