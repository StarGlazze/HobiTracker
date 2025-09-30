<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgresTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_id',
        'status',
        'file_bukti',
        'link_gdrive',
        'catatan',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke TargetHobi
     */
    public function targetHobi()
    {
        return $this->belongsTo(TargetHobi::class, 'target_id');
    }
}