<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Atelier;
use App\Models\Evenement;
use App\Models\Entreprise;
use Illuminate\Support\Facades\Auth;

class AtelierController extends Controller
{
    /**
     * Display workshops organized by company and event for superadmin
     */
    public function indexOrganized()
    {
        // Ensure only superadmin can access this
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Accès réservé au super administrateur');
        }

        // Get all companies with their events and workshops
        $entreprises = Entreprise::with(['evenements.ateliers'])
            ->orderBy('nom')
            ->get();

        // Organize data: Company -> Event -> Workshops
        $organizedData = $entreprises->map(function ($entreprise) {
            $eventsWithWorkshops = $entreprise->evenements->map(function ($evenement) {
                return [
                    'evenement' => $evenement,
                    'total_ateliers' => $evenement->ateliers->count(),
                    'ateliers' => $evenement->ateliers->sortBy('date')
                ];
            });

            return [
                'entreprise' => $entreprise,
                'total_evenements' => $entreprise->evenements->count(),
                'total_ateliers' => $entreprise->evenements->sum(fn($e) => $e->ateliers->count()),
                'events_with_workshops' => $eventsWithWorkshops
            ];
        });

        return view('admin.ateliers.organized', compact('organizedData'));
        
    }
}