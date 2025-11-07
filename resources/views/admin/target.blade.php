@extends('admin.layouts.app')

@section('title', 'Target Hobi')

@section('content')
    <div class="container-fluid" style="padding-top: 20px">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">
                            <i class="ti ti-target text-primary me-2"></i>Target Hobi
                        </h3>
                        <p class="text-muted mb-0">Atur dan pantau target-target hobi Anda untuk mencapai tujuan yang
                            diinginkan.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahTargetModal">
                            <i class="ti ti-plus me-2"></i>Tambah Target
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Targets Table -->
        <!-- Targets Table with Enhanced Search, Sort & Pagination -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-1">
                            <i class="ti ti-list text-primary me-2"></i>Daftar Target Hobi Anda
                        </h5>
                        <p class="text-muted small mb-0">Kelola dan pantau semua target hobi Anda</p>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.target.index') }}" id="searchForm">
                            <!-- Preserve sorting parameters -->
                            <input type="hidden" name="sort_by" value="{{ $sortBy ?? 'target_deadline' }}">
                            <input type="hidden" name="sort_direction" value="{{ $sortDirection ?? 'asc' }}">

                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white">
                                    <i class="ti ti-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0"
                                    placeholder="Cari target, hobi, atau kategori..." name="search" id="searchInput"
                                    value="{{ $search ?? '' }}" autocomplete="off">
                                @if (!empty($search))
                                @endif
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>

                            @if (!empty($search))
                                <div class="mt-1">
                                    <small class="text-muted">
                                        Hasil untuk: <strong>"{{ $search }}"</strong>
                                        <a href="{{ route('admin.target.index', ['sort_by' => $sortBy, 'sort_direction' => $sortDirection]) }}"
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

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col" class="border-0 py-3 px-4" style="width: 3%;">
                                    <span class="text-muted">#</span>
                                </th>
                                <th scope="col" class="border-0 py-3" style="width: 20%;">
                                    <a href="{{ route('admin.target.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'nama_target', 'sort_direction' => $sortBy == 'nama_target' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                        class="text-decoration-none text-dark d-flex align-items-center sortable-header {{ $sortBy == 'nama_target' ? 'active' : '' }}">
                                        <i class="ti ti-target me-2 text-primary"></i>
                                        <span class="fw-semibold">Nama Target</span>
                                        @if ($sortBy == 'nama_target')
                                            <i
                                                class="ti ti-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ms-2 text-primary"></i>
                                        @else
                                            <i class="ti ti-selector ms-2 text-muted" style="opacity: 0.3;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="border-0 py-3" style="width: 15%;">
                                    <a href="{{ route('admin.target.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'hobi', 'sort_direction' => $sortBy == 'hobi' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                        class="text-decoration-none text-dark d-flex align-items-center sortable-header {{ $sortBy == 'hobi' ? 'active' : '' }}">
                                        <i class="ti ti-heart me-2 text-danger"></i>
                                        <span class="fw-semibold">Hobi</span>
                                        @if ($sortBy == 'hobi')
                                            <i
                                                class="ti ti-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ms-2 text-primary"></i>
                                        @else
                                            <i class="ti ti-selector ms-2 text-muted" style="opacity: 0.3;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="border-0 py-3" style="width: 12%;">
                                    <a href="{{ route('admin.target.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'kategori', 'sort_direction' => $sortBy == 'kategori' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                        class="text-decoration-none text-dark d-flex align-items-center sortable-header {{ $sortBy == 'kategori' ? 'active' : '' }}">
                                        <i class="ti ti-category me-2 text-success"></i>
                                        <span class="fw-semibold">Kategori</span>
                                        @if ($sortBy == 'kategori')
                                            <i
                                                class="ti ti-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ms-2 text-primary"></i>
                                        @else
                                            <i class="ti ti-selector ms-2 text-muted" style="opacity: 0.3;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="border-0 py-3" style="width: 12%;">
                                    <a href="{{ route('admin.target.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'target_deadline', 'sort_direction' => $sortBy == 'target_deadline' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                        class="text-decoration-none text-dark d-flex align-items-center sortable-header {{ $sortBy == 'target_deadline' ? 'active' : '' }}">
                                        <i class="ti ti-calendar me-2 text-warning"></i>
                                        <span class="fw-semibold">Deadline</span>
                                        @if ($sortBy == 'target_deadline')
                                            <i
                                                class="ti ti-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ms-2 text-primary"></i>
                                        @else
                                            <i class="ti ti-selector ms-2 text-muted" style="opacity: 0.3;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="border-0 py-3" style="width: 18%;">
                                    <span class="text-muted fw-semibold">
                                        <i class="ti ti-chart-bar me-2"></i>Progress
                                    </span>
                                </th>
                                <th scope="col" class="border-0 py-3" style="width: 12%;">
                                    <span class="text-muted fw-semibold">
                                        <i class="ti ti-flag me-2"></i>Status
                                    </span>
                                </th>
                                <th scope="col" class="border-0 py-3 text-center" style="width: 8%;">
                                    <span class="text-muted fw-semibold">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($targets as $target)
                                <tr class="border-bottom hover-row">
                                    @php
                                        $aktivitasCount = $target->aktivitas->count();
                                        $progress =
                                            $target->jumlah_aktivitas_dibutuhkan > 0
                                                ? ($aktivitasCount / $target->jumlah_aktivitas_dibutuhkan) * 100
                                                : 0;
                                        $isCompleted = $progress >= 100;
                                        $isExpired = $target->target_deadline < now()->startOfDay() && !$isCompleted;
                                    @endphp
                                    <td class="px-4 py-3">
                                        <span
                                            class="text-muted">{{ ($targets->currentPage() - 1) * $targets->perPage() + $loop->iteration }}</span>
                                    </td>
                                    <td class="py-3">
                                        <h6 class="mb-0 fw-semibold">{{ $target->nama_target }}</h6>
                                    </td>
                                    <td class="py-3">{{ $target->hobi->nama_hobi ?? 'N/A' }}</td>
                                    <td class="py-3">
                                        <span
                                            class="badge bg-{{ $target->hobi->kategoriHobi->background_color ?? 'primary' }}-subtle text-dark px-3 py-2">
                                            <i
                                                class="ti {{ $target->hobi->kategoriHobi->icon ?? 'ti-category' }} me-1"></i>
                                            {{ $target->hobi->kategoriHobi->nama_kategori ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <small class="text-muted">
                                            <i class="ti ti-calendar-event" style="font-size: 0.75rem;"></i>
                                            {{ \Carbon\Carbon::parse($target->target_deadline)->format('d M Y') }}
                                            <br>
                                            @php
                                                $deadline = \Carbon\Carbon::parse(
                                                    $target->target_deadline,
                                                )->startOfDay();
                                                $now = now()->startOfDay();
                                                $diffDays = $now->diffInDays($deadline, false);

                                                if ($diffDays == 0) {
                                                    $relativeText = 'Hari ini';
                                                } elseif ($diffDays > 0) {
                                                    // Deadline di masa depan
                                                    $relativeText =
                                                        $diffDays == 1 ? '1 hari lagi' : $diffDays . ' hari lagi';
                                                } else {
                                                    // Deadline sudah lewat
                                                    $daysAgo = abs($diffDays);
                                                    $relativeText =
                                                        $daysAgo == 1
                                                            ? '1 hari yang lalu'
                                                            : $daysAgo . ' hari yang lalu';
                                                }
                                            @endphp

                                            @if ($relativeText)
                                                <small class="text-muted">{{ $relativeText }}</small>
                                            @endif
                                        </small>
                                    </td>

                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="progress position-relative flex-grow-1"
                                                style="height: 24px; border-radius: 12px; background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%); box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);">
                                                <div class="progress-bar position-relative overflow-hidden {{ $progress >= 100 ? 'bg-success' : ($progress >= 50 ? 'bg-warning' : 'bg-primary') }}"
                                                    role="progressbar"
                                                    style="width: {{ min($progress, 100) }}%;
                                                    border-radius: 12px;
                                                    background: {{ $progress >= 100 ? 'linear-gradient(90deg, #28a745 0%, #20c997 100%)' : ($progress >= 50 ? 'linear-gradient(90deg, #ffc107 0%, #fd7e14 100%)' : 'linear-gradient(90deg, #007bff 0%, #6610f2 100%)') }};
                                                    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                                                    transition: width 0.6s ease-in-out;"
                                                    aria-valuenow="{{ min($progress, 100) }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="ms-2 fw-bold text-dark"
                                                style="font-size: 14px; min-width: 45px;">
                                                {{ number_format($progress, 1) }}%
                                            </span>
                                        </div>
                                        <small class="text-muted d-flex align-items-center mt-1">
                                            <i class="ti ti-activity me-1"></i>
                                            {{ $aktivitasCount }} / {{ $target->jumlah_aktivitas_dibutuhkan }} aktivitas
                                        </small>
                                    </td>
                                    <td class="py-3 text-center">
                                        @if ($isCompleted)
                                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                                <i class="ti ti-check-circle me-1"></i>Completed
                                            </span>
                                        @elseif($isExpired)
                                            <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                                <i class="ti ti-x-circle me-1"></i>Failed
                                            </span>
                                        @elseif($aktivitasCount > 0)
                                            <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                                <i class="ti ti-clock me-1"></i>On Progress
                                            </span>
                                        @else
                                            <span class="badge bg-info-subtle text-info px-3 py-2">
                                                <i class="ti ti-plus me-1"></i>No Progress
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            @if ($isExpired)
                                                <button class="btn btn-secondary btn-sm" disabled
                                                    title="Target Expired - Cannot Edit">
                                                    <i class="ti ti-pencil-off"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editTargetModal{{ $target->id }}"
                                                    title="Edit Target">
                                                    <i class="ti ti-pencil"></i>
                                                </button>
                                            @endif
                                            <form action="{{ route('admin.target.destroy', ['target' => $target->id]) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" title="Hapus Target"
                                                    onclick="confirmDelete(this)">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $target->id }}" title="Lihat Detail">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="ti ti-mood-sad text-muted mb-3" style="font-size: 4rem;"></i>
                                            @if (!empty($search))
                                                <h5 class="text-muted mb-2">Tidak ada hasil untuk "{{ $search }}"
                                                </h5>
                                                <p class="text-muted mb-3">Coba gunakan kata kunci yang berbeda</p>
                                                <a href="{{ route('admin.target.index', ['sort_by' => $sortBy, 'sort_direction' => $sortDirection]) }}"
                                                    class="btn btn-primary">
                                                    <i class="ti ti-arrow-left me-2"></i>Tampilkan Semua Target
                                                </a>
                                            @else
                                                <h5 class="text-muted mb-2">Belum ada Target Hobi</h5>
                                                <p class="text-muted mb-3">Mulai dengan menambahkan target hobi pertama
                                                    Anda</p>
                                                <button class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#tambahTargetModal">
                                                    <i class="ti ti-plus me-2"></i>Tambah Target Sekarang
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($targets->hasPages())
                    <div class="card-footer bg-transparent border-top">
                        <div class="row align-items-center g-3">
                            <div class="col-md-6 text-center text-md-start">
                                <small class="text-muted">
                                    <i class="ti ti-list-details me-1"></i>
                                    Menampilkan <strong>{{ $targets->firstItem() ?? 0 }}</strong> -
                                    <strong>{{ $targets->lastItem() ?? 0 }}</strong> dari
                                    <strong>{{ $targets->total() }}</strong> target
                                </small>
                            </div>
                            <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                                <nav aria-label="Page navigation">
                                    @if ($targets->lastPage() > 1)
                                        <ul class="pagination pagination-sm mb-0">
                                            {{-- Previous Page Link --}}
                                            @if ($targets->onFirstPage())
                                                <li class="page-item disabled" aria-disabled="true"
                                                    aria-label="Previous">
                                                    <span class="page-link" aria-hidden="true">&laquo;</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $targets->appends(request()->query())->previousPageUrl() }}"
                                                        rel="prev" aria-label="Previous">&laquo;</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($targets->getUrlRange(max($targets->currentPage() - 2, 1), min($targets->currentPage() + 2, $targets->lastPage())) as $page => $url)
                                                @if ($page == $targets->currentPage())
                                                    <li class="page-item active" aria-current="page"><span
                                                            class="page-link">{{ $page }}</span></li>
                                                @else
                                                    <li class="page-item"><a class="page-link"
                                                            href="{{ $url }}">{{ $page }}</a></li>
                                                @endif
                                            @endforeach

                                            {{-- Next Page Link --}}
                                            @if ($targets->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $targets->appends(request()->query())->nextPageUrl() }}"
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

        <link rel="stylesheet" href="{{ asset('admin/css/target.css') }}">

        <!-- Add Target Modal -->
        <div class="modal fade" id="tambahTargetModal" tabindex="-1" aria-labelledby="tambahTargetModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('admin.target.store') }}" method="POST">
                        @csrf
                        <div class="modal-header bg-primary text-white border-0">
                            <h5 class="modal-title" id="tambahTargetModalLabel">
                                <i class="ti ti-plus-circle me-2"></i>Tambah Target Baru
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="text-muted mb-4">
                                <i class="ti ti-info-circle me-2"></i>
                                Tambahkan target baru untuk hobi Anda agar bisa mulai melacak kemajuan.
                            </p>

                            <div class="mb-3">
                                <label for="hobi_id" class="form-label fw-semibold">
                                    <i class="ti ti-heart text-danger me-2"></i>Hobi
                                </label>
                                <select class="form-select" id="hobi_id" name="hobi_id" required>
                                    <option value="">Pilih Hobi</option>
                                    @foreach ($hobis as $hobi)
                                        <option value="{{ $hobi->id }}"
                                            {{ old('hobi_id') == $hobi->id ? 'selected' : '' }}>{{ $hobi->nama_hobi }}
                                            ({{ $hobi->kategoriHobi->nama_kategori ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="nama_target" class="form-label fw-semibold">
                                    <i class="ti ti-target text-primary me-2"></i>Nama Target
                                </label>
                                <input type="text" class="form-control" id="nama_target" name="nama_target"
                                    placeholder="Masukkan nama target" value="{{ old('nama_target') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="target_deadline" class="form-label fw-semibold">
                                    <i class="ti ti-calendar text-success me-2"></i>Batas Waktu
                                </label>
                                <input type="date" class="form-control" id="target_deadline" name="target_deadline"
                                    min="{{ date('Y-m-d', strtotime('today')) }}" value="{{ old('target_deadline') }}"
                                    required>
                                @error('target_deadline')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jumlah_aktivitas_dibutuhkan" class="form-label fw-semibold">
                                    <i class="ti ti-number text-info me-2"></i>Jumlah Aktivitas Dibutuhkan
                                </label>
                                <input type="number" class="form-control" id="jumlah_aktivitas_dibutuhkan"
                                    name="jumlah_aktivitas_dibutuhkan" min="1"
                                    value="{{ old('jumlah_aktivitas_dibutuhkan', 1) }}" required>
                                @error('jumlah_aktivitas_dibutuhkan')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="ti ti-x me-2"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-2"></i>Simpan Target
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Target Modals -->
        @foreach ($targets as $target)
            <div class="modal fade" id="editTargetModal{{ $target->id }}" tabindex="-1"
                aria-labelledby="editTargetModalLabel{{ $target->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow">
                        <form action="{{ route('admin.target.update', ['target' => $target->id]) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-header bg-warning text-white border-0">
                                <h5 class="modal-title" id="editTargetModalLabel{{ $target->id }}">
                                    <i class="ti ti-edit me-2"></i>Edit Target: {{ $target->nama_target }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <p class="text-muted mb-4">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Edit detail target Anda sesuai dengan perubahan yang diinginkan
                                </p>

                                <div class="mb-3">
                                    <label for="hobi_id{{ $target->id }}" class="form-label fw-semibold">
                                        <i class="ti ti-heart text-danger me-2"></i>Hobi
                                    </label>
                                    <select class="form-select" id="hobi_id{{ $target->id }}" name="hobi_id" required>
                                        <option value="">Pilih Hobi</option>
                                        @foreach ($hobis as $hobi)
                                            <option value="{{ $hobi->id }}"
                                                {{ old('hobi_id', $target->hobi_id) == $hobi->id ? 'selected' : '' }}>
                                                {{ $hobi->nama_hobi }} ({{ $hobi->kategoriHobi->nama_kategori ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="nama_target{{ $target->id }}" class="form-label fw-semibold">
                                        <i class="ti ti-target text-primary me-2"></i>Nama Target
                                    </label>
                                    <input type="text" class="form-control" id="nama_target{{ $target->id }}"
                                        name="nama_target" value="{{ old('nama_target', $target->nama_target) }}"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="target_deadline{{ $target->id }}" class="form-label fw-semibold">
                                        <i class="ti ti-calendar text-success me-2"></i>Batas Waktu
                                    </label>
                                    <input type="date" class="form-control" id="target_deadline{{ $target->id }}"
                                        name="target_deadline"
                                        value="{{ old('target_deadline', $target->target_deadline->format('Y-m-d')) }}"
                                        min="{{ date('Y-m-d', strtotime('today')) }}" required>
                                    @error('target_deadline')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="jumlah_aktivitas_dibutuhkan{{ $target->id }}"
                                        class="form-label fw-semibold">
                                        <i class="ti ti-number text-info me-2"></i>Jumlah Aktivitas Dibutuhkan
                                    </label>
                                    <input type="number" class="form-control"
                                        id="jumlah_aktivitas_dibutuhkan{{ $target->id }}"
                                        name="jumlah_aktivitas_dibutuhkan"
                                        value="{{ old('jumlah_aktivitas_dibutuhkan', $target->jumlah_aktivitas_dibutuhkan) }}"
                                        min="1" required>
                                    @error('jumlah_aktivitas_dibutuhkan')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
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
        @endforeach

        <!-- Detail Modals -->
        @foreach ($targets as $target)
            <div class="modal fade" id="detailModal{{ $target->id }}" tabindex="-1"
                aria-labelledby="detailModalLabel{{ $target->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-secondary text-white border-0">
                            <h5 class="modal-title" id="detailModalLabel{{ $target->id }}">
                                <i class="ti ti-eye me-2"></i>Detail: {{ $target->nama_target }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="text-muted mb-4">
                                <i class="ti ti-info-circle me-2"></i>
                                Lihat detail target dan daftar progres.
                            </p>

                            <dl class="row">
                                <dt class="col-sm-3">Nama Target</dt>
                                <dd class="col-sm-9">{{ $target->nama_target }}</dd>

                                <dt class="col-sm-3">Hobi</dt>
                                <dd class="col-sm-9">{{ $target->hobi->nama_hobi ?? 'N/A' }}</dd>

                                <dt class="col-sm-3">Kategori</dt>
                                <dd class="col-sm-9">
                                    <span
                                        class="badge {{ $target->hobi->kategoriHobi->background_color ?? 'bg-info-subtle' }} {{ $target->hobi->kategoriHobi->background_color ? 'text-white' : 'text-info' }} px-3 py-2">
                                        <i
                                            class="ti {{ $target->hobi->kategoriHobi->icon ?? 'ti-tag' }} me-1"></i>{{ $target->hobi->kategoriHobi->nama_kategori ?? 'N/A' }}
                                    </span>
                                </dd>

                                <dt class="col-sm-3">Batas Waktu</dt>
                                <dd class="col-sm-9">
                                    {{ \Carbon\Carbon::parse($target->target_deadline)->format('d F Y') }}</dd>

                                <dt class="col-sm-3">Tanggal Dibuat</dt>
                                <dd class="col-sm-9">{{ $target->created_at->format('d F Y') }}</dd>

                                <dt class="col-sm-3">Jumlah Aktivitas Dibutuhkan</dt>
                                <dd class="col-sm-9">{{ $target->jumlah_aktivitas_dibutuhkan }}</dd>
                            </dl>

                            <!-- Aktivitas dan Bukti Section -->
                            @if ($target->aktivitas->count() > 0)
                                <hr class="my-4">
                                <h6 class="fw-bold mb-3 text-primary">
                                    <i class="ti ti-activity me-2"></i>Aktivitas dan Bukti
                                    ({{ $target->aktivitas->count() }})
                                </h6>
                                <div class="row">
                                    @foreach ($target->aktivitas as $aktivitas)
                                        <div class="col-12 mb-4">
                                            <div class="card border-0 shadow-sm h-100">
                                                <div class="card-header bg-light border-0 py-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0 fw-semibold text-dark">
                                                            <i
                                                                class="ti ti-circle-check text-success me-2"></i>{{ $aktivitas->nama_aktivitas }}
                                                        </h6>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    @if ($aktivitas->catatan)
                                                        <p class="mb-3 text-muted">
                                                            <i class="ti ti-message me-2"></i>{{ $aktivitas->catatan }}
                                                        </p>
                                                    @endif

                                                    @php
                                                        $rawFileBukti = $aktivitas->file_bukti;

                                                        // Handle backward compatibility: could be array, JSON string, or plain string
                                                        if (is_array($rawFileBukti)) {
                                                            $fileData = $rawFileBukti;
                                                        } elseif (is_string($rawFileBukti) && !empty($rawFileBukti)) {
                                                            // Try to decode as JSON first
                                                            $decoded = json_decode($rawFileBukti, true);
                                                            if (
                                                                json_last_error() === JSON_ERROR_NONE &&
                                                                is_array($decoded)
                                                            ) {
                                                                $fileData = $decoded;
                                                            } else {
                                                                // Old format: plain string, check if it's GDrive URL
                                                                $fileData = str_contains(
                                                                    $rawFileBukti,
                                                                    'drive.google.com',
                                                                )
                                                                    ? ['gdrive' => $rawFileBukti]
                                                                    : ['file' => $rawFileBukti];
                                                            }
                                                        } else {
                                                            $fileData = [];
                                                        }
                                                        
                                                        $hasFile =
                                                            isset($fileData['file']) && !empty($fileData['file']);
                                                        $hasGdrive =
                                                            isset($fileData['gdrive']) && !empty($fileData['gdrive']);
                                                    @endphp

                                                    @if ($hasFile || $hasGdrive)
                                                        <div class="bukti-files">
                                                            <h6 class="text-secondary mb-3">
                                                                <i class="ti ti-paperclip me-2"></i>Bukti Aktivitas
                                                            </h6>
                                                            <div class="row g-3">
                                                                @if ($hasFile)
                                                                    @php
                                                                        $extension = strtolower(
                                                                            pathinfo(
                                                                                $fileData['file'],
                                                                                PATHINFO_EXTENSION,
                                                                            ),
                                                                        );
                                                                        $imageExts = [
                                                                            'jpg',
                                                                            'jpeg',
                                                                            'png',
                                                                            'gif',
                                                                            'webp',
                                                                        ];

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

                                                                    <div class="col-md-6 col-lg-4">
                                                                        <div class="position-relative">
                                                                            @if ($fileType === 'image')
                                                                                <img src="{{ asset('storage/' . $fileData['file']) }}"
                                                                                    class="img-fluid rounded shadow-sm w-100"
                                                                                    style="height: 150px; object-fit: cover; cursor: pointer;"
                                                                                    alt="Bukti Aktivitas"
                                                                                    onclick="openFileModal('{{ asset('storage/' . $fileData['file']) }}', '{{ $fileType }}', '{{ $aktivitas->nama_aktivitas }}')">
                                                                            @elseif($fileType === 'video')
                                                                                <video
                                                                                    class="img-fluid rounded shadow-sm w-100"
                                                                                    style="height: 150px; object-fit: cover; cursor: pointer;"
                                                                                    onclick="openFileModal('{{ asset('storage/' . $fileData['file']) }}', '{{ $fileType }}', '{{ $aktivitas->nama_aktivitas }}')">
                                                                                    <source
                                                                                        src="{{ asset('storage/' . $fileData['file']) }}"
                                                                                        type="video/{{ $extension === 'mov' ? 'quicktime' : ($extension === 'avi' ? 'avi' : 'mp4') }}">
                                                                                </video>
                                                                            @else
                                                                                <div class="d-flex align-items-center justify-content-center bg-light rounded shadow-sm"
                                                                                    style="height: 150px; cursor: pointer;"
                                                                                    onclick="openFileModal('{{ asset('storage/' . $fileData['file']) }}', '{{ $fileType }}', '{{ $aktivitas->nama_aktivitas }}')">
                                                                                    <i
                                                                                        class="ti {{ $icon }} fs-1 text-muted"></i>
                                                                                </div>
                                                                            @endif
                                                                            <div class="position-absolute top-0 end-0 m-2">
                                                                                <span class="badge bg-primary">
                                                                                    <i
                                                                                        class="ti {{ $icon }}"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                @if ($hasGdrive)
                                                                    <div class="col-md-6 col-lg-4">
                                                                        <div class="position-relative">
                                                                            <div class="d-flex align-items-center justify-content-center bg-light rounded shadow-sm"
                                                                                style="height: 150px; cursor: pointer;"
                                                                                onclick="openFileModal('{{ $fileData['gdrive'] }}', 'gdrive', '{{ $aktivitas->nama_aktivitas }}')">
                                                                                <i class="ti ti-brand-google-drive text-info"
                                                                                    style="font-size: 3rem;"></i>
                                                                            </div>
                                                                            <div class="position-absolute top-0 end-0 m-2">
                                                                                <span class="badge bg-info">
                                                                                    <i class="ti ti-external-link"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="text-center py-4">
                                                            <i class="ti ti-photo-off text-muted"
                                                                style="font-size: 2rem;"></i>
                                                            <p class="text-muted mt-2 mb-0">Tidak ada file bukti</p>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="card-footer bg-transparent border-0 py-2">
                                                    <small class="text-muted">
                                                        <i class="ti ti-calendar me-1"></i>Dilakukan pada
                                                        {{ $aktivitas->created_at->format('d F Y H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <hr class="my-4">
                                <div class="text-center py-5">
                                    <i class="ti ti-activity text-muted" style="font-size: 3rem;"></i>
                                    <h6 class="text-muted mt-3">Belum ada Aktivitas</h6>
                                    <p class="text-muted mb-0">Aktivitas akan muncul di sini setelah Anda menambahkannya.
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ti ti-x me-2"></i>Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('scripts')
    <script>
        var hasSuccess = {!! session('success') ? 'true' : 'false' !!};
        var showModal = '{{ session('show_modal') }}';
        var targetId = '{{ session('target_id') }}';
    </script>
    <script src="{{ asset('admin/js/target.js') }}"></script>
@endsection
