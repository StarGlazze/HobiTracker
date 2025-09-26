/**
 * Aktivitas Management JavaScript
 * Handles CRUD operations for activities page with improved file evidence validation
 */

document.addEventListener('DOMContentLoaded', function() {
    // CSRF token untuk AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        console.warn('CSRF Token tidak ditemukan!');
    }

    // Initialize all functionality
    initFormValidation();
    initEditForm();
    initDeleteHandlers();
    initSearch();
    initFilePreview();
});

/**
 * Initialize Form Validation for Both Add and Edit
 */
function initFormValidation() {
    // Handle Add Form
    const tambahForm = document.querySelector('#tambahAktivitasModal form');
    if (tambahForm) {
        tambahForm.addEventListener('submit', function(e) {
            if (!validateFileEvidence(tambahForm)) {
                e.preventDefault();
                showFileEvidenceError(tambahForm, 'Minimal satu bukti harus dikirim: File Bukti atau Link Google Drive!');
                return false;
            }
            clearFileEvidenceError(tambahForm);
        });

        // Real-time validation
        const fileInput = tambahForm.querySelector('#fileBukti');
        const linkInput = tambahForm.querySelector('#gdriveLink');
        
        if (fileInput && linkInput) {
            fileInput.addEventListener('change', () => clearFileEvidenceError(tambahForm));
            linkInput.addEventListener('input', () => clearFileEvidenceError(tambahForm));
        }
    }

    // Handle Edit Form  
    const editForm = document.querySelector('#editAktivitasForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            clearFileEvidenceError(editForm);
        });

        // Real-time validation for edit form
        const editFileInput = editForm.querySelector('#editFileBukti');
        const editLinkInput = editForm.querySelector('#editGdriveLink');
        
        if (editFileInput && editLinkInput) {
            editFileInput.addEventListener('change', () => clearFileEvidenceError(editForm));
            editLinkInput.addEventListener('input', () => clearFileEvidenceError(editForm));
        }
    }
}

/**
 * Validate File Evidence (at least one must be present)
 */
function validateFileEvidence(form) {
    const fileInput = form.querySelector('input[name="file_bukti"]');
    const linkInput = form.querySelector('input[name="gdrive_link"]');
    
    const hasFile = fileInput && fileInput.files && fileInput.files.length > 0;
    const hasLink = linkInput && linkInput.value.trim();
    
    return hasFile || hasLink;
}

/**
 * Show File Evidence Error
 */
function showFileEvidenceError(form, message) {
    // Clear existing errors first
    clearFileEvidenceError(form);
    
    const fileInput = form.querySelector('input[name="file_bukti"]');
    const linkInput = form.querySelector('input[name="gdrive_link"]');
    
    // Add error classes
    if (fileInput) {
        fileInput.classList.add('is-invalid');
    }
    if (linkInput) {
        linkInput.classList.add('is-invalid');
    }
    
    // Create error message element
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-danger mt-3 file-evidence-error';
    errorDiv.innerHTML = `<i class="ti ti-alert-triangle me-2"></i>${message}`;
    
    // Insert error message after gdrive link input group
    const gdriveGroup = linkInput ? linkInput.closest('.mb-3') : fileInput.closest('.mb-3');
    if (gdriveGroup) {
        gdriveGroup.insertAdjacentElement('afterend', errorDiv);
    }
    
    // Scroll to error
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

/**
 * Clear File Evidence Error
 */
function clearFileEvidenceError(form) {
    // Remove error classes
    const fileInput = form.querySelector('input[name="file_bukti"]');
    const linkInput = form.querySelector('input[name="gdrive_link"]');
    
    if (fileInput) {
        fileInput.classList.remove('is-invalid');
    }
    if (linkInput) {
        linkInput.classList.remove('is-invalid');
    }
    
    // Remove error message
    const errorAlert = form.querySelector('.file-evidence-error');
    if (errorAlert) {
        errorAlert.remove();
    }
}

/**
 * Initialize Edit Form
 */
function initEditForm() {
    // Handle edit button clicks using event delegation
    document.addEventListener('click', function(e) {
        const editButton = e.target.closest('button[data-bs-target="#editAktivitasModal"]');

        if (editButton) {
            e.preventDefault();
            const aktivitasId = editButton.getAttribute('data-id');

            if (aktivitasId) {
                loadAktivitasData(aktivitasId, editButton);
            } else {
                alert('ID aktivitas tidak ditemukan');
            }
        }
    });
}

/**
 * Initialize Delete Handlers
 */
function initDeleteHandlers() {
    document.addEventListener('click', function(e) {
        // Handle delete from form submission (existing functionality)
        const deleteForm = e.target.closest('form[action*="aktivitas"][onsubmit*="confirm"]');
        if (deleteForm && e.target.type === 'submit') {
            // Let the form's onsubmit handle the confirmation
            return true;
        }
    });
}

/**
 * Initialize File Preview
 */
function initFilePreview() {
    // File preview is handled by the global showFilePreview function
    // which is called from onclick attributes in the blade template
}

/**
 * Simple Search Functionality
 */
function initSearch() {
    const searchInput = document.querySelector('input[placeholder="Cari aktivitas..."]');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const tableBody = document.querySelector('tbody');
            const rows = tableBody.querySelectorAll('tr');
            let visibleRows = 0;

            // Filter rows
            rows.forEach(row => {
                // Skip no-results row
                if (row.classList.contains('no-results-row')) {
                    return;
                }

                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle no results message
            let noResultsRow = tableBody.querySelector('.no-results-row');
            
            if (visibleRows === 0 && searchTerm !== '') {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.className = 'no-results-row';
                    noResultsRow.innerHTML = `
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="ti ti-search-off mb-2" style="font-size: 2rem;"></i>
                                <p class="mb-0">Tidak ada aktivitas yang cocok dengan pencarian "${searchTerm}"</p>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(noResultsRow);
                }
                noResultsRow.style.display = '';
            } else {
                if (noResultsRow) {
                    noResultsRow.style.display = 'none';
                }
            }
        });
    }
}

/**
 * Load Activity Data for Edit Form
 */
function loadAktivitasData(aktivitasId, editButton) {
    try {
        // Get data from button attributes (fallback method)
        const nama = editButton.getAttribute('data-nama') || '';
        const hobi = editButton.getAttribute('data-hobi') || '';
        const durasi = editButton.getAttribute('data-durasi') || '';
        const catatan = editButton.getAttribute('data-catatan') || '';
        const fileBukti = editButton.getAttribute('data-file-bukti') || '';

        // Fill form fields
        document.getElementById('editNamaAktivitas').value = nama;
        document.getElementById('editDurasiMenit').value = durasi;
        document.getElementById('editCatatanAktivitas').value = catatan === 'Tidak ada catatan' ? '' : catatan;

        // Set hobi selection
        const hobiSelect = document.getElementById('editPilihHobi');
        for (let option of hobiSelect.options) {
            if (option.text.trim() === hobi.trim()) {
                option.selected = true;
                break;
            }
        }

        // Clear file inputs first
        document.getElementById('editFileBukti').value = '';
        document.getElementById('editGdriveLink').value = '';

        // Handle file data (now stored as JSON)
        try {
            const fileData = fileBukti ? JSON.parse(fileBukti) : {};

            // If there's a GDrive link, populate it
            if (fileData.gdrive) {
                document.getElementById('editGdriveLink').value = fileData.gdrive;
            }
            // Note: We don't populate the file input as browsers don't allow setting file input values for security reasons
            // The user will need to re-upload if they want to change the file
        } catch (e) {
            console.warn('Failed to parse file_bukti data:', e);
        }

        // Set form action
        const editForm = document.getElementById('editAktivitasForm');
        const baseUrl = editForm.getAttribute('data-base-url') || '/aktivitas';
        editForm.action = `${baseUrl}/${aktivitasId}`;

        // Store ID for reference
        document.getElementById('editAktivitasModal').setAttribute('data-aktivitas-id', aktivitasId);

        // Clear any existing validation errors
        clearFileEvidenceError(editForm);

    } catch (error) {
        console.error('Error loading aktivitas data:', error);
        alert('Gagal memuat data aktivitas');
    }
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
        modalTitle.innerHTML = '<i class="ti ti-brand-google-drive me-2"></i>File dari Google Drive';
        modalBody.innerHTML = `
            <div class="text-center">
                <i class="ti ti-brand-google-drive text-primary mb-3" style="font-size: 4rem;"></i>
                <h5 class="mb-3">File disimpan di Google Drive</h5>
                <p class="text-muted mb-4">Klik tombol di bawah untuk membuka file di Google Drive</p>
                <a href="${fileUrl}" target="_blank" class="btn btn-primary btn-lg">
                    <i class="ti ti-external-link me-2"></i>Buka di Google Drive
                </a>
            </div>
        `;
    } else if (fileType === 'image') {
        modalTitle.innerHTML = '<i class="ti ti-photo me-2"></i>Preview Gambar';
        modalBody.innerHTML = `
            <div class="text-center">
                <img src="${fileUrl}" class="img-fluid rounded shadow" style="max-height: 500px; max-width: 100%;" alt="Preview gambar bukti aktivitas">
                <div class="mt-3">
                    <a href="${fileUrl}" target="_blank" class="btn btn-outline-primary">
                        <i class="ti ti-external-link me-2"></i>Buka di Tab Baru
                    </a>
                </div>
            </div>
        `;
    } else if (fileType === 'video') {
        modalTitle.innerHTML = '<i class="ti ti-video me-2"></i>Preview Video';
        modalBody.innerHTML = `
            <div class="text-center">
                <video controls class="rounded shadow" style="max-height: 500px; max-width: 100%;">
                    <source src="${fileUrl}" type="video/mp4">
                    <source src="${fileUrl}" type="video/quicktime">
                    <source src="${fileUrl}" type="video/x-msvideo">
                    Browser Anda tidak mendukung pemutaran video ini.
                </video>
                <div class="mt-3">
                    <a href="${fileUrl}" target="_blank" class="btn btn-outline-primary">
                        <i class="ti ti-download me-2"></i>Download Video
                    </a>
                </div>
            </div>
        `;
    } else {
        modalTitle.innerHTML = '<i class="ti ti-file me-2"></i>File Bukti';
        modalBody.innerHTML = `
            <div class="text-center">
                <i class="ti ti-file text-muted mb-3" style="font-size: 4rem;"></i>
                <h5 class="mb-3">File tidak dapat dipratinjau</h5>
                <p class="text-muted mb-4">File ini tidak dapat ditampilkan di browser. Silakan download untuk melihat isinya.</p>
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
 * Handle Success Messages Auto-hide
 */
function handleSuccessMessages() {
    const successAlerts = document.querySelectorAll('.alert-success');
    successAlerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000); // Auto-hide after 5 seconds
    });
}

/**
 * Form Reset Helper
 */
function resetForm(formElement) {
    if (formElement) {
        formElement.reset();
        
        // Clear validation errors
        formElement.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        
        formElement.querySelectorAll('.invalid-feedback').forEach(el => {
            el.remove();
        });
        
        clearFileEvidenceError(formElement);
    }
}

/**
 * Modal Helper Functions
 */
function closeModal(modalId) {
    const modalElement = document.getElementById(modalId);
    if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }
    }
}

/**
 * File Size Validation Helper
 */
function validateFileSize(fileInput, maxSizeMB = 50) {
    if (!fileInput.files || fileInput.files.length === 0) {
        return true; // No file selected is OK
    }
    
    const file = fileInput.files[0];
    const maxSizeBytes = maxSizeMB * 1024 * 1024;
    
    if (file.size > maxSizeBytes) {
        alert(`Ukuran file terlalu besar! Maksimal ${maxSizeMB}MB. Ukuran file Anda: ${(file.size / 1024 / 1024).toFixed(2)}MB`);
        fileInput.value = ''; // Clear the input
        return false;
    }
    
    return true;
}

/**
 * URL validation helper
 */
function isValidGoogleDriveUrl(url) {
    if (!url) return true; // Empty is OK
    
    const gdrivePattern = /^https:\/\/(drive|docs)\.google\.com\//;
    return gdrivePattern.test(url);
}

// Additional event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Add file size validation
    const fileInputs = document.querySelectorAll('input[type="file"][name="file_bukti"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            validateFileSize(this, 50); // 50MB max
        });
    });
    
    // Handle success messages
    handleSuccessMessages();
    
    // Reset forms when modals are hidden
    document.getElementById('tambahAktivitasModal')?.addEventListener('hidden.bs.modal', function() {
        resetForm(this.querySelector('form'));
    });
    
    document.getElementById('editAktivitasModal')?.addEventListener('hidden.bs.modal', function() {
        resetForm(this.querySelector('form'));
    });

    // Add Google Drive URL validation
    const gdriveInputs = document.querySelectorAll('input[name="gdrive_link"]');
    
    gdriveInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !isValidGoogleDriveUrl(this.value)) {
                this.classList.add('is-invalid');
                
                // Remove existing feedback
                const existingFeedback = this.parentNode.querySelector('.invalid-feedback');
                if (existingFeedback) {
                    existingFeedback.remove();
                }
                
                // Add feedback
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'URL harus berupa link Google Drive yang valid (https://drive.google.com/...)';
                this.parentNode.appendChild(feedback);
            } else {
                this.classList.remove('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.remove();
                }
            }
        });
    });
});

// Global functions
window.showFilePreview = showFilePreview;

// Export functions for potential external use
window.AktivitasManager = {
    validateFileEvidence,
    showFilePreview,
    resetForm,
    closeModal,
    validateFileSize,
    isValidGoogleDriveUrl
};