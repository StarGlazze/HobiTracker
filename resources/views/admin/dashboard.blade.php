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
                <div class="card border-0 shadow-sm bg-success bg-gradient text-white h-100">
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
                <div class="card border-0 shadow-sm bg-warning bg-gradient text-white h-100">
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
                <div class="card border-0 shadow-sm bg-danger bg-gradient text-white h-100">
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
                        <div id="sales-overview" class="mt-4 mx-n6" data-chart='@json($chartData ?? [])'></div>

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
                            <h6 class="fw-bold text-primary">{{ $weeklyStats['topHobby'] ?? 'Belum ada' }}</h6>
                            <div class="progress mb-1" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: {{ $weeklyStats['topHobbyCount'] ?? 0 }}%"></div>
                            </div>
                            <small class="text-muted">{{ $weeklyStats['topHobbyCount'] ?? 0 }} aktivitas minggu ini</small>
                        </div>

                        {{-- Consistency Streak --}}
                        <div class="mb-4 weekly-stat-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-calendar text-success fs-5 me-2 stat-icon-success"></i>
                                <span class="fw-semibold">Consistency Streak</span>
                            </div>
                            <h6 class="fw-bold text-success">{{ $weeklyStats['consistencyStreak'] ?? 0 }} Hari</h6>
                            <div class="progress mb-1" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: {{ min(($weeklyStats['consistencyStreak'] ?? 0) / 20 * 100, 100) }}%"></div>
                            </div>
                            <small class="text-muted">Target 20 hari streak</small>
                        </div>



                        {{-- Goals Progress --}}
                        <div class="mb-4 weekly-stat-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-target text-danger fs-5 me-2 stat-icon-danger"></i>
                                <span class="fw-semibold">Goals Progress</span>
                            </div>
                            <h6 class="fw-bold text-danger">{{ $weeklyStats['goalsProgress'] ?? 0 }}%</h6>
                            <div class="progress mb-1" style="height: 6px;">
                                <div class="progress-bar bg-danger" style="width: {{ $weeklyStats['goalsProgress'] ?? 0 }}%"></div>
                            </div>
                            <small class="text-muted">Progress rata-rata target</small>
                        </div>

                        {{-- Total Activities This Week --}}
                        <div class="mb-2 weekly-stat-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-activity text-warning fs-5 me-2 stat-icon"></i>
                                <span class="fw-semibold">Total Activities This Week</span>
                            </div>
                            <h6 class="fw-bold text-warning">{{ $weeklyStats['totalActivitiesThisWeek'] ?? 0 }}</h6>
                            <small class="text-muted">Aktivitas minggu ini</small>
                        </div>

                        {{-- Most Productive Day --}}
                        <div class="mb-2 weekly-stat-item">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-sun text-info fs-5 me-2 stat-icon"></i>
                                <span class="fw-semibold">Most Productive Day</span>
                            </div>
                            <h6 class="fw-bold text-info">{{ $weeklyStats['mostProductiveDay'] ?? 'Belum ada' }}</h6>
                            <small class="text-muted">{{ $weeklyStats['mostProductiveCount'] ?? 0 }} aktivitas tercatat</small>
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
                        @forelse($targetProgress ?? [] as $target)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold">{{ $target['nama_target'] }}</span>
                                <span class="text-muted">{{ $target['progress'] }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $target['progress'] >= 80 ? 'success' : ($target['progress'] >= 50 ? 'warning' : 'danger') }}" style="width: {{ $target['progress'] }}%"></div>
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
