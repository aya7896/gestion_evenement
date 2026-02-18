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
        Schema::create('event_partenaire', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_event');
            $table->unsignedBigInteger('id_partenaire');
            $table->text('contribution')->nullable()->comment('Description de la contribution du partenaire');
            $table->decimal('montant', 10, 2)->nullable()->comment('Montant de la contribution');
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_event')
                  ->references('id_event')
                  ->on('evenements')
                  ->onDelete('cascade');

            $table->foreign('id_partenaire')
                  ->references('id_partenaire')
                  ->on('partenaires')
                  ->onDelete('cascade');

            // Unique constraint to prevent duplicates
            $table->unique(['id_event', 'id_partenaire']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_partenaire');
    }
};
