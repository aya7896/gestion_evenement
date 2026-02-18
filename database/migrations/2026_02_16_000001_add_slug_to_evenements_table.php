<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('titre');
        });

        // Populate slug for existing records
        $events = \DB::table('evenements')->select('id_event', 'titre')->get();
        foreach ($events as $e) {
            $slug = Str::slug($e->titre ?: 'event-' . $e->id_event);
            // ensure uniqueness
            $base = $slug;
            $i = 1;
            while (\DB::table('evenements')->where('slug', $slug)->where('id_event', '!=', $e->id_event)->exists()) {
                $slug = $base . '-' . $i++;
            }
            \DB::table('evenements')->where('id_event', $e->id_event)->update(['slug' => $slug]);
        }
    }

    public function down()
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
