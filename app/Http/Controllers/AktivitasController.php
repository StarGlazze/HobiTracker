<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        // Mengambil semua aktivitas milik user yang sedang login
        $aktivitas = Aktivitas::whereHas('hobi', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('hobi')->get();

        // Menghitung statistik untuk dashboard cards
        $totalAktivitas = $aktivitas->count();
        $totalDurasi = $aktivitas->sum('durasi_menit');
        $hobiAktif = $aktivitas->pluck('hobi')->unique('id')->count();
        $rataRataDurasi = $totalAktivitas > 0 ? round($totalDurasi / $totalAktivitas) : 0;

        // Format durasi untuk tampilan
        $totalDurasiFormatted = $totalDurasi . 'm';
        $rataRataDurasiFormatted = $rataRataDurasi . 'm';

        return view('admin.aktivitas', [
            'aktivitas' => $aktivitas,
            'totalAktivitas' => $totalAktivitas,
            'totalDurasi' => $totalDurasiFormatted,
            'hobiAktif' => $hobiAktif,
            'rataRataDurasi' => $rataRataDurasiFormatted,
        ]);
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
    public function show(Aktivitas $aktivitas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aktivitas $aktivitas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aktivitas $aktivitas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aktivitas $aktivitas)
    {
        //
    }
}
