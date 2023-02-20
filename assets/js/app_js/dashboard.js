
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
    loadProfileData();

    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

});

function initMap() {
    var maplat = parseFloat(MAP_LATITUDE);
    var maplng = parseFloat(MAP_LONGITUDE);

    map = new google.maps.Map(document.getElementById('dashboard_map'), {
        zoom: 10,
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
        mapTypeId: 'satellite'
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
                    }else if(key == "FiberInquiry"){
                        $.each(datas, function(k, item) {
                            for (i = 0; i < item.length; i++) {
                                if (item[i]['vLatitude'] !== undefined && item[i]['vLongitude'] !== undefined) {
                                    var id = item[i]['iFiberInquiryId'];
                                    var vName = item[i]['vContactName'];
                                    var vAddress = item[i]['vAddress'];
                                    var premiseid = item[i]['iMatchingPremiseId'];
                                    var vPremiseName = item[i]['vPremiseName'];
                                    var vPremiseSubType = item[i]['vPremiseSubType'];
                                    var vEngagement = item[i]['vEngagement'];
                                    var vZoneName = item[i]['vZoneName'];
                                    var vNetwork = item[i]['vNetwork'];
                                    var vStatus = item[i]['vFStatus'];
                                    var vIcon = item[i]['vIcon'];
                                    var content = "";
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


                                    var lat_long = new google.maps.LatLng(item[i]['vLatitude'], item[i]['vLongitude']);
                                    var marker = new google.maps.Marker({
                                        icon: vIcon,
                                        position: lat_long
                                    });
                                    if (marker) {
                                        CreatePopup(content, marker, i, lat_long);

                                        fiberInquiry_arr.push(marker);

                                        if (markerSpiderfier) {
                                            markerSpiderfier.addMarker(marker);
                                        }
                                    }

                                }
                            }
                            var selected_fiberInquiry = fiberInquiry_arr.length;
                            for (i = 0; i < selected_fiberInquiry; i++) {
                                fiberInquiry_arr[i].setMap(map);
                            }
                        });
                    }else if(key == "Workorder"){
                        $.each(datas, function(k, item) {
                            for (i = 0; i < item.length; i++) {
                                if (item[i]['vLatitude'] !== undefined && item[i]['vLongitude'] !== undefined) {
                                    var id = item[i]['iWOId'];
                                    var vName = item[i]['vContactName'];
                                    var vAddress = item[i]['vAddress'];
                                    var premiseid = item[i]['iPremiseId'];
                                    var vPremiseName = item[i]['vPremiseName'];
                                    var vZoneName = item[i]['vZoneName'];
                                    var vNetwork = item[i]['vNetwork'];
                                    var vStatus = item[i]['vStatus'];
                                    var vPremiseType = item[i]['vPremiseType'];
                                    var vServiceOrder = item[i]['vServiceOrder'];
                                    var vWOProject = item[i]['vWOProject'];
                                    var vType = item[i]['vType'];
                                    var vRequestor = item[i]['vRequestor'];
                                    var vAssignedTo = item[i]['vAssignedTo'];
                                    var vStatus = item[i]['vStatus'];
                                    var vIcon = item[i]['vIcon'];
                                    
                                    var content = "";
                                    var vPremiseData =  premiseid+" ("+vPremiseName+"; "+vPremiseType+")";
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


                                    var lat_long = new google.maps.LatLng(item[i]['vLatitude'], item[i]['vLongitude']);
                                    var marker = new google.maps.Marker({
                                        icon: vIcon,
                                        position: lat_long
                                    });
                                    if (marker) {
                                        CreatePopup(content, marker, i, lat_long);

                                        workorder_arr.push(marker);

                                        if (markerSpiderfier) {
                                            markerSpiderfier.addMarker(marker);
                                        }
                                    }

                                }
                            }
                            var selected_workorder = workorder_arr.length;
                            for (i = 0; i < selected_workorder; i++) {
                                workorder_arr[i].setMap(map);
                            }
                        });
                    }else if(key == "TroubleTicket"){
                        $.each(datas, function(k, item) {
                            for (i = 0; i < item.length; i++) {
                                if (item[i]['vLatitude'] !== undefined && item[i]['vLongitude'] !== undefined) {
                                    var id = item[i]['iTroubleTicketId'];
                                    var iSeverity = item[i]['iSeverity'];
                                    var vAddress = item[i]['vAddress'];
                                    var premiseid = item[i]['iPremiseId'];
                                    var vPremiseName = item[i]['vPremiseName'];
                                    var vPremiseType = item[i]['vPremiseType'];
                                    var vServiceOrder = item[i]['vServiceOrder'];
                                    var dTroubleStartDate = item[i]['dTroubleStartDate'];
                                    var vStatus = item[i]['iStatus'];
                                    var vIcon = item[i]['vIcon'];
                                    var content = "";
                                    var vPremiseData =  premiseid+" ("+vPremiseName+"; "+vPremiseType+")";
                                    content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
                                    content += "<h5 class='border-bottom pb-2 mb-3'>Trouble Ticket #" + id +"</h5>";
                                    content += "<div class='d-flex'><h6>" + iSeverity + "</h6></div>";
                                    content += "<div class='d-flex'><span class='font-weight-bold'>Premise :</span>&nbsp;" + vPremiseData + "</div>";
                                    content += "<div class='d-flex'><span class='font-weight-bold'>Address :</span>&nbsp;" + vAddress + "</div>";
                                    content += "<div class='d-flex'><span class='font-weight-bold'>Service Order :</span>&nbsp;" + vServiceOrder + "</div>";
                                    content += "<div class='d-flex'><span class='font-weight-bold'>Trouble Start Date :</span>&nbsp;" + dTroubleStartDate + "</span></div>";
                                    content += "<div class='d-flex'><span class='font-weight-bold'>Status :</span>&nbsp;" + vStatus + "</div>";
                                    content += "<div class='button mt-3'><a class='btn btn-primary  mr-2 text-white' href='" + site_url + "trouble_ticket/trouble_ticket_edit&mode=Update&iTroubleTicketId=" + id + "' target='_blank'>Edit Trouble Ticket</a></div>";
                                    content += "</div>";


                                    var lat_long = new google.maps.LatLng(item[i]['vLatitude'], item[i]['vLongitude']);
                                    var marker = new google.maps.Marker({
                                        icon: vIcon,
                                        position: lat_long
                                    });
                                    if (marker) {
                                        CreatePopup(content, marker, i, lat_long);
                                        trouble_ticket_arr.push(marker);
                                        if (markerSpiderfier) {
                                            markerSpiderfier.addMarker(marker);
                                        }
                                    }

                                }
                            }
                            var selected_trouble_ticket = trouble_ticket_arr.length;
                            for (i = 0; i < selected_trouble_ticket; i++) {
                                trouble_ticket_arr[i].setMap(map);
                            }
                        });
                    }else if(key == "MaintenanceTicket"){
                        $.each(datas, function(k, item) {
                            for (i = 0; i < item.length; i++) {
                                if (item[i]['vLatitude'] !== undefined && item[i]['vLongitude'] !== undefined) {
                                    var id = item[i]['iMaintenanceTicketId'];
                                    var iSeverity = item[i]['iSeverity'];
                                    var vAddress = item[i]['vAddress'];
                                    var premiseid = item[i]['iPremiseId'];
                                    var vPremiseName = item[i]['vPremiseName'];
                                    var vPremiseType = item[i]['vPremiseType'];
                                    var vServiceOrder = item[i]['vServiceOrder'];
                                    var dMaintenanceStartDate = item[i]['dMaintenanceStartDate'];
                                    var vStatus = item[i]['iStatus'];
                                    var vIcon = item[i]['vIcon'];
                                    var content = "";
                                    var vPremiseData =  premiseid+" ("+vPremiseName+"; "+vPremiseType+")";
                                    content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
                                    content += "<h5 class='border-bottom pb-2 mb-3'>Maintenance Ticket #" + id +"</h5>";
                                    content += "<div class='d-flex'><h6>" + iSeverity + "</h6></div>";
                                    content += "<div class='d-flex'><span class='font-weight-bold'>Premise :</span>&nbsp;" + vPremiseData + "</div>";
                                    content += "<div class='d-flex'><span class='font-weight-bold'>Address :</span>&nbsp;" + vAddress + "</div>";
                                    content += "<div class='d-flex'><span class='font-weight-bold'>Service Order :</span>&nbsp;" + vServiceOrder + "</div>";
                                    content += "<div class='d-flex'><span class='font-weight-bold'>Maintenance Start Date :</span>&nbsp;" + dMaintenanceStartDate + "</span></div>";
                                    content += "<div class='d-flex'><span class='font-weight-bold'>Status :</span>&nbsp;" + vStatus + "</div>";
                                    content += "<div class='button mt-3'><a class='btn btn-primary  mr-2 text-white' href='" + site_url + "maintenance_ticket/maintenance_ticket_edit&mode=Update&iMaintenanceTicketId=" + id + "' target='_blank'>Edit Maintenance Ticket</a></div>";
                                    content += "</div>";


                                    var lat_long = new google.maps.LatLng(item[i]['vLatitude'], item[i]['vLongitude']);
                                    var marker = new google.maps.Marker({
                                        icon: vIcon,
                                        position: lat_long
                                    });
                                    if (marker) {
                                        CreatePopup(content, marker, i, lat_long);
                                        maintenance_ticket_arr.push(marker);
                                        if (markerSpiderfier) {
                                            markerSpiderfier.addMarker(marker);
                                        }
                                    }

                                }
                            }
                            var selected_maintenance_ticket = maintenance_ticket_arr.length;
                            for (i = 0; i < selected_maintenance_ticket; i++) {
                                maintenance_ticket_arr[i].setMap(map);
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

function drawChart() {
    
    var data = google.visualization.arrayToDataTable(dashboard_SObarchart);
    var options = {
        title: 'Service Order Status',
        titleTextStyle: {
            color: primarycolor,  
            fontSize: 18,              
            bold: true,
        },
        bars: 'vertical', // Required for Material Bar Charts.
        width: 600,
        height: 300,
        legend: {textStyle: {fontSize: 12}},
    };
    var chart = new google.charts.Bar(document.getElementById('serviceorder_chart'));
    chart.draw(data, google.charts.Bar.convertOptions(options));


    var wodata = google.visualization.arrayToDataTable(dashboard_WObarchart);
    var WOoptions = {
        title: 'Work Order Status',
        titleTextStyle: {
            color: primarycolor,        
            fontSize: 18,              
            bold: true,      
        },
        bars: 'vertical', // Required for Material Bar Charts.
        width: 600,
        height: 300,
        legend: {textStyle: {fontSize: 12}},

    };
    var wochart = new google.charts.Bar(document.getElementById('workorder_chart'));
    wochart.draw(wodata, google.charts.Bar.convertOptions(WOoptions));
}

function loadProfileData() {
    var so_str = '';
    if(dashboard_serviceorder && dashboard_serviceorder.length > 0){
        for (var i = 0; i < dashboard_serviceorder.length; i++) {
            var color_class = dashboard_serviceorder[i].color_class;
            so_str += '<tr>';
                so_str += '<td class="text-center">'+dashboard_serviceorder[i].id+'</td>';
                so_str += '<td>'+dashboard_serviceorder[i].vPremise+'</td>';
                so_str += '<td>'+dashboard_serviceorder[i].vCarrier+'</td>';
                so_str += '<td class="text-center font-weight-bold '+color_class+'">'+dashboard_serviceorder[i].vStatus+'</td>';
            so_str += '</tr>';
        }
    }else {
        so_str += '<tr>';
            so_str += '<td colspan="4" class="text-center font-weight-bold">No Records found!</td>';
        so_str += '</tr>';   
    }
    if(so_str != ''){
        $(".service_order_data").html(so_str);
    }

    var wo_str = '';
    if(dashboard_workorder && dashboard_workorder.length > 0){
        for (var i = 0; i < dashboard_workorder.length; i++) {
            var color_class = dashboard_workorder[i].color_class;
            wo_str += '<tr>';
                wo_str += '<td class="text-center">'+dashboard_workorder[i].id+'</td>';
                wo_str += '<td>'+dashboard_workorder[i].vPremise+'</td>';
                wo_str += '<td>'+dashboard_workorder[i].vServiceOrder+'</td>';
                wo_str += '<td class="text-center font-weight-bold '+color_class+'">'+dashboard_workorder[i].vStatus+'</td>';
            wo_str += '</tr>';
        }
    }else {
        wo_str += '<tr>';
            wo_str += '<td colspan="4" class="text-center font-weight-bold">No Records found!</td>';
        wo_str += '</tr>';   
    }
    if(wo_str != ''){
        $(".work_order_data").html(wo_str);
    }

    var fi_str = '';
    if(dashboard_fiberinquiry && dashboard_fiberinquiry.length > 0){
        for (var i = 0; i < dashboard_fiberinquiry.length; i++) {
            var color_class = dashboard_fiberinquiry[i].color_class;
            fi_str += '<tr>';
                fi_str += '<td class="text-center">'+dashboard_fiberinquiry[i].id+'</td>';
                fi_str += '<td>'+dashboard_fiberinquiry[i].vName+'</td>';
                fi_str += '<td>'+dashboard_fiberinquiry[i].vAddress+'</td>';
                fi_str += '<td class="text-center font-weight-bold '+color_class+'">'+dashboard_fiberinquiry[i].vStatus+'</td>';
            fi_str += '</tr>';
        }
    }else {
        fi_str += '<tr>';
            fi_str += '<td colspan="4" class="text-center font-weight-bold">No Records found!</td>';
        fi_str += '</tr>';   
    }
    if(fi_str != ''){
        $(".fiberinquiry_data").html(fi_str);
    }
}