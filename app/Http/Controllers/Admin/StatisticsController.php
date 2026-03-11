<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perte;
use App\Models\TypePiece;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Afficher les statistiques et rapports
     */
    public function index(Request $request)
    {
        // Période par défaut : 30 derniers jours
        $dateDebut = $request->input('date_debut', now()->subDays(30)->format('Y-m-d'));
        $dateFin = $request->input('date_fin', now()->format('Y-m-d'));
        $typePieceId = $request->input('type_piece_id');

        // Query de base pour les déclarations
        $query = Perte::whereBetween('created_at', [$dateDebut, $dateFin]);

        if ($typePieceId) {
            $query->where('type_piece_id', $typePieceId);
        }

        // Statistiques principales
        $stats = [
            'total_declarations' => $query->count(),
            'en_attente' => (clone $query)->where('statut', 'en_attente')->count(),
            'validees' => (clone $query)->where('statut', 'validee')->count(),
            'rejetees' => (clone $query)->where('statut', 'rejetee')->count(),
            'new_this_month' => Perte::whereMonth('created_at', now()->month)
                                     ->whereYear('created_at', now()->year)
                                     ->count(),
        ];

        // Tous les types de pièces pour le filtre
        $typesPieces = TypePiece::where('is_active', true)->orderBy('nom')->get();

        // Statistiques par type de pièce
        $statsByType = $this->getStatsByType($dateDebut, $dateFin);

        // Données pour les graphiques
        $chartData = [
            'evolution' => $this->getEvolutionData($dateDebut, $dateFin),
            'status' => $this->getStatusData($dateDebut, $dateFin),
            'types' => $this->getTypesData($dateDebut, $dateFin),
            'users' => $this->getUsersData($dateDebut, $dateFin),
            'weekday' => $this->getWeekdayData($dateDebut, $dateFin),
            'hourly' => $this->getHourlyData($dateDebut, $dateFin),
        ];

        // Statistiques mensuelles (12 derniers mois)
        $monthlyStats = $this->getMonthlyStats();

        return view('admin.stats.index', compact(
            'stats',
            'typesPieces',
            'statsByType',
            'chartData',
            'monthlyStats'
        ));
    }

    /**
     * Statistiques par type de pièce
     */
    private function getStatsByType($dateDebut, $dateFin)
    {
        $stats = DB::table('pertes')
            ->join('types_pieces', 'pertes.type_piece_id', '=', 'types_pieces.id')
            ->whereBetween('pertes.created_at', [$dateDebut, $dateFin])
            ->select(
                'types_pieces.nom as type',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN pertes.statut = "en_attente" THEN 1 ELSE 0 END) as en_attente'),
                DB::raw('SUM(CASE WHEN pertes.statut = "validee" THEN 1 ELSE 0 END) as validees'),
                DB::raw('SUM(CASE WHEN pertes.statut = "rejetee" THEN 1 ELSE 0 END) as rejetees'),
                DB::raw('AVG(DATEDIFF(pertes.updated_at, pertes.created_at)) as delai_moyen')
            )
            ->groupBy('types_pieces.id', 'types_pieces.nom')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($item) {
                $item->taux_validation = $item->total > 0 
                    ? ($item->validees / $item->total) * 100 
                    : 0;
                $item->delai_moyen = round($item->delai_moyen ?? 0, 1);
                return $item;
            });

        return $stats;
    }

    /**
     * Données pour le graphique d'évolution
     */
    private function getEvolutionData($dateDebut, $dateFin)
    {
        $evolution = Perte::whereBetween('created_at', [$dateDebut, $dateFin])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $evolution->pluck('date')->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            })->toArray(),
            'data' => $evolution->pluck('count')->toArray()
        ];
    }

    /**
     * Données pour le graphique de statut
     */
    private function getStatusData($dateDebut, $dateFin)
    {
        $status = Perte::whereBetween('created_at', [$dateDebut, $dateFin])
            ->select('statut', DB::raw('COUNT(*) as count'))
            ->groupBy('statut')
            ->get();

        $statusLabels = [
            'en_attente' => 'En Attente',
            'validee' => 'Validées',
            'rejetee' => 'Rejetées'
        ];

        return [
            'labels' => $status->pluck('statut')->map(fn($s) => $statusLabels[$s] ?? $s)->toArray(),
            'data' => $status->pluck('count')->toArray()
        ];
    }

    /**
     * Données pour le graphique des types de pièces
     */
    private function getTypesData($dateDebut, $dateFin)
    {
        $types = Perte::whereBetween('pertes.created_at', [$dateDebut, $dateFin])
            ->join('types_pieces', 'pertes.type_piece_id', '=', 'types_pieces.id')
            ->select('types_pieces.nom', DB::raw('COUNT(*) as count'))
            ->groupBy('types_pieces.id', 'types_pieces.nom')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return [
            'labels' => $types->pluck('nom')->toArray(),
            'data' => $types->pluck('count')->toArray()
        ];
    }

    /**
     * Données pour le graphique des utilisateurs actifs
     */
    private function getUsersData($dateDebut, $dateFin)
    {
        $users = Perte::whereBetween('pertes.created_at', [$dateDebut, $dateFin])
            ->join('users', 'pertes.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('COUNT(*) as count'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return [
            'labels' => $users->pluck('name')->toArray(),
            'data' => $users->pluck('count')->toArray()
        ];
    }

    /**
     * Données par jour de la semaine
     */
    private function getWeekdayData($dateDebut, $dateFin)
    {
        $weekdays = Perte::whereBetween('created_at', [$dateDebut, $dateFin])
            ->select(
                DB::raw('DAYNAME(created_at) as day'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('day')
            ->get();

        $daysOrder = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $dayTranslations = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche'
        ];

        $data = collect($daysOrder)->map(function($day) use ($weekdays, $dayTranslations) {
            $englishDay = array_search($day, $dayTranslations);
            $record = $weekdays->firstWhere('day', $englishDay);
            return $record ? $record->count : 0;
        });

        return [
            'labels' => $daysOrder,
            'data' => $data->toArray()
        ];
    }

    /**
     * Données par heure de la journée
     */
    private function getHourlyData($dateDebut, $dateFin)
    {
        $hourly = Perte::whereBetween('created_at', [$dateDebut, $dateFin])
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour')
            ->get();

        $hours = collect(range(0, 23));
        $data = $hours->map(function($hour) use ($hourly) {
            $record = $hourly->firstWhere('hour', $hour);
            return $record ? $record->count : 0;
        });

        return [
            'labels' => $hours->map(fn($h) => $h . 'h')->toArray(),
            'data' => $data->toArray()
        ];
    }

    /**
     * Statistiques mensuelles (12 derniers mois)
     */
    private function getMonthlyStats()
    {
        $months = [];
        $previousTotal = 0;

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $total = Perte::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $validees = Perte::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('statut', 'validee')
                ->count();
            $rejetees = Perte::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('statut', 'rejetee')
                ->count();

            $evolution = $previousTotal > 0 
                ? round((($total - $previousTotal) / $previousTotal) * 100, 1) 
                : 0;

            $months[] = [
                'mois' => $date->translatedFormat('F Y'),
                'total' => $total,
                'validees' => $validees,
                'rejetees' => $rejetees,
                'taux_validation' => $total > 0 ? ($validees / $total) * 100 : 0,
                'evolution' => $evolution
            ];

            $previousTotal = $total;
        }

        return $months;
    }
}