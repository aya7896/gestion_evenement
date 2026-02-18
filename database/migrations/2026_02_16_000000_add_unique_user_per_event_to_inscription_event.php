<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajoute la colonne id_user à la table inscription_event (si elle n'existe pas déjà)
     * et crée une contrainte unique pour empêcher qu'un même utilisateur 
     * s'inscrive deux fois au même événement.
     */
    public function up(): void
    {
        // Ajouter la colonne id_user seulement si elle n'existe pas
        if (!Schema::hasColumn('inscription_event', 'id_user')) {
            Schema::table('inscription_event', function (Blueprint $table) {
                $table->unsignedBigInteger('id_user')->nullable()->after('id_inscription');
            });

            // Remplir la colonne id_user avec les données existantes
            DB::statement('
                UPDATE inscription_event 
                SET id_user = (
                    SELECT id_user FROM inscriptions 
                    WHERE inscriptions.id_inscription = inscription_event.id_inscription
                )
            ');

            // Rendre la colonne NOT NULL après l'avoir remplie
            Schema::table('inscription_event', function (Blueprint $table) {
                $table->unsignedBigInteger('id_user')->change();
            });
        }

        // Supprimer l'ancienne contrainte unique si elle existe (elle n'empêchait pas les vrais doublons)
        if (Schema::hasTable('inscription_event')) {
            $indexName = 'unique_inscription_event';
            $indexes = DB::select("SHOW INDEXES FROM inscription_event WHERE Key_name = ?", [$indexName]);
            if (!empty($indexes)) {
                DB::statement("ALTER TABLE inscription_event DROP INDEX {$indexName}");
            }
        }

        // Ajouter la nouvelle contrainte unique sur (id_user, id_event)
        Schema::table('inscription_event', function (Blueprint $table) {
            $table->unique(['id_user', 'id_event'], 'unique_user_per_event');
        });

        // Ajouter la clé étrangère si elle n'existe pas
        if (!Schema::hasColumn('inscription_event', 'id_user')) {
            return;
        }

        $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                                    WHERE TABLE_NAME='inscription_event' AND COLUMN_NAME='id_user' AND REFERENCED_TABLE_NAME='users'");
        if (empty($foreignKeys)) {
            Schema::table('inscription_event', function (Blueprint $table) {
                $table->foreign('id_user')
                    ->references('id_user')
                    ->on('users')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscription_event', function (Blueprint $table) {
            // Vérifier et supprimer les contraintes
            if (Schema::hasColumn('inscription_event', 'id_user')) {
                try {
                    $table->dropForeign(['id_user']);
                } catch (\Exception $e) {
                    // La clé étrangère n'existe pas, on continue
                }
            }
            
            if (Schema::hasColumn('inscription_event', 'id_user')) {
                try {
                    $table->dropUnique('unique_user_per_event');
                } catch (\Exception $e) {
                    // L'index unique n'existe pas, on continue
                }
            }
        });

        // Récréer l'ancienne contrainte unique
        if (Schema::hasTable('inscription_event')) {
            try {
                Schema::table('inscription_event', function (Blueprint $table) {
                    $table->unique(['id_inscription', 'id_event'], 'unique_inscription_event');
                });
            } catch (\Exception $e) {
                // L'index existe peut-être déjà
            }
        }
    }
};
