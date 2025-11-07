<?php

namespace App\Http\Controllers;

use App\Models\Hobi;
use App\Imports\HobiImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class HobiController extends Controller
{
    // Menampilkan daftar hobi milik user yang sedang login
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Query untuk semua hobi (untuk statistik)
        $allHobisQuery = Hobi::where('user_id', $userId)->with('kategoriHobi', 'targetHobi.aktivitas');
        $allHobis = $allHobisQuery->get();

        // Query untuk hobi yang akan ditampilkan (dengan sorting, search, pagination)
        $query = Hobi::where('user_id', $userId)->with('kategoriHobi', 'targetHobi.aktivitas');

        // Handle search
        $search = $request->input('search');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_hobi', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%')
                    ->orWhereHas('kategoriHobi', function ($q2) use ($search) {
                        $q2->where('nama_kategori', 'like', '%' . $search . '%');
                    });
            });
        }

        // Handle sorting - gunakan parameter yang konsisten
        $sortBy = $request->input('sort_by', 'nama_hobi');
        $sortDirection = $request->input('sort_direction', 'asc');

        // Validasi sort direction
        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // Validasi sort by dan terapkan sorting
        switch ($sortBy) {
            case 'kategori':
                // Sorting berdasarkan nama kategori
                $query->join('kategori_hobis', 'hobis.kategori_id', '=', 'kategori_hobis.id')
                    ->orderBy('kategori_hobis.nama_kategori', $sortDirection)
                    ->select('hobis.*');
                break;
            case 'deskripsi':
                $query->orderBy('deskripsi', $sortDirection);
                break;
            case 'created_at':
                $query->orderBy('created_at', $sortDirection);
                break;
            case 'nama_hobi':
            default:
                $query->orderBy('nama_hobi', $sortDirection);
                break;
        }

        // Pagination dengan append query parameters
        $hobis = $query->paginate(10)->withQueryString();

        $kategoriHobis = \App\Models\KategoriHobi::all();

        $totalHobi = $allHobis->count();

        $hobiTerpopuler = null;
        $maxAktivitas = 0;
        foreach ($allHobis as $hobi) {
            $aktivitasCount = $hobi->targetHobi->sum(function ($target) {
                return $target->aktivitas ? $target->aktivitas->count() : 0;
            });
            if ($aktivitasCount > $maxAktivitas) {
                $maxAktivitas = $aktivitasCount;
                $hobiTerpopuler = $hobi;
            }
        }

        $kategoriAktif = $allHobis->groupBy('kategori_id')->count();

        $hobiBulanIni = $allHobis->filter(function ($hobi) {
            return $hobi->created_at->isCurrentMonth();
        })->count();

        $topKategoriHobis = \App\Models\KategoriHobi::withCount(['hobis' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->having('hobis_count', '>', 0)->orderBy('hobis_count', 'desc')->take(3)->get();

        return view('admin.hobi', [
            'hobis' => $hobis,
            'kategoriHobis' => $kategoriHobis,
            'topKategoriHobis' => $topKategoriHobis,
            'totalHobi' => $totalHobi,
            'hobiTerpopuler' => $hobiTerpopuler,
            'maxAktivitas' => $maxAktivitas,
            'kategoriAktif' => $kategoriAktif,
            'hobiBulanIni' => $hobiBulanIni,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
            'search' => $search,
        ]);
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
        $hobi = Hobi::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        return view('admin.hobi_edit', ['hobi' => $hobi]);
    }

    // Mengupdate data hobi
    public function update(Request $request, $id)
    {
        $hobi = Hobi::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

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
        $hobi = Hobi::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $hobi->delete();

        return redirect()->route('hobi.index')->with('success', 'Hobi berhasil dihapus');
    }

    // Import hobi dari file Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048', // Max 2MB
        ]);

        try {
            DB::beginTransaction();

            $import = new HobiImport();
            Excel::import($import, $request->file('file'));

            $errors = $import->getErrors();
            $successCount = $import->getSuccessCount();
            $updateCount = $import->getUpdateCount();

            DB::commit();

            $message = '';
            if ($successCount > 0) {
                $message .= "{$successCount} hobi berhasil diimpor. ";
            }
            if ($updateCount > 0) {
                $message .= "{$updateCount} hobi berhasil diperbarui. ";
            }

            if (!empty($errors)) {
                $message .= "Namun ada beberapa error: " . implode('; ', $errors);
                return redirect()->route('hobi.index')->with('error', $message);
            }

            return redirect()->route('hobi.index')->with('success', trim($message));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('hobi.index')->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }
}
