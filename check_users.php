<?php
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Configuration de la base de données
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'port'      => '3305',
    'database'  => 'gestion_events_stage',
    'username'  => 'root',
    'password'  => 'aya20062905783@',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Vérification des utilisateurs
$users = \Illuminate\Database\Eloquent\Model::getConnectionResolver()
    ->connection()
    ->table('users')
    ->select('email', 'role')
    ->get();




echo "Utilisateurs trouvés :\n";
foreach ($users as $user) {
    echo "- " . $user->email . " (role: " . $user->role . ")\n";
}