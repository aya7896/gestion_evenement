<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `evenements` MODIFY `id_Collaborateur` BIGINT UNSIGNED NULL");
        DB::statement("ALTER TABLE `evenements` MODIFY `id_entreprise` BIGINT UNSIGNED NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `evenements` MODIFY `id_Collaborateur` BIGINT UNSIGNED NOT NULL");
        DB::statement("ALTER TABLE `evenements` MODIFY `id_entreprise` BIGINT UNSIGNED NOT NULL");
    }
};
