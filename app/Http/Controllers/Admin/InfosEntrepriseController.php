<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Entreprise;

class InfosEntrepriseController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $entreprise = null;
        $evenementsCount = 0;
        $ateliersCount = 0;
        $evenements = collect();

        if ($user->role === 'super_admin') {
            $id = request('entreprise');
            if ($id) {
                $entreprise = Entreprise::with(['evenements.ateliers'])->find($id);
            }
        } else {
            $collab = $user->collaborateurs()->first();
            if ($collab) {
                $entreprise = Entreprise::with(['evenements.ateliers'])->find($collab->id_entreprise);
            }
        }

        if ($entreprise) {
            $evenements = $entreprise->evenements;
            $evenementsCount = $evenements->count();
            $ateliersCount = $evenements->reduce(function ($carry, $evenement) {
                return $carry + $evenement->ateliers->count();
            }, 0);
        }

        return view('admin.entreprises.infos', compact('entreprise', 'evenementsCount', 'ateliersCount', 'evenements'));
    }
}