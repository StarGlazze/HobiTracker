@extends('admin.layouts.app')

@section('title', 'Aktivitas - HobiTracker')

@section('content')
    <div class="container-fluid" style="padding-top: 20px">
        <!-- Page Header with Better Layout -->
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">
                            <i class="ti ti-activity text-primary me-2"></i>Kelola Aktivitas
                        </h3>
                        <p class="text-muted mb-0">Tambah, edit, dan kelola semua aktivitas hobi Anda</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahAktivitasModal">
                            <i class="ti ti-plus me-2"></i>Tambah Aktivitas
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ti ti-alert-triangle me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Quick Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Total Aktivitas</h6>
                                <h4 class="mb-0">{{ $totalAktivitas ?? 0 }}</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-list-check fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success bg-gradient text-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Hobi Aktif</h6>
                                <h4 class="mb-0">{{ $hobiAktif ?? 0 }}</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-heart fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-info bg-gradient text-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Bulan Ini</h6>
                                <h4 class="mb-0">{{ $bulanIni ?? 0 }}</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-calendar fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning bg-gradient text-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Dengan Mood</h6>
                                <h4 class="mb-0">{{ $denganMood ?? 0 }}</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-mood-happy fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom-0 pt-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-1">Daftar Aktivitas</h5>
                        <p class="text-muted small mb-0">Kelola semua aktivitas hobi Anda di sini</p>
                    </div>
                    <div class="col-auto">
                        <div class="input-group input-group-sm" style="width: 280px;">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ti ti-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Cari aktivitas...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fixed table section for aktivitas.blade.php --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="border-0 py-3 px-4" style="width: 5%;">#</th>
                            <th scope="col" class="border-0 py-3" style="width: 30%;">Aktivitas</th>
                            <th scope="col" class="border-0 py-3" style="width: 20%;">Target</th>
                            <th scope="col" class="border-0 py-3" style="width: 15%;">Mood</th>
                            <th scope="col" class="border-0 py-3">Catatan</th>
                            <th scope="col" class="border-0 py-3 text-center" style="width: 8%;">File</th>
                            <th scope="col" class="border-0 py-3 text-center" style="width: 12%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aktivitas as $index => $aktivitasItem)
                            <tr class="border-bottom" data-aktivitas-row data-id="{{ $aktivitasItem->id }}">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="py-3">
                                    <h6 class="mb-1 fw-semibold">{{ $aktivitasItem->nama_aktivitas }}</h6>
                                </td>
                                <td class="py-3">
                                    <span class="fw-semibold">{{ $aktivitasItem->target->nama_target ?? 'N/A' }}</span>
                                    <br><small
                                        class="text-muted">{{ $aktivitasItem->target->hobi->nama_hobi ?? 'N/A' }}</small>
                                </td>
                                <td class="py-3">
                                    <span class="fw-semibold">{{ $aktivitasItem->energy_mood_level ?? '-' }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="text-truncate" style="max-width: 180px;" data-bs-toggle="tooltip"
                                        title="{{ $aktivitasItem->catatan ?? 'Tidak ada catatan' }}">
                                        {{ $aktivitasItem->catatan ?? 'Tidak ada catatan' }}
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    @php
                                        $rawFileBukti = $aktivitasItem->file_bukti;

                                        // Handle backward compatibility: could be array, JSON string, or plain string
                                        if (is_array($rawFileBukti)) {
                                            $fileData = $rawFileBukti;
                                        } elseif (is_string($rawFileBukti) && !empty($rawFileBukti)) {
                                            // Try to decode as JSON first
                                            $decoded = json_decode($rawFileBukti, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                $fileData = $decoded;
                                            } else {
                                                // Old format: plain string, check if it's GDrive URL
        $fileData = str_contains($rawFileBukti, 'drive.google.com')
            ? ['gdrive' => $rawFileBukti]
            : ['file' => $rawFileBukti];
    }
} else {
    $fileData = [];
}

$hasFile = isset($fileData['file']) && !empty($fileData['file']);
$hasGdrive = isset($fileData['gdrive']) && !empty($fileData['gdrive']);
                                    @endphp

                                    @if ($hasFile || $hasGdrive)
                                        <div class="d-flex gap-1 justify-content-center">
                                            @if ($hasFile)
                                                {{-- Local file - detect type --}}
                                                @php
                                                    $extension = strtolower(
                                                        pathinfo($fileData['file'], PATHINFO_EXTENSION),
                                                    );
                                                    $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                                    $videoExts = ['mp4', 'mov', 'avi', 'webm'];

                                                    if (in_array($extension, $imageExts)) {
                                                        $fileType = 'image';
                                                        $icon = 'ti-photo';
                                                        $title = 'Lihat gambar bukti';
                                                    } elseif (in_array($extension, $videoExts)) {
                                                        $fileType = 'video';
                                                        $icon = 'ti-video';
                                                        $title = 'Lihat video bukti';
                                                    } else {
                                                        $fileType = 'file';
                                                        $icon = 'ti-file-text';
                                                        $title = 'Lihat file bukti';
                                                    }
                                                @endphp

                                                <button class="btn btn-sm btn-outline-primary rounded-circle"
                                                    data-bs-toggle="tooltip" title="{{ $title }}"
                                                    data-file-url="{{ Storage::url($fileData['file']) }}"
                                                    data-file-type="{{ $fileType }}"
                                                    onclick="showFilePreview('{{ Storage::url($fileData['file']) }}', '{{ $fileType }}')">
                                                    <i class="ti {{ $icon }}"></i>
                                                </button>
                                            @endif

                                            @if ($hasGdrive)
                                                {{-- Google Drive link --}}
                                                <button class="btn btn-sm btn-outline-primary rounded-circle"
                                                    data-bs-toggle="tooltip" title="Lihat file di Google Drive"
                                                    data-file-url="{{ $fileData['gdrive'] }}" data-file-type="gdrive"
                                                    onclick="showFilePreview('{{ $fileData['gdrive'] }}', 'gdrive')">
                                                    <i class="ti ti-brand-google-drive"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editAktivitasModal" title="Edit Aktivitas"
                                            data-aktivitas="{{ json_encode([
                                                'id' => $aktivitasItem->id,
                                                'nama' => $aktivitasItem->nama_aktivitas,
                                                'target_id' => $aktivitasItem->target_id,
                                                'energy_mood' => $aktivitasItem->energy_mood_level ?? '',
                                                'catatan' => $aktivitasItem->catatan ?? '',
                                                'file_bukti' => $aktivitasItem->file_bukti
                                            ]) }}">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                        <form action="{{ route('aktivitas.destroy', $aktivitasItem->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus aktivitas ini?');"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Aktivitas">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="ti ti-activity text-muted" style="font-size: 4rem;"></i>
                                    </div>
                                    <h5 class="text-muted">Belum ada aktivitas</h5>
                                    <p class="text-muted mb-4">Mulai dengan menambahkan aktivitas hobi pertama Anda</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Enhanced Modal Tambah Aktivitas -->
    <div class="modal fade" id="tambahAktivitasModal" tabindex="-1" aria-labelledby="tambahAktivitasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="{{ route('aktivitas.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title" id="tambahAktivitasModalLabel">
                            <i class="ti ti-plus-circle me-2"></i>Tambah Aktivitas Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted mb-4">
                            <i class="ti ti-info-circle me-2"></i>
                            Isi detail aktivitas baru Anda dengan lengkap untuk tracking yang lebih baik
                        </p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pilihTarget" class="form-label fw-semibold">
                                        <i class="ti ti-target text-danger me-2"></i>Pilih Target
                                    </label>
                                    <select class="form-select @error('target_id') is-invalid @enderror" id="pilihTarget"
                                        name="target_id" required>
                                        <option value="" selected disabled>Pilih Target Terkait...</option>
                                        @foreach ($targets ?? [] as $target)
                                            <option value="{{ $target->id }}"
                                                {{ old('target_id') == $target->id ? 'selected' : '' }}>
                                                {{ $target->nama_target }} ({{ $target->hobi->nama_hobi }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('target_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="energyMoodLevel" class="form-label fw-semibold">
                                        <i class="ti ti-mood-happy text-info me-2"></i>Mood(opsional)
                                    </label>
                                    <input type="text"
                                        class="form-control @error('energy_mood_level') is-invalid @enderror"
                                        id="energyMoodLevel" name="energy_mood_level"
                                        value="{{ old('energy_mood_level') }}" placeholder="Contoh: 5, 😊, Enerjik"
                                        maxlength="50">
                                    @error('energy_mood_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Masukkan skala 1-5, emoji mood, atau deskripsi
                                        singkat</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="namaAktivitas" class="form-label fw-semibold">
                                <i class="ti ti-activity text-primary me-2"></i>Nama Aktivitas
                            </label>
                            <input type="text" class="form-control @error('nama_aktivitas') is-invalid @enderror"
                                id="namaAktivitas" name="nama_aktivitas" value="{{ old('nama_aktivitas') }}"
                                placeholder="Contoh: Baca novel Dune chapter 1-3, Lari keliling taman 5km" required>
                            @error('nama_aktivitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="catatanAktivitas" class="form-label fw-semibold">
                                <i class="ti ti-notes text-warning me-2"></i>Catatan
                            </label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatanAktivitas" name="catatan"
                                rows="3" placeholder="Deskripsi tambahan, target yang ingin dicapai, atau catatan lainnya...">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- File Bukti Section dengan Validasi --}}
                        <div class="mb-4">
                            <div class="alert alert-info border-0">
                                <h6 class="alert-heading mb-2">
                                    <i class="ti ti-info-circle me-2"></i>Bukti Aktivitas (WAJIB)
                                </h6>
                                <p class="mb-2">Pilih salah satu atau kedua opsi di bawah ini untuk memberikan bukti
                                    aktivitas:</p>
                                <small class="text-muted">
                                    <i class="ti ti-check me-1"></i>Upload file langsung (maks 50MB)<br>
                                    <i class="ti ti-check me-1"></i>Atau berikan link Google Drive
                                </small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fileBukti" class="form-label fw-semibold">
                                <i class="ti ti-paperclip text-success me-2"></i>Opsi 1: Upload File Bukti
                            </label>
                            <input class="form-control @error('file_bukti') is-invalid @enderror" type="file"
                                id="fileBukti" name="file_bukti" accept="image/*,video/*">
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Format yang didukung: Gambar (jpg, png, gif) dan Video (mp4, mov, avi) - maksimal 50MB
                            </div>
                            @error('file_bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="gdriveLink" class="form-label fw-semibold">
                                <i class="ti ti-link text-info me-2"></i>Opsi 2: Link Google Drive
                            </label>
                            <input type="url" class="form-control @error('gdrive_link') is-invalid @enderror"
                                id="gdriveLink" name="gdrive_link" value="{{ old('gdrive_link') }}"
                                placeholder="https://drive.google.com/file/...">
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Gunakan jika file terlalu besar atau ingin menyimpan di Google Drive
                            </div>
                            @error('gdrive_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ti ti-x me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Simpan Aktivitas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal untuk edit --}}
    <div class="modal fade" id="editAktivitasModal" tabindex="-1" aria-labelledby="editAktivitasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="" id="editAktivitasForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-white border-0">
                        <h5 class="modal-title" id="editAktivitasModalLabel">
                            <i class="ti ti-edit me-2"></i>Edit Aktivitas
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted mb-4">
                            <i class="ti ti-info-circle me-2"></i>
                            Edit detail aktivitas Anda sesuai dengan perubahan yang diinginkan
                        </p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editPilihTarget" class="form-label fw-semibold">
                                        <i class="ti ti-target text-danger me-2"></i>Pilih Target
                                    </label>
                                    <select class="form-select" id="editPilihTarget" name="target_id" required>
                                        <option value="" selected disabled>Pilih Target Terkait...</option>
                                        @foreach ($targets ?? [] as $target)
                                            <option value="{{ $target->id }}">{{ $target->nama_target }}
                                                ({{ $target->hobi->nama_hobi }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editEnergyMoodLevel" class="form-label fw-semibold">
                                        <i class="ti ti-mood-happy text-info me-2"></i>Mood (opsional)
                                    </label>
                                    <input type="text" class="form-control" id="editEnergyMoodLevel"
                                        name="energy_mood_level" placeholder="Contoh: 5, 😊, Enerjik" maxlength="50">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editNamaAktivitas" class="form-label fw-semibold">
                                <i class="ti ti-activity text-primary me-2"></i>Nama Aktivitas
                            </label>
                            <input type="text" class="form-control" id="editNamaAktivitas" name="nama_aktivitas"
                                placeholder="Contoh: Baca novel Dune chapter 1-3, Lari keliling taman 5km" required>
                        </div>

                        <div class="mb-3">
                            <label for="editCatatanAktivitas" class="form-label fw-semibold">
                                <i class="ti ti-notes text-warning me-2"></i>Catatan
                            </label>
                            <textarea class="form-control" id="editCatatanAktivitas" name="catatan" rows="3"
                                placeholder="Deskripsi tambahan, target yang ingin dicapai, atau catatan lainnya..."></textarea>
                        </div>

                        {{-- File Bukti Section untuk Edit dengan Validasi --}}
                        <div class="mb-4">
                            <div class="alert alert-info border-0">
                                <h6 class="alert-heading mb-2">
                                    <i class="ti ti-info-circle me-2"></i>Update Bukti Aktivitas (Opsional)
                                </h6>
                                <p class="mb-2">Aktivitas ini sudah memiliki bukti yang tersimpan. Anda bisa:</p>
                                <small class="text-muted">
                                    <i class="ti ti-check me-1"></i>Tetap menggunakan bukti yang ada (kosongkan kedua
                                    field)<br>
                                    <i class="ti ti-check me-1"></i>Mengganti dengan file baru<br>
                                    <i class="ti ti-check me-1"></i>Mengganti dengan link Google Drive baru
                                </small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editFileBukti" class="form-label fw-semibold">
                                <i class="ti ti-paperclip text-success me-2"></i>File Bukti Baru (Opsional)
                            </label>
                            <input class="form-control" type="file" id="editFileBukti" name="file_bukti"
                                accept="image/*,video/*">
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Upload file baru jika ingin mengganti bukti yang ada
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editGdriveLink" class="form-label fw-semibold">
                                <i class="ti ti-link text-info me-2"></i>Link Google Drive Baru (Opsional)
                            </label>
                            <input type="url" class="form-control" id="editGdriveLink" name="gdrive_link"
                                placeholder="https://drive.google.com/file/...">
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Masukkan link baru jika ingin mengubah ke Google Drive
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ti ti-x me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="ti ti-device-floppy me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal File Preview --}}
    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title" id="filePreviewModalLabel">
                        <i class="ti ti-file-text me-2"></i>Preview File
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- Content will be loaded dynamically by JavaScript --}}
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('./admin/js/aktivitas.js') }}"></script>
@endsection
