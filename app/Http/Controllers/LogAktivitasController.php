<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\TargetHobi;
use App\Exports\LogsExport;
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
        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $sortBy = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $isAdmin = Auth::user()->email === 'admin@example.com';

        $query = LogAktivitas::with(['aktivitas.target.hobi']);

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

        if ($isAdmin) {
            // For admin, show all logs
            $totalAktivitas = LogAktivitas::when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                return $q->whereBetween('created_at', [$startDate, $endDate]);
            })->count();

            $bulanIni = LogAktivitas::whereMonth('created_at', now()->month)
                ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                    return $q->whereBetween('created_at', [$startDate, $endDate]);
                })->count();
        } else {
            // For regular users, show only their logs
            $totalAktivitas = LogAktivitas::where('user_id', Auth::id())
                ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                    return $q->whereBetween('created_at', [$startDate, $endDate]);
                })->count();

            $bulanIni = LogAktivitas::where('user_id', Auth::id())
                ->whereMonth('created_at', now()->month)
                ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                    return $q->whereBetween('created_at', [$startDate, $endDate]);
                })->count();
        }

        return view('admin.logs', compact('logs', 'totalAktivitas', 'bulanIni', 'search', 'startDate', 'endDate'));
    }

    /**
     * Display the specified resource.
     * Route: GET /log-aktivitas/{id}
     */
    public function show($id)
    {
        // Log request info
        Log::info("=== LOG DETAIL REQUEST ===");
        Log::info("ID: {$id}");
        Log::info("User ID: " . Auth::id());
        Log::info("User Email: " . Auth::user()->email);
        Log::info("Request Headers: ", request()->headers->all());

        try {
            // Cari log berdasarkan ID
            $logAktivitas = LogAktivitas::find($id);

            if (!$logAktivitas) {
                Log::error("Log not found: {$id}");
                return response()->json([
                    'error' => 'Log tidak ditemukan',
                    'id' => $id
                ], 404);
            }

            Log::info("Log found: {$id}");

            // Check authorization
            if (Auth::user()->email !== 'admin@example.com' && $logAktivitas->user_id !== Auth::id()) {
                Log::warning("Unauthorized access attempt to log {$id} by user " . Auth::id());
                return response()->json([
                    'error' => 'Anda tidak memiliki akses ke data ini',
                    'log_user_id' => $logAktivitas->user_id,
                    'current_user_id' => Auth::id()
                ], 403);
            }

            Log::info("Authorization passed");

            // Load relasi
            try {
                $logAktivitas->load(['aktivitas.target.hobi']);
                Log::info("Relations loaded successfully");
            } catch (\Exception $e) {
                Log::error("Error loading relations: " . $e->getMessage());
                // Continue anyway, we'll handle missing relations below
            }

            // Process bukti files
            $bukti = [];
            if ($fileBukti = $logAktivitas->file_bukti) {
                Log::info("File bukti raw: " . $fileBukti);

                // Coba decode sebagai JSON
                $decoded = json_decode($fileBukti, true);

                if (is_array($decoded)) {
                    Log::info("File bukti is JSON array");
                    if (isset($decoded['file']) && !empty($decoded['file'])) {
                        $url = Storage::url($decoded['file']);
                        $bukti[] = $url;
                        Log::info("Added file: " . $url);
                    }
                    if (isset($decoded['gdrive']) && !empty($decoded['gdrive'])) {
                        $bukti[] = $decoded['gdrive'];
                        Log::info("Added gdrive: " . $decoded['gdrive']);
                    }
                } elseif (is_string($fileBukti) && !empty($fileBukti)) {
                    Log::info("File bukti is string");
                    if (filter_var($fileBukti, FILTER_VALIDATE_URL)) {
                        $bukti[] = $fileBukti;
                        Log::info("Added URL: " . $fileBukti);
                    } else {
                        $url = Storage::url($fileBukti);
                        $bukti[] = $url;
                        Log::info("Added storage path: " . $url);
                    }
                } else {
                    Log::info("File bukti format unknown: " . gettype($fileBukti));
                }
            } else {
                Log::info("No file bukti");
            }

            Log::info("Total bukti: " . count($bukti));

            $responseData = [
                'tanggal' => $logAktivitas->created_at->format('d F Y'),
                'waktu_upload' => $logAktivitas->created_at->format('H:i'),
                'aktivitas' => optional($logAktivitas->aktivitas)->nama_aktivitas ?? 'tidak ada',
                'target' => optional(optional($logAktivitas->aktivitas)->target)->nama_target ?? 'tidak ada',
                'hobi' => optional(optional(optional($logAktivitas->aktivitas)->target)->hobi)->nama_hobi ?? 'tidak ada',
                'energy_mood_level' => optional($logAktivitas->aktivitas)->energy_mood_level ?? '-',
                'catatan' => $logAktivitas->catatan ?: 'tidak ada catatan',
                'bukti' => $bukti
            ];

            Log::info("Response data prepared", $responseData);
            Log::info("=== LOG DETAIL SUCCESS ===");

            return response()->json($responseData, 200, [], JSON_UNESCAPED_SLASHES);
        } catch (\Exception $e) {
            Log::error("=== LOG DETAIL ERROR ===");
            Log::error("Error: " . $e->getMessage());
            Log::error("File: " . $e->getFile());
            Log::error("Line: " . $e->getLine());
            Log::error("Trace: " . $e->getTraceAsString());

            return response()->json([
                'error' => 'Gagal memuat detail log',
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan server',
                'file' => config('app.debug') ? $e->getFile() : null,
                'line' => config('app.debug') ? $e->getLine() : null
            ], 500);
        }
    }

    /**
     * Export logs to PDF.
     */
    public function export(Request $request)
    {
        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $sortBy = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $isAdmin = Auth::user()->email === 'admin@example.com';

        $query = LogAktivitas::with(['aktivitas.target.hobi']);

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

        // Get current page logs only (same as pagination)
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $logs = $query->orderBy($sortBy, $direction)
            ->paginate($perPage, ['*'], 'page', $currentPage);

        // Create export with current page data
        $logsExport = new LogsExport($request, $logs, $request->has('include_images'));
        return $logsExport->export();
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
