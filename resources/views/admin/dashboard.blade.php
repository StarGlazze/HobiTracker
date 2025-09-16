@extends('admin.layouts.app')

@section('title', 'Dashboard - HobiTracker')

@section('content')
    <div class="container-fluid" style="padding-top: 20px">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">
                            <i class="ti ti-dashboard text-primary me-2"></i>Dashboard HobiTracker
                        </h3>
                        <p class="text-muted mb-0">
                            Pantau dan kelola hobi serta aktivitas Anda dengan mudah dan menyenangkan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Stats Cards -->
        <div class="row mb-4 g-3 g-md-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary bg-gradient text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-heart me-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="text-white-50 mb-0">Total Hobbies</h6>
                                </div>
                                <h2 class="fw-bold mb-1">12</h2>
                                <small class="text-white-75">+2 dari bulan lalu</small>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-heart" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-trending-up me-2"></i>
                                <small class="text-white-75">Aktif bulan ini</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success bg-gradient text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-activity me-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="text-white-50 mb-0">Total Activities</h6>
                                </div>
                                <h2 class="fw-bold mb-1">34</h2>
                                <small class="text-white-75">+8 minggu ini</small>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-activity" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="progress bg-white-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: 75%"></div>
                            </div>
                            <small class="text-white-75 mt-2 d-block">75% dari target</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning bg-gradient text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-target me-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="text-white-50 mb-0">Active Targets</h6>
                                </div>
                                <h2 class="fw-bold mb-1">7</h2>
                                <small class="text-white-75">5 tercapai bulan ini</small>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-target" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-star me-2"></i>
                                <small class="text-white-75">Target tercapai</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-danger bg-gradient text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-trending-up me-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="text-white-50 mb-0">Progress Rate</h6>
                                </div>
                                <h2 class="fw-bold mb-1">78%</h2>
                                <small class="text-white-75">+12% minggu lalu</small>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-trending-up" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-chart-line me-2"></i>
                                <small class="text-white-75">Tren positif</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row utama: Chart besar + Weekly Stats --}}
        <div class="row g-4 mb-4 align-items-start">
            {{-- Chart besar --}}
            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h4 class="card-title mb-0">Activity Overview</h4>
                                <small class="text-muted">Visualisasi aktivitas hobi berdasarkan kategori</small>
                            </div>
                            <div>
                                <select class="form-select form-select-sm" style="width: 120px;">
                                    <option selected>Harian</option>
                                    <option>Mingguan</option>
                                    <option>Bulanan</option>
                                </select>
                            </div>
                        </div>
                        <div id="sales-overview" class="mt-4 mx-n6"></div>

                    </div>
                </div>
            </div>

            {{-- Weekly Stats --}}
            <div class="col-lg-4">
                <div class="card shadow-sm" style="max-height: 420px; overflow-y: auto;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Weekly Stats</h5>

                        {{-- Top Hobby --}}
                        <div class="mb-4 weekly-stat-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-trophy text-primary fs-5 me-2 stat-icon-primary"></i>
                                <span class="fw-semibold">Top Hobby</span>
                            </div>
                            <h6 class="fw-bold text-primary">Bermain Gitar</h6>
                            <div class="progress mb-1" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: 92%"></div>
                            </div>
                            <small class="text-muted">92% aktivitas minggu ini</small>
                        </div>

                        {{-- Consistency Streak --}}
                        <div class="mb-4 weekly-stat-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-calendar text-success fs-5 me-2 stat-icon-success"></i>
                                <span class="fw-semibold">Consistency Streak</span>
                            </div>
                            <h6 class="fw-bold text-success">15 Hari</h6>
                            <div class="progress mb-1" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 75%"></div>
                            </div>
                            <small class="text-muted">Target 20 hari streak</small>
                        </div>

                        {{-- Time Invested --}}
                        <div class="mb-4 weekly-stat-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-clock text-warning fs-5 me-2 stat-icon-warning"></i>
                                <span class="fw-semibold">Time Invested</span>
                            </div>
                            <h6 class="fw-bold text-warning">24.5 Jam</h6>
                            <div class="progress mb-1" style="height: 6px;">
                                <div class="progress-bar bg-warning" style="width: 68%"></div>
                            </div>
                            <small class="text-muted">68% dari target mingguan</small>
                        </div>

                        {{-- Goals Progress --}}
                        <div class="mb-4 weekly-stat-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-target text-danger fs-5 me-2 stat-icon-danger"></i>
                                <span class="fw-semibold">Goals Progress</span>
                            </div>
                            <h6 class="fw-bold text-danger">80%</h6>
                            <div class="progress mb-1" style="height: 6px;">
                                <div class="progress-bar bg-danger" style="width: 80%"></div>
                            </div>
                            <small class="text-muted">Tercapai 8/10 target</small>
                        </div>

                        {{-- Most Productive Day --}}
                        <div class="mb-2 weekly-stat-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-sun text-info fs-5 me-2 stat-icon"></i>
                                <span class="fw-semibold">Most Productive Day</span>
                            </div>
                            <h6 class="fw-bold text-info">Minggu</h6>
                            <small class="text-muted">5 aktivitas tercatat</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Target Progress Overview --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="ti ti-target me-2 text-warning"></i>
                            Target Progress
                        </h5>
                        <a href="/progress" class="btn btn-outline-warning btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold">Baca 5 buku sebulan</span>
                                <span class="text-muted">20%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" style="width: 20%"></div>
                            </div>
                            <small class="text-muted">Deadline: 31 Dec 2025</small>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold">Lari 20 km per minggu</span>
                                <span class="text-muted">65%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: 65%"></div>
                            </div>
                            <small class="text-muted">Deadline: 31 Dec 2025</small>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold">Mytic Imortal Bintang 200</span>
                                <span class="text-muted">95%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: 95%"></div>
                            </div>
                            <small class="text-muted">Deadline: 1 Dec 2025</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="ti ti-file-text me-2 text-info"></i>
                            Recent Logs
                        </h5>
                        <a href="/logs" class="btn btn-outline-info btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-0">Membaca Novel "Dune"</h6>
                                    <small class="text-muted">2 jam lalu • 2 jam durasi</small>
                                </div>
                            </div>
                            <div class="list-group-item px-0 d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-0">Bersepeda pagi</h6>
                                    <small class="text-muted">5 jam lalu • 1 jam durasi</small>
                                </div>
                            </div>
                            <div class="list-group-item px-0 d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-0">Latihan Piano</h6>
                                    <small class="text-muted">1 hari lalu • 30 menit durasi</small>
                                </div>
                            </div>
                            <div class="list-group-item px-0 d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-0">Bermain Clash Royale</h6>
                                    <small class="text-muted">2 hari lalu • 50 menit durasi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reports & Export --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                <h5 class="fw-bold mb-0">
                    <i class="ti ti-chart-bar me-2 text-primary"></i>
                    Reports & Export
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" id="export-excel">
                        <i class="ti ti-file-spreadsheet me-1"></i>
                        Export Excel
                    </button>
                    <button class="btn btn-success btn-sm" id="export-pdf">
                        <i class="ti ti-file-pdf me-1"></i>
                        Export PDF
                    </button>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">Fitur ekspor akan tersedia setelah implementasi backend lengkap.</p>
            </div>
        </div>
    </div>
@endsection
