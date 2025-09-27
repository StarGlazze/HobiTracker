<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('aktivitas', fn($sub) => $sub->where('nama_aktivitas', 'like', "%{$search}%"))
                  ->orWhere('catatan', 'like', "%{$search}%");
            });
        }

        // Paginasi
        $logs = $query->orderBy('created_at', 'desc')->paginate(10);

        // Hitung stats (global atau per user)
        $statsQuery = LogAktivitas::query();
        if (Auth::user()->email !== 'admin@example.com') {
            $statsQuery->where('user_id', Auth::id());
        }
        $totalAktivitas = (clone $statsQuery)->count();
        $bulanIni = (clone $statsQuery)->whereMonth('created_at', now()->month)->count();
        $totalDurasi = (clone $statsQuery)->with('aktivitas')->get()->sum(fn($log) => $log->aktivitas->durasi_menit ?? 0);
        $rataRataHarian = $totalDurasi > 0 ? round($totalDurasi / (clone $statsQuery)->distinct('DATE(created_at)')->count(), 1) : 0;

        return view('admin.logs', compact('logs', 'totalAktivitas', 'bulanIni', 'totalDurasi', 'rataRataHarian', 'search'));
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
        $logAktivitas->load(['aktivitas.hobi', 'user']);
        return response()->json([
            'tanggal' => $logAktivitas->created_at->format('d F Y'),
            'waktu_mulai' => $logAktivitas->created_at->format('H:i'),
            'waktu_selesai' => $logAktivitas->created_at->addMinutes($logAktivitas->aktivitas->durasi_menit)->format('H:i'),
            'aktivitas' => $logAktivitas->aktivitas->nama_aktivitas,
            'hobi' => $logAktivitas->aktivitas->hobi->nama_hobi,
            'durasi' => $logAktivitas->aktivitas->durasi_menit . ' Menit',
            'status' => 'Selesai', // Placeholder
            'catatan' => $logAktivitas->catatan,
            'bukti' => $logAktivitas->aktivitas->file_bukti ?? []
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
     * Remove the specified resource from storage.
     */
    public function destroy(LogAktivitas $logAktivitas)
    {
        //
    }
}
