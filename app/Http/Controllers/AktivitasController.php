<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Hobi;
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
    public function index()
    {
        $userId = Auth::id();

        // Mengambil semua aktivitas milik user yang sedang login
        $aktivitas = Aktivitas::whereHas('hobi', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('hobi')->orderBy('created_at', 'desc')->get();

        // Menghitung statistik untuk dashboard cards
        $totalAktivitas = $aktivitas->count();
        $totalDurasi = $aktivitas->sum('durasi_menit');
        $hobiAktif = $aktivitas->pluck('hobi')->unique('id')->count();
        $rataRataDurasi = $totalAktivitas > 0 ? round($totalDurasi / $totalAktivitas) : 0;

        // Format durasi untuk tampilan
        $totalDurasiFormatted = $totalDurasi . 'm';
        $rataRataDurasiFormatted = $rataRataDurasi . 'm';

        // Mengambil hobi milik user untuk dropdown
        $hobis = Hobi::where('user_id', $userId)->get();

        return view('admin.aktivitas', [
            'aktivitas' => $aktivitas,
            'hobis' => $hobis,
            'totalAktivitas' => $totalAktivitas,
            'totalDurasi' => $totalDurasiFormatted,
            'hobiAktif' => $hobiAktif,
            'rataRataDurasi' => $rataRataDurasiFormatted,
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
                'hobi_id' => 'required|exists:hobis,id',
                'nama_aktivitas' => 'required|string|max:255',
                'durasi_menit' => 'required|integer|min:1',
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

            // Pastikan hobi milik user yang sedang login
            $hobi = Hobi::where('id', $request->hobi_id)->where('user_id', $userId)->first();
            if (!$hobi) {
                return redirect()->back()->with('error', 'Hobi tidak ditemukan atau bukan milik Anda')->withInput();
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
                'hobi_id' => $request->hobi_id,
                'nama_aktivitas' => $request->nama_aktivitas,
                'durasi_menit' => $request->durasi_menit,
                'catatan' => $request->catatan,
                'file_bukti' => json_encode($fileData),
            ]);

            return redirect()->back()->with('success', 'Aktivitas berhasil ditambahkan');

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

        // Load hobi relationship untuk cek kepemilikan
        $aktivitas->load('hobi');

        // Pastikan aktivitas milik user yang sedang login
        if (!$aktivitas->hobi || $aktivitas->hobi->user_id !== $userId) {
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

            // Load hobi relationship untuk cek kepemilikan
            $aktivitas->load('hobi');

            // Pastikan aktivitas milik user yang sedang login
            if (!$aktivitas->hobi || $aktivitas->hobi->user_id !== $userId) {
                return redirect()->back()->with('error', 'Aktivitas tidak ditemukan atau bukan milik Anda')->withInput();
            }

            // Validasi dasar terlebih dahulu
            $validator = Validator::make($request->all(), [
                'hobi_id' => 'required|exists:hobis,id',
                'nama_aktivitas' => 'required|string|max:255',
                'durasi_menit' => 'required|integer|min:1',
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

            // Pastikan hobi milik user yang sedang login
            $hobi = Hobi::where('id', $request->hobi_id)->where('user_id', $userId)->first();
            if (!$hobi) {
                return redirect()->back()->with('error', 'Hobi tidak ditemukan atau bukan milik Anda')->withInput();
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
                'hobi_id' => $request->hobi_id,
                'nama_aktivitas' => $request->nama_aktivitas,
                'durasi_menit' => $request->durasi_menit,
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

            // Load hobi relationship untuk cek kepemilikan
            $aktivitas->load('hobi');

            // Pastikan aktivitas milik user yang sedang login
            if (!$aktivitas->hobi || $aktivitas->hobi->user_id !== $userId) {
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