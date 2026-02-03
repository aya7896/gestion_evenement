<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Entreprise;

class EquipeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $collab = $user->collaborateurs()->first();
        $collaborateurs = $collab ? $collab->entreprise->collaborateurs()->with('user')->get() : collect();
        return view('admin.equipe.index', compact('collaborateurs'));
    }
}
