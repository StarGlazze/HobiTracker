@extends('admin.layouts.app')

@section('title', 'Hobi - HobiTracker')

@section('content')

    <div class="container-fluid" style="padding-top: 20px">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">
                            <i class="ti ti-heart text-danger me-2"></i>Kelola Hobi
                        </h3>
                        <p class="text-muted mb-0">Tambah, edit, dan kelola semua hobi favorit Anda</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importHobiModal">
                            <i class="ti ti-file-upload me-2"></i>Import Excel
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahHobiModal">
                            <i class="ti ti-plus me-2"></i>Tambah Hobi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Stats Cards -->
        <div class="row mb-4 g-3">
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="card border-0 shadow-sm bg-primary bg-gradient text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-heart me-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="text-white-50 mb-0">Total Hobi</h6>
                                </div>
                                <h2 class="fw-bold mb-1">{{ $totalHobi ?? 0 }}</h2>
                                <small class="text-white-75">Hobi yang terdaftar</small>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-heart" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-trending-up me-2"></i>
                                <small class="text-white-75">
                                    @if ($hobiBulanIni > 0)
                                        +{{ $hobiBulanIni }} hobi bulan ini
                                    @elseif($totalHobi > 0)
                                        Tidak ada hobi baru bulan ini
                                    @else
                                        Belum ada hobi
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="card border-0 shadow-sm bg-success bg-gradient text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-medal me-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="text-white-50 mb-0">Hobi Terpopuler</h6>
                                </div>
                                <h4 class="fw-bold mb-1">{{ $hobiTerpopuler ? $hobiTerpopuler->nama_hobi : 'Belum ada' }}
                                </h4>
                                <small class="text-white-75">{{ $maxAktivitas }} aktivitas tercatat</small>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-medal" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="progress bg-white-25" style="height: 4px;">
                                @php
                                    $percentage =
                                        $totalHobi > 0
                                            ? min(
                                                ($maxAktivitas /
                                                    max(
                                                        $hobis->sum(function ($h) {
                                                            return $h->targetHobi->sum(function ($target) {
                                                                return $target->aktivitas->count();
                                                            });
                                                        }),
                                                        1,
                                                    )) *
                                                    100,
                                                100,
                                            )
                                            : 0;
                                @endphp
                                <div class="progress-bar bg-white" style="width: {{ $percentage }}%"></div>
                            </div>
                            <small class="text-white-75 mt-2 d-block">{{ number_format($percentage, 1) }}% dari total
                                aktivitas</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="card border-0 shadow-sm bg-info bg-gradient text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-category me-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="text-white-50 mb-0">Kategori Aktif</h6>
                                </div>
                                <h4 class="fw-bold mb-1">{{ $kategoriAktif ?? 0 }}</h4>
                                <small class="text-white-75">Kategori berbeda</small>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-category" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-star me-2"></i>
                                <small class="text-white-75">
                                    @if ($hobiTerpopuler && $hobiTerpopuler->kategoriHobi)
                                        {{ $hobiTerpopuler->kategoriHobi->nama_kategori }} paling populer
                                    @else
                                        Belum ada kategori aktif
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Category Distribution Cards -->
        @if ($topKategoriHobis->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="fw-semibold mb-3">
                        <i class="ti ti-chart-pie text-primary me-2"></i>Distribusi Kategori
                    </h5>
                </div>
                @foreach ($topKategoriHobis as $kategori)
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <div
                                        class="avatar-lg rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <i class="ti {{ $kategori->icon ?? 'ti-book' }} text-dark"
                                            style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                                <h6 class="fw-semibold">{{ $kategori->nama_kategori }}</h6>
                                <span
                                    class="badge {{ $kategori->background_color ?? 'bg-primary' }} text-white px-3 py-2">{{ $kategori->hobis_count }}
                                    Hobi</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Main Table Card with Enhanced Search & Sort -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-1">
                            <i class="ti ti-list text-primary me-2"></i>Daftar Hobi Anda
                        </h5>
                        <p class="text-muted small mb-0">Kelola dan pantau semua hobi favorit Anda</p>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('hobi.index') }}" id="searchForm">
                            <!-- Preserve sorting parameters -->
                            <input type="hidden" name="sort_by" value="{{ $sortBy ?? 'nama_hobi' }}">
                            <input type="hidden" name="sort_direction" value="{{ $sortDirection ?? 'asc' }}">

                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white">
                                    <i class="ti ti-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0"
                                    placeholder="Cari hobi, kategori, atau deskripsi..." name="search" id="searchInput"
                                    value="{{ $search ?? '' }}" autocomplete="off">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>

                            @if (!empty($search))
                                <div class="mt-1">
                                    <small class="text-muted">
                                        Hasil untuk: <strong>"{{ $search }}"</strong>
                                        <a href="{{ route('hobi.index', ['sort_by' => $sortBy, 'sort_direction' => $sortDirection]) }}"
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
                                <th scope="col" class="border-0 py-3 px-4" style="width: 5%;">
                                    <span class="text-muted px-2 py-1">#</span>
                                </th>
                                <th scope="col" class="border-0 py-3" style="width: 25%;">
                                    <a href="{{ route('hobi.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'nama_hobi', 'sort_direction' => $sortBy == 'nama_hobi' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                        class="text-decoration-none text-dark d-flex align-items-center sortable-header {{ $sortBy == 'nama_hobi' ? 'active' : '' }}">
                                        <i class="ti ti-heart me-2 text-danger"></i>
                                        <span class="fw-semibold">Nama Hobi</span>
                                        @if ($sortBy == 'nama_hobi')
                                            <i
                                                class="ti ti-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ms-2 text-primary"></i>
                                        @else
                                            <i class="ti ti-selector ms-2 text-muted" style="opacity: 0.3;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="border-0 py-3" style="width: 20%;">
                                    <a href="{{ route('hobi.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'kategori', 'sort_direction' => $sortBy == 'kategori' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
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
                                <th scope="col" class="border-0 py-3" style="width: 35%;">
                                    <a href="{{ route('hobi.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'deskripsi', 'sort_direction' => $sortBy == 'deskripsi' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                        class="text-decoration-none text-dark d-flex align-items-center sortable-header {{ $sortBy == 'deskripsi' ? 'active' : '' }}">
                                        <i class="ti ti-notes me-2 text-info"></i>
                                        <span class="fw-semibold">Deskripsi</span>
                                        @if ($sortBy == 'deskripsi')
                                            <i
                                                class="ti ti-arrow-{{ $sortDirection == 'asc' ? 'up' : 'down' }} ms-2 text-primary"></i>
                                        @else
                                            <i class="ti ti-selector ms-2 text-muted" style="opacity: 0.3;"></i>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="border-0 py-3 text-center" style="width: 15%;">
                                    <span class="fw-semibold text-muted">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hobis as $hobi)
                                <tr class="border-bottom hover-row">
                                    <td class="px-4 py-3">
                                        <span
                                            class="text-muted">{{ ($hobis->currentPage() - 1) * $hobis->perPage() + $loop->iteration }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $hobi->nama_hobi }}</h6>
                                                <small class="text-muted">
                                                    <i class="ti ti-calendar-event" style="font-size: 0.75rem;"></i>
                                                    {{ $hobi->created_at->format('d M Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span
                                            class="badge bg-{{ $hobi->kategoriHobi->background_color ?? 'primary' }}-subtle text-dark px-3 py-2">
                                            <i class="ti {{ $hobi->kategoriHobi->icon ?? 'ti-category' }} me-1"></i>
                                            {{ $hobi->kategoriHobi->nama_kategori ?? 'Tidak Diketahui' }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        @if ($hobi->deskripsi)
                                            <div class="text-truncate" style="max-width: 300px;" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="{{ $hobi->deskripsi }}">
                                                {{ $hobi->deskripsi }}
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic">Tidak ada deskripsi</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editHobiModal" title="Edit Hobi"
                                                data-id="{{ $hobi->id }}" data-nama="{{ $hobi->nama_hobi }}"
                                                data-kategori="{{ $hobi->kategori_id }}"
                                                data-deskripsi="{{ $hobi->deskripsi }}">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            <form action="{{ route('hobi.destroy', $hobi->id) }}" method="POST"
                                                class="d-inline hapus-hobi-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Hobi"
                                                    data-nama="{{ $hobi->nama_hobi }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="ti ti-mood-sad text-muted mb-3" style="font-size: 4rem;"></i>
                                            @if (!empty($search))
                                                <h5 class="text-muted mb-2">Tidak ada hasil untuk "{{ $search }}"
                                                </h5>
                                                <p class="text-muted mb-3">Coba gunakan kata kunci yang berbeda</p>
                                                <a href="{{ route('hobi.index', ['sort_by' => $sortBy, 'sort_direction' => $sortDirection]) }}"
                                                    class="btn btn-primary">
                                                    <i class="ti ti-arrow-left me-2"></i>Tampilkan Semua Hobi
                                                </a>
                                            @else
                                                <h5 class="text-muted mb-2">Belum ada hobi</h5>
                                                <p class="text-muted mb-3">Mulai dengan menambahkan hobi pertama Anda</p>
                                                <button class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#tambahHobiModal">
                                                    <i class="ti ti-plus me-2"></i>Tambah Hobi Sekarang
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($hobis->hasPages())
                    <div class="card-footer bg-transparent border-top">
                        <div class="row align-items-center g-3">
                            <div class="col-md-6 text-center text-md-start">
                                <small class="text-muted">
                                    <i class="ti ti-list-details me-1"></i>
                                    Menampilkan <strong>{{ $hobis->firstItem() ?? 0 }}</strong> -
                                    <strong>{{ $hobis->lastItem() ?? 0 }}</strong> dari
                                    <strong>{{ $hobis->total() }}</strong> hobi
                                </small>
                            </div>
                            <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                                <nav aria-label="Page navigation">
                                    @if ($hobis->lastPage() > 1)
                                        <ul class="pagination pagination-sm mb-0">
                                            {{-- Previous Page Link --}}
                                            @if ($hobis->onFirstPage())
                                                <li class="page-item disabled" aria-disabled="true"
                                                    aria-label="Previous">
                                                    <span class="page-link" aria-hidden="true">&laquo;</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $hobis->appends(request()->query())->previousPageUrl() }}"
                                                        rel="prev" aria-label="Previous">&laquo;</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($hobis->getUrlRange(max($hobis->currentPage() - 2, 1), min($hobis->currentPage() + 2, $hobis->lastPage())) as $page => $url)
                                                @if ($page == $hobis->currentPage())
                                                    <li class="page-item active" aria-current="page"><span
                                                            class="page-link">{{ $page }}</span></li>
                                                @else
                                                    <li class="page-item"><a class="page-link"
                                                            href="{{ $url }}">{{ $page }}</a></li>
                                                @endif
                                            @endforeach

                                            {{-- Next Page Link --}}
                                            @if ($hobis->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $hobis->appends(request()->query())->nextPageUrl() }}"
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

        <link rel="stylesheet" href="{{ asset('admin/css/hobi-custom.css') }}">
    </div>

    <!-- Enhanced Modal -->
    <div class="modal fade" id="tambahHobiModal" tabindex="-1" aria-labelledby="tambahHobiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="{{ route('hobi.store') }}">
                    @csrf
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title" id="tambahHobiModalLabel">
                            <i class="ti ti-plus-circle me-2"></i>Tambah Hobi Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted mb-4">
                            <i class="ti ti-info-circle me-2"></i>
                            Definisikan hobi baru Anda agar bisa mulai melacak dan mencatat semua aktivitasnya
                        </p>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="namaHobi" class="form-label fw-semibold">
                                        <i class="ti ti-heart text-danger me-2"></i>Nama Hobi
                                    </label>
                                    <input type="text" class="form-control @error('nama_hobi') is-invalid @enderror"
                                        id="namaHobi" name="nama_hobi"
                                        placeholder="Contoh: Bermain Gitar, Memasak, Fotografi"
                                        value="{{ old('nama_hobi') }}" required>
                                    @error('nama_hobi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="kategoriHobi" class="form-label fw-semibold">
                                <i class="ti ti-category text-success me-2"></i>Kategori Hobi
                            </label>
                            <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategoriHobi"
                                name="kategori_id" required>
                                <option value="" disabled {{ old('kategori_id') ? '' : 'selected' }}>Pilih Kategori
                                    Hobi...</option>
                                @foreach ($kategoriHobis as $kategori)
                                    <option value="{{ $kategori->id }}" data-icon="{{ $kategori->icon ?? '' }}"
                                        {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsiHobi" class="form-label fw-semibold">
                                <i class="ti ti-notes text-info me-2"></i>Deskripsi Hobi
                            </label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsiHobi" name="deskripsi"
                                rows="3"
                                placeholder="Ceritakan tentang hobi ini, mengapa Anda menyukainya, atau apa yang ingin Anda capai...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="ti ti-bulb me-1"></i>
                                Tip: Deskripsi yang baik akan membantu Anda mengingat motivasi awal memulai hobi ini
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ti ti-x me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Simpan Hobi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Hobi Modal -->
    <div class="modal fade" id="importHobiModal" tabindex="-1" aria-labelledby="importHobiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="{{ route('hobi.import') }}" enctype="multipart/form-data"
                    id="importHobiForm">
                    @csrf
                    <div class="modal-header bg-success text-white border-0">
                        <h5 class="modal-title" id="importHobiModalLabel">
                            <i class="ti ti-file-upload me-2"></i>Import Hobi dari Excel
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted mb-4">
                            <i class="ti ti-info-circle me-2"></i>
                            Upload file Excel untuk mengimpor hobi secara massal. Hobi dengan nama sama akan diperbarui.
                        </p>

                        <div class="alert alert-info border-0">
                            <h6 class="fw-semibold mb-2">
                                <i class="ti ti-file-spreadsheet me-2"></i>Format File Excel
                            </h6>
                            <p class="mb-2">File Excel harus memiliki header pada baris pertama:</p>
                            <ul class="mb-0 small">
                                <li><strong>nama_hobi</strong> (wajib) - Nama hobi</li>
                                <li><strong>kategori_id</strong> (wajib) - ID kategori hobi</li>
                                <li><strong>deskripsi</strong> (opsional) - Deskripsi hobi</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label for="importFile" class="form-label fw-semibold">
                                <i class="ti ti-file me-2"></i>Pilih File Excel
                            </label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror"
                                id="importFile" name="file" accept=".xlsx,.xls" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="ti ti-bulb me-1"></i>
                                Format yang didukung: .xlsx, .xls (maksimal 2MB)
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-semibold mb-2">Daftar Kategori Hobi:</h6>
                            <div class="row g-2">
                                @foreach ($kategoriHobis as $kategori)
                                    <div class="col-md-6">
                                        <div
                                            class="badge bg-{{ $kategori->background_color ?? 'primary' }}-subtle text-dark px-3 py-2 w-100">
                                            <strong>{{ $kategori->id }}</strong> - {{ $kategori->nama_kategori }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ti ti-x me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-success" id="importSubmitBtn">
                            <i class="ti ti-file-upload me-2"></i>Import Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Hobi Modal -->
    <div class="modal fade" id="editHobiModal" tabindex="-1" aria-labelledby="editHobiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form method="POST" id="editHobiForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-white border-0">
                        <h5 class="modal-title" id="editHobiModalLabel">
                            <i class="ti ti-edit me-2"></i>Edit Hobi
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted mb-4">
                            <i class="ti ti-info-circle me-2"></i>
                            Edit detail hobi Anda sesuai dengan perubahan yang diinginkan
                        </p>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="editNamaHobi" class="form-label fw-semibold">
                                        <i class="ti ti-heart text-danger me-2"></i>Nama Hobi
                                    </label>
                                    <input type="text" class="form-control @error('nama_hobi') is-invalid @enderror"
                                        id="editNamaHobi" name="nama_hobi"
                                        placeholder="Contoh: Bermain Gitar, Memasak, Fotografi" required>
                                    @error('nama_hobi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editKategoriHobi" class="form-label fw-semibold">
                                <i class="ti ti-category text-success me-2"></i>Kategori Hobi
                            </label>
                            <select class="form-select @error('kategori_id') is-invalid @enderror" id="editKategoriHobi"
                                name="kategori_id" required>
                                <option value="" disabled selected>Pilih Kategori Hobi...</option>
                                @foreach ($kategoriHobis as $kategori)
                                    <option value="{{ $kategori->id }}" data-icon="{{ $kategori->icon ?? '' }}">
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="editDeskripsiHobi" class="form-label fw-semibold">
                                <i class="ti ti-notes text-info me-2"></i>Deskripsi Hobi
                            </label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="editDeskripsiHobi" name="deskripsi"
                                rows="3"
                                placeholder="Ceritakan tentang hobi ini, mengapa Anda menyukainya, atau apa yang ingin Anda capai..."></textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="ti ti-bulb me-1"></i>
                                Tip: Deskripsi yang baik akan membantu Anda mengingat motivasi awal memulai hobi ini
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
@endsection

@section('scripts')
    <script src="{{ asset('./admin/js/hobi.js') }}"></script>
@endsection
