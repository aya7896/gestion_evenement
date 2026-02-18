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
        Schema::create('speakers', function (Blueprint $table) {
            $table->id('id_speaker');
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable()->comment('Chemin vers la photo');
            $table->string('company')->nullable()->comment('Entreprise du speaker');
            $table->string('poste')->nullable()->comment('Poste/titre du speaker');
            $table->json('social_links')->nullable()->comment('Liens sociaux: linkedin, twitter, etc.');
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speakers');
    }
};
