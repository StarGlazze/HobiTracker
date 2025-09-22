document.addEventListener('DOMContentLoaded', function () {
    var editHobiModal = document.getElementById('editHobiModal');
    editHobiModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nama = button.getAttribute('data-nama');
        var kategori = button.getAttribute('data-kategori');
        var deskripsi = button.getAttribute('data-deskripsi');

        var form = document.getElementById('editHobiForm');
        form.action = '/hobi/' + id;

        document.getElementById('editNamaHobi').value = nama;
        document.getElementById('editKategoriHobi').value = kategori;
        document.getElementById('editDeskripsiHobi').value = deskripsi;
    });
});
