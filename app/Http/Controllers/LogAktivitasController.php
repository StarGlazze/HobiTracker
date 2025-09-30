<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\ProgresTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LogAktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'aktivitas'); // default: aktivitas
        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $sortBy = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $isAdmin = Auth::user()->email === 'admin@example.com';

        if ($type === 'target') {
            // Query untuk Target
            $query = ProgresTarget::with(['targetHobi.hobi.kategoriHobi']);
            
            if (!$isAdmin) {
                $query->where('user_id', Auth::id());
            }

            // Search
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('targetHobi', fn($sub) => $sub->where('nama_target', 'like', "%{$search}%"))
                      ->orWhere('catatan', 'like', "%{$search}%");
                });
            }

            // Filter tanggal
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            // Sorting
            $logs = $query->orderBy($sortBy, $direction)->paginate(10);

            // Stats untuk Target
            $userId = !$isAdmin ? Auth::id() : null;
            $whereUser = $userId ? "AND p.user_id = $userId" : "";
            $whereDate = ($startDate && $endDate) ? "AND p.created_at BETWEEN '$startDate' AND '$endDate'" : "";

            $totalAktivitas = DB::select("SELECT COUNT(*) as count FROM progres_targets p WHERE 1=1 $whereUser $whereDate")[0]->count;
            $bulanIni = DB::select("SELECT COUNT(*) as count FROM progres_targets p WHERE MONTH(created_at) = ? $whereUser $whereDate", [now()->month])[0]->count;
            
            // Hitung completed
            $completed = DB::select("SELECT COUNT(*) as count FROM progres_targets p WHERE status = 'completed' $whereUser $whereDate")[0]->count;
            
            // Hitung failed
            $failed = DB::select("SELECT COUNT(*) as count FROM progres_targets p WHERE status = 'failed' $whereUser $whereDate")[0]->count;

            $totalDurasi = $completed; // Gunakan completed count sebagai "durasi"
            $rataRataHarian = $failed; // Gunakan failed count sebagai "rata-rata"

        } else {
            // Query untuk Aktivitas (existing code)
            $query = LogAktivitas::with(['aktivitas.hobi']);
            
            if (!$isAdmin) {
                $query->where('user_id', Auth::id());
            }

            // Search
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('aktivitas', fn($sub) => $sub->where('nama_aktivitas', 'like', "%{$search}%"))
                      ->orWhere('catatan', 'like', "%{$search}%");
                });
            }

            // Filter tanggal
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            // Sorting
            $logs = $query->orderBy($sortBy, $direction)->paginate(10);

            // Stats untuk Aktivitas
            $userId = !$isAdmin ? Auth::id() : null;
            $whereUser = $userId ? "AND l.user_id = $userId" : "";
            $whereDate = ($startDate && $endDate) ? "AND l.created_at BETWEEN '$startDate' AND '$endDate'" : "";

            $totalAktivitas = DB::select("SELECT COUNT(*) as count FROM log_aktivitas l WHERE 1=1 $whereUser $whereDate")[0]->count;
            $bulanIni = DB::select("SELECT COUNT(*) as count FROM log_aktivitas l WHERE MONTH(created_at) = ? $whereUser $whereDate", [now()->month])[0]->count;
            $totalDurasi = DB::select("SELECT SUM(a.durasi_menit) as sum FROM log_aktivitas l JOIN aktivitas a ON l.aktivitas_id = a.id WHERE 1=1 $whereUser $whereDate")[0]->sum ?? 0;
            $distinctDays = DB::select("SELECT COUNT(DISTINCT DATE(created_at)) as count FROM log_aktivitas l WHERE 1=1 $whereUser $whereDate")[0]->count;
            $rataRataHarian = $distinctDays > 0 ? round($totalDurasi / $distinctDays, 1) : 0;
        }

        return view('admin.logs', compact('logs', 'totalAktivitas', 'bulanIni', 'totalDurasi', 'rataRataHarian', 'search', 'startDate', 'endDate', 'type'));
    }

    /**
     * Display the specified resource.
     */
    public function show(LogAktivitas $logAktivitas)
    {
        // Validasi: user hanya bisa akses logs miliknya, kecuali admin
        if (Auth::user()->email !== 'admin@example.com' && $logAktivitas->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $logAktivitas->load(['aktivitas.hobi']);

        // Decode bukti from log
        $bukti = [];
        if ($fileBukti = $logAktivitas->file_bukti) {
            $decoded = json_decode($fileBukti, true) ?: [];
            if (isset($decoded['file'])) {
                $bukti[] = Storage::url($decoded['file']);
            }
            if (isset($decoded['gdrive'])) {
                $bukti[] = $decoded['gdrive'];
            }
        }

        return response()->json([
            'tanggal' => $logAktivitas->created_at->format('d F Y'),
            'waktu_upload' => $logAktivitas->created_at->format('H:i'),
            'aktivitas' => $logAktivitas->aktivitas->nama_aktivitas,
            'hobi' => $logAktivitas->aktivitas->hobi->nama_hobi,
            'durasi' => $logAktivitas->aktivitas->durasi_menit . ' Menit',
            'catatan' => $logAktivitas->catatan ?: 'tidak ada catatan',
            'bukti' => $bukti
        ]);
    }

    /**
     * Display the specified target progress.
     */
    public function showTarget($id)
    {
        $progres = ProgresTarget::with(['targetHobi.hobi'])->findOrFail($id);

        // Validasi: user hanya bisa akses logs miliknya, kecuali admin
        if (Auth::user()->email !== 'admin@example.com' && $progres->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Decode bukti from progres
        $bukti = [];
        if ($progres->file_bukti) {
            $bukti[] = Storage::url($progres->file_bukti);
        }
        if ($progres->link_gdrive) {
            $bukti[] = $progres->link_gdrive;
        }

        return response()->json([
            'tanggal' => $progres->created_at->format('d F Y'),
            'waktu_upload' => $progres->created_at->format('H:i'),
            'target' => $progres->targetHobi->nama_target,
            'hobi' => $progres->targetHobi->hobi->nama_hobi,
            'status' => ucfirst($progres->status),
            'deadline' => \Carbon\Carbon::parse($progres->targetHobi->target_deadline)->format('d F Y'),
            'catatan' => $progres->catatan ?: 'tidak ada catatan',
            'bukti' => $bukti
        ]);
    }

    /**
     * Export logs to CSV.
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'aktivitas');
        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $isAdmin = Auth::user()->email === 'admin@example.com';

        if ($type === 'target') {
            $query = ProgresTarget::with(['targetHobi.hobi']);
            
            if (!$isAdmin) {
                $query->where('user_id', Auth::id());
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('targetHobi', fn($sub) => $sub->where('nama_target', 'like', "%{$search}%"))
                      ->orWhere('catatan', 'like', "%{$search}%");
                });
            }

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $logs = $query->orderBy('created_at', 'desc')->get();

            $filename = 'logs_target_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($logs) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Tanggal', 'Target', 'Hobi', 'Status', 'Catatan']);

                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->created_at->format('d F Y'),
                        $log->targetHobi->nama_target,
                        $log->targetHobi->hobi->nama_hobi,
                        ucfirst($log->status),
                        $log->catatan,
                    ]);
                }

                fclose($file);
            };

        } else {
            $query = LogAktivitas::with(['aktivitas.hobi']);
            
            if (!$isAdmin) {
                $query->where('user_id', Auth::id());
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('aktivitas', fn($sub) => $sub->where('nama_aktivitas', 'like', "%{$search}%"))
                      ->orWhere('catatan', 'like', "%{$search}%");
                });
            }

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $logs = $query->orderBy('created_at', 'desc')->get();

            $filename = 'logs_aktivitas_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($logs) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Tanggal', 'Aktivitas', 'Hobi', 'Durasi (Menit)', 'Catatan']);

                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->created_at->format('d F Y'),
                        $log->aktivitas->nama_aktivitas,
                        $log->aktivitas->hobi->nama_hobi,
                        $log->aktivitas->durasi_menit,
                        $log->catatan,
                    ]);
                }

                fclose($file);
            };
        }

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogAktivitas $logAktivitas)
    {
        if (Auth::user()->email !== 'admin@example.com') {
            return redirect()->route('admin.logs')->with('error', 'Unauthorized');
        }

        $logAktivitas->delete();
        return redirect()->route('admin.logs')->with('success', 'Log berhasil dihapus');
    }
}