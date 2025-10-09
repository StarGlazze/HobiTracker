document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM Loaded');
    
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
    console.group('🔍 Loading Detail for ID:', id);
    
    // Get elements
    const loadingEl = document.getElementById('detail-loading');
    const contentEl = document.getElementById('detail-content');
    
    // Validate elements exist
    if (!loadingEl || !contentEl) {
        console.error('❌ Modal elements not found!');
        alert('Error: Modal elements tidak ditemukan. Refresh halaman dan coba lagi.');
        console.groupEnd();
        return;
    }
    
    // Show loading
    loadingEl.classList.remove('d-none');
    contentEl.classList.add('d-none');

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    console.log('🔐 CSRF Token:', csrfToken ? 'Found ✅' : 'Not Found ❌');
    
    // Construct URL
    const url = `/log-aktivitas/${id}`;
    console.log('🌐 Fetching URL:', window.location.origin + url);

    // Prepare headers
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    };
    
    if (csrfToken) {
        headers['X-CSRF-TOKEN'] = csrfToken;
    }

    fetch(url, {
        method: 'GET',
        headers: headers,
        credentials: 'same-origin'
    })
    .then(async response => {
        console.log('📡 Response Status:', response.status);
        console.log('📡 Response OK:', response.ok);
        
        // Get response text first
        const responseText = await response.text();
        console.log('📄 Response (first 500 chars):', responseText.substring(0, 500));
        
        if (!response.ok) {
            console.error('❌ Response not OK');
            
            // Try to parse as JSON
            let errorData;
            try {
                errorData = JSON.parse(responseText);
                console.log('📄 Error Data (JSON):', errorData);
            } catch (e) {
                console.log('📄 Error Data (Text):', responseText);
            }
            
            if (response.status === 404) {
                throw new Error('Data tidak ditemukan (404). ID mungkin tidak valid.');
            } else if (response.status === 403) {
                throw new Error('Anda tidak memiliki akses ke data ini (403).');
            } else if (response.status === 500) {
                throw new Error('Terjadi kesalahan pada server (500). Cek log server.');
            } else if (response.status === 419) {
                throw new Error('CSRF Token expired (419). Refresh halaman dan coba lagi.');
            }
            
            throw new Error(`HTTP Error ${response.status}: ${responseText.substring(0, 100)}`);
        }
        
        // Parse JSON
        let data;
        try {
            data = JSON.parse(responseText);
            console.log('✅ Data parsed successfully:', data);
        } catch (e) {
            console.error('❌ Failed to parse JSON:', e);
            throw new Error('Response bukan JSON valid. Kemungkinan ada error di controller.');
        }
        
        return data;
    })
    .then(data => {
        console.log('✅ Processing data...');
        
        // Update modal fields with null safety
        const fields = {
            'detail-tanggal': data.tanggal || '-',
            'detail-waktu-upload': data.waktu_upload || '-',
            'detail-aktivitas': data.aktivitas || 'tidak ada',
            'detail-target': data.target || 'tidak ada',
            'detail-hobi': data.hobi || 'tidak ada',
            'detail-energy-mood': data.energy_mood_level || '-',
            'detail-catatan': data.catatan || 'tidak ada catatan'
        };
        
        // Update each field safely
        Object.keys(fields).forEach(key => {
            const el = document.getElementById(key);
            if (el) {
                el.innerHTML = fields[key];
                console.log(`✅ Updated ${key}:`, fields[key]);
            } else {
                console.warn(`⚠️ Element not found: ${key}`);
            }
        });

        // Update bukti
        const buktiElement = document.getElementById('detail-bukti');
        if (!buktiElement) {
            console.error('❌ Bukti element not found!');
        } else {
            console.log('🔎 Bukti data:', data.bukti);
            
            if (data.bukti && Array.isArray(data.bukti) && data.bukti.length > 0) {
                let buktiHtml = '<div class="row g-3">';
                data.bukti.forEach((bukti, index) => {
                    console.log(`🔎 Processing bukti ${index}:`, bukti);
                    
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
                        } else if (bukti.match(/\.(jpg|jpeg|png|gif|webp|bmp)$/i)) {
                            buktiHtml += `
                            <div class="col-12 col-md-6">
                                <img src="${bukti}" 
                                     alt="Bukti" 
                                     class="img-fluid rounded shadow-sm" 
                                     style="width: 100%; max-width: 500px; height: 300px; object-fit: cover; cursor: pointer;" 
                                     onclick="showImageModal('${bukti}')">
                                <small class="text-muted d-block text-center mt-1">Gambar Bukti (Klik untuk perbesar)</small>
                            </div>
                        `;
                        } else if (bukti.match(/\.(mp4|avi|mov|webm|mkv)$/i)) {
                            buktiHtml += `
                            <div class="col-12 col-md-6">
                                <video controls class="rounded shadow-sm" style="width: 100%; max-width: 500px; height: 300px; object-fit: contain;">
                                    <source src="${bukti}" type="video/mp4">
                                    Browser tidak mendukung video.
                                </video>
                                <small class="text-muted d-block text-center mt-1">Video Bukti</small>
                            </div>
                        `;
                        } else if (bukti.match(/\.pdf$/i)) {
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
                                <a href="${bukti}" target="_blank" class="text-decoration-none">
                                    <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center" style="width: 100%; max-width: 500px; height: 300px; cursor: pointer;">
                                        <i class="ti ti-file text-muted" style="font-size: 5rem;"></i>
                                    </div>
                                    <small class="text-muted d-block text-center mt-1">File Bukti</small>
                                </a>
                            </div>
                        `;
                        }
                    }
                });
                buktiHtml += '</div>';
                buktiElement.innerHTML = buktiHtml;
                console.log('✅ Bukti HTML updated');
            } else {
                buktiElement.innerHTML = '<p class="text-muted">Tidak ada bukti tersedia.</p>';
                console.log('ℹ️ No bukti available');
            }
        }

        // Hide loading and show content
        loadingEl.classList.add('d-none');
        contentEl.classList.remove('d-none');
        console.log('✅ Modal content displayed');
        console.groupEnd();
    })
    .catch(error => {
        console.error('❌ ERROR:', error);
        console.groupEnd();
        
        loadingEl.classList.add('d-none');
        contentEl.innerHTML = `
            <div class="alert alert-danger">
                <h5 class="alert-heading">
                    <i class="ti ti-alert-circle me-2"></i>
                    Gagal memuat detail log
                </h5>
                <hr>
                <p class="mb-2"><strong>Error:</strong> ${error.message}</p>
                <hr>
                <div class="mb-0">
                    <strong>🔧 Cara Debug:</strong>
                    <ol class="mb-0 mt-2">
                        <li>Buka Developer Tools (tekan F12)</li>
                        <li>Lihat tab "Console" untuk detail error</li>
                        <li>Lihat tab "Network" untuk melihat request/response</li>
                        <li>Cek file <code>storage/logs/laravel.log</code> di server</li>
                    </ol>
                </div>
            </div>
        `;
        contentEl.classList.remove('d-none');
    });
}

/**
 * Show image in fullscreen modal
 * @param {string} imageUrl - URL of the image to display
 */
function showImageModal(imageUrl) {
    // Cek apakah modal sudah ada
    let imageModal = document.getElementById('imageModal');
    
    if (!imageModal) {
        // Buat modal baru
        const modalHtml = `
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content bg-dark border-0">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title text-white" id="imageModalLabel">
                                <i class="ti ti-photo me-2"></i>Bukti Aktivitas
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 text-center">
                            <img id="modalImage" src="" class="img-fluid rounded shadow-lg" alt="Bukti Diperbesar" style="max-height: 80vh; width: auto;">
                        </div>
                        <div class="modal-footer border-0 justify-content-between">
                            <a id="downloadImageBtn" href="" download class="btn btn-sm btn-primary">
                                <i class="ti ti-download me-1"></i>Download
                            </a>
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                                <i class="ti ti-x me-1"></i>Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        imageModal = document.getElementById('imageModal');
    }
    
    // Update gambar dan link download
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('downloadImageBtn').href = imageUrl;
    
    // Show modal
    const modal = new bootstrap.Modal(imageModal);
    modal.show();
    
    console.log('🖼️ Image modal opened:', imageUrl);
}