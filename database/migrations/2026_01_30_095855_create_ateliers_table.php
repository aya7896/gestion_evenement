<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ateliers', function (Blueprint $table) {
            $table->id('id_atelier');

            $table->unsignedBigInteger('id_event');

            $table->string('titre');
            $table->enum('visibility', ['privee', 'publique'])->default('publique');

            $table->time('heure_debut');
            $table->time('heure_fin');

            $table->text('sujet')->nullable();
            $table->string('banniere')->nullable();
            $table->date('date');

            $table->string('image')->nullable();
            $table->integer('capacite')->nullable();

            $table->enum('status', ['actif', 'annule'])->default('actif');
            $table->string('online_link')->nullable();

            $table->foreign('id_event')
                  ->references('id_event')
                  ->on('evenements')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ateliers');
    }
};

