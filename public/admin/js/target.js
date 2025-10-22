$(document).ready(function() {
    // Handle edit target modal form submission
    $('form[action*="target"][method*="POST"]:has(input[name="_method"][value="PUT"])').on('submit', function(e) {
        var deadlineInput = $(this).find('input[name="target_deadline"]');
        var deadline = new Date(deadlineInput.val());
        var today = new Date();
        today.setHours(0,0,0,0);

        var yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);

        if (deadline <= yesterday) {
            alert('Batas waktu harus setelah hari kemarin.');
            e.preventDefault();
            return false;
        }
    });

    // Handle add target modal form submission
    $('#tambahTargetModal form').on('submit', function(e) {
        var deadlineInput = $(this).find('input[name="target_deadline"]');
        var deadline = new Date(deadlineInput.val());
        var today = new Date();
        today.setHours(0,0,0,0);

        var yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);

        if (deadline <= yesterday) {
            alert('Batas waktu harus setelah hari kemarin.');
            e.preventDefault();
            return false;
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Check for expired targets on page load - SKIP COMPLETED TARGETS
    checkExpiredTargets();
    
    function checkExpiredTargets() {
        var today = new Date();
        today.setHours(0,0,0,0);
        
        $('table tbody tr').each(function() {
            var row = $(this);
            var statusBadge = row.find('.badge');
            
            // SKIP if status is completed
            if (statusBadge.text().includes('Completed')) {
                return; // continue to next row
            }
            
            var deadlineText = row.find('td').eq(4).text().trim();
            
            if (deadlineText && deadlineText !== 'N/A') {
                // Parse date format "dd Mon yyyy"
                var deadlineDate = parseDeadlineDate(deadlineText);
                
                if (deadlineDate && deadlineDate < today) {
                    // Mark row as expired only if not completed
                    row.addClass('table-warning');
                }
            }
        });
    }
    
    function parseDeadlineDate(dateStr) {
        var months = {
            'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'May': 4, 'Jun': 5,
            'Jul': 6, 'Aug': 7, 'Sep': 8, 'Oct': 9, 'Nov': 10, 'Dec': 11,
            'January': 0, 'February': 1, 'March': 2, 'April': 3, 'May': 4, 'June': 5,
            'July': 6, 'August': 7, 'September': 8, 'October': 9, 'November': 10, 'December': 11,
            'Januari': 0, 'Februari': 1, 'Maret': 2, 'April': 3, 'Mei': 4, 'Juni': 5,
            'Juli': 6, 'Agustus': 7, 'September': 8, 'Oktober': 9, 'November': 10, 'Desember': 11
        };
        
        // Extract date parts using icon tag
        var parts = dateStr.replace(/\s+/g, ' ').trim().split(' ');
        
        // Find numeric parts and month name
        var day, month, year;
        for (var i = 0; i < parts.length; i++) {
            var part = parts[i];
            if (!isNaN(part) && part.length <= 2) {
                day = parseInt(part);
            } else if (months[part] !== undefined) {
                month = months[part];
            } else if (!isNaN(part) && part.length === 4) {
                year = parseInt(part);
            }
        }
        
        if (day && month !== undefined && year) {
            return new Date(year, month, day);
        }
        return null;
    }

    // Initialize form validation on modal show
    $('.modal').on('shown.bs.modal', function() {
        var modal = $(this);
        var form = modal.find('form');

        // Reset form validation states
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
    });

    // Functions for onclick
    window.confirmDelete = function(button) {
        if (confirm('Yakin ingin menghapus target ini?')) {
            button.closest('form').submit();
        }
    };

    // Auto-reload jika ada session success
    if (typeof hasSuccess !== 'undefined' && hasSuccess) {
        setTimeout(function() {
            location.reload();
        }, 1500);
    }

    // Show modal if there are validation errors
    if (typeof showModal !== 'undefined' && showModal) {
        if (showModal === 'tambah') {
            $('#tambahTargetModal').modal('show');
        } else if (showModal === 'edit' && typeof targetId !== 'undefined' && targetId) {
            $('#editTargetModal' + targetId).modal('show');
        }
    }

    // Auto-update expired targets - SKIP COMPLETED TARGETS
    setInterval(function() {
        var currentDate = new Date();
        currentDate.setHours(0, 0, 0, 0);

        $('table tbody tr').each(function() {
            var row = $(this);
            var statusBadge = row.find('.badge');

            // SKIP if status is completed
            if (statusBadge.text().includes('Completed')) {
                return; // continue to next row
            }

            var deadlineText = row.find('td').eq(4).text().trim();

            if (deadlineText && !deadlineText.includes('EXPIRED')) {
                var deadline = parseDeadlineDate(deadlineText);
                if (deadline && deadline < currentDate) {
                    // Mark as expired only if not completed
                    row.addClass('table-danger');
                    if (statusBadge.hasClass('bg-warning-subtle') || statusBadge.hasClass('bg-info-subtle')) {
                        statusBadge.removeClass('bg-warning-subtle text-warning bg-info-subtle text-info')
                            .addClass('bg-danger-subtle text-danger')
                            .html('<i class="ti ti-x-circle me-1"></i>Expired');
                    }
                }
            }
        });
    }, 60000); // Check every minute

    // File modal functionality
    window.openFileModal = function(fileUrl, fileType, title) {
        let modalContent = '';
        let modalTitle = '';
        let modalHtml = '';

        if (fileType === 'image') {
            modalTitle = `<i class="ti ti-photo me-2"></i>${title}`;
            modalContent = `<img src="${fileUrl}" class="img-fluid" alt="Bukti Aktivitas" style="max-height: 70vh;">`;
            modalHtml = `
                <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-dark text-white border-0">
                                <h5 class="modal-title text-white" id="fileModalLabel">${modalTitle}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0 text-center bg-dark">
                                ${modalContent}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else if (fileType === 'video') {
            modalTitle = `<i class="ti ti-video me-2"></i>${title}`;
            const extension = fileUrl.split('.').pop().toLowerCase();
            const mimeType = extension === 'mov' ? 'video/quicktime' : (extension === 'avi' ? 'video/avi' : 'video/mp4');
            modalContent = `<video controls class="w-100" style="max-height: 70vh;"><source src="${fileUrl}" type="${mimeType}">Browser Anda tidak mendukung pemutaran video.</video>`;
            modalHtml = `
                <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-dark text-white border-0">
                                <h5 class="modal-title text-white" id="fileModalLabel">${modalTitle}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0 text-center bg-dark">
                                ${modalContent}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else if (fileType === 'gdrive') {
            modalTitle = `<i class="ti ti-brand-google-drive me-2"></i>${title}`;
            modalContent = `
                <div class="text-center py-5">
                    <i class="ti ti-brand-google-drive text-info mb-3" style="font-size: 4rem;"></i>
                    <h5>File tersimpan di Google Drive</h5>
                    <p class="text-muted mb-4">Klik tombol di bawah untuk membuka file di Google Drive</p>
                    <a href="${fileUrl}" target="_blank" class="btn btn-primary">
                        <i class="ti ti-external-link me-2"></i>Buka di Google Drive
                    </a>
                </div>
            `;
            modalHtml = `
                <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-light text-dark border-0">
                                <h5 class="modal-title" id="fileModalLabel">${modalTitle}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0 text-center bg-light">
                                ${modalContent}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            modalTitle = `<i class="ti ti-file-text me-2"></i>${title}`;
            modalContent = `
                <div class="text-center py-5">
                    <i class="ti ti-file-text fs-1 text-muted mb-3"></i>
                    <h5>File tidak dapat dipreview</h5>
                    <p class="text-muted mb-4">File ini tidak dapat ditampilkan di browser</p>
                    <a href="${fileUrl}" target="_blank" class="btn btn-primary">
                        <i class="ti ti-download me-2"></i>Download File
                    </a>
                </div>
            `;
            modalHtml = `
                <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-dark text-white border-0">
                                <h5 class="modal-title text-white" id="fileModalLabel">${modalTitle}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0 text-center bg-dark">
                                ${modalContent}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Remove existing modal if present
        const existingModal = document.getElementById('fileModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Append modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('fileModal'));
        modal.show();
    };
});
