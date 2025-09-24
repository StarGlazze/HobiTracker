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
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahHobiModal">
                            <i class="ti ti-plus me-2"></i>Tambah Hobi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
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
            <div class="col-md-4">
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
                                                            return $h->aktivitas->count();
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
            <div class="col-md-4">
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
                    <div class="col-4 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <div
                                        class="avatar-lg {{ $kategori->background_color ?? 'bg-primary' }}-subtle rounded-circle d-inline-flex align-items-center justify-content-center">
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

        <!-- Main Table Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom-0 pt-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-1">
                            <i class="ti ti-list text-primary me-2"></i>Daftar Hobi Anda
                        </h5>
                        <p class="text-muted small mb-0">Kelola dan pantau semua hobi favorit Anda</p>
                    </div>
                    <div class="col-auto">
                        <div class="input-group input-group-sm" style="width: 320px;">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ti ti-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Cari hobi..."
                                id="searchHobi">
                            <button class="btn btn-outline-secondary border-start-0" type="button" id="clearSearchBtn"
                                style="display: none;">
                                <i class="ti ti-x text-muted"></i>
                            </button>
                        </div>
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
                                <th scope="col" class="border-0 py-3" style="width: 30%;">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-heart me-2 text-muted"></i>
                                        <span class="fw-semibold">Hobi</span>
                                    </div>
                                </th>
                                <th scope="col" class="border-0 py-3" style="width: 25%;">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-category me-2 text-muted"></i>
                                        <span class="fw-semibold">Kategori</span>
                                    </div>
                                </th>
                                <th scope="col" class="border-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-notes me-2 text-muted"></i>
                                        <span class="fw-semibold">Deskripsi</span>
                                    </div>
                                </th>
                                <th scope="col" class="border-0 py-3 text-center" style="width: 15%;">
                                    <span class="fw-semibold">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hobis as $index => $hobi)
                                <tr class="border-bottom">
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-1 fw-semibold">{{ $hobi->nama_hobi }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <strong>{{ $hobi->kategoriHobi->nama_kategori ?? 'Tidak Diketahui' }}</strong>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-truncate" style="max-width: 250px;" data-bs-toggle="tooltip"
                                            title="{{ $hobi->deskripsi }}">
                                            {{ $hobi->deskripsi }}
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editHobiModal" title="Edit Hobi"
                                                data-id="{{ $hobi->id }}" data-nama="{{ $hobi->nama_hobi }}"
                                                data-kategori="{{ $hobi->kategori_id }}"
                                                data-deskripsi="{{ $hobi->deskripsi }}">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            <form action="{{ route('hobi.destroy', $hobi->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus hobi ini?');"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    data-bs-toggle="tooltip" title="Hapus Hobi">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        Belum ada hobi yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Empty State (if no data) -->

            <div class="card-body text-center py-5" id="empty-state" style="display: none;">
                <div class="mb-4">
                    <i class="ti ti-heart text-muted" style="font-size: 4rem;"></i>
                </div>
                <h5 class="text-muted">Belum ada hobi</h5>
                <p class="text-muted mb-4">Mulai dengan menambahkan hobi pertama Anda</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahHobiModal">
                    <i class="ti ti-plus me-2"></i>Tambah Hobi Pertama
                </button>
            </div>

        </div>
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