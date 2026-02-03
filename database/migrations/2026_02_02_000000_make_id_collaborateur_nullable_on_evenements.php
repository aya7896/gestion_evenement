<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Use a raw statement to avoid requiring doctrine/dbal for column change
        DB::statement('ALTER TABLE evenements MODIFY id_Collaborateur BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        // Change back to NOT NULL (may fail if NULL values exist)
        DB::statement('ALTER TABLE evenements MODIFY id_Collaborateur BIGINT UNSIGNED NOT NULL');
    }
};
