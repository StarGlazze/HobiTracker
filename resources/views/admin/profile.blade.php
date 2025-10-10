@extends('admin.layouts.app')

@section('title', 'Profil - HobiTracker')

@section('styles')
    <link rel="stylesheet" href="{{ asset('admin/css/achievement.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/profile-extracted.css') }}">
@endsection

@section('content')

    <div class="container-fluid py-4">
        <div class="row">
            {{-- Sidebar Profile --}}
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        @if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil))
                            <img src="{{ asset('storage/' . $user->foto_profil) }}" class="rounded-circle mb-3"
                                alt="Foto Profil" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="/admin/images/profile/user-1.jpg" class="rounded-circle mb-3" alt="Foto Profil"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        @endif

                        <h4 class="fw-bold text-dark">{{ $user->name }}</h4>
                        <p class="text-muted mb-1">{{ $user->bio ?? 'Belum ada bio' }}</p>
                        <span class="badge bg-success">Aktif</span>

                        <hr>

                        <h6 class="fw-bold text-dark mb-3">
                            <i class="ti ti-trophy me-2 text-warning"></i>Pencapaian Hobi
                        </h6>

                        <div class="achievements-grid">
                            @foreach ([
                                'early_bird' => ['icon' => 'ti-sun', 'title' => 'Early Bird', 'desc' => 'Login sebelum jam 7 pagi 10 kali'],
                                'night_owl' => ['icon' => 'ti-moon', 'title' => 'Night Owl', 'desc' => 'Aktivitas setelah jam 10 malam 5 kali'],
                                'explorer' => ['icon' => 'ti-compass', 'title' => 'Explorer', 'desc' => '5 kategori hobi berbeda'],
                                'consistency' => ['icon' => 'ti-calendar-event', 'title' => 'Consistency King', 'desc' => 'Login 30 hari berturut'],
                                'goal_crusher' => ['icon' => 'ti-target', 'title' => 'Goal Crusher', 'desc' => 'Selesaikan 20 target'],
                                'storyteller' => ['icon' => 'ti-book', 'title' => 'Storyteller', 'desc' => 'Deskripsi >200 karakter'],
                                'collector' => ['icon' => 'ti-photo', 'title' => 'Collector', 'desc' => 'Upload 50 file bukti'],
                                'speedrunner' => ['icon' => 'ti-bolt', 'title' => 'Speedrunner', 'desc' => 'Selesaikan target < 24 jam 5 kali'],
                                'creative_spark' => ['icon' => 'ti-bulb', 'title' => 'Creative Spark', 'desc' => 'Buat 10 hobi berbeda'],
                                'milestone_master' => ['icon' => 'ti-trophy', 'title' => 'Milestone Master', 'desc' => 'Selesaikan 100 aktivitas'],
                            ] as $key => $achievement)
                                <div class="achievement-item {{ $achievements[$key] ? 'achieved' : 'locked' }}">
                                    <span class="achievement-badge {{ $achievements[$key] ? '' : 'locked' }}">
                                        {{ $achievements[$key] ? 'Tercapai!' : 'Terkunci' }}
                                    </span>
                                    <i class="ti {{ $achievement['icon'] }}"></i>
                                    <div class="achievement-title">{{ $achievement['title'] }}</div>
                                    <div class="achievement-desc">{{ $achievement['desc'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                                    type="button" role="tab">
                                    <i class="ti ti-user me-1"></i> Info
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password"
                                    type="button" role="tab">
                                    <i class="ti ti-lock me-1"></i> Ganti Password
                                </button>
                            </li>
                        </ul>

                        <button class="edit-profile-btn" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="ti ti-edit me-2"></i>Edit Profil
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="profileTabsContent">
                            {{-- Info Tab --}}
                            <div class="tab-pane fade show active" id="info" role="tabpanel"
                                aria-labelledby="info-tab">
                                <h5 class="fw-bold mb-3">Informasi Profil</h5>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-semibold">
                                        <i class="ti ti-user me-2 text-primary"></i>Nama Lengkap
                                    </div>
                                    <div class="col-sm-8">{{ $user->name }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-semibold">
                                        <i class="ti ti-mail me-2 text-info"></i>Email
                                    </div>
                                    <div class="col-sm-8">{{ $user->email }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-semibold">
                                        <i class="bi bi-people me-2 text-warning"></i>Pekerjaan
                                    </div>
                                    <div class="col-sm-8">{{ $user->pekerjaan ?? 'Belum diisi' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-semibold">
                                        <i class="ti ti-calendar-event me-2 text-success"></i>Umur
                                    </div>
                                    <div class="col-sm-8">{{ $user->umur ? $user->umur . ' tahun' : 'Belum diisi' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-semibold">
                                        <i class="ti ti-heart me-2 text-danger"></i>Hobi Utama
                                    </div>
                                    <div class="col-sm-8">{{ $user->hobi_utama ?? 'Belum diisi' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 fw-semibold">
                                        <i class="ti ti-file-text me-2 text-secondary"></i>Bio
                                    </div>
                                    <div class="col-sm-8">{{ $user->bio ?? 'Belum diisi' }}</div>
                                </div>
                            </div>

                            {{-- Password Tab --}}
                            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                <h5 class="fw-bold mb-3">Ganti Password</h5>
                                <form>
                                    <div class="mb-3">
                                        <label for="passwordLama" class="form-label">Password Lama</label>
                                        <input type="password" class="form-control" id="passwordLama" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="passwordBaru" class="form-label">Password Baru</label>
                                        <input type="password" class="form-control" id="passwordBaru" required
                                            minlength="6">
                                        <div class="form-text">Minimal 6 karakter</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="passwordKonfirmasi" class="form-label">Konfirmasi Password
                                            Baru</label>
                                        <input type="password" class="form-control" id="passwordKonfirmasi" required
                                            minlength="6">
                                    </div>
                                    <button type="submit" class="btn btn-success">Perbarui Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white">
                        <h5 class="fw-bold mb-0">
                            <i class="ti ti-clock me-2 text-primary"></i>Aktivitas Terbaru
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if ($recentActivities->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach ($recentActivities as $activity)
                                    <li class="list-group-item">
                                        <div class="d-flex flex-column flex-md-row justify-content-between">
                                            <!-- Main Content -->
                                            <div class="flex-grow-1 mb-2 mb-md-0">
                                                <div class="d-flex align-items-start">
                                                    <i class="ti ti-check text-success me-2 mt-1"
                                                        style="font-size: 1.2rem;"></i>
                                                    <div class="flex-grow-1">
                                                        <strong class="d-block mb-1">
                                                            {{ $activity->aktivitas->nama_aktivitas ?? 'Melakukan aktivitas hobi' }}
                                                        </strong>

                                                        @if ($activity->target)
                                                            <div class="mb-2">
                                                                <span class="badge bg-primary me-1 mb-1">
                                                                    <i class="ti ti-target"
                                                                        style="font-size: 0.75rem;"></i>
                                                                    {{ Str::limit($activity->target->nama_target, 20) }}
                                                                </span>
                                                                <span class="badge bg-info mb-1">
                                                                    <i class="ti ti-heart"
                                                                        style="font-size: 0.75rem;"></i>
                                                                    {{ Str::limit($activity->target->hobi->nama_hobi, 20) }}
                                                                </span>
                                                            </div>
                                                        @endif

                                                        @if ($activity->aktivitas->catatan)
                                                            <div class="text-muted small mt-1 mb-2"
                                                                style="line-height: 1.4;">
                                                                {{ Str::limit($activity->aktivitas->catatan, 100) }}
                                                            </div>
                                                        @endif

                                                        @php
                                                            $rawFileData = $activity->aktivitas->file_bukti ?? [];
                                                            $fileData = [];

                                                            if (is_string($rawFileData) && !empty($rawFileData)) {
                                                                $decoded = json_decode($rawFileData, true);
                                                                if (
                                                                    json_last_error() === JSON_ERROR_NONE &&
                                                                    is_array($decoded)
                                                                ) {
                                                                    $fileData = $decoded;
                                                                } else {
                                                                    if (
                                                                        str_contains($rawFileData, 'drive.google.com')
                                                                    ) {
                                                                        $fileData = ['gdrive' => $rawFileData];
                                                                    } else {
                                                                        $fileData = ['file' => $rawFileData];
                                                                    }
                                                                }
                                                            } elseif (is_array($rawFileData)) {
                                                                $fileData = $rawFileData;
                                                            }

                                                            $files = [];
                                                            $gdriveLinks = [];

                                                            foreach ($fileData as $key => $value) {
                                                                if ($key === 'gdrive') {
                                                                    $gdriveLinks[] = $value;
                                                                } elseif ($key === 'file') {
                                                                    $files[] = $value;
                                                                } elseif (is_string($value)) {
                                                                    $files[] = $value;
                                                                }
                                                            }
                                                        @endphp

                                                        @if (!empty($files) || !empty($gdriveLinks))
                                                            <div class="file-bukti-container p-2 rounded bg-light mt-2">
                                                                <small class="text-muted d-block mb-2">
                                                                    <i class="ti ti-paperclip me-1"></i>Bukti Aktivitas
                                                                </small>
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach ($files as $file)
                                                                        @php
                                                                            $extension = strtolower(
                                                                                pathinfo($file, PATHINFO_EXTENSION),
                                                                            );
                                                                            $imageExts = [
                                                                                'jpg',
                                                                                'jpeg',
                                                                                'png',
                                                                                'gif',
                                                                                'webp',
                                                                            ];
                                                                            $videoExts = ['mp4', 'mov', 'avi', 'webm'];
                                                                            $fileType = 'file';
                                                                            $icon = 'ti-file-text';
                                                                            $title = 'Lihat file bukti';

                                                                            if (in_array($extension, $imageExts)) {
                                                                                $fileType = 'image';
                                                                                $icon = 'ti-photo';
                                                                                $title = 'Lihat gambar bukti';
                                                                            } elseif (
                                                                                in_array($extension, $videoExts)
                                                                            ) {
                                                                                $fileType = 'video';
                                                                                $icon = 'ti-video';
                                                                                $title = 'Lihat video bukti';
                                                                            }
                                                                        @endphp
                                                                        <div class="file-bukti-item position-relative"
                                                                            style="width: 60px; height: 60px; cursor: pointer; overflow: hidden; border-radius: 0.5rem; border: 2px solid #e9ecef;"
                                                                            onclick="openFileModal('{{ asset('storage/' . $file) }}', '{{ $fileType }}', '{{ $activity->aktivitas->nama_aktivitas }}')"
                                                                            title="{{ $title }}">
                                                                            @if ($fileType === 'image')
                                                                                <img src="{{ asset('storage/' . $file) }}"
                                                                                    alt="Bukti" class="w-100 h-100"
                                                                                    style="object-fit: cover;">
                                                                            @elseif($fileType === 'video')
                                                                                <video class="w-100 h-100"
                                                                                    style="object-fit: cover;" muted>
                                                                                    <source
                                                                                        src="{{ asset('storage/' . $file) }}"
                                                                                        type="video/{{ $extension === 'mov' ? 'quicktime' : ($extension === 'avi' ? 'avi' : 'mp4') }}">
                                                                                </video>
                                                                                <div
                                                                                    class="position-absolute top-50 start-50 translate-middle">
                                                                                    <i class="ti ti-player-play-filled text-white"
                                                                                        style="font-size: 1.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.5);"></i>
                                                                                </div>
                                                                            @else
                                                                                <div
                                                                                    class="d-flex align-items-center justify-content-center h-100 bg-white">
                                                                                    <i class="ti {{ $icon }} text-muted"
                                                                                        style="font-size: 1.5rem;"></i>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach

                                                                    @foreach ($gdriveLinks as $gdrive)
                                                                        <div class="file-bukti-item"
                                                                            style="width: 60px; height: 60px; cursor: pointer; border-radius: 0.5rem; border: 2px solid #e9ecef; background-color: #fff;"
                                                                            onclick="openFileModal('{{ $gdrive }}', 'gdrive', '{{ $activity->aktivitas->nama_aktivitas }}')"
                                                                            title="Lihat file bukti Google Drive">
                                                                            <div
                                                                                class="d-flex align-items-center justify-content-center h-100">
                                                                                <i class="ti ti-brand-google-drive text-info"
                                                                                    style="font-size: 2rem;"></i>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Timestamp -->
                                            <div class="text-end text-md-end mt-2 mt-md-0 ms-md-3">
                                                <small class="text-muted d-block"
                                                    title="{{ $activity->created_at->format('d M Y H:i:s') }}"
                                                    style="white-space: nowrap;">
                                                    <i class="ti ti-clock" style="font-size: 0.75rem;"></i>
                                                    {{ $activity->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-5">
                                <i class="ti ti-activity text-muted mb-3" style="font-size: 3rem;"></i>
                                <p class="text-muted mb-0">Belum ada aktivitas terbaru</p>
                            </div>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </div>

    {{-- Edit Profile Modal --}}
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold" id="editProfileModalLabel">
                        <i class="ti ti-edit me-2"></i>Edit Profil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="editProfileForm">
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editPekerjaan" class="form-label fw-semibold">
                                        <i class="bi bi-people me-2 text-warning"></i>Pekerjaan
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="editPekerjaan"
                                        name="pekerjaan" value="{{ $user->pekerjaan }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editUmur" class="form-label fw-semibold">
                                        <i class="ti ti-calendar-event me-2 text-success"></i>Umur
                                    </label>
                                    <input type="number" class="form-control form-control-lg" id="editUmur"
                                        name="umur" value="{{ $user->umur }}" min="10" max="100">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editHobi" class="form-label fw-semibold">
                                <i class="ti ti-heart me-2 text-danger"></i>Hobi Utama
                            </label>
                            <textarea class="form-control form-control-lg" id="editHobi" name="hobi_utama" rows="3"
                                placeholder="Masukkan hobi Anda, pisahkan dengan koma">{{ $user->hobi_utama }}</textarea>
                            <div class="form-text">Pisahkan hobi dengan koma (,)</div>
                        </div>

                        <div class="mb-3">
                            <label for="editBio" class="form-label fw-semibold">
                                <i class="ti ti-file-text me-2 text-secondary"></i>Bio Singkat
                            </label>
                            <textarea class="form-control form-control-lg" id="editBio" name="bio" rows="3"
                                placeholder="Ceritakan sedikit tentang diri Anda">{{ $user->bio }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="ti ti-photo me-2 text-primary"></i>Foto Profil
                            </label>
                            <input type="file" class="form-control" id="editFoto" name="foto_profil"
                                accept="image/*">
                            <div class="form-text">Format: JPG, PNG, GIF. Maksimal 5MB</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">
                            <i class="ti ti-x me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="ti ti-device-floppy me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <i class="ti ti-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="fw-bold text-success mb-2">Berhasil!</h5>
                    <p class="text-muted mb-0">Profil Anda telah berhasil diperbarui.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-success rounded-3 px-4" data-bs-dismiss="modal">
                        <i class="ti ti-check me-2"></i>OK
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('./admin/js/profile.js') }}"></script>
    <script src="{{ asset('./admin/js/target.js') }}"></script>
@endsection
