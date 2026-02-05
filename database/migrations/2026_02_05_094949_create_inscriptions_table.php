<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id('id_inscription');
            $table->unsignedBigInteger('id_user');
            $table->dateTime('date_ins')->nullable();
            $table->string('company')->nullable();
            
            // ✅ Photo stockera le chemin du fichier (ex: photos/abc123.jpg)
            $table->string('photo')->nullable();
            
            // ✅ TEXT pour supporter jusqu'à 65,535 caractères (validation: 1000 max)
            $table->text('presentation')->nullable();
            
            $table->string('poste')->nullable();
            $table->string('lien_linkedin')->nullable();
            
            // ✅ TEXT pour supporter jusqu'à 65,535 caractères (validation: 1000 max)
            $table->text('objectif')->nullable();

            // Clés étrangères
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('cascade');
            
            // Index pour améliorer les performances des requêtes
            $table->index('id_user');
            $table->index('date_ins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};