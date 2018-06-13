$( document ).ready(function() {
    $('#select-country').on('change', function(){
    	var latitude = $(this).data('latitude');
    	var longitude = $(this).data('longitude');
    	var cityName = $(this).val();
		var locations = [
			    ['Los Angeles', 34.052235, -118.243683],
			    ['Santa Monica', 34.024212, -118.496475],
			    ['Redondo Beach', 33.849182, -118.388405],
			    ['Newport Beach', 33.628342, -117.927933],
			    ['Long Beach', 33.770050, -118.193739]
			  ];
		var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8,
          center: new google.maps.LatLng(parseFloat(locations[0][1]), parseFloat(locations[0][2])),
        });

		for (i = 0; i < locations.length; i++) {
			var marker = new google.maps.Marker({
	            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
	            title: locations[i][0],
	            map: map
	        });
		}
  //   	$.ajax({
		// 	url: '/near-cities',
		// 	headers: {
		// 		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		// 	},
		// 	type: 'POST',
		// 	dataType: 'json',
		// 	data: {
		// 		cityName: cityName,
		// 		latitude: latitude,
		// 		longitude: longitude
		// 	},
		// })
		// .done(function(locations) {
		// 	for (i = 0; i < locations.length; i++) {
		// 		var marker = new google.maps.Marker({
		//             position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		//             title: locations[i][0],
		//             map: map
		//         });
		// 	}
		// })
		// .fail(function(xhr) {
		// 	console.log(xhr);
		// })
    });
});