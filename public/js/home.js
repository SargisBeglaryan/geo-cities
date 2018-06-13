$(document).ready(function () {
    $('#select-country').on('change', function () {
        var latitude = $(this).find(':selected').data('latitude');
        var longitude = $(this).find(':selected').data('longitude');
        var cityName = $(this).val();
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude)),
        });

		$.ajax({
            url: '/near-cities',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            dataType: 'json',
            data: {
                cityName: cityName,
                latitude: latitude,
                longitude: longitude
            },
        })
        .done(function (locations) {
            for (i = 0; i < locations.length; i++) {
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i]['latitude'], locations[i]['longitude']),
                    title: locations[i]['name'],
                    map: map
                });
            }
        })
        .fail(function (xhr) {
            console.log(xhr);
        })
    });
});