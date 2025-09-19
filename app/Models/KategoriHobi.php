<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriHobi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
    ];

    // Relationships
    public function hobis()
    {
        return $this->hasMany(Hobi::class, 'kategori_id');
    }
}
