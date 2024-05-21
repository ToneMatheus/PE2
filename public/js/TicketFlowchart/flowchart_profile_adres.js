document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="profileAdres"]');
    const addAdres = document.getElementById('addAdresText');
    const changeAdres = document.getElementById('changeAdresText');

    typeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            console.log("changes");
            if (radio.value === 'addAdres' && radio.checked) {
                addAdres.style.display = 'block';
                changeAdres.style.display = 'none';
            } else if (radio.value === 'changeAdres' && radio.checked) {
                addAdres.style.display = 'none';
                changeAdres.style.display = 'block';
            } else {
                addAdres.style.display = 'none';
                changeAdres.style.display = 'none';
            }
        });
    });
});
