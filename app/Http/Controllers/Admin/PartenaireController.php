<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partenaire;
use Illuminate\Http\Request;

class PartenaireController extends Controller
{
    public function index()
    {
        $partenaires = Partenaire::orderBy('ordre')->orderBy('nom')->paginate(20);
        return view('admin.partenaires.index', compact('partenaires'));
    }

    public function create()
    {
        $types = Partenaire::TYPES;
        return view('admin.partenaires.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:gold,silver,bronze,media,institutionnel,autre',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'site_web' => 'nullable|url|max:255',
            'ordre' => 'nullable|integer|min:0',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('partenaires/logos', 'public');
        }

        $validated['actif'] = true;
        $validated['ordre'] = $validated['ordre'] ?? 0;
        Partenaire::create($validated);

        return redirect()->route('admin.partenaires.index')->with('success', 'Sponsor ajouté avec succès.');
    }
}

