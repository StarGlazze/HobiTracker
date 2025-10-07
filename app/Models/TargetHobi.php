<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetHobi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hobi_id',
        'nama_target',
        'target_deadline',
        'jumlah_aktivitas_dibutuhkan',
    ];

    protected $casts = [
        'target_deadline' => 'date',
    ];

    // Relationships
    public function hobi()
    {
        return $this->belongsTo(Hobi::class);
    }

    public function progresTarget()
    {
        return $this->hasMany(ProgresTarget::class, 'target_id');
    }

    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class, 'target_id');
    }
}
