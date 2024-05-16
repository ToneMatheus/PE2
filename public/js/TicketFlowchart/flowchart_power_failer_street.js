document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="powerFailerStreet"]');
    const NopowerFailureStreet = document.getElementById('meterCupboardContainer');
    const YespowerFailureStreet = document.getElementById('powerFailerStreetText');

    typeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'No' && radio.checked) {
                NopowerFailureStreet.style.display = 'block';
                YespowerFailureStreet.style.display = 'none';
            } else if (radio.value === 'Yes' && radio.checked) {
                NopowerFailureStreet.style.display = 'none';
                YespowerFailureStreet.style.display = 'block';
            } else {
                NopowerFailureStreet.style.display = 'none';
                YespowerFailureStreet.style.display = 'none';
            }
        });
    });
});
