// Settings page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Logo preview
    const logoInput = document.getElementById('site_logo');
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const logoImg = document.querySelector('#general img');
                    if (logoImg) {
                        logoImg.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Favicon preview
    const faviconInput = document.getElementById('favicon');
    if (faviconInput) {
        faviconInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const faviconImg = document.querySelector('#general img[alt="Favicon"]');
                    if (faviconImg) {
                        faviconImg.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Save settings functionality
    const saveSettingsBtn = document.getElementById('saveSettingsBtn');
    if (saveSettingsBtn) {
        saveSettingsBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="ti ti-loader-2 me-2"></i>Menyimpan...';
            this.disabled = true;

            // Get form data
            const formData = new FormData(document.getElementById('settingsForm'));

            // Send AJAX request
            fetch('/setting/save-settings', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Reset button state
                saveSettingsBtn.innerHTML = originalText;
                saveSettingsBtn.disabled = false;

                if (data.success) {
                    // Show success message
                    showAlert('success', data.message || 'Pengaturan berhasil disimpan!');
                } else {
                    showAlert('error', data.message || 'Gagal menyimpan pengaturan');
                }
            })
            .catch(error => {
                // Reset button state
                saveSettingsBtn.innerHTML = originalText;
                saveSettingsBtn.disabled = false;

                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat menyimpan pengaturan: ' + error.message);
            });
        });
    }

    // Alert function
    function showAlert(type, message) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert-custom');
        existingAlerts.forEach(alert => alert.remove());

        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show alert-custom`;
        alertDiv.innerHTML = `
            <i class="ti ti-${type === 'success' ? 'check-circle' : 'alert-circle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Insert at top of container
        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Tab switching enhancement
    const tabs = document.querySelectorAll('#settingsTab .nav-link');
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            console.log('Tab switched to:', e.target.id);
        });
    });

    // Hobby categories modal logic
    const addCategoryBtn = document.getElementById('addCategoryBtn');
    const newCategoryNameInput = document.getElementById('new_category_name');
    const newCategoryIconInput = document.getElementById('new_category_icon');
    const newCategoryColorInput = document.getElementById('new_category_color');
    const categoriesList = document.getElementById('categoriesList');

    function removeCategory(categoryId) {
        if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
            fetch(`/setting/remove-category/${categoryId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Remove the item from the list
                    const item = document.querySelector(`[data-id="${categoryId}"]`);
                    if (item) {
                        item.remove();
                    }
                    showAlert('success', data.message);
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat menghapus kategori: ' + error.message);
            });
        }
    }
    window.removeCategory = removeCategory;

    if (addCategoryBtn && newCategoryNameInput && newCategoryIconInput && newCategoryColorInput && categoriesList) {
        addCategoryBtn.addEventListener('click', () => {
            const categoryName = newCategoryNameInput.value.trim();
            const categoryIcon = newCategoryIconInput.value;
            const categoryColor = newCategoryColorInput.value;

            if (categoryName === '') {
                showAlert('error', 'Nama kategori tidak boleh kosong.');
                return;
            }
            if (categoryIcon === '') {
                showAlert('error', 'Silakan pilih icon untuk kategori.');
                return;
            }
            if (categoryColor === '') {
                showAlert('error', 'Silakan pilih warna untuk kategori.');
                return;
            }

            // Check for duplicates
            const existingCategories = Array.from(categoriesList.children).map(el =>
                el.querySelector('span').textContent.trim()
            );
            if (existingCategories.includes(categoryName)) {
                showAlert('error', 'Kategori sudah ada.');
                return;
            }

            // Disable button while processing
            const originalText = addCategoryBtn.innerHTML;
            addCategoryBtn.innerHTML = '<i class="ti ti-loader-2 me-1"></i>Menambah...';
            addCategoryBtn.disabled = true;

            // Send AJAX request to add category
            fetch('/setting/add-category', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    nama_kategori: categoryName,
                    icon: categoryIcon,
                    background_color: categoryColor
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Reset button
                addCategoryBtn.innerHTML = originalText;
                addCategoryBtn.disabled = false;

                if (data.success && data.category && data.category.id) {
                    // Create new category item
                    const newItem = document.createElement('div');
                    newItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                    newItem.setAttribute('data-id', data.category.id);

                    const itemContent = document.createElement('div');
                    itemContent.className = 'd-flex align-items-center';

                    const icon = document.createElement('i');
                    icon.className = `ti ${categoryIcon} me-2`;
                    itemContent.appendChild(icon);

                    const nameSpan = document.createElement('span');
                    nameSpan.textContent = categoryName;
                    itemContent.appendChild(nameSpan);

                    const badge = document.createElement('span');
                    badge.className = `badge ${categoryColor} ms-2`;
                    badge.textContent = '0 hobi';
                    itemContent.appendChild(badge);

                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'btn btn-sm btn-outline-danger';
                    removeBtn.type = 'button';
                    removeBtn.innerHTML = '<i class="ti ti-trash"></i>';
                    removeBtn.onclick = () => removeCategory(data.category.id);

                    newItem.appendChild(itemContent);
                    newItem.appendChild(removeBtn);
                    categoriesList.appendChild(newItem);

                    // Clear form
                    newCategoryNameInput.value = '';
                    newCategoryIconInput.value = '';
                    newCategoryColorInput.value = '';

                    showAlert('success', data.message);
                } else {
                    showAlert('error', data.message || 'Gagal menambahkan kategori');
                }
            })
            .catch(error => {
                // Reset button
                addCategoryBtn.innerHTML = originalText;
                addCategoryBtn.disabled = false;

                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat menambahkan kategori: ' + error.message);
            });
        });
    }
});
