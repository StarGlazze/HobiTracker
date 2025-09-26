$(document).ready(function() {
    // Handle edit target modal form submission
    $('form[action*="target"][method*="POST"]:has(input[name="_method"][value="PUT"])').on('submit', function(e) {
        var deadlineInput = $(this).find('input[name="target_deadline"]');
        var deadline = new Date(deadlineInput.val());
        var today = new Date();
        today.setHours(0,0,0,0);

        if (deadline <= today) {
            alert('Batas waktu harus setelah hari ini.');
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

        if (deadline <= today) {
            alert('Batas waktu harus setelah hari ini.');
            e.preventDefault();
            return false;
        }
    });

    // Validate progress form on submit - check for both add and edit progress forms
    $('form[action*="progres.store"], form[action*="progres.update"]').on('submit', function(e) {
        var fileInput = $(this).find('input[name="file_bukti"]');
        var linkInput = $(this).find('input[name="link_gdrive"]');
        var fileVal = fileInput.val();
        var linkVal = linkInput.val().trim();
        
        // For edit forms, check if there's existing evidence
        var isEditForm = $(this).find('input[name="_method"][value="PUT"]').length > 0;
        var hasExistingFile = $(this).find('small:contains("File saat ini")').length > 0;
        var hasExistingLink = isEditForm && linkInput.attr('value') && linkInput.attr('value').trim() !== '';

        // Check if at least one evidence exists (new file, new link, or existing evidence)
        if (!fileVal && !linkVal && !hasExistingFile && !hasExistingLink) {
            alert('Harus upload file bukti atau isi link Google Drive.');
            e.preventDefault();
            return false;
        }

        // Check file size if new file is selected
        if (fileInput[0] && fileInput[0].files.length > 0) {
            var fileSize = fileInput[0].files[0].size / 1024 / 1024; // MB
            if (fileSize > 5) {
                alert('Ukuran file maksimal 5MB.');
                e.preventDefault();
                return false;
            }
        }
    });

    // Auto-update status based on evidence submission
    $('select[name="status"]').on('change', function() {
        var form = $(this).closest('form');
        var fileInput = form.find('input[name="file_bukti"]');
        var linkInput = form.find('input[name="link_gdrive"]');
        var statusSelect = $(this);
        
        // If user selects completed, ensure they have evidence
        if (statusSelect.val() === 'completed') {
            var fileVal = fileInput.val();
            var linkVal = linkInput.val().trim();
            var hasExistingFile = form.find('small:contains("File saat ini")').length > 0;
            var hasExistingLink = linkInput.attr('value') && linkInput.attr('value').trim() !== '';
            
            if (!fileVal && !linkVal && !hasExistingFile && !hasExistingLink) {
                alert('Status "Completed" memerlukan file bukti atau link Google Drive.');
                statusSelect.val('on_progress');
            }
        }
    });

    // When file is uploaded or link is added, auto-update status to completed
    $('input[name="file_bukti"], input[name="link_gdrive"]').on('change input', function() {
        var form = $(this).closest('form');
        var fileInput = form.find('input[name="file_bukti"]');
        var linkInput = form.find('input[name="link_gdrive"]');
        var statusSelect = form.find('select[name="status"]');
        
        var hasFile = fileInput[0] && fileInput[0].files.length > 0;
        var hasLink = linkInput.val().trim() !== '';
        var hasExistingFile = form.find('small:contains("File saat ini")').length > 0;
        var hasExistingLink = linkInput.attr('value') && linkInput.attr('value').trim() !== '';
        
        // Auto-set to completed if evidence is provided and current status is on_progress
        if ((hasFile || hasLink || hasExistingFile || hasExistingLink) && statusSelect.val() === 'on_progress') {
            statusSelect.val('completed');
        }
        
        // Reset to on_progress if no evidence and status was completed
        if (!hasFile && !hasLink && !hasExistingFile && !hasExistingLink && statusSelect.val() === 'completed') {
            statusSelect.val('on_progress');
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Check for expired targets on page load
    checkExpiredTargets();
    
    function checkExpiredTargets() {
        var today = new Date();
        today.setHours(0,0,0,0);
        
        $('table tbody tr').each(function() {
            var row = $(this);
            var deadlineText = row.find('td:nth-child(5)').text().trim();
            
            if (deadlineText && deadlineText !== 'N/A') {
                // Parse Indonesian date format "dd Month yyyy"
                var deadlineDate = parseIndonesianDate(deadlineText);
                
                if (deadlineDate && deadlineDate < today) {
                    // Mark row as expired
                    row.addClass('table-warning');
                    row.find('td:nth-child(5)').append(' <small class="text-danger fw-bold">(EXPIRED)</small>');
                }
            }
        });
    }
    
    function parseIndonesianDate(dateStr) {
        var months = {
            'January': 0, 'February': 1, 'March': 2, 'April': 3, 'May': 4, 'June': 5,
            'July': 6, 'August': 7, 'September': 8, 'October': 9, 'November': 10, 'December': 11,
            'Januari': 0, 'Februari': 1, 'Maret': 2, 'April': 3, 'Mei': 4, 'Juni': 5,
            'Juli': 6, 'Agustus': 7, 'September': 8, 'Oktober': 9, 'November': 10, 'Desember': 11
        };
        
        var parts = dateStr.split(' ');
        if (parts.length === 3) {
            var day = parseInt(parts[0]);
            var month = months[parts[1]];
            var year = parseInt(parts[2]);
            
            if (!isNaN(day) && month !== undefined && !isNaN(year)) {
                return new Date(year, month, day);
            }
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
        
        // Set default status for new progress
        if (modal.attr('id').includes('progressModal') && !modal.attr('id').includes('edit')) {
            form.find('select[name="status"]').val('on_progress');
        }
    });

    // Simple Search functionality
    $('input[placeholder*="Cari target"]').on('input', function() {
        var searchTerm = $(this).val().toLowerCase().trim();
        var tableBody = $(this).closest('.card').find('table tbody');
        var rows = tableBody.find('tr');
        var visibleRows = 0;

        // Filter rows
        rows.each(function() {
            var row = $(this);
            
            // Skip no-results row
            if (row.hasClass('no-results-row')) {
                return;
            }

            var text = row.text().toLowerCase();
            if (text.includes(searchTerm)) {
                row.show();
                visibleRows++;
            } else {
                row.hide();
            }
        });

        // Handle no results message
        var noResultsRow = tableBody.find('.no-results-row');
        
        if (visibleRows === 0 && searchTerm !== '') {
            if (noResultsRow.length === 0) {
                noResultsRow = $('<tr class="no-results-row">' +
                    '<td colspan="7" class="text-center py-4">' +
                        '<div class="text-muted">' +
                            '<i class="ti ti-search-off mb-2" style="font-size: 2rem;"></i>' +
                            '<p class="mb-0">Tidak ada target yang cocok dengan pencarian "' + searchTerm + '"</p>' +
                        '</div>' +
                    '</td>' +
                '</tr>');
                tableBody.append(noResultsRow);
            }
            noResultsRow.show();
        } else {
            if (noResultsRow.length > 0) {
                noResultsRow.hide();
            }
        }
    });

    // Functions for onclick
    window.confirmDelete = function(button) {
        if (confirm('Yakin ingin menghapus target ini? Semua progres terkait juga akan terhapus.')) {
            button.closest('form').submit();
        }
    };

    window.confirmDeleteProgress = function(button) {
        if (confirm('Yakin ingin menghapus progres ini?')) {
            button.closest('form').submit();
        }
    };

    window.viewFile = function(fileUrl, fileName) {
        var fileContent = $('#fileContent');
        fileContent.empty(); // Clear previous content

        var fileExtension = fileName.split('.').pop().toLowerCase();

        if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(fileExtension)) {
            // Display image
            var img = $('<img>', {
                src: fileUrl,
                class: 'img-fluid rounded',
                alt: 'Bukti File'
            });
            fileContent.append(img);
        } else if (fileExtension === 'pdf') {
            // Display PDF
            var iframe = $('<iframe>', {
                src: fileUrl,
                width: '100%',
                height: '500px',
                style: 'border: none;'
            });
            fileContent.append(iframe);
        } else {
            // For other files, show download link
            var link = $('<a>', {
                href: fileUrl,
                target: '_blank',
                class: 'btn btn-primary',
                text: 'Download File'
            });
            fileContent.append('<p>File tidak dapat ditampilkan di browser. </p>').append(link);
        }

        // Update modal title for file view
        $('#fileViewModalLabel').html('<i class="ti ti-eye me-2"></i>Lihat Bukti File');

        $('#fileViewModal').modal('show');
    };

    window.viewGDrive = function(gdriveUrl) {
        var fileContent = $('#fileContent');
        fileContent.empty(); // Clear previous content

        // Simple container matching the image design
        var container = $('<div>', {
            class: 'text-center p-4'
        });

        // Header with Google Drive title
        var header = $('<div>', {
            class: 'mb-4'
        }).append(
            $('<h5>', {
                class: 'text-primary mb-2',
                text: 'File dari Google Drive'
            })
        );

        // Google Drive logo/icon
        var logo = $('<div>', {
            class: 'mb-3'
        }).append(
            $('<i>', {
                class: 'ti ti-brand-google-drive',
                style: 'font-size: 3rem; color: #4285f4;'
            })
        );

        container.append(header, logo);

        // Instruction text
        var instruction = $('<p>', {
            class: 'text-muted mb-4',
            text: 'Klik File di atas untuk membuka file Google Drive'
        });

        container.append(instruction);

        // Simple button to open in Google Drive
        var openButton = $('<a>', {
            href: gdriveUrl,
            target: '_blank',
            class: 'btn btn-primary px-4 py-2'
        }).append(
            $('<i>', { class: 'ti ti-external-link me-2' }),
            'Buka di Google Drive'
        );

        container.append(openButton);

        fileContent.append(container);

        // Update modal title for GDrive
        $('#fileViewModalLabel').html('<i class="ti ti-brand-google-drive me-2 text-info"></i>File dari Google Drive');

        $('#fileViewModal').modal('show');
    };

    // Auto-reload jika ada session success
    if (typeof hasSuccess !== 'undefined' && hasSuccess) {
        setTimeout(function() {
            location.reload();
        }, 1500);
    }

    // Real-time validation for evidence requirement
    $(document).on('change', 'input[name="file_bukti"], input[name="link_gdrive"]', function() {
        var form = $(this).closest('form');
        var fileInput = form.find('input[name="file_bukti"]');
        var linkInput = form.find('input[name="link_gdrive"]');
        var submitBtn = form.find('button[type="submit"]');
        var statusSelect = form.find('select[name="status"]');

        var hasFile = fileInput[0] && fileInput[0].files.length > 0;
        var hasLink = linkInput.val().trim() !== '';
        var hasExistingEvidence = form.find('small:contains("File saat ini")').length > 0;

        // Enable/disable submit button
        if (hasFile || hasLink || hasExistingEvidence) {
            submitBtn.prop('disabled', false);
            // Auto-set status to completed if evidence provided
            if (statusSelect.val() === 'on_progress') {
                statusSelect.val('completed');
            }
        } else {
            submitBtn.prop('disabled', true);
            statusSelect.val('on_progress');
        }

        // Show/hide validation message
        var validationMsg = form.find('.evidence-validation');
        if (validationMsg.length === 0) {
            validationMsg = $('<div class="evidence-validation alert alert-warning mt-2"></div>');
            linkInput.parent().after(validationMsg);
        }

        if (!hasFile && !hasLink && !hasExistingEvidence) {
            validationMsg.html(
                    '<i class="ti ti-alert-circle me-1"></i>Harus upload file bukti atau isi link Google Drive')
                .show();
        } else {
            validationMsg.hide();
        }
    });

    // Auto-update expired targets
    setInterval(function() {
        var currentDate = new Date();
        currentDate.setHours(0, 0, 0, 0);

        $('table tbody tr').each(function() {
            var row = $(this);
            var deadlineText = row.find('td:nth-child(5)').text().trim();

            if (deadlineText && !deadlineText.includes('EXPIRED')) {
                var deadline = parseIndonesianDate(deadlineText);
                if (deadline && deadline < currentDate) {
                    // Mark as expired and update status
                    row.addClass('table-danger');
                    var statusBadge = row.find('.badge');
                    if (statusBadge.hasClass('bg-warning-subtle') || statusBadge.hasClass(
                            'bg-info-subtle')) {
                        statusBadge.removeClass(
                                'bg-warning-subtle text-warning bg-info-subtle text-info')
                            .addClass('bg-danger-subtle text-danger')
                            .html('<i class="ti ti-x-circle me-1"></i>Expired');
                    }
                }
            }
        });
    }, 60000); // Check every minute
});