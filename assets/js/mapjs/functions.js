var markerCluster;
function getMapData(siteTypes, sAttr, skCity, skZones, sr, larvalfieldtask, landingfieldtask, positive, custlayer, siteSubTypes) {

	/*console.log("Site : " + siteTypes);
	console.log("Premise Sub type : " + siteSubTypes);
	console.log("Attr : " + sAttr);
	console.log("city: " + skCity);
	console.log("Zone : " + skZones);
	console.log("SR : " + sr);
	console.log("positive : " + positive);
	console.log("siteFilter : " + siteFilter);
	console.log("srFilter : " + srFilter);*/
//	console.log('1111');
	//console.log('custome layer=>'+custlayer)
	//alert(fieldtask);
	//var latlngbounds = new google.maps.LatLngBounds();
	
	//siteMarker.length = 0;
	clearMap();
	 if(siteTypes != "" || sAttr != "" || skCity != "" || skZones != "" || sr != "" || larvalfieldtask != "" || landingfieldtask !="" || positive != "" ||  custlayer != ""|| siteSubTypes != ""){
		if(typeof siteSubTypes == "undefined"){
			siteSubTypes = [];
		}
		$.ajax({
			type: "POST",
			url: 'vmap/api/',
			data: {
				action: "getJson",
				siteType: siteTypes.join(","),
				siteSubTypes: siteSubTypes.join(","),
				sAttr: sAttr.join(","),
				city: skCity.join(","),
				zone: skZones.join(","),
				sr: sr.join(","),
				larvalfieldtask: larvalfieldtask.join(","),
				landingfieldtask: landingfieldtask.join(","),
				positive: positive.join(","),
				custlayer: custlayer.join(","),
			},
			cache: true,
			beforeSend: function() {
				$(".loading").show();
				/*
				if (localCache.exist(url)) {
					doSomething(localCache.get(url));
					return false;
				}
				return true;
				*/
			},
			success: function(data) {
				//console.log(data);
				if (data) {
					console.log('data found');
					var response = JSON.parse(data);
					var siteData = response.sites;
					var reslanddata = "";
					var reslarvaldata = "";
					var ressrdata = "";
					var respositivedata = "";
					
					//var srcount = 0;
					if (response.polyZone !== undefined) {
						$.each(response.polyZone, function(zoneid, item) {
							showZonePolygonMap(item, map);
							
						});
					
					}
					if (response.sites !== undefined) {
						$.each(siteData, function(siteid, item) {
								//console.log(siteData[siteid]);
								if (siteData[siteid].polygon !== undefined) {
									
									//console.log(siteData[siteid].polygon);
							    	//console.log('-------------');
									showPolygonMap(siteData[siteid].polygon, map, siteData[siteid].icon, siteid);
									if (siteData[siteid].polyCenter !== undefined) {
										/*var centerPoint = {
											lat: siteData[siteid].polyCenter['lat']+ (Math.random() / 10000),
											lng: siteData[siteid].polyCenter['lng']+ (Math.random() / 10000)
										};*/
										var centerPoint = {
											lat: siteData[siteid].polyCenter['lat']+ mathRandLat,
											lng: siteData[siteid].polyCenter['lng']+ mathRandLng
										};
										showPolyCenter(centerPoint, map, siteData[siteid].icon, siteid);
									}
								}
								if (siteData[siteid].poly_line !== undefined) {
									
									showPolyLineMap(siteData[siteid].poly_line, map, siteData[siteid].icon, siteid);
								}
								if (siteData[siteid].point !== undefined) {
									
									for (i = 0; i < siteData[siteid].point.length; i++) {
										
										/*var pointMatrix = {
											lat: siteData[siteid].point[i]['lat']+ (Math.random() / 10000),
											lng: siteData[siteid].point[i]['lng']+ (Math.random() / 10000)
										};*/
										var pointMatrix = {
											lat: siteData[siteid].point[i]['lat']+ mathRandLat,
											lng: siteData[siteid].point[i]['lng']+ mathRandLng
										};
										
										showPointMap(pointMatrix, map, siteData[siteid].icon, siteid);
										var vName = siteData[siteid].vName;
										var vAddress = siteData[siteid].vAddress;
										var vRequestType = siteData[siteid].vRequestType;
										var vAssignTo = siteData[siteid].vAssignTo;
										var vStatus = siteData[siteid].vStatus;
									}
								}
						});
          }
         	if (response.landing_rate !== undefined ) {
            reslanddata =response.landing_rate;
						$.each(reslanddata, function(id, item) {
							fieldtask = landingfieldtask;
							if (reslanddata[id].polygon !== undefined) {
								
								showPolygonMapForfieldtask(reslanddata[id].polygon, map, reslanddata[id].icon, id, fieldtask);
								if (reslanddata[id].polyCenter !== undefined) {
									var centerPoint = {
										lat: reslanddata[id].polyCenter['lat']+ (Math.random() / 10000),
										lng: reslanddata[id].polyCenter['lng']+ (Math.random() / 10000)
									};
									/*var centerPoint = {
										lat: reslanddata[id].polyCenter['lat']+ mathRandLat,
										lng: reslanddata[id].polyCenter['lng']+ mathRandLng
									};*/
									showPolyCenterForfieldtask(centerPoint, map, reslanddata[id].icon, id, fieldtask);
								}
							}
							if (reslanddata[id].poly_line !== undefined) {
								showPolyLineMapForfieldtask(reslanddata[id].poly_line, map, reslanddata[id].icon, id, fieldtask);
							}
							if (reslanddata[id].point !== undefined) {
								
								for (i = 0; i < reslanddata[id].point.length; i++) {
									var pointMatrix = {
										lat: reslanddata[id].point[i]['lat']+ (Math.random() / 10000),
										lng: reslanddata[id].point[i]['lng']+ (Math.random() / 10000)
									};
									/*var pointMatrix = {
										lat: reslanddata[id].point[i]['lat']+ mathRandLat,
										lng: reslanddata[id].point[i]['lng']+ mathRandLng
									};*/
									showPointMapForfieldtask(pointMatrix, map, reslanddata[id].icon, id, fieldtask,reslanddata[id].count);
								}
							}
						});
          }
          if (response.larval !== undefined ) {
            reslarvaldata =response.larval;
						$.each(reslarvaldata, function(id, item) {
							fieldtask = larvalfieldtask;
							if (reslarvaldata[id].polygon !== undefined) {
								
								showPolygonMapForfieldtask(reslarvaldata[id].polygon, map, reslarvaldata[id].icon, id, fieldtask);
								if (reslarvaldata[id].polyCenter !== undefined) {
									var centerPoint = {
										lat: reslarvaldata[id].polyCenter['lat']+ (Math.random() / 10000),
										lng: reslarvaldata[id].polyCenter['lng']+ (Math.random() / 10000)
									};
									/*var centerPoint = {
										lat: reslarvaldata[id].polyCenter['lat']+ mathRandLat,
										lng: reslarvaldata[id].polyCenter['lng']+ mathRandLng
									};*/
									showPolyCenterForfieldtask(centerPoint, map, reslarvaldata[id].icon, id, fieldtask);
								}
							}
							if (reslarvaldata[id].poly_line !== undefined) {
								showPolyLineMapForfieldtask(reslarvaldata[id].poly_line, map, reslarvaldata[id].icon, id, fieldtask);
							}
							if (reslarvaldata[id].point !== undefined) {
								
								for (i = 0; i < reslarvaldata[id].point.length; i++) {
									var pointMatrix = {
										lat: reslarvaldata[id].point[i]['lat']+ (Math.random() / 10000),
										lng: reslarvaldata[id].point[i]['lng']+ (Math.random() / 10000)
									};
									/*var pointMatrix = {
										lat: reslarvaldata[id].point[i]['lat']+ mathRandLat,
										lng: reslarvaldata[id].point[i]['lng']+ mathRandLng
									};*/
									showPointMapForfieldtask(pointMatrix, map, reslarvaldata[id].icon, id, fieldtask,reslarvaldata[id].count);
								}
							}
						});
          }
          if (response.sr !== undefined ) {
            ressrdata = response.sr;
            $.each(ressrdata, function(id, item) {
              if (ressrdata[id].point !== undefined) {
                for (i = 0; i < ressrdata[id].point.length; i++) {
									/*var pointMatrix = {
										lat: ressrdata[id].point[i]['lat']+ (Math.random() / 10000),
										lng: ressrdata[id].point[i]['lng']+ (Math.random() / 10000)
									};*/
									var pointMatrix = {
										lat: ressrdata[id].point[i]['lat']+ mathRandLat,
										lng: ressrdata[id].point[i]['lng']+ mathRandLng
									};
									var vName = ressrdata[id].vName;
									var vAddress = ressrdata[id].vAddress;
									var vRequestType = ressrdata[id].vRequestType;
									var vAssignTo = ressrdata[id].vAssignTo;
									var vStatus = ressrdata[id].vStatus;
									showPointMapForSr(pointMatrix, map, ressrdata[id].icon, id, vName, vAddress, vRequestType, vAssignTo, vStatus);
									//srcount++;
								}
            	}
            });
          }
          if(response.positive != undefined){
            respositivedata = response.positive;
            $.each(respositivedata, function(id, item) {
								var iTMPId = '';
								var iTTId = '';
								iTTId = respositivedata[id].iTTId;
								iTMPId = respositivedata[id].iTMPId;
								if (respositivedata[id].polygon !== undefined) {
									showPolygonMapForpositive(respositivedata[id].polygon, map, respositivedata[id].icon, id,respositivedata);
									if (respositivedata[id].polyCenter !== undefined) {
										var centerPoint = {
											lat: respositivedata[id].polyCenter['lat']+ (Math.random() / 10000),
											lng: respositivedata[id].polyCenter['lng']+ (Math.random() / 10000)
										};
										/*var centerPoint = {
											lat: respositivedata[id].polyCenter['lat']+ mathRandLat,
											lng: respositivedata[id].polyCenter['lng']+ mathRandLng
										};*/
										showPolyCenterForpositive(centerPoint, map, respositivedata[id].icon, id,respositivedata);
									}
								}
								if (respositivedata[id].poly_line !== undefined) {
									
									showPolyLineMapForpositive(respositivedata[id].poly_line, map, respositivedata[id].icon, id,respositivedata);
								}
								if (respositivedata[id].point !== undefined) {
									
									for (i = 0; i < respositivedata[id].point.length; i++) {
										var pointMatrix = {
											lat: respositivedata[id].point[i]['lat']+ (Math.random() / 10000),
											lng: respositivedata[id].point[i]['lng']+ (Math.random() / 10000)
										};
										/*var pointMatrix = {
											lat: respositivedata[id].point[i]['lat']+ mathRandLat,
											lng: respositivedata[id].point[i]['lng']+ mathRandLng
										};*/
										
										showPointMapForpositive(pointMatrix, map, respositivedata[id].icon, id,respositivedata);
									}
								}
							});
           }
					
					//custom layer kml data
					if (response.customlayer !== undefined) {
						$.each(response.customlayer, function(id, item) {
							//console.log(item['file_url']);
							//var src =  "http://butte.vectorcontrolsystem.com/storage/kml/6/1606197216_1516332155_organic.kml";
							var src = item['file_url'];
	                        var kml = new google.maps.KmlLayer(src, {
	                          suppressInfoWindows: true,
	                          preserveViewport: false,
	                          map: map
	                        });


							kml.vName = item['vName'];
                        	customeLayerArr.push(kml);
						});

			                var kmls = customeLayerArr.length;
			                if (kmls > 0) {
			                	//info window
			                    for (i = 0; i < kmls; i++) {
			                        //customeLayerArr[i].setMap(map);
									var obj = {
										'vname':customeLayerArr[i].vName,							
									};
									customeLayerArr[i].objInfo = obj;
									if(customeLayerArr[i]) {
										 google.maps.event.addListener(customeLayerArr[i], 'click', function(evt) {
											
											if(infowindow_customlayer) {
												infowindow_customlayer.close();
											}

											infowindow_customlayer = new google.maps.InfoWindow({
													content: this.objInfo.vname,
													zIndex: 100,
													pixelOffset:evt.pixelOffset, 
      												position:evt.latLng
											});
													
											infowindow_customlayer.open(map,customeLayerArr[i]);

										 })
									}
			                    }
			                }
					}

					if(jQuery.isEmptyObject(response) == false){

						if (response.length > 0){
							
							markerCluster = new MarkerClusterer(map, siteMarker, {
							// var markerCluster = new google.maps.Map(map, siteMarker, {
								imagePath: imagePath
							});

							//Center map and adjust Zoom based on the position of all markers.
	                        map.setCenter(latlngbounds.getCenter());
	                        map.fitBounds(latlngbounds);
						}
					}
					//console.log(latlngbounds);

					/*if (srcount != 0){
						srlayermarkerCluster = new MarkerClusterer(map, srlayerMarker, {
						imagePath: imagePath
						});
					}*/
					/*if (siteData){
						var mapmarkers =  [siteMarker, pCenterMarker, polygonObj]; // add additional markers to the array if you have them
						
						 var markerCluster = new google.maps.Map(map, mapmarkers, {
							imagePath: imagePath
						});
					}*/

				} else {
					console.log('no data found');
					//alert("No sites found");
					clearMap();
				
				}
				 
				$(".loading").hide();
			}
		});
    }
}

const map_styles = {
  default: [],
  hide: [
    {
      featureType: "poi.business",
      stylers: [{ visibility: "off" }],
    },
    {
      featureType: "transit",
      elementType: "labels.icon",
      stylers: [{ visibility: "off" }],
    },
    {
        featureType: 'transit',
        elementType: 'labels.icon',
        stylers: [{ visibility: 'off' }]
      },
      {
      	featureType: 'poi',
        stylers: [{ visibility: 'off' }]
      },
      {
        featureType: 'road',
        stylers: [{ visibility: 'off' }]
      },
  ],
};
function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
		zoom: 9,
		center: {
			lat: parseFloat(MAP_LAT),
			lng: parseFloat(MAP_LONG)
		},
		zoomControl: true,
		zoomControlOptions: {
			position: google.maps.ControlPosition.LEFT_TOP
		},
		scaleControl: true,
		streetViewControl: true,
		streetViewControlOptions: {
			position: google.maps.ControlPosition.LEFT_TOP
		},
		fullscreenControl: true,
		mapTypeId: 'satellite'
	});
	map.setCenter({lat: parseFloat(MAP_LAT), lng: parseFloat(MAP_LONG)}); 
	/*hide features(label) on the map*/
   map.setOptions({ styles: map_styles["hide"] });

	/*const locationButton = document.createElement("button");
  	locationButton.textContent = "Current Location";
  	locationButton.classList.add("btn");
  	map.controls[google.maps.ControlPosition.TOP_LEFT].push(locationButton);
*/
  	let controlCurrentPosition = document.createElement("button");
  	controlCurrentPosition.style.color = "rgb(25,25,25)";
 	controlCurrentPosition.style.background = "rgb(255,255,255)";
 	controlCurrentPosition.style.border = "0px";
  	controlCurrentPosition.style.fontSize = "16px";
  	controlCurrentPosition.style.lineHeight = "35px";
 	controlCurrentPosition.style.paddingLeft = "5px";
  	controlCurrentPosition.style.paddingRight = "5px";
  	controlCurrentPosition.style.marginTop = "12px";
  	controlCurrentPosition.textContent = "Current Location";
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(controlCurrentPosition);


	controlCurrentPosition.addEventListener("click", function(){
    
		getCurrentlatlong(true);

	    	// Try HTML5 geolocation.
	    	/**************************************/
			/*if(navigator.geolocation){
	           // timeout at 60000 milliseconds (60 seconds)
	           var options = {timeout:60000};
	           navigator.geolocation.getCurrentPosition(showLocation, errorHandler, options);
	        } else{
	           alert("Sorry, browser does not support geolocation!");
	        }*/
	        /**************************************/
		  /*if (navigator.geolocation) {
		      	navigator.geolocation.getCurrentPosition(
			        (position) => {
			          const pos = {
			            lat: position.coords.latitude,
			            lng: position.coords.longitude,
			          };

			          infoWindow.setPosition(pos);
			          infoWindow.setContent("Location found.");
			          infoWindow.open(map);
			          map.setCenter(pos);
			        },
		        () => {
		          handleLocationError(true, infoWindow, map.getCenter());
		        }
		      );
		    } else {alert(2222);
		      // Browser doesn't support Geolocation
		      handleLocationError(false, infoWindow, map.getCenter());
		    }*/
		    /*if (navigator.geolocation) {
		    navigator.geolocation.getCurrentPosition(function(position) {
		        var pos = {
		            lat: position.coords.latitude,
		            lng: position.coords.longitude
		        };
		        var lat_and_long = pos.lat+","+pos.lng;
		        alert(lat_and_long);
		    });
		    }*/

		      /*$.ajax({
		    url: 'https://www.googleapis.com/geolocation/v1/geolocate?key='+GOOGLE_GEOCODE_API_KEY,
		    data: JSON.stringify({ "considerIp": "true" }),
		    type: 'POST',
		    contentType: 'application/json',
		    success: function(data) {
		      if(data.location) {
		        alert(data.location.lat + ', ' + data.location.lng);
		      } else {
		        alert('not found');
		      }
		    }
		  });*/
  	});


	/*controlCurrentPosition.addEventListener("click", function(){
		 var apiGeolocationSuccess = function(position) {
		    alert("API geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);
		};

		var tryAPIGeolocation = function() {
		    jQuery.post( "https://www.googleapis.com/geolocation/v1/geolocate?key="+GOOGLE_GEOCODE_API_KEY+"", function(success) {
		        apiGeolocationSuccess({coords: {latitude: success.location.lat, longitude: success.location.lng}});
		  })
		  .fail(function(err) {
		    alert("API Geolocation error! \n\n"+err);
		  });
		};

		var browserGeolocationSuccess = function(position) {
		    alert("Browser geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);
		};

		var browserGeolocationFail = function(error) {
		  switch (error.code) {
		    case error.TIMEOUT:
		      alert("Browser geolocation error !\n\nTimeout.");
		      break;
		    case error.PERMISSION_DENIED:
		      if(error.message.indexOf("Only secure origins are allowed") == 0) {
		        tryAPIGeolocation();
		      }
		      break;
		    case error.POSITION_UNAVAILABLE:
		      alert("Browser geolocation error !\n\nPosition unavailable.");
		      break;
		  }
		};

		var tryGeolocation = function() {
		  if (navigator.geolocation) {
		    navigator.geolocation.getCurrentPosition(
		        browserGeolocationSuccess,
		      browserGeolocationFail,
		      {maximumAge: 50000, timeout: 20000, enableHighAccuracy: true});
		  }
		};

		tryGeolocation();
	});*/
}
function showLocation(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    //console.log(latitude);

    if(latitude != "" && longitude != "")
	{
		currentlatitude = latitude;
		currentlongitude = longitude;

        var posMatrix = {
            lat: latitude,
            lng: longitude
        };

        //Extend each marker's position in LatLngBounds object.
        latlngbounds.extend(posMatrix);

        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);
        //alert('111');
	}
}
function errorHandler(err) {
	if(err.code == 1) {
		alert("Error: Access is denied!");
	} else if( err.code == 2) {
		alert("Error: Position is unavailable!");
	}
}
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  /*infoWindow.setPosition(pos);
  infoWindow.setContent(
    browserHasGeolocation
      ? "Error: The Geolocation service failed."
      : "Error: Your browser doesn't support geolocation."
  );
  infoWindow.open(map);*/
  console.log(browserHasGeolocation);
}

function getCurrentlatlong($setposition = false){
	/*$.ajax({
	    url: site_url+"vmap/index",
	    data: { "mode": "getCurrentLocation" },
	    type: 'POST',
		dataType: "json",
	    success: function(data) {
	     	//console.log("current location=>"+data);
	     	currentlatitude = data['geo']['latitude'];
	     	currentlongitude = data['geo']['longitude'];
	     	//alert('current lat=>'+currentlatitude+"=>long=>"+currentlongitude);
			var bounds = new google.maps.LatLngBounds();
	     	if($setposition == true && currentlatitude != "" && currentlongitude != "")
	     	{
	            var posMatrix = {
	                lat: currentlatitude,
	                lng: currentlongitude
	            };

	            //Extend each marker's position in LatLngBounds object.
                //latlngbounds.extend(posMatrix);
                bounds.extend(posMatrix);

	            map.setCenter(bounds.getCenter());
	            map.fitBounds(bounds);
	            //alert('111');
			}
	    }
	});*/
	if (navigator.geolocation) {
	      	navigator.geolocation.getCurrentPosition(
		        (position) => {
		          const pos = {
		            lat: position.coords.latitude,
		            lng: position.coords.longitude,
		          };

		          /*infoWindow.setPosition(pos);
		          infoWindow.setContent("Location found.");
		          infoWindow.open(map);*/
		          	currentlatitude = position.coords.latitude;
	     			currentlongitude = position.coords.longitude;
	     			console.log('lat=>'+position.coords.latitude);
	     			console.log('lang=>'+position.coords.longitude);
		          map.setCenter(pos);
		        },
	        () => {
	          handleLocationError(true, infoWindow, map.getCenter());
	        }
	      );
	    } else {alert(2222);
	      // Browser doesn't support Geolocation
	      handleLocationError(false, infoWindow, map.getCenter());
	    }
}

function showPolygonMapForpositive(sitePath, map, icon, siteid,siteData) {

	/*polygonObj[pl] = new google.maps.Polygon({
		path: sitePath,
		strokeColor: '#FF0000',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#FF0000',
		fillOpacity: 0.35,
		icon: icon
	});
	$site_map = polygonObj[pl];
	info_positive_popup($site_map, siteid,siteData);
	//info_larval_popup($site_map, siteid,Fieldtask);
	polygonObj[pl].setMap(map);
	pl++;
	*/
	positivesiteMarker[pov] = new google.maps.Polygon({
		path: sitePath,
		strokeColor: '#FF0000',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#FF0000',
		fillOpacity: 0.35,
		icon: icon
	});


	$site_map = positivesiteMarker[pov];
	gmarkers.push($site_map);
	info_positive_popup($site_map, siteid,siteData);

	infoPolygonArea($site_map, siteid);

  positivesiteMarker[pov].setMap(map);
	pov++;
}

function showPointMapForpositive(sitePath, map, icon, siteid,siteData) {
	/*map.setCenter({lat: 41.595526, lng: -72.687145}); */
	/*siteMarker[pCount] = new google.maps.Marker({
		map: map,
		position: sitePath,
		icon: icon
	});

	$site_map = siteMarker[pCount];
	info_positive_popup($site_map, siteid,siteData);
	siteMarker[pCount].setMap(map);
	pCount++;*/
	positivesiteMarker[pov] = new google.maps.Marker({
		map: map,
		position: sitePath,
		icon: icon
	});

	$site_map = positivesiteMarker[pov];
	gmarkers.push($site_map);
	info_positive_popup($site_map, siteid,siteData);
	positivesiteMarker[pov].setMap(map);

	//Extend each marker's position in LatLngBounds object.
    latlngbounds.extend(positivesiteMarker[pov].position);

	pov++;
}

function showPolyLineMapForpositive(sitePath, map, icon, siteid,siteData) {
	/*polyLineObj[pline] = new google.maps.Polyline({
		path: sitePath,
		strokeColor: '#FF0000',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#FF0000',
		fillOpacity: 0.35,
		icon: icon,
	});

	info_positive_popup(polyLineObj[pline], siteid,siteData);
	polyLineObj[pline].setMap(map);
	pline++;*/
	positivesiteMarker[pov] = new google.maps.Polyline({
		path: sitePath,
		strokeColor: '#FF0000',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#FF0000',
		fillOpacity: 0.35,
		icon: icon,
	});

	info_positive_popup(positivesiteMarker[pov], siteid,siteData);
	positivesiteMarker[pov].setMap(map);

	//Extend each marker's position in LatLngBounds object.
    //latlngbounds.extend(positivesiteMarker[pov].position);
    positivesiteMarker[pov].getPath().forEach(function (path, index) {                              
        latlngbounds.extend(path);
    });


	pov++;
}

function showPolyCenterForpositive(sitePath, map, icon, siteid,siteData) {
	/*pCenterMarker[pCenter] = new google.maps.Marker({
		position: sitePath,
		map: map,
		icon: icon,
	});

	$site_map = pCenterMarker[pCenter];
	info_positive_popup($site_map, siteid,siteData);
	pCenterMarker[pCenter].setMap(map);
	pCenter++;*/
	positivesiteMarker[pov] = new google.maps.Marker({
		position: sitePath,
		map: map,
		icon: icon,
	});

	$site_map = positivesiteMarker[pov];
	gmarkers.push($site_map);
	info_positive_popup($site_map, siteid,siteData);
	positivesiteMarker[pov].setMap(map);

	//Extend each marker's position in LatLngBounds object.
    latlngbounds.extend(positivesiteMarker[pov].position);

	pov++;
}
function showPolygonMapForfieldtask(sitePath, map, icon, siteid,Fieldtask) {

	polygonObj[pl] = new google.maps.Polygon({
		path: sitePath,
		strokeColor: '#FF0000',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#FF0000',
		fillOpacity: 0.35,
		icon: icon,
	});

	$site_map = polygonObj[pl];
	info_fieldtask_popup($site_map, siteid,Fieldtask);
	
	infoPolygonArea($site_map, siteid);
        
	polygonObj[pl].setMap(map);
	pl++;
}

function showPointMapForfieldtask(sitePath, map, icon, siteid,Fieldtask,count) {
	/*map.setCenter({lat: 41.595526, lng: -72.687145}); */
    color: 'white';
	if(count >=0 && count <=10)
	{
		color = "green";
	}
	else if(count >=11 && count <=50)
	{
		color = "yellow";
	}
	else if(count >=51 && count <=200)
	{
		color = "blue";
	}
	else if(count >=201 && count <=500)
	{
		color = "orange";
	}
	else if(count >=501)
	{
		color = "red";
	}else{
		color = "white";
	}
	/*alert(color);
	alert(count);*/
	var mIcon = {
		path: google.maps.SymbolPath.CIRCLE,
		fillOpacity: 1,
		fillColor: color,
		strokeOpacity: 1,
		strokeWeight: 1,
		strokeColor: '#333',
		scale: 15
	};
	siteMarker[pCount] = new google.maps.Marker({
		map: map,
		position: sitePath,
		icon: mIcon,
		label: {color: 'black', fontSize: '12px', fontWeight: '600',text: count}
	});
	$site_map = siteMarker[pCount];
	info_fieldtask_popup($site_map, siteid,Fieldtask);
	siteMarker[pCount].setMap(map);

    //Extend each marker's position in LatLngBounds object.
    latlngbounds.extend(siteMarker[pCount].position);

	pCount++;
}

function showPolyLineMapForfieldtask(sitePath, map, icon, siteid,Fieldtask) {
	polyLineObj[pline] = new google.maps.Polyline({
		path: sitePath,
		strokeColor: '#FF0000',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#FF0000',
		fillOpacity: 0.35,
		icon: icon,
	});

	$site_map = polyLineObj[pline];
	info_fieldtask_popup($site_map, siteid,Fieldtask);
	polyLineObj[pline].setMap(map);

	//Extend each marker's position in LatLngBounds object.
    //latlngbounds.extend(polyLineObj[pline].position);
    polyLineObj[pline].getPath().forEach(function (path, index) {                                
        latlngbounds.extend(path);
    });

	pline++;
}

function showPolyCenterForfieldtask(sitePath, map, icon, siteid,Fieldtask) {
	pCenterMarker[pCenter] = new google.maps.Marker({
		position: sitePath,
		map: map,
		icon: icon,
	});

	$site_map = pCenterMarker[pCenter];
	
	info_fieldtask_popup($site_map, siteid,Fieldtask);
	pCenterMarker[pCenter].setMap(map);

	//Extend each marker's position in LatLngBounds object.
    latlngbounds.extend(pCenterMarker[pCenter].position);

	pCenter++;
}

function showPolygonMap(sitePath, map, icon, siteid) {

	polygonObj[pl] = new google.maps.Polygon({
		path: sitePath,
		strokeColor: '#FF0000',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#FF0000',
		fillOpacity: 0.35,
		icon: icon,
	});

	$site_map = polygonObj[pl];
	info_popup($site_map, siteid);

	infoPolygonArea($site_map, siteid);
        
	polygonObj[pl].setMap(map);
	pl++;
}

function showZonePolygonMap(sitePath, map) {
	//console.log(sitePath);
	zonePolygonObj[zCount] = new google.maps.Polygon({
		path: sitePath,
		strokeColor: '#FF9C6E',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#FF9C6E',
		fillOpacity: 0,
	});


	$site_map = zonePolygonObj[zCount];

	zonePolygonObj[zCount].setMap(map);

	//Extend each marker's position in LatLngBounds object.
    var bounds = new google.maps.LatLngBounds();
    zonePolygonObj[zCount].getPath().forEach(function (path, index) {
        bounds.extend(path);
        //latlngbounds.extend(path);
    });
    map.fitBounds(bounds);

	zCount++;
}

function showPointMapForSr(sitePath, map, icon, siteid, vName, vAddress, vRequestType, vAssignTo, vStatus) {
	/*map.setCenter({lat: 41.595526, lng: -72.687145}); */
	//console.log('pCount=>'+pCount);
	srlayerMarker[srCount] = new google.maps.Marker({
		map: map,
		position: sitePath,
		icon: icon
	});
	//if (srFilter.length != 0) {
		newLocation(sitePath.lat,sitePath.lng);
	//}
	$sr_map = srlayerMarker[srCount];
	gmarkers.push($sr_map);
	srinfo_popup($sr_map, siteid, vName, vAddress, vRequestType, vAssignTo, vStatus);
	srlayerMarker[srCount].setMap(map);

	//Extend each marker's position in LatLngBounds object.
    latlngbounds.extend(srlayerMarker[srCount].position);


	srCount++;


	/*siteMarker[pCount] = new google.maps.Marker({
		map: map,
		position: sitePath,
		icon: icon
	});
	if (srFilter.length != 0) {
		newLocation(sitePath.lat,sitePath.lng);
	}
	$sr_map = siteMarker[pCount];

	srinfo_popup($sr_map, siteid, vName, vAddress, vRequestType, vAssignTo, vStatus);
	siteMarker[pCount].setMap(map);
	pCount++;
	gmarkers.push($sr_map);*/
}

function showPointMap(sitePath, map, icon, siteid) {
	//alert(sitePath);
	/*map.setCenter({lat: 41.595526, lng: -72.687145}); */
	siteMarker[pCount] = new google.maps.Marker({
		map: map,
		position: sitePath,
		icon: icon
	});
	//if (siteFilter.length != 0) {
		newLocation(sitePath.lat,sitePath.lng);
	//}
	$site_map = siteMarker[pCount];
	gmarkers.push($site_map);
	info_popup($site_map, siteid);
	siteMarker[pCount].setMap(map);

	//Extend each marker's position in LatLngBounds object.
   	latlngbounds.extend(siteMarker[pCount].position);

	pCount++;
}

function showPolyLineMap(sitePath, map, icon, siteid) {
	
	polyLineObj[pline] = new google.maps.Polyline({
		path: sitePath,
		strokeColor: '#FF0000',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#FF0000',
		fillOpacity: 0.35,
		icon: icon,
	});
	//if (siteFilter.length != 0) {
		newLocation(sitePath.lat,sitePath.lng);
	//}
	$site_map = polyLineObj[pline];
	info_popup($site_map, siteid);
	polyLineObj[pline].setMap(map);

	//Extend each marker's position in LatLngBounds object.
   	//latlngbounds.extend(polyLineObj[pline].position);
   	polyLineObj[pline].getPath().forEach(function (path, index) {                                
        latlngbounds.extend(path);
    });

	pline++;
}

function showPolyCenter(sitePath, map, icon, siteid) {
	pCenterMarker[pCenter] = new google.maps.Marker({
		position: sitePath,
		map: map,
		icon: icon,
	});
	//if (siteFilter.length != 0) {
		newLocation(sitePath.lat,sitePath.lng);
	//}
	$site_map = pCenterMarker[pCenter];
	info_popup($site_map, siteid);
	pCenterMarker[pCenter].setMap(map);

	//Extend each marker's position in LatLngBounds object.
    latlngbounds.extend(pCenterMarker[pCenter].position);


	pCenter++;
}

// Handles click events on a map, and adds a new point to the Polyline.
function addLatLng(event) {
	var path = poly.getPath();


	// Because path is an MVCArray, we can simply append a new coordinate
	// and it will automatically appear.
	path.push(event.latLng);
	//console.log(polylineMarker);
	// Add a new marker at the new plotted point on the polyline.
	polylineMarker[polylineCount] = new google.maps.Marker({
		position: event.latLng,
		title: '#' + path.getLength(),
		map: map
	});
	if (m1) {
		var distance = haversine_distance(m1, polylineMarker[polylineCount]);
		totalDistance = totalDistance + distance;
		//alert(totalDistance.toFixed(2));
		var distanceFt = totalDistance * 5280;
		$("#distanceinmiles").val(totalDistance.toFixed(2));
		$("#distanceinft").val(distanceFt.toFixed(2));
	}
	m1 = polylineMarker[polylineCount];
	polylineCount++;
}

// Handles click events on a map, and adds a new point to the Polyline.
function addLatLngPoly(event) {
	var path = poly.getPath();


	// Because path is an MVCArray, we can simply append a new coordinate
	// and it will automatically appear.
	path.push(event.latLng);
	//console.log(marker);
	// Add a new marker at the new plotted point on the polyline.
	polygonMarker[polygonCount] = new google.maps.Marker({
		position: event.latLng,
		title: '#' + path.getLength(),
		map: map
	});

	var polyArea = google.maps.geometry.spherical.computeArea(poly.getPath());
	//console.log(polyArea);

	var sqMile = polyArea.toFixed(2) * parseFloat('0.00000039');
	console.log('mile: ' + sqMile);
	var sqFeet = sqMile.toFixed(2) * parseInt('27878400');
	console.log('Feet: ' + sqFeet);
	$("#areainmiles").val(sqMile.toFixed(2));
	$("#areainft").val(sqFeet.toFixed(2));
	
	polygonCount++;

}

// Handles click events on a map, and adds Circle Shape.
function addLatLngCircle(event) {

	/*if (cityCircle !== undefined) {
		console.log('clear Circle');
		cityCircle.setMap(null);
		//initMap();
		if (circleMarker !== undefined) {
			for (var i = 0; i < circleMarker.length; i++) {
				circleMarker[i].setMap(null);
			}
		}
	}*/
	//console.log('CIRCLE=>'+typeof cityCircle);
	if (typeof cityCircle == "object") {
		//console.log('clear Circle');
		cityCircle.setMap(null);
	}

	if (circleMarker !== undefined) {
			for (var i = 0; i < circleMarker.length; i++) {
				circleMarker[i].setMap(null);
			}
		}

	//console.log(cmCount);
	circleMarker[cmCount] = new google.maps.Marker({
		position: event.latLng,
		//title: '#' + path.getLength(),
		map: map
	});
	circleMarker[cmCount].setMap(map);
	if (!cntrOfCircle) {
		cntrOfCircle = circleMarker[cmCount].getPosition();
	}
	//console.log(cntrOfCircle);

	if (cm) {
		var distance = haversine_distance(cm, circleMarker[cmCount]);
		//console.log(distance);

		var circleRadius = distance * parseFloat('1609.34');
		var circleArea = (parseFloat('3.14') * (circleRadius * circleRadius));
		//console.log(circleRadius);

		cityCircle = new google.maps.Circle({
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0.35,
			map: map,
			center: cntrOfCircle,
			radius: parseFloat(circleRadius)
		});

		cityCircle.setMap(map);

		$("#rCircle").val(circleRadius);
		$("#areaCircle").val(circleArea);

		cm = false;
		cntrOfCircle = false;
		//circleMarker = [];

	} else {
		cm = circleMarker[cmCount];
	}

	cmCount++;
}

function haversine_distance(mk1, mk2) {
	var R = 3958.8; // Radius of the Earth in miles
	var rlat1 = mk1.position.lat() * (Math.PI / 180); // Convert degrees to radians
	var rlat2 = mk2.position.lat() * (Math.PI / 180); // Convert degrees to radians
	var difflat = rlat2 - rlat1; // Radian difference (latitudes)
	var difflon = (mk2.position.lng() - mk1.position.lng()) * (Math.PI / 180); // Radian difference (longitudes)

	var d = 2 * R * Math.asin(Math.sqrt(Math.sin(difflat / 2) * Math.sin(difflat / 2) + Math.cos(rlat1) * Math.cos(rlat2) * Math.sin(difflon / 2) * Math.sin(difflon / 2)));
	return d;
}

function generateJson() {
	//console.log(site_url + "vmap/api");
	$.ajax({
		type: "POST",
		url: site_url + "vmap/api",
		data: {
			action: "getData",
		},
		success: function(data) {
			console.log(data);
		}
	});
}

function generateSRJson() {
	//console.log(site_url + "vmap/api");
	$.ajax({
		type: "POST",
		url: site_url + "vmap/api",
		data: {
			action: "getSrData",
		},
		success: function(data) {
			console.log(data);
		}
	});
}

function generatelandingrateJson() {
	//console.log(site_url + "vmap/api");
	$.ajax({
		type: "POST",
		url: site_url + "vmap/api",
		data: {
			action: "getlandingrateData",
		},
		success: function(data) {
			console.log(data);
		}
	});
}
function getlarvalJson() {
	//console.log(site_url + "vmap/api");
	$.ajax({
		type: "POST",
		url: site_url + "vmap/api",
		data: {
			action: "getlarvalData",
		},
		success: function(data) {
			console.log(data);
		}
	});
}
function getpositiveJson() {
	//console.log(site_url + "vmap/api");
	$.ajax({
		type: "POST",
		url: site_url + "vmap/api",
		data: {
			action: "getpositiveData",
		},
		success: function(data) {
			console.log('getpositiveData');
			console.log(data);
		}
	});
}
function getCustomLayerJson() {
	$.ajax({
		type: "POST",
		url: site_url + "vmap/api",
		data: {
			action: "getCustomLayerData",
		},
		success: function(data) {
			console.log(data);
		}
	});
}

function clearMap() {
	console.log('11');


	if (polygonObj.length > 0) {
        for (i = 0; i < polygonObj.length; i++) {
            polygonObj[i].setMap(null);
        }
    }

    if (polyLineObj.length > 0) {
        for (i = 0; i < polyLineObj.length; i++) {
            polyLineObj[i].setMap(null);
        }
    }
   if (positivesiteMarker.length > 0) {
        for (i = 0; i < positivesiteMarker.length; i++) {
            positivesiteMarker[i].setMap(null);
        }
    }
	

	if (pCenterMarker.length > 0) {
        for (i = 0; i < pCenterMarker.length; i++) {
            pCenterMarker[i].setMap(null);
        }
    }

	if(siteMarker.length > 0){
		for (i = 0; i < siteMarker.length; i++) {
            siteMarker[i].setMap(null);
        }
	}

	if (zonePolygonObj.length > 0) {
        for (i = 0; i < zonePolygonObj.length; i++) {
            zonePolygonObj[i].setMap(null);
        }
    }

    if (srlayerMarker.length > 0) {
        for (i = 0; i < srlayerMarker.length; i++) {
            srlayerMarker[i].setMap(null);
        }
    }

    

	if (zonePolygonObj !== undefined) {
		zonePolygonObj = [];
	}
	if (polygonObj !== undefined) {
		polygonObj = [];
	}
	if (polyLineObj !== undefined) {
		polyLineObj = [];
	}
	if (siteMarker !== undefined) {
		siteMarker = [];
	}
	if (pCenterMarker !== undefined) {
		pCenterMarker = [];
	}

	if (positivesiteMarker !== undefined) {
		positivesiteMarker = [];
	}

	if (srlayerMarker !== undefined) {
		srlayerMarker = [];
	}
	/*if (fieldmap_sr_arr !== undefined) {
		fieldmap_sr_arr = [];

	}
	if (landing_rate !== undefined) {
		landing_rate = [];
	}
	if (larval !== undefined) {
		larval = [];
	}
	if (Fieldtask !== undefined) {
		Fieldtask = [];
	}

	if (positive !== undefined) {
		positive = [];
	}*/

	/*siteTypes.length = 0;
	sAttr.length = 0;
	skCity.length = 0;
	skZones.length = 0;
	fieldmap_sr_arr.length = 0;
	landing_rate.length = 0;
	larval.length = 0;
	Fieldtask.length = 0;
	positive.length = 0;
	siteMarker.length = 0;
	zonePolygonObj.length = 0;
	polyLineObj.length=0;*/


	 var clayers = customeLayerArr.length;
    if (clayers > 0) {
        for (i = 0; i < clayers; i++) {
            customeLayerArr[i].setMap(null);
        }
    }
    customeLayerArr.length = 0;

	pCount =0;
	pl =0;
	pline = 0;
	zCount = 0;
	pCenter =0;
	pov = 0;
	srCount=0;

	if (markerCluster && markerCluster.setMap) {
        markerCluster.clearMarkers();
        
    }

  console.log('resetmap');
    initMap();


			
}

function info_popup(marker, siteid) {
	//console.log(siteid);

	google.maps.event.addListener(marker, 'click', ( function(marker, siteid) {
		return function(arg) {
			 

			var content = "";
			__marker__ = marker;
			$.ajax({
				type: "POST",
				dataType: "json",
				url: site_url + "vmap/index",
				data: 'mode=site_map&iSiteId=' + siteid,
				success: function(data) {

					if (data.site.length > 0) {
							//console.log(data.site);
						var vName = '';
						if (typeof data.site[0]['vName'] != "undefined" && data.site[0]['vName'] != null && data.site[0]['vName'] != '') {
							vName += data.site[0]['vName'];
						}
						if (typeof data.site[0]['vSubTypeName'] !== "undefined" && data.site[0]['vSubTypeName'] != null && data.site[0]['vSubTypeName'] != '') {
							type_str += " " + data.site[0]['vSubTypeName'];
						}

						var type_str = '';
						if (typeof data.site[0]['vTypeName'] !== "undefined" && data.site[0]['vTypeName'] != null && data.site[0]['vTypeName'] != '') {
							type_str += data.site[0]['vTypeName'];
						}
						if (typeof data.site[0]['vSubTypeName'] !== "undefined" && data.site[0]['vSubTypeName'] != null && data.site[0]['vSubTypeName'] != '') {
							type_str += " " + data.site[0]['vSubTypeName'];
						}

						if (typeof data.site_attribute !== "undefined" && data.site_attribute != '') {
							type_str += " (" + data.site_attribute + ")";
						}
						var address_str = '';
						if (typeof data.site[0]['vAddress1'] !== "undefined" && data.site[0]['vAddress1'] != null && data.site[0]['vAddress1'] != '') {
							address_str += data.site[0]['vAddress1'];
						}
						if (typeof data.site[0]['vStreet'] !== "undefined" && data.site[0]['vStreet'] != null && data.site[0]['vStreet'] != '') {
							address_str += " " + data.site[0]['vStreet'];
						}
						if (typeof data.site[0]['vCity'] !== "undefined" && data.site[0]['vCity'] != null && data.site[0]['vCity'] != '') {
							address_str += " ," + data.site[0]['vCity'];
						}
						if (typeof data.site[0]['vState'] !== "undefined" && data.site[0]['vState'] != null && data.site[0]['vState'] != '') {
							address_str += " ," + data.site[0]['vState'];
						}
						content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
						content += "<h5 class='border-bottom pb-2 mb-3'>Premise ID " + data.site[0]['iSiteId'] + " " + vName + "</h5>";
						content += "<h6>" + type_str + "</h6>";
						content += "<strong>" + address_str + "</strong>";
						content += "<div class='button mt-3'>";
						content += "<a class='btn btn-primary  mr-2 text-white' title='Landing Rate' onclick=addEditDataTaskAdult(0,'add','" + siteid + "')>Landing Rate</a><a class='btn btn-primary  mr-2 text-white' title='Larval Surveillance' onclick=addEditDataTaskLarval(0,'add','" + siteid + "')>Larval Surveillance</a><a class='btn btn-primary  mr-2 text-white' title='Treatment Task' onclick=addEditDataTaskTreatment(0,'add','" + siteid + "')>Treatment</a><a class='btn btn-primary  mr-2 text-white' title='Trap' onclick=addEditDataTaskTrap(0,'add','" + siteid + "')>Trap Place</a><a class='btn btn-primary  mr-2 text-white' title='Other Task' onclick=addEditDataTaskOther(0,'add','" + siteid + "')>Other</a><a class='btn btn-primary  mr-2 text-white' href='" + site_url + "premise/edit&mode=Update&iSiteId=" + siteid + "' target='_blank'>Edit premise</a>";
						if(ENABLE_INSTA_TREATMENT=='Y'){
							content += "<a class='btn btn-primary  mr-2 text-white' title='Landing Rate' onclick=addInstaTreatData('add','" + siteid + "')>Insta Treat</a>";
						}
						content += "</div>";
						
						if (typeof data.site_history !== "undefined" && data.site_history != null && data.site_history != '') {
							
							if (data.site_history.length > 0) {
								content += "<h5 class='border-bottom pb-2 mb-3 mt-3'>History</h5>";
								siteInfoWindowTaskOtherArr = [];
								siteInfoWindowTaskLandingArr = [];
								siteInfoWindowTaskTrapArr = [];
								siteInfoWindowTaskLarvalArr = [];
								siteInfoWindowTaskTreatmentArr = [];
								$.each(data.site_history, function(index, item) {
									if(item['Type'] == "Treatment") {
										siteInfoWindowTaskTreatmentArr.push(item['hidden_arr']);
										content += item['hidden_fields']+"<span class='w-100 d-block  pb-2'><a href='javascript:void(0);' onclick='addEditDataTaskTreatment("+item['id']+",\"edit\",0)'>" + item['Date'] + " " + item['Description'] + "</a></span>";
									}
									else if(item['Type'] == "Landing Rate") {
										siteInfoWindowTaskLandingArr.push(item['hidden_arr']);
										content += item['hidden_fields']+"<span class='w-100 d-block  pb-2'><a href='javascript:void(0);' onclick='addEditDataTaskAdult("+item['id']+",\"edit\",0)'>" + item['Date'] + " " + item['Description'] + "</a></span>";
									}
									else if(item['Type'] == "Task Trap") {
										siteInfoWindowTaskTrapArr.push(item['hidden_arr']);
										content += item['hidden_fields']+"<span class='w-100 d-block  pb-2'><a href='javascript:void(0);' onclick='addEditDataTaskTrap("+item['id']+",\"edit\",0)'>" + item['Date'] + " " + item['Description'] + "</a></span>";
									}
									else if(item['Type'] == "Laravel Surveillance") {
										siteInfoWindowTaskLarvalArr.push(item['hidden_arr']);
										content += item['hidden_fields']+"<span class='w-100 d-block  pb-2'><a href='javascript:void(0);' onclick='addEditDataTaskLarval("+item['id']+",\"edit\",0)'>" + item['Date'] + " " + item['Description'] + "</a></span>";
									}
									else if(item['Type'] == "Other") {
										siteInfoWindowTaskOtherArr.push(item['hidden_arr']);
										content += item['hidden_fields']+"<span class='w-100 d-block  pb-2'><a href='javascript:void(0);' onclick='addEditDataTaskOther("+item['id']+",\"edit\",0)'>" + item['Date'] + " " + item['Description'] + "</a></span>";
									}
									else if(item['Type'] == "SR") {
										var sr_url = site_url+"sr/edit&mode=Update&iSRId="+item['id'];
										content += "<span class='w-100 d-block  pb-2'><a href='"+sr_url+"' target='_blank'>" + item['Date'] + " " + item['Description'] + "</a></span>";
									}
									//content += "<span class='w-100 d-block  pb-2'><a href='javascript:void(0);'>" + item['Date'] + " " + item['Description'] + "</a></span>";
								});
							}
						}
						content += "</div>";
						
						if (infowindow) {
							infowindow.close();
						}

	
						infowindow = new google.maps.InfoWindow({
							content: content,
							zIndex: 100,
							position: arg.latLng,
						});
						
						infowindow.open(map, marker);
						
					}
				}
			});
			google.maps.event.clearListeners(marker, 'mouseout');
		}
	})(marker, siteid));
}

function srinfo_popup(marker, siteid, vName, vAddress, vRequestType, vAssignTo, vStatus) {
	var full_name = vName;
	google.maps.event.addListener(marker, 'click', (function(marker, siteid, full_name, vAddress, vRequestType, vAssignTo, vStatus) {
		return function() {
			var content = "";
			__marker__ = marker;
			var vName = full_name;
			if (vName == null || vName == 'undefined' || vName == '') {
				vName = '';
			} else {
				vName = "(" + full_name + ")";
			}
			if (vAddress == null || vAddress == 'undefined' || vAddress == '') {
				vAddress = '';
			} else {
				vAddress = vAddress;
			}
			if (vRequestType == null || vRequestType == 'undefined' || vRequestType == '') {
				vRequestType = '';
			} else {
				vRequestType = vRequestType;
			}
			if (vStatus == null || vStatus == 'undefined' || vStatus == '') {
				vStatus = '';
			} else {
				vStatus = vStatus;
			}
			
			content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
			content += "<h5 class='border-bottom pb-2 mb-3'>Fiber Inquiry Id " + siteid + " " + vName + "</h5>";
			content += "<div class='d-flex'><h6>" + vRequestType + "</h6></div>";
			content += "<div class='d-flex'><span>" + vAddress + "</span></div>";
			content += "<div class='d-flex'><b>Status : </b>&nbsp;<span>" + vStatus + "</span></div>";
			content += "<div class='button mt-3'><a class='btn btn-primary  mr-2 text-white' href='" + site_url + "fiber_inquiry/edit&mode=Update&iFiberInquiryId=" + siteid + "' target='_blank'>Edit Fiber Inquiry</a></div>";
			content += "</div>";
			if (infowindow) {
				infowindow.close();
			}
			infowindow = new google.maps.InfoWindow({
				content: content,
				zIndex: 100
			});
			infowindow.open(map, marker);
			gmarkers.push(marker);
			google.maps.event.clearListeners(marker, 'mouseout');
		}
	})(marker, siteid, full_name, vAddress, vRequestType, vAssignTo, vStatus));
}
function info_fieldtask_popup(marker, siteid, Fieldtask) {
	// calculate single files task after add multi select //
	google.maps.event.addListener(marker, 'click', (function(marker, siteid, Fieldtask) {
		return function() {
			var content = "";
			__marker__ = marker;
			$.ajax({
				type: "POST",
				dataType: "json",
				url: site_url + "vmap/index",
				data: 'mode=site_map_'+Fieldtask[0]+'&iSiteId=' + siteid+"&fieldtask="+Fieldtask[0],
				success: function(data){
					if(Fieldtask[0] == 'landing_rate'){
						//var vName = data.site[0]['vName'];
						var vName = '' ;
						console.log(data);
						if(Object.keys(data.site).length > 0){
							/*if (typeof data.site[0]['vName'] == 'undefined' ||  data.site[0]['vName'] ==  null || data.site[0]['vName'] == '') {
								vName = '';
							} else {
								vName = "(" + data.site[0]['vName'] + ")";
							}*/
							if (typeof data.site[0]['vName'] !== 'undefined' &&  data.site[0]['vName'] ==  null && data.site[0]['vName'] !== '') {
								vName = "(" + data.site[0]['vName'] + ")";
							} else {
								vName = '';
							}

							var type_str = '';
							if (typeof data.site[0]['vTypeName'] !== "undefined" && data.site[0]['vTypeName'] != null && data.site[0]['vTypeName'] != '') {
								type_str += data.site[0]['vTypeName'];
							}
							if (typeof data.site[0]['vSubTypeName'] !== "undefined" && data.site[0]['vSubTypeName'] != null && data.site[0]['vSubTypeName'] != '') {
								type_str += " " + data.site[0]['vSubTypeName'];
							}

							if (typeof data.site_attribute !== "undefined" && data.site_attribute != '') {
								type_str += " (" + data.site_attribute + ")";
							}
							var address_str = '';
							if (typeof data.site[0]['vAddress1'] !== "undefined" && data.site[0]['vAddress1'] != null && data.site[0]['vAddress1'] != '') {
								address_str += data.site[0]['vAddress1'];
							}
							if (typeof data.site[0]['vStreet'] !== "undefined" && data.site[0]['vStreet'] != null && data.site[0]['vStreet'] != '') {
								address_str += " " + data.site[0]['vStreet'];
							}
							if (typeof data.site[0]['vCity'] !== "undefined" && data.site[0]['vCity'] != null && data.site[0]['vCity'] != '') {
								address_str += " ," + data.site[0]['vCity'];
							}
							if (typeof data.site[0]['vState'] !== "undefined" && data.site[0]['vState'] != null && data.site[0]['vState'] != '') {
								address_str += " ," + data.site[0]['vState'];
							}
							var date_str = '';
							var srid_str = '';
							var tNotes_str = '';
							var vMaxLandingRate = '';
							content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
							content += "<h5 class='border-bottom pb-2 mb-3'>Premise ID " + data.site[0]['iSiteId'] + " " + vName + "</h5>";
							content += "<h6>" + type_str + "</h6>";
							content += "<strong>" + address_str + "</strong>";
							if (typeof data.site !== "undefined" && data.site != null && data.site != '') {
								if (data.site.length > 0) {
									content += "<h5 class='border-bottom pb-2 mb-3 mt-3'></h5>";
									content += "<table class=table>";
									content += "<thead class='thead-dark'>";
									content += "<tr>";
									content += "<th>Date</th><th>Summary</th><th>SR</th><th>Notes</th>";
									content += "</tr>";
									content += "</thead><tbody>";
									$.each(data.site, function(index, item) {
										if (typeof data.site[index]['dDate'] !== "undefined" && data.site[index]['dDate'] != null && data.site[index]['dDate'] != '') {
											date_str = data.site[index]['dDate'];
										}
										if (typeof data.site[index]['iSRId'] !== "undefined" && data.site[index]['iSRId'] != null && data.site[index]['iSRId'] != '') {
											srid_str = data.site[index]['iSRId'];
										}
										if (typeof data.site[index]['tNotes'] !== "undefined" && data.site[index]['tNotes'] != null && data.site[index]['tNotes'] != '') {
											tNotes_str = data.site[index]['tNotes'];
										}
										if (typeof data.site[index]['vMaxLandingRate'] !== "undefined" && data.site[index]['vMaxLandingRate'] != null && data.site[0]['vMaxLandingRate'] != '') {
											vMaxLandingRate = data.site[index]['vMaxLandingRate'];
										}
										content += "<tr>";
										content += "<td>" + date_str + "</td><td>Landing Rate" + vMaxLandingRate + "</td><td>" + srid_str + "</td><td>" + tNotes_str + "</td>";
										content += "</tr>";
									});
									content += "</tbody></table>";
								}
							}
							content += "</div>";
						}
					}
					else if(Fieldtask[0] == 'larval'){
						if(Object.keys(data.site).length > 0){
							var vName = data.site[0]['vName'];
							if (vName == null || vName == 'undefined' || vName == '') {
							vName = '';
							} else {
								vName = "(" + data.site[0]['vName'] + ")";
							}

							var type_str = '';
							if (typeof data.site[0]['vTypeName'] !== "undefined" && data.site[0]['vTypeName'] != null && data.site[0]['vTypeName'] != '') {
								type_str += data.site[0]['vTypeName'];
							}
							if (typeof data.site[0]['vSubTypeName'] !== "undefined" && data.site[0]['vSubTypeName'] != null && data.site[0]['vSubTypeName'] != '') {
								type_str += " " + data.site[0]['vSubTypeName'];
							}

							if (typeof data.site_attribute !== "undefined" && data.site_attribute != '') {
								type_str += " (" + data.site_attribute + ")";
							}
							var address_str = '';
							if (typeof data.site[0]['vAddress1'] !== "undefined" && data.site[0]['vAddress1'] != null && data.site[0]['vAddress1'] != '') {
								address_str += data.site[0]['vAddress1'];
							}
							if (typeof data.site[0]['vStreet'] !== "undefined" && data.site[0]['vStreet'] != null && data.site[0]['vStreet'] != '') {
								address_str += " " + data.site[0]['vStreet'];
							}
							if (typeof data.site[0]['vCity'] !== "undefined" && data.site[0]['vCity'] != null && data.site[0]['vCity'] != '') {
								address_str += " ," + data.site[0]['vCity'];
							}
							if (typeof data.site[0]['vState'] !== "undefined" && data.site[0]['vState'] != null && data.site[0]['vState'] != '') {
								address_str += " ," + data.site[0]['vState'];
							}
							var date_str = '';
							var srid_str = '';
							var tNotes_str = '';
							var vMaxLandingRate = '';
							var Summary = '';
							var iGenus = '';
							var iGenus2 = '';
							var bEggs = '';
							var bEggs2 = '';
							var iDips = '';
							var bInstar1 = '';
							var bInstar2 = '';
							var bInstar3 = '';
							var bInstar4 = '';
							var bInstar12 = '';
							var bInstar22 = '';
							var bInstar32 = '';
							var bInstar42 = '';
							var iCount = '';
							var iCount2 = '';
							var bPupae2 = '';
							var bAdult2 = '';
							var bPupae = '';
							var bAdult = '';
							var rAvgLarvel = '';
							content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
							content += "<h5 class='border-bottom pb-2 mb-3'>Premise ID " + data.site[0]['iSiteId'] + " " + vName + "</h5>";
							content += "<h6>" + type_str + "</h6>";
							content += "<strong>" + address_str + "</strong>";
							if (typeof data.site !== "undefined" && data.site != null && data.site != '') {
								if (data.site.length > 0) {
									content += "<h5 class='border-bottom pb-2 mb-3 mt-3'></h5>";
									content += "<table class=table>";
									content += "<thead class='thead-dark'>";
									content += "<tr>";
									content += "<th>Date</th><th>Summary</th><th>SR</th><th>Notes</th>";
									content += "</tr>";
									content += "</thead><tbody>";
									$.each(data.site, function(index, item) {
										if (typeof data.site[index]['iGenus'] !== "undefined" && data.site[index]['iGenus'] != null && data.site[index]['iGenus'] != '') {
											if(data.site[index]['iGenus'] == 1){
												iGenus = 'Ae.';
											}
											else if(data.site[index]['iGenus'] == 2){
												iGenus = 'An.';
											}
											else if(data.site[index]['iGenus'] == 3){
												iGenus = 'Cs.';
											}
											else if(data.site[index]['iGenus'] == 4){
												iGenus = 'Cx.';
											}
											else {
												iGenus = 'N/A';
											}
										}
										if (typeof data.site[index]['iGenus2'] !== "undefined" && data.site[index]['iGenus2'] != null && data.site[index]['iGenus'] != '') {
											if(data.site[index]['iGenus2'] == 1){
												iGenus2 = 'Ae.';
											}
											else if(data.site[index]['iGenus2'] == 2){
												iGenus2 = 'An.';
											}
											else if(data.site[index]['iGenus2'] == 3){
												iGenus2 = 'Cs.';
											}
											else if(data.site[index]['iGenus2'] == 4){
												iGenus2 = 'Cx.';
											}
											else {
												iGenus = 'N/A';
											}
										}


										if (typeof data.site[index]['bEggs'] !== "undefined" && data.site[index]['bEggs'] != null && data.site[index]['bEggs'] != '' && data.site[index]['bEggs'] == 't') {
											bEggs = ", E";
										}

										if (typeof data.site[index]['bEggs2'] !== "undefined" && data.site[index]['bEggs2'] != null && data.site[index]['bEggs2'] != '' && data.site[index]['bEggs2'] == 't') {
											bEggs2 = ", E";
										}

										if (typeof data.site[index]['bInstar12'] !== "undefined" && data.site[index]['bInstar12'] != null && data.site[index]['bInstar12'] != '' && data.site[index]['bInstar12'] == 't') {
											bInstar12 = ", I1";
										}
										if (typeof data.site[index]['bInstar22'] !== "undefined" && data.site[index]['bInstar22'] != null && data.site[index]['bInstar22'] != '' && data.site[index]['bInstar22'] == 't') {
											bInstar22 = ", I2";
										}
										if (typeof data.site[index]['bInstar32'] !== "undefined" && data.site[index]['bInstar32'] != null && data.site[index]['bInstar32'] != '' && data.site[index]['bInstar32'] == 't') {
											bInstar32 = ", I3";
										}
										if (typeof data.site[index]['bInstar42'] !== "undefined" && data.site[index]['bInstar42'] != null && data.site[index]['bInstar42'] != '' && data.site[index]['bInstar42'] == 't') {
											bInstar42 = ", I4";
										}
										if (typeof data.site[index]['bPupae2'] !== "undefined" && data.site[index]['bPupae2'] != null && data.site[index]['bPupae2'] != '' && data.site[index]['bPupae2'] == 't') {
											bPupae2 = ", P";
										}
										if (typeof data.site[index]['bAdult2'] !== "undefined" && data.site[index]['bAdult2'] != null && data.site[index]['bAdult2'] != '' && data.site[index]['bAdult2'] == 't') {
											bAdult2 = ", A";
										}


										if (typeof data.site[index]['bInstar1'] !== "undefined" && data.site[index]['bInstar1'] != null && data.site[index]['bInstar1'] != '' && data.site[index]['bInstar1'] == 't') {
											bInstar1 = "I1";
										}
										if (typeof data.site[index]['bInstar2'] !== "undefined" && data.site[index]['bInstar2'] != null && data.site[index]['bInstar2'] != '' && data.site[index]['bInstar2'] == 't') {
											bInstar2 = "I2";
										}
										if (typeof data.site[index]['bInstar3'] !== "undefined" && data.site[index]['bInstar3'] != null && data.site[index]['bInstar3'] != '' && data.site[index]['bInstar3'] == 't') {
											bInstar3 = "I3";
										}
										if (typeof data.site[index]['bInstar4'] !== "undefined" && data.site[index]['bInstar4'] != null && data.site[index]['bInstar4'] != '' && data.site[index]['bInstar4'] == 't') {
											bInstar4 = "I4";
										}
										

										if (typeof data.site[index]['bPupae'] !== "undefined" && data.site[index]['bPupae'] != null && data.site[index]['bPupae'] != '' && data.site[index]['bPupae'] == 't') {
											bPupae = "P";
										}
										if (typeof data.site[index]['bAdult'] !== "undefined" && data.site[index]['bAdult'] != null && data.site[index]['bAdult'] != '' && data.site[index]['bAdult'] == 't') {
											bAdult = "A";
										}

										if (typeof data.site[index]['dDate'] !== "undefined" && data.site[index]['dDate'] != null && data.site[index]['dDate'] != '') {
											date_str = data.site[index]['dDate'];
										}
										if (typeof data.site[index]['iSRId'] !== "undefined" && data.site[index]['iSRId'] != null && data.site[index]['iSRId'] != '') {
											srid_str = data.site[index]['iSRId'];
										}
										if (typeof data.site[index]['tNotes'] !== "undefined" && data.site[index]['tNotes'] != null && data.site[index]['tNotes'] != '') {
											tNotes_str = data.site[index]['tNotes'];
										}

										if (typeof data.site[index]['iDips'] !== "undefined" && data.site[index]['iDips'] != null && data.site[index]['iDips'] != '') {
											iDips = " Dips "+data.site[index]['iDips'];
										}

										if (typeof data.site[index]['rAvgLarvel'] !== "undefined" && data.site[index]['rAvgLarvel'] != null && data.site[index]['rAvgLarvel'] != '') {
											rAvgLarvel = ", Avg Larval: "+data.site[index]['rAvgLarvel']+"";
										}

										if (typeof data.site[index]['iCount'] !== "undefined" && data.site[index]['iCount'] != null && data.site[index]['iCount'] != '') {
											iCount = data.site[index]['iCount'];
										}

										if (typeof data.site[index]['iCount2'] !== "undefined" && data.site[index]['iCount2'] != null && data.site[index]['iCount2'] != '') {
											iCount2 = data.site[index]['iCount2'];
										}
										Summary = 'Larval'+iDips+rAvgLarvel+' <font color=red>|</font> Species 1 : '+iGenus+' '+iCount+bEggs+''+bInstar1+''+bInstar2+''+bInstar3+''+bInstar4+''+bPupae+''+bAdult+'<font color=red> | </font>Species 2 : '+iGenus2+' '+iCount2+bEggs2+''+bInstar12+''+bInstar22+''+bInstar32+''+bInstar42+''+bPupae2+''+bAdult2;
										content += "<tr>";
										content += "<td>" + date_str + "</td><td>" + Summary + "</td><td>" + srid_str + "</td><td>" + tNotes_str + "</td>";
										content += "</tr>";
									});
									content += "</tbody></table>";
								}
							}
							content += "</div>";
						}
					}
					else
					{

					}
					if (infowindow) {
						infowindow.close();
					}
					infowindow = new google.maps.InfoWindow({
						content: content,
						zIndex: 100
					});
					infowindow.open(map, marker, Fieldtask);
				}
			});
			google.maps.event.clearListeners(marker, 'mouseout');
		}
	})(marker, siteid, Fieldtask));
}

function info_positive_popup(marker, siteid, siteData) {
	google.maps.event.addListener(marker, 'click', (function(marker, siteid,iTMPId,iTTId) {
		return function() {
			var content = "";
			__marker__ = marker;
			iTTId = siteData[siteid].iTTId;
			work_staus = "No";
			$.ajax({
				type: "POST",
				dataType: "json",
				url: site_url + "vmap/index",
				data: 'mode=site_map_positive&iTTId='+iTTId,
				success: function(data) {
					if(data.bLabWorkComplete==1)
					{
						work_staus = "Yes";
					}
					else
					{
						work_staus = "No";
					}
					var type_poll_id = '';
					if (typeof data.iTMPId !== "undefined" && data.iTMPId != null && data.iTMPId != '') {
						type_poll_id += data.iTMPId;
					}
					if (typeof data.vPool !== "undefined" && data.vPool != null && data.vPool != '') {
						type_poll_id += "("+ data.vPool;
					}
					if (typeof data.iNumberinPool !== "undefined" && data.iNumberinPool != null && data.iNumberinPool != '') {
						type_poll_id += "Number in Poll"+data.iNumberinPool+")";
					}

					var trap_id = '';
					if (typeof data.iTTId !== "undefined" && data.iTTId != null && data.iTTId != '') {
						trap_id += data.iTTId;
					}
					if (typeof data.vTrapName !== "undefined" && data.vTrapName != null && data.vTrapName != '') {
						trap_id += " (" + data.vTrapName+" Placed  ";
					}
					if (typeof data.dTrapPlaced !== "undefined" && data.dTrapPlaced != null && data.dTrapPlaced != '') {
						trap_id += " "+data.dTrapPlaced+"; Collected  ";
					}
					if (typeof data.dTrapCollected !== "undefined" && data.dTrapCollected != null && data.dTrapCollected != '') {
						trap_id +=" "+data.dTrapCollected+")";
					}

					var site_id = '';
					if (typeof data.iSiteId !== "undefined" && data.iSiteId != null && data.iSiteId != '') {
						site_id += data.iSiteId;
					}
					if (typeof data.vSiteName !== "undefined" && data.vSiteName != null && data.vSiteName != '') {
						site_id += " ("+data.vSiteName+")";
					}
					var vSiteAddress = '';
					if (typeof data.vSiteAddress !== "undefined" && data.vSiteAddress != null && data.vSiteAddress != '') {
						vSiteAddress += data.vSiteAddress;
					}
					var vSiteAddress = '';
					if (typeof data.vSiteAddress !== "undefined" && data.vSiteAddress != null && data.vSiteAddress != '') {
						vSiteAddress += data.vSiteAddress;
					}
					content += "<table style=width:100%;>";
					content += "<tr>";
					content += "<th>Pool Id</th><td>"+type_poll_id+"</td>";
					content += "</tr>";
					content += "<tr>";
					content += "<th>Trap Id</th><td>"+trap_id+"</td>";
					content += "</tr>";
					content += "<tr>";
					content += "<th>Premise ID</th><td>"+site_id+"</td>";
					content += "</tr>";
					content += "<tr>";
					content += "<th>Site Address</th><td>"+vSiteAddress+"</td>";
					content += "</tr>";
					content += "<tr>";
					content += "<th>Lab Work Complate</th><td>"+work_staus+"</td>";
					content += "</tr>";
					content += "</table>";
					if (typeof data.result !== "undefined" && data.result != null && data.result != '') {
						if (data.result.length > 0) {
							content += "<table border=1 style=width:100%;>";
							content += "<tr>";
							content += "<th>Agent</th><th>Test</th><th>Value</th>";
							content += "</tr>"
							$.each(data.result, function(index, item) {
								content += "<tr>"
								content += "<td>"+item['vTitle']+"</td><td>"+item['vResult']+"</td><td>"+item['iValue']+"</td>";
								content += "</tr>";
							});
							content += "</table>";
						}
					}
					if (infowindow) {
						infowindow.close();
					}
					infowindow = new google.maps.InfoWindow({
						content: content,
						zIndex: 100
					});
					infowindow.open(map, marker,siteData);
				}
			});
			google.maps.event.clearListeners(marker, 'mouseout');
		}
	})(marker, siteid,siteData));
}

function deleteMarkers() {
	// for (i = 0; i < gmarkers.length; i++) {
	// 	//gmarkers[i].setMap(null);
	// 	//markerCluster.setMap(null);
	// 	//markerCluster.removeMarkers(markerCluster.getMarkers());
	// 	//markerCluster.clearMarkers();
	// 	//markerCluster = ;
	// }

	//initMap();

	//markerCluster.removeMarkers(markerCluster.getMarkers());
	// gmarkers = [];
	// siteMarker = [];
	// markerCluster.redraw();
}
function newLocation(newLat,newLng)
{
	//alert(newLat +" == "+newLng)
	if (typeof(newLat) !== 'undefined' && newLat && typeof(newLng) !== 'undefined' && newLng) {

		map.setCenter({
			lat : newLat,
			lng : newLng
		});
	}
	map.setZoom(14);
}

function clearMapTest(variable) {
	

	/*if (siteMarker !== undefined) {
		siteMarker = [];
	}
	if(fieldmap_sr_arr !== undefined  || variable == 'sr') {
		fieldmap_sr_arr = [];
		fieldmap_sr_arr.length = 0;
	}
	if (landing_rate !== undefined || variable == 'landing_rate') {
		landing_rate = [];
		landing_rate.length = 0;
	}
	if (larval !== undefined || variable == 'larval') {
		larval = [];
		larval.length = 0;
	}
	if (Fieldtask !== undefined ) {
		Fieldtask = [];
		Fieldtask.length = 0;
	}

	if (positive !== undefined || variable == 'positive') {
		positive = [];
		positive.length = 0;
	}



	
	
	siteMarker.length = 0;*/
	if(variable == "positive"){
		console.log('11');

	    if (positivesiteMarker.length > 0) {
	        for (i = 0; i < positivesiteMarker.length; i++) {
	            positivesiteMarker[i].setMap(null);
	        }
	    }
		
		if (positivesiteMarker !== undefined) {
			positivesiteMarker = [];
		}

		pov = 0;

	}

	if(variable == "srlayer"){
		//console.log('11');

	    if (srlayerMarker.length > 0) {
	        for (i = 0; i < srlayerMarker.length; i++) {
	            srlayerMarker[i].setMap(null);
	        }
	    }
		
		if (srlayerMarker !== undefined) {
			srlayerMarker = [];
		}

		srCount = 0;

	}
			
}

function getSiteSRFilterData(siteFilter,srFilter){
	console.log('filter_map_site_sr');
	var latlngbounds = new google.maps.LatLngBounds();
    if (sitesrFilterMarker.length > 0) {
        for (i = 0; i < sitesrFilterMarker.length; i++) {
            sitesrFilterMarker[i].setMap(null);
        }

        sitesrFilterMarker.length = 0;
    }

    sitesrFilterMarker = [];
    sitesrFilterMarker.length = 0;

	if(siteFilter != "" || srFilter != ""){

		$.ajax({
			type: "POST",
			url: 'vmap/api/',
			data: {
				action: "getSiteSRFilterData",
				siteId: siteFilter.join(","),
				srId: srFilter.join(","),
			},
			cache: true,
			beforeSend: function() {
				$(".loading").show();
			},
			success: function(data) {
				//console.log(data);
				if (data) {
					console.log('data found-filter');
					var response = JSON.parse(data);
					var siteData = response.site;
					var srData = response.sr;
					//console.log(response);
					var ccount = 0;
					if(siteData !== undefined && jQuery.isEmptyObject(siteData) == false){
						
						$.each(siteData, function(siteid, item) {
								if (siteData[siteid].polygon !== undefined) {
								
									sitesrFilterMarker[ccount] = new google.maps.Polygon({
                                        path: siteData[siteid].polygon,
                                        strokeColor: '#FF0000',
                                        strokeOpacity: 0.8,
                                        strokeWeight: 2,
                                        fillColor: '#FF0000',
                                        fillOpacity: 0.35,
                                        icon: siteData[siteid].icon,
                                    });

                                    $site_map = sitesrFilterMarker[ccount];
                                    info_popup($site_map, siteid);
                                    sitesrFilterMarker[ccount].setMap(map);
                                    //Extend each marker's position in LatLngBounds object.
                                    /*latlngbounds.extend(sitesrFilterMarker[ccount].position);*/
                                    sitesrFilterMarker[ccount].getPath().forEach(function (path, index) {                                
								        latlngbounds.extend(path);
								    });

                                    ccount++;

                                    if (siteData[siteid].polyCenter !== undefined) {
                                       /* var centerPoint = {
                                            lat: siteData[siteid].polyCenter['lat'] + (Math.random() / 10000),
                                            lng: siteData[siteid].polyCenter['lng'] + (Math.random() / 10000)
                                        };*/
                                        var centerPoint = {
                                            lat: siteData[siteid].polyCenter['lat'] + mathRandLat,
                                            lng: siteData[siteid].polyCenter['lng'] + mathRandLng
                                        };
                                        
                                        sitesrFilterMarker[ccount] = new google.maps.Marker({
                                            position: centerPoint,
                                            map: map,
                                            icon: siteData[siteid].icon,
                                        });
                                        //if (siteFilter.length != 0) {
                                            newLocation(centerPoint.lat, centerPoint.lng);
                                        //}
                                        $site_map = sitesrFilterMarker[ccount];
                                        info_popup($site_map, siteid);
                                        sitesrFilterMarker[ccount].setMap(map);

                                        //Extend each marker's position in LatLngBounds object.
                                        latlngbounds.extend(sitesrFilterMarker[ccount].position);

                                        ccount++;

                                    }
									ccount++;
								}
								if (siteData[siteid].poly_line !== undefined) {
									
									sitesrFilterMarker[ccount] = new google.maps.Polyline({
                                        path: siteData[siteid].poly_line,
                                        strokeColor: '#FF0000',
                                        strokeOpacity: 0.8,
                                        strokeWeight: 2,
                                        fillColor: '#FF0000',
                                        fillOpacity: 0.35,
                                        icon: siteData[siteid].icon,
                                    });
                                    //if (siteData[siteid].length != 0) {
                                        newLocation(siteData[siteid].poly_line.lat, siteData[siteid].poly_line.lng);
                                    //}
                                    $site_map = sitesrFilterMarker[ccount];
                                    info_popup($site_map, siteid);
                                    sitesrFilterMarker[ccount].setMap(map);

                                    //Extend each marker's position in LatLngBounds object.
                                    /*latlngbounds.extend(sitesrFilterMarker[ccount].position);*/
                                    sitesrFilterMarker[ccount].getPath().forEach(function (path, index) {                                
								        latlngbounds.extend(path);
								    });

                                    ccount++;
								}
								if (siteData[siteid].point !== undefined) {
									
									for (i = 0; i < siteData[siteid].point.length; i++) {
                                        /*var pointMatrix = {
                                            lat: siteData[siteid].point[i]['lat'] + (Math.random() / 10000),
                                            lng: siteData[siteid].point[i]['lng'] + (Math.random() / 10000)
                                        };*/
                                        var pointMatrix = {
                                            lat: siteData[siteid].point[i]['lat'] + mathRandLat,
                                            lng: siteData[siteid].point[i]['lng'] + mathRandLng
                                        };
                                        //showPointMap(pointMatrix, map, siteData[siteid].icon, siteid);

                                        sitesrFilterMarker[ccount] = new google.maps.Marker({
                                            map: map,
                                            position: pointMatrix,
                                            icon: siteData[siteid].icon
                                        });
                                        //if (siteData[siteid].length != 0) {
                                            newLocation(pointMatrix.lat, pointMatrix.lng);
                                       // }
                                        $site_map = sitesrFilterMarker[ccount];
                                        gmarkers.push($site_map);
                                        info_popup($site_map, siteid);
                                        sitesrFilterMarker[ccount].setMap(map);

                                        //Extend each marker's position in LatLngBounds object.
                                        latlngbounds.extend(sitesrFilterMarker[ccount].position);

                                        ccount++;

                                        var vName = siteData[siteid].vName;
                                        var vAddress = siteData[siteid].vAddress;
                                        var vRequestType = siteData[siteid].vRequestType;
                                        var vAssignTo = siteData[siteid].vAssignTo;
                                        var vStatus = siteData[siteid].vStatus;
                                    }
								}
							
						});
					}
					if(srData !== undefined && jQuery.isEmptyObject(srData) == false){
						$.each(srData, function(siteid, item) {
							if (srData[siteid].point !== undefined) {
                                    for (i = 0; i < srData[siteid].point.length; i++) {
                                        /*var pointMatrix = {
                                            lat: srData[siteid].point[i]['lat']+ (Math.random() / 10000),
                                            lng: srData[siteid].point[i]['lng']+ (Math.random() / 10000)
                                        };*/
                                        var pointMatrix = {
                                            lat: srData[siteid].point[i]['lat']+ mathRandLat,
                                            lng: srData[siteid].point[i]['lng']+ mathRandLng
                                        };
                                        var vName = srData[siteid].vName;
                                        var vAddress = srData[siteid].vAddress;
                                        var vRequestType = srData[siteid].vRequestType;
                                        var vAssignTo = srData[siteid].vAssignTo;
                                        var vStatus = srData[siteid].vStatus;
                                        
                                        sitesrFilterMarker[ccount] = new google.maps.Marker({
                                            map: map,
                                            position: pointMatrix,
                                            icon: srData[siteid].icon
                                        });
                                        //if (srData[siteid].length != 0) {
                                            newLocation(pointMatrix.lat,pointMatrix.lng);
                                       // }
                                        $sr_map = sitesrFilterMarker[ccount];

                                        srinfo_popup($sr_map, siteid, vName, vAddress, vRequestType, vAssignTo, vStatus);
                                        sitesrFilterMarker[ccount].setMap(map);

                                        //Extend each marker's position in LatLngBounds object.
                                        latlngbounds.extend(sitesrFilterMarker[ccount].position);

                                        ccount++;
                                    }
                                }
						});
					}
					
					if (ccount > 0){
						//Center map and adjust Zoom based on the position of all markers.
                        map.setCenter(latlngbounds.getCenter());
                        map.fitBounds(latlngbounds);
					}

				} else {
					console.log('no data found');
					//alert("No sites found");
					//clearMap();
				
				}
				 
				$(".loading").hide();
			}
		});
    }
}

function addInstaTreatData(mode,siteid){
		infowindow.close();

	 $.ajax({
            type: "POST",
            url: site_url + "vmap/index",
            data: {
                mode: "AddInstaTreat",
                siteId: siteid,
            },
            cache: true,
            dataType:'json',
            beforeSend: function() {
               $(".loading").show();
            },
            success: function(data) {

            	  $(".loading").hide();
            	 if(data['error'] == "0"){
                    toastr.success(data['msg']);
                }else{
                    toastr.error(data['msg']);
                }
            }
        });
}
/* function setMapOnAlltest(map) {
        for (let i = 0; i < srMarker.length; i++) {
          srMarker[i].setMap(map);
        }
      } // Removes the markers from the map, but keeps them in the array.

      function clearMarkerstest() {
        setMapOnAlltest(null);
      } // Shows any markers currently in the array.*/
/*show all site-icons within 0.5 mile radius of the user current location*/

$("#nearbysite").click(function(){
	var latlngbounds = new google.maps.LatLngBounds();
	 
	//sitesearchMarker.setMap(null);
    if (siteNearDataMarker.length > 0) {
        for (i = 0; i < siteNearDataMarker.length; i++) {
            siteNearDataMarker[i].setMap(null);
        }

        siteNearDataMarker.length = 0;
    }

    siteNearDataMarker = [];
    siteNearDataMarker.length = 0;
     sncount =0;
    if (this.checked) {
		getCurrentlatlong('');

		setTimeout(function(){
			//alert(currentlongitude+"=>"+currentlatitude);
			/*get nearby 0.5mile site*/
			$.ajax({
				type: "POST",
				url: 'vmap/api/',
				data: {
					action: "getNearBySite",
					'lat' : currentlatitude,
					'long' : currentlongitude,
					/*'lat' :  40.743486,
					'long' : -74.316397,*/
					'meter' :stmeter
				},
				cache: true,
				beforeSend: function() {
					$(".loading").show();
				},
				success: function(data) {
					//console.log(data);
					//sncount =0;
					var siteData = JSON.parse(data);
	                    if(Object.keys(siteData).length > 0){
	                        $.each(siteData, function(siteid, item) {
	                            if (siteData[siteid].polygon !== undefined) {
	                                //showPolygonMap(siteData[siteid].polygon, map, siteData[siteid].icon, siteid,iTMPId,iTTId);

	                                siteNearDataMarker[sncount] = new google.maps.Polygon({
	                                    path: siteData[siteid].polygon,
	                                    strokeColor: '#FF0000',
	                                    strokeOpacity: 0.8,
	                                    strokeWeight: 2,
	                                    fillColor: '#FF0000',
	                                    fillOpacity: 0.35,
	                                    icon: siteData[siteid].icon,
	                                });

	                                $site_map = siteNearDataMarker[sncount];
	                                info_popup($site_map, siteid);
	                                siteNearDataMarker[sncount].setMap(map);
									//Extend each marker's position in LatLngBounds object.
	                                /*latlngbounds.extend(siteNearDataMarker[sncount].position);*/
	                                siteNearDataMarker[sncount].getPath().forEach(function (path, index) {                                
								        latlngbounds.extend(path);
								    });

	                                sncount++;

	                                if (siteData[siteid].polyCenter !== undefined) {
	                                   /* var centerPoint = {
	                                        lat: siteData[siteid].polyCenter['lat'] + (Math.random() / 10000),
	                                        lng: siteData[siteid].polyCenter['lng'] + (Math.random() / 10000)
	                                    };*/
	                                    var centerPoint = {
	                                        lat: siteData[siteid].polyCenter['lat'] + mathRandLat,
	                                        lng: siteData[siteid].polyCenter['lng'] + mathRandLng
	                                    };
	                                    //showPolyCenter(centerPoint, map, siteData[siteid].icon, siteid);

	                                    siteNearDataMarker[sncount] = new google.maps.Marker({
	                                        position: centerPoint,
	                                        map: map,
	                                        icon: siteData[siteid].icon,
	                                    });
	                                    /*if (siteData[siteid].length != 0) {
	                                        newLocation(centerPoint.lat, centerPoint.lng);
	                                    }*/
	                                    newLocation(centerPoint.lat, centerPoint.lng);
	                                    $site_map = siteNearDataMarker[sncount];
	                                    info_popup($site_map, siteid);
	                                    siteNearDataMarker[sncount].setMap(map);

	                                    //Extend each marker's position in LatLngBounds object.
	                                    latlngbounds.extend(siteNearDataMarker[sncount].position);

	                                    sncount++;

	                                }
	                            }
	                            if (siteData[siteid].poly_line !== undefined) {
	                                //showPolyLineMap(siteData[siteid].poly_line, map, siteData[siteid].icon, siteid);

	                                siteNearDataMarker[sncount] = new google.maps.Polyline({
	                                    path: siteData[siteid].poly_line,
	                                    strokeColor: '#FF0000',
	                                    strokeOpacity: 0.8,
	                                    strokeWeight: 2,
	                                    fillColor: '#FF0000',
	                                    fillOpacity: 0.35,
	                                    icon: siteData[siteid].icon,
	                                });
	                                /*if (siteData[siteid].length != 0) {
	                                    newLocation(siteData[siteid].poly_line.lat, siteData[siteid].poly_line.lng);
	                                }*/
	                                newLocation(siteData[siteid].poly_line.lat, siteData[siteid].poly_line.lng);
	                                $site_map = siteNearDataMarker[sncount];
	                                info_popup($site_map, siteid);
	                                siteNearDataMarker[sncount].setMap(map);

	                                //Extend each marker's position in LatLngBounds object.
	                                /*latlngbounds.extend(siteNearDataMarker[sncount].position);*/
	                                siteNearDataMarker[sncount].getPath().forEach(function (path, index) {                                
								        latlngbounds.extend(path);
								    });

	                                sncount++;
	                            }
	                            if (siteData[siteid].point !== undefined) {
	                                //console.log(siteid);
	                                for (i = 0; i < siteData[siteid].point.length; i++) {
	                                    /*var pointMatrix = {
	                                        lat: siteData[siteid].point[i]['lat'] + (Math.random() / 10000),
	                                        lng: siteData[siteid].point[i]['lng'] + (Math.random() / 10000)
	                                    };*/
	                                    var pointMatrix = {
	                                        lat: siteData[siteid].point[i]['lat'] + mathRandLat,
	                                        lng: siteData[siteid].point[i]['lng'] + mathRandLng
	                                    };
	                                    
	                                    siteNearDataMarker[sncount] = new google.maps.Marker({
	                                        map: map,
	                                        position: pointMatrix,
	                                        icon: siteData[siteid].icon,
	                                        draggable:true,
	                                    });
	                                    
	                                    newLocation(pointMatrix.lat, pointMatrix.lng);
	                                    $site_map = siteNearDataMarker[sncount];
	                                    gmarkers.push($site_map);
	                                    info_popup($site_map, siteid);
	                                    siteNearDataMarker[sncount].setMap(map);

	                                    //Extend each marker's position in LatLngBounds object.
	                                    latlngbounds.extend(siteNearDataMarker[sncount].position);

	                                    sncount++;

	                                    var vName = siteData[siteid].vName;
	                                    var vAddress = siteData[siteid].vAddress;
	                                    var vRequestType = siteData[siteid].vRequestType;
	                                    var vAssignTo = siteData[siteid].vAssignTo;
	                                    var vStatus = siteData[siteid].vStatus;
	                                }
	                            }
	                        });
	                         
	                        //Center map and adjust Zoom based on the position of all markers.
	                        map.setCenter(latlngbounds.getCenter());
	                        map.fitBounds(latlngbounds);

	                    }


					$(".loading").hide();
					//alert(sncount);
				}
			});

		},500);
	}else{
		//alert('unchecked');
	}

});


function infoPolygonArea($site_map, siteid)
{
	//show polygon area
  google.maps.event.addListener($site_map, 'mouseover', ( function($site_map, siteid) {
      return function(arg) {
          var polyArea = google.maps.geometry.spherical.computeArea($site_map.getPath()); //in sq. meter

          var polyacres = (polyArea.toFixed(2)) * parseFloat(0.000247);
          var content = "Area: "+polyacres.toFixed(2)+" acre";

          __marker__ = $site_map;
          if (infowindow) {
              //close previous opened infowindow
              infowindow.close();
          }

          infowindow = new google.maps.InfoWindow({
              content: content,
              zIndex: 100,
              position: arg.latLng,
          });
                      
          infowindow.open(map, $site_map);
          google.maps.event.clearListeners($site_map, 'mouseout',function function_name(infowindow) {
          	if (infowindow) {
              //close previous opened infowindow
              infowindow.close();
          }
          });
      }
  })($site_map, siteid));
}