<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgresTarget extends Model
{
    use HasFactory;

    protected $table = 'progres_targets';

    protected $fillable = [
        'user_id',
        'target_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function target()
    {
        return $this->belongsTo(TargetHobi::class, 'target_id');
    }
}
