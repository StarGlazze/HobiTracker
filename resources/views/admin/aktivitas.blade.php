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

        <!-- Main Content Card with Enhanced Search & Sort -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-1">
                            <i class="ti ti-list text-primary me-2"></i>Daftar Aktivitas
                        </h5>
                        <p class="text-muted small mb-0">Kelola semua aktivitas hobi Anda di sini</p>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('aktivitas.index') }}" id="searchForm">
                            <!-- Preserve sorting parameters -->
                            <input type="hidden" name="sort_by" value="{{ $sortBy ?? 'created_at' }}">
                            <input type="hidden" name="sort_direction" value="{{ $sortDirection ?? 'desc' }}">

                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white">
                                    <i class="ti ti-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0"
                                    placeholder="Cari aktivitas, target, hobi, atau mood..." name="search" id="searchInput"
                                    value="{{ $search ?? '' }}" autocomplete="off">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>

                            @if (!empty($search))
                                <div class="mt-1">
                                    <small class="text-muted">
                                        Hasil untuk: <strong>"{{ $search }}"</strong>
                                        <a href="{{ route('aktivitas.index', ['sort_by' => $sortBy, 'sort_direction' => $sortDirection]) }}"
                                            class="text-primary ms-2">
                                            <i class="ti ti-x" style="font-size: 0.7rem;"></i> Hapus
                                        </a>
                                    </small>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{-- Fixed table section for aktivitas.blade.php --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="border-0 py-3 px-4" style="width: 5%;">
                                <span class="text-muted px-2 py-1">#</span>
                            </th>
                            <th scope="col" class="border-0 py-3" style="width: 25%;">
                                <a href="{{ route('aktivitas.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'nama_aktivitas', 'sort_direction' => $sortBy == 'nama_aktivitas' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="text-decoration-none text-dark d-flex align-items-center sortable-header {{ $sortBy == 'nama_aktivitas' ? 'active' : '' }}">
                                    <i class="ti ti-activity me-2 text-primary"></i>
                                    <span class="fw-semibold">Aktivitas</span>
                                    @if ($sortBy == 'nama_aktivitas')
                                        <i
                                            class="ti ti-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ms-2 text-primary"></i>
                                    @else
                                        <i class="ti ti-selector ms-2 text-muted" style="opacity: 0.3;"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="border-0 py-3" style="width: 20%;">
                                <a href="{{ route('aktivitas.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'target', 'sort_direction' => $sortBy == 'target' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="text-decoration-none text-dark d-flex align-items-center sortable-header {{ $sortBy == 'target' ? 'active' : '' }}">
                                    <i class="ti ti-target me-2 text-danger"></i>
                                    <span class="fw-semibold">Target</span>
                                    @if ($sortBy == 'target')
                                        <i
                                            class="ti ti-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ms-2 text-primary"></i>
                                    @else
                                        <i class="ti ti-selector ms-2 text-muted" style="opacity: 0.3;"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="border-0 py-3" style="width: 15%;">
                                <a href="{{ route('aktivitas.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'energy_mood_level', 'sort_direction' => $sortBy == 'energy_mood_level' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="text-decoration-none text-dark d-flex align-items-center sortable-header {{ $sortBy == 'energy_mood_level' ? 'active' : '' }}">
                                    <i class="ti ti-mood-happy me-2 text-info"></i>
                                    <span class="fw-semibold">Mood</span>
                                    @if ($sortBy == 'energy_mood_level')
                                        <i
                                            class="ti ti-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ms-2 text-primary"></i>
                                    @else
                                        <i class="ti ti-selector ms-2 text-muted" style="opacity: 0.3;"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="border-0 py-3" style="width: 20%;">
                                <span class="fw-semibold text-muted">
                                    <i class="ti ti-notes me-2"></i>Catatan
                                </span>
                            </th>
                            <th scope="col" class="border-0 py-3 text-center" style="width: 8%;">
                                <span class="fw-semibold text-muted">File</span>
                            </th>
                            <th scope="col" class="border-0 py-3 text-center" style="width: 12%;">
                                <span class="fw-semibold text-muted">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aktivitas as $index => $aktivitasItem)
                            <tr class="border-bottom hover-row" data-aktivitas-row data-id="{{ $aktivitasItem->id }}">
                                <td class="px-4 py-3">
                                    <span
                                        class="text-muted">{{ ($aktivitas->currentPage() - 1) * $aktivitas->perPage() + $loop->iteration }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0 fw-semibold">{{ $aktivitasItem->nama_aktivitas }}</h6>
                                            <small class="text-muted">
                                                <i class="ti ti-calendar-event" style="font-size: 0.75rem;"></i>
                                                {{ $aktivitasItem->created_at->format('d M Y') }}
                                            </small>
                                        </div>
                                    </div>
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

                                                    if (in_array($extension, $imageExts)) {
                                                        $fileType = 'image';
                                                        $icon = 'ti-photo';
                                                        $title = 'Lihat gambar bukti';
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
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editAktivitasModal" title="Edit Aktivitas"
                                            data-aktivitas="{{ json_encode([
                                                'id' => $aktivitasItem->id,
                                                'nama' => $aktivitasItem->nama_aktivitas,
                                                'target_id' => $aktivitasItem->target_id,
                                                'energy_mood' => $aktivitasItem->energy_mood_level ?? '',
                                                'catatan' => $aktivitasItem->catatan ?? '',
                                                'file_bukti' => $aktivitasItem->file_bukti,
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
                                    <div class="py-4">
                                        <i class="ti ti-mood-sad text-muted mb-3" style="font-size: 4rem;"></i>
                                        @if (!empty($search))
                                            <h5 class="text-muted mb-2">Tidak ada hasil untuk "{{ $search }}"</h5>
                                            <p class="text-muted mb-3">Coba gunakan kata kunci yang berbeda</p>
                                            <a href="{{ route('aktivitas.index', ['sort_by' => $sortBy, 'sort_direction' => $sortDirection]) }}"
                                                class="btn btn-primary">
                                                <i class="ti ti-arrow-left me-2"></i>Tampilkan Semua Aktivitas
                                            </a>
                                        @else
                                            <h5 class="text-muted mb-2">Belum ada aktivitas</h5>
                                            <p class="text-muted mb-3">Mulai dengan menambahkan aktivitas hobi pertama Anda
                                            </p>
                                            <button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#tambahAktivitasModal">
                                                <i class="ti ti-plus me-2"></i>Tambah Aktivitas Sekarang
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($aktivitas->hasPages())
                <div class="card-footer bg-transparent border-top">
                    <div class="row align-items-center g-3">
                        <div class="col-md-6 text-center text-md-start">
                            <small class="text-muted">
                                <i class="ti ti-list-details me-1"></i>
                                Menampilkan <strong>{{ $aktivitas->firstItem() ?? 0 }}</strong> -
                                <strong>{{ $aktivitas->lastItem() ?? 0 }}</strong> dari
                                <strong>{{ $aktivitas->total() }}</strong> aktivitas
                            </small>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                            <nav aria-label="Page navigation">
                                @if ($aktivitas->lastPage() > 1)
                                    <ul class="pagination pagination-sm mb-0">
                                        {{-- Previous Page Link --}}
                                        @if ($aktivitas->onFirstPage())
                                            <li class="page-item disabled" aria-disabled="true" aria-label="Previous">
                                                <span class="page-link" aria-hidden="true">&laquo;</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $aktivitas->appends(request()->query())->previousPageUrl() }}"
                                                    rel="prev" aria-label="Previous">&laquo;</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($aktivitas->getUrlRange(max($aktivitas->currentPage() - 2, 1), min($aktivitas->currentPage() + 2, $aktivitas->lastPage())) as $page => $url)
                                            @if ($page == $aktivitas->currentPage())
                                                <li class="page-item active" aria-current="page"><span
                                                        class="page-link">{{ $page }}</span></li>
                                            @else
                                                <li class="page-item"><a class="page-link"
                                                        href="{{ $url }}">{{ $page }}</a></li>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($aktivitas->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $aktivitas->appends(request()->query())->nextPageUrl() }}"
                                                    rel="next" aria-label="Next">&raquo;</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled" aria-disabled="true" aria-label="Next">
                                                <span class="page-link" aria-hidden="true">&raquo;</span>
                                            </li>
                                        @endif
                                    </ul>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            @endif
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
                                    <i class="ti ti-check me-1"></i>Upload file langsung (maks 5MB)<br>
                                    <i class="ti ti-check me-1"></i>Atau berikan link Google Drive
                                </small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fileBukti" class="form-label fw-semibold">
                                <i class="ti ti-paperclip text-success me-2"></i>Opsi 1: Upload File Bukti
                            </label>
                            <input class="form-control @error('file_bukti') is-invalid @enderror" type="file"
                                id="fileBukti" name="file_bukti" accept="image/*">
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Format yang didukung: Gambar (jpg, png, gif, webp) - maksimal 5MB
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
                                                ({{ $target->hobi->nama_hobi }})
                                            </option>
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
                                accept="image/*">
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Upload gambar baru jika ingin mengganti bukti yang ada (maks 5MB)
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
    <script>
        // Auto-submit search form on enter
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchForm').submit();
            }
        });

        // Clear search on escape
        document.getElementById('searchInput').addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.location.href =
                    '{{ route('aktivitas.index', ['sort_by' => $sortBy, 'sort_direction' => $sortDirection]) }}';
            }
        });
    </script>
@endsection
