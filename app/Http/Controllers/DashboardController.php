<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entreprise;
use App\Models\Collaborateur;
use App\Models\Evenement;
use App\Models\Atelier;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->role === 'super_admin';
        $isAdminEntreprise = false;
        $entreprise = null;

        if ($user->role === 'collaborateur') {
            // Récupérer le premier collaborateur rattaché à l'utilisateur
            $collab = $user->collaborateurs()->first();
            if ($collab) {
                $isAdminEntreprise = $collab->role === 'admin_entreprise';
                $entreprise = $collab->entreprise;
            }
        }

        $stats = $this->getStats($isSuperAdmin, $entreprise ? $entreprise->id_entreprise : null);

        // Fetch upcoming events (next 5 by start date)
        $upcomingEventsQuery = Evenement::where('date_heure_debut', '>=', now())
            ->orderBy('date_heure_debut', 'asc');
        
        if (!$isSuperAdmin && $entreprise) {
            $upcomingEventsQuery->where('id_entreprise', $entreprise->id_entreprise);
        }
        
        $upcomingEvents = $upcomingEventsQuery->take(5)->get();

        // Fetch recent activities (last 5 events by creation date)
        $recentActivitiesQuery = Evenement::orderBy('created_at', 'desc');
        
        if (!$isSuperAdmin && $entreprise) {
            $recentActivitiesQuery->where('id_entreprise', $entreprise->id_entreprise);
        }
        
        $recentActivities = $recentActivitiesQuery->take(5)->get();

        return view('dashboard', compact(
            'isSuperAdmin', 
            'isAdminEntreprise', 
            'entreprise', 
            'stats', 
            'upcomingEvents', 
            'recentActivities'
        ));
    }
    
    private function getStats(bool $isSuperAdmin, ?int $entrepriseId): array
    {
        $query = Evenement::query();

        if (!$isSuperAdmin && $entrepriseId) {
            $query->where('id_entreprise', $entrepriseId);
        }

        // Total des Ã©vÃ©nements
        $totalEvents = (clone $query)->count();
        
        // Ã‰vÃ©nements du dernier mois
        $lastMonth = now()->subMonth();
        $eventsLastMonth = (clone $query)->where('created_at', '>=', $lastMonth)->count();
        $eventsGrowth = $totalEvents > 0 ? round(($eventsLastMonth / $totalEvents) * 100) : 0;

        // Total des participants (basÃ© sur la capacitÃ©)
        $totalParticipants = (clone $query)->sum('capacite'); 
        $participantsLastMonth = (clone $query)
            ->where('created_at', '>=', $lastMonth)
            ->sum('capacite');
        $participantsGrowth = $totalParticipants > 0 
            ? round(($participantsLastMonth / $totalParticipants) * 100) 
            : 0;

        // Statistiques d'aujourd'hui
        $today = now()->toDateString();
        $todayEvents = (clone $query)->whereDate('date_heure_debut', $today)->count();
        
        $yesterday = now()->subDay()->toDateString();
        $yesterdayEvents = (clone $query)->whereDate('date_heure_debut', $yesterday)->count();
        $todayEventsGrowth = $yesterdayEvents > 0 
            ? round((($todayEvents - $yesterdayEvents) / $yesterdayEvents) * 100) 
            : 0;

        // Inscriptions du jour
        $todayRegistrations = (clone $query)->whereDate('created_at', $today)->count();
        $yesterdayRegistrations = (clone $query)->whereDate('created_at', $yesterday)->count();
        $todayRegistrationsGrowth = $yesterdayRegistrations > 0 
            ? round((($todayRegistrations - $yesterdayRegistrations) / $yesterdayRegistrations) * 100) 
            : 0;

        // Statistiques de la semaine
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $weekEvents = (clone $query)
            ->whereBetween('date_heure_debut', [$startOfWeek, $endOfWeek])
            ->count();
        $weekParticipations = (clone $query)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('capacite');

        // Taux de remplissage
        $totalCapacity = (clone $query)->sum('capacite');
        $fillRate = $totalCapacity > 0 
            ? round(($totalParticipants / $totalCapacity) * 100) 
            : 0;

        // DonnÃ©es pour les graphiques
        $revenueChartData = $this->getMonthlyEventsData($query, 6);
        $revenueChartMonths = $revenueChartData['months'];
        $revenueChartValues = $revenueChartData['values'];

        // Distribution des statuts (exemple)
        $statusDistribution = [
            'Ã€ venir' => (clone $query)->where('date_heure_debut', '>', now())->count(),
            'En cours' => (clone $query)
                ->where('date_heure_debut', '<=', now())
                ->where('date_heure_fin', '>=', now())
                ->count(),
            'TerminÃ©' => (clone $query)->where('date_heure_fin', '<', now())->count(),
        ];

        $stats = [
            'total_events' => $totalEvents,
            'events_growth' => $eventsGrowth,
            'total_participants' => $totalParticipants,
            'participants_growth' => $participantsGrowth,
            'today_events' => $todayEvents,
            'today_events_growth' => $todayEventsGrowth,
            'today_registrations' => $todayRegistrations,
            'today_registrations_growth' => $todayRegistrationsGrowth,
            'week_events' => $weekEvents,
            'week_participations' => $weekParticipations,
            'fill_rate' => min($fillRate, 100), // Cap Ã  100%
            'revenue_chart_data' => $revenueChartValues,
            'revenue_chart_months' => $revenueChartMonths,
            'status_distribution_values' => array_values($statusDistribution),
            'status_distribution_labels' => array_keys($statusDistribution),
        ];

        // Statistiques spÃ©cifiques selon le rÃ´le
        if ($isSuperAdmin) {
            $stats['entreprises'] = Entreprise::count();
            $stats['collaborateurs'] = Collaborateur::count();
            $stats['evenements'] = $totalEvents;
            $stats['ateliers'] = Atelier::count();
        } else if ($entrepriseId) {
            $stats['entreprises'] = 1;
            $stats['collaborateurs'] = Collaborateur::where('id_entreprise', $entrepriseId)->count();
            $stats['evenements'] = $totalEvents;
            $stats['ateliers'] = Atelier::whereHas('evenement', function($q) use ($entrepriseId) {
                $q->where('id_entreprise', $entrepriseId);
            })->count();
        }

        return $stats;
    }

    /**
     * Obtenir les donnÃ©es mensuelles des Ã©vÃ©nements
     */
    private function getMonthlyEventsData($query, int $months = 6): array
    {
        $data = [
            'months' => [],
            'values' => []
        ];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $data['months'][] = $date->locale('fr')->isoFormat('MMM');
            $data['values'][] = (clone $query)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
        }

        return $data;
    }
}