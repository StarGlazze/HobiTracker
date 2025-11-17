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
                            <i class="ti ti-file-text me-2"></i>Log Aktivitas
                        </h3>
                        <p class="text-muted mb-0">Lihat riwayat lengkap aktivitas hobi dan target Anda di sini</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Total Aktivitas</h6>
                                <h4 class="mb-0">{{ $totalAktivitas }}</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-activity fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success bg-gradient text-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Bulan Ini</h6>
                                <h4 class="mb-0">{{ $bulanIni }}</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-calendar fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-info bg-gradient text-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Total Logs</h6>
                                <h4 class="mb-0">{{ $totalAktivitas }}</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-file-text fs-1 text-white-50"></i>
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
                    <div class="col-md-5 mb-3 mb-md-0">
                        <h5 class="mb-1">Riwayat Aktivitas</h5>
                        <p class="text-muted small mb-0">Daftar lengkap aktivitas hobi Anda</p>
                    </div>
                    <div class="col-md-7">
                        <form method="GET" action="{{ route('admin.logs') }}" class="row g-2 align-items-center">
                            <!-- Search -->
                            <div class="col-12 col-lg-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ti ti-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0"
                                        placeholder="Cari aktivitas..." value="{{ $search ?? '' }}">
                                </div>
                            </div>

                            <!-- Date Range -->
                            <div class="col-6 col-lg-3">
                                <input type="date" name="start_date" class="form-control form-control-sm"
                                    value="{{ $startDate ?? '' }}" placeholder="Dari Tanggal">
                            </div>

                            <div class="col-6 col-lg-3">
                                <input type="date" name="end_date" class="form-control form-control-sm"
                                    value="{{ $endDate ?? '' }}" placeholder="Sampai Tanggal">
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 col-lg-2">
                                <div class="d-flex gap-1">
                                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                        <i class="ti ti-filter"></i> Filter
                                    </button>
                                    @if ($search || $startDate || $endDate)
                                        <a href="{{ route('admin.logs') }}" class="btn btn-outline-danger btn-sm">
                                            <i class="ti ti-x"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <!-- Table Aktivitas -->
                    <table id="logs-table" class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col" class="border-0 py-3 px-4" style="width: 120px;">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-calendar-event me-2 text-muted"></i>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-decoration-none text-dark">
                                            Tanggal
                                            @if (request('sort') === 'created_at')
                                                <i
                                                    class="ti ti-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @endif
                                        </a>
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
                                        <i class="ti ti-target me-2 text-muted"></i>
                                        Target
                                    </div>
                                </th>
                                <th scope="col" class="border-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-mood-happy me-2 text-muted"></i>
                                        Mood
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
                            @forelse($logs as $log)
                                <tr class="border-bottom">
                                    <td class="px-4 py-3">
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">{{ $log->created_at->format('d F Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $log->aktivitas->nama_aktivitas }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="fw-semibold">{{ $log->aktivitas->target->nama_target }}</span>
                                        <br><small
                                            class="text-muted">{{ $log->aktivitas->target->hobi->nama_hobi }}</small>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="fw-semibold">{{ $log->aktivitas->energy_mood_level ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-truncate" style="max-width: 200px;">
                                            {{ $log->catatan ?: 'tidak ada catatan' }}
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                            data-bs-target="#detailModal" title="Lihat Detail"
                                            onclick="loadDetail({{ $log->id }}, 'aktivitas')">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        @if (Auth::user()->email === 'admin@example.com')
                                            <form method="POST" action="{{ route('log-aktivitas.destroy', $log) }}"
                                                style="display: inline;"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus log ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger ms-1" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="ti ti-file-x text-muted fs-1"></i>
                                        <p class="text-muted mt-2">Belum ada log aktivitas.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="card-footer bg-transparent border-top">
                <div class="row align-items-center g-3">
                    <!-- Info Text -->
                    <div class="col-12 col-md-4 text-center text-md-start">
                        <small class="text-muted">
                            Menampilkan {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }} dari
                            {{ $logs->total() }} aktivitas
                        </small>
                    </div>

                    <!-- Pagination Links -->
                    <div class="col-12 col-md-4 d-flex justify-content-center">
                        <nav aria-label="Page navigation">
                            @if ($logs->lastPage() > 1)
                                <ul class="pagination pagination-sm mb-0">
                                    {{-- Previous Page Link --}}
                                    @if ($logs->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true" aria-label="Previous">
                                            <span class="page-link" aria-hidden="true">&laquo;</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $logs->appends(request()->query())->previousPageUrl() }}"
                                                rel="prev" aria-label="Previous">&laquo;</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($logs->getUrlRange(max($logs->currentPage() - 2, 1), min($logs->currentPage() + 2, $logs->lastPage())) as $page => $url)
                                        @if ($page == $logs->currentPage())
                                            <li class="page-item active" aria-current="page"><span
                                                    class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($logs->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $logs->appends(request()->query())->nextPageUrl() }}"
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

                    <!-- Export Button -->
                    <div class="col-12 col-md-4 d-flex justify-content-md-end justify-content-center">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exportModal">
                            <i class="ti ti-download me-1"></i> Export PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* Fix video fullscreen cropping */
            video:-webkit-full-screen {
                width: 100% !important;
                height: 100% !important;
                object-fit: contain !important;
            }

            video:-moz-full-screen {
                width: 100% !important;
                height: 100% !important;
                object-fit: contain !important;
            }

            video:-ms-fullscreen {
                width: 100% !important;
                height: 100% !important;
                object-fit: contain !important;
            }

            video:fullscreen {
                width: 100% !important;
                height: 100% !important;
                object-fit: contain !important;
            }

            #detailModal video {
                cursor: pointer;
            }

            #detailModal video::-webkit-media-controls-fullscreen-button {
                display: block;
            }

            /* Pagination responsive fixes */
            @media (max-width: 767px) {
                .card-footer .row>div {
                    text-align: center;
                }
            }
        </style>

    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title" id="exportModalLabel">
                        <i class="ti ti-download me-2"></i>Export PDF Log Aktivitas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('logs.export') }}">
                    @csrf
                    @foreach (request()->query() as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Info:</strong> Export akan menghasilkan PDF dari {{ $logs->count() }} log aktivitas
                            yang sedang ditampilkan di halaman ini.
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="includeImages" name="include_images"
                                    value="1" checked>
                                <label class="form-check-label" for="includeImages">
                                    <strong>Sertakan gambar bukti</strong>
                                </label>
                            </div>
                            <small class="text-muted">
                                <i class="ti ti-info-circle me-1"></i>
                                Menyertakan gambar akan membuat file PDF lebih besar dan memakan waktu lebih lama untuk
                                di-generate.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ti ti-x me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-danger" id="confirmExportBtn">
                            <i class="ti ti-download me-2"></i>Export PDF
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
                        <i class="ti ti-eye me-2"></i>Detail Log Aktivitas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="detail-loading" class="text-center py-5 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2">Memuat detail...</p>
                    </div>
                    <div id="detail-content">
                        <p class="text-muted mb-4">
                            <i class="ti ti-info-circle me-2"></i>
                            Lihat keseluruhan data log aktivitas hobi Anda.
                        </p>

                        <dl class="row">
                            <dt class="col-sm-3">Tanggal</dt>
                            <dd class="col-sm-9" id="detail-tanggal">-</dd>

                            <dt class="col-sm-3">Waktu Upload</dt>
                            <dd class="col-sm-9" id="detail-waktu-upload">-</dd>

                            <dt class="col-sm-3">Aktivitas</dt>
                            <dd class="col-sm-9" id="detail-aktivitas">-</dd>

                            <dt class="col-sm-3">Target</dt>
                            <dd class="col-sm-9" id="detail-target">-</dd>

                            <dt class="col-sm-3">Hobi</dt>
                            <dd class="col-sm-9" id="detail-hobi">-</dd>

                            <dt class="col-sm-3">Mood</dt>
                            <dd class="col-sm-9" id="detail-energy-mood">-</dd>

                            <dt class="col-sm-3">Catatan</dt>
                            <dd class="col-sm-9" id="detail-catatan">-</dd>
                        </dl>

                        <div class="row mt-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Bukti</label>
                                <div id="detail-bukti" class="row g-3">
                                    <p class="text-muted">Tidak ada bukti tersedia.</p>
                                </div>
                            </div>
                        </div>
                    </div>
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

@section('scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('./admin/js/logs.js') }}"></script>
@endsection
