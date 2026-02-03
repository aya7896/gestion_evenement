<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\AtelierController;
use App\Http\Controllers\Admin\EntrepriseController;
use App\Http\Controllers\Admin\CollaborateurController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profil utilisateur
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -------------------------
// -------------------------
// Landing page publique événement (partage)
// -------------------------
Route::get('/e/{evenement}', [App\Http\Controllers\EvenementController::class, 'publicLanding'])
    ->name('public.evenement.landing');
// Formulaire d'inscription public à l'événement
Route::post('/e/{evenement}/inscription', [App\Http\Controllers\EvenementController::class, 'publicInscription'])
    ->name('public.evenement.inscription');
// Routes Evenements / Ateliers 
// -------------------------
Route::middleware(['auth'])->group(function () {

    // CRUD Evenements (accessible aux collaborateurs uniquement)
    Route::resource('evenements', EvenementController::class);

    // Download or generate plaquette (PDF)
    Route::get('evenements/{evenement}/plaquette', [EvenementController::class, 'downloadPlaquette'])
        ->name('evenements.plaquette.download');

    // CRUD Ateliers par evenement
    Route::prefix('evenements/{evenement}')->name('evenements.')->group(function () {
        Route::resource('ateliers', AtelierController::class);
    });

    // Routes directes pour les ateliers (pour la navigation)
    Route::get('ateliers', [AtelierController::class, 'index'])->name('ateliers.index');
    
    Route::get('ateliers/create', [AtelierController::class, 'create'])->name('ateliers.create');
    Route::post('ateliers', [AtelierController::class, 'store'])->name('ateliers.store');


});

// -------------------------
// Routes Back-office Admin
// -------------------------
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Mon équipe (admin_entreprise)
    Route::get('equipe', [\App\Http\Controllers\Admin\EquipeController::class, 'index'])->name('equipe.index')->middleware('checkrole:admin_entreprise');

    // Infos entreprise (admin_entreprise)
    Route::get('entreprises/infos', [\App\Http\Controllers\Admin\InfosEntrepriseController::class, 'show'])->name('entreprises.infos')->middleware('checkrole:admin_entreprise');

    // Entreprises - Super Admin et Admin Entreprise
    Route::resource('entreprises', EntrepriseController::class)
        ->middleware('checkrole:super_admin,admin_entreprise');

    // Collaborateurs - Super Admin et Admin Entreprise
    Route::resource('collaborateurs', CollaborateurController::class)
        ->middleware('checkrole:super_admin,admin_entreprise,collaborateur');

    // Événements - Super Admin et Admin Entreprise
    Route::resource('evenements', \App\Http\Controllers\Admin\EvenementController::class)
        ->middleware('checkrole:super_admin,admin_entreprise');

    // Super Admin specific routes for event and workshop organization
    Route::middleware('checkrole:super_admin')->group(function () {
        // Events grouped by company
        Route::get('evenements-entreprises', [\App\Http\Controllers\Admin\EvenementController::class, 'indexByCompany'])
            ->name('evenements.by-company');
        
        // Workshops grouped by event and company
        Route::get('ateliers-organises', [\App\Http\Controllers\Admin\AtelierController::class, 'indexOrganized'])
            ->name('ateliers.organized');
    });
});

// -------------------------
// Auth routes (Breeze)
require __DIR__.'/auth.php';