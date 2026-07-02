<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Réduit la longueur par défaut pour les indexes (nécessaire pour MySQL)
        Schema::defaultStringLength(191);
        
        // Partager les préférences de l'utilisateur avec toutes les vues
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $preferences = $user->preferences ?? [];
                
                // S'assurer que les préférences par défaut existent
                $defaultPreferences = [
                    'dark_mode' => $user->theme === 'dark',
                    'email_notifications' => $preferences['email_notifications'] ?? true,
                    'language' => $preferences['language'] ?? 'fr',
                    'timezone' => $preferences['timezone'] ?? 'Africa/Lome',
                ];
                
                // Fusionner les préférences avec les valeurs par défaut
                $finalPreferences = array_merge($defaultPreferences, $preferences);
                
                $view->with('userPreferences', $finalPreferences);
                $view->with('userTheme', $user->theme ?? 'light');
            } else {
                $view->with('userPreferences', [
                    'dark_mode' => false,
                    'email_notifications' => true,
                    'language' => 'fr',
                    'timezone' => 'Africa/Lome',
                ]);
                $view->with('userTheme', 'light');

                if (Session::has('locale')) {
        App::setLocale(Session::get('locale'));
    }
            }
        });
    }
}