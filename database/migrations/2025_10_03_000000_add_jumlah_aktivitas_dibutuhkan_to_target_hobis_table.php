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
        Schema::table('target_hobis', function (Blueprint $table) {
            $table->integer('jumlah_aktivitas_dibutuhkan')->default(1)->after('target_deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('target_hobis', function (Blueprint $table) {
            $table->dropColumn('jumlah_aktivitas_dibutuhkan');
        });
    }
};
