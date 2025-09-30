document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#logs-table tbody tr');

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

function loadDetail(id, type = 'aktivitas') {
    // Show loading and hide content
    document.getElementById('detail-loading').classList.remove('d-none');
    document.getElementById('detail-content').classList.add('d-none');

    const url = type === 'target' ? `/logs/target/${id}` : `/log-aktivitas/${id}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal memuat data');
            }
            return response.json();
        })
        .then(data => {
            // Update modal fields based on type
            document.getElementById('detail-tanggal').textContent = data.tanggal;
            document.getElementById('detail-waktu-upload').textContent = data.waktu_upload;
            
            if (type === 'target') {
                // Update for target
                document.getElementById('detail-aktivitas').parentElement.querySelector('dt').textContent = 'Target';
                document.getElementById('detail-aktivitas').textContent = data.target;
                document.getElementById('detail-hobi').textContent = data.hobi;
                
                // Change durasi to status for target
                document.getElementById('detail-durasi').parentElement.querySelector('dt').textContent = 'Status';
                document.getElementById('detail-durasi').innerHTML = getStatusBadge(data.status);
                
                // Add deadline field if not exists
                let deadlineRow = document.getElementById('detail-deadline-row');
                if (!deadlineRow) {
                    const durasiRow = document.getElementById('detail-durasi').parentElement;
                    deadlineRow = document.createElement('div');
                    deadlineRow.className = 'row';
                    deadlineRow.id = 'detail-deadline-row';
                    deadlineRow.innerHTML = '<dt class="col-sm-3">Deadline</dt><dd class="col-sm-9" id="detail-deadline">-</dd>';
                    durasiRow.parentElement.insertBefore(deadlineRow, durasiRow.nextSibling);
                }
                document.getElementById('detail-deadline').textContent = data.deadline;
            } else {
                // Update for aktivitas
                document.getElementById('detail-aktivitas').parentElement.querySelector('dt').textContent = 'Aktivitas';
                document.getElementById('detail-aktivitas').textContent = data.aktivitas;
                document.getElementById('detail-hobi').textContent = data.hobi;
                document.getElementById('detail-durasi').parentElement.querySelector('dt').textContent = 'Durasi';
                document.getElementById('detail-durasi').textContent = data.durasi;
                
                // Remove deadline row if exists
                const deadlineRow = document.getElementById('detail-deadline-row');
                if (deadlineRow) {
                    deadlineRow.remove();
                }
            }

            document.getElementById('detail-catatan').textContent = data.catatan || 'tidak ada catatan';

            // Update bukti
            const buktiElement = document.getElementById('detail-bukti');
            if (data.bukti && data.bukti.length > 0) {
                let buktiHtml = '<div class="row g-3">';
                data.bukti.forEach(bukti => {
                    if (typeof bukti === 'string') {
                        if (bukti.includes('drive.google.com')) {
                            buktiHtml += `
                            <div class="col-12 col-md-6">
                                <a href="${bukti}" target="_blank" class="text-decoration-none">
                                    <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 100%; max-width: 500px; height: 300px; cursor: pointer;">
                                        <i class="ti ti-link text-primary" style="font-size: 5rem;"></i>
                                    </div>
                                    <small class="text-muted d-block text-center mt-1">Google Drive</small>
                                </a>
                            </div>
                        `;
                        } else if (bukti.includes('.jpg') || bukti.includes('.png') || bukti.includes('.jpeg') || bukti.includes('.gif')) {
                            buktiHtml += `
                            <div class="col-12 col-md-6">
                                <img src="${bukti}" alt="Bukti" class="img-fluid rounded shadow-sm" style="width: 100%; max-width: 500px; height: 300px; object-fit: cover;">
                                <small class="text-muted d-block text-center mt-1">Gambar Bukti</small>
                            </div>
                        `;
                        } else if (bukti.includes('.mp4') || bukti.includes('.avi') || bukti.includes('.mov') || bukti.includes('.webm')) {
                            buktiHtml += `
                            <div class="col-12 col-md-6">
                                <video controls class="rounded shadow-sm" style="width: 100%; max-width: 500px; height: 300px; object-fit: contain;" title="Klik untuk Fullscreen">
                                    <source src="${bukti}" type="video/mp4">
                                    Browser tidak mendukung video.
                                </video>
                                <small class="text-muted d-block text-center mt-1">Video Bukti</small>
                            </div>
                        `;
                        } else if (bukti.includes('.pdf')) {
                            buktiHtml += `
                            <div class="col-12 col-md-6">
                                <a href="${bukti}" target="_blank" class="text-decoration-none">
                                    <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 100%; max-width: 500px; height: 300px; cursor: pointer;">
                                        <i class="ti ti-file-text text-danger" style="font-size: 5rem;"></i>
                                    </div>
                                    <small class="text-muted d-block text-center mt-1">PDF Bukti</small>
                                </a>
                            </div>
                        `;
                        } else {
                            buktiHtml += `
                            <div class="col-12 col-md-6">
                                <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 100%; max-width: 500px; height: 300px;">
                                    <i class="ti ti-file text-muted" style="font-size: 5rem;"></i>
                                </div>
                                <small class="text-muted d-block text-center mt-1">File Bukti</small>
                            </div>
                        `;
                        }
                    } else {
                        buktiHtml += `
                        <div class="col-12 col-md-6">
                            <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 100%; max-width: 500px; height: 300px;">
                                <i class="ti ti-file text-muted" style="font-size: 5rem;"></i>
                            </div>
                            <small class="text-muted d-block text-center mt-1">File Bukti</small>
                        </div>
                    `;
                    }
                });
                buktiHtml += '</div>';
                buktiElement.innerHTML = buktiHtml;
            } else {
                buktiElement.innerHTML = '<p class="text-muted">Tidak ada bukti tersedia.</p>';
            }

            // Hide loading and show content
            document.getElementById('detail-loading').classList.add('d-none');
            document.getElementById('detail-content').classList.remove('d-none');
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('detail-loading').classList.add('d-none');
            document.getElementById('detail-content').innerHTML =
                '<div class="alert alert-danger">Gagal memuat detail log. Silakan coba lagi.</div>';
            document.getElementById('detail-content').classList.remove('d-none');
        });
}

function getStatusBadge(status) {
    const statusLower = status.toLowerCase();
    if (statusLower === 'completed') {
        return '<span class="badge bg-success-subtle text-success px-3 py-2"><i class="ti ti-check-circle me-1"></i>Completed</span>';
    } else if (statusLower === 'failed') {
        return '<span class="badge bg-danger-subtle text-danger px-3 py-2"><i class="ti ti-x-circle me-1"></i>Failed</span>';
    } else {
        return '<span class="badge bg-warning-subtle text-warning px-3 py-2"><i class="ti ti-clock me-1"></i>On Progress</span>';
    }
}