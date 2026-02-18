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
        Schema::create('atelier_speaker', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_atelier');
            $table->unsignedBigInteger('id_speaker');
            $table->string('role')->default('speaker')->comment('Role: speaker, moderateur, invite, etc.');
            $table->integer('ordre')->default(0)->comment('Ordre de passage');
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_atelier')
                  ->references('id_atelier')
                  ->on('ateliers')
                  ->onDelete('cascade');

            $table->foreign('id_speaker')
                  ->references('id_speaker')
                  ->on('speakers')
                  ->onDelete('cascade');

            // Unique constraint
            $table->unique(['id_atelier', 'id_speaker']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atelier_speaker');
    }
};
