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

    // Simple Search functionality
    var searchInput = document.getElementById('searchHobi');
    var clearSearchBtn = document.getElementById('clearSearchBtn');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var searchTerm = this.value.toLowerCase().trim();
            var tableBody = document.querySelector('.table tbody');
            var rows = tableBody.querySelectorAll('tr');
            var visibleRows = 0;

            // Show/hide clear button
            if (clearSearchBtn) {
                clearSearchBtn.style.display = searchTerm ? 'block' : 'none';
            }

            // Filter rows
            rows.forEach(function(row) {
                // Skip no-results row
                if (row.classList.contains('no-results-row')) {
                    return;
                }

                var text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle no results message
            var noResultsRow = tableBody.querySelector('.no-results-row');
            
            if (visibleRows === 0 && searchTerm !== '') {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.className = 'no-results-row';
                    noResultsRow.innerHTML = `
                        <td colspan="5" class="text-center py-4">
                            <div class="text-muted">
                                <i class="ti ti-search-off mb-2" style="font-size: 2rem;"></i>
                                <p class="mb-0">Tidak ada hobi yang cocok dengan pencarian "${searchTerm}"</p>
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

    // Clear search functionality
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            if (searchInput) {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
            }
        });
    }
});

// Global function to clear search
function clearSearch() {
    var searchInput = document.getElementById('searchHobi');
    if (searchInput) {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
    }
}