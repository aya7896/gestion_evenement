<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n=== VÉRIFICATION DE LA CONTRAINTE UNIQUE ===\n\n";

// Vérifier que la colonne id_user existe
if (Schema::hasColumn('inscription_event', 'id_user')) {
    echo "✅ Colonne 'id_user' existe dans inscription_event\n";
} else {
    echo "❌ Colonne 'id_user' N'existe PAS dans inscription_event\n";
}

// Vérifier les colonnes
$columns = Schema::getColumns('inscription_event');
echo "\n🔍 Colonnes de la table inscription_event:\n";
foreach ($columns as $col) {
    echo "  - {$col['name']} ({$col['type']})\n";
}

// Vérifier les index
echo "\n🔍 Index uniques:\n";
$indexes = DB::select("SELECT * FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_NAME='inscription_event' 
    AND INDEX_NAME LIKE 'unique%'");

if (empty($indexes)) {
    echo "  ❌ Aucun index unique trouvé\n";
} else {
    foreach ($indexes as $idx) {
        echo "  ✅ {$idx->INDEX_NAME} sur colonne(s): {$idx->COLUMN_NAME}\n";
    }
}

// Vérifier les clés étrangères
echo "\n🔍 Clés étrangères:\n";
$fks = DB::select("SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE TABLE_NAME='inscription_event' 
    AND REFERENCED_TABLE_NAME IS NOT NULL");

if (empty($fks)) {
    echo "  ⚠️  Aucune clé étrangère trouvée\n";
} else {
    foreach ($fks as $fk) {
        echo "  ✅ {$fk->CONSTRAINT_NAME}: {$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
    }
}

// Compter les données
$count = DB::table('inscription_event')->count();
echo "\n📊 Nombre d'enregistrements dans inscription_event: {$count}\n";

echo "\n✅ VÉRIFICATION TERMINÉE!\n\n";
