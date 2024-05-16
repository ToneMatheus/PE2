document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="blownFuse"]');
    const NoBlownFuse = document.getElementById('meterCupboardContainer');
    const YesBlownFuse = document.getElementById('BlownFuseText');

    typeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'No' && radio.checked) {
                NoBlownFuse.style.display = 'block';
                YesBlownFuse.style.display = 'none';
            } else if (radio.value === 'Yes' && radio.checked) {
                NoBlownFuse.style.display = 'none';
                YesBlownFuse.style.display = 'block';
            } else {
                NoBlownFuse.style.display = 'none';
                YesBlownFuse.style.display = 'none';
            }
        });
    });
});
