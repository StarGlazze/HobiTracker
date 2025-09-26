<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ProgresTarget;
use App\Models\TargetHobi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UpdateExpiredTarget
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only run for authenticated users
        if (Auth::check()) {
            $this->updateExpiredTargetsForUser(Auth::id());
        }

        return $next($request);
    }

    /**
     * Update expired targets for specific user
     */
    private function updateExpiredTargetsForUser($userId)
    {
        try {
            // Get expired targets that still have on_progress status
            $expiredProgres = ProgresTarget::whereHas('targetHobi', function($query) use ($userId) {
                    $query->where('user_id', $userId)
                          ->where('target_deadline', '<', now()->startOfDay());
                })
                ->where('status', 'on_progress')
                ->get();

            // Update status to failed
            foreach ($expiredProgres as $progres) {
                $progres->update([
                    'status' => 'failed',
                    'catatan' => ($progres->catatan ? $progres->catatan . "\n\n" : '') . 
                                '[AUTO] Target expired on ' . now()->format('Y-m-d H:i:s')
                ]);
            }

            // Log for debugging (optional)
            if ($expiredProgres->count() > 0) {
                Log::info("Updated {$expiredProgres->count()} expired targets for user {$userId}");
            }

        } catch (\Exception $e) {
            // Silent fail - don't break the application
            Log::error("Failed to update expired targets for user {$userId}: " . $e->getMessage());
        }
    }
}

// Alternative: Helper Class (if you prefer not to use middleware)
class TargetStatusHelper 
{
    /**
     * Update expired targets to failed status
     */
    public static function updateExpiredTargets($userId = null)
    {
        $userId = $userId ?: Auth::id();
        
        if (!$userId) {
            return 0;
        }

        try {
            // Get all on_progress status for expired targets
            $expiredProgres = ProgresTarget::whereHas('targetHobi', function($query) use ($userId) {
                    $query->where('user_id', $userId)
                          ->where('target_deadline', '<', now()->startOfDay());
                })
                ->where('status', 'on_progress')
                ->get();

            $updated = 0;
            foreach ($expiredProgres as $progres) {
                $progres->update([
                    'status' => 'failed',
                    'catatan' => ($progres->catatan ? $progres->catatan . "\n\n" : '') . 
                                '[AUTO] Target expired on ' . now()->format('Y-m-d H:i:s')
                ]);
                $updated++;
            }

            return $updated;

        } catch (\Exception $e) {
            Log::error("Failed to update expired targets: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get target statistics for user
     */
    public static function getTargetStats($userId = null)
    {
        $userId = $userId ?: Auth::id();
        
        if (!$userId) {
            return null;
        }

        // Update expired targets first
        self::updateExpiredTargets($userId);

        $stats = [
            'total_targets' => TargetHobi::where('user_id', $userId)->count(),
            'active_targets' => TargetHobi::where('user_id', $userId)
                                         ->where('target_deadline', '>=', now()->startOfDay())
                                         ->count(),
            'expired_targets' => TargetHobi::where('user_id', $userId)
                                          ->where('target_deadline', '<', now()->startOfDay())
                                          ->count(),
            'completed_progress' => ProgresTarget::whereHas('targetHobi', function($query) use ($userId) {
                                        $query->where('user_id', $userId);
                                    })
                                    ->where('status', 'completed')
                                    ->count(),
            'failed_progress' => ProgresTarget::whereHas('targetHobi', function($query) use ($userId) {
                                     $query->where('user_id', $userId);
                                 })
                                 ->where('status', 'failed')
                                 ->count(),
            'on_progress' => ProgresTarget::whereHas('targetHobi', function($query) use ($userId) {
                                 $query->where('user_id', $userId);
                             })
                             ->where('status', 'on_progress')
                             ->count(),
        ];

        return $stats;
    }

    /**
     * Check if target deadline validation should be bypassed (for admin)
     */
    public static function canBypassDeadline()
    {
        return Auth::check() && Auth::user()->email === 'admin@example.com';
    }

    /**
     * Get targets that will expire soon (within next 7 days)
     */
    public static function getTargetsExpiringSoon($userId = null, $days = 7)
    {
        $userId = $userId ?: Auth::id();
        
        if (!$userId) {
            return collect();
        }

        return TargetHobi::where('user_id', $userId)
                         ->where('target_deadline', '>=', now()->startOfDay())
                         ->where('target_deadline', '<=', now()->addDays($days)->endOfDay())
                         ->whereHas('progresTarget', function($query) {
                             $query->where('status', 'on_progress');
                         })
                         ->orWhere(function($query) use ($userId) {
                             $query->where('user_id', $userId)
                                   ->where('target_deadline', '>=', now()->startOfDay())
                                   ->where('target_deadline', '<=', now()->addDays(7)->endOfDay())
                                   ->doesntHave('progresTarget');
                         })
                         ->with('hobi', 'progresTarget')
                         ->orderBy('target_deadline', 'asc')
                         ->get();
    }
}