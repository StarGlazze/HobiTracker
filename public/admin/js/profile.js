document.addEventListener('DOMContentLoaded', function () {
    const editProfileForm = document.getElementById('editProfileForm');
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));

    editProfileForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(editProfileForm);

        fetch('/profile/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update navbar profile image if foto_profil is provided
                    if (data.data && data.data.foto_profil) {
                        const navbarImg = document.querySelector('.navbar .dropdown img');
                        if (navbarImg) {
                            navbarImg.src = data.data.foto_profil;
                        }
                    }
                    successModal.show();
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    let errorMessage = data.message || 'Terjadi kesalahan saat memperbarui profil.';
                    if (data.errors) {
                        const errorList = Object.values(data.errors).flat().join('\n');
                        errorMessage += '\n\nDetail kesalahan:\n' + errorList;
                    }
                    alert(errorMessage);
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan saat memperbarui profil.');
            });
    });

    // Password change form handling
    const passwordForm = document.querySelector('#password form');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const passwordLama = document.getElementById('passwordLama').value;
            const passwordBaru = document.getElementById('passwordBaru').value;
            const passwordKonfirmasi = document.getElementById('passwordKonfirmasi').value;

            if (passwordBaru !== passwordKonfirmasi) {
                alert('Konfirmasi password baru tidak cocok.');
                return;
            }

            fetch('/profile/change-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    password_lama: passwordLama,
                    password_baru: passwordBaru,
                    password_baru_confirmation: passwordKonfirmasi,
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Password berhasil diubah.');
                        passwordForm.reset();
                    } else {
                        alert(data.message || 'Terjadi kesalahan saat mengubah password.');
                    }
                })
                .catch(() => {
                    alert('Terjadi kesalahan saat mengubah password.');
                });
        });
    }
});
