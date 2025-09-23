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

    // Search functionality
    var searchInput = document.getElementById('searchHobi');
    var clearSearchBtn = document.getElementById('clearSearchBtn');
    var tableBody = document.querySelector('.table tbody');
    var emptyState = document.getElementById('empty-state');

    console.log('Search elements found:', {
        searchInput: !!searchInput,
        clearSearchBtn: !!clearSearchBtn,
        tableBody: !!tableBody,
        emptyState: !!emptyState
    });

    // Clear search button functionality
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            clearSearch();
        });
    }

    // Main search functionality
    if (searchInput && tableBody) {
        searchInput.addEventListener('input', function() {
            var searchTerm = this.value.toLowerCase().trim();
            console.log('Search term:', searchTerm);

            // Show/hide clear button
            if (clearSearchBtn) {
                clearSearchBtn.style.display = searchTerm ? 'block' : 'none';
            }

            var rows = tableBody.querySelectorAll('tr');
            console.log('Total rows found:', rows.length);

            var visibleRows = 0;

            rows.forEach(function(row, index) {
                // Skip empty rows or rows with "Belum ada hobi" message
                if (row.cells.length < 5) {
                    // This is likely the "Belum ada hobi yang ditambahkan" row
                    if (searchTerm === '') {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                    return;
                }

                try {
                    // Get text content from specific cells based on your table structure
                    // Cell 0: # (index)
                    // Cell 1: Nama Hobi
                    // Cell 2: Kategori
                    // Cell 3: Deskripsi
                    // Cell 4: Aksi

                    var namaHobi = row.cells[1] ? row.cells[1].textContent.toLowerCase().trim() : '';
                    var kategoriHobi = row.cells[2] ? row.cells[2].textContent.toLowerCase().trim() : '';
                    var deskripsiHobi = row.cells[3] ? row.cells[3].textContent.toLowerCase().trim() : '';

                    console.log('Row', index, 'data:', {
                        namaHobi: namaHobi,
                        kategoriHobi: kategoriHobi,
                        deskripsiHobi: deskripsiHobi
                    });

                    // Check if search term matches any of the fields
                    var matches = searchTerm === '' ||
                        namaHobi.includes(searchTerm) ||
                        kategoriHobi.includes(searchTerm) ||
                        deskripsiHobi.includes(searchTerm);

                    console.log('Row', index, 'matches:', matches);

                    if (matches) {
                        row.style.display = '';
                        visibleRows++;
                    } else {
                        row.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Error processing row', index, ':', error);
                    // Show row by default if there's an error
                    row.style.display = '';
                }
            });

            console.log('Visible rows:', visibleRows);

            // Handle empty state
            handleEmptyState(visibleRows, searchTerm);
        });

        // Add Enter key support
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
    } else {
        console.error('Search elements not found!', {
            searchInput: searchInput,
            tableBody: tableBody
        });
    }

    function handleEmptyState(visibleRows, searchTerm) {
        if (!emptyState) return;

        if (visibleRows === 0 && searchTerm !== '') {
            // No search results
            emptyState.style.display = 'block';
            emptyState.innerHTML = `
                <div class="mb-4">
                    <i class="ti ti-search text-muted" style="font-size: 4rem;"></i>
                </div>
                <h5 class="text-muted">Tidak ada hasil untuk "${searchTerm}"</h5>
                <p class="text-muted mb-4">Coba kata kunci yang berbeda atau periksa ejaan</p>
                <button class="btn btn-outline-primary" onclick="clearSearch()">
                    <i class="ti ti-x me-2"></i>Tampilkan Semua
                </button>
            `;
        } else {
            // Hide empty state when there are results or no search
            emptyState.style.display = 'none';
        }
    }
});

// Global function to clear search
function clearSearch() {
    var searchInput = document.getElementById('searchHobi');
    var clearSearchBtn = document.getElementById('clearSearchBtn');

    if (searchInput) {
        searchInput.value = '';
        // Trigger input event to refresh search results
        var event = new Event('input', { bubbles: true });
        searchInput.dispatchEvent(event);
    }

    // Hide clear button
    if (clearSearchBtn) {
        clearSearchBtn.style.display = 'none';
    }
}