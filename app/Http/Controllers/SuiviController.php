<?php

namespace App\Http\Controllers;

use App\Models\Perte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuiviController extends Controller
{
    /**
     * Rechercher une déclaration par son numéro et code de suivi
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rechercher(Request $request)
    {
        // 1. Validation des champs
        $request->validate([
            'numero_declaration' => 'required|string|max:50',
            'code_suivi' => 'required|string|size:6',
        ], [
            'numero_declaration.required' => 'Le numéro de déclaration est obligatoire.',
            'code_suivi.required' => 'Le code de suivi est obligatoire.',
            'code_suivi.size' => 'Le code de suivi doit contenir exactement 6 caractères.',
        ]);

        try {
            // 2. Recherche de la déclaration
            $perte = Perte::where('numero_declaration', trim($request->numero_declaration))
                          ->where('code_suivi', strtoupper(trim($request->code_suivi)))
                          ->first();

            // 3. Si non trouvée → retour avec erreur
            if (!$perte) {
                return redirect()->route('home')
                    ->with('error', '❌ Numéro de déclaration ou code de suivi incorrect. Veuillez réessayer.')
                    ->withInput();
            }

            // 4. Si trouvée → retour avec les informations
            return redirect()->route('home')
                ->with('suivi_resultat', $perte)
                ->with('success', '✅ Déclaration trouvée !');

        } catch (\Exception $e) {
            // 5. En cas d'erreur
            Log::error('Erreur lors du suivi : ' . $e->getMessage());
            
            return redirect()->route('home')
                ->with('error', '❌ Une erreur est survenue lors de la recherche. Veuillez réessayer.')
                ->withInput();
        }
    }

    /**
     * Afficher les détails d'une déclaration (optionnel)
     * Si vous voulez une page dédiée au suivi
     * 
     * @param string $numero_declaration
     * @param string $code_suivi
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($numero_declaration, $code_suivi)
    {
        $perte = Perte::where('numero_declaration', $numero_declaration)
                      ->where('code_suivi', strtoupper($code_suivi))
                      ->first();

        if (!$perte) {
            return redirect()->route('home')
                ->with('error', '❌ Déclaration non trouvée.');
        }

        return view('suivi.detail', compact('perte'));
    }

    /**
     * Réinitialiser le code de suivi (optionnel)
     * Si le citoyen a perdu son code
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function regenererCode(Request $request)
    {
        $request->validate([
            'numero_declaration' => 'required|string|max:50',
            'email' => 'required|email',
        ]);

        $perte = Perte::where('numero_declaration', $request->numero_declaration)
                      ->where('email', $request->email)
                      ->first();

        if (!$perte) {
            return redirect()->back()
                ->with('error', '❌ Aucune déclaration trouvée avec ces informations.');
        }

        // Générer un nouveau code
        $nouveauCode = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        $perte->code_suivi = $nouveauCode;
        $perte->save();

        // Envoyer le nouveau code par email
        try {
            Mail::send('emails.suivi', [
                'perte' => $perte,
                'code_suivi' => $nouveauCode,
                'nom' => $perte->first_name . ' ' . $perte->last_name,
                'numero_declaration' => $perte->numero_declaration,
            ], function ($message) use ($perte) {
                $message->to($perte->email, $perte->first_name . ' ' . $perte->last_name)
                        ->subject('🔑 Votre nouveau code de suivi - e-Déclaration TG');
            });
        } catch (\Exception $e) {
            Log::error('Erreur envoi email nouveau code : ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', '✅ Un nouveau code de suivi a été envoyé à votre adresse email.');
    }
}