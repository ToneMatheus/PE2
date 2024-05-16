document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="powerFailure"]');
    const NopowerFailure = document.getElementById('nonBlownFuseContainer');
    const YespowerFailure = document.getElementById('powerFailureStreetContainer');


    typeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'No' && radio.checked) {
                NopowerFailure.style.display = 'block';
                YespowerFailure.style.display = 'none';

            } else if (radio.value === 'Yes' && radio.checked) {
                NopowerFailure.style.display = 'none';
                YespowerFailure.style.display = 'block';

            } else {
                NopowerFailure.style.display = 'none';
                YespowerFailure.style.display = 'none';
            }
        });
    });
});
