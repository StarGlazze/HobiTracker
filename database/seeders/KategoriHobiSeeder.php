<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriHobi;

class KategoriHobiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if categories already exist to avoid duplicates
        if (KategoriHobi::count() > 0) {
            $this->command->info('KategoriHobi records already exist. Skipping seeder.');
            return;
        }

        $kategoriHobis = [
            ['nama_kategori' => 'Olahraga & Kebugaran', 'icon' => 'ti-barbell', 'background_color' => 'bg-success'],
            ['nama_kategori' => 'Seni & Kreativitas', 'icon' => 'ti-palette', 'background_color' => 'bg-warning'],
            ['nama_kategori' => 'Musik & Performing Arts', 'icon' => 'ti-music', 'background_color' => 'bg-info'],
            ['nama_kategori' => 'Membaca & Literasi', 'icon' => 'ti-book', 'background_color' => 'bg-primary'],
            ['nama_kategori' => 'Gaming & E-Sports', 'icon' => 'ti-device-gamepad', 'background_color' => 'bg-cyan'],
            ['nama_kategori' => 'Kuliner & Memasak', 'icon' => 'ti-chef-hat', 'background_color' => 'bg-danger'],
            ['nama_kategori' => 'Travel & Outdoor', 'icon' => 'ti-map-pin', 'background_color' => 'bg-secondary'],
            ['nama_kategori' => 'Komunitas & Sosial', 'icon' => 'ti-users', 'background_color' => 'bg-indigo'],
            ['nama_kategori' => 'Koleksi & Hobi Khusus', 'icon' => 'ti-archive', 'background_color' => 'bg-purple'],
            ['nama_kategori' => 'Teknologi & Sains', 'icon' => 'ti-cpu', 'background_color' => 'bg-teal'],
            ['nama_kategori' => 'Relaksasi & Lifestyle', 'icon' => 'ti-leaf', 'background_color' => 'bg-orange'],
            ['nama_kategori' => 'Lainnya', 'icon' => 'ti-dots', 'background_color' => 'bg-pink'],
        ];

        foreach ($kategoriHobis as $kategori) {
            KategoriHobi::create($kategori);
        }

        $this->command->info('KategoriHobi seeder completed successfully!');
    }
}
