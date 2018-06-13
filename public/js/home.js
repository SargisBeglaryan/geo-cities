$( document ).ready(function() {
    $('#select-country').on('change', function(){
    	var latitude = $(this).data('latitude');
    	var longitude = $(this).data('longitude');
    	var cityName = $(this).val();
    	$.ajax({
				url: route('getNearCities'),
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
			.done(function(result) {
				var locations = [
			    ['Los Angeles', 34.052235, -118.243683],
			    ['Santa Monica', 34.024212, -118.496475],
			    ['Redondo Beach', 33.849182, -118.388405],
			    ['Newport Beach', 33.628342, -117.927933],
			    ['Long Beach', 33.770050, -118.193739]
			  ];
			var marker, count;
			for (count = 0; count < locations.length; count++) {
			    marker = new google.maps.Marker({
			      position: new google.maps.LatLng(locations[count][1], locations[count][2]),
			      map: map,
			      title: locations[count][0]
			    });
			  }
			})
			.fail(function(xhr) {
				console.log(xhr);
			})
    });
});