document.addEventListener('DOMContentLoaded', function() {
    const typeRadioselec = document.querySelectorAll('input[name="CupBoardelectricity"]');
    const typeRadiosgas = document.querySelectorAll('input[name="CupBoardgas"]');
    const NoCupBoardElectricity = document.getElementById('NoCupBoardTextelectricity');
    const NoCupBoardGas = document.getElementById('NoCupBoardTextgas');
    const YesCupBoardElectricity = document.getElementById('YesCupBoardTextelectricity');
    const YesCupBoardGas = document.getElementById('YesCupBoardTextgas');

    typeRadioselec.forEach(radio => {
        radio.addEventListener('change', () => {
            console.log("changes");
            if (radio.value === 'No' && radio.checked) {
                NoCupBoardElectricity.style.display = 'block';
                YesCupBoardElectricity.style.display = 'none';
            } else if (radio.value === 'Yes' && radio.checked) {
                NoCupBoardElectricity.style.display = 'none';
                YesCupBoardElectricity.style.display = 'block';
            } else {
                NoCupBoardElectricity.style.display = 'none';
                YesCupBoardElectricity.style.display = 'none';
            }
        });
    });

    typeRadiosgas.forEach(radio => {
        radio.addEventListener('change', () => {
            console.log("changes");
            if (radio.value === 'No' && radio.checked) {
                console.log("gasNo");
                NoCupBoardGas.style.display = 'block';
                YesCupBoardGas.style.display = 'none';
            } else if (radio.value === 'Yes' && radio.checked) {
                console.log("gasYes");

                NoCupBoardGas.style.display = 'none';
                YesCupBoardGas.style.display = 'block';
            } else {
                NoCupBoardGas.style.display = 'none';
                YesCupBoardGas.style.display = 'none';
            }
        });
    });
});
