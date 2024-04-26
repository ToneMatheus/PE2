function initAutocomplete() {
    //var input = document.getElementById('address-input');
    var input = document.getElementById('street');
    
    var options = {
        types: ['address'],
        componentRestrictions: { country: 'be' } // Beperk de suggesties tot België
    };

    var autocomplete = new google.maps.places.Autocomplete(input, options);

    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        var streetName = '';
        var province = '';
        var city = '';
        var postalcode = '';
        var streetNumber = '';
        var country = '';


        // Zoek het adrescomponent met type 'route' (straatnaam)
        place.address_components.forEach(function(component) {
            if (component.types.includes('route')) {
                streetName = component.long_name;
            }
        });

        //stad
        place.address_components.forEach(function(component) {
            if (component.types.includes('locality')) {
                city = component.long_name;
            }
        });

        //provincie
        place.address_components.forEach(function(component) {
            if (component.types.includes('administrative_area_level_2')) {
                province = component.long_name;
            }
        });

        //postal code
        place.address_components.forEach(function(component) {
            if (component.types.includes('postal_code')) {
                postalcode = component.long_name;
            }
            //huisnummer
            if (component.types.includes('street_number')) {
                streetNumber = component.long_name;
            }
            //country
            if (component.types.includes('country')) {
                country = component.long_name;
            }
        });



        document.getElementById("street").value=streetName;
        document.getElementById("province").value=province;
        document.getElementById("postal_code").value=postalcode;
        document.getElementById("number").value=streetNumber;
        document.getElementById("city").value=city;
        document.getElementById("country").value=country;

        
    });
}