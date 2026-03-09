<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Perte;

class ProfileController extends Controller
{
    /**
     * Afficher la page de profil
     */
   public function index()
{
    $user = Auth::user();
    
    // Statistiques
    $totalDeclarations = Perte::where('user_id', $user->id)->count(); // ← OK
    $enAttente = Perte::where('user_id', $user->id)->where('statut', 'en_attente')->count();
    $validees = Perte::where('user_id', $user->id)->where('statut', 'validee')->count();
    $rejetees = Perte::where('user_id', $user->id)->where('statut', 'rejetee')->count();
    
    // Dernières déclarations pour les notifications
    $dernieresDeclarations = Perte::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    // Récupérer les préférences de l'utilisateur
    $preferences = $this->getUserPreferences($user);
    
    // ✅ On passe TOUTES les variables à la vue
    return view('profile.index', compact(
        'user', 
        'totalDeclarations',   // ← Bien présent
        'enAttente', 
        'validees', 
        'rejetees', 
        'dernieresDeclarations',
        'preferences'
    ));
}
    

    /**
     * Récupérer les préférences de l'utilisateur
     */
    private function getUserPreferences($user)
    {
        // Utiliser le champ theme de l'utilisateur (dark/light)
        // et d'autres préférences si elles sont stockées en base
        // Pour l'instant, on utilise le champ theme et des valeurs par défaut pour les autres
        return [
            'dark_mode' => $user->theme === 'dark', // true si dark
            'email_notifications' => true, // à adapter si vous avez un champ
            'sms_notifications' => false,
            'language' => 'fr',
            'timezone' => 'Africa/Lome',
        ];
    }

    /**
     * Mettre à jour le profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:500',
            'nationality' => 'nullable|string|max:100',
            'gender' => 'nullable|in:M,F',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update($validator->validated());

        return redirect()->route('profile.index')
            ->with('success', '✅ Profil mis à jour avec succès !');
    }

    /**
     * Changer le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', '❌ Le mot de passe actuel est incorrect');
        }

        if (Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->with('error', '❌ Le nouveau mot de passe doit être différent de l\'ancien');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.index')
            ->with('success', '✅ Mot de passe modifié avec succès !');
    }

    /**
     * Changer l'email
     */
    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->with('error', '❌ Mot de passe incorrect');
        }

        if ($request->email === $user->email) {
            return redirect()->back()
                ->with('error', '❌ Le nouvel email doit être différent de l\'email actuel');
        }

        $user->email = $request->email;
        $user->email_verified_at = null;
        $user->save();

        return redirect()->route('profile.index')
            ->with('success', '✅ Email modifié avec succès !');
    }

    /**
     * Mettre à jour les préférences (y compris le thème)
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        // Mettre à jour le thème si présent
        if ($request->has('dark_mode')) {
            $user->theme = $request->boolean('dark_mode') ? 'dark' : 'light';
        }

        // Vous pouvez ajouter d'autres préférences ici
        // Par exemple, si vous avez des champs pour notifications, etc.
        // Pour l'instant, on ne les stocke pas en base, on peut les laisser en session
        // ou les stocker aussi dans un champ JSON.

        $user->save();

        // Optionnel : stocker les autres préférences en session
        session([
            'email_notifications' => $request->boolean('email_notifications'),
            'sms_notifications' => $request->boolean('sms_notifications'),
            'language' => $request->input('language', 'fr'),
            'timezone' => $request->input('timezone', 'Africa/Lome'),
        ]);

        return redirect()->route('profile.index')
            ->with('success', '✅ Préférences enregistrées avec succès !');
    }

    /**
     * Supprimer le compte
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->with('error', '❌ Mot de passe incorrect');
        }

        $enAttente = Perte::where('user_id', $user->id)
            ->where('statut', 'en_attente')
            ->exists();

        if ($enAttente) {
            return redirect()->back()
                ->with('error', '❌ Vous ne pouvez pas supprimer votre compte car vous avez des déclarations en attente');
        }

        Auth::logout();
        $user->delete();

        return redirect()->route('home')
            ->with('success', '✅ Votre compte a été supprimé définitivement');
    }

    /**
     * API pour basculer le mode sombre (AJAX)
     */
    public function toggleDarkMode(Request $request)
    {
        $user = Auth::user();
        
        // Bascule le thème
        $newTheme = $user->theme === 'dark' ? 'light' : 'dark';
        $user->theme = $newTheme;
        $user->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'theme' => $newTheme
            ]);
        }

        return redirect()->back();
    }
}