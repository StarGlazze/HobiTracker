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
                                <th scope="col" class="border-0 py-3" style="width: 15%;">Status</th>
                                <th scope="col" class="border-0 py-3 text-center" style="width: 5%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($targets as $index => $target)
                                <tr class="border-bottom">
                                    @php
                                        $latestProgress = $target->progresTarget->sortByDesc('created_at')->first();
                                        $isExpired = $target->target_deadline < now()->startOfDay();
                                    @endphp
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="py-3">{{ $target->nama_target }}</td>
                                    <td class="py-3">{{ $target->hobi->nama_hobi ?? 'N/A' }}</td>
                                    <td class="py-3">
                                        <span class="badge {{ $target->hobi->kategoriHobi->background_color ?? 'bg-info-subtle' }} {{ $target->hobi->kategoriHobi->background_color ? 'text-white' : 'text-info' }} px-3 py-2">
                                            <i
                                                class="ti {{ $target->hobi->kategoriHobi->icon ?? 'ti-tag' }} me-1"></i>{{ $target->hobi->kategoriHobi->nama_kategori ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        {{ \Carbon\Carbon::parse($target->target_deadline)->format('d F Y') }}</td>
                                    <td class="py-3 text-center">
                                        @if ($latestProgress)
                                            @if ($latestProgress->status === 'completed')
                                                <span class="badge bg-success-subtle text-success px-3 py-2">
                                                    <i class="ti ti-check-circle me-1"></i>Completed
                                                </span>
                                            @elseif($latestProgress->status === 'failed' || $isExpired)
                                                <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                                    <i class="ti ti-x-circle me-1"></i>Failed
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                                    <i class="ti ti-clock me-1"></i>On Progress
                                                </span>
                                            @endif
                                        @else
                                            @if ($isExpired)
                                                <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                                    <i class="ti ti-x-circle me-1"></i>Expired
                                                </span>
                                            @else
                                                <span class="badge bg-info-subtle text-info px-3 py-2">
                                                    <i class="ti ti-plus me-1"></i>No Progress
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editTargetModal{{ $target->id }}" title="Edit Target">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            <form action="{{ route('admin.target.destroy', ['target' => $target->id]) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" title="Hapus Target"
                                                    onclick="confirmDelete(this)">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#progressModal{{ $target->id }}" title="Lihat Progress" {{ $isExpired ? 'disabled' : '' }}>
                                                <i class="ti ti-chart-bar"></i>
                                            </button>
                                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $target->id }}" title="Lihat Detail">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="mb-4">
                                            <i class="ti ti-target text-muted" style="font-size: 4rem;"></i>
                                        </div>
                                        <h5 class="text-muted">Belum ada Target Hobi</h5>
                                        <p class="text-muted mb-4">Mulai dengan menambahkan target hobi pertama Anda</p>
                                    </td>
                                </tr>
                            @endforelse
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
                    <form action="{{ route('admin.target.store') }}" method="POST">
                        @csrf
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
                                <label for="hobi_id" class="form-label fw-semibold">
                                    <i class="ti ti-heart text-danger me-2"></i>Hobi
                                </label>
                                <select class="form-select" id="hobi_id" name="hobi_id" required>
                                    <option value="">Pilih Hobi</option>
                                    @foreach ($hobis as $hobi)
                                        <option value="{{ $hobi->id }}">{{ $hobi->nama_hobi }}
                                            ({{ $hobi->kategoriHobi->nama_kategori ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="nama_target" class="form-label fw-semibold">
                                    <i class="ti ti-target text-primary me-2"></i>Nama Target
                                </label>
                                <input type="text" class="form-control" id="nama_target" name="nama_target"
                                    placeholder="Masukkan nama target" required>
                            </div>

                            <div class="mb-3">
                                <label for="target_deadline" class="form-label fw-semibold">
                                    <i class="ti ti-calendar text-success me-2"></i>Batas Waktu
                                </label>
                                <input type="date" class="form-control" id="target_deadline" name="target_deadline"
                                    required>
                                @error('target_deadline')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
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

        <!-- Edit Target Modals -->
        @foreach ($targets as $target)
            <div class="modal fade" id="editTargetModal{{ $target->id }}" tabindex="-1"
                aria-labelledby="editTargetModalLabel{{ $target->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow">
                        <form action="{{ route('admin.target.update', ['target' => $target->id]) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-header bg-warning text-white border-0">
                                <h5 class="modal-title" id="editTargetModalLabel{{ $target->id }}">
                                    <i class="ti ti-edit me-2"></i>Edit Target: {{ $target->nama_target }}
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
                                    <label for="hobi_id{{ $target->id }}" class="form-label fw-semibold">
                                        <i class="ti ti-heart text-danger me-2"></i>Hobi
                                    </label>
                                    <select class="form-select" id="hobi_id{{ $target->id }}" name="hobi_id" required>
                                        <option value="">Pilih Hobi</option>
                                        @foreach ($hobis as $hobi)
                                            <option value="{{ $hobi->id }}"
                                                {{ $target->hobi_id == $hobi->id ? 'selected' : '' }}>
                                                {{ $hobi->nama_hobi }} ({{ $hobi->kategoriHobi->nama_kategori ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="nama_target{{ $target->id }}" class="form-label fw-semibold">
                                        <i class="ti ti-target text-primary me-2"></i>Nama Target
                                    </label>
                                    <input type="text" class="form-control" id="nama_target{{ $target->id }}"
                                        name="nama_target" value="{{ $target->nama_target }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="target_deadline{{ $target->id }}" class="form-label fw-semibold">
                                        <i class="ti ti-calendar text-success me-2"></i>Batas Waktu
                                    </label>
                                    <input type="date" class="form-control" id="target_deadline{{ $target->id }}"
                                        name="target_deadline" value="{{ $target->target_deadline->format('Y-m-d') }}"
                                        min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                    @error('target_deadline')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
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
        @endforeach

        <!-- Progress Modals -->
        @foreach ($targets as $target)
            <div class="modal fade" id="progressModal{{ $target->id }}" tabindex="-1"
                aria-labelledby="progressModalLabel{{ $target->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow">
                        <form action="{{ route('admin.progres.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="target_id" value="{{ $target->id }}">
                            <div class="modal-header bg-info text-white border-0">
                                <h5 class="modal-title" id="progressModalLabel{{ $target->id }}">
                                    <i class="ti ti-chart-bar me-2"></i>Progress: {{ $target->nama_target }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <p class="text-muted mb-4">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Tambah progres untuk target "{{ $target->nama_target }}".
                                </p>

                                <div class="mb-3">
                                    <label for="status{{ $target->id }}" class="form-label fw-semibold">
                                        <i class="ti ti-check-circle text-success me-2"></i>Status
                                    </label>
                                    <select class="form-select" id="status{{ $target->id }}" name="status" required>
                                        <option value="on_progress" selected>On Progress</option>
                                        <option value="completed">Completed</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="file_bukti{{ $target->id }}" class="form-label fw-semibold">
                                        <i class="ti ti-paperclip text-primary me-2"></i>Bukti File
                                    </label>
                                    <input class="form-control" type="file" id="file_bukti{{ $target->id }}"
                                        name="file_bukti" accept="image/*,.pdf">
                                    <div class="form-text">
                                        <i class="ti ti-info-circle me-1"></i>
                                        Format yang didukung: Gambar (max 5MB) atau PDF. Wajib jika tidak ada link Google
                                        Drive.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="link_gdrive{{ $target->id }}" class="form-label fw-semibold">
                                        <i class="ti ti-link text-info me-2"></i>Link Google Drive
                                    </label>
                                    <input type="url" class="form-control" id="link_gdrive{{ $target->id }}"
                                        name="link_gdrive" placeholder="https://drive.google.com/...">
                                    <div class="form-text">
                                        <i class="ti ti-info-circle me-1"></i>
                                        Wajib jika tidak ada file bukti. Gunakan jika file terlalu besar.
                                    </div>
                                </div>
                                @if ($errors->has('bukti') || $errors->has('file_bukti') || $errors->has('link_gdrive'))
                                    <div class="alert alert-danger">
                                        @if ($errors->has('bukti'))
                                            {{ $errors->first('bukti') }}
                                        @endif
                                        @if ($errors->has('file_bukti'))
                                            {{ $errors->first('file_bukti') }}
                                        @endif
                                        @if ($errors->has('link_gdrive'))
                                            {{ $errors->first('link_gdrive') }}
                                        @endif
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="catatan{{ $target->id }}" class="form-label fw-semibold">
                                        <i class="ti ti-notes text-warning me-2"></i>Catatan
                                    </label>
                                    <textarea class="form-control" id="catatan{{ $target->id }}" name="catatan" rows="3"
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
        @endforeach

        <!-- Detail Modals -->
        @foreach ($targets as $target)
            <div class="modal fade" id="detailModal{{ $target->id }}" tabindex="-1"
                aria-labelledby="detailModalLabel{{ $target->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-secondary text-white border-0">
                            <h5 class="modal-title" id="detailModalLabel{{ $target->id }}">
                                <i class="ti ti-eye me-2"></i>Detail: {{ $target->nama_target }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="text-muted mb-4">
                                <i class="ti ti-info-circle me-2"></i>
                                Lihat detail target dan daftar progres.
                            </p>

                            <dl class="row">
                                <dt class="col-sm-3">Nama Target</dt>
                                <dd class="col-sm-9">{{ $target->nama_target }}</dd>

                                <dt class="col-sm-3">Hobi</dt>
                                <dd class="col-sm-9">{{ $target->hobi->nama_hobi ?? 'N/A' }}</dd>

                                <dt class="col-sm-3">Kategori</dt>
                                <dd class="col-sm-9">
                                    <span class="badge {{ $target->hobi->kategoriHobi->background_color ?? 'bg-info-subtle' }} {{ $target->hobi->kategoriHobi->background_color ? 'text-white' : 'text-info' }} px-3 py-2">
                                        <i
                                            class="ti {{ $target->hobi->kategoriHobi->icon ?? 'ti-tag' }} me-1"></i>{{ $target->hobi->kategoriHobi->nama_kategori ?? 'N/A' }}
                                    </span>
                                </dd>

                                <dt class="col-sm-3">Batas Waktu</dt>
                                <dd class="col-sm-9">
                                    {{ \Carbon\Carbon::parse($target->target_deadline)->format('d F Y') }}</dd>

                                <dt class="col-sm-3">Tanggal Dibuat</dt>
                                <dd class="col-sm-9">{{ $target->created_at->format('d F Y') }}</dd>
                            </dl>

                            <h6 class="mt-4">Daftar Progres:</h6>
                            @forelse($target->progresTarget as $progres)
                                <div class="border rounded p-3 mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>Status: <strong>{{ ucfirst($progres->status) }}</strong></span>
                                        <div>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editProgresModal{{ $progres->id }}"
                                                title="Edit Progres">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            <form
                                                action="{{ route('admin.progres.destroy', ['progresTarget' => $progres->id]) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    title="Hapus Progres" onclick="confirmDeleteProgress(this)">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                            <small>{{ $progres->created_at->format('d F Y H:i') }}</small>
                                        </div>
                                    </div>
                                    @if ($progres->file_bukti)
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="viewFile('{{ asset('storage/' . $progres->file_bukti) }}', '{{ $progres->file_bukti }}')">
                                                <i class="ti ti-eye"></i> Lihat Bukti File
                                            </button>
                                        </div>
                                    @endif
                                    @if ($progres->link_gdrive)
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                onclick="viewGDrive('{{ $progres->link_gdrive }}')">
                                                <i class="ti ti-external-link"></i> Lihat di Google Drive
                                            </button>
                                        </div>
                                    @endif
                                    @if ($progres->catatan)
                                        <p class="mt-2 mb-0"><em>{{ $progres->catatan }}</em></p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted">Belum ada progres.</p>
                            @endforelse
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ti ti-x me-2"></i>Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Edit Progres Modals -->
        @foreach ($targets as $target)
            @foreach ($target->progresTarget as $progres)
                <div class="modal fade" id="editProgresModal{{ $progres->id }}" tabindex="-1"
                    aria-labelledby="editProgresModalLabel{{ $progres->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow">
                            <form action="{{ route('admin.progres.update', $progres) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="modal-header bg-warning text-white border-0">
                                    <h5 class="modal-title" id="editProgresModalLabel{{ $progres->id }}">
                                        <i class="ti ti-edit me-2"></i>Edit Progres: {{ $target->nama_target }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <p class="text-muted mb-4">
                                        <i class="ti ti-info-circle me-2"></i>
                                        Edit detail progres untuk target "{{ $target->nama_target }}".
                                    </p>

                                    <div class="mb-3">
                                        <label for="status_edit{{ $progres->id }}" class="form-label fw-semibold">
                                            <i class="ti ti-check-circle text-success me-2"></i>Status
                                        </label>
                                        <select class="form-select" id="status_edit{{ $progres->id }}" name="status"
                                            required>
                                            <option value="on_progress"
                                                {{ $progres->status == 'on_progress' ? 'selected' : '' }}>On Progress
                                            </option>
                                            <option value="completed"
                                                {{ $progres->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="failed" {{ $progres->status == 'failed' ? 'selected' : '' }}>
                                                Failed</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="file_bukti_edit{{ $progres->id }}" class="form-label fw-semibold">
                                            <i class="ti ti-paperclip text-primary me-2"></i>Bukti File
                                        </label>
                                        <input class="form-control" type="file"
                                            id="file_bukti_edit{{ $progres->id }}" name="file_bukti"
                                            accept="image/*,.pdf">
                                        <div class="form-text">
                                            <i class="ti ti-info-circle me-1"></i>
                                            Biarkan kosong jika tidak ingin ganti. Format: Gambar atau PDF (max 5MB).
                                        </div>
                                        @if ($progres->file_bukti)
                                            <small class="text-muted">File saat ini:
                                                {{ basename($progres->file_bukti) }}</small>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="link_gdrive_edit{{ $progres->id }}" class="form-label fw-semibold">
                                            <i class="ti ti-link text-info me-2"></i>Link Google Drive
                                        </label>
                                        <input type="url" class="form-control"
                                            id="link_gdrive_edit{{ $progres->id }}" name="link_gdrive"
                                            value="{{ $progres->link_gdrive }}"
                                            placeholder="https://drive.google.com/...">
                                        <div class="form-text">
                                            <i class="ti ti-info-circle me-1"></i>
                                            Wajib jika tidak ada file bukti. Gunakan jika file terlalu besar.
                                        </div>
                                    </div>
                                    @if ($errors->has('bukti') || $errors->has('file_bukti') || $errors->has('link_gdrive'))
                                        <div class="alert alert-danger">
                                            @if ($errors->has('bukti'))
                                                {{ $errors->first('bukti') }}
                                            @endif
                                            @if ($errors->has('file_bukti'))
                                                {{ $errors->first('file_bukti') }}
                                            @endif
                                            @if ($errors->has('link_gdrive'))
                                                {{ $errors->first('link_gdrive') }}
                                            @endif
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label for="catatan_edit{{ $progres->id }}" class="form-label fw-semibold">
                                            <i class="ti ti-notes text-warning me-2"></i>Catatan
                                        </label>
                                        <textarea class="form-control" id="catatan_edit{{ $progres->id }}" name="catatan" rows="3">{{ $progres->catatan }}</textarea>
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
            @endforeach
        @endforeach

        <!-- File View Modal -->
        <div class="modal fade" id="fileViewModal" tabindex="-1" aria-labelledby="fileViewModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title" id="fileViewModalLabel">
                            <i class="ti ti-eye me-2"></i>Lihat Bukti File
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <div id="fileContent"></div>
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

@section('scripts')
    <script>
        var hasSuccess = {!! session('success') ? 'true' : 'false' !!};
    </script>
    <script src="{{ asset('admin/js/target.js') }}"></script>
@endsection
