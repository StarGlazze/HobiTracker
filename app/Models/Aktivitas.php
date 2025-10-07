<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aktivitas extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_id',
        'nama_aktivitas',
        'durasi_menit',
        'catatan',
        'file_bukti',
    ];

    protected $casts = [
        'file_bukti' => 'array',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * Get the route key name for the model.
     */
    public function getRouteKey()
    {
        return $this->id;
    }

    // Relationships
    public function target()
    {
        return $this->belongsTo(TargetHobi::class, 'target_id');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }
}
