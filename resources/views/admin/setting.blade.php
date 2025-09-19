@extends('admin.layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-dark mb-0">
                            <i class="ti ti-adjustments me-2 text-primary"></i>Pengaturan Website
                        </h3>
                        <p class="text-muted mb-0">Konfigurasi umum untuk website HobiTracker</p>
                    </div>
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="settingsTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="general-tab" data-bs-toggle="tab"
                                    data-bs-target="#general" type="button" role="tab" aria-controls="general"
                                    aria-selected="true">
                                    <i class="ti ti-world me-2"></i>Umum
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                    type="button" role="tab" aria-controls="contact" aria-selected="false">
                                    <i class="ti ti-phone me-2"></i>Kontak
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social"
                                    type="button" role="tab" aria-controls="social" aria-selected="false">
                                    <i class="ti ti-brand-facebook me-2"></i>Sosial Media
                                </button>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content mt-4" id="settingsTabContent">
                            <!-- General Tab -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel"
                                aria-labelledby="general-tab">
                                <form>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="site_name" class="form-label fw-semibold">
                                                    <i class="ti ti-world me-2 text-primary"></i>Nama Website
                                                </label>
                                                <input type="text" class="form-control form-control-lg" id="site_name"
                                                    name="site_name" placeholder="Masukkan nama situs">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="site_description" class="form-label fw-semibold">
                                                    <i class="ti ti-file-description me-2 text-info"></i>Deskripsi Website
                                                </label>
                                                <textarea class="form-control form-control-lg" id="site_description" name="site_description" rows="2"
                                                    placeholder="Deskripsi singkat website"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="site_logo" class="form-label fw-semibold">
                                                    <i class="ti ti-photo me-2 text-warning"></i>Logo Website
                                                </label>
                                                <input type="file" class="form-control" id="site_logo" name="site_logo"
                                                    accept="image/*">
                                                <div class="mt-2">
                                                    <img src="/admin/images/logos/HobiTracker.png" alt="Logo Situs"
                                                        class="img-thumbnail rounded-3" style="max-height: 100px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="favicon" class="form-label fw-semibold">
                                                    <i class="ti ti-star me-2 text-success"></i>Favicon
                                                </label>
                                                <input type="file" class="form-control" id="favicon" name="favicon"
                                                    accept="image/*">
                                                <div class="mt-2">
                                                    <img src="/admin/images/logos/favicon-v2.png" alt="Favicon"
                                                        class="img-thumbnail rounded-3" style="max-height: 50px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="ti ti-list-check me-2 text-secondary"></i>Kategori Hobi
                                        </label>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#hobbyCategoriesModal">
                                                <i class="ti ti-edit me-2"></i>Kelola Kategori Hobi
                                            </button>
                                            <small class="text-muted d-block mt-1">Klik untuk mengelola kategori
                                                hobi</small>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Contact Tab -->
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <form>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="contact_email" class="form-label fw-semibold">
                                                    <i class="ti ti-mail me-2 text-info"></i>Email Kontak
                                                </label>
                                                <input type="email" class="form-control form-control-lg"
                                                    id="contact_email" name="contact_email"
                                                    placeholder="Masukkan email kontak">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="contact_phone" class="form-label fw-semibold">
                                                    <i class="ti ti-phone me-2 text-success"></i>Nomor Telepon
                                                </label>
                                                <input type="text" class="form-control form-control-lg"
                                                    id="contact_phone" name="contact_phone"
                                                    placeholder="Masukkan telepon kontak">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label fw-semibold">
                                            <i class="ti ti-map-pin me-2 text-danger"></i>Alamat Lengkap
                                        </label>
                                        <textarea class="form-control form-control-lg" id="address" name="address" rows="3"
                                            placeholder="Masukkan alamat lengkap"></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="whatsapp" class="form-label fw-semibold">
                                                    <i class="ti ti-brand-whatsapp me-2 text-success"></i>WhatsApp
                                                </label>
                                                <input type="text" class="form-control form-control-lg" id="whatsapp"
                                                    name="whatsapp" placeholder="Nomor WhatsApp">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="telegram" class="form-label fw-semibold">
                                                    <i class="ti ti-brand-telegram me-2 text-info"></i>Telegram
                                                </label>
                                                <input type="text" class="form-control form-control-lg" id="telegram"
                                                    name="telegram" placeholder="Username Telegram">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Social Media Tab -->
                            <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                                <form>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="facebook" class="form-label fw-semibold">
                                                    <i class="ti ti-brand-facebook me-2 text-primary"></i>Facebook
                                                </label>
                                                <input type="url" class="form-control form-control-lg" id="facebook"
                                                    name="facebook" placeholder="https://facebook.com/username">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="instagram" class="form-label fw-semibold">
                                                    <i class="ti ti-brand-instagram me-2 text-danger"></i>Instagram
                                                </label>
                                                <input type="url" class="form-control form-control-lg" id="instagram"
                                                    name="instagram" placeholder="https://instagram.com/username">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="twitter" class="form-label fw-semibold">
                                                    <i class="ti ti-brand-twitter me-2 text-info"></i>Twitter/X
                                                </label>
                                                <input type="url" class="form-control form-control-lg" id="twitter"
                                                    name="twitter" placeholder="https://twitter.com/username">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="linkedin" class="form-label fw-semibold">
                                                    <i class="ti ti-brand-linkedin me-2 text-primary"></i>LinkedIn
                                                </label>
                                                <input type="url" class="form-control form-control-lg" id="linkedin"
                                                    name="linkedin" placeholder="https://linkedin.com/in/username">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="youtube" class="form-label fw-semibold">
                                            <i class="ti ti-brand-youtube me-2 text-danger"></i>YouTube
                                        </label>
                                        <input type="url" class="form-control form-control-lg" id="youtube"
                                            name="youtube" placeholder="https://youtube.com/channel/UC...">
                                    </div>
                                </form>
                            </div>

                        </div>

                        <!-- Hobby Categories Modal -->
                        <div class="modal fade" id="hobbyCategoriesModal" tabindex="-1"
                            aria-labelledby="hobbyCategoriesModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="hobbyCategoriesModalLabel">
                                            <i class="ti ti-list-check me-2"></i>Kelola Kategori Hobi
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="new_category" class="form-label">Tambah Kategori Baru</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="new_category"
                                                    placeholder="Masukkan nama kategori">
                                                <button class="btn btn-primary" type="button" id="addCategoryBtn">
                                                    <i class="ti ti-plus me-2"></i>Tambah
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Daftar Kategori</label>
                                            <div id="categoriesList" class="list-group">
                                                <!-- Categories will be populated here -->
                                                <div
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    Olahraga
                                                    <button class="btn btn-sm btn-outline-danger"
                                                        onclick="removeCategory(this)">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                                <div
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    Musik
                                                    <button class="btn btn-sm btn-outline-danger"
                                                        onclick="removeCategory(this)">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                                <div
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    Membaca
                                                    <button class="btn btn-sm btn-outline-danger"
                                                        onclick="removeCategory(this)">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                        <button type="button" class="btn btn-primary" id="saveCategoriesBtn">
                                            <i class="ti ti-device-floppy me-2"></i>Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-secondary rounded-3 px-4 me-2">
                                <i class="ti ti-refresh me-2"></i>Reset
                            </button>
                            <button type="button" class="btn btn-primary rounded-3 px-4">
                                <i class="ti ti-device-floppy me-2"></i>Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="/admin/js/settings.js"></script>
@endsection
