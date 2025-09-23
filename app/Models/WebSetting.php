<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class WebSetting extends Model
{
    use HasFactory;

    protected $table = 'web_settings';

    protected $fillable = [
        'nama_website',
        'deskripsi',
        'logo',
        'favicon',
        'email',
        'telepon',
        'alamat',
        'whatsapp',
        'telegram',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'youtube',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get full URL for logo
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            // Ambil dari storage/app/public/uploads/logos/
            return asset('storage/' . $this->logo);
        }
        return asset('admin/images/logos/HobiTrackerr.png'); // Default logo
    }

    /**
     * Get full URL for favicon
     */
    public function getFaviconUrlAttribute()
    {
        if ($this->favicon) {
            // Ambil dari storage/app/public/uploads/favicons/
            return asset('storage/' . $this->favicon);
        }
        return asset('admin/images/logos/favicon-v2.png'); // Default favicon
    }

    /**
     * Get formatted phone number for WhatsApp
     */
    public function getWhatsappLinkAttribute()
    {
        if ($this->whatsapp) {
            // Remove non-numeric characters and add country code if needed
            $number = preg_replace('/\D/', '', $this->whatsapp);
            if (substr($number, 0, 1) === '0') {
                $number = '62' . substr($number, 1);
            }
            return "https://wa.me/{$number}";
        }
        return null;
    }

    /**
     * Get formatted Telegram link
     */
    public function getTelegramLinkAttribute()
    {
        if ($this->telegram) {
            $username = ltrim($this->telegram, '@');
            return "https://t.me/{$username}";
        }
        return null;
    }

    /**
     * Scope to get single settings row
     */
    public function scopeSingle($query)
    {
        return $query->first() ?: new static([
            'nama_website' => 'HobiTracker',
            'deskripsi' => 'Platform tracking hobi terbaik',
        ]);
    }
}
