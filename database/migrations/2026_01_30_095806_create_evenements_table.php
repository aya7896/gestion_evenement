<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id('id_event');

            $table->unsignedBigInteger('id_Collaborateur');
            $table->unsignedBigInteger('id_entreprise');

            $table->string('titre');
            $table->integer('capacite')->nullable();
            $table->text('description')->nullable();

            $table->enum('type', ['presentiel', 'en_ligne', 'hybride']);
            $table->string('localisation')->nullable();
            $table->string('lieu')->nullable();

            $table->dateTime('date_heure_debut');
            $table->dateTime('date_heure_fin');

            $table->string('mode')->nullable();
            $table->string('plaquette_pdf')->nullable();

            $table->boolean('validation_superAdmin')->default(false);

            $table->enum('status', ['draft', 'published', 'finished'])->default('draft');
            $table->enum('visibility', ['privee', 'publique'])->default('privee');

            $table->string('event_link')->nullable();
            $table->string('image')->nullable();

            $table->foreign('id_Collaborateur')
                  ->references('id_Collaborateur')
                  ->on('collaborateurs');

            $table->foreign('id_entreprise')
                  ->references('id_entreprise')
                  ->on('entreprises');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
