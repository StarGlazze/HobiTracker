@extends('admin.layouts.app')

@section('title', 'Hobi - HobiTracker')

@section('content')
    <div class="container-fluid" style="padding-top: 20px">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
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
                                <h2 class="fw-bold mb-1">3</h2>
                                <small class="text-white-75">Hobi yang terdaftar</small>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-heart" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-trending-up me-2"></i>
                                <small class="text-white-75">+1 hobi bulan ini</small>
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
                                <h4 class="fw-bold mb-1">Membaca</h4>
                                <small class="text-white-75">15 aktivitas tercatat</small>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-medal" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="progress bg-white-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: 75%"></div>
                            </div>
                            <small class="text-white-75 mt-2 d-block">75% dari total aktivitas</small>
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
                                <h4 class="fw-bold mb-1">3</h4>
                                <small class="text-white-75">Kategori berbeda</small>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-category" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-star me-2"></i>
                                <small class="text-white-75">Membaca & Literasi paling populer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Distribution Cards -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="fw-semibold mb-3">
                    <i class="ti ti-chart-pie text-primary me-2"></i>Distribusi Kategori
                </h5>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div
                                class="avatar-lg bg-primary-subtle rounded-circle d-inline-flex align-items-center justify-content-center">
                                <i class="ti ti-book-2 text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <h6 class="fw-semibold">Membaca & Literasi</h6>
                        <p class="text-muted small mb-3">Hobi yang berkaitan dengan membaca dan pembelajaran</p>
                        <span class="badge bg-primary-subtle text-primary px-3 py-2">1 Hobi</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div
                                class="avatar-lg bg-success-subtle rounded-circle d-inline-flex align-items-center justify-content-center">
                                <i class="ti ti-bike text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <h6 class="fw-semibold">Olahraga & Kebugaran</h6>
                        <p class="text-muted small mb-3">Aktivitas fisik dan menjaga kesehatan tubuh</p>
                        <span class="badge bg-success-subtle text-success px-3 py-2">1 Hobi</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div
                                class="avatar-lg bg-info-subtle rounded-circle d-inline-flex align-items-center justify-content-center">
                                <i class="ti ti-music text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <h6 class="fw-semibold">Musik & Performing Arts</h6>
                        <p class="text-muted small mb-3">Seni pertunjukan dan ekspresi musikal</p>
                        <span class="badge bg-info-subtle text-info px-3 py-2">1 Hobi</span>
                    </div>
                </div>
            </div>
        </div>

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
                        <div class="input-group input-group-sm" style="width: 280px;">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ti ti-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Cari hobi...">
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
                                        <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                            <i class="ti ti-book me-1"></i>{{ $hobi->kategoriHobi->nama_kategori ?? 'Tidak Diketahui' }}
                                        </span>
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
                                                data-bs-target="#editHobiModal" title="Edit Hobi" data-id="{{ $hobi->id }}" data-nama="{{ $hobi->nama_hobi }}" data-kategori="{{ $hobi->kategori_id }}" data-deskripsi="{{ $hobi->deskripsi }}">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            <form action="{{ route('hobi.destroy', $hobi->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus hobi ini?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Hapus Hobi">
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
                <form>
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
                                    <input type="text" class="form-control" id="editNamaHobi"
                                        placeholder="Contoh: Bermain Gitar, Memasak, Fotografi" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editKategoriHobi" class="form-label fw-semibold">
                                <i class="ti ti-category text-success me-2"></i>Kategori Hobi
                            </label>
                            <select class="form-select" id="editKategoriHobi" required>
                                <option value="" selected disabled>Pilih Kategori Hobi...</option>
                                <option value="olahraga-kebugaran" data-icon="ti-bike">Olahraga & Kebugaran</option>
                                <option value="seni-kreativitas" data-icon="ti-palette">Seni & Kreativitas</option>
                                <option value="musik-performing" data-icon="ti-music">Musik & Performing Arts</option>
                                <option value="membaca-literasi" data-icon="ti-book-2">Membaca & Literasi</option>
                                <option value="gaming-esports" data-icon="ti-device-gamepad-2">Gaming & E-Sports</option>
                                <option value="kuliner-memasak" data-icon="ti-chef-hat">Kuliner & Memasak</option>
                                <option value="travel-outdoor" data-icon="ti-mountain">Travel & Outdoor</option>
                                <option value="komunitas-sosial" data-icon="ti-users"> Komunitas & Sosial</option>
                                <option value="koleksi-khusus" data-icon="ti-collection">Koleksi & Hobi Khusus</option>
                                <option value="teknologi-sains" data-icon="ti-cpu">Teknologi & Sains</option>
                                <option value="relaksasi-lifestyle" data-icon="ti-lotus">Relaksasi & Lifestyle</option>
                                <option value="lainnya" data-icon="ti-dots">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editDeskripsiHobi" class="form-label fw-semibold">
                                <i class="ti ti-notes text-info me-2"></i>Deskripsi Hobi
                            </label>
                            <textarea class="form-control" id="editDeskripsiHobi" rows="3"
                                placeholder="Ceritakan tentang hobi ini, mengapa Anda menyukainya, atau apa yang ingin Anda capai..."></textarea>
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
