@extends('admin.layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-dark mb-0">
                            <i class="ti ti-adjustments me-2 text-primary"></i>Pengaturan
                        </h3>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="site_name" class="form-label fw-semibold">
                                            <i class="ti ti-world me-2 text-primary"></i>Nama Website
                                        </label>
                                        <input type="text" class="form-control form-control-lg" id="site_name" name="site_name" placeholder="Masukkan nama situs">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_email" class="form-label fw-semibold">
                                            <i class="ti ti-mail me-2 text-info"></i>Email
                                        </label>
                                        <input type="email" class="form-control form-control-lg" id="contact_email" name="contact_email" placeholder="Masukkan email kontak">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_phone" class="form-label fw-semibold">
                                            <i class="ti ti-phone me-2 text-success"></i>Nomor Telepon
                                        </label>
                                        <input type="text" class="form-control form-control-lg" id="contact_phone" name="contact_phone" placeholder="Masukkan telepon kontak">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="site_logo" class="form-label fw-semibold">
                                            <i class="ti ti-photo me-2 text-warning"></i>Logo Website
                                        </label>
                                        <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/*">
                                        <div class="mt-2">
                                            <img src="/admin/images/logos/HobiTracker.png" alt="Logo Situs" class="img-thumbnail rounded-3" style="max-height: 100px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label fw-semibold">
                                    <i class="ti ti-map-pin me-2 text-danger"></i>Alamat
                                </label>
                                <textarea class="form-control form-control-lg" id="address" name="address" rows="3" placeholder="Masukkan alamat"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="hobby_categories" class="form-label fw-semibold">
                                    <i class="ti ti-list-check me-2 text-secondary"></i>Kategori Hobi
                                </label>
                                <textarea class="form-control form-control-lg" id="hobby_categories" name="hobby_categories" rows="3" placeholder="Masukkan kategori hobi dipisahkan dengan koma"></textarea>
                                <div class="form-text">Pisahkan kategori dengan koma (,)</div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary rounded-3 px-4">
                                    <i class="ti ti-device-floppy me-2"></i>Perbarui Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
