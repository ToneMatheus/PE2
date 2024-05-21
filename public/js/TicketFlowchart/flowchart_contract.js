document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="contract"]');
    const makeContractContainer = document.getElementById('makeContractContainer');
    const typeProblemContainer = document.getElementById('typeProblemContainer');

    typeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'No' && radio.checked) {
                makeContractContainer.style.display = 'block';
                typeProblemContainer.style.display = 'none';
            } else if (radio.value === 'Yes' && radio.checked) {
                makeContractContainer.style.display = 'none';
                typeProblemContainer.style.display = 'block';
            } else {
                makeContractContainer.style.display = 'none';
                typeProblemContainer.style.display = 'none';
            }
        });
    });
});