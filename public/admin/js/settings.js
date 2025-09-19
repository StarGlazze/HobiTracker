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

    // Form validation (basic)
    const forms = document.querySelectorAll('#settingsTabContent form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Add validation logic here if needed
            console.log('Form submitted:', form.id);
        });
    });

    // Tab switching enhancement
    const tabs = document.querySelectorAll('#settingsTab .nav-link');
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            console.log('Tab switched to:', e.target.id);
        });
    });

    // Hobby categories modal logic
    const addCategoryBtn = document.getElementById('addCategoryBtn');
    const newCategoryInput = document.getElementById('new_category');
    const categoriesList = document.getElementById('categoriesList');
    const saveCategoriesBtn = document.getElementById('saveCategoriesBtn');

    function removeCategory(button) {
        const item = button.closest('.list-group-item');
        if (item) {
            item.remove();
        }
    }
    window.removeCategory = removeCategory;

    if (addCategoryBtn && newCategoryInput && categoriesList) {
        addCategoryBtn.addEventListener('click', () => {
            const newCategory = newCategoryInput.value.trim();
            if (newCategory === '') {
                alert('Nama kategori tidak boleh kosong.');
                return;
            }
            // Check for duplicates
            const existingCategories = Array.from(categoriesList.children).map(el => el.textContent.trim());
            if (existingCategories.includes(newCategory)) {
                alert('Kategori sudah ada.');
                return;
            }
            // Create new category item
            const newItem = document.createElement('div');
            newItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            newItem.textContent = newCategory;

            const removeBtn = document.createElement('button');
            removeBtn.className = 'btn btn-sm btn-outline-danger';
            removeBtn.type = 'button';
            removeBtn.innerHTML = '<i class="ti ti-trash"></i>';
            removeBtn.onclick = () => removeCategory(removeBtn);

            newItem.appendChild(removeBtn);
            categoriesList.appendChild(newItem);
            newCategoryInput.value = '';
        });
    }

    if (saveCategoriesBtn) {
        saveCategoriesBtn.addEventListener('click', () => {
            // Collect categories
            const categories = Array.from(categoriesList.children).map(el => el.firstChild.textContent.trim());
            // TODO: Save categories via AJAX or form submission
            alert('Kategori hobi disimpan: ' + categories.join(', '));
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('hobbyCategoriesModal'));
            if (modal) {
                modal.hide();
            }
        });
    }
});
