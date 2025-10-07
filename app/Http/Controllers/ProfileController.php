<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\LogAktivitas;
use App\Models\TargetHobi;
use App\Models\Hobi;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Calculate achievements
        $achievements = $this->calculateAchievements($user);

        // Recent activities - with better error handling
        $recentActivities = LogAktivitas::where('user_id', $user->id)
            ->with(['aktivitas.target.hobi'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('admin.profile', compact('user', 'achievements', 'recentActivities'));
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'pekerjaan' => 'nullable|string|max:255',
                'umur' => 'nullable|integer|min:10|max:100',
                'hobi_utama' => 'nullable|string|max:500',
                'bio' => 'nullable|string|max:1000',
                'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ], [
                'umur.min' => 'Umur minimal 10 tahun.',
                'umur.max' => 'Umur maksimal 100 tahun.',
                'foto_profil.image' => 'File harus berupa gambar.',
                'foto_profil.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
                'foto_profil.max' => 'Ukuran gambar maksimal 5MB.',
            ]);

            $user = User::find(Auth::id());

            // Prepare data for update
            $user->pekerjaan = $request->pekerjaan;
            $user->umur = $request->umur;
            $user->hobi_utama = $request->hobi_utama;
            $user->bio = $request->bio;

            // Handle file upload
            if ($request->hasFile('foto_profil')) {
                // Delete old file if exists
                if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                    Storage::disk('public')->delete($user->foto_profil);
                }
                
                // Store new file
                $file = $request->file('foto_profil');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('profile', $filename, 'public');
                $user->foto_profil = $path;
            }

            // Save user
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui.',
                'data' => [
                    'pekerjaan' => $user->pekerjaan,
                    'umur' => $user->umur,
                    'hobi_utama' => $user->hobi_utama,
                    'bio' => $user->bio,
                    'foto_profil' => $user->foto_profil ? asset('storage/' . $user->foto_profil) : null,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'password_lama' => 'required',
                'password_baru' => 'required|min:6|confirmed',
            ], [
                'password_lama.required' => 'Password lama harus diisi.',
                'password_baru.required' => 'Password baru harus diisi.',
                'password_baru.min' => 'Password baru minimal 6 karakter.',
                'password_baru.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);

            $user = User::find(Auth::id());

            // Check old password
            if (!Hash::check($request->password_lama, $user->password)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Password lama tidak sesuai.'
                ], 422);
            }

            // Update password
            $user->password = Hash::make($request->password_baru);
            $user->save();

            return response()->json([
                'success' => true, 
                'message' => 'Password berhasil diubah.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculateAchievements(User $user)
    {
        $achievements = [];

        // Early Bird: Login sebelum jam 7 pagi sebanyak 10 kali
        $earlyBirdCount = LogAktivitas::where('user_id', $user->id)
            ->whereTime('created_at', '<', '07:00:00')
            ->count();
        $achievements['early_bird'] = $earlyBirdCount >= 10;

        // Night Owl: Selesaikan aktivitas setelah jam 10 malam sebanyak 5 kali
        $nightOwlCount = LogAktivitas::where('user_id', $user->id)
            ->whereTime('created_at', '>=', '22:00:00')
            ->count();
        $achievements['night_owl'] = $nightOwlCount >= 5;

        // Explorer: Tambahkan hobi dari minimal 5 kategori berbeda
        $distinctHobbies = Hobi::where('user_id', $user->id)
            ->distinct('kategori_id')
            ->count('kategori_id');
        $achievements['explorer'] = $distinctHobbies >= 5;

        // Consistency King/Queen: Login 30 hari berturut-turut
        $consecutiveDays = 0;
        $currentDate = now();
        for ($i = 0; $i < 30; $i++) {
            $hasActivity = LogAktivitas::where('user_id', $user->id)
                ->whereDate('created_at', $currentDate->copy()->subDays($i))
                ->exists();

            if ($hasActivity) {
                $consecutiveDays++;
            } else {
                break;
            }
        }
        $achievements['consistency'] = $consecutiveDays >= 30;

        // Goal Crusher: Selesaikan 20 target yang sudah dibuat
        $completedTargets = DB::table('progres_targets')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->distinct('target_id')
            ->count('target_id');
        $achievements['goal_crusher'] = $completedTargets >= 20;

        // Storyteller: Tambahkan deskripsi/detail lebih dari 200 karakter di sebuah hobi
        $hasLongDescription = Hobi::where('user_id', $user->id)
            ->whereRaw('LENGTH(deskripsi) > 200')
            ->exists();
        $achievements['storyteller'] = $hasLongDescription;

        // Collector: Upload minimal 50 file bukti (foto/video)
        $proofFiles = LogAktivitas::where('user_id', $user->id)
            ->whereNotNull('file_bukti')
            ->where('file_bukti', '!=', '')
            ->count();
        $achievements['collector'] = $proofFiles >= 50;

        // Speedrunner: Selesaikan target dalam waktu < 24 jam sebanyak 5 kali
        $speedrunnerCount = DB::table('progres_targets')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereRaw('TIMESTAMPDIFF(HOUR, created_at, updated_at) < 24')
            ->count();
        $achievements['speedrunner'] = $speedrunnerCount >= 5;

        // Creative Spark: Buat minimal 10 hobi berbeda
        $totalHobbies = Hobi::where('user_id', $user->id)->count();
        $achievements['creative_spark'] = $totalHobbies >= 10;

        // Milestone Master: Selesaikan 100 aktivitas sepanjang waktu
        $totalActivities = LogAktivitas::where('user_id', $user->id)->count();
        $achievements['milestone_master'] = $totalActivities >= 100;

        return $achievements;
    }
}