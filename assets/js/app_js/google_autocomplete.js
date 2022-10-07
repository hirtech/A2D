var placeSearch, autocomplete;
var countryRestrict = { 'country': GoogleMapCountryCode };
var componentForm = {
	street_number: 'short_name',
	route: 'long_name',
	locality: 'long_name',
	administrative_area_level_1: 'short_name',
	country: 'long_name',
	postal_code: 'short_name',
	administrative_area_level_2: 'short_name'
};

var defaultBounds = new google.maps.LatLngBounds(
	/*new google.maps.LatLng(26.642487, -81.709854),
	new google.maps.LatLng(26.642487, -81.709854)*/
	new google.maps.LatLng(MAP_LATITUDE,MAP_LONGITUDE),
	new google.maps.LatLng(MAP_LATITUDE, MAP_LONGITUDE)
);

function initialize() {
    // Create the autocomplete object, restricting the search
    // to geographical location types.
    //$('.address_fields').hide();
    autocomplete = new google.maps.places.Autocomplete(
    	/** @type {HTMLInputElement} */(document.getElementById('autofilladdress')),
    	{  bounds: defaultBounds, types: ['geocode'], componentRestrictions: { 'country': GoogleMapCountryCode } });

	// When the user selects an address from the dropdown,
	// populate the address fields in the form.
	google.maps.event.addListener(autocomplete, 'place_changed', function() {
		fillInAddress();
	});
}

// [START region_fillform]
function fillInAddress() {
	//var html="<input type='button' onclick='showNearbySr()' class='btn btn-primary' value='SR History'>";
	//$("#showNearbySr").html(html);
	// Get the place details from the autocomplete object.
	var place = autocomplete.getPlace();
	$("#address_loading").show();
	//alert(JSON.stringify(place));return false;
	//alert(place.geometry.location.lng() + " === " + place.geometry.location.lat());
	$('#vLatitude').val(place.geometry.location.lat());
	$('#vLongitude').val(place.geometry.location.lng());
	var vLatitude_nearbysr ='';
	var vLongitude_nearbysr ='';
	var meter = '402.336';
	var vLatitude_nearbysr = place.geometry.location.lat();
	var vLongitude_nearbysr = place.geometry.location.lng();

	var html="<input type='button' onclick='showNearbySr("+vLatitude_nearbysr+","+vLongitude_nearbysr+","+meter+")' class='btn btn-primary' value='Fiber Inquiry History'>";
	$("#showNearbySr").html(html);

	$(".address-details").show();
	if(place.geometry.location.lat()!="" && place.geometry.location.lng()!="") {
		//alert('mode=get_zone_from_latlong&lat=' + place.geometry.location.lat() + '&long=' + place.geometry.location.lng())
		$.ajax({
			type: "POST",
			dataType: "json",
			url: site_url+"fiber_inquiry/list",
			data: 'mode=get_zone_from_latlong&lat=' + place.geometry.location.lat() + '&long=' + place.geometry.location.lng(),
			success: function(data){
				if(data){
					//alert(JSON.stringify(data))
					$('#iZoneId').val(data.iZoneId);
					$('#vLatitude').val(data.lat);
					$('#vLongitude').val(data.long);

					$('.iZoneId').html(data.iZoneId);
					$('.vLatitude').html(data.lat);
					$('.vLongitude').html(data.long);
					$('.vNetwork').html(data.vNetwork);
					$('.vZoneName').html(data.vZoneName);
				}
				else{
					$('#iZoneId').val('');
					$('#vLatitude').val('');
					$('#vLongitude').val('');

					$('.iZoneId').html('');
					$('.vLatitude').html('');
					$('.vLongitude').html('');
					$('.vNetwork').html('');
					$('.vZoneName').html('');
				}	
				$("#address_loading").hide();				
			}
		});
	}
	//$('.address_fields').show();

	for (var component in componentForm) {
		//document.getElementById(component).value = '';
	    //document.getElementById(component).disabled = false;
	}

	// Get each component of the address from the place details
	// and fill the corresponding field on the form.

	var city = "";
	var state_code = "";
	var house_number = "";
	var street = "";
	var zipcode = "";
	var county = "";
	var address_data = '';

  	//alert(JSON.stringify(place.address_components));
  
  	for (var i = 0; i < place.address_components.length; i++) {
  		var addressType = place.address_components[i].types[0];
		//alert(place.address_components[i][componentForm['postal_code']]);
	
		if (componentForm[addressType]) {
			var val = place.address_components[i][componentForm[addressType]];
		 	// alert(addressType);
			if(addressType == "locality"){ //City
				city = val;
			}
			if(addressType == "administrative_area_level_1"){ //State Code
				state_code = val;
			}
			if(addressType == "street_number"){ // House number
				house_number = val;
			}
			if(addressType == "route"){ // Street
				street = val;
			}
			if(addressType == "postal_code"){ // Zipcode
				zipcode = val;
			}
			if(addressType == "administrative_area_level_2"){ // county
				county = val;
			}
	      //document.getElementById(addressType).value = val;
	  	}
	}

	if(city != "" && state_code != "" ) {

		$.ajax({
			type: "POST",
			dataType: "json",
			url: site_url+"fiber_inquiry/list",
			data: 'mode=check_city_state&city='+city+'&state_code='+state_code,
			async: false,
			success: function(data){
				//alert(data.check)
				if(data.check == 0){
					var zone_flag = 0;
					$('#autofilladdress').val("");
					$('#autofilladdress').focus();
				}
				else {
					$(".clear_address").show();
					var addr_val = $('#autofilladdress').val().split(" ");
					//alert(addr_val[0])

					if(house_number == '')
						house_number = addr_val[0];

					if(house_number != '')
						address_data += house_number+", ";	
					if(street != '')
						address_data += street+", ";


					$('#vAddress1').val(house_number);
					$('#vStreet').val(street);
					
					$('.vAddress1').html(house_number);
					$('.vStreet').html(street);

					if(state_code != ""){
						$.ajax({
							type: "POST",
							dataType: "json",
							url: site_url+"fiber_inquiry/list",
							data: 'mode=get_state&vStateCode='+state_code,
							async: false,
							success: function(data){
								if(data.iStateId){
									$('#iStateId').val(data.iStateId);
									$('.iStateId').html(data.iStateId);
									
								}
							}
						});
					}
					
					if(city != "") {
						$.ajax({
							type: "POST",
							dataType: "json",
							url: site_url+"fiber_inquiry/list",
							data: 'mode=get_city&city='+city+'&county='+county,
							async: false,
							success: function(data){
								if(data.iCityId){
									$('#iCountyId').val(data.iCountyId);
									$('#iCityId').val(data.iCityId);

									$('.iCountyId').html(data.iCountyId);
									$('.iCityId').html(data.iCityId);
									//alert(county)
									if(county != '')
										address_data += county+", ";
								}
							}
						});
					}

					if(zipcode != "") {
						$.ajax({
							type: "POST",
							dataType: "json",
							url: site_url+"fiber_inquiry/list",
							data: 'mode=get_zipcode&vZipcode='+zipcode,
							async: false,
							success: function(data){
								$('#iZipcode').val(data.iZipcode);
								$('.iZipcode').html(data.iZipcode);
								if(zipcode != '')
									address_data += zipcode;
							}
						});
					}
					$(".address_data").html(address_data)
				}
				return false;
			}
		});
	}
}
initialize();


