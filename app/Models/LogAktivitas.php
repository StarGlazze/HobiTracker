<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $fillable = [
        'aktivitas_id',
        'user_id',
        'file_bukti',
        'catatan',
    ];

    // Relationships
    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
