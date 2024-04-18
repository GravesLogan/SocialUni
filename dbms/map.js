function initMap() {
    const map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 28.6135559, lng: -81.2022648 },
        zoom: 14
    });

    const marker = new google.maps.Marker({
        position: { lat: 28.6135559, lng: -81.2022648 },
        map: map,
        title: 'My Location'
    });

    // Add click event listener to the marker
    marker.addListener('click', function() {
        updateInputBox(marker);
    });

    // Add click event listener to each marker on the map
    map.addListener('click', function(event) {
        // Use Geocoder to get the address from the clicked position
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ location: event.latLng }, function(results, status) {
            if (status === 'OK') {
                if (results[0]) {
                    // Update the input box with the address
                    document.getElementById('addressInput').value = results[0].formatted_address;
                } else {
                    console.log('No results found');
                }
            } else {
                console.log('Geocoder failed due to: ' + status);
            }
        });
    });
}

function updateInputBox(marker) {
    // Get the position of the clicked marker
    const position = marker.getPosition();
    
    // Use Geocoder to get the address from the marker's position
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ location: position }, function(results, status) {
        if (status === 'OK') {
            if (results[0]) {
                // Update the input box with the address
                document.getElementById('addressInput').value = results[0].formatted_address;
            } else {
                console.log('No results found');
            }
        } else {
            console.log('Geocoder failed due to: ' + status);
        }
    });
}
