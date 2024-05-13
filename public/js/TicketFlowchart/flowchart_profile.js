document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="profile"]');
    const ProfileInfo = document.getElementById('ProfileInfoText');
    const profileProblemAdress = document.getElementById('profileProblemAdress');

    typeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            console.log("changes");
            if (radio.value === 'Adres' && radio.checked) {
                profileProblemAdress.style.display = 'block';
                ProfileInfo.style.display = 'none';
            } else if (radio.value === 'ProfileInfo' && radio.checked) {
                profileProblemAdress.style.display = 'none';
                ProfileInfo.style.display = 'block';
            } else {
                profileProblemAdress.style.display = 'none';
                ProfileInfo.style.display = 'none';
            }
        });
    });
});
