<?php

namespace App\Http\Controllers;

use App\Models\TargetHobi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TargetHobiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Query untuk targets yang akan ditampilkan (dengan sorting, search, pagination)
        $query = TargetHobi::query()->where('target_hobis.user_id', $userId);

        // Handle search
        $search = $request->input('search');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('target_hobis.nama_target', 'like', '%' . $search . '%')
                    ->orWhereHas('hobi', function ($q2) use ($search) {
                        $q2->where('nama_hobi', 'like', '%' . $search . '%')
                            ->orWhereHas('kategoriHobi', function ($q3) use ($search) {
                                $q3->where('nama_kategori', 'like', '%' . $search . '%');
                            });
                    });
            });
        }

        // Handle sorting
        $sortBy = $request->input('sort_by', 'target_deadline');
        $sortDirection = $request->input('sort_direction', 'asc');

        // Validasi sort direction
        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // Validasi sort by dan terapkan sorting
        switch ($sortBy) {
            case 'nama_target':
                $query->orderBy('target_hobis.nama_target', $sortDirection);
                break;
            case 'hobi':
                $query->leftJoin('hobis', 'target_hobis.hobi_id', '=', 'hobis.id')
                    ->orderBy('hobis.nama_hobi', $sortDirection)
                    ->select('target_hobis.*');
                break;
            case 'kategori':
                $query->leftJoin('hobis as hobis_for_sort', 'target_hobis.hobi_id', '=', 'hobis_for_sort.id')
                    ->leftJoin('kategori_hobis', 'hobis_for_sort.kategori_id', '=', 'kategori_hobis.id')
                    ->orderBy('kategori_hobis.nama_kategori', $sortDirection)
                    ->select('target_hobis.*');
                break;
            case 'target_deadline':
                $query->orderBy('target_hobis.target_deadline', $sortDirection);
                break;
            case 'created_at':
                $query->orderBy('target_hobis.created_at', $sortDirection);
                break;
            default:
                $query->orderBy('target_hobis.target_deadline', 'asc');
                break;
        }

        // Pagination dengan append query parameters
        $targets = $query->paginate(5)->withQueryString();

        // Load relations setelah pagination
        $targets->load('hobi.kategoriHobi', 'aktivitas');

        $hobis = \App\Models\Hobi::with('kategoriHobi')
            ->where('user_id', $userId)
            ->get();

        return view('admin.target', [
            'targets' => $targets,
            'hobis' => $hobis,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
            'search' => $search,
        ]);
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
        $validator = Validator::make($request->all(), [
            'hobi_id' => 'required|exists:hobis,id',
            'nama_target' => 'required|string|max:255',
            'target_deadline' => 'required|date|after:yesterday',
            'jumlah_aktivitas_dibutuhkan' => 'required|integer|min:1|max:1000',
        ], [
            'target_deadline.after' => 'Batas waktu harus setelah hari kemarin.',
            'hobi_id.required' => 'Silakan pilih hobi.',
            'hobi_id.exists' => 'Hobi yang dipilih tidak valid.',
            'nama_target.required' => 'Nama target wajib diisi.',
            'jumlah_aktivitas_dibutuhkan.required' => 'Jumlah aktivitas dibutuhkan wajib diisi.',
            'jumlah_aktivitas_dibutuhkan.integer' => 'Jumlah aktivitas harus berupa angka.',
            'jumlah_aktivitas_dibutuhkan.min' => 'Jumlah aktivitas minimal 1.',
            'jumlah_aktivitas_dibutuhkan.max' => 'Jumlah aktivitas maksimal 1000.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('show_modal', 'tambah');
        }

        // Verify hobi belongs to user
        $hobi = \App\Models\Hobi::where('id', $request->hobi_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$hobi) {
            return back()->withErrors(['hobi_id' => 'Hobi yang dipilih tidak valid.'])
                ->withInput()
                ->with('show_modal', 'tambah');
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
        $targetHobi->load('hobi', 'aktivitas');
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

        $validator = Validator::make($request->all(), [
            'hobi_id' => 'required|exists:hobis,id',
            'nama_target' => 'required|string|max:255',
            'target_deadline' => 'required|date|after:yesterday',
            'jumlah_aktivitas_dibutuhkan' => 'required|integer|min:1|max:1000',
        ], [
            'target_deadline.after' => 'Batas waktu harus setelah hari kemarin.',
            'hobi_id.required' => 'Silakan pilih hobi.',
            'hobi_id.exists' => 'Hobi yang dipilih tidak valid.',
            'nama_target.required' => 'Nama target wajib diisi.',
            'jumlah_aktivitas_dibutuhkan.required' => 'Jumlah aktivitas dibutuhkan wajib diisi.',
            'jumlah_aktivitas_dibutuhkan.integer' => 'Jumlah aktivitas harus berupa angka.',
            'jumlah_aktivitas_dibutuhkan.min' => 'Jumlah aktivitas minimal 1.',
            'jumlah_aktivitas_dibutuhkan.max' => 'Jumlah aktivitas maksimal 1000.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('show_modal', 'edit')->with('target_id', $target->id);
        }

        // Verify hobi belongs to user (except for admin)
        if (Auth::user()->email !== 'admin@example.com') {
            $hobi = \App\Models\Hobi::where('id', $request->hobi_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$hobi) {
                return back()->withErrors(['hobi_id' => 'Hobi yang dipilih tidak valid.'])
                    ->withInput()
                    ->with('show_modal', 'edit')
                    ->with('target_id', $target->id);
            }
        }

        $target->update($request->only(['hobi_id', 'nama_target', 'target_deadline', 'jumlah_aktivitas_dibutuhkan']));

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

        $target->delete();

        return redirect()->route('admin.target.index')
            ->with('success', 'Target berhasil dihapus.');
    }

    /**
     * Get targets summary for dashboard
     */
    public function getSummary()
    {
        $user_id = Auth::id();

        $targets = TargetHobi::with('aktivitas')
            ->where('user_id', $user_id)
            ->get();

        $summary = [
            'total' => $targets->count(),
            'completed' => $targets->filter(function ($target) {
                $aktivitasCount = $target->aktivitas->count();
                return $aktivitasCount >= $target->jumlah_aktivitas_dibutuhkan;
            })->count(),
            'on_progress' => $targets->filter(function ($target) {
                $aktivitasCount = $target->aktivitas->count();
                $isExpired = $target->target_deadline < now()->startOfDay();
                return $aktivitasCount < $target->jumlah_aktivitas_dibutuhkan && !$isExpired;
            })->count(),
            'failed' => $targets->filter(function ($target) {
                $aktivitasCount = $target->aktivitas->count();
                $isExpired = $target->target_deadline < now()->startOfDay();
                return $aktivitasCount < $target->jumlah_aktivitas_dibutuhkan && $isExpired;
            })->count(),
            'expired' => $targets->filter(function ($target) {
                return $target->target_deadline < now()->startOfDay();
            })->count(),
        ];

        return $summary;
    }
}
