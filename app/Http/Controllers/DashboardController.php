<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Hobi;
use App\Models\TargetHobi;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Statistik Cards
        $totalHobbies = Hobi::where('user_id', $user->id)->count();
        $totalActivities = Aktivitas::whereHas('target.hobi', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        $activeTargets = TargetHobi::where('user_id', $user->id)
            ->where('target_deadline', '>=', now())
            ->count();

        // Progress Rate: rata-rata progress dari target aktif
        $progressRate = $this->getProgressRate($user->id);

        // Activities Progress: progress total aktivitas vs total target aktivitas
        $totalNeededActivities = TargetHobi::where('user_id', $user->id)->sum('jumlah_aktivitas_dibutuhkan');
        $activitiesProgress = $totalNeededActivities > 0 ? ($totalActivities / $totalNeededActivities) * 100 : 0;

        // Active Hobbies This Month
        $activeHobbiesThisMonth = Hobi::where('user_id', $user->id)
            ->whereHas('targetHobi.aktivitas', function ($query) {
                $query->where('created_at', '>=', now()->startOfMonth());
            })
            ->count();

        // Data untuk Chart Activity Overview (aktivitas per kategori hobi bulan ini)
        $chartData = $this->getChartData($user->id, 'monthly');

        // Check if user has any activities at all
        $hasAnyActivities = Aktivitas::whereHas('target.hobi', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();

        // Weekly Stats
        $weeklyStats = $this->getWeeklyStats($user->id);

        // Target Progress (3 target terbaru)
        $targetProgress = $this->getTargetProgress($user->id);

        // Recent Logs (4 terbaru)
        $recentLogs = $this->getRecentLogs($user->id);

        return view('admin.dashboard', [
            'totalHobbies' => $totalHobbies,
            'totalActivities' => $totalActivities,
            'activeTargets' => $activeTargets,
            'progressRate' => $progressRate,
            'activitiesProgress' => round($activitiesProgress),
            'activeHobbiesThisMonth' => $activeHobbiesThisMonth,
            'chartData' => $chartData,
            'hasAnyActivities' => $hasAnyActivities,
            'weeklyStats' => $weeklyStats,
            'targetProgress' => $targetProgress,
            'recentLogs' => $recentLogs,
        ]);
    }

    // AJAX endpoint untuk filter periode chart
    public function getChartDataAjax(Request $request)
    {
        try {
            $user = Auth::user();
            $period = $request->input('period', 'monthly');

            // Validasi period
            if (!in_array($period, ['daily', 'weekly', 'monthly'])) {
                $period = 'monthly';
            }

            $chartData = $this->getChartData($user->id, $period);

            // Log untuk debugging
            Log::info('Chart data requested', [
                'user_id' => $user->id,
                'period' => $period,
                'data' => $chartData
            ]);

            return response()->json($chartData);
        } catch (\Exception $e) {
            Log::error('Error getting chart data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'categories' => ['Error'],
                'series' => [0],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getChartData($userId, $period = 'monthly')
    {
        try {
            // Tentukan range tanggal berdasarkan periode
            $startDate = match ($period) {
                'daily' => now()->startOfDay(),
                'weekly' => now()->startOfWeek(),
                'monthly' => now()->startOfMonth(),
                default => now()->startOfMonth(),
            };

            // Aktivitas per kategori hobi dalam periode tertentu
            $aktivitas = Aktivitas::whereHas('target.hobi', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
                ->where('created_at', '>=', $startDate)
                ->with(['target.hobi.kategoriHobi'])
                ->get();

            // Group by kategori
            $data = $aktivitas->groupBy(function ($item) {
                return $item->target && $item->target->hobi && $item->target->hobi->kategoriHobi
                    ? $item->target->hobi->kategoriHobi->nama_kategori
                    : 'Uncategorized';
            })
                ->map(function ($group) {
                    return $group->count();
                })
                ->sortDesc();

            // Jika tidak ada data, berikan data default
            if ($data->isEmpty()) {
                return [
                    'categories' => ['Belum ada data'],
                    'series' => [0],
                ];
            }

            return [
                'categories' => array_values($data->keys()->toArray()),
                'series' => array_values($data->values()->toArray()),
            ];
        } catch (\Exception $e) {
            Log::error('Error in getChartData', [
                'user_id' => $userId,
                'period' => $period,
                'error' => $e->getMessage()
            ]);

            return [
                'categories' => ['Error'],
                'series' => [0],
            ];
        }
    }

    private function getWeeklyStats($userId)
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        // Top Hobby minggu ini
        $topHobbyData = Aktivitas::whereHas('target.hobi', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->with('target.hobi')
            ->get()
            ->groupBy(function ($item) {
                return $item->target && $item->target->hobi
                    ? $item->target->hobi->nama_hobi
                    : 'Unknown';
            })
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc();

        $topHobbyName = $topHobbyData->isNotEmpty() ? $topHobbyData->keys()->first() : 'Belum ada';
        $topHobbyCount = $topHobbyData->isNotEmpty() ? $topHobbyData->first() : 0;

        // Consistency Streak (hari berturut-turut dengan aktivitas)
        $streak = $this->calculateStreak($userId);

        // Goals Progress minggu ini
        $goalsProgress = $this->calculateGoalsProgress($userId);

        // Total Activities This Week
        $totalActivitiesThisWeek = Aktivitas::whereHas('target.hobi', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();

        // Most Productive Day minggu ini
        $productiveDayData = Aktivitas::whereHas('target.hobi', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('l'); // Nama hari
            })
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc();

        $mostProductiveDay = $productiveDayData->isNotEmpty() ? $productiveDayData->keys()->first() : 'Belum ada';
        $mostProductiveCount = $productiveDayData->isNotEmpty() ? $productiveDayData->first() : 0;

        return [
            'topHobby' => $topHobbyName,
            'topHobbyCount' => $topHobbyCount,
            'consistencyStreak' => $streak,
            'goalsProgress' => $goalsProgress,
            'totalActivitiesThisWeek' => $totalActivitiesThisWeek,
            'mostProductiveDay' => $mostProductiveDay,
            'mostProductiveCount' => $mostProductiveCount,
        ];
    }

    private function calculateStreak($userId)
    {
        $streak = 0;
        $currentDate = now()->toDateString();

        while (true) {
            $hasActivity = Aktivitas::whereHas('target.hobi', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
                ->whereDate('created_at', $currentDate)
                ->exists();

            if ($hasActivity) {
                $streak++;
                $currentDate = Carbon::parse($currentDate)->subDay()->toDateString();
            } else {
                break;
            }
        }

        return $streak;
    }

    private function calculateGoalsProgress($userId)
    {
        $targets = TargetHobi::where('user_id', $userId)->with('aktivitas')->get();
        if ($targets->isEmpty()) return 0;

        $totalProgress = 0;
        foreach ($targets as $target) {
            $done = $target->aktivitas->count();
            $needed = $target->jumlah_aktivitas_dibutuhkan;
            $progress = $needed > 0 ? min(($done / $needed) * 100, 100) : 0;
            $totalProgress += $progress;
        }

        return round($totalProgress / $targets->count());
    }

    private function getProgressRate($userId)
    {
        $targets = TargetHobi::where('user_id', $userId)
            ->where('target_deadline', '>=', now())
            ->with('aktivitas')
            ->get();

        $progressRate = 0;
        if ($targets->count() > 0) {
            $totalProgress = 0;
            foreach ($targets as $target) {
                $done = $target->aktivitas->count();
                $needed = $target->jumlah_aktivitas_dibutuhkan;
                $progress = $needed > 0 ? min(($done / $needed) * 100, 100) : 0;
                $totalProgress += $progress;
            }
            $progressRate = round($totalProgress / $targets->count());
        }

        return $progressRate;
    }

    private function getTargetProgress($userId)
    {
        return TargetHobi::where('user_id', $userId)
            ->with('aktivitas', 'hobi')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($target) {
                $done = $target->aktivitas->count();
                $needed = $target->jumlah_aktivitas_dibutuhkan;
                $progress = $needed > 0 ? min(($done / $needed) * 100, 100) : 0;
                return [
                    'nama_target' => $target->nama_target,
                    'progress' => round($progress),
                    'deadline' => $target->target_deadline->format('d M Y'),
                ];
            });
    }

    private function getRecentLogs($userId)
    {
        return LogAktivitas::where('user_id', $userId)
            ->with('aktivitas')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get()
            ->map(function ($log) {
                return [
                    'nama_aktivitas' => $log->aktivitas ? $log->aktivitas->nama_aktivitas : 'Aktivitas',
                    'waktu' => $log->created_at->diffForHumans(),
                    'mood' => $log->aktivitas && $log->aktivitas->energy_mood_level
                        ? $log->aktivitas->energy_mood_level
                        : '-',
                    'energi' => 'Tinggi', // Placeholder, bisa disesuaikan
                ];
            });
    }
}
