// Icon preview updater
document.getElementById('kategoriHobi')?.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const iconClass = selectedOption.getAttribute('data-icon');
    const iconPreview = document.getElementById('iconPreview');

    if (iconClass && iconPreview) {
        iconPreview.className = `ti ${iconClass} text-primary`;
    }
});
