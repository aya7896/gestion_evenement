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
        // Si superadmin, on récupère l'id de l'entreprise depuis la requête
        if ($user->role === 'super_admin') {
            $id = request('entreprise');
            $entreprise = $id ? \App\Models\Entreprise::find($id) : null;
        } else {
            $collab = $user->collaborateurs()->first();
            $entreprise = $collab ? $collab->entreprise : null;
        }
        return view('admin.entreprises.infos', compact('entreprise'));
    }
}
