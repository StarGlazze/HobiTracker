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
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom-0 pt-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-1">
                            <i class="ti ti-list text-primary me-2"></i>Daftar Target Hobi Anda
                        </h5>
                        <p class="text-muted small mb-0">Kelola dan pantau semua target hobi Anda</p>
                    </div>
                    <div class="col-auto">
                        <div class="input-group input-group-sm" style="width: 280px;">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ti ti-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Cari target...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col" class="border-0 py-3 px-4" style="width: 5%;">#</th>
                                <th scope="col" class="border-0 py-3" style="width: 25%;">Nama Target</th>
                                <th scope="col" class="border-0 py-3" style="width: 25%;">Hobi</th>
                                <th scope="col" class="border-0 py-3" style="width: 20%;">Kategori</th>
                                <th scope="col" class="border-0 py-3" style="width: 20%;">Batas Waktu</th>
                                <th scope="col" class="border-0 py-3 text-center" style="width: 5%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Example row, replace with dynamic data --}}
                            <tr class="border-bottom">
                                <td class="px-4 py-3">1</td>
                                <td class="py-3">Belajar Gitar</td>
                                <td class="py-3">Main Gitar</td>
                                <td class="py-3">
                                    <span class="badge bg-info-subtle text-info px-3 py-2">
                                        <i class="ti ti-music me-1"></i>Musik & Performing Arts
                                    </span>
                                </td>
                                <td class="py-3">28 November 2025</td>
                                <td class="py-3 text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editTargetModal" title="Edit Target">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Hapus Target">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#progressModal" title="Lihat Progress">
                                            <i class="ti ti-chart-bar"></i>
                                        </button>
                                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#detailModal" title="Lihat Detail">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="border-bottom">
                                <td class="px-4 py-3">2</td>
                                <td class="py-3">Membaca Novel</td>
                                <td class="py-3">Membaca</td>
                                <td class="py-3">
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                        <i class="ti ti-book me-1"></i>Membaca & Literasi
                                    </span>
                                </td>
                                <td class="py-3">15 Desember 2025</td>
                                <td class="py-3 text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editTargetModal" title="Edit Target">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                            title="Hapus Target">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#progressModal" title="Lihat Progress">
                                            <i class="ti ti-chart-bar"></i>
                                        </button>
                                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#detailModal" title="Lihat Detail">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="border-bottom">
                                <td class="px-4 py-3">3</td>
                                <td class="py-3">Jogging Pagi</td>
                                <td class="py-3">Lari</td>
                                <td class="py-3">
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                        <i class="ti ti-heartbeat me-1"></i>Olahraga & Kebugaran
                                    </span>
                                </td>
                                <td class="py-3">10 Januari 2026</td>
                                <td class="py-3 text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editTargetModal" title="Edit Target">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                            title="Hapus Target">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#progressModal" title="Lihat Progress">
                                            <i class="ti ti-chart-bar"></i>
                                        </button>
                                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#detailModal" title="Lihat Detail">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- End example row --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Target Modal -->
        <div class="modal fade" id="tambahTargetModal" tabindex="-1" aria-labelledby="tambahTargetModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <form>
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
                                <label for="hobiInput" class="form-label fw-semibold">
                                    <i class="ti ti-heart text-danger me-2"></i>Hobi
                                </label>
                                <input type="text" class="form-control" id="hobiInput" name="hobi"
                                    placeholder="Contoh: Membaca, Bersepeda, Menyanyi..." required>
                            </div>


                            <div class="mb-3">
                                <label for="namaTarget" class="form-label fw-semibold">
                                    <i class="ti ti-target text-primary me-2"></i>Nama Target
                                </label>
                                <input type="text" class="form-control" id="namaTarget"
                                    placeholder="Masukkan nama target" required>
                            </div>

                            <div class="mb-3">
                                <label for="targetDeadline" class="form-label fw-semibold">
                                    <i class="ti ti-calendar text-success me-2"></i>Batas Waktu
                                </label>
                                <input type="date" class="form-control" id="targetDeadline" required>
                            </div>
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

        <!-- Edit Target Modal -->
        <div class="modal fade" id="editTargetModal" tabindex="-1" aria-labelledby="editTargetModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <form>
                        <div class="modal-header bg-warning text-white border-0">
                            <h5 class="modal-title" id="editTargetModalLabel">
                                <i class="ti ti-edit me-2"></i>Edit Target
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
                                <label for="editHobiInput" class="form-label fw-semibold">
                                    <i class="ti ti-heart text-danger me-2"></i>Hobi
                                </label>
                                <input type="text" class="form-control" id="editHobiInput" name="hobi"
                                    placeholder="Contoh: Membaca, Bersepeda, Menyanyi..." required>
                            </div>

                            <div class="mb-3">
                                <label for="editNamaTarget" class="form-label fw-semibold">
                                    <i class="ti ti-target text-primary me-2"></i>Nama Target
                                </label>
                                <input type="text" class="form-control" id="editNamaTarget"
                                    placeholder="Masukkan nama target" required>
                            </div>

                            <div class="mb-3">
                                <label for="editTargetDeadline" class="form-label fw-semibold">
                                    <i class="ti ti-calendar text-success me-2"></i>Batas Waktu
                                </label>
                                <input type="date" class="form-control" id="editTargetDeadline" required>
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

        <!-- Progress Modal -->
        <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <form>
                        <div class="modal-header bg-info text-white border-0">
                            <h5 class="modal-title" id="progressModalLabel">
                                <i class="ti ti-chart-bar me-2"></i>Progress Target Hobi
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="text-muted mb-4">
                                <i class="ti ti-info-circle me-2"></i>
                                Pantau perkembangan target hobi Anda sesuai dengan alur Target Hobi.
                            </p>

                            <div class="mb-3">
                                <label for="status" class="form-label fw-semibold">
                                    <i class="ti ti-check-circle text-success me-2"></i>Status
                                </label>
                                <select class="form-select" id="status" required>
                                    <option value="on_progress" selected>On Progress</option>
                                    <option value="completed" id="completedOption" disabled>Completed (Upload bukti terlebih dahulu)</option>
                                    <option value="failed" disabled>Failed (Otomatis jika melewati batas waktu)</option>
                                </select>
                                <div class="form-text">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Status "Completed" hanya bisa dipilih jika Anda mengupload bukti. Status "Failed" akan otomatis jika melewati batas waktu.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="bukti" class="form-label fw-semibold">
                                    <i class="ti ti-paperclip text-primary me-2"></i>Bukti
                                </label>
                                <input class="form-control" type="file" id="bukti"
                                    accept="image/*,video/*">
                                <div class="form-text">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Format yang didukung: Gambar (max 5MB) dan Video (max 50MB)
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="gdriveLink" class="form-label fw-semibold">
                                    <i class="ti ti-link text-success me-2"></i>Link Google Drive (Alternatif)
                                </label>
                                <input type="url" class="form-control" id="gdriveLink"
                                    placeholder="https://drive.google.com/file/...">
                                <div class="form-text">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Gunakan jika file terlalu besar untuk diupload langsung
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="catatan" class="form-label fw-semibold">
                                    <i class="ti ti-notes text-warning me-2"></i>Catatan
                                </label>
                                <textarea class="form-control" id="catatan" rows="3"
                                    placeholder="Tambahkan catatan tentang perkembangan progress..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="ti ti-x me-2"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-info">
                                <i class="ti ti-device-floppy me-2"></i>Simpan Progress
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-secondary text-white border-0">
                        <h5 class="modal-title" id="detailModalLabel">
                            <i class="ti ti-eye me-2"></i>Detail Target Hobi
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                        <div class="modal-body p-4">
                            <p class="text-muted mb-4">
                                <i class="ti ti-info-circle me-2"></i>
                                Lihat keseluruhan data target dan progress hobi Anda.
                            </p>

                            <dl class="row">
                                <dt class="col-sm-3">Nama Target</dt>
                                <dd class="col-sm-9">Belajar Gitar</dd>

                                <dt class="col-sm-3">Hobi</dt>
                                <dd class="col-sm-9">Main Gitar</dd>

                                <dt class="col-sm-3">Kategori</dt>
                                <dd class="col-sm-9">
                                    <span class="badge bg-info-subtle text-info px-3 py-2">
                                        <i class="ti ti-music me-1"></i>Musik & Performing Arts
                                    </span>
                                </dd>

                                <dt class="col-sm-3">Batas Waktu</dt>
                                <dd class="col-sm-9">28 November 2025</dd>

                                <dt class="col-sm-3">Status</dt>
                                <dd class="col-sm-9">
                                    <span class="badge bg-success text-white px-3 py-2 text-uppercase">Completed</span>
                                </dd>

                                <dt class="col-sm-3">Tanggal Dibuat</dt>
                                <dd class="col-sm-9">15 Oktober 2024</dd>

                                <dt class="col-sm-3">Tanggal Diupdate</dt>
                                <dd class="col-sm-9">28 November 2025</dd>

                                <dt class="col-sm-3">Tanggal Completed</dt>
                                <dd class="col-sm-9">28 November 2025</dd>

                                <dt class="col-sm-3">Tanggal Failed</dt>
                                <dd class="col-sm-9">-</dd>

                                <dt class="col-sm-3">Bukti</dt>
                                <dd class="col-sm-9">
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            <div class="position-relative">
                                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjNmNGY2Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5YTNhZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkJ1a3RpIEdhbWJhcjwvdGV4dD48L3N2Zz4=" alt="Bukti Gambar" class="img-fluid rounded shadow-sm" style="max-width: 200px; height: 150px; object-fit: cover;">
                                                <div class="position-absolute top-50 start-50 translate-middle">
                                                    <i class="ti ti-photo text-muted" style="font-size: 2rem;"></i>
                                                </div>
                                            </div>
                                            <small class="text-muted d-block text-center mt-1">Gambar Bukti</small>
                                        </div>
                                        <div class="col-auto">
                                            <div class="position-relative">
                                                <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 200px; height: 150px;">
                                                    <i class="ti ti-video text-muted" style="font-size: 3rem;"></i>
                                                </div>
                                            </div>
                                            <small class="text-muted d-block text-center mt-1">Video Bukti</small>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-link text-primary me-2"></i>
                                            <span class="text-muted">Atau lihat file di </span>
                                            <a href="#" target="_blank" class="text-decoration-none ms-1">
                                                <span class="badge bg-primary-subtle text-primary px-2 py-1">
                                                    <i class="ti ti-external-link me-1"></i>Google Drive
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </dd>

                                <dt class="col-sm-3">Catatan</dt>
                                <dd class="col-sm-9">Sudah menyelesaikan latihan gitar selama 30 jam.</dd>
                            </dl>
                        </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ti ti-x me-2"></i>Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
