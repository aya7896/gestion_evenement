<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->string('landing_template', 30)->default('template_1')->after('hero_appearance');
            $table->json('landing_content')->nullable()->after('landing_template');
        });
    }

    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->dropColumn(['landing_template', 'landing_content']);
        });
    }
};
