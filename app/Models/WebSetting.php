<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_website',
        'logo',
        'deskripsi',
        'contact_email',
        'contact_phone',
        'address',
        'whatsapp',
        'telegram',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'youtube',
    ];

    
    
}
