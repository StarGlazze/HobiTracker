<?php

namespace App\Imports;

use App\Models\Hobi;
use App\Models\KategoriHobi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class HobiImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    private $userId;
    private $errors = [];
    private $successCount = 0;
    private $updateCount = 0;

    public function __construct()
    {
        $this->userId = Auth::id();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Validasi kategori_id ada
        $kategori = KategoriHobi::find($row['kategori_id']);
        if (!$kategori) {
            $this->errors[] = "Kategori ID {$row['kategori_id']} tidak ditemukan untuk hobi '{$row['nama_hobi']}'";
            return null;
        }

        // Cek apakah hobi dengan nama sama sudah ada untuk user ini
        $existingHobi = Hobi::where('user_id', $this->userId)
            ->where('nama_hobi', $row['nama_hobi'])
            ->first();

        if ($existingHobi) {
            // Update existing hobi
            $existingHobi->update([
                'kategori_id' => $row['kategori_id'],
                'deskripsi' => $row['deskripsi'] ?? null,
            ]);
            $this->updateCount++;
            return null; // Return null karena sudah diupdate, bukan create baru
        } else {
            // Create hobi baru
            $this->successCount++;
            return new Hobi([
                'user_id' => $this->userId,
                'kategori_id' => $row['kategori_id'],
                'nama_hobi' => $row['nama_hobi'],
                'deskripsi' => $row['deskripsi'] ?? null,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'nama_hobi' => 'required|string|max:255',
            'kategori_id' => 'required|integer|exists:kategori_hobis,id',
            'deskripsi' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_hobi.required' => 'Nama hobi wajib diisi.',
            'nama_hobi.max' => 'Nama hobi maksimal 255 karakter.',
            'kategori_id.required' => 'Kategori ID wajib diisi.',
            'kategori_id.integer' => 'Kategori ID harus berupa angka.',
            'kategori_id.exists' => 'Kategori ID tidak valid.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
        ];
    }

    public function onError(Throwable $e)
    {
        $this->errors[] = 'Error: ' . $e->getMessage();
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getUpdateCount()
    {
        return $this->updateCount;
    }
}
