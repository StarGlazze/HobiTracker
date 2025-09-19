<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hobi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_id',
        'nama_hobi',
        'deskripsi',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategoriHobi()
    {
        return $this->belongsTo(KategoriHobi::class, 'kategori_id');
    }

    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class);
    }

    public function targetHobi()
    {
        return $this->hasMany(TargetHobi::class);
    }
}
