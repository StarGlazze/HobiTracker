@extends('admin.layouts.app')

@section('title', 'Dashboard - HobiTracker')

@section('styles')
    <link rel="stylesheet" href="{{ asset('./admin/css/dashboard.css') }}" />
@endsection

@section('content')
    <div class="container-fluid" style="padding-top: 20px">
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">
                            <i class="ti ti-dashboard text-primary me-2"></i>Dashboard HobiTracker
                        </h3>
                        <small
                            class="text-muted mb-2">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY | HH:mm') }}</small>
                        <p class="text-muted mb-0">
                            Pantau dan kelola hobi serta aktivitas Anda dengan mudah dan menyenangkan.
                        </p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-primary d-flex align-items-center" type="button" id="shareDashboardBtn"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-share me-2"></i>Bagikan
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="shareDashboardBtn">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="#" id="share-twitter">
                                    <i class="ti ti-brand-twitter me-2 text-info"></i>
                                    <span>Twitter</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="#" id="share-facebook">
                                    <i class="ti ti-brand-facebook me-2 text-primary"></i>
                                    <span>Facebook</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="#" id="share-whatsapp">
                                    <i class="ti ti-brand-whatsapp me-2 text-success"></i>
                                    <span>WhatsApp</span>
                                </a>
                            </li>
                            {{-- PENAMBAHAN FITUR SHARE INSTAGRAM --}}
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="#" id="share-instagram">
                                    <i class="ti ti-brand-instagram me-2" style="color: #E4405F;"></i>
                                    <span>Instagram</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="#" id="share-copy-link">
                                    <i class="ti ti-link me-2 text-muted"></i>
                                    <span>Copy Link</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4 g-3 g-md-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-heart me-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="text-white-50 mb-0">Total Hobbies</h6>
                                </div>
                                <h2 class="fw-bold mb-1">{{ $totalHobbies ?? 0 }}</h2>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-heart" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-trending-up me-2"></i>
                                <small class="text-white-75">{{ $activeHobbiesThisMonth ?? 0 }} aktif bulan ini</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-activity me-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="text-white-50 mb-0">Total Activities</h6>
                                </div>
                                <h2 class="fw-bold mb-1">{{ $totalActivities ?? 0 }}</h2>
                                <small class="text-white-75">Total aktivitas</small>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-activity" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="progress bg-white-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: {{ $activitiesProgress ?? 0 }}%"></div>
                            </div>
                            <small class="text-white-75 mt-2 d-block">{{ $activitiesProgress ?? 0 }}% dari target</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-target me-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="text-white-50 mb-0">Active Targets</h6>
                                </div>
                                <h2 class="fw-bold mb-1">{{ $activeTargets ?? 0 }}</h2>
                                <small class="text-white-75">Target aktif</small>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-target" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-star me-2"></i>
                                <small class="text-white-75">{{ $activeTargets ?? 0 }} target aktif</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-danger text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-trending-up me-2" style="font-size: 1.5rem;"></i>
                                    <h6 class="text-white-50 mb-0">Progress Rate</h6>
                                </div>
                                <h2 class="fw-bold mb-1">{{ $progressRate ?? 0 }}%</h2>
                                <small class="text-white-75">Rata-rata progress</small>
                            </div>
                            <div class="text-white-25">
                                <i class="ti ti-trending-up" style="font-size: 3rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white-25">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-chart-line me-2"></i>
                                <small class="text-white-75">{{ $progressRate ?? 0 }}% rata-rata</small>
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
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                            <div>
                                <h4 class="card-title fw-bold mb-1 text-dark">
                                    <i class="ti ti-chart-line text-primary me-2"></i>
                                    Activity Overview
                                </h4>
                                <small class="text-muted">Visualisasi aktivitas hobi berdasarkan kategori</small>
                            </div>
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Chart type selector">
                                    <button type="button" class="btn btn-outline-primary active"
                                        onclick="updateChartType('area')" id="chart-area" title="Area Chart"
                                        data-bs-toggle="tooltip">
                                        <i class="ti ti-chart-area-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary"
                                        onclick="updateChartType('bar')" id="chart-bar" title="Bar Chart"
                                        data-bs-toggle="tooltip">
                                        <i class="ti ti-chart-bar"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary"
                                        onclick="updateChartType('line')" id="chart-line" title="Line Chart"
                                        data-bs-toggle="tooltip">
                                        <i class="ti ti-chart-line"></i>
                                    </button>
                                </div>

                                <select class="form-select form-select-sm" id="period-selector"
                                    style="width: auto; min-width: 130px;" aria-label="Period selector">
                                    <option value="Bulanan" selected>Bulanan</option>
                                    <option value="Mingguan">Mingguan</option>
                                    <option value="Harian">Harian</option>
                                </select>
                            </div>
                        </div>

                        <div class="chart-container position-relative" style="width: 100%; min-height: 350px;">
                            <div id="sales-overview" class="mt-3" data-chart='@json($chartData ?? ['categories' => ['Belum ada data'], 'series' => [0]])'></div>
                        </div>

                        <div class="row g-3 mt-3 pt-3 border-top">
                            <div class="col-6 col-md-3">
                                <div class="text-center">
                                    <h6 class="mb-1 text-muted small">Total Kategori</h6>
                                    <h4 class="fw-bold text-primary mb-0">{{ count($chartData['categories'] ?? []) }}</h4>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center">
                                    <h6 class="mb-1 text-muted small">Total Aktivitas</h6>
                                    <h4 class="fw-bold text-success mb-0">{{ array_sum($chartData['series'] ?? [0]) }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center">
                                    <h6 class="mb-1 text-muted small">Rata-rata</h6>
                                    <h4 class="fw-bold text-info mb-0">
                                        {{ count($chartData['series'] ?? [1]) > 0 ? round(array_sum($chartData['series'] ?? [0]) / count($chartData['series'] ?? [1]), 1) : 0 }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center">
                                    <h6 class="mb-1 text-muted small">Tertinggi</h6>
                                    <h4 class="fw-bold text-warning mb-0">{{ max($chartData['series'] ?? [0]) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Weekly Stats dengan styling yang diperbaiki --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0" style="max-height: 560px; overflow-y: auto;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="ti ti-calendar-stats text-primary me-2"></i>
                            Weekly Stats
                        </h5>

                        {{-- Top Hobby --}}
                        <div class="card mb-3 border-0 bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="ti ti-trophy text-primary fs-5"></i>
                                    </div>
                                    <span class="fw-semibold text-dark">Top Hobby</span>
                                </div>
                                <h5 class="fw-bold text-primary mb-1">{{ $weeklyStats['topHobby'] ?? 'Belum ada' }}</h5>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-primary"
                                        style="width: {{ min(($weeklyStats['topHobbyCount'] ?? 0) * 10, 100) }}%;"></div>
                                </div>
                                <small class="text-muted">
                                    <i class="ti ti-activity me-1"></i>
                                    {{ $weeklyStats['topHobbyCount'] ?? 0 }} aktivitas minggu ini
                                </small>
                            </div>
                        </div>

                        {{-- Consistency Streak --}}
                        <div class="card mb-3 border-0 bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="ti ti-flame text-success fs-5"></i>
                                    </div>
                                    <span class="fw-semibold text-dark">Consistency Streak</span>
                                </div>
                                <h5 class="fw-bold text-success mb-1">🔥 {{ $weeklyStats['consistencyStreak'] ?? 0 }} Hari
                                </h5>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-success"
                                        style="width: {{ min((($weeklyStats['consistencyStreak'] ?? 0) / 20) * 100, 100) }}%;">
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="ti ti-target me-1"></i>
                                    Target 20 hari streak
                                </small>
                            </div>
                        </div>

                        {{-- Goals Progress --}}
                        <div class="card mb-3 border-0 bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="ti ti-target text-danger fs-5"></i>
                                    </div>
                                    <span class="fw-semibold text-dark">Goals Progress</span>
                                </div>
                                <h5 class="fw-bold text-danger mb-1">{{ $weeklyStats['goalsProgress'] ?? 0 }}%</h5>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-danger"
                                        style="width: {{ $weeklyStats['goalsProgress'] ?? 0 }}%;"></div>
                                </div>
                                <small class="text-muted">
                                    <i class="ti ti-chart-pie me-1"></i>
                                    Progress rata-rata target
                                </small>
                            </div>
                        </div>

                        {{-- Total Activities This Week --}}
                        <div class="card mb-3 border-0 bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="ti ti-activity text-info fs-5"></i>
                                    </div>
                                    <span class="fw-semibold text-dark">Total Activities</span>
                                </div>
                                <h5 class="fw-bold text-info mb-1">{{ $weeklyStats['totalActivitiesThisWeek'] ?? 0 }}</h5>
                                <small class="text-muted">
                                    <i class="ti ti-clock me-1"></i>
                                    Aktivitas minggu ini
                                </small>
                            </div>
                        </div>

                        {{-- Most Productive Day --}}
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="ti ti-sun text-warning fs-5"></i>
                                    </div>
                                    <span class="fw-semibold text-dark">Most Productive Day</span>
                                </div>
                                <h5 class="fw-bold text-warning mb-1">
                                    {{ $weeklyStats['mostProductiveDay'] ?? 'Belum ada' }}
                                </h5>
                                <small class="text-muted">
                                    <i class="ti ti-check me-1"></i>
                                    {{ $weeklyStats['mostProductiveCount'] ?? 0 }} aktivitas tercatat
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Target Progress Overview --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="ti ti-target me-2 text-warning"></i>
                            Target Progress
                        </h5>
                        <a href="/admin/target" class="btn btn-outline-warning btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        @forelse($targetProgress ?? [] as $target)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold">{{ $target['nama_target'] }}</span>
                                    <span class="text-muted">{{ $target['progress'] }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $target['progress'] >= 80 ? 'success' : ($target['progress'] >= 50 ? 'warning' : 'danger') }}"
                                        style="width: {{ $target['progress'] }}%"></div>
                                </div>
                                <small class="text-muted">Deadline: {{ $target['deadline'] }}</small>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="ti ti-target text-muted mb-2" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0">Belum ada target</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="ti ti-file-text me-2 text-info"></i>
                            Recent Logs
                        </h5>
                        <a href="/logs" class="btn btn-outline-info btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @forelse($recentLogs ?? [] as $log)
                                <div class="list-group-item px-0 d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-semibold mb-0">{{ $log['nama_aktivitas'] }}</h6>
                                        <small class="text-muted">{{ $log['waktu'] }} • Mood: {{ $log['mood'] }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="ti ti-notes text-muted mb-2" style="font-size: 2rem;"></i>
                                    <p class="text-muted mb-0">Belum ada log aktivitas</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('./admin/js/dashboard.js') }}"></script>
@endsection
