<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgresTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_id',
        'status',
        'file_bukti',
        'catatan',
    ];

    // Relationships
    public function targetHobi()
    {
        return $this->belongsTo(TargetHobi::class, 'target_id');
    }
}
