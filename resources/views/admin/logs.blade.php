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
                                <h4 class="mb-0">{{ $totalAktivitas }}</h4>
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
                                <h4 class="mb-0">{{ $bulanIni }}</h4>
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
                                <h4 class="mb-0">{{ $totalDurasi }}m</h4>
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
                                <h4 class="mb-0">{{ $rataRataHarian }}m</h4>
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
                        <div class="col-12">
                            <form method="GET" action="{{ route('admin.logs') }}" class="row g-2 align-items-center">
                                <!-- Cari aktivitas (panjang di desktop, full di HP) -->
                                <div class="col-12 col-md-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ti ti-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control border-start-0"
                                            placeholder="Cari aktivitas..." value="{{ $search ?? '' }}">
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">Cari</button>
                                    </div>
                                </div>

                                <!-- Tanggal mulai -->
                                <div class="col-6 col-md-3">
                                    <input type="date" name="start_date" class="form-control form-control-sm"
                                        value="{{ $startDate ?? '' }}" placeholder="Dari Tanggal"
                                        onfocus="this.showPicker()">
                                </div>

                                <!-- Tanggal akhir -->
                                <div class="col-6 col-md-3">
                                    <input type="date" name="end_date" class="form-control form-control-sm"
                                        value="{{ $endDate ?? '' }}" placeholder="Sampai Tanggal"
                                        onfocus="this.showPicker()">
                                </div>

                                <!-- Tombol filter -->
                                <div class="col-6 col-md-auto">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="ti ti-filter"></i> Filter
                                    </button>
                                </div>

                                <!-- Tombol reset -->
                                @if ($search || $startDate || $endDate)
                                    <div class="col-6 col-md-auto">
                                        <a href="{{ route('admin.logs') }}" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="ti ti-x"></i> Reset
                                        </a>
                                    </div>
                                @endif
                            </form>
                        </div>

                    </div>

                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
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
                                        <span class="fw-semibold px-3 py-2">{{ $log->aktivitas->hobi->nama_hobi }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-semibold">{{ $log->aktivitas->durasi_menit }} Menit</span>
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
                                            onclick="loadDetail({{ $log->id }})">
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
            <div class="card-footer bg-transparent border-top-0">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Menampilkan {{ $logs->firstItem() }}-{{ $logs->lastItem() }} dari
                        {{ $logs->total() }} aktivitas</small>
                    <div class="d-flex align-items-center gap-2">
                        {!! $logs->appends(request()->query())->links() !!}
                        <a href="{{ url('/logs/export?' . http_build_query(request()->query())) }}"
                            class="btn btn-success btn-sm">
                            <i class="ti ti-download"></i> Export PDF
                        </a>
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
        </style>

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

                            <dt class="col-sm-3">Hobi</dt>
                            <dd class="col-sm-9" id="detail-hobi">-</dd>

                            <dt class="col-sm-3">Durasi</dt>
                            <dd class="col-sm-9" id="detail-durasi">-</dd>

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
        <script src="{{ asset('./admin/js/logs.js') }}"></script>
    @endsection
