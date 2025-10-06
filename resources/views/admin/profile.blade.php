@extends('admin.layouts.app')

@section('title', 'Profil - HobiTracker')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/achievement.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/profile.css') }}">
@endsection

@section('content')

<div class="container-fluid py-4">
    <div class="row">
        {{-- Sidebar Profile --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    @if($user->foto_profil && Storage::disk('public')->exists($user->foto_profil))
                        <img src="{{ asset('storage/' . $user->foto_profil) }}" class="rounded-circle mb-3" alt="Foto Profil"
                            style="width: 150px; height: 150px; object-fit: cover;">
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
                        {{-- Consistency King/Queen --}}
                        <div class="achievement-item {{ $achievements['consistency'] ? 'achieved' : 'locked' }}">
                            @if($achievements['consistency'])
                                <span class="achievement-badge">Tercapai!</span>
                            @else
                                <span class="achievement-badge locked">Terkunci</span>
                            @endif
                            <i class="ti ti-calendar-event"></i>
                            <div class="achievement-title">Consistency King</div>
                            <div class="achievement-desc">Login 30 hari berturut</div>
                        </div>

                        {{-- Explorer --}}
                        <div class="achievement-item {{ $achievements['explorer'] ? 'achieved' : 'locked' }}">
                            @if($achievements['explorer'])
                                <span class="achievement-badge">Tercapai!</span>
                            @else
                                <span class="achievement-badge locked">Terkunci</span>
                            @endif
                            <i class="ti ti-compass"></i>
                            <div class="achievement-title">Explorer</div>
                            <div class="achievement-desc">5 hobi berbeda</div>
                        </div>

                        {{-- Master of Hobby --}}
                        <div class="achievement-item {{ $achievements['master'] ? 'achieved' : 'locked' }}">
                            @if($achievements['master'])
                                <span class="achievement-badge">Tercapai!</span>
                            @else
                                <span class="achievement-badge locked">Terkunci</span>
                            @endif
                            <i class="ti ti-star"></i>
                            <div class="achievement-title">Master of Hobby</div>
                            <div class="achievement-desc">10 target selesai</div>
                        </div>

                        {{-- Collector --}}
                        <div class="achievement-item {{ $achievements['collector'] ? 'achieved' : 'locked' }}">
                            @if($achievements['collector'])
                                <span class="achievement-badge">Tercapai!</span>
                            @else
                                <span class="achievement-badge locked">Terkunci</span>
                            @endif
                            <i class="ti ti-photo"></i>
                            <div class="achievement-title">Collector</div>
                            <div class="achievement-desc">20 file bukti</div>
                        </div>
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
                        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
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
                                    <input type="password" class="form-control" id="passwordBaru" required minlength="6">
                                    <div class="form-text">Minimal 6 karakter</div>
                                </div>
                                <div class="mb-3">
                                    <label for="passwordKonfirmasi" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" id="passwordKonfirmasi" required minlength="6">
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
                    <h5 class="fw-bold mb-0"><i class="ti ti-clock me-2 text-primary"></i>Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    @if($recentActivities->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentActivities as $activity)
                                <li class="list-group-item">
                                    <i class="ti ti-check text-success me-2"></i>
                                    {{ $activity->catatan ?? 'Melakukan aktivitas hobi' }}
                                    @if($activity->hobi)
                                        - <b>{{ $activity->hobi->nama_hobi }}</b>
                                    @endif
                                    <span class="text-muted small float-end">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted text-center py-3">Belum ada aktivitas terbaru</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Profile Modal --}}
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="editProfileModalLabel">
                    <i class="ti ti-edit me-2"></i>Edit Profil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProfileForm">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editPekerjaan" class="form-label fw-semibold">
                                    <i class="bi bi-people me-2 text-warning"></i>Pekerjaan
                                </label>
                                <input type="text" class="form-control form-control-lg" id="editPekerjaan" name="pekerjaan"
                                    value="{{ $user->pekerjaan }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editUmur" class="form-label fw-semibold">
                                    <i class="ti ti-calendar-event me-2 text-success"></i>Umur
                                </label>
                                <input type="number" class="form-control form-control-lg" id="editUmur" name="umur"
                                    value="{{ $user->umur }}" min="10" max="100">
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
                        <input type="file" class="form-control" id="editFoto" name="foto_profil" accept="image/*">
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
@endsection