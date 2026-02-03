<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('collaborateurs', function (Blueprint $table) {
            $table->id('id_Collaborateur');

            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_entreprise');

            $table->boolean('active')->default(true);

            $table->enum('role', [
                'admin_entreprise',
                'scanner'
            ]);

            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('id_entreprise')
                  ->references('id_entreprise')
                  ->on('entreprises')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collaborateurs');
    }
};
