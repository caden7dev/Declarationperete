<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\PerteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentDashboardController;
use App\Http\Controllers\Admin\UserController; 
use App\Http\Controllers\Admin\TypePieceController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\PerteController as AdminPerteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DocumentTrouveController;
use App\Http\Controllers\AgentProfileController;
use App\Http\Controllers\CitizenMessageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Page d'aide publique
Route::get('/aide', function () {
    return view('help.public');
})->name('help.public');

// ===== ROUTES PUBLIQUES POUR DOCUMENTS TROUVÉS =====
Route::prefix('documents-trouves')->name('documents-trouves.')->group(function () {
    Route::get('/declarer', [DocumentTrouveController::class, 'create'])->name('create');
    Route::post('/declarer', [DocumentTrouveController::class, 'store'])->name('store');
    Route::get('/recherche', [DocumentTrouveController::class, 'search'])->name('search');
});

// ===== ROUTES D'AUTHENTIFICATION (Mot de passe oublié) =====
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', function (Request $request, $token) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->email,
    ]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->password = Hash::make($password);
            $user->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.update');


/*
|--------------------------------------------------------------------------
| Routes protégées (authentification requise)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ===== ROUTES DE DÉCLARATIONS DE PERTE (CRUD COMPLET) =====
    Route::get('/perte/nouvelle', [PerteController::class, 'create'])->name('perte.create');
    Route::post('/perte', [PerteController::class, 'store'])->name('perte.store');
    Route::get('/mes-declarations', [PerteController::class, 'index'])->name('perte.index');
    Route::get('/perte/{id}', [PerteController::class, 'show'])->name('perte.show');
    
    // ROUTES POUR L'ÉDITION
    Route::get('/perte/{id}/edit', [PerteController::class, 'edit'])->name('perte.edit');
    Route::put('/perte/{id}', [PerteController::class, 'update'])->name('perte.update');
    Route::delete('/perte/{id}', [PerteController::class, 'destroy'])->name('perte.destroy');
    Route::get('/perte/{id}/download', [PerteController::class, 'download'])->name('perte.download');
    
    // Nouvelles routes
    Route::get('/perte/{id}/download-recu', [PerteController::class, 'downloadRecu'])->name('perte.download-recu');
    Route::get('/suivi/{id}', [PerteController::class, 'showSuivi'])->name('perte.suivi-detail');
    Route::get('/perte/{id}/aperçu-html', [PerteController::class, 'viewRecuHtml'])->name('perte.recu.html');
    
    // Route alternative
    Route::get('/pertes', [PerteController::class, 'index'])->name('pertes.index');
    
    // ✅ NOUVELLE ROUTE : Suivi des déclarations (liste)
    Route::get('/suivi', [PerteController::class, 'suivi'])->name('perte.suivi');
    
    // ===== ROUTES PROFIL/PARAMÈTRES =====
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::put('/email', [ProfileController::class, 'updateEmail'])->name('email');
        Route::delete('/delete', [ProfileController::class, 'destroy'])->name('delete');
        Route::post('/preferences', [ProfileController::class, 'updatePreferences'])->name('preferences');
        Route::post('/toggle-dark-mode', [ProfileController::class, 'toggleDarkMode'])->name('toggle-dark-mode');
        Route::post('/language', [ProfileController::class, 'changeLanguage'])->name('language');
    });
    
    // ===== ROUTES NOTIFICATIONS =====
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/latest', [NotificationController::class, 'getLatest'])->name('latest');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');

        // Routes API pour les notifications (dans le groupe auth)
        Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
        Route::post('/api/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    });

    // ===== ROUTES AIDE =====
    Route::get('/help', [HelpController::class, 'index'])->name('help.index');
    Route::post('/help/send', [HelpController::class, 'sendMessage'])->name('help.send');

    // ===== ROUTES POUR LES DOCUMENTS TROUVÉS (utilisateur connecté) =====
    Route::get('/mes-documents-trouves/{id}', [DocumentTrouveController::class, 'show'])
        ->name('documents-trouves.show');
});

Route::post('/perte/{id}/confirm-recuperation', [PerteController::class, 'confirmRecuperation'])
    ->name('perte.confirm-recuperation');


/*
|--------------------------------------------------------------------------
| Routes Citoyen
|--------------------------------------------------------------------------
*/
Route::prefix('citoyen')->name('citoyen.')->middleware(['auth'])->group(function () {
    Route::get('/messages', [CitizenMessageController::class, 'index'])->name('messages');
    Route::get('/agents/list', [CitizenMessageController::class, 'getAgentsList'])->name('agents.list');
    Route::get('/messages/{agentId}/history', [CitizenMessageController::class, 'getMessages'])->name('messages.history');
    Route::post('/messages/envoyer', [CitizenMessageController::class, 'sendMessageAjax'])->name('messages.envoyer');
    Route::get('/messages/unread-count', [CitizenMessageController::class, 'unreadCount'])->name('messages.unread-count');
    Route::post('/recuperation/{perteId}', [PerteController::class, 'signalerRecuperation'])->name('signaler-recuperation');
});


/*
|--------------------------------------------------------------------------
| Routes Agent
|--------------------------------------------------------------------------
*/
Route::prefix('agent')->name('agent.')->middleware(['auth', 'agent'])->group(function () {
    // Dashboard agent
    Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des déclarations de perte
    Route::get('/perte/{id}', [AgentDashboardController::class, 'show'])->name('perte.show');
    Route::post('/perte/{id}/valider', [AgentDashboardController::class, 'valider'])->name('perte.valider');
    Route::post('/perte/{id}/rejeter', [AgentDashboardController::class, 'rejeter'])->name('perte.rejeter');
    Route::post('/perte/{id}/annuler', [AgentDashboardController::class, 'annuler'])->name('perte.annuler');
    
    Route::post('/perte/{id}/pret-recuperation', [AgentDashboardController::class, 'marquerPretRecuperation'])->name('perte.pret-recuperation');
    
    // ============================================================
    // 🚀 ROUTES DE PRISE EN CHARGE
    // ============================================================
    Route::post('/perte/{id}/prendre', [AgentDashboardController::class, 'prendreEnCharge'])
        ->name('perte.prendre');
    
    // Route pour la recherche de correspondances
    Route::get('/perte/{id}/recherche', [AgentDashboardController::class, 'rechercheCorrespondances'])
        ->name('perte.recherche');
    
    // Autres routes de traitement
    Route::post('/perte/{id}/leguer', [AgentDashboardController::class, 'leguer'])->name('perte.leguer');
    Route::post('/perte/{id}/liberer', [AgentDashboardController::class, 'liberer'])->name('perte.liberer');
    
    Route::post('/perte/{id}/associer', [AgentDashboardController::class, 'associerDocument'])->name('perte.associer');
    Route::post('/perte/{id}/non-retrouve', [AgentDashboardController::class, 'declarerNonRetrouve'])->name('perte.non-retrouve');
    
    Route::post('/perte/{id}/simuler-pret', [AgentDashboardController::class, 'simulerPretRecuperation'])->name('perte.simuler-pret');

    // Gestion des documents trouvés (agent)
    Route::get('/documents-trouves', [AgentDashboardController::class, 'documentsTrouves'])->name('documents-trouves.index');
    Route::get('/documents-trouves/{id}', [AgentDashboardController::class, 'showDocumentTrouve'])->name('documents-trouves.show');
    Route::post('/documents-trouves/{id}/matcher', [AgentDashboardController::class, 'matcherDocumentTrouve'])->name('documents-trouves.matcher');
    
    // ✅ Route pour marquer la notification comme lue (sans matcher)
    Route::post('/documents-trouves/{id}/marquer-lu', [AgentDashboardController::class, 'marquerLuDocumentTrouve'])
        ->name('documents-trouves.marquer-lu');
    
    Route::post('/documents-trouves/{id}/restituer', [AgentDashboardController::class, 'marquerRestitue'])->name('documents-trouves.restituer');
    Route::delete('/documents-trouves/{id}', [AgentDashboardController::class, 'supprimerDocumentTrouve'])->name('documents-trouves.destroy');
    Route::get('/documents-trouves/exporter/stats', [AgentDashboardController::class, 'exporterStatsDocumentsTrouves'])->name('documents-trouves.exporter-stats');
    
    // Route AJAX pour l'aperçu rapide d'un document trouvé
    Route::get('/documents-trouves/{document}/preview', [AgentDashboardController::class, 'previewDocumentTrouve'])->name('documents-trouves.preview');

    // Profil Agent
    Route::get('/profile', [AgentProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [AgentProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AgentProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/preferences', [AgentProfileController::class, 'updatePreferences'])->name('profile.preferences');

    // Actions diverses
    Route::get('/traiter-suivant', [AgentDashboardController::class, 'traiterSuivant'])->name('traiter-suivant');
    Route::post('/validation-groupee', [AgentDashboardController::class, 'validationGroupee'])->name('validation-groupee');
    Route::get('/statistiques', [AgentDashboardController::class, 'statistiques'])->name('statistiques');
    Route::get('/rapport-pdf', [AgentDashboardController::class, 'rapportPDF'])->name('rapport-pdf');
    Route::get('/rapports', [AgentDashboardController::class, 'rapports'])->name('rapports');

    // Messagerie Agent
    Route::get('/messages', [AgentDashboardController::class, 'messages'])->name('messages');
    Route::post('/messages/envoyer', [AgentDashboardController::class, 'envoyerMessage'])->name('messages.envoyer');
    Route::get('/messages/{userId}/history', [AgentDashboardController::class, 'messageHistory'])->name('messages.history');
    Route::get('/citizens/list', [AgentDashboardController::class, 'getCitizensList'])->name('citizens.list');
    Route::get('/messages/unread-count', [AgentDashboardController::class, 'unreadCount'])->name('messages.unread-count');
    
    // Notifications
    Route::get('/notifications', [AgentDashboardController::class, 'notifications'])->name('notifications');
    /* En cas de conflit de nommage avec la méthode restituer de documents trouvés */
    Route::post('/perte/{id}/restitution', [AgentDashboardController::class, 'restituer'])->name('perte.restitution');
    
    // Confirmation de récupération
    Route::post('/perte/{id}/confirmer-recuperation', [AgentDashboardController::class, 'confirmerRecuperation'])->name('perte.confirmer-recuperation');
});


/*
|--------------------------------------------------------------------------
| Routes Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard admin
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Gestion des utilisateurs - Route resource (gère TOUTES les routes CRUD)
    // ✅ CORRECTION : Une seule route resource suffit, pas besoin des routes individuelles
    Route::resource('users', UserController::class);
    
    // Profil Admin
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    
    // Types de pièces - Route resource
    Route::resource('types-pieces', TypePieceController::class);
    
    // Statistiques
    Route::get('/statistiques', [StatisticsController::class, 'index'])->name('stats.index');
    
    // Rôles
    Route::get('/roles', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles.index');
    Route::put('/roles/{user}/update', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('roles.update');
});

// ============================================================
// ROUTES GLOBALES
// ============================================================

// Route pour changer la langue
Route::get('/set-lang/{locale}', function($locale, Request $request) {
    session(['locale' => $locale]);
    App::setLocale($locale);
    
    if (auth()->check()) {
        $preferences = auth()->user()->preferences ?? [];
        $preferences['language'] = $locale;
        auth()->user()->preferences = $preferences;
        auth()->user()->save();
    }
    
    return redirect()->back()->with('success', "Langue changée en " . $locale);
})->name('set-lang');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['fr', 'en'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
        if (auth()->check()) {
            auth()->user()->update(['preferred_language' => $locale]);
        }
    }
    return redirect()->back();
})->name('lang');

// Route de test pour les rôles
Route::get('/test-role', function () {
    return auth()->user()->role ?? 'Non connecté';
});

// Routes d'authentification
require __DIR__.'/auth.php';