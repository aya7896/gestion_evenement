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
        Schema::create('partenaires', function (Blueprint $table) {
            $table->id('id_partenaire');
            $table->string('nom');
            $table->integer('ordre')->default(0)->comment('Ordre d\'affichage');
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('contrat')->nullable()->comment('Chemin vers le fichier contrat');
            $table->string('logo')->nullable()->comment('Chemin vers le logo');
            $table->text('description')->nullable();
            $table->string('site_web')->nullable();
            $table->enum('type', ['gold', 'silver', 'bronze', 'media', 'institutionnel', 'autre'])->default('autre');
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partenaires');
    }
};
