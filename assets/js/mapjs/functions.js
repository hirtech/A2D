var markerCluster;
function getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custlayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer) {
	clearMap();
	if(skNetwork != "" || skCity != "" || skZipcode != "" || skZones != "" || networkLayer != ""|| zoneLayer != "" || custlayer != "" || fiberInquiryLayer != "" || serviceOrderLayer != "" || workOrderLayer != "" || pCircuitStatusLayer != "" || pCircuitcTypeLayer != "" || premiseStatusLayer != "" || premiseAttribute != "" || premiseTypeLayer != "" || premisesubTypeLayer != ""){

		$.ajax({
			type: "POST",
			url: 'vmap/api/',
			data: {
				action: "getJson",
				city: skCity.join(","),
				network: skNetwork.join(","),
				zipcode: skZipcode.join(","),
				zone: skZones.join(","),
				networkLayer: networkLayer.join(","),
				zoneLayer: zoneLayer.join(","),
				custlayer: custlayer.join(","),
				fiberInquiryLayer: fiberInquiryLayer.join(","),
				serviceOrderLayer: serviceOrderLayer.join(","),
				workOrderLayer: workOrderLayer.join(","),
				pCircuitStatusLayer: pCircuitStatusLayer.join(","),
				pCircuitcTypeLayer: pCircuitcTypeLayer.join(","),
				premiseStatusLayer: premiseStatusLayer.join(","),
				premiseAttribute: premiseAttribute.join(","),
				premiseTypeLayer: premiseTypeLayer.join(","),
				premisesubTypeLayer: premisesubTypeLayer.join(","),
			},
			cache: true,
			beforeSend: function() {
				$(".loading").show();
				/*if (localCache.exist(url)) {
					doSomething(localCache.get(url));
					return false;
				}
				return true;*/
			},
			success: function(data) {
				//console.log(data);
				if (data) {
					//console.log('data found');
					var response = JSON.parse(data);
					var siteData = response.sites;
					var ressrdata = "";
					//var fiberInquiryCount = 0;
					if (response.polyZone !== undefined) {
						$.each(response.polyZone, function(zoneid, item) {
							showZonePolygonMap(item, map);
						});
					}
					if (response.sites !== undefined) {
						$.each(siteData, function(skey, item) {
							var premiseid = siteData[skey].premiseid;
							if (siteData[skey].point !== undefined) {
								for (i = 0; i < siteData[skey].point.length; i++) {
									var pointMatrix = {
										lat: siteData[skey].point[i]['lat']+ mathRandLat,
										lng: siteData[skey].point[i]['lng']+ mathRandLng
									};
									showPointMap(pointMatrix, map, siteData[skey].icon, premiseid);
									var vName = siteData[skey].vName;
									var vAddress = siteData[skey].vAddress;
									var vRequestType = siteData[skey].vRequestType;
									var vAssignTo = siteData[skey].vAssignTo;
									var vStatus = siteData[skey].vStatus;
								}
							}
						});
          			}
         	
         			// ******** Network layer ******** //
					if (response.networkLayer !== undefined) {
						$.each(response.networkLayer, function(id, item) {
							var src = item['file_url'];
							//var src =  "http://54.167.253.109/eCommunityfiber/storage/netowrk_kml/1665577412_eCommunity_Warner_Robins.kml";
	                        var kml = new google.maps.KmlLayer({
	                            url: src,
	                            suppressInfoWindows: true,  
	                            map:map,
	                            zindex: 0
	                        }); 

							kml.vName = item['vName'];
                        	networkLayerArr.push(kml);
						});
		                var kmls = networkLayerArr.length;
		                if (kmls > 0) {
		                	//info window
		                    for (i = 0; i < kmls; i++) {
								var obj = {
									'vname':networkLayerArr[i].vName,		
								};
								networkLayerArr[i].objInfo = obj;
								if(networkLayerArr[i]) {
									google.maps.event.addListener(networkLayerArr[i], 'click', function(evt) {
										if(infowindow_networkLayer) {
											infowindow_networkLayer.close();
										}
										infowindow_networkLayer = new google.maps.InfoWindow({
												content: this.objInfo.vname,
												zIndex: 100,
												pixelOffset:evt.pixelOffset, 
  												position:evt.latLng
										});
										infowindow_networkLayer.open(map,networkLayerArr[i]);
									})
								}
		                    }
		                }
					}

					// ******** Zone layer ******** //
					if (response.zoneLayer !== undefined) {
						$.each(response.zoneLayer, function(id, item) {
							var src = item['file_url'];
							//var src =  "http://54.167.253.109/eCommunityfiber/storage/zone/1664396393_Polygon_One.kml";
	                        var kml = new google.maps.KmlLayer({
	                            url: src,
	                            suppressInfoWindows: true,  
	                            map:map,
	                            zindex: 0
	                        }); 

							kml.vZoneName = item['vZoneName'];
                        	zoneLayerArr.push(kml);
						});
		                var kmls = zoneLayerArr.length;
		                if (kmls > 0) {
		                	//info window
		                    for (i = 0; i < kmls; i++) {
								var obj = {
									'vzonename':zoneLayerArr[i].vZoneName,		
								};
								zoneLayerArr[i].objInfo = obj;
								if(zoneLayerArr[i]) {
									google.maps.event.addListener(zoneLayerArr[i], 'click', function(evt) {
										if(infowindow_zoneLayer) {
											infowindow_zoneLayer.close();
										}
										infowindow_zoneLayer = new google.maps.InfoWindow({
												content: this.objInfo.vzonename,
												zIndex: 100,
												pixelOffset:evt.pixelOffset, 
  												position:evt.latLng
										});
										infowindow_zoneLayer.open(map,zoneLayerArr[i]);
									})
								}
		                    }
		                }
					}

					// ******** custom layer ******** //
					if (response.customlayer !== undefined) {
						$.each(response.customlayer, function(id, item) {
							var src = item['file_url'];
	                        var kml = new google.maps.KmlLayer({
	                            url: src,
	                            suppressInfoWindows: true,  
	                            map:map,
	                            zindex: 0
	                        }); 
							kml.vName = item['vName'];
                        	customeLayerArr.push(kml);
						});
		                var kmls = customeLayerArr.length;
		                if (kmls > 0) {
		                	//info window
		                    for (i = 0; i < kmls; i++) {
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

					// ******** Fiber Inquiry layer ******** //
					if (response.fiberInquiry !== undefined ) {
			            resfiberInquirydata = response.fiberInquiry;
			            $.each(resfiberInquirydata, function(id, item) {
			              	if (resfiberInquirydata[id].point !== undefined) {
			                	for (i = 0; i < resfiberInquirydata[id].point.length; i++) {
									var pointMatrix = {
										lat: resfiberInquirydata[id].point[i]['lat']+ mathRandLat,
										lng: resfiberInquirydata[id].point[i]['lng']+ mathRandLng
									};
									var vName = resfiberInquirydata[id].vName;
									var vAddress = resfiberInquirydata[id].vAddress;
                                    var premiseid = resfiberInquirydata[id].premiseid;
                                    var vPremiseName = resfiberInquirydata[id].vPremiseName;
                                    var vPremiseSubType	= resfiberInquirydata[id].vPremiseSubType;
                                    var vEngagement = resfiberInquirydata[id].vEngagement;
                                    var vZoneName = resfiberInquirydata[id].vZoneName;
                                    var vNetwork = resfiberInquirydata[id].vNetwork;
                                    var vStatus = resfiberInquirydata[id].vStatus;
                                    var iFiberInquiryId = resfiberInquirydata[id].iFiberInquiryId;

									showPointMapForFiberInquiry(pointMatrix, map, resfiberInquirydata[id].icon, id, vName, vAddress, premiseid, vPremiseName, vPremiseSubType, vEngagement, vZoneName, vNetwork, vStatus, iFiberInquiryId);
									//fiberInquiryCount++;
								}
			            	}
			            });
          			}

          			// ******** Service Order layer ******** //
          			if (response.serviceOrder !== undefined ) {
					    resserviceOrderdata = response.serviceOrder;
					    $.each(resserviceOrderdata, function(id, item) {
					      	if (resserviceOrderdata[id].point !== undefined) {
					        	for (i = 0; i < resserviceOrderdata[id].point.length; i++) {
									var pointMatrix = {
										lat: resserviceOrderdata[id].point[i]['lat']+ mathRandLat,
										lng: resserviceOrderdata[id].point[i]['lng']+ mathRandLng
									};

					                var vMasterMSA = resserviceOrderdata[id]['vMasterMSA'];
					                var vServiceOrder = resserviceOrderdata[id]['vServiceOrder'];
					                var vSalesRepName = resserviceOrderdata[id]['vSalesRepName'];
					                var vSalesRepEmail = resserviceOrderdata[id]['vSalesRepEmail'];
					                var premiseid = resserviceOrderdata[id]['premiseid'];
					                var vPremiseName = resserviceOrderdata[id]['vPremiseName'];
					                var vAddress = resserviceOrderdata[id]['vAddress'];
					                var cityid = resserviceOrderdata[id]['cityid'];
					                var stateid = resserviceOrderdata[id]['stateid'];
					                var countyid = resserviceOrderdata[id]['countyid'];
					                var zipcode = resserviceOrderdata[id]['zipcode'];
					                var zoneid = resserviceOrderdata[id]['zoneid'];
					                var vZoneName = resserviceOrderdata[id]['vZoneName'];
					                var networkid = resserviceOrderdata[id]['networkid'];
					                var vNetwork = resserviceOrderdata[id]['vNetwork'];
					                var vPremiseType = resserviceOrderdata[id]['vPremiseType'];
					                var vCompanyName = resserviceOrderdata[id]['vCompanyName'];
					                var vConnectionTypeName = resserviceOrderdata[id]['vConnectionTypeName'];
					                var vServiceType1 = resserviceOrderdata[id]['vServiceType1'];
					                var vStatus = resserviceOrderdata[id]['vStatus'];

									showPointMapForserviceOrder(pointMatrix, map, resserviceOrderdata[id].icon, id, vMasterMSA, vServiceOrder, vSalesRepName, vSalesRepEmail, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vCompanyName, vConnectionTypeName, vServiceType1, vStatus);
								}
					    	}
					    });
					}

					// ******** Work Order layer ******** //
          			if (response.workOrder !== undefined ) {
					    resworkOrderdata = response.workOrder;
					    $.each(resworkOrderdata, function(id, item) {
					      	if (resworkOrderdata[id].point !== undefined) {
					        	for (i = 0; i < resworkOrderdata[id].point.length; i++) {
									var pointMatrix = {
										lat: resworkOrderdata[id].point[i]['lat']+ mathRandLat,
										lng: resworkOrderdata[id].point[i]['lng']+ mathRandLng
									};

					                var premiseid = resworkOrderdata[id]['premiseid'];
					                var vPremiseName = resworkOrderdata[id]['vPremiseName'];
					                var vAddress = resworkOrderdata[id]['vAddress'];
					                var cityid = resworkOrderdata[id]['cityid'];
					                var stateid = resworkOrderdata[id]['stateid'];
					                var countyid = resworkOrderdata[id]['countyid'];
					                var zipcode = resworkOrderdata[id]['zipcode'];
					                var zoneid = resworkOrderdata[id]['zoneid'];
					                var vZoneName = resworkOrderdata[id]['vZoneName'];
					                var networkid = resworkOrderdata[id]['networkid'];
					                var vNetwork = resworkOrderdata[id]['vNetwork'];
					                var vPremiseType = resworkOrderdata[id]['vPremiseType'];
					                var vServiceOrder = resworkOrderdata[id]['vServiceOrder'];
					                var vWOProject = resworkOrderdata[id]['vWOProject'];
					                var vType = resworkOrderdata[id]['vType'];
					                var vRequestor = resworkOrderdata[id]['vRequestor'];
					                var vAssignedTo = resworkOrderdata[id]['vAssignedTo'];
					                var vStatus = resworkOrderdata[id]['vStatus'];

									showPointMapForworkOrder(pointMatrix, map, resworkOrderdata[id].icon, id,premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vServiceOrder, vWOProject, vType, vRequestor, vAssignedTo, vStatus);
								}
					    	}
					    });
					}

					// ******** Premise Circuit layer ******** //
          			if (response.premiseCircuit !== undefined ) {
					    respremiseCircuitdata = response.premiseCircuit;
					    $.each(respremiseCircuitdata, function(id, item) {
					      	if (respremiseCircuitdata[id].point !== undefined) {
					        	for (i = 0; i < respremiseCircuitdata[id].point.length; i++) {
									var pointMatrix = {
										lat: respremiseCircuitdata[id].point[i]['lat']+ mathRandLat,
										lng: respremiseCircuitdata[id].point[i]['lng']+ mathRandLng
									};

					                var premiseid = respremiseCircuitdata[id]['premiseid'];
					                var vPremiseName = respremiseCircuitdata[id]['vPremiseName'];
					                var vAddress = respremiseCircuitdata[id]['vAddress'];
					                var cityid = respremiseCircuitdata[id]['cityid'];
					                var stateid = respremiseCircuitdata[id]['stateid'];
					                var countyid = respremiseCircuitdata[id]['countyid'];
					                var zipcode = respremiseCircuitdata[id]['zipcode'];
					                var zoneid = respremiseCircuitdata[id]['zoneid'];
					                var vZoneName = respremiseCircuitdata[id]['vZoneName'];
					                var networkid = respremiseCircuitdata[id]['networkid'];
					                var vNetwork = respremiseCircuitdata[id]['vNetwork'];
					                var vPremiseType = respremiseCircuitdata[id]['vPremiseType'];
					                var vWorkOrder = respremiseCircuitdata[id]['vWorkOrder'];
					                var circuitid = respremiseCircuitdata[id]['circuitid'];
					                var vCircuitName = respremiseCircuitdata[id]['vCircuitName'];
					                var connectiontypeid = respremiseCircuitdata[id]['connectiontypeid'];
					                var vConnectionTypeName = respremiseCircuitdata[id]['vConnectionTypeName'];
					                var vStatus = respremiseCircuitdata[id]['vStatus'];

									showPointMapForpremiseCircuit(pointMatrix, map, respremiseCircuitdata[id].icon, id,premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vWorkOrder, circuitid, vCircuitName, connectiontypeid, vConnectionTypeName, vStatus);
								}
					    	}
					    });
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
	                        map.setZoom(defaultZoom);
                            //console.log("defaultZoom bounds = "+defaultZoom)
						}
					}
				} else {
					//console.log('no data found');
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
		zoom: defaultZoom,
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
    //console.log("map init")

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
  	});
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
	if (navigator.geolocation) {
	    navigator.geolocation.getCurrentPosition((position) => {
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
	    },() => {
          	handleLocationError(true, infoWindow, map.getCenter());
        });
    } else {
    	alert(2222);
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }
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

function showPointMapForFiberInquiry(sitePath, map, icon, id, vName, vAddress, premiseid, vPremiseName, vPremiseSubType, vEngagement, vZoneName, vNetwork, vStatus,iFiberInquiryId) {
	/*map.setCenter({lat: 41.595526, lng: -72.687145}); */
	//console.log('pCount=>'+pCount);
	fiberInquirylayerMarker[fiberInquiryCount] = new google.maps.Marker({
		map: map,
		position: sitePath,
		icon: icon
	});
	newLocation(sitePath.lat,sitePath.lng);
	$sr_map = fiberInquirylayerMarker[fiberInquiryCount];
	gmarkers.push($sr_map);
	//alert("id" +id)
	fiberInquiryinfo_popup($sr_map, id, vName, vAddress, premiseid, vPremiseName, vPremiseSubType, vEngagement, vZoneName, vNetwork, vStatus,iFiberInquiryId);
	fiberInquirylayerMarker[fiberInquiryCount].setMap(map);

	//Extend each marker's position in LatLngBounds object.
    latlngbounds.extend(fiberInquirylayerMarker[fiberInquiryCount].position);
	fiberInquiryCount++;
}

function showPointMapForserviceOrder(sitePath, map, icon, id, vMasterMSA, vServiceOrder, vSalesRepName, vSalesRepEmail, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vCompanyName, vConnectionTypeName, vServiceType1, vStatus) {
	
	serviceOrderlayerMarker[serviceOrderCount] = new google.maps.Marker({
		map: map,
		position: sitePath,
		icon: icon
	});
	newLocation(sitePath.lat,sitePath.lng);
	$so_map = serviceOrderlayerMarker[serviceOrderCount];
	gmarkers.push($so_map);
	serviceOrderinfo_popup($so_map, id, vMasterMSA, vServiceOrder, vSalesRepName, vSalesRepEmail, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vCompanyName, vConnectionTypeName, vServiceType1, vStatus);
	serviceOrderlayerMarker[serviceOrderCount].setMap(map);

	//Extend each marker's position in LatLngBounds object.
    latlngbounds.extend(serviceOrderlayerMarker[serviceOrderCount].position);
	serviceOrderCount++;
}

function showPointMapForworkOrder(sitePath, map, icon, id, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vServiceOrder, vWOProject, vType, vRequestor, vAssignedTo, vStatus) {
	
	workOrderlayerMarker[workOrderCount] = new google.maps.Marker({
		map: map,
		position: sitePath,
		icon: icon
	});
	newLocation(sitePath.lat,sitePath.lng);
	$wo_map = workOrderlayerMarker[workOrderCount];
	gmarkers.push($wo_map);
	workOrderinfo_popup($wo_map, id, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vServiceOrder, vWOProject, vType, vRequestor, vAssignedTo, vStatus);
	workOrderlayerMarker[workOrderCount].setMap(map);

	//Extend each marker's position in LatLngBounds object.
    latlngbounds.extend(workOrderlayerMarker[workOrderCount].position);
	workOrderCount++;
}

function showPointMapForpremiseCircuit(sitePath, map, icon, id, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vWorkOrder, circuitid, vCircuitName, connectiontypeid, vConnectionTypeName, vStatus) {
	
	premiseCircuitlayerMarker[premiseCircuitCount] = new google.maps.Marker({
		map: map,
		position: sitePath,
		icon: icon
	});
	newLocation(sitePath.lat,sitePath.lng);
	$pc_map = premiseCircuitlayerMarker[premiseCircuitCount];
	gmarkers.push($pc_map);
	premiseCircuitinfo_popup($pc_map, id, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vWorkOrder, circuitid, vCircuitName, connectiontypeid, vConnectionTypeName, vStatus);
	premiseCircuitlayerMarker[premiseCircuitCount].setMap(map);

	//Extend each marker's position in LatLngBounds object.
    latlngbounds.extend(premiseCircuitlayerMarker[premiseCircuitCount].position);
	premiseCircuitCount++;
}

function showPointMap(sitePath, map, icon, premiseid) {
	//alert(sitePath);
	/*map.setCenter({lat: 41.595526, lng: -72.687145}); */
	siteMarker[pCount] = new google.maps.Marker({
		map: map,
		position: sitePath,
		icon: icon
	});
	newLocation(sitePath.lat,sitePath.lng);
	$site_map = siteMarker[pCount];
	gmarkers.push($site_map);
	info_popup($site_map, premiseid);
	siteMarker[pCount].setMap(map);

	//Extend each marker's position in LatLngBounds object.
   	latlngbounds.extend(siteMarker[pCount].position);

	pCount++;
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
	//console.log('mile: ' + sqMile);
	var sqFeet = sqMile.toFixed(2) * parseInt('27878400');
	//console.log('Feet: ' + sqFeet);
	$("#areainmiles").val(sqMile.toFixed(2));
	$("#areainft").val(sqFeet.toFixed(2));
	
	polygonCount++;
}

// Handles click events on a map, and adds Circle Shape.
function addLatLngCircle(event) {
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
			//console.log(data);
		}
	});
}

function getnetworkLayerJson() {
	$.ajax({
		type: "POST",
		url: site_url + "vmap/api",
		data: {
			action: "getnetworkLayerData",
		},
		success: function(data) {
			//console.log(data);
		}
	});
}

function getZoneLayerJson() {
	$.ajax({
		type: "POST",
		url: site_url + "vmap/api",
		data: {
			action: "getZoneLayerData",
		},
		success: function(data) {
			//console.log(data);
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
			//console.log(data);
		}
	});
}

function getFiberInquiryJson() {
	$.ajax({
		type: "POST",
		url: site_url + "vmap/api",
		data: {
			action: "getFiberInquiryData",
		},
		success: function(data) {
			//console.log(data);
		}
	});
}

function getServiceOrderJson() {
	$.ajax({
		type: "POST",
		url: site_url + "vmap/api",
		data: {
			action: "getServiceOrderData",
		},
		success: function(data) {
			//console.log(data);
		}
	});
}

function getWorkOrderJson() {
	$.ajax({
		type: "POST",
		url: site_url + "vmap/api",
		data: {
			action: "getWorkOrderData",
		},
		success: function(data) {
			//console.log(data);
		}
	});
}

function getPremiseCircuitJson() {
	$.ajax({
		type: "POST",
		url: site_url + "vmap/api",
		data: {
			action: "getPremiseCircuitData",
		},
		success: function(data) {
			//console.log(data);
		}
	});
}

function clearMap() {
	//console.log('11');
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

    if (fiberInquirylayerMarker.length > 0) {
        for (i = 0; i < fiberInquirylayerMarker.length; i++) {
            fiberInquirylayerMarker[i].setMap(null);
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

	var clayers = customeLayerArr.length;
    if (clayers > 0) {
        for (i = 0; i < clayers; i++) {
            customeLayerArr[i].setMap(null);
        }
    }
    customeLayerArr.length = 0;

    var nlayers = networkLayerArr.length;
    if (nlayers > 0) {
        for (i = 0; i < nlayers; i++) {
            networkLayerArr[i].setMap(null);
        }
    }
    networkLayerArr.length = 0;

    var zlayers = zoneLayerArr.length;
    if (zlayers > 0) {
        for (i = 0; i < zlayers; i++) {
            zoneLayerArr[i].setMap(null);
        }
    }
    zoneLayerArr.length = 0;

    if (fiberInquirylayerMarker !== undefined) {
		fiberInquirylayerMarker = [];
	}

    if (serviceOrderlayerMarker !== undefined) {
		serviceOrderlayerMarker = [];
	}
	if (workOrderlayerMarker !== undefined) {
		workOrderlayerMarker = [];
	}

	if (premiseCircuitlayerMarker !== undefined) {
		premiseCircuitlayerMarker = [];
	}

	pCount = 0;
	pl = 0;
	pline = 0;
	zCount = 0;
	pCenter = 0;
	pov = 0;
	fiberInquiryCount = 0;
	serviceOrderCount = 0;
	workOrderCount = 0;
	premiseCircuitCount = 0;

	if (markerCluster && markerCluster.setMap) {
        markerCluster.clearMarkers();
    }

    //console.log('resetmap');
    initMap();
}

function info_popup(marker, premiseid) {
	//console.log(premiseid);
	google.maps.event.addListener(marker, 'click', ( function(marker, premiseid) {
		return function(arg) {
			var content = "";
			__marker__ = marker;
			$.ajax({
				type: "POST",
				dataType: "json",
				url: site_url + "vmap/index",
				data: 'mode=site_map&iPremiseId=' + premiseid,
				success: function(data) {

					if (data.site.length > 0) {
							//console.log(data.site);
						var vName = '';
						if (typeof data.site[0]['vName'] != "undefined" && data.site[0]['vName'] != null && data.site[0]['vName'] != '') {
							vName += data.site[0]['vName'];
						}
						if (typeof data.site[0]['vSubTypeName'] !== "undefined" && data.site[0]['vSubTypeName'] != null && data.site[0]['vSubTypeName'] != '') {
							type_str += " - " + data.site[0]['vSubTypeName'];
						}

						var type_str = '';
						if (typeof data.site[0]['vTypeName'] !== "undefined" && data.site[0]['vTypeName'] != null && data.site[0]['vTypeName'] != '') {
							type_str += data.site[0]['vTypeName'];
						}
						if (typeof data.site[0]['vSubTypeName'] !== "undefined" && data.site[0]['vSubTypeName'] != null && data.site[0]['vSubTypeName'] != '') {
							type_str += " - " + data.site[0]['vSubTypeName'];
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
							address_str += ", " + data.site[0]['vCity'];
						}
						if (typeof data.site[0]['vState'] !== "undefined" && data.site[0]['vState'] != null && data.site[0]['vState'] != '') {
							address_str += ", " + data.site[0]['vState'];
						}
						content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
						content += "<h5 class='border-bottom pb-2 mb-3'>Premise #" + data.site[0]['iPremiseId'] + " - " + vName + "</h5>";
						content += "<h6>" + type_str + "</h6>";
						content += "<strong>" + address_str + "</strong>";
						content += "<div class='button mt-3'>";
						
						var ServiceOrderCount = 0;
						if (data.site[0]['ServiceOrderCount'] > 0) {
							ServiceOrderCount = data.site[0]['ServiceOrderCount'];
						}
						content += "<a class='btn btn-primary  mr-2 text-white' title='Awareness' onclick=addEditDataAwareness(0,'add','" + premiseid + "')>Awareness</a>";
						content += "<a class='btn btn-primary  mr-2 text-white' title='Inquiry'>Inquiry</a>";
						content += "<a class='btn btn-primary  mr-2 text-white' title='Service Order' onclick=mapRedirectServiceOrder('" + ServiceOrderCount + "','" + premiseid + "')>Service Order</a>";

						var WorkOrderCount = 0;
						if (data.site[0]['WorkOrderCount'] > 0) {
							WorkOrderCount = data.site[0]['WorkOrderCount'];
						}
						content += "<a class='btn btn-primary  mr-2 text-white' title='Work Order' onclick=mapRedirectWorkOrder('" + WorkOrderCount + "','" + premiseid + "')>Work Order</a>";
						content += "<a class='btn btn-primary  mr-2 text-white' title='Circuit'>Circuit</a>";
						content += "<a class='btn btn-primary  mr-2 text-white' title='Trouble'>Trouble</a>";
						content += "<a class='btn btn-primary  mr-2 text-white' title='Other'>Other</a>";
						content += "<a class='btn btn-primary  mr-2 text-white'  title='Other' href='" + site_url + "premise/edit&mode=Update&iPremiseId=" + premiseid + "' target='_blank'>Edit Premise</a>";
						
						content += "</div>";
						
						if (typeof data.site_history !== "undefined" && data.site_history != null && data.site_history != '') {
							
							if (data.site_history.length > 0) {
								content += "<h5 class='border-bottom pb-2 mb-3 mt-3'>History</h5>";
								siteInfoWindowTaskAwarenessArr = [];
								$.each(data.site_history, function(index, item) {
									if(item['Type'] == "Awareness") {
										siteInfoWindowTaskAwarenessArr.push(item['hidden_arr']);
										content += item['hidden_fields']+"<span class='w-100 d-block  pb-2'><a href='javascript:void(0);' onclick='addEditDataAwareness("+item['id']+",\"edit\",0)'>" + item['Date'] + " " + item['Description'] + "</a></span>";
									}else if(item['Type'] == "FiberInquiry") {
										var fiber_inquiry_link = site_url+"fiber_inquiry/edit&mode=Update&iFiberInquiryId="+item['id'];
										content += "<span class='w-100 d-block  pb-2'><a href='"+fiber_inquiry_link+"' target='_blank'>" + item['Date'] + " " + item['Description'] + "</a></span>";
									}else if(item['Type'] == "ServiceOrder") {
										var so_link = site_url+"service_order/edit&mode=Update&iServiceOrderId="+item['id'];
										content += "<span class='w-100 d-block  pb-2'><a href='"+so_link+"' target='_blank'>" + item['Date'] + " " + item['Description'] + "</a></span>";
									}else if(item['Type'] == "WorkOrder") {
										var wo_link = site_url+"service_order/workorder_add&mode=Update&iWOId="+item['id'];
										content += "<span class='w-100 d-block  pb-2'><a href='"+wo_link+"' target='_blank'>" + item['Date'] + " " + item['Description'] + "</a></span>";
									}else if(item['Type'] == "TroubleTicket") {
										var tt_link = site_url+"trouble_ticket/trouble_ticket_edit&mode=Update&iTroubleTicketId="+item['id'];
										content += "<span class='w-100 d-block  pb-2'><a href='"+tt_link+"' target='_blank'>" + item['Date'] + " " + item['Description'] + "</a></span>";
									}else if(item['Type'] == "MaintainanceTicket") {
										var mt_link = site_url+"/maintenance_ticket/maintenance_ticket_edit&mode=Update&iMaintenanceTicketId="+item['id'];
										content += "<span class='w-100 d-block  pb-2'><a href='"+mt_link+"' target='_blank'>" + item['Date'] + " " + item['Description'] + "</a></span>";
									}
									
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
	})(marker, premiseid));
}

function fiberInquiryinfo_popup(marker, id, vName, vAddress, premiseid,vPremiseName, vPremiseSubType, vEngagement, vZoneName, vNetwork, vStatus,iFiberInquiryId) {
	var full_name = vName;
	//alert("fiberInquiryId + "+fiberInquiryId)
	google.maps.event.addListener(marker, 'click', (function(marker, id, full_name, vAddress, premiseid, vPremiseName, vPremiseSubType, vEngagement, vZoneName, vNetwork, vStatus,iFiberInquiryId) {
		return function() {
			var content = "";
			__marker__ = marker;
			var vName = full_name;
			//alert(id)
			if (vName == null || vName == 'undefined' || vName == '') {
				vName = '';
			} else {
				vName = "(" + full_name + ")";
			}

			if (vAddress == null || vAddress == 'undefined' || vAddress == '') {
				vAddress = '';
			} 

			if (premiseid == null || premiseid == 'undefined' || premiseid == '') {
				premiseid = '';
			}
			if (vPremiseName == null || vPremiseName == 'undefined' || vPremiseName == '') {
				vPremiseName = '';
			}

			if (vPremiseSubType == null || vPremiseSubType == 'undefined' || vPremiseSubType == '') {
				vPremiseSubType = '';
			} 

			if (vNetwork == null || vNetwork == 'undefined' || vNetwork == '') {
				vNetwork = '';
			}

			if (vZoneName == null || vZoneName == 'undefined' || vZoneName == '') {
				vZoneName = '';
			} 
			
			if (vEngagement == null || vEngagement == 'undefined' || vEngagement == '') {
				vEngagement = '';
			} 
			if (vStatus == null || vStatus == 'undefined' || vStatus == '') {
				vStatus = '';
			}
			if (iFiberInquiryId == null || iFiberInquiryId == 'undefined' || iFiberInquiryId == '') {
				iFiberInquiryId = '';
			} 

			var premise_data = 'Premise #'+premiseid+" ("+vPremiseName+")";
			
			content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
			content += "<h5 class='border-bottom pb-2 mb-3'>Fiber Inquiry #" + id + " " + vName + "</h5>";
			content += "<div class='d-flex'><h6>" + premise_data + "</h6></div>";
			content += "<div class='d-flex'><span class='font-weight-bold'>Sub Type :</span>&nbsp;" + vPremiseSubType + "</div>";
			content += "<div class='d-flex'><span class='font-weight-bold'>Address :</span>&nbsp;" + vAddress + "</div>";
			content += "<div class='d-flex'><span class='font-weight-bold'>Network :</span>&nbsp;" + vNetwork + "</span></div>";
			content += "<div class='d-flex'><span class='font-weight-bold'>Zone Name :</span>&nbsp;" + vZoneName + "</span></div>";
			content += "<div class='d-flex'><span class='font-weight-bold'>Engagement :</span>&nbsp;" + vEngagement + "</span></div>";
			content += "<div class='d-flex'><span class='font-weight-bold'>Status :</span>&nbsp;" + vStatus + "</div>";
			content += "<div class='button mt-3'><a class='btn btn-primary  mr-2 text-white' href='" + site_url + "fiber_inquiry/edit&mode=Update&iFiberInquiryId=" + id + "' target='_blank'>Edit Fiber Inquiry</a></div>";
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
	})(marker, id, full_name, vAddress, premiseid, vPremiseName, vPremiseSubType, vEngagement, vZoneName, vNetwork, vStatus, iFiberInquiryId));
}

function serviceOrderinfo_popup(marker, id, vMasterMSA, vServiceOrder, vSalesRepName, vSalesRepEmail, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vCompanyName, vConnectionTypeName, vServiceType1, vStatus) {
    google.maps.event.addListener(marker, 'click', (function(marker, id, vMasterMSA, vServiceOrder, vSalesRepName, vSalesRepEmail, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vCompanyName, vConnectionTypeName, vServiceType1, vStatus) {
        return function() {
            var content = "";
            __marker__ = marker;

            if (vMasterMSA == null || vMasterMSA == 'undefined' || vMasterMSA == '') {
                vMasterMSA = '';
            } 

            if (vServiceOrder == null || vServiceOrder == 'undefined' || vServiceOrder == '') {
                vServiceOrder = '';
            }

            if (vSalesRepName == null || vSalesRepName == 'undefined' || vSalesRepName == '') {
                vSalesRepName = '';
            }

            if (vSalesRepEmail == null || vSalesRepEmail == 'undefined' || vSalesRepEmail == '') {
                vSalesRepEmail = '';
            }

            if (premiseid == null || premiseid == 'undefined' || premiseid == '') {
                premiseid = '';
            }

            if (vPremiseName == null || vPremiseName == 'undefined' || vPremiseName == '') {
			    vPremiseName = '';
			}
			if (vAddress == null || vAddress == 'undefined' || vAddress == '') {
			    vAddress = '';
			}
			if (cityid == null || cityid == 'undefined' || cityid == '') {
			    cityid = '';
			}
			if (stateid == null || stateid == 'undefined' || stateid == '') {
			    stateid = '';
			}
			if (countyid == null || countyid == 'undefined' || countyid == '') {
			    countyid = '';
			}
			if (zipcode == null || zipcode == 'undefined' || zipcode == '') {
			    zipcode = '';
			}
			if (zoneid == null || zoneid == 'undefined' || zoneid == '') {
			    zoneid = '';
			}
			if (vZoneName == null || vZoneName == 'undefined' || vZoneName == '') {
			    vZoneName = '';
			}
			if (networkid == null || networkid == 'undefined' || networkid == '') {
			    networkid = '';
			}
			if (vNetwork == null || vNetwork == 'undefined' || vNetwork == '') {
			    vNetwork = '';
			}
			if (vPremiseType == null || vPremiseType == 'undefined' || vPremiseType == '') {
			    vPremiseType = '';
			}
			if (vCompanyName == null || vCompanyName == 'undefined' || vCompanyName == '') {
			    vCompanyName = '';
			}
			if (vConnectionTypeName == null || vConnectionTypeName == 'undefined' || vConnectionTypeName == '') {
			    vConnectionTypeName = '';
			}
			if (vServiceType1 == null || vServiceType1 == 'undefined' || vServiceType1 == '') {
			    vServiceType1 = '';
			}
			if (vStatus == null || vStatus == 'undefined' || vStatus == '') {
			    vStatus = '';
			}

			var vPremiseData = 	premiseid+" ("+vPremiseName+"; "+vPremiseType+")";
            content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
            content += "<h5 class='border-bottom pb-2 mb-3'>Service Order #" + id + " (" + vServiceOrder + ")</h5>";
            content += "<div class='d-flex'><h6>" + vMasterMSA + "</h6></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Premise :</span>&nbsp;" + vPremiseData + "</div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Zone :</span>&nbsp;" + vZoneName + "</span></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Network :</span>&nbsp;" + vNetwork + "</span></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>SalesRep Name :</span>&nbsp;" + vSalesRepName + "</div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>SalesRep Email :</span>&nbsp;" + vSalesRepEmail + "</span></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Carrier :</span>&nbsp;" + vCompanyName + "</span></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Service Type :</span>&nbsp;" + vServiceType1 + "</span></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Status :</span>&nbsp;" + vStatus + "</div>";
            content += "<div class='button mt-3'><a class='btn btn-primary  mr-2 text-white' href='" + site_url + "service_order/edit&mode=Update&iServiceOrderId=" + id + "' target='_blank'>Edit Service Order</a></div>";
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
    })(marker, id, vMasterMSA, vServiceOrder, vSalesRepName, vSalesRepEmail, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vCompanyName, vConnectionTypeName, vServiceType1, vStatus));
}

function workOrderinfo_popup(marker, id, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vServiceOrder, vWOProject, vType, vRequestor, vAssignedTo, vStatus) {
    google.maps.event.addListener(marker, 'click', (function(marker, id, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vServiceOrder, vWOProject, vType, vRequestor, vAssignedTo, vStatus) {
        return function() {
            var content = "";
            __marker__ = marker;

            if (premiseid == null || premiseid == 'undefined' || premiseid == '') {
                premiseid = '';
            }

            if (vPremiseName == null || vPremiseName == 'undefined' || vPremiseName == '') {
			    vPremiseName = '';
			}
			if (vAddress == null || vAddress == 'undefined' || vAddress == '') {
			    vAddress = '';
			}
			if (cityid == null || cityid == 'undefined' || cityid == '') {
			    cityid = '';
			}
			if (stateid == null || stateid == 'undefined' || stateid == '') {
			    stateid = '';
			}
			if (countyid == null || countyid == 'undefined' || countyid == '') {
			    countyid = '';
			}
			if (zipcode == null || zipcode == 'undefined' || zipcode == '') {
			    zipcode = '';
			}
			if (zoneid == null || zoneid == 'undefined' || zoneid == '') {
			    zoneid = '';
			}
			if (vZoneName == null || vZoneName == 'undefined' || vZoneName == '') {
			    vZoneName = '';
			}
			if (networkid == null || networkid == 'undefined' || networkid == '') {
			    networkid = '';
			}
			if (vNetwork == null || vNetwork == 'undefined' || vNetwork == '') {
			    vNetwork = '';
			}
			if (vPremiseType == null || vPremiseType == 'undefined' || vPremiseType == '') {
			    vPremiseType = '';
			}

			if (vServiceOrder == null || vServiceOrder == 'undefined' || vServiceOrder == '') {
			    vServiceOrder = '';
			}

			if (vWOProject == null || vWOProject == 'undefined' || vWOProject == '') {
			    vWOProject = '';
			}

			if (vType == null || vType == 'undefined' || vType == '') {
			    vType = '';
			}

			if (vRequestor == null || vRequestor == 'undefined' || vRequestor == '') {
			    vRequestor = '';
			}

			if (vAssignedTo == null || vAssignedTo == 'undefined' || vAssignedTo == '') {
			    vAssignedTo = '';
			}
			
			if (vStatus == null || vStatus == 'undefined' || vStatus == '') {
			    vStatus = '';
			}

			var vPremiseData = 	premiseid+" ("+vPremiseName+"; "+vPremiseType+")";
            content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
            content += "<h5 class='border-bottom pb-2 mb-3'>Work Order #" + id + " (" + vType + ")</h5>";
            content += "<div class='d-flex'><h6>" + vWOProject + "</h6></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Premise :</span>&nbsp;" + vPremiseData + "</div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Zone :</span>&nbsp;" + vZoneName + "</span></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Network :</span>&nbsp;" + vNetwork + "</span></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Service Order :</span>&nbsp;" + vServiceOrder + "</div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Requestor :</span>&nbsp;" + vRequestor + "</span></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Assigned To :</span>&nbsp;" + vAssignedTo + "</span></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Status :</span>&nbsp;" + vStatus + "</div>";
            content += "<div class='button mt-3'><a class='btn btn-primary  mr-2 text-white' href='" + site_url + "service_order/workorder_add&mode=Update&iWOId=" + id + "' target='_blank'>Edit Work Order</a></div>";
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
    })(marker, id, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vServiceOrder, vWOProject, vType, vRequestor, vAssignedTo, vStatus));
}

function premiseCircuitinfo_popup(marker, id, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vWorkOrder, circuitid, vCircuitName, connectiontypeid, vConnectionTypeName, vStatus) {
    google.maps.event.addListener(marker, 'click', (function(marker, id, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vWorkOrder, circuitid, vCircuitName, connectiontypeid, vConnectionTypeName, vStatus) {
        return function() {
            var content = "";
            __marker__ = marker;

            if (premiseid == null || premiseid == 'undefined' || premiseid == '') {
                premiseid = '';
            }

            if (vPremiseName == null || vPremiseName == 'undefined' || vPremiseName == '') {
			    vPremiseName = '';
			}
			if (vAddress == null || vAddress == 'undefined' || vAddress == '') {
			    vAddress = '';
			}
			if (cityid == null || cityid == 'undefined' || cityid == '') {
			    cityid = '';
			}
			if (stateid == null || stateid == 'undefined' || stateid == '') {
			    stateid = '';
			}
			if (countyid == null || countyid == 'undefined' || countyid == '') {
			    countyid = '';
			}
			if (zipcode == null || zipcode == 'undefined' || zipcode == '') {
			    zipcode = '';
			}
			if (zoneid == null || zoneid == 'undefined' || zoneid == '') {
			    zoneid = '';
			}
			if (vZoneName == null || vZoneName == 'undefined' || vZoneName == '') {
			    vZoneName = '';
			}
			if (networkid == null || networkid == 'undefined' || networkid == '') {
			    networkid = '';
			}
			if (vNetwork == null || vNetwork == 'undefined' || vNetwork == '') {
			    vNetwork = '';
			}
			if (vPremiseType == null || vPremiseType == 'undefined' || vPremiseType == '') {
			    vPremiseType = '';
			}

			if (vWorkOrder == null || vWorkOrder == 'undefined' || vWorkOrder == '') {
			    vWorkOrder = '';
			}

			if (circuitid == null || circuitid == 'undefined' || circuitid == '') {
			    circuitid = '';
			}

			if (vCircuitName == null || vCircuitName == 'undefined' || vCircuitName == '') {
			    vCircuitName = '';
			}

			if (connectiontypeid == null || connectiontypeid == 'undefined' || connectiontypeid == '') {
			    connectiontypeid = '';
			}

			if (vConnectionTypeName == null || vConnectionTypeName == 'undefined' || vConnectionTypeName == '') {
			    vConnectionTypeName = '';
			}
			
			if (vStatus == null || vStatus == 'undefined' || vStatus == '') {
			    vStatus = '';
			}

			var vPremiseData = 	premiseid+" ("+vPremiseName+"; "+vPremiseType+")";
            content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
            content += "<h5 class='border-bottom pb-2 mb-3'>Premise #" + vPremiseData +"</h5>";
            content += "<div class='d-flex'><h6>Workorder" + vWorkOrder + "</h6></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Zone :</span>&nbsp;" + vZoneName + "</span></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Network :</span>&nbsp;" + vNetwork + "</span></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Circuit Name :</span>&nbsp;" + vCircuitName + "</div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Connection Type :</span>&nbsp;" + vConnectionTypeName + "</span></div>";
            content += "<div class='d-flex'><span class='font-weight-bold'>Status :</span>&nbsp;" + vStatus + "</div>";
            content += "<div class='button mt-3'><a class='btn btn-primary  mr-2 text-white' href='" + site_url + "premise_circuit/premise_circuit_edit&mode=Update&iPremiseCircuitId=" + id + "' target='_blank'>Edit Premise Circuit</a></div>";
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
    })(marker, id,  premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vWorkOrder, circuitid, vCircuitName, connectiontypeid, vConnectionTypeName, vStatus));
}

function newLocation(newLat,newLng) {
	//alert(newLat +" == "+newLng)
	if (typeof(newLat) !== 'undefined' && newLat && typeof(newLng) !== 'undefined' && newLng) {
		map.setCenter({
			lat : newLat,
			lng : newLng
		});
	}
	
    //console.log("defaultZoom New Location = "+defaultZoom)
    google.maps.event.addListener(map, 'zoom_changed', function() {
	    defaultZoom = map.getZoom();
	});
	if(defaultZoom < 14) {
		defaultZoom = 14;	
	}
	map.setZoom(defaultZoom);
	//console.log("defaultZoom New Location1 = "+defaultZoom)
}

function getPremiseFiberInquiryFilterData(siteFilter,srFilter){
	//console.log('filter_map_site_sr');
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
				action: "getPremiseFiberInquiryFilterData",
				premiseid: siteFilter.join(","),
				fInquiryId: srFilter.join(","),
			},
			cache: true,
			beforeSend: function() {
				$(".loading").show();
			},
			success: function(data) {
				//console.log(data);
				if (data) {
					//console.log('data found-filter');
					var response = JSON.parse(data);
					var siteData = response.site;
					var fInquiryData = response.fInquiry;
					//console.log(response);
					var ccount = 0;
					if(siteData !== undefined && jQuery.isEmptyObject(siteData) == false){
						
						$.each(siteData, function(premiseid, item) {
								if (siteData[premiseid].point !== undefined) {
									for (i = 0; i < siteData[premiseid].point.length; i++) {
                                        
                                        var pointMatrix = {
                                            lat: siteData[premiseid].point[i]['lat'] + mathRandLat,
                                            lng: siteData[premiseid].point[i]['lng'] + mathRandLng
                                        };
                                        //showPointMap(pointMatrix, map, siteData[premiseid].icon, premiseid);

                                        sitesrFilterMarker[ccount] = new google.maps.Marker({
                                            map: map,
                                            position: pointMatrix,
                                            icon: siteData[premiseid].icon
                                        });
                                        //if (siteData[premiseid].length != 0) {
                                            newLocation(pointMatrix.lat, pointMatrix.lng);
                                       // }
                                        $site_map = sitesrFilterMarker[ccount];
                                        gmarkers.push($site_map);
                                        info_popup($site_map, premiseid);
                                        sitesrFilterMarker[ccount].setMap(map);

                                        //Extend each marker's position in LatLngBounds object.
                                        latlngbounds.extend(sitesrFilterMarker[ccount].position);

                                        ccount++;

                                        var vName = siteData[premiseid].vName;
                                        var vAddress = siteData[premiseid].vAddress;
                                        var vRequestType = siteData[premiseid].vRequestType;
                                        var vAssignTo = siteData[premiseid].vAssignTo;
                                        var vStatus = siteData[premiseid].vStatus;
                                    }
								}
							
						});
					}
					if(fInquiryData !== undefined && jQuery.isEmptyObject(fInquiryData) == false){
						$.each(fInquiryData, function(id, item) {
							if (fInquiryData[id].point !== undefined) {
                                    for (i = 0; i < fInquiryData[id].point.length; i++) {

                                        var pointMatrix = {
                                            lat: fInquiryData[id].point[i]['lat']+ mathRandLat,
                                            lng: fInquiryData[id].point[i]['lng']+ mathRandLng
                                        };
                                        var vName = fInquiryData[id].vName;
                                        var vAddress = fInquiryData[id].vAddress;
                                        var premiseid = fInquiryData[id].premiseid;
                                        var vPremiseName = fInquiryData[id].vPremiseName;
                                        var vPremiseSubType	= fInquiryData[id].vPremiseSubType;
                                        var vEngagement = fInquiryData[id].vEngagement;
                                        var vZoneName = fInquiryData[id].vZoneName;
                                        var vNetwork = fInquiryData[id].vNetwork;
                                        var vStatus = fInquiryData[id].vStatus;
                                        var fiberInquiryId = fInquiryData[id].fiberInquiryId;

                                        sitesrFilterMarker[ccount] = new google.maps.Marker({
                                            map: map,
                                            position: pointMatrix,
                                            icon: fInquiryData[id].icon
                                        });
                                        newLocation(pointMatrix.lat,pointMatrix.lng);
                                        $sr_map = sitesrFilterMarker[ccount];

                                        fiberInquiryinfo_popup($sr_map, id, vName, vAddress, premiseid, vPremiseName, vPremiseSubType, vEngagement, vZoneName, vNetwork, vStatus, fiberInquiryId);
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
					//console.log('no data found');
					//alert("No sites found");
					//clearMap();
				
				}
				 
				$(".loading").hide();
			}
		});
    }
}

function mapRedirectServiceOrder(ServiceOrderLength, iPremiseId) {
	if(ServiceOrderLength > 0){
		var so_url = site_url+"service_order/list&iPremiseId="+iPremiseId;
		window.open(so_url,'_blank');
	}else {
		alert("No service order exists for this premise");return false;
	}
}

function mapRedirectWorkOrder(WorkOrderLength, iPremiseId) {
	if(WorkOrderLength > 0){
		var wo_url = site_url+"service_order/workorder_list&iPremiseId="+iPremiseId;
		window.open(wo_url,'_blank');
	}else {
		alert("No work order exists for this premise");return false;
	}
}