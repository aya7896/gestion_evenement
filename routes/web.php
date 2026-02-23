<?php 
 
 use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\StorageProxyController;
 use App\Http\Controllers\EvenementController; 
 use App\Http\Controllers\AtelierController; 
 use App\Http\Controllers\InscriptionController; 
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

// Fallback to serve files from storage/app/public when public/storage symlink is missing
Route::get('storage/{path}', [StorageProxyController::class, 'show'])->where('path', '.*');
 
 // Dashboard 
 Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index']) 
     ->middleware(['auth', 'verified']) 
     ->name('dashboard'); 

// Analytics page for company admins (admin_entreprise)
Route::get('/analytics', [App\Http\Controllers\DashboardController::class, 'analytics'])
    ->middleware(['auth', 'checkrole:admin_entreprise'])
    ->name('analytics.index');
// Export analytics as CSV
Route::get('/analytics/export-csv', [App\Http\Controllers\DashboardController::class, 'exportCsv'])
    ->middleware(['auth', 'checkrole:admin_entreprise'])
    ->name('analytics.export');
 
 // Profil utilisateur 
 Route::middleware('auth')->group(function () { 
     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); 
     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); 
     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); 
 }); 
 
 // ------------------------- 
 // Landing page publique Ã©vÃ©nement (partage) 
 // ------------------------- 
 Route::get('/e/{evenement}', [EvenementController::class, 'publicLanding']) 
     ->name('public.evenement.landing');
 
 // Ateliers publics d'un Ã©vÃ©nement
 Route::get('/e/{evenement}/ateliers', [AtelierController::class, 'publicList'])
     ->name('public.evenement.ateliers');
 
 // TÃ©lÃ©chargement public de la plaquette de l'Ã©vÃ©nement
 Route::get('/e/{evenement}/plaquette', [EvenementController::class, 'publicDownloadPlaquette'])
     ->name('public.evenement.plaquette.download');
 
 // ------------------------- 
 // Routes d'Inscription Publiques 
 // ------------------------- 
 // Formulaire d'inscription 
 Route::get('/e/{evenement}/inscription', [InscriptionController::class, 'create']) 
     ->name('inscription.create'); 

 // OAuth social pour pre-remplir l'inscription
 Route::get('/e/{evenement}/inscription/social/{provider}/redirect', [InscriptionController::class, 'redirectToProvider'])
     ->name('inscription.social.redirect');
 Route::get('/inscription/social/{provider}/callback', [InscriptionController::class, 'handleProviderCallback'])
     ->name('inscription.social.callback');
 
 // Enregistrer l'inscription 
 Route::post('/e/{evenement}/inscription', [InscriptionController::class, 'store']) 
     ->name('inscription.store'); 

 // Verification inscription par code
 Route::get('/inscription/{inscription}/verify', [InscriptionController::class, 'showVerificationForm'])
     ->name('inscription.verify.form');
 Route::post('/inscription/{inscription}/verify', [InscriptionController::class, 'verifyCode'])
     ->name('inscription.verify.submit');
 Route::post('/inscription/{inscription}/verify/resend', [InscriptionController::class, 'resendCode'])
     ->name('inscription.verify.resend');
 Route::post('/inscription/{inscription}/verify/sms', [InscriptionController::class, 'trySmsVerification'])
     ->name('inscription.verify.sms');
 
 // Page de confirmation 
 Route::get('/inscription/{inscription}/confirmation', [InscriptionController::class, 'confirmation']) 
     ->name('inscription.confirmation'); 
 
 // TÃ©lÃ©charger le badge 
 Route::get('/inscription/{inscription}/badge', [InscriptionController::class, 'downloadBadge']) 
     ->name('inscription.badge.download'); 
 
 // TÃ©lÃ©charger la plaquette 
 Route::get('/inscription/{inscription}/plaquette', [InscriptionController::class, 'downloadPlaquette']) 
     ->name('inscription.plaquette.download'); 
 
 // Annuler une inscription 
 Route::delete('/inscription/{inscription}', [InscriptionController::class, 'cancel']) 
     ->name('inscription.cancel'); 
 
 // SÃ©lection des ateliers aprÃ¨s inscription
 Route::get('/inscription/{inscription}/ateliers', [InscriptionController::class, 'selectAteliers'])->name('inscription.ateliers.select');
 Route::post('/inscription/{inscription}/ateliers', [InscriptionController::class, 'storeAteliers'])->name('inscription.ateliers.store');
 
 // ------------------------- 
 // Routes Evenements / Ateliers (AuthentifiÃ©es) 
 // ------------------------- 
 Route::middleware(['auth'])->group(function () { 
 
     // CRUD Evenements (accessible aux collaborateurs uniquement) 
     Route::resource('evenements', EvenementController::class); 

     // Preview template landing for admin choice
     Route::get('landing/templates/{template}/preview', [EvenementController::class, 'previewLandingTemplate'])
         ->name('landing.templates.preview');
 
     // Download or generate plaquette (PDF) 
     Route::get('evenements/{evenement}/plaquette', [EvenementController::class, 'downloadPlaquette']) 
         ->name('evenements.plaquette.download');

     // Sponsors/partenaires association on event
     Route::post('evenements/{evenement}/partenaires', [EvenementController::class, 'attachPartenaire'])
         ->name('evenements.partenaires.attach');
     Route::post('evenements/{evenement}/partenaires/create', [EvenementController::class, 'createAndAttachPartenaire'])
         ->name('evenements.partenaires.createAndAttach');
     Route::delete('evenements/{evenement}/partenaires/{partenaire}', [EvenementController::class, 'detachPartenaire'])
         ->name('evenements.partenaires.detach');

     // Toggle event visibility
     Route::patch('evenements/{evenement}/toggle-visibility', [EvenementController::class, 'toggleVisibility'])
         ->name('evenements.toggleVisibility');
     
     // Voir les dÃ©tails d'une inscription
     Route::get('inscriptions/{inscription}', [InscriptionController::class, 'show'])
         ->name('inscriptions.show');
     
     // Exporter les inscriptions en CSV
     Route::get('evenements/{evenement_id}/inscriptions/export-csv', [InscriptionController::class, 'exportCsv'])
         ->name('inscriptions.export-csv');
     
     // Valider une ou plusieurs inscriptions
     Route::post('inscriptions/valider', [InscriptionController::class, 'valider'])
         ->name('inscriptions.valider');
 
 
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
     // Mon Ã©quipe (admin_entreprise) 
     Route::get('equipe', [\App\Http\Controllers\Admin\EquipeController::class, 'index']) 
         ->name('equipe.index') 
         ->middleware('checkrole:admin_entreprise'); 
 
     // Infos entreprise (admin_entreprise) 
     Route::get('entreprises/infos', [\App\Http\Controllers\Admin\InfosEntrepriseController::class, 'show']) 
         ->name('entreprises.infos') 
         ->middleware('checkrole:admin_entreprise'); 
 
     // Entreprises - Super Admin et Admin Entreprise 
     Route::resource('entreprises', EntrepriseController::class) 
         ->middleware('checkrole:super_admin,admin_entreprise'); 
 
     // Collaborateurs - Super Admin et Admin Entreprise 
     Route::resource('collaborateurs', CollaborateurController::class) 
         ->middleware('checkrole:super_admin,admin_entreprise,collaborateur'); 
 
     // Speakers (admin entreprise)
     Route::get('speakers', [\App\Http\Controllers\Admin\SpeakerController::class, 'index'])
         ->name('speakers.index')
         ->middleware('checkrole:admin_entreprise');
     Route::get('speakers/create', [\App\Http\Controllers\Admin\SpeakerController::class, 'create'])
         ->name('speakers.create')
         ->middleware('checkrole:admin_entreprise');
     Route::post('speakers', [\App\Http\Controllers\Admin\SpeakerController::class, 'store'])
         ->name('speakers.store')
         ->middleware('checkrole:admin_entreprise');

     // Sponsors / Partenaires (admin entreprise)
     Route::get('partenaires', [\App\Http\Controllers\Admin\PartenaireController::class, 'index'])
         ->name('partenaires.index')
         ->middleware('checkrole:admin_entreprise');
     Route::get('partenaires/create', [\App\Http\Controllers\Admin\PartenaireController::class, 'create'])
         ->name('partenaires.create')
         ->middleware('checkrole:admin_entreprise');
     Route::post('partenaires', [\App\Http\Controllers\Admin\PartenaireController::class, 'store'])
         ->name('partenaires.store')
         ->middleware('checkrole:admin_entreprise'); 
 
     // Ã‰vÃ©nements - Super Admin et Admin Entreprise 
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
 
         // Gestion des inscriptions 
         Route::get('inscriptions', [\App\Http\Controllers\InscriptionController::class, 'index']) 
             ->name('inscriptions.index'); 
         Route::get('inscriptions/{inscription}', [\App\Http\Controllers\InscriptionController::class, 'show']) 
             ->name('inscriptions.show'); 
     }); 
 }); 
 
 // ------------------------- 
 // Auth routes (Breeze) 
 require __DIR__.'/auth.php';
