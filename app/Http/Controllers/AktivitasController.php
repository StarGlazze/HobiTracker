<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\TargetHobi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Query untuk semua aktivitas (untuk statistik)
        $allAktivitasQuery = Aktivitas::whereHas('target.hobi', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('target.hobi');
        $allAktivitas = $allAktivitasQuery->get();

        // Query untuk aktivitas yang akan ditampilkan (dengan sorting, search, pagination)
        $query = Aktivitas::whereHas('target.hobi', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('target.hobi');

        // Handle search
        $search = $request->input('search');
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_aktivitas', 'like', '%' . $search . '%')
                  ->orWhere('catatan', 'like', '%' . $search . '%')
                  ->orWhere('energy_mood_level', 'like', '%' . $search . '%')
                  ->orWhereHas('target', function($q2) use ($search) {
                      $q2->where('nama_target', 'like', '%' . $search . '%')
                         ->orWhereHas('hobi', function($q3) use ($search) {
                             $q3->where('nama_hobi', 'like', '%' . $search . '%');
                         });
                  });
            });
        }

        // Handle sorting - gunakan parameter yang konsisten
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        // Validasi sort direction
        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // Validasi sort by dan terapkan sorting
        switch ($sortBy) {
            case 'nama_aktivitas':
                $query->orderBy('nama_aktivitas', $sortDirection);
                break;
            case 'target':
                // Sorting berdasarkan nama target
                $query->join('target_hobis', 'aktivitas.target_id', '=', 'target_hobis.id')
                      ->orderBy('target_hobis.nama_target', $sortDirection)
                      ->select('aktivitas.*');
                break;
            case 'energy_mood_level':
                $query->orderBy('energy_mood_level', $sortDirection);
                break;
            case 'created_at':
            default:
                $query->orderBy('created_at', $sortDirection);
                break;
        }

        // Pagination dengan append query parameters
        $aktivitas = $query->paginate(5)->withQueryString();

        // Menghitung statistik untuk dashboard cards
        $totalAktivitas = $allAktivitas->count();
        $bulanIni = $allAktivitas->where('created_at', '>=', now()->startOfMonth())->count();
        $hobiAktif = $allAktivitas->pluck('target.hobi')->unique('id')->count();
        $denganMood = $allAktivitas->whereNotNull('energy_mood_level')->where('energy_mood_level', '!=', '')->count();

        // Mengambil target milik user untuk dropdown
        $targets = TargetHobi::whereHas('hobi', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('hobi')->get();

        return view('admin.aktivitas', [
            'aktivitas' => $aktivitas,
            'targets' => $targets,
            'totalAktivitas' => $totalAktivitas,
            'bulanIni' => $bulanIni,
            'hobiAktif' => $hobiAktif,
            'denganMood' => $denganMood,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $userId = Auth::id();

            // Validasi dasar terlebih dahulu
            $validator = Validator::make($request->all(), [
                'target_id' => 'required|exists:target_hobis,id',
                'nama_aktivitas' => 'required|string|max:255',
                'energy_mood_level' => 'nullable|string|max:50',
                'catatan' => 'nullable|string|max:1000',
                'file_bukti' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:51200', // Max 50MB
                'gdrive_link' => 'nullable|url|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // **VALIDASI KUSTOM: Minimal satu bukti harus ada**
            $hasFile = $request->hasFile('file_bukti') && $request->file('file_bukti')->isValid();
            $hasGdriveLink = !empty($request->gdrive_link);

            if (!$hasFile && !$hasGdriveLink) {
                return redirect()->back()
                    ->withErrors(['file_bukti' => 'Minimal satu bukti harus dikirim: File Bukti atau Link Google Drive'])
                    ->withInput();
            }

            // Pastikan target milik user yang sedang login
            $target = TargetHobi::where('id', $request->target_id)->whereHas('hobi', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->first();
            if (!$target) {
                return redirect()->back()->with('error', 'Target tidak ditemukan atau bukan milik Anda')->withInput();
            }

            $fileData = [];

            // Handle file upload jika ada
            if ($hasFile) {
                $file = $request->file('file_bukti');
                $filename = time() . '_' . $userId . '_' . $file->getClientOriginalName();
                $fileData['file'] = $file->storeAs('aktivitas_bukti', $filename, 'public');
            }

            // Handle Google Drive link jika ada
            if ($hasGdriveLink) {
                $fileData['gdrive'] = $request->gdrive_link;
            }

            // Buat aktivitas baru
            $aktivitas = Aktivitas::create([
                'target_id' => $request->target_id,
                'nama_aktivitas' => $request->nama_aktivitas,
                'energy_mood_level' => $request->energy_mood_level,
                'catatan' => $request->catatan,
                'file_bukti' => json_encode($fileData),
            ]);

            // Buat log aktivitas secara otomatis
            \App\Models\LogAktivitas::create([
                'aktivitas_id' => $aktivitas->id,
                'user_id' => $userId,
                'file_bukti' => json_encode($fileData),
                'catatan' => $request->catatan,
            ]);

            return redirect()->back()->with('success', 'Aktivitas berhasil ditambahkan dan dicatat di log');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan pada server: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Aktivitas $aktivitas)
    {
        $userId = Auth::id();

        // Load target.hobi relationship untuk cek kepemilikan
        $aktivitas->load('target.hobi');

        // Pastikan aktivitas milik user yang sedang login
        if (!$aktivitas->target || !$aktivitas->target->hobi || $aktivitas->target->hobi->user_id !== $userId) {
            return redirect()->back()->with('error', 'Aktivitas tidak ditemukan atau bukan milik Anda');
        }

        return redirect()->back()->with('aktivitas', $aktivitas);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aktivitas $aktivitas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aktivitas $aktivitas)
    {
        try {
            $userId = Auth::id();

            // Load target.hobi relationship untuk cek kepemilikan
            $aktivitas->load('target.hobi');

            // Pastikan aktivitas milik user yang sedang login
            if (!$aktivitas->target || !$aktivitas->target->hobi || $aktivitas->target->hobi->user_id !== $userId) {
                return redirect()->back()->with('error', 'Aktivitas tidak ditemukan atau bukan milik Anda')->withInput();
            }

            // Validasi dasar terlebih dahulu
            $validator = Validator::make($request->all(), [
                'target_id' => 'required|exists:target_hobis,id',
                'nama_aktivitas' => 'required|string|max:255',
                'energy_mood_level' => 'nullable|string|max:50',
                'catatan' => 'nullable|string|max:1000',
                'file_bukti' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:51200', // Max 50MB
                'gdrive_link' => 'nullable|url|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // **VALIDASI KUSTOM UNTUK UPDATE: Lebih fleksibel**
            $hasFile = $request->hasFile('file_bukti') && $request->file('file_bukti')->isValid();
            $hasGdriveLink = !empty($request->gdrive_link);
            $hasExistingFile = !empty($aktivitas->file_bukti);

            // Hanya validasi jika tidak ada file existing DAN tidak ada input baru
            if (!$hasExistingFile && !$hasFile && !$hasGdriveLink) {
                return redirect()->back()
                    ->withErrors(['file_bukti' => 'Minimal satu bukti harus ada: File Bukti atau Link Google Drive'])
                    ->withInput();
            }

            // Pastikan target milik user yang sedang login
            $target = TargetHobi::where('id', $request->target_id)->whereHas('hobi', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->first();
            if (!$target) {
                return redirect()->back()->with('error', 'Target tidak ditemukan atau bukan milik Anda')->withInput();
            }

            // Parse existing file data with backward compatibility
            $rawFileBukti = $aktivitas->file_bukti;
            if (is_array($rawFileBukti)) {
                $existingFileData = $rawFileBukti;
            } elseif (is_string($rawFileBukti) && !empty($rawFileBukti)) {
                $decoded = json_decode($rawFileBukti, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $existingFileData = $decoded;
                } else {
                    // Old format: plain string
                    $existingFileData = str_contains($rawFileBukti, 'drive.google.com')
                        ? ['gdrive' => $rawFileBukti]
                        : ['file' => $rawFileBukti];
                }
            } else {
                $existingFileData = [];
            }
            $fileData = $existingFileData; // Start with existing data

            // Handle file upload jika ada file baru
            if ($hasFile) {
                // Hapus file lama jika ada
                if (isset($existingFileData['file']) && Storage::disk('public')->exists($existingFileData['file'])) {
                    Storage::disk('public')->delete($existingFileData['file']);
                }

                $file = $request->file('file_bukti');
                $filename = time() . '_' . $userId . '_' . $file->getClientOriginalName();
                $fileData['file'] = $file->storeAs('aktivitas_bukti', $filename, 'public');
            }

            // Handle Google Drive link jika ada
            if ($hasGdriveLink) {
                $fileData['gdrive'] = $request->gdrive_link;
            }

            // Update aktivitas
            $aktivitas->update([
                'target_id' => $request->target_id,
                'nama_aktivitas' => $request->nama_aktivitas,
                'energy_mood_level' => $request->energy_mood_level,
                'catatan' => $request->catatan,
                'file_bukti' => json_encode($fileData),
            ]);

            // Update semua log aktivitas terkait agar sinkron dengan perubahan aktivitas
            $aktivitas->logAktivitas()->update([
                'catatan' => $request->catatan,
                'file_bukti' => json_encode($fileData),
            ]);

            return redirect()->back()->with('success', 'Aktivitas berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan pada server: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aktivitas $aktivitas)
    {
        try {
            $userId = Auth::id();

            // Load target.hobi relationship untuk cek kepemilikan
            $aktivitas->load('target.hobi');

            // Pastikan aktivitas milik user yang sedang login
            if (!$aktivitas->target || !$aktivitas->target->hobi || $aktivitas->target->hobi->user_id !== $userId) {
                return redirect()->back()->with('error', 'Aktivitas tidak ditemukan atau bukan milik Anda');
            }

            // Hapus file bukti jika ada
            $rawFileBukti = $aktivitas->file_bukti;

            // Handle backward compatibility
            if (is_array($rawFileBukti)) {
                $fileData = $rawFileBukti;
            } elseif (is_string($rawFileBukti) && !empty($rawFileBukti)) {
                $decoded = json_decode($rawFileBukti, true);
                $fileData = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
                // For old string format, if it's not GDrive, it might be a file path
                if (empty($fileData) && !str_contains($rawFileBukti, 'drive.google.com') && Storage::disk('public')->exists($rawFileBukti)) {
                    Storage::disk('public')->delete($rawFileBukti);
                }
            } else {
                $fileData = [];
            }

            // Delete file if exists
            if (isset($fileData['file']) && Storage::disk('public')->exists($fileData['file'])) {
                Storage::disk('public')->delete($fileData['file']);
            }

            // Hapus aktivitas
            $aktivitas->delete();

            return redirect()->back()->with('success', 'Aktivitas berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan pada server: ' . $e->getMessage());
        }
    }
}
