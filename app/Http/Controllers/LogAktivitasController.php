<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
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
        // Ambil logs dengan relasi, filter by user jika bukan admin
        $query = LogAktivitas::with(['aktivitas.hobi', 'user']);
        if (Auth::user()->email !== 'admin@example.com') {
            $query->where('user_id', Auth::id());
        }

        // Search berdasarkan nama aktivitas atau catatan
        $search = $request->get('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('aktivitas', fn($sub) => $sub->where('nama_aktivitas', 'like', "%{$search}%"))
                  ->orWhere('catatan', 'like', "%{$search}%");
            });
        }

        // Filter tanggal
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $logs = $query->orderBy($sortBy, $direction)->paginate(10);

        // Hitung stats dengan query efisien
        $userId = Auth::user()->email !== 'admin@example.com' ? Auth::id() : null;
        $whereUser = $userId ? "AND l.user_id = $userId" : "";
        $whereDate = ($startDate && $endDate) ? "AND l.created_at BETWEEN '$startDate' AND '$endDate'" : "";

        $totalAktivitas = DB::select("SELECT COUNT(*) as count FROM log_aktivitas l WHERE 1=1 $whereUser $whereDate")[0]->count;
        $bulanIni = DB::select("SELECT COUNT(*) as count FROM log_aktivitas l WHERE MONTH(created_at) = ? $whereUser $whereDate", [now()->month])[0]->count;
        $totalDurasi = DB::select("SELECT SUM(a.durasi_menit) as sum FROM log_aktivitas l JOIN aktivitas a ON l.aktivitas_id = a.id WHERE 1=1 $whereUser $whereDate")[0]->sum ?? 0;
        $distinctDays = DB::select("SELECT COUNT(DISTINCT DATE(created_at)) as count FROM log_aktivitas l WHERE 1=1 $whereUser $whereDate")[0]->count;
        $rataRataHarian = $distinctDays > 0 ? round($totalDurasi / $distinctDays, 1) : 0;

        return view('admin.logs', compact('logs', 'totalAktivitas', 'bulanIni', 'totalDurasi', 'rataRataHarian', 'search', 'startDate', 'endDate'));
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
        //
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

        $logAktivitas->load(['aktivitas.hobi', 'user']);

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
     * Show the form for editing the specified resource.
     */
    public function edit(LogAktivitas $logAktivitas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogAktivitasController $logAktivitas)
    {
        //
    }

    /**
     * Export logs to CSV.
     */
    public function export(Request $request)
    {
        $query = LogAktivitas::with(['aktivitas.hobi', 'user']);
        if (Auth::user()->email !== 'admin@example.com') {
            $query->where('user_id', Auth::id());
        }

        // Apply same filters as index
        $search = $request->get('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('aktivitas', fn($sub) => $sub->where('nama_aktivitas', 'like', "%{$search}%"))
                  ->orWhere('catatan', 'like', "%{$search}%");
            });
        }

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $filename = 'logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
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

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogAktivitas $logAktivitas)
    {
        // Validasi: hanya admin yang bisa delete
        if (Auth::user()->email !== 'admin@example.com') {
            return redirect()->route('admin.logs')->with('error', 'Unauthorized');
        }

        $logAktivitas->delete();
        return redirect()->route('admin.logs')->with('success', 'Log berhasil dihapus');
    }
}
