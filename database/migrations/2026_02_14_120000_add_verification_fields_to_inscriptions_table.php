<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->string('verification_code')->nullable()->after('statut');
            $table->dateTime('verification_expires_at')->nullable()->after('verification_code');
            $table->dateTime('verification_sent_at')->nullable()->after('verification_expires_at');
            $table->string('verification_method')->nullable()->after('verification_sent_at');
            $table->unsignedSmallInteger('verification_attempts')->default(0)->after('verification_method');
            $table->dateTime('verified_at')->nullable()->after('verification_attempts');
        });
    }

    public function down(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'verification_code',
                'verification_expires_at',
                'verification_sent_at',
                'verification_method',
                'verification_attempts',
                'verified_at',
            ]);
        });
    }
};
