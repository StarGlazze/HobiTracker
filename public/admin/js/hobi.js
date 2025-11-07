document.addEventListener('DOMContentLoaded', function () {
    var editHobiModal = document.getElementById('editHobiModal');

    // Edit modal functionality
    if (editHobiModal) {
        editHobiModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var nama = button.getAttribute('data-nama');
            var kategori = button.getAttribute('data-kategori');
            var deskripsi = button.getAttribute('data-deskripsi');

            var form = document.getElementById('editHobiForm');
            if (form) {
                form.action = '/hobi/' + id;
            }

            var editNamaHobi = document.getElementById('editNamaHobi');
            var editKategoriHobi = document.getElementById('editKategoriHobi');
            var editDeskripsiHobi = document.getElementById('editDeskripsiHobi');

            if (editNamaHobi) editNamaHobi.value = nama || '';
            if (editKategoriHobi) editKategoriHobi.value = kategori || '';
            if (editDeskripsiHobi) editDeskripsiHobi.value = deskripsi || '';
        });
    }

    // Konfirmasi hapus hobi
    document.querySelectorAll('.hapus-hobi-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var nama = form.querySelector('button[type="submit"]').getAttribute('data-nama');
            var pesan = 'Yakin ingin menghapus hobi "' + nama + '"? Tindakan ini akan menghapus semua target dan aktivitas terkait secara permanen.';
            if (!confirm(pesan)) {
                e.preventDefault();
            }
        });
    });

    // Auto-submit search on Enter key
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchForm').submit();
            }
        });
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Import hobi functionality
document.addEventListener('DOMContentLoaded', function () {
    const importForm = document.getElementById('importHobiForm');
    const importSubmitBtn = document.getElementById('importSubmitBtn');
    const importFileInput = document.getElementById('importFile');

    if (importForm && importSubmitBtn) {
        // Disable submit button during form submission
        importForm.addEventListener('submit', function(e) {
            importSubmitBtn.disabled = true;
            importSubmitBtn.innerHTML = '<i class="ti ti-loader me-2"></i>Mengimpor...';

            // Re-enable after 30 seconds as fallback
            setTimeout(function() {
                importSubmitBtn.disabled = false;
                importSubmitBtn.innerHTML = '<i class="ti ti-file-upload me-2"></i>Import Sekarang';
            }, 30000);
        });

        // Validate file type on change
        if (importFileInput) {
            importFileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('Format file tidak didukung. Harap pilih file Excel (.xlsx atau .xls)');
                        e.target.value = '';
                        return;
                    }

                    // Check file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                        e.target.value = '';
                        return;
                    }
                }
            });
        }
    }
});

// Global function to clear search
function clearSearch(event) {
    if (event) {
        event.preventDefault();
    }
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.value = '';
        document.getElementById('searchForm').submit();
    }
}
