document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="mkContract"]');
    const makeContractNo = document.getElementById('makeContractNo');
    const makeContractYes = document.getElementById('makeContractYes');

    typeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'No' && radio.checked) {
                makeContractNo.style.display = 'block';
                makeContractYes.style.display = 'none';
            } else if (radio.value === 'Yes' && radio.checked) {
                makeContractNo.style.display = 'none';
                makeContractYes.style.display = 'block';
            } else {
                makeContractNo.style.display = 'none';
                makeContractYes.style.display = 'none';
            }
        });
    });
});
