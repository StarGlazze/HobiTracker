<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aktivitas extends Model
{
    use HasFactory;

    protected $fillable = [
        'hobi_id',
        'nama_aktivitas',
        'durasi_menit',
        'catatan',
        'file_bukti',
    ];

    // Relationships
    public function hobi()
    {
        return $this->belongsTo(Hobi::class);
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }
}
