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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DocumentTrouveController;

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
    
    // Route alternative
    Route::get('/pertes', [PerteController::class, 'index'])->name('pertes.index');
    
    // ===== ROUTES PROFIL/PARAMÈTRES =====
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::put('/email', [ProfileController::class, 'updateEmail'])->name('email');
        Route::delete('/delete', [ProfileController::class, 'destroy'])->name('delete');
        Route::post('/preferences', [ProfileController::class, 'updatePreferences'])->name('preferences');
        Route::post('/toggle-dark-mode', [ProfileController::class, 'toggleDarkMode'])->name('toggle-dark-mode');
    });

    // ===== ROUTES NOTIFICATIONS =====
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/latest', [NotificationController::class, 'getLatest'])->name('latest');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // ===== ROUTES AIDE =====
    Route::get('/help', [HelpController::class, 'index'])->name('help.index');
    Route::post('/help/send', [HelpController::class, 'sendMessage'])->name('help.send');

    // ===== ROUTES POUR LES DOCUMENTS TROUVÉS (utilisateur connecté) =====
    Route::get('/mes-documents-trouves/{id}', [DocumentTrouveController::class, 'show'])
        ->name('documents-trouves.show');
   
       
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
    Route::get('/perte/{perte}', [AgentDashboardController::class, 'show'])->name('perte.show');
    Route::post('/perte/{perte}/valider', [AgentDashboardController::class, 'valider'])->name('perte.valider');
    Route::post('/perte/{perte}/rejeter', [AgentDashboardController::class, 'rejeter'])->name('perte.rejeter');
    Route::post('/perte/{perte}/annuler', [AgentDashboardController::class, 'annuler'])->name('perte.annuler');
    Route::post('/send-message', [AgentDashboardController::class, 'sendMessage'])->name('send-message');

    // ===== NOUVEAU : Gestion des documents trouvés (agent) =====
    Route::get('/documents-trouves', [AgentDashboardController::class, 'documentsTrouves'])->name('documents-trouves.index');
    Route::get('/documents-trouves/{id}', [AgentDashboardController::class, 'showDocumentTrouve'])->name('documents-trouves.show');
    Route::post('/documents-trouves/{id}/matcher', [AgentDashboardController::class, 'matcherDocumentTrouve'])->name('documents-trouves.matcher');
    Route::post('/documents-trouves/{id}/restituer', [AgentDashboardController::class, 'marquerRestitue'])->name('documents-trouves.restituer');
    Route::delete('/documents-trouves/{id}', [AgentDashboardController::class, 'supprimerDocumentTrouve'])
        ->name('documents-trouves.destroy');
    Route::get('/documents-trouves/exporter/stats', [AgentDashboardController::class, 'exporterStatsDocumentsTrouves'])
        ->name('documents-trouves.exporter-stats');
});

/*
|--------------------------------------------------------------------------
| Routes Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard admin
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Gestion des utilisateurs
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Gestion des types de pièces
    Route::get('/types-pieces', [TypePieceController::class, 'index'])->name('types-pieces.index');
    Route::get('/types-pieces/create', [TypePieceController::class, 'create'])->name('types-pieces.create');
    Route::post('/types-pieces', [TypePieceController::class, 'store'])->name('types-pieces.store');
    
    // Gestion des rôles (temporaire)
    Route::get('/roles', function () {
        return view('admin.roles.index');
    })->name('roles.index');
});

// Routes d'authentification (login, register, logout)
require __DIR__.'/auth.php';