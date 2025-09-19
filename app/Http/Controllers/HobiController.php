<?php

namespace App\Http\Controllers;

use App\Models\Hobi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HobiController extends Controller
{
    // Menampilkan daftar hobi milik user yang sedang login
    public function index()
    {
        $userId = Auth::id();
        $hobis = Hobi::where('user_id', $userId)->with('kategoriHobi')->get();
        $kategoriHobis = \App\Models\KategoriHobi::all();
        return view('admin.hobi', ['hobis' => $hobis, 'kategoriHobis' => $kategoriHobis]);
    }

    // Menampilkan form untuk membuat hobi baru
    public function create()
    {
        return view('admin.hobi_create');
    }

    // Menyimpan data hobi baru
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_hobis,id',
            'nama_hobi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $hobi = new Hobi();
        $hobi->user_id = Auth::id();
        $hobi->kategori_id = $request->kategori_id;
        $hobi->nama_hobi = $request->nama_hobi;
        $hobi->deskripsi = $request->deskripsi;
        $hobi->save();

        return redirect()->route('hobi.index')->with('success', 'Hobi berhasil dibuat');
    }

    // Menampilkan form edit hobi
    public function edit($id)
    {
        $hobi = Hobi::find($id);

        if (!$hobi || $hobi->user_id != Auth::id()) {
            return redirect()->route('hobi.index')->with('error', 'Hobi tidak ditemukan atau tidak punya akses');
        }

        return view('admin.hobi_edit', ['hobi' => $hobi]);
    }

    // Mengupdate data hobi
    public function update(Request $request, $id)
    {
        $hobi = Hobi::find($id);

        if (!$hobi || $hobi->user_id != Auth::id()) {
            return redirect()->route('hobi.index')->with('error', 'Hobi tidak ditemukan atau tidak punya akses');
        }

        $request->validate([
            'kategori_id' => 'required|exists:kategori_hobis,id',
            'nama_hobi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $hobi->kategori_id = $request->kategori_id;
        $hobi->nama_hobi = $request->nama_hobi;
        $hobi->deskripsi = $request->deskripsi;
        $hobi->save();

        return redirect()->route('hobi.index')->with('success', 'Hobi berhasil diupdate');
    }

    // Menghapus data hobi
    public function destroy($id)
    {
        $hobi = Hobi::find($id);

        if (!$hobi || $hobi->user_id != Auth::id()) {
            return redirect()->route('hobi.index')->with('error', 'Hobi tidak ditemukan atau tidak punya akses');
        }

        $hobi->delete();

        return redirect()->route('hobi.index')->with('success', 'Hobi berhasil dihapus');
    }
}
