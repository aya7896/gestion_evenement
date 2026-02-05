<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inscription_event', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_inscription');
            $table->unsignedBigInteger('id_event');
            $table->timestamps();

            // Clés étrangères
            $table->foreign('id_inscription')
                  ->references('id_inscription')
                  ->on('inscriptions')
                  ->onDelete('cascade');
                  
            $table->foreign('id_event')
                  ->references('id_event')
                  ->on('evenements')
                  ->onDelete('cascade');
            
            // ✅ Contrainte unique : empêche qu'une inscription soit liée 2 fois au même événement
            $table->unique(['id_inscription', 'id_event'], 'unique_inscription_event');
            
            // Index pour optimiser les requêtes
            $table->index('id_inscription');
            $table->index('id_event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscription_event');
    }
};