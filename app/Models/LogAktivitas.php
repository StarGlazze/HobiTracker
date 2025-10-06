<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hobi_id',
        'aktivitas_id',
        'durasi',
        'catatan',
        'file_bukti',
        'tanggal_aktivitas',
    ];

    protected $casts = [
        'tanggal_aktivitas' => 'date',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Hobi
     */
    public function hobi()
    {
        return $this->belongsTo(Hobi::class);
    }

    /**
     * Relasi ke Aktivitas (jika ada tabel aktivitas)
     */
    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class);
    }
}