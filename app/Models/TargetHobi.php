<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetHobi extends Model
{
    use HasFactory;

    protected $fillable = [
        'hobi_id',
        'nama_target',
        'target_deadline',
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
}
