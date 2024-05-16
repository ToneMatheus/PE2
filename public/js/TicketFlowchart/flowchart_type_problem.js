document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="TypeProblem"]');
    const OtherText = document.getElementById('OtherText');
    const electricityContainer = document.getElementById('electricityContainer');
    const gasContainer = document.getElementById('gasContainer');
    const profileContainer = document.getElementById('profileContainer');


    typeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'Other' && radio.checked) {
                OtherText.style.display = 'block';
                profileContainer.style.display = 'none';
                electricityContainer.style.display = 'none';
                gasContainer.style.display = 'none';

            } else if (radio.value === 'Profile' && radio.checked) {
                OtherText.style.display = 'none';
                profileContainer.style.display = 'block';
                electricityContainer.style.display = 'none';
                gasContainer.style.display = 'none';

            } else if (radio.value === 'Gas' && radio.checked) {
                OtherText.style.display = 'none';
                profileContainer.style.display = 'none';
                electricityContainer.style.display = 'none';
                gasContainer.style.display = 'block';
            

            } else if (radio.value === 'Electricity' && radio.checked) {
                OtherText.style.display = 'none';
                profileContainer.style.display = 'none';
                electricityContainer.style.display = 'block';
                gasContainer.style.display = 'none';

            } else {
                OtherText.style.display = 'none';
                profileContainer.style.display = 'none';
                electricityContainer.style.display = 'none';
                gasContainer.style.display = 'none';
            }
        });
    });
});