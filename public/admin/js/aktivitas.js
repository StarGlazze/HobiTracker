/**
 * Aktivitas Management JavaScript
 * Handles CRUD operations for activities page
 */

document.addEventListener('DOMContentLoaded', function() {
    // CSRF token untuk AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        showNotification('CSRF Token tidak ditemukan!', 'error');
        return;
    }

    // Check routes configuration
    if (!window.routes || !window.routes.aktivitas) {
        showNotification('Konfigurasi routes tidak ditemukan. Silakan refresh halaman.', 'error');
        return;
    }

    // Initialize all functionality
    initAktivitasForm();
    initEditForm();
    initDeleteHandlers();
    initSearch();
    initFilePreview();
});

/**
 * Initialize Add Activity Form
 */
function initAktivitasForm() {
    const tambahForm = document.querySelector('#tambahAktivitasForm');

    if (tambahForm) {
        tambahForm.addEventListener('submit', handleTambahAktivitas);
    }
}

/**
 * Initialize Edit Activity Form
 */
function initEditForm() {
    // Handle edit button clicks using event delegation
    document.addEventListener('click', function(e) {
        const editButton = e.target.closest('button[data-bs-target="#editAktivitasModal"]');

        if (editButton) {
            e.preventDefault();
            const aktivitasId = editButton.getAttribute('data-id');

            if (aktivitasId) {
                loadAktivitasData(aktivitasId);
            } else {
                showNotification('ID aktivitas tidak ditemukan', 'error');
            }
        }
    });

    // Handle edit form submission
    const editForm = document.querySelector('#editAktivitasForm');
    if (editForm) {
        editForm.addEventListener('submit', handleEditAktivitas);
    }
}

/**
 * Initialize Delete Handlers
 */
function initDeleteHandlers() {
    document.addEventListener('click', function(e) {
        const deleteButton = e.target.closest('button.btn-danger[data-id]');

        if (deleteButton) {
            e.preventDefault();
            const aktivitasId = deleteButton.getAttribute('data-id');

            if (aktivitasId) {
                handleDeleteAktivitas(aktivitasId, deleteButton);
            }
        }
    });
}

/**
 * Initialize File Preview
 */
function initFilePreview() {
    document.addEventListener('click', function(e) {
        const previewButton = e.target.closest('button[data-file-url]');

        if (previewButton) {
            e.preventDefault();
            const fileUrl = previewButton.getAttribute('data-file-url');
            const fileType = previewButton.getAttribute('data-file-type');
            showFilePreview(fileUrl, fileType);
        }
    });
}

/**
 * Show File Preview in Modal
 */
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

/**
 * Initialize Search Functionality
 */
function initSearch() {
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
}

/**
 * Handle Add Activity Form Submission
 */
function handleTambahAktivitas(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    // Show loading state
    setButtonLoading(submitBtn, 'Menyimpan...');

    fetch(window.routes.aktivitas.store, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().catch(() => ({ message: 'Gagal terhubung ke server atau server error.' }));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Aktivitas berhasil ditambahkan!', 'success');
            closeModal('tambahAktivitasModal');
            form.reset();
            setTimeout(() => window.location.reload(), 1500);
        } else if (data.errors) {
            showValidationErrors(data.errors);
            showNotification('Periksa kembali form Anda', 'warning');
        } else {
            showNotification(data.message || 'Terjadi kesalahan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat menyimpan data.', 'error');
    })
    .finally(() => {
        resetButton(submitBtn, originalText);
    });
}

/**
 * Load Activity Data for Edit Form
 */
function loadAktivitasData(aktivitasId) {
    // Find the button and row
    const editButton = document.querySelector(`button[data-bs-target="#editAktivitasModal"][data-id="${aktivitasId}"]`);
    if (!editButton) {
        showNotification('Tombol edit tidak ditemukan', 'error');
        return;
    }

    const row = editButton.closest('tr');
    if (!row) {
        showNotification('Data baris tidak ditemukan', 'error');
        return;
    }

    try {
        // Get data from table cells
        const cells = row.querySelectorAll('td');

        if (cells.length < 7) {
            throw new Error('Insufficient table cells');
        }

        // Extract data from specific cells
        const namaAktivitas = cells[1].querySelector('h6')?.textContent?.trim() || '';
        const hobiText = cells[2].querySelector('span')?.textContent?.trim() || '';
        const durasiText = cells[3].querySelector('span')?.textContent?.trim() || '';
        const catatan = cells[4].querySelector('.text-truncate')?.textContent?.trim() || '';

        // Parse durasi
        const durasi = parseInt(durasiText.replace(/\D/g, '')) || 0;

        // Find hobi_id
        const hobiSelect = document.getElementById('editPilihHobi');
        let hobiId = '';

        if (hobiSelect) {
            for (let option of hobiSelect.options) {
                if (option.text.trim() === hobiText) {
                    hobiId = option.value;
                    break;
                }
            }
        }

        // Fill form fields
        const fields = {
            'editPilihHobi': hobiId,
            'editNamaAktivitas': namaAktivitas,
            'editDurasiMenit': durasi,
            'editCatatanAktivitas': catatan === 'Tidak ada catatan' ? '' : catatan,
            'editGdriveLink': ''
        };

        for (const [fieldId, value] of Object.entries(fields)) {
            const field = document.getElementById(fieldId);
            if (field) {
                field.value = value;
            }
        }

        // Store ID for form submission
        document.getElementById('editAktivitasModal').setAttribute('data-aktivitas-id', aktivitasId);

    } catch (error) {
        console.error('Error extracting data:', error);
        showNotification('Gagal memuat data aktivitas', 'error');
    }
}

/**
 * Handle Edit Activity Form Submission
 */
function handleEditAktivitas(e) {
    e.preventDefault();

    const form = e.target;
    const modal = document.getElementById('editAktivitasModal');
    const aktivitasId = modal.getAttribute('data-aktivitas-id');

    if (!aktivitasId) {
        showNotification('ID aktivitas tidak ditemukan', 'error');
        return;
    }

    const formData = new FormData(form);
    formData.append('_method', 'PUT');

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    // Show loading state
    setButtonLoading(submitBtn, 'Menyimpan...');

    fetch(`${window.routes.aktivitas.base}/${aktivitasId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        // Cek apakah respons berhasil sebelum mencoba parse JSON
        if (!response.ok) {
            return response.json().catch(() => ({ message: 'Gagal terhubung ke server atau server error.' }));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Aktivitas berhasil diperbarui!', 'success');
            closeModal('editAktivitasModal');
            setTimeout(() => window.location.reload(), 1500);
        } else if (data.errors) {
            showValidationErrors(data.errors, 'edit');
            showNotification('Periksa kembali form Anda', 'warning');
        } else {
            showNotification(data.message || 'Terjadi kesalahan saat memperbarui aktivitas', 'error');
        }
    })
    .catch(error => {
        console.error('Edit error:', error);
        showNotification('Terjadi kesalahan saat memperbarui data.', 'error');
    })
    .finally(() => {
        resetButton(submitBtn, originalText);
    });
}

/**
 * Handle Delete Activity
 */
function handleDeleteAktivitas(aktivitasId, buttonElement) {
    const row = buttonElement.closest('tr');
    const nameElement = row.querySelector('h6');
    const aktivitasName = nameElement ? nameElement.textContent.trim() : 'aktivitas ini';

    if (confirm(`Apakah Anda yakin ingin menghapus "${aktivitasName}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
        const originalHTML = buttonElement.innerHTML;
        setButtonLoading(buttonElement, '');

        fetch(`${window.routes.aktivitas.base}/${aktivitasId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            // Cek status respons sebelum parsing
            if (!response.ok) {
                return response.json().catch(() => ({ message: 'Gagal terhubung ke server atau server error.' }));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification('Aktivitas berhasil dihapus!', 'success');
                row.style.transition = 'opacity 0.5s';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    const remainingRows = document.querySelectorAll('tbody tr[data-aktivitas-row]');
                    if (remainingRows.length === 0) {
                        location.reload();
                    }
                }, 500);
            } else {
                showNotification(data.message || 'Gagal menghapus aktivitas', 'error');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            showNotification('Terjadi kesalahan saat menghapus data.', 'error');
        })
        .finally(() => {
            resetButton(buttonElement, originalHTML);
        });
    }
}

/**
 * Utility Functions
 */
function showNotification(message, type = 'info') {
    // Remove existing notifications
    document.querySelectorAll('.custom-notification').forEach(n => n.remove());

    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed custom-notification`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);';

    const iconMap = {
        success: 'ti-check-circle',
        error: 'ti-x-circle',
        warning: 'ti-alert-triangle',
        info: 'ti-info-circle'
    };

    notification.innerHTML = `
        <i class="ti ${iconMap[type] || 'ti-info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

function showValidationErrors(errors, prefix = '') {
    // Clear previous errors
    document.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.remove();
    });

    // Show new errors
    for (const [field, messages] of Object.entries(errors)) {
        let fieldId = field;
        if (prefix) {
            fieldId = prefix + field.charAt(0).toUpperCase() + field.slice(1);
        }

        const element = document.getElementById(fieldId) ||
                      document.querySelector(`[name="${field}"]`);

        if (element) {
            element.classList.add('is-invalid');

            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = Array.isArray(messages) ? messages[0] : messages;
            element.parentNode.appendChild(feedback);
        }
    }
}

function setButtonLoading(button, text) {
    button.disabled = true;
    if (text) {
        button.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>${text}`;
    } else {
        button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    }
}

function resetButton(button, originalText) {
    button.disabled = false;
    button.innerHTML = originalText;
}

function closeModal(modalId) {
    const modalElement = document.getElementById(modalId);
    if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        modal.hide();
    }
}