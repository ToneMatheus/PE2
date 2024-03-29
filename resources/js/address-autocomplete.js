function initAutocomplete() {
    var input = document.getElementById('address-input');
    var options = {
        types: ['address'],
        componentRestrictions: { country: 'be' } // Beperk de suggesties tot België
    };

    var autocomplete = new google.maps.places.Autocomplete(input, options);
}
