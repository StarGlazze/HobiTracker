@extends('admin.layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
    <div class="container-fluid" style="padding-top: 20px">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1">
                            <i class="ti ti-file-text me-2"></i>Log Aktivitas</h3>
                        <p class="text-muted mb-0">Lihat riwayat lengkap aktivitas hobi Anda di sini</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Total Aktivitas</h6>
                                <h4 class="mb-0">156</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-activity fs-1 text-white-50"></i>
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
                                <h6 class="text-white-50 mb-1">Bulan Ini</h6>
                                <h4 class="mb-0">23</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-calendar fs-1 text-white-50"></i>
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
                                <h4 class="mb-0">1,240m</h4>
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
                                <h6 class="text-white-50 mb-1">Rata-rata Harian</h6>
                                <h4 class="mb-0">45m</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-trending-up fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom-0 pt-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-1">Riwayat Aktivitas</h5>
                        <p class="text-muted small mb-0">Daftar lengkap aktivitas hobi Anda</p>
                    </div>
                    <div class="col-auto">
                        <div class="input-group input-group-sm" style="width: 250px;">
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
                                <th scope="col" class="border-0 py-3 px-4">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-calendar-event me-2 text-muted"></i>
                                        Tanggal
                                    </div>
                                </th>
                                <th scope="col" class="border-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-activity me-2 text-muted"></i>
                                        Aktivitas
                                    </div>
                                </th>
                                <th scope="col" class="border-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-heart me-2 text-muted"></i>
                                        Hobi
                                    </div>
                                </th>
                                <th scope="col" class="border-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-clock me-2 text-muted"></i>
                                        Durasi
                                    </div>
                                </th>
                                <th scope="col" class="border-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-notes me-2 text-muted"></i>
                                        Catatan
                                    </div>
                                </th>
                                <th scope="col" class="border-0 py-3 text-center">
                                    <i class="ti ti-paperclip text-muted"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-bottom">
                                <td class="px-4 py-3">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">10 September 2025</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-1">Membaca novel "Dune"</h6>
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
                                    <div class="text-truncate" style="max-width: 200px;">
                                        Membaca 5 halaman setiap hari untuk mencapai target
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#detailModal" title="Lihat Detail">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="px-4 py-3">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">24 September 2025</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-1">Gowes pagi</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="fw-semibold px-3 py-2">Olahraga</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold">30 Menit</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="text-truncate" style="max-width: 200px;">
                                        Lari 3 km setiap pagi untuk menjaga kesehatan
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#detailModal" title="Lihat Detail">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="px-4 py-3">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">03 Oktober 2025</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-1">Menyanyi lagu "About You"</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="fw-semibold px-3 py-2">Menyanyi</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold">10 Menit</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="text-truncate" style="max-width: 200px;">
                                        Lagu yang wajib dinyanyikan setiap hari
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#detailModal" title="Lihat Detail">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="card-footer bg-transparent border-top-0">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Menampilkan 1-10 dari 156 aktivitas</small>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#"><i class="ti ti-chevron-left"></i></a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#"><i class="ti ti-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-secondary text-white border-0">
                    <h5 class="modal-title" id="detailModalLabel">
                        <i class="ti ti-eye me-2"></i>Detail Log Aktivitas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">
                        <i class="ti ti-info-circle me-2"></i>
                        Lihat keseluruhan data log aktivitas hobi Anda.
                    </p>

                    <dl class="row">
                        <dt class="col-sm-3">Tanggal</dt>
                        <dd class="col-sm-9">10 September 2025</dd>

                        <dt class="col-sm-3">Waktu Mulai</dt>
                        <dd class="col-sm-9">08:00</dd>

                        <dt class="col-sm-3">Waktu Selesai</dt>
                        <dd class="col-sm-9">09:00</dd>

                        <dt class="col-sm-3">Aktivitas</dt>
                        <dd class="col-sm-9">Membaca novel "Dune"</dd>

                        <dt class="col-sm-3">Hobi</dt>
                        <dd class="col-sm-9">Membaca</dd>

                        <dt class="col-sm-3">Durasi</dt>
                        <dd class="col-sm-9">60 Menit</dd>

                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">
                            <span class="badge bg-success text-white px-3 py-2">Selesai</span>
                        </dd>

                        <dt class="col-sm-3">Catatan</dt>
                        <dd class="col-sm-9">Membaca 5 halaman setiap hari untuk mencapai target</dd>

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

@endsection
