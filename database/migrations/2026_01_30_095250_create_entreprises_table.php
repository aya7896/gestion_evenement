<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id('id_entreprise');

            $table->string('nom');
            $table->string('logo')->nullable();
            $table->string('site_web')->nullable();
            $table->string('email');
            $table->string('tel')->nullable();

            $table->text('description')->nullable();
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('secteur_activite')->nullable();

            $table->enum('taille_entreprise', [
                'micro',
                'petite',
                'moyenne',
                'grande'
            ])->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
