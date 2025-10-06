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
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambahkan
            if (!Schema::hasColumn('users', 'pekerjaan')) {
                $table->string('pekerjaan')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('users', 'umur')) {
                $table->integer('umur')->nullable()->after('pekerjaan');
            }
            
            if (!Schema::hasColumn('users', 'hobi_utama')) {
                $table->text('hobi_utama')->nullable()->after('umur');
            }
            
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('hobi_utama');
            }
            
            if (!Schema::hasColumn('users', 'foto_profil')) {
                $table->string('foto_profil')->nullable()->after('bio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pekerjaan', 'umur', 'hobi_utama', 'bio', 'foto_profil']);
        });
    }
};