<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Speaker;
use App\Models\Atelier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpeakerController extends Controller
{
    /**
     * Vérifie si l'utilisateur est super admin
     */
    private function isSuperAdmin()
    {
        return Auth::user()->role === 'super_admin';
    }

    /**
     * Récupère l'entreprise de l'admin connecté
     */
    private function getUserEntrepriseId()
    {
        if ($this->isSuperAdmin()) {
            return null;
        }
        $collab = Auth::user()->collaborateurs()->first();
        return $collab ? $collab->id_entreprise : null;
    }

    /**
     * Display a listing of speakers.
     */
    public function index()
    {
        $speakers = Speaker::with('ateliers')->orderBy('nom')->get();
        return view('admin.speakers.index', compact('speakers'));
    }

    /**
     * Show the form for creating a new speaker.
     */
    public function create()
    {
        return view('admin.speakers.create');
    }

    /**
     * Store a newly created speaker.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'bio' => 'nullable|string',
            'company' => 'nullable|string|max:255',
            'poste' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:500',
            'telephone' => 'nullable|string|max:50',
            'photo' => 'nullable|image|max:2048',
            'linkedin' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('speakers/photos', 'public');
        }

        // Build social_links array
        $validated['social_links'] = array_filter([
            'linkedin' => $request->input('linkedin'),
            'twitter' => $request->input('twitter'),
            'website' => $request->input('website'),
        ]);

        $validated['actif'] = $request->boolean('actif', true);

        // Remove individual social fields before create
        unset($validated['linkedin'], $validated['twitter'], $validated['website']);

        Speaker::create($validated);

        return redirect()->route('admin.speakers.index')
            ->with('success', 'Speaker créé avec succès.');
    }

    /**
     * Display the specified speaker.
     */
    public function show(Speaker $speaker)
    {
        $speaker->load('ateliers.evenement');
        return view('admin.speakers.show', compact('speaker'));
    }

    /**
     * Show the form for editing the specified speaker.
     */
    public function edit(Speaker $speaker)
    {
        return view('admin.speakers.edit', compact('speaker'));
    }

    /**
     * Update the specified speaker.
     */
    public function update(Request $request, Speaker $speaker)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'bio' => 'nullable|string',
            'company' => 'nullable|string|max:255',
            'poste' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:500',
            'telephone' => 'nullable|string|max:50',
            'photo' => 'nullable|image|max:2048',
            'linkedin' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($speaker->photo) {
                Storage::disk('public')->delete($speaker->photo);
            }
            $validated['photo'] = $request->file('photo')->store('speakers/photos', 'public');
        }

        // Build social_links array
        $validated['social_links'] = array_filter([
            'linkedin' => $request->input('linkedin'),
            'twitter' => $request->input('twitter'),
            'website' => $request->input('website'),
        ]);

        $validated['actif'] = $request->boolean('actif', true);

        // Remove individual social fields before update
        unset($validated['linkedin'], $validated['twitter'], $validated['website']);

        $speaker->update($validated);

        return redirect()->route('admin.speakers.index')
            ->with('success', 'Speaker mis à jour avec succès.');
    }

    /**
     * Remove the specified speaker.
     */
    public function destroy(Speaker $speaker)
    {
        // Delete photo
        if ($speaker->photo) {
            Storage::disk('public')->delete($speaker->photo);
        }

        $speaker->delete();

        return redirect()->route('admin.speakers.index')
            ->with('success', 'Speaker supprimé avec succès.');
    }

    /**
     * Attach speaker to an atelier.
     */
    public function attachToAtelier(Request $request, Atelier $atelier)
    {
        $validated = $request->validate([
            'id_speaker' => 'required|exists:speakers,id_speaker',
            'role' => 'nullable|string|max:100',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $atelier->speakers()->attach($validated['id_speaker'], [
            'role' => $validated['role'] ?? 'speaker',
            'ordre' => $validated['ordre'] ?? 0,
        ]);

        return redirect()->back()->with('success', 'Speaker ajouté à l\'atelier.');
    }

    /**
     * Detach speaker from an atelier.
     */
    public function detachFromAtelier(Atelier $atelier, Speaker $speaker)
    {
        $atelier->speakers()->detach($speaker->id_speaker);
        return redirect()->back()->with('success', 'Speaker retiré de l\'atelier.');
    }
}
