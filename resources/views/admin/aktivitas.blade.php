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


        <!-- Quick Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Total Aktivitas</h6>
                                <h4 class="mb-0">3</h4>
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
                                <h4 class="mb-0">3</h4>
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
                                <h6 class="text-white-50 mb-1">Total Durasi</h6>
                                <h4 class="mb-0">100m</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-clock fs-1 text-white-50"></i>
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
                                <h6 class="text-white-50 mb-1">Rata-rata</h6>
                                <h4 class="mb-0">33m</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-trending-up fs-1 text-white-50"></i>
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
                                        <i class="ti ti-activity me-2 text-muted"></i>
                                        <span class="fw-semibold">Aktivitas</span>
                                    </div>
                                </th>
                                <th scope="col" class="border-0 py-3" style="width: 20%;">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-heart me-2 text-muted"></i>
                                        <span class="fw-semibold">Hobi</span>
                                    </div>
                                </th>
                                <th scope="col" class="border-0 py-3" style="width: 15%;">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-clock me-2 text-muted"></i>
                                        <span class="fw-semibold">Durasi</span>
                                    </div>
                                </th>
                                <th scope="col" class="border-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-notes me-2 text-muted"></i>
                                        <span class="fw-semibold">Catatan</span>
                                    </div>
                                </th>
                                <th scope="col" class="border-0 py-3 text-center" style="width: 8%;">
                                    <i class="ti ti-paperclip text-muted"></i>
                                </th>
                                <th scope="col" class="border-0 py-3 text-center" style="width: 12%;">
                                    <span class="fw-semibold">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-bottom">
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1">1</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-semibold">Membaca novel "Dune"</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="fw-semibold px-3 py-2">Membaca</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">

                                        <span class="fw-semibold">60 Menit</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="text-truncate" style="max-width: 180px;" data-bs-toggle="tooltip"
                                        title="Membaca 5 halaman setiap hari untuk mencapai target bulanan">
                                        Membaca 5 halaman setiap hari
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <button class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="tooltip"
                                        title="Lihat file bukti">
                                        <i class="ti ti-file-text"></i>
                                    </button>
                                </td>
                                <td class="py-3 text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAktivitasModal" title="Edit Aktivitas">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                            title="Hapus Aktivitas">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1">2</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-semibold">Lari pagi</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="fw-semibold px-3 py-2">Bersepeda</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold">30 Menit</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="text-truncate" style="max-width: 180px;" data-bs-toggle="tooltip"
                                        title="Lari 3 km setiap pagi untuk menjaga kesehatan dan kebugaran">
                                        Lari 3 km setiap pagi
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <button class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="tooltip"
                                        title="Lihat file bukti">
                                        <i class="ti ti-file-text"></i>
                                    </button>
                                </td>
                                <td class="py-3 text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="tooltip"
                                            title="Edit Aktivitas">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                            title="Hapus Aktivitas">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1">3</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-semibold">Menyanyi lagu "About You"</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="fw-semibold px-3 py-2">Menyanyi
                                    </span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold">10 Menit</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="text-truncate" style="max-width: 180px;" data-bs-toggle="tooltip"
                                        title="Lagu yang wajib dinyanyikan setiap hari untuk latihan vokal">
                                        Lagu yang wajib dinyanyikan setiap hari
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <button class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="tooltip"
                                        title="Lihat file bukti">
                                        <i class="ti ti-file-text"></i>
                                    </button>
                                </td>
                                <td class="py-3 text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="tooltip"
                                            title="Edit Aktivitas">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                            title="Hapus Aktivitas">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Empty State (if no data) -->
            <div class="card-body text-center py-5" id="empty-state" style="display: none;">
                <div class="mb-4">
                    <i class="ti ti-activity text-muted" style="font-size: 4rem;"></i>
                </div>
                <h5 class="text-muted">Belum ada aktivitas</h5>
                <p class="text-muted mb-4">Mulai dengan menambahkan aktivitas hobi pertama Anda</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahAktivitasModal">
                    <i class="ti ti-plus me-2"></i>Tambah Aktivitas Pertama
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Modal -->
    <div class="modal fade" id="tambahAktivitasModal" tabindex="-1" aria-labelledby="tambahAktivitasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form>
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
                                    <label for="pilihHobi" class="form-label fw-semibold">
                                        <i class="ti ti-heart text-danger me-2"></i>Pilih Hobi
                                    </label>
                                    <select class="form-select" id="pilihHobi" required>
                                        <option value="" selected disabled>Pilih Hobi Terkait...</option>
                                        <option value="1">Membaca</option>
                                        <option value="2">Bersepeda</option>
                                        <option value="3">Menyanyi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="durasiMenit" class="form-label fw-semibold">
                                        <i class="ti ti-clock text-info me-2"></i>Durasi (menit)
                                    </label>
                                    <input type="number" class="form-control" id="durasiMenit"
                                        placeholder="Contoh: 30, 120" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="namaAktivitas" class="form-label fw-semibold">
                                <i class="ti ti-activity text-primary me-2"></i>Nama Aktivitas
                            </label>
                            <input type="text" class="form-control" id="namaAktivitas"
                                placeholder="Contoh: Baca novel Dune chapter 1-3, Lari keliling taman 5km" required>
                        </div>

                        <div class="mb-3">
                            <label for="catatanAktivitas" class="form-label fw-semibold">
                                <i class="ti ti-notes text-warning me-2"></i>Catatan
                            </label>
                            <textarea class="form-control" id="catatanAktivitas" rows="3"
                                placeholder="Deskripsi tambahan, target yang ingin dicapai, atau catatan lainnya..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="fileBukti" class="form-label fw-semibold">
                                <i class="ti ti-paperclip text-success me-2"></i>File Bukti
                            </label>
                            <input class="form-control" type="file" id="fileBukti"
                                accept="image/*,video/*" required>
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Format yang didukung: Gambar (max 5MB) dan Video (max 50MB)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="gdriveLink" class="form-label fw-semibold">
                                <i class="ti ti-link text-info me-2"></i>Link Google Drive (Alternatif)
                            </label>
                            <input type="url" class="form-control" id="gdriveLink"
                                placeholder="https://drive.google.com/file/...">
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Gunakan jika file terlalu besar untuk diupload langsung
                            </div>
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

    <!-- Edit Aktivitas Modal -->
    <div class="modal fade" id="editAktivitasModal" tabindex="-1" aria-labelledby="editAktivitasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form>
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
                                    <label for="editPilihHobi" class="form-label fw-semibold">
                                        <i class="ti ti-heart text-danger me-2"></i>Pilih Hobi
                                    </label>
                                    <select class="form-select" id="editPilihHobi" required>
                                        <option value="" selected disabled>Pilih Hobi Terkait...</option>
                                        <option value="1">Membaca</option>
                                        <option value="2">Bersepeda</option>
                                        <option value="3">Menyanyi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editDurasiMenit" class="form-label fw-semibold">
                                        <i class="ti ti-clock text-info me-2"></i>Durasi (menit)
                                    </label>
                                    <input type="number" class="form-control" id="editDurasiMenit"
                                        placeholder="Contoh: 30, 120" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editNamaAktivitas" class="form-label fw-semibold">
                                <i class="ti ti-activity text-primary me-2"></i>Nama Aktivitas
                            </label>
                            <input type="text" class="form-control" id="editNamaAktivitas"
                                placeholder="Contoh: Baca novel Dune chapter 1-3, Lari keliling taman 5km" required>
                        </div>

                        <div class="mb-3">
                            <label for="editCatatanAktivitas" class="form-label fw-semibold">
                                <i class="ti ti-notes text-warning me-2"></i>Catatan
                            </label>
                            <textarea class="form-control" id="editCatatanAktivitas" rows="3"
                                placeholder="Deskripsi tambahan, target yang ingin dicapai, atau catatan lainnya..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="editFileBukti" class="form-label fw-semibold">
                                <i class="ti ti-paperclip text-success me-2"></i>File Bukti
                            </label>
                            <input class="form-control" type="file" id="editFileBukti"
                                accept="image/*,video/*">
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Format yang didukung: Gambar (max 5MB) dan Video (max 50MB)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editGdriveLink" class="form-label fw-semibold">
                                <i class="ti ti-link text-info me-2"></i>Link Google Drive (Alternatif)
                            </label>
                            <input type="url" class="form-control" id="editGdriveLink"
                                placeholder="https://drive.google.com/file/...">
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Gunakan jika file terlalu besar untuk diupload langsung
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
