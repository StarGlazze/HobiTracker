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
        Schema::table('aktivitas', function (Blueprint $table) {
            $table->dropColumn('durasi_menit');
            $table->string('energy_mood_level')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aktivitas', function (Blueprint $table) {
            $table->dropColumn('energy_mood_level');
            $table->integer('durasi_menit');
        });
    }
};
