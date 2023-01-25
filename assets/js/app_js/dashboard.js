 $(document).ready(function(){
    if(dashboard_amchart_arr.length > 0) {
        $(".chartdiv_row").show();
        AmCharts.makeChart("chartdiv", {
            "type": "serial",
            "categoryField": "vServiceType",
            "startDuration": 1,
            "theme": "dark",
            "categoryAxis": {
                "classNameField": "",
                "gridPosition": "start",
                "title": "Service Type"
            },
            "chartCursor": {
                "enabled": true
            },
            "chartScrollbar": {
                "enabled": true
            },
            "trendLines": [],
            "graphs": [{
                    "fillAlphas": 1,
                    "id": "AmGraph-1",
                    "title": "graph 1",
                    "type": "column",
                    "valueField": "count"
                },
                {
                    "id": "AmGraph-2",
                    "title": "graph 2"
                }
            ],
            "guides": [],
            "valueAxes": [{
                "id": "ValueAxis-1",
                "title": "Total Count"
            }],
            "allLabels": [],
            "balloon": {},
            "titles": [{
                "id": "Title-1",
                "size": 15,
                "text": "Services Installed YTD"
            }],
            "dataProvider": dashboard_amchart_arr
        });
    }else {
        $(".chartdiv_row").hide();
    }

    initMap();
});

function initMap() {
    var maplat = parseFloat(MAP_LATITUDE);
    var maplng = parseFloat(MAP_LONGITUDE);

    map = new google.maps.Map(document.getElementById('dashboard_map'), {
        zoom: 5,
        center: {
            lat: maplat,
            lng: maplng
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
        mapTypeId: 'terrain'
    });
    map.loaded = false;
    g_LoadedListener = google.maps.event.addListener(map, "tilesloaded", function(){
        //console.log('load');
        if(this.loaded){return;}
            this.loaded = true;
        onMapLoad();
    }); 
    map.setCenter(new google.maps.LatLng(maplat, maplng));

    /*---------- Marker Spider --------------*/
    var spiderConfig = {
        keepSpiderfied: true
    };

    markerSpiderfier = new OverlappingMarkerSpiderfier(map, spiderConfig);
    /*---------- Marker Spider --------------*/
}

function onMapLoad(){
    $.ajax({
        url: site_url+'dashboard/dashboard',
        type: 'POST',
        data: 'mode=dashboard_map',
        dataType: "json",
        success: function(data){
            var siteData = data['site'];
            if(Object.keys(siteData).length > 0){
                $.each(siteData, function(key, datas) {
                    if(key == "Serviceorder"){
                        $.each(datas, function(k, item) {
                            for (i = 0; i < item.length; i++) {
                                if (item[i]['vLatitude'] !== undefined && item[i]['vLongitude'] !== undefined) {
                                    var id = item[i]['iServiceOrderId'];
                                    var vMasterMSA = item[i]['vMasterMSA'];
                                    var vServiceOrder = item[i]['vServiceOrder'];
                                    var vSalesRepName = item[i]['vSalesRepName'];
                                    var vSalesRepEmail = item[i]['vSalesRepEmail'];
                                    var premiseid = item[i]['iPremiseId'];
                                    var vPremiseName = item[i]['vPremiseName'];
                                    var vAddress = item[i]['vAddress'];
                                    var vZoneName = item[i]['vZoneName'];
                                    var vNetwork = item[i]['vNetwork'];
                                    var vPremiseType = item[i]['vPremiseType'];
                                    var vCompanyName = item[i]['vCompanyName'];
                                    var vConnectionTypeName = item[i]['vConnectionTypeName'];
                                    var vServiceType1 = item[i]['vServiceType1'];
                                    var vStatus = item[i]['vSOStatus'];
                                    var vIcon = item[i]['vIcon'];
                                    var vPremiseData =  premiseid+" ("+vPremiseName+"; "+vPremiseType+")";
                                    var content = "";
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


                                    var lat_long = new google.maps.LatLng(item[i]['vLatitude'], item[i]['vLongitude']);
                                    var marker = new google.maps.Marker({
                                        icon: vIcon,
                                        position: lat_long
                                    });
                                    if (marker) {
                                        CreatePopup(content, marker, i, lat_long);

                                        serviceorder_arr.push(marker);

                                        if (markerSpiderfier) {
                                            markerSpiderfier.addMarker(marker);
                                        }
                                    }

                                }
                            }
                            var selected_serviceorder = serviceorder_arr.length;
                            for (i = 0; i < selected_serviceorder; i++) {
                                serviceorder_arr[i].setMap(map);
                            }
                        });
                    }
                });
            }
        }
    });
}

function CreatePopup(html, marker, ind, lat_long) {
    google.maps.event.addListener(marker, 'click', (function (marker, ind) {
        return function () {
            __marker__ = marker;

            if (infowindow) {
                infowindow.close();
            }

            infowindow = new google.maps.InfoWindow({
                content: html,
                zIndex: 100
            });

            infowindow.open(map, marker);

            //google.maps.event.clearListeners(marker, 'mouseover');
            google.maps.event.clearListeners(marker, 'mouseout');
        }
    })(marker, ind));
}