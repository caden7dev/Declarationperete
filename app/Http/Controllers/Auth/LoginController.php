<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = $request->email;

        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'contact';

        $credentials = [
            $fieldType => $loginInput,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // ✅ Redirection selon le rôle
            return redirect($this->redirectTo(Auth::user()));
        }

        throw ValidationException::withMessages([
            'email' => ['Email ou numéro de téléphone incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Vous avez été déconnecté.');
    }

    /**
     * Retourne l'URL de redirection selon le rôle de l'utilisateur.
     */
    protected function redirectTo($user)
    {
        if ($user->role === 'admin') {
            return route('admin.dashboard');
        } elseif ($user->role === 'agent') {
            return route('agent.dashboard');
        } else {
            return route('dashboard');
        }
    }

    /**
     * La méthode authenticated est appelée après une authentification réussie.
     * Elle permet de stocker la préférence de langue et éventuellement le thème.
     */
    protected function authenticated(Request $request, $user)
    {
        // ✅ Appliquer la langue préférée si elle existe en base
        if (!empty($user->preferred_language)) {
            session(['locale' => $user->preferred_language]);
            app()->setLocale($user->preferred_language);
        } else {
            // Langue par défaut (français)
            session(['locale' => 'fr']);
            app()->setLocale('fr');
        }

        // ✅ Optionnel : appliquer le thème sombre si l'utilisateur l'a enregistré
        if (isset($user->dark_mode) && $user->dark_mode) {
            session(['dark_mode' => true]);
        } else {
            session(['dark_mode' => false]);
        }
    }
}