<?php

namespace App\Http\Controllers;

use App\Models\TargetHobi;
use App\Models\ProgresTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TargetHobiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Auto update expired targets before displaying
        $this->updateExpiredTargets();
        
        $targets = TargetHobi::with('hobi.kategoriHobi', 'progresTarget', 'aktivitas')
                    ->where('user_id', Auth::id())
                    ->orderBy('target_deadline', 'asc')
                    ->get();
        
        $hobis = \App\Models\Hobi::with('kategoriHobi')
                    ->where('user_id', Auth::id())
                    ->get();

        return view('admin.target', compact('targets', 'hobis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hobis = \App\Models\Hobi::where('user_id', Auth::id())->get();
        return view('admin.target', compact('hobis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'hobi_id' => 'required|exists:hobis,id',
            'nama_target' => 'required|string|max:255',
            'target_deadline' => 'required|date|after:yesterday',
            'jumlah_aktivitas_dibutuhkan' => 'required|integer|min:1',
        ], [
            'target_deadline.after' => 'Batas waktu harus setelah hari kemarin.',
            'hobi_id.required' => 'Silakan pilih hobi.',
            'hobi_id.exists' => 'Hobi yang dipilih tidak valid.',
            'nama_target.required' => 'Nama target wajib diisi.',
            'jumlah_aktivitas_dibutuhkan.required' => 'Jumlah aktivitas dibutuhkan wajib diisi.',
            'jumlah_aktivitas_dibutuhkan.integer' => 'Jumlah aktivitas harus berupa angka.',
            'jumlah_aktivitas_dibutuhkan.min' => 'Jumlah aktivitas minimal 1.',
        ]);

        // Verify hobi belongs to user
        $hobi = \App\Models\Hobi::where('id', $request->hobi_id)
                                ->where('user_id', Auth::id())
                                ->first();
        
        if (!$hobi) {
            return back()->withErrors(['hobi_id' => 'Hobi yang dipilih tidak valid.'])
                        ->withInput();
        }

        TargetHobi::create([
            'user_id' => Auth::id(),
            'hobi_id' => $request->hobi_id,
            'nama_target' => $request->nama_target,
            'target_deadline' => $request->target_deadline,
            'jumlah_aktivitas_dibutuhkan' => $request->jumlah_aktivitas_dibutuhkan,
        ]);

        return redirect()->route('admin.target.index')
                        ->with('success', 'Target berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TargetHobi $targetHobi)
    {
        if ($targetHobi->user_id != Auth::id()) {
            abort(403);
        }
        $targetHobi->load('hobi', 'progresTarget');
        return view('admin.target', compact('targetHobi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TargetHobi $targetHobi)
    {
        if (Auth::user()->email !== 'admin@example.com' && $targetHobi->user_id != Auth::id()) {
            abort(403);
        }
        $hobis = \App\Models\Hobi::where('user_id', Auth::id())->get();
        return view('admin.target', compact('targetHobi', 'hobis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TargetHobi $target)
    {
        if (Auth::user()->email !== 'admin@example.com' && $target->user_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'hobi_id' => 'required|exists:hobis,id',
            'nama_target' => 'required|string|max:255',
            'target_deadline' => 'required|date|after:yesterday',
            'jumlah_aktivitas_dibutuhkan' => 'required|integer|min:1',
        ], [
            'target_deadline.after' => 'Batas waktu harus setelah hari kemarin.',
            'hobi_id.required' => 'Silakan pilih hobi.',
            'hobi_id.exists' => 'Hobi yang dipilih tidak valid.',
            'nama_target.required' => 'Nama target wajib diisi.',
            'jumlah_aktivitas_dibutuhkan.required' => 'Jumlah aktivitas dibutuhkan wajib diisi.',
            'jumlah_aktivitas_dibutuhkan.integer' => 'Jumlah aktivitas harus berupa angka.',
            'jumlah_aktivitas_dibutuhkan.min' => 'Jumlah aktivitas minimal 1.',
        ]);

        // Verify hobi belongs to user (except for admin)
        if (Auth::user()->email !== 'admin@example.com') {
            $hobi = \App\Models\Hobi::where('id', $request->hobi_id)
                                    ->where('user_id', Auth::id())
                                    ->first();
            
            if (!$hobi) {
                return back()->withErrors(['hobi_id' => 'Hobi yang dipilih tidak valid.'])
                            ->withInput();
            }
        }

        $target->update($request->only(['hobi_id', 'nama_target', 'target_deadline', 'jumlah_aktivitas_dibutuhkan']));

        // Update any failed progress back to on_progress if deadline is extended
        if ($request->target_deadline > now()->format('Y-m-d')) {
            $target->progresTarget()
                   ->where('status', 'failed')
                   ->where('created_at', '>', now()->subDay()) // Only recent fails
                   ->update(['status' => 'on_progress']);
        }

        return redirect()->route('admin.target.index')
                        ->with('success', 'Target berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TargetHobi $target)
    {
        if (Auth::user()->email !== 'admin@example.com' && $target->user_id != Auth::id()) {
            abort(403);
        }

        // Delete associated progress files
        foreach ($target->progresTarget as $progres) {
            if ($progres->file_bukti) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($progres->file_bukti);
            }
        }

        $target->delete();

        return redirect()->route('admin.target.index')
                        ->with('success', 'Target berhasil dihapus.');
    }

    /**
     * Display combined target and progress page.
     */
    public function indexProgres()
    {
        $this->updateExpiredTargets();
        
        $targets = TargetHobi::with('hobi', 'progresTarget')
                    ->where('user_id', Auth::id())
                    ->orderBy('target_deadline', 'asc')
                    ->get();
        
        $hobis = \App\Models\Hobi::where('user_id', Auth::id())->get();

        return view('admin.target', compact('targets', 'hobis'));
    }

    /**
     * Update expired targets to failed status
     */
    private function updateExpiredTargets()
    {
        // Get all expired targets for current user
        $expiredTargets = TargetHobi::where('user_id', Auth::id())
                                   ->where('target_deadline', '<', now()->startOfDay())
                                   ->with('progresTarget')
                                   ->get();

        foreach ($expiredTargets as $target) {
            // Update only on_progress status to failed for expired targets
            $target->progresTarget()
                   ->where('status', 'on_progress')
                   ->update(['status' => 'failed']);
        }

        return $expiredTargets->count();
    }

    /**
     * Get targets summary for dashboard
     */
    public function getSummary()
    {
        $this->updateExpiredTargets();
        
        $user_id = Auth::id();
        
        $summary = [
            'total' => TargetHobi::where('user_id', $user_id)->count(),
            'completed' => TargetHobi::where('user_id', $user_id)
                            ->whereHas('progresTarget', function($query) {
                                $query->where('status', 'completed');
                            })->count(),
            'on_progress' => TargetHobi::where('user_id', $user_id)
                            ->whereHas('progresTarget', function($query) {
                                $query->where('status', 'on_progress');
                            })->count(),
            'failed' => TargetHobi::where('user_id', $user_id)
                            ->whereHas('progresTarget', function($query) {
                                $query->where('status', 'failed');
                            })->count(),
            'expired' => TargetHobi::where('user_id', $user_id)
                            ->where('target_deadline', '<', now()->startOfDay())
                            ->count(),
        ];

        return $summary;
    }
}