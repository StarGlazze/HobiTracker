<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriHobiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriHobis = [
            ['nama_kategori' => 'Olahraga & Kebugaran'],
            ['nama_kategori' => 'Seni & Kreativitas'],
            ['nama_kategori' => 'Musik & Performing Arts'],
            ['nama_kategori' => 'Membaca & Literasi'],
            ['nama_kategori' => 'Gaming & E-Sports'],
            ['nama_kategori' => 'Kuliner & Memasak'],
            ['nama_kategori' => 'Travel & Outdoor'],
            ['nama_kategori' => 'Komunitas & Sosial'],
            ['nama_kategori' => 'Koleksi & Hobi Khusus'],
            ['nama_kategori' => 'Teknologi & Sains'],
            ['nama_kategori' => 'Relaksasi & Lifestyle'],
            ['nama_kategori' => 'Lainnya'],
        ];

        DB::table('kategori_hobis')->insert($kategoriHobis);
    }
}
