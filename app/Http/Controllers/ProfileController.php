<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
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
        $totalDeclarations = Perte::where('user_id', $user->id)->count();
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
        
        // 🔥 PRIORITÉ À LA SESSION PUIS AUX PRÉFÉRENCES
        $locale = session('locale') ?? $preferences['language'] ?? 'fr';
        App::setLocale($locale);
        
        return view('profile.index', compact(
            'user', 
            'totalDeclarations',
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
        $preferences = $user->preferences ?? [];
        
        return [
            'dark_mode' => $user->theme === 'dark',
            'email_notifications' => $preferences['email_notifications'] ?? true,
            'sms_notifications' => $preferences['sms_notifications'] ?? false,
            'language' => $preferences['language'] ?? 'fr',
            'timezone' => $preferences['timezone'] ?? 'Africa/Lome',
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
     * Mettre à jour les préférences (AJAX)
     */
   /**
 * Mettre à jour les préférences (AJAX)
 */
public function updatePreferences(Request $request)
{
    try {
        // 🔥 CORRECTION : Traiter les champs correctement
        $darkMode = $request->input('dark_mode');
        $emailNotif = $request->input('email_notifications');
        
        $request->validate([
            'dark_mode' => 'nullable|in:0,1,true,false',
            'email_notifications' => 'nullable|in:0,1,true,false',
            'language' => 'nullable|string|in:fr,en',
            'timezone' => 'nullable|string',
        ]);

        $user = Auth::user();
        $preferences = $user->preferences ?? [];

        // 🔥 CORRECTION : Convertir correctement les valeurs
        if ($request->has('dark_mode')) {
            $isDark = filter_var($request->input('dark_mode'), FILTER_VALIDATE_BOOLEAN);
            $preferences['dark_mode'] = $isDark;
            $user->theme = $isDark ? 'dark' : 'light';
        }
        
        if ($request->has('email_notifications')) {
            $preferences['email_notifications'] = filter_var($request->input('email_notifications'), FILTER_VALIDATE_BOOLEAN);
        }
        
        if ($request->has('language')) {
            $language = $request->input('language');
            $preferences['language'] = $language;
            
            // 🔥 FORCER LA SESSION AVANT DE RÉPONDRE
            session(['locale' => $language]);
            App::setLocale($language);
        }
        
        if ($request->has('timezone')) {
            $preferences['timezone'] = $request->input('timezone');
        }

        $user->preferences = $preferences;
        $user->save();

        // 🔥 SESSION USER PREFERENCES
        session(['user_preferences' => $preferences]);

        return response()->json([
            'success' => true,
            'message' => 'Préférences mises à jour avec succès',
            'preferences' => $preferences,
            'language' => $preferences['language'] ?? 'fr',
            'locale' => app()->getLocale(),
            'session_locale' => session('locale')
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Données invalides',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Erreur updatePreferences: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Changer la langue uniquement (sans AJAX - méthode alternative)
     */
   
public function changeLanguage(Request $request)
{
    $request->validate([
        'locale' => 'required|string|in:fr,en',
    ]);
 
    $locale = $request->input('locale');
 
    // 1. Sauvegarder en session (lu par SetLocale à chaque requête)
    session(['locale' => $locale]);
 
    // 2. Appliquer immédiatement pour cette requête
    App::setLocale($locale);
 
    // 3. Sauvegarder en base de données (persistance après reconnexion)
    if (Auth::check()) {
        $user = Auth::user();
        $preferences = $user->preferences ?? [];
        $preferences['language'] = $locale;
        $user->preferences = $preferences;
        $user->save();
    }
 
    // 4. Retourner sur la même page avec confirmation
    return redirect()->back()->with('success',
        $locale === 'fr'
            ? '✅ Langue changée en Français'
            : '✅ Language changed to English'
    );
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
        try {
            $user = Auth::user();
            
            $isDark = $request->boolean('dark_mode');
            $newTheme = $isDark ? 'dark' : 'light';
            
            $user->theme = $newTheme;
            
            // Mettre à jour les préférences également
            $preferences = $user->preferences ?? [];
            $preferences['dark_mode'] = $isDark;
            $user->preferences = $preferences;
            
            $user->save();
            
            session(['user_preferences' => $preferences]);

            return response()->json([
                'success' => true,
                'theme' => $newTheme,
                'dark_mode' => $isDark
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur toggleDarkMode: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de thème'
            ], 500);
        }
    }
}