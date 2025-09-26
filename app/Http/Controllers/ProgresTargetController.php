<?php

namespace App\Http\Controllers;

use App\Models\ProgresTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProgresTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $progres = ProgresTarget::with('targetHobi')->where('user_id', Auth::id())->get();
        return view('admin.target', compact('progres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $targets = \App\Models\TargetHobi::where('user_id', Auth::id())->get();
        return view('admin.target', compact('targets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'target_id' => 'required|exists:target_hobis,id',
            'file_bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB
            'link_gdrive' => 'nullable|url',
            'catatan' => 'nullable|string',
            'status' => 'nullable|in:on_progress,completed,failed'
        ]);

        // Pastikan salah satu bukti ada
        if (!$request->hasFile('file_bukti') && !$request->filled('link_gdrive')) {
            return back()->withErrors(['bukti' => 'Harus upload file bukti atau isi link Google Drive.'])
                        ->withInput();
        }

        $filePath = null;
        if ($request->hasFile('file_bukti')) {
            $filePath = $request->file('file_bukti')->store('bukti_progres', 'public');
        }

        // Auto-determine status based on evidence
        $status = 'on_progress'; // default
        if ($filePath || $request->filled('link_gdrive')) {
            $status = 'completed'; // auto-complete when evidence is provided
        }

        // Override if user specifically set status
        if ($request->filled('status')) {
            $status = $request->status;
        }

        // Check if target is expired
        $target = \App\Models\TargetHobi::find($request->target_id);
        if ($target && $target->target_deadline < now()->startOfDay()) {
            $status = 'failed'; // Auto-fail if past deadline
        }

        ProgresTarget::create([
            'user_id' => Auth::id(),
            'target_id' => $request->target_id,
            'status' => $status,
            'file_bukti' => $filePath,
            'link_gdrive' => $request->link_gdrive,
            'catatan' => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Progres berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgresTarget $progresTarget)
    {
        if ($progresTarget->user_id != Auth::id()) {
            abort(403);
        }
        $progresTarget->load('targetHobi');
        return view('admin.target', compact('progresTarget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgresTarget $progresTarget)
    {
        if (Auth::user()->email !== 'admin@example.com' && $progresTarget->user_id != Auth::id()) {
            abort(403);
        }
        return view('admin.target', compact('progresTarget'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgresTarget $progresTarget)
    {
        if (Auth::user()->email !== 'admin@example.com' && $progresTarget->user_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:on_progress,completed,failed',
            'file_bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'link_gdrive' => 'nullable|url',
            'catatan' => 'nullable|string',
        ]);

        // Check if we need evidence (at least one of file, link, or existing evidence)
        $hasNewFile = $request->hasFile('file_bukti');
        $hasNewLink = $request->filled('link_gdrive');
        $hasExistingFile = !empty($progresTarget->file_bukti);
        $hasExistingLink = !empty($progresTarget->link_gdrive);

        // If no new evidence and removing existing evidence, ensure we still have something
        if (!$hasNewFile && !$hasNewLink && !$hasExistingFile && !$hasExistingLink) {
            return back()->withErrors(['bukti' => 'Harus ada file bukti atau link Google Drive.'])
                        ->withInput();
        }

        $filePath = $progresTarget->file_bukti;
        
        // Handle file upload
        if ($hasNewFile) {
            // Delete old file if exists
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file_bukti')->store('bukti_progres', 'public');
        }

        // Auto-determine status based on evidence if status is on_progress
        $status = $request->status;
        if ($status === 'on_progress') {
            $willHaveEvidence = $hasNewFile || $hasNewLink || 
                              ($hasExistingFile && !$hasNewFile) || 
                              ($hasExistingLink && !$hasNewLink);
            
            if ($willHaveEvidence) {
                $status = 'completed';
            }
        }

        // Check if target is expired and force fail
        $target = $progresTarget->targetHobi;
        if ($target && $target->target_deadline < now()->startOfDay() && $status !== 'failed') {
            $status = 'failed';
        }

        $progresTarget->update([
            'status' => $status,
            'file_bukti' => $filePath,
            'link_gdrive' => $request->link_gdrive,
            'catatan' => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Progres berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgresTarget $progresTarget)
    {
        if (Auth::user()->email !== 'admin@example.com' && $progresTarget->user_id != Auth::id()) {
            abort(403);
        }

        if ($progresTarget->file_bukti) {
            Storage::disk('public')->delete($progresTarget->file_bukti);
        }

        $progresTarget->delete();

        return redirect()->back()->with('success', 'Progres berhasil dihapus.');
    }

    /**
     * Update expired targets to failed status
     */
    public static function updateExpiredTargets()
    {
        $expiredProgres = ProgresTarget::whereHas('targetHobi', function($query) {
                $query->where('target_deadline', '<', now()->startOfDay());
            })
            ->where('status', 'on_progress')
            ->get();

        foreach ($expiredProgres as $progres) {
            $progres->update(['status' => 'failed']);
        }

        return $expiredProgres->count();
    }
}