<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Hobi;
use App\Models\TargetHobi;
use App\Models\Aktivitas;
use App\Models\KategoriHobi;

class HobiTrackerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Use Indonesian locale for names

        // Get all kategori IDs
        $kategoriIds = KategoriHobi::pluck('id')->toArray();

        if (empty($kategoriIds)) {
            $this->command->error('No kategori_hobis found. Please run KategoriHobiSeeder first.');
            return;
        }

        // Create 100 users
        $users = [];
        for ($i = 0; $i < 100; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'pekerjaan' => $faker->jobTitle,
                'umur' => $faker->numberBetween(18, 65),
                'hobi_utama' => $faker->randomElement(['Olahraga', 'Musik', 'Membaca', 'Gaming', 'Memasak']),
                'bio' => $faker->sentence,
                'foto_profil' => null,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
            $users[] = $user;
        }

        $this->command->info('Created 100 users.');

        // For each user, create 10 hobbies
        foreach ($users as $user) {
            for ($j = 0; $j < 10; $j++) {
                $hobi = Hobi::create([
                    'user_id' => $user->id,
                    'kategori_id' => $faker->randomElement($kategoriIds),
                    'nama_hobi' => $faker->randomElement([
                        'Berenang', 'Membaca Novel', 'Bermain Gitar', 'Memasak', 'Lari Pagi',
                        'Menggambar', 'Bermain Piano', 'Fotografi', 'Berkebun', 'Menari',
                        'Bermain Basket', 'Menulis Cerita', 'Bermain Drum', 'Memasak Kue', 'Yoga',
                        'Melukis', 'Bermain Saxophone', 'Traveling', 'Koleksi Buku', 'Bermain Catur'
                    ]),
                    'deskripsi' => $faker->sentence,
                ]);

                // For each hobby, create 1 target
                $target = TargetHobi::create([
                    'user_id' => $user->id,
                    'hobi_id' => $hobi->id,
                    'nama_target' => 'Target untuk ' . $hobi->nama_hobi . ' - ' . $faker->sentence(3),
                    'target_deadline' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
                    'jumlah_aktivitas_dibutuhkan' => 5,
                ]);

                // For each target, create 5 activities
                for ($k = 0; $k < 5; $k++) {
                    Aktivitas::create([
                        'target_id' => $target->id,
                        'nama_aktivitas' => 'Aktivitas ' . ($k + 1) . ' untuk ' . $target->nama_target,
                        'energy_mood_level' => $faker->randomElement(['Low', 'Medium', 'High']),
                        'catatan' => $faker->sentence,
                        'file_bukti' => json_encode([$faker->imageUrl(), $faker->imageUrl()]),
                    ]);
                }
            }
        }

        $this->command->info('Created 1000 hobbies, 1000 targets, and 5000 activities.');
    }
}
