<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\ProgresTarget;
use App\Models\TargetHobi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class LogAktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'aktivitas');
        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $sortBy = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $isAdmin = Auth::user()->email === 'admin@example.com';

        if ($type === 'target') {
            // Update expired targets before showing logs
            $this->updateExpiredTargets();

            $query = ProgresTarget::with(['targetHobi.hobi.kategoriHobi']);
            
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

            // Group by target_id and get only the latest progress per target
            $logs = $query->orderBy($sortBy, $direction)
                         ->get()
                         ->groupBy('target_id')
                         ->map(function ($group) {
                             return $group->sortByDesc('created_at')->first();
                         })
                         ->values();

            // Convert to paginator manually
            $perPage = 5;
            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $currentItems = $logs->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $logs = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItems,
                $logs->count(),
                $perPage,
                $currentPage,
                ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
            );

            // Stats calculation
            $userId = !$isAdmin ? Auth::id() : null;
            $statsQuery = ProgresTarget::query();
            
            if ($userId) {
                $statsQuery->where('user_id', $userId);
            }
            
            if ($startDate && $endDate) {
                $statsQuery->whereBetween('created_at', [$startDate, $endDate]);
            }

            // Get all progress and group by target_id to get latest status
            $allProgress = $statsQuery->get()->groupBy('target_id')->map(function ($group) {
                return $group->sortByDesc('created_at')->first();
            });

            $totalAktivitas = $allProgress->count();
            $bulanIni = $allProgress->filter(function ($item) {
                return $item->created_at->month === now()->month;
            })->count();
            $completed = $allProgress->where('status', 'completed')->count();
            $failed = $allProgress->where('status', 'failed')->count();

            $totalDurasi = $completed;
            $rataRataHarian = $failed;

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

            $logs = $query->orderBy($sortBy, $direction)->paginate(10);

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
        try {
            if (Auth::user()->email !== 'admin@example.com' && $logAktivitas->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $logAktivitas->load(['aktivitas.hobi']);

            // Check if relations exist
            if (!$logAktivitas->aktivitas || !$logAktivitas->aktivitas->hobi) {
                return response()->json(['error' => 'Data aktivitas tidak lengkap'], 404);
            }

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
        } catch (\Exception $e) {
            Log::error('Error loading aktivitas detail: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat detail: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified target progress.
     */
    public function showTarget($id)
    {
        try {
            $progres = ProgresTarget::with(['targetHobi.hobi'])->findOrFail($id);

            if (Auth::user()->email !== 'admin@example.com' && $progres->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Check if relations exist
            if (!$progres->targetHobi || !$progres->targetHobi->hobi) {
                return response()->json(['error' => 'Data target tidak lengkap'], 404);
            }

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
        } catch (\Exception $e) {
            Log::error('Error loading target detail: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat detail: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Export logs to PDF.
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
        }

        $data = [
            'logs' => $logs,
            'type' => $type,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedDate' => now()->format('d F Y H:i')
        ];

        $pdf = Pdf::loadView('admin.logs_pdf', $data);
        $filename = 'logs_' . $type . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
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

    /**
     * Update expired targets to failed status
     */
    private function updateExpiredTargets()
    {
        $userId = Auth::user()->email === 'admin@example.com' ? null : Auth::id();
        
        // Get all expired targets
        $expiredTargetsQuery = TargetHobi::where('target_deadline', '<', now()->startOfDay())
                                        ->with('progresTarget');
        
        if ($userId) {
            $expiredTargetsQuery->where('user_id', $userId);
        }
        
        $expiredTargets = $expiredTargetsQuery->get();

        foreach ($expiredTargets as $target) {
            // Update existing on_progress to failed
            $target->progresTarget()
                   ->where('status', 'on_progress')
                   ->update(['status' => 'failed']);
            
            // Auto-create failed entry for targets without any progress
            if ($target->progresTarget->isEmpty()) {
                ProgresTarget::create([
                    'user_id' => $target->user_id,
                    'target_id' => $target->id,
                    'status' => 'failed',
                    'catatan' => 'Target expired tanpa progress',
                    'file_bukti' => null,
                    'link_gdrive' => null,
                ]);
            }
        }

        return $expiredTargets->count();
    }
}