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

    // Auto-submit search on Enter key
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchForm').submit();
            }
        });
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Global function to clear search
function clearSearch(event) {
    if (event) {
        event.preventDefault();
    }
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.value = '';
        document.getElementById('searchForm').submit();
    }
}
