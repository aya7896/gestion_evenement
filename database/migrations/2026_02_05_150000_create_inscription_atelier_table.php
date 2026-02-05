<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inscription_atelier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_inscription');
            $table->unsignedBigInteger('id_atelier');
            $table->timestamps();

            $table->foreign('id_inscription')->references('id_inscription')->on('inscriptions')->onDelete('cascade');
            $table->foreign('id_atelier')->references('id_atelier')->on('ateliers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscription_atelier');
    }
};
