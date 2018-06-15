$(document).ready(function () {

    $('.reset-search').on('click', function() {
        if($('#cityInput').val() == ''){
            return false;
        }
        $('#cityInput').val('');
        $('#allCountriesList').html('').hide();
        initMap();
    });

    var is_timeout = false;

    $('#cityInput').on('input', function() {
        var _this = $(this);
        if(is_timeout){
            clearTimeout(is_timeout);
        }
        is_timeout = setTimeout(function() {
            if($(_this).val() == ''){
                $('#allCountriesList').html('').hide();
                return false;
            }
            var searchedName = $(_this).val();
            $.ajax({
                url: '/get-all-cities',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'json',
                data: {
                    searchedName: searchedName,
                },
            })
            .done(function (citiesList) {
                var citiesAllList = '';
                if(citiesList && citiesList.length > 1) {
                    for(var i = 0; i < citiesList.length; i++){
                        citiesAllList += '<li class="list-group-item" data-id="'+citiesList[i]['id'] + '"' +
                                         'data-latitude="'+citiesList[i]['latitude']+'"' +
                                         'data-longitude="'+citiesList[i]['longitude']+'" >' +
                                          '<strong>'+ citiesList[i]['name'] + '</strong> ' + citiesList[i]['alternatenames']
                                          '</li>';
                    }
                    $('#allCountriesList').html(citiesAllList).show();
                } else {
                   $('#allCountriesList').html('<li>No result</li>').show();
                }
            })
            .fail(function (xhr) {
                console.log(xhr);
            })
        }, 300);
    });

    $('#allCountriesList').on('click', '.list-group-item', function () {
        var latitude = $(this).data('latitude');
        var longitude = $(this).data('longitude');
        var cityId = $(this).data('id');
        var cityName = $(this).text().trim();
        $('#cityInput').val(cityName);
        $('#allCountriesList').html('').hide();
        var image = {
          url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
          size: new google.maps.Size(20, 32),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(0, 32)
        };
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 11,
            center: new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude)),
        });
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude)),
            title: cityName,
            map: map,
            icon: image
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
                cityId: cityId,
                latitude: latitude,
                longitude: longitude
            },
        })
        .done(function (locations) {
        	if(locations){
        		for (i = 0; i < locations.length; i++) {
	                var marker = new google.maps.Marker({
	                    position: new google.maps.LatLng(locations[i]['latitude'], locations[i]['longitude']),
	                    title: locations[i]['name'],
	                    map: map
	                });
	            }
        	}
        })
        .fail(function (xhr) {
            console.log(xhr);
        })
    });
});

function initMap(){
    var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 11,
            center: new google.maps.LatLng(40.177200 , 44.503490),
        });
}