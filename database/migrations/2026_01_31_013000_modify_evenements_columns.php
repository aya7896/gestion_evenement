<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // Change enum columns to VARCHAR to accept form values (no doctrine/dbal required using raw SQL)
        DB::statement("ALTER TABLE `evenements` MODIFY `type` VARCHAR(191) NOT NULL");
        DB::statement("ALTER TABLE `evenements` MODIFY `visibility` VARCHAR(50) NOT NULL DEFAULT 'public'");
        DB::statement("ALTER TABLE `evenements` MODIFY `status` VARCHAR(50) NOT NULL DEFAULT 'active'");
    }

    public function down(): void
    {
        // Recreate the original enums (best effort - may fail if existing values are incompatible)
        DB::statement("ALTER TABLE `evenements` MODIFY `type` ENUM('presentiel','en_ligne','hybride') NOT NULL");
        DB::statement("ALTER TABLE `evenements` MODIFY `visibility` ENUM('privee','publique') NOT NULL DEFAULT 'privee'");
        DB::statement("ALTER TABLE `evenements` MODIFY `status` ENUM('draft','published','finished') NOT NULL DEFAULT 'draft'");
    }
};
