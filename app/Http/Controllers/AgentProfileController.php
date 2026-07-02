<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AgentProfileController extends Controller
{
    /**
     * Constructeur avec middleware agent
     */
    public function __construct()
    {
        $this->middleware(['auth', 'agent']);
    }

    /**
     * Afficher la page de profil agent
     */
    public function index()
    {
        $user = Auth::user();
        return view('agent.profile', compact('user'));
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
            'address' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->update($validator->validated());

        return redirect()->route('agent.profile')->with('success', 'Profil mis à jour avec succès');
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
            return redirect()->back()->withErrors($validator);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Mot de passe actuel incorrect');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('agent.profile')->with('success', 'Mot de passe modifié avec succès');
    }

    /**
     * Mettre à jour les préférences (AJAX)
     */
    public function updatePreferences(Request $request)
    {
        try {
            $user = Auth::user();
            $preferences = $user->preferences ?? [];
            
            if ($request->has('dark_mode')) {
                $preferences['dark_mode'] = $request->boolean('dark_mode');
                $user->theme = $request->boolean('dark_mode') ? 'dark' : 'light';
            }
            
            if ($request->has('email_notifications')) {
                $preferences['email_notifications'] = $request->boolean('email_notifications');
            }
            
            if ($request->has('language')) {
                $language = $request->input('language');
                $preferences['language'] = $language;
                session(['locale' => $language]);
                app()->setLocale($language);
            }
            
            $user->preferences = $preferences;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Préférences mises à jour'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}