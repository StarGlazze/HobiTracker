import './bootstrap';

if (document.getElementById('bukti')) {
    document.getElementById('bukti').addEventListener('change', function() {
        const completedOption = document.getElementById('completedOption');
        if (this.files.length > 0) {
            completedOption.disabled = false;
            completedOption.textContent = 'Completed';
        } else {
            completedOption.disabled = true;
            completedOption.textContent = 'Completed (Upload bukti terlebih dahulu)';
        }
    });

    document.getElementById('gdriveLink').addEventListener('input', function() {
        const completedOption = document.getElementById('completedOption');
        if (this.value.trim() !== '') {
            completedOption.disabled = false;
            completedOption.textContent = 'Completed';
        } else {
            // Check if file is uploaded
            const buktiFile = document.getElementById('bukti');
            if (buktiFile.files.length === 0) {
                completedOption.disabled = true;
                completedOption.textContent = 'Completed (Upload bukti terlebih dahulu)';
            }
        }
    });
}
