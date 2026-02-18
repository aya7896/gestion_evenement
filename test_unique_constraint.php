#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

/** @var \Illuminate\Foundation\Application $app */
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$exit = $kernel->handle(
    $input = new \Symfony\Component\Console\Input\ArgvInput(),
    new \Symfony\Component\Console\Output\ConsoleOutput()
);

use App\Models\User;
use App\Models\Evenement;
use App\Models\Inscription;
use App\Models\Inscription_event;
use Illuminate\Support\Facades\DB;

echo "\n=== TEST DE LA CONTRAINTE UNIQUE ===\n\n";

try {
    // Récupérer le premier événement et le premier utilisateur
    $evenement = Evenement::first();
    $user = User::where('role', 'participant')->first();
    
    if (!$evenement || !$user) {
        echo "❌ Pas d'événement ou d'utilisateur trouvé!\n";
        exit(1);
    }
    
    echo "Événement: {$evenement->titre}\n";
    echo "Utilisateur: {$user->email}\n\n";
    
    // Vérifier la structure de la table
    echo "--- Structure de la table inscription_event ---\n";
    $columns = DB::select("DESCRIBE inscription_event");
    foreach ($columns as $col) {
        echo "  - {$col->Field} ({$col->Type})\n";
    }
    
    // Vérifier les premières inscriptions
    echo "\n--- Premières inscriptions pour cet utilisateur et événement ---\n";
    $inscriptions = Inscription_event::where('id_user', $user->id_user)
        ->where('id_event', $evenement->id_event)
        ->get();
    
    echo "Nombre d'inscriptions: " . count($inscriptions) . "\n";
    
    if (count($inscriptions) > 0) {
        echo "\n⚠️  Cet utilisateur est déjà inscrit à cet événement.\n";
        echo "✅ La contrainte unique empêcherait un doublon à ce niveau aussi.\n";
    } else {
        echo "\n✅ Cet utilisateur n'est pas encore inscrit à cet événement.\n";
    }
    
    // Vérifier les contraintes uniques
    echo "\n--- Contraintes uniques sur la table ---\n";
    $indexes = DB::select("SHOW INDEXES FROM inscription_event WHERE Non_unique = 0");
    foreach ($indexes as $idx) {
        echo "  Index: {$idx->Key_name} sur colonne(s): {$idx->Column_name}\n";
    }
    
    echo "\n✅ Test D'VÉRIFICATION TERMINÉ AVEC SUCCÈS!\n\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
    echo $e->getTraceAsString();
    exit(1);
}

exit(0);
