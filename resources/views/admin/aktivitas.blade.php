@extends('admin.layouts.app')

@section('title', 'Aktivitas - HobiTracker')

@section('content')
    <div class="container-fluid" style="padding-top: 20px">
        <!-- Page Header with Better Layout -->
        <div class="row mb-4">
            <div class="col-12">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">
                            <i class="ti ti-activity text-primary me-2"></i>Kelola Aktivitas
                        </h3>
                        <p class="text-muted mb-0">Tambah, edit, dan kelola semua aktivitas hobi Anda</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahAktivitasModal">
                            <i class="ti ti-plus me-2"></i>Tambah Aktivitas
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        <!-- Quick Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Total Aktivitas</h6>
                                <h4 class="mb-0">{{ $totalAktivitas ?? 0 }}</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-list-check fs-1 text-white-50"></i>
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
                                <h6 class="text-white-50 mb-1">Hobi Aktif</h6>
                                <h4 class="mb-0">{{ $hobiAktif ?? 0 }}</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-heart fs-1 text-white-50"></i>
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
                                <h4 class="mb-0">{{ $totalDurasi ?? '0m' }}</h4>
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
                                <h6 class="text-white-50 mb-1">Rata-rata</h6>
                                <h4 class="mb-0">{{ $rataRataDurasi ?? '0m' }}</h4>
                            </div>
                            <div class="ms-3">
                                <i class="ti ti-trending-up fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom-0 pt-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-1">Daftar Aktivitas</h5>
                        <p class="text-muted small mb-0">Kelola semua aktivitas hobi Anda di sini</p>
                    </div>
                    <div class="col-auto">
                        <div class="input-group input-group-sm" style="width: 280px;">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ti ti-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Cari aktivitas...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fixed table section for aktivitas.blade.php --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="border-0 py-3 px-4" style="width: 5%;">#</th>
                            <th scope="col" class="border-0 py-3" style="width: 30%;">Aktivitas</th>
                            <th scope="col" class="border-0 py-3" style="width: 20%;">Hobi</th>
                            <th scope="col" class="border-0 py-3" style="width: 15%;">Durasi</th>
                            <th scope="col" class="border-0 py-3">Catatan</th>
                            <th scope="col" class="border-0 py-3 text-center" style="width: 8%;">File</th>
                            <th scope="col" class="border-0 py-3 text-center" style="width: 12%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aktivitas as $index => $aktivitasItem)
                            <tr class="border-bottom" data-aktivitas-row data-id="{{ $aktivitasItem->id }}">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="py-3">
                                    <h6 class="mb-1 fw-semibold">{{ $aktivitasItem->nama_aktivitas }}</h6>
                                </td>
                                <td class="py-3">
                                    <span class="fw-semibold">{{ $aktivitasItem->hobi->nama_hobi ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="fw-semibold">{{ $aktivitasItem->durasi_menit }} Menit</span>
                                </td>
                                <td class="py-3">
                                    <div class="text-truncate" style="max-width: 180px;" data-bs-toggle="tooltip"
                                        title="{{ $aktivitasItem->catatan ?? 'Tidak ada catatan' }}">
                                        {{ $aktivitasItem->catatan ?? 'Tidak ada catatan' }}
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    @if ($aktivitasItem->file_bukti)
                                        @if (str_contains($aktivitasItem->file_bukti, 'drive.google.com'))
                                            {{-- Google Drive link --}}
                                            <button class="btn btn-sm btn-outline-primary rounded-circle"
                                                data-bs-toggle="tooltip" title="Lihat file di Google Drive"
                                                data-file-url="{{ $aktivitasItem->file_bukti }}" data-file-type="gdrive"
                                                onclick="showFilePreview('{{ $aktivitasItem->file_bukti }}', 'gdrive')">
                                                <i class="ti ti-brand-google-drive"></i>
                                            </button>
                                        @else
                                            {{-- Local file - detect type --}}
                                            @php
                                                $extension = strtolower(
                                                    pathinfo($aktivitasItem->file_bukti, PATHINFO_EXTENSION),
                                                );
                                                $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                                $videoExts = ['mp4', 'mov', 'avi', 'webm'];

                                                if (in_array($extension, $imageExts)) {
                                                    $fileType = 'image';
                                                    $icon = 'ti-photo';
                                                } elseif (in_array($extension, $videoExts)) {
                                                    $fileType = 'video';
                                                    $icon = 'ti-video';
                                                } else {
                                                    $fileType = 'file';
                                                    $icon = 'ti-file-text';
                                                }
                                            @endphp

                                            <button class="btn btn-sm btn-outline-primary rounded-circle"
                                                data-bs-toggle="tooltip" title="Lihat file bukti"
                                                data-file-url="{{ Storage::url($aktivitasItem->file_bukti) }}"
                                                data-file-type="{{ $fileType }}"
                                                onclick="showFilePreview('{{ Storage::url($aktivitasItem->file_bukti) }}', '{{ $fileType }}')">
                                                <i class="ti {{ $icon }}"></i>
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editAktivitasModal" data-bs-toggle="tooltip"
                                            title="Edit Aktivitas" data-id="{{ $aktivitasItem->id }}"
                                            data-nama="{{ $aktivitasItem->nama_aktivitas }}"
                                            data-hobi="{{ $aktivitasItem->hobi->nama_hobi ?? '' }}"
                                            data-durasi="{{ $aktivitasItem->durasi_menit }}"
                                            data-catatan="{{ $aktivitasItem->catatan ?? '' }}">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                        <form action="{{ route('aktivitas.destroy', $aktivitasItem->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus aktivitas ini?');"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Aktivitas">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="ti ti-activity text-muted" style="font-size: 4rem;"></i>
                                    </div>
                                    <h5 class="text-muted">Belum ada aktivitas</h5>
                                    <p class="text-muted mb-4">Mulai dengan menambahkan aktivitas hobi pertama Anda</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Empty State (if no data) -->
            {{-- <div class="card-body text-center py-5" id="empty-state" style="display: none;">
                <div class="mb-4">
                    <i class="ti ti-activity text-muted" style="font-size: 4rem;"></i>
                </div>
                <h5 class="text-muted">Belum ada aktivitas</h5>
                <p class="text-muted mb-4">Mulai dengan menambahkan aktivitas hobi pertama Anda</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahAktivitasModal">
                    <i class="ti ti-plus me-2"></i>Tambah Aktivitas Pertama
                </button>
            </div> --}}
        </div>
    </div>

    <!-- Enhanced Modal -->
    <div class="modal fade" id="tambahAktivitasModal" tabindex="-1" aria-labelledby="tambahAktivitasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="{{ route('aktivitas.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title" id="tambahAktivitasModalLabel">
                            <i class="ti ti-plus-circle me-2"></i>Tambah Aktivitas Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted mb-4">
                            <i class="ti ti-info-circle me-2"></i>
                            Isi detail aktivitas baru Anda dengan lengkap untuk tracking yang lebih baik
                        </p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pilihHobi" class="form-label fw-semibold">
                                        <i class="ti ti-heart text-danger me-2"></i>Pilih Hobi
                                    </label>
                                    <select class="form-select" id="pilihHobi" name="hobi_id" required>
                                        <option value="" selected disabled>Pilih Hobi Terkait...</option>
                                        @foreach ($hobis ?? [] as $hobi)
                                            <option value="{{ $hobi->id }}">{{ $hobi->nama_hobi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="durasiMenit" class="form-label fw-semibold">
                                        <i class="ti ti-clock text-info me-2"></i>Durasi (menit)
                                    </label>
                                    <input type="number" class="form-control" id="durasiMenit" name="durasi_menit"
                                        placeholder="Contoh: 30, 120" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="namaAktivitas" class="form-label fw-semibold">
                                <i class="ti ti-activity text-primary me-2"></i>Nama Aktivitas
                            </label>
                            <input type="text" class="form-control" id="namaAktivitas" name="nama_aktivitas"
                                placeholder="Contoh: Baca novel Dune chapter 1-3, Lari keliling taman 5km" required>
                        </div>

                        <div class="mb-3">
                            <label for="catatanAktivitas" class="form-label fw-semibold">
                                <i class="ti ti-notes text-warning me-2"></i>Catatan
                            </label>
                            <textarea class="form-control" id="catatanAktivitas" name="catatan" rows="3"
                                placeholder="Deskripsi tambahan, target yang ingin dicapai, atau catatan lainnya..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="fileBukti" class="form-label fw-semibold">
                                <i class="ti ti-paperclip text-success me-2"></i>File Bukti
                            </label>
                            <input class="form-control" type="file" id="fileBukti" name="file_bukti"
                                accept="image/*,video/*">
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Format yang didukung: Gambar (max 5MB) dan Video (max 50MB)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="gdriveLink" class="form-label fw-semibold">
                                <i class="ti ti-link text-info me-2"></i>Link Google Drive (Alternatif)
                            </label>
                            <input type="url" class="form-control" id="gdriveLink" name="gdrive_link"
                                placeholder="https://drive.google.com/file/...">
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Gunakan jika file terlalu besar untuk diupload langsung
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ti ti-x me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Simpan Aktivitas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal untuk edit - PERBAIKAN FORM ID --}}
    <div class="modal fade" id="editAktivitasModal" tabindex="-1" aria-labelledby="editAktivitasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="" id="editAktivitasForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-white border-0">
                        <h5 class="modal-title" id="editAktivitasModalLabel">
                            <i class="ti ti-edit me-2"></i>Edit Aktivitas
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted mb-4">
                            <i class="ti ti-info-circle me-2"></i>
                            Edit detail aktivitas Anda sesuai dengan perubahan yang diinginkan
                        </p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editPilihHobi" class="form-label fw-semibold">
                                        <i class="ti ti-heart text-danger me-2"></i>Pilih Hobi
                                    </label>
                                    <select class="form-select" id="editPilihHobi" name="hobi_id" required>
                                        <option value="" selected disabled>Pilih Hobi Terkait...</option>
                                        @foreach ($hobis ?? [] as $hobi)
                                            <option value="{{ $hobi->id }}">{{ $hobi->nama_hobi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editDurasiMenit" class="form-label fw-semibold">
                                        <i class="ti ti-clock text-info me-2"></i>Durasi (menit)
                                    </label>
                                    <input type="number" class="form-control" id="editDurasiMenit" name="durasi_menit"
                                        placeholder="Contoh: 30, 120" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editNamaAktivitas" class="form-label fw-semibold">
                                <i class="ti ti-activity text-primary me-2"></i>Nama Aktivitas
                            </label>
                            <input type="text" class="form-control" id="editNamaAktivitas" name="nama_aktivitas"
                                placeholder="Contoh: Baca novel Dune chapter 1-3, Lari keliling taman 5km" required>
                        </div>

                        <div class="mb-3">
                            <label for="editCatatanAktivitas" class="form-label fw-semibold">
                                <i class="ti ti-notes text-warning me-2"></i>Catatan
                            </label>
                            <textarea class="form-control" id="editCatatanAktivitas" name="catatan" rows="3"
                                placeholder="Deskripsi tambahan, target yang ingin dicapai, atau catatan lainnya..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="editFileBukti" class="form-label fw-semibold">
                                <i class="ti ti-paperclip text-success me-2"></i>File Bukti
                            </label>
                            <input class="form-control" type="file" id="editFileBukti" name="file_bukti"
                                accept="image/*,video/*">
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Format yang didukung: Gambar (max 5MB) dan Video (max 50MB)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editGdriveLink" class="form-label fw-semibold">
                                <i class="ti ti-link text-info me-2"></i>Link Google Drive (Alternatif)
                            </label>
                            <input type="url" class="form-control" id="editGdriveLink" name="gdrive_link"
                                placeholder="https://drive.google.com/file/...">
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Gunakan jika file terlalu besar untuk diupload langsung
                            </div>
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

    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title" id="filePreviewModalLabel">
                        <i class="ti ti-file-text me-2"></i>Preview File
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- Content will be loaded dynamically --}}
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // Simple form population for edit modal
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('button[data-bs-target="#editAktivitasModal"]');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const hobi = this.getAttribute('data-hobi');
                    const durasi = this.getAttribute('data-durasi');
                    const catatan = this.getAttribute('data-catatan');

                    // Populate form fields
                    document.getElementById('editNamaAktivitas').value = nama;
                    document.getElementById('editDurasiMenit').value = durasi;
                    document.getElementById('editCatatanAktivitas').value = catatan;

                    // Set hobi selection
                    const hobiSelect = document.getElementById('editPilihHobi');
                    for (let option of hobiSelect.options) {
                        if (option.text.trim() === hobi) {
                            option.selected = true;
                            break;
                        }
                    }

                    // Set form action
                    document.getElementById('editAktivitasForm').action =
                        `{{ url('aktivitas') }}/${id}`;
                });
            });

            // Simple search functionality
            const searchInput = document.querySelector('input[placeholder="Cari aktivitas..."]');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('tbody tr[data-aktivitas-row]');

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });

        // File preview modal function
        function showFilePreview(fileUrl, fileType) {
            const modal = document.getElementById('filePreviewModal');
            const modalBody = modal.querySelector('.modal-body');
            const modalTitle = modal.querySelector('.modal-title');

            // Clear previous content
            modalBody.innerHTML = '';

            if (fileType === 'gdrive') {
                modalTitle.textContent = 'File dari Google Drive';
                modalBody.innerHTML = `
                    <div class="text-center">
                        <i class="ti ti-brand-google-drive text-primary mb-3" style="font-size: 3rem;"></i>
                        <p class="mb-3">File disimpan di Google Drive</p>
                        <a href="${fileUrl}" target="_blank" class="btn btn-primary">
                            <i class="ti ti-external-link me-2"></i>Buka di Google Drive
                        </a>
                    </div>
                `;
            } else if (fileType === 'image') {
                modalTitle.textContent = 'Preview Gambar';
                modalBody.innerHTML = `
                    <div class="text-center">
                        <img src="${fileUrl}" class="img-fluid" style="max-height: 500px;" alt="Preview">
                    </div>
                `;
            } else if (fileType === 'video') {
                modalTitle.textContent = 'Preview Video';
                modalBody.innerHTML = `
                    <div class="text-center">
                        <video controls class="img-fluid" style="max-height: 500px;">
                            <source src="${fileUrl}" type="video/mp4">
                            Browser Anda tidak mendukung video.
                        </video>
                    </div>
                `;
            } else {
                modalTitle.textContent = 'File Bukti';
                modalBody.innerHTML = `
                    <div class="text-center">
                        <i class="ti ti-file text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="mb-3">File tidak dapat dipratinjau</p>
                        <a href="${fileUrl}" target="_blank" class="btn btn-primary">
                            <i class="ti ti-download me-2"></i>Download File
                        </a>
                    </div>
                `;
            }

            // Show modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }

        // Auto refresh after successful operations
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there's a success message and refresh after 2 seconds
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                setTimeout(function() {
                    location.reload();
                }, 2000);
            }
        });
    </script>
@endsection
