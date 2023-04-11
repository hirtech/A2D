siteFilter = [];
srFilter = [];
networkLayer = [];
custLayer = [];
pCircuitStatusLayer = [];
pCircuitcTypeLayer = [];
premiseStatusLayer = [];
premiseTypeLayer = [];
premisesubTypeLayer = [];
premiseAttribute = [];
zoneLayer = [];
fiberInquiryLayer = [];
serviceOrderLayer = [];
workOrderLayer = [];
let listenerLatLngCircle;
let listenerLatLngPoly;
let listenerLatLngPolyline;
let zoneLatLngPolyline = []; 
let zoneLatLngPoly = []; 
let zoneLatLngCircle = []; 
let mapAddZoneSiteListner = []; 
let addSiteMarker = null;
let mapAddZoneBatchSiteListner = []; 
let addBatchSiteMarker = [];
let  infoWindow;

$(document).ready(function() {
    //console.log("Api ready!");
    // alert(mode);
    initMap();
    generateJson();
    getnetworkLayerJson();
    getZoneLayerJson();
    getCustomLayerJson();
    getFiberInquiryJson();
    getServiceOrderJson();
    getWorkOrderJson();
    getPremiseCircuitJson();
    if (mode == 'filter_sites') {
        var iPremiseId = $.urlParam('iPremiseId');
        siteFilter.push(iPremiseId);
        getPremiseFiberInquiryFilterData(siteFilter, srFilter);
    } else if (mode == 'filter_fiberInquiry') {
        var iFiberInquiryId = $.urlParam('iFiberInquiryId');
        srFilter.push(iFiberInquiryId);
        getPremiseFiberInquiryFilterData(siteFilter, srFilter);
    } else {
        setTimeout(function(){
            if(typeof user_networks !== 'undefined' && user_networks.length > 0){
                skNetwork = skCity = skZones = skZipcode = [];
                networkLayer = zoneLayer = custLayer = [];
                fiberInquiryLayer = serviceOrderLayer = workOrderLayer = [];
                pCircuitStatusLayer = pCircuitcTypeLayer = [];
                premiseStatusLayer = premiseAttribute = premiseTypeLayer = premisesubTypeLayer = [];

                $.each($("input[name='sNetwork[]']:checked"), function() {
                    skNetwork.push($(this).val());
                });
                resetButton();
                clearFilterData();
                clearLayerData();
                getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
            }
        },2000);
    }  

    $(document).on("click", "#showDistance", function() {
        if ($("#showArea").prop("checked")) {
            $("#showArea").prop("checked", false);
        }
        if ($("#showCircle").prop("checked")) {
            $("#showCircle").prop("checked", false);
        }
        clearMapTool();
        clearMap();
        resetButton();
        clearLayerData();
        clearFilterData();
        if ($("#showDistance").prop("checked")) {
            poly = new google.maps.Polyline({
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 3
            });
            poly.setMap(map);

            // Add a listener for the click event
            listenerLatLngPolyline = map.addListener('click', addLatLng);

            //Add a listener event for zone draw polyline
            if(zCount > 0){
                for (k = 0 ;k <zCount; k++ ){
                    zoneLatLngPolyline[k] = google.maps.event.addListener(zonePolygonObj[k], 'click', addLatLng);
                }
            }
        }
    });
    $(document).on("click", "#showArea", function() {
        //console.log("Polygon Ready!!");
        if ($("#showDistance").prop("checked")) {
            $("#showDistance").prop("checked", false);
        }
        if ($("#showCircle").prop("checked")) {
            $("#showCircle").prop("checked", false);
        }
        clearMapTool();
        clearMap();
        resetButton();
        clearLayerData();
        clearFilterData();
        if ($("#showArea").prop("checked")) {
            poly = new google.maps.Polygon({
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 3
            });
            poly.setMap(map);

            // Add a listener for the click event
            listenerLatLngPoly =map.addListener('click', addLatLngPoly);

            //Add a listener event for zone draw polyline
            if(zCount > 0){
                for (k = 0 ;k <zCount; k++ ){
                    zoneLatLngPoly[k] = google.maps.event.addListener(zonePolygonObj[k], 'click', addLatLngPoly);
                }
            }
        }
    });

    $(document).on("click", "#showCircle", function() {
        // console.log("Circle Ready!!");
        if ($("#showDistance").prop("checked")) {
            $("#showDistance").prop("checked", false);
        }
        if ($("#showArea").prop("checked")) {
            $("#showArea").prop("checked", false);
        }
        //initMap();
        clearMapTool();

        clearMap();
        resetButton();
        clearLayerData();
        clearFilterData();
      
        if ($("#showCircle").prop("checked")) {
            listenerLatLngCircle= map.addListener('click', addLatLngCircle);
            //Add a listener event for zone draw polyline
            if(zCount > 0){
                for (k = 0 ;k <zCount; k++ ){
                    zoneLatLngCircle[k] = google.maps.event.addListener(zonePolygonObj[k], 'click', addLatLngCircle);
                }
            }
        }
    });

    $(document).on("click", ".map-tool-checkbox", function() {
        if ($("#selectAllNetwork").prop("checked") && $("#selectAllNetwork").val() != 'Yes') {
            $("#selectAllNetwork").prop("checked", false);
        }
        if ($("#selectAllZone").prop("checked") && $("#selectAllZone").val() != 'Yes') {
            $("#selectAllZone").prop("checked", false);
        }
        if ($("#selectAllCity").prop("checked") && $("#selectAllCity").val() != 'Yes') {
            $("#selectAllCity").prop("checked", false);
        }
        if ($("#selectAllZipcode").prop("checked") && $("#selectAllZipcode").val() != 'Yes') {
            $("#selectAllZipcode").prop("checked", false);
        }        
    });

    // ************* START - Filter Submenu ************* //
    // ************* Select All Network - Filter Submenu ************* //
    $(document).on("click", "#selectAllNetwork", function() {
        clearMap();
        var checksone = checkNetworkSelected();
        skNetwork = [];
        if(checksone == true){
            if ($("#selectAllNetwork").prop("checked")) {
                $(".selectAllNetwork").prop("checked", true);
                $("#selectAllNetwork").val("Yes");
                $.each($("input[name='sNetwork[]']:checked"), function() {
                    skNetwork.push($(this).val());
                });
            } else {
                $(".selectAllNetwork").prop("checked", false);
                $("#selectAllNetwork").val("No");
                skNetwork = [];
                $.each($("input[name='sNetwork[]']:checked"), function() {
                    skNetwork.push($(this).val());
                });
            }
        }else{
            skNetwork = [];
            skCity = [];
            skZipcode = [];
            skZones = [];
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        clearLayerData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Network - Filter Submenu ************* //
    $(document).on("click",".selectAllNetwork",function(){
        clearMap();
        var checksone = checkNetworkSelected();
        skNetwork = [];
        if(checksone == true){
            if ($("#selectAllNetwork").prop("checked") && $("#selectAllNetwork").val() != 'Yes') {
                $("#selectAllNetwork").prop("checked", false);
            }
            $.each($("input[name='sNetwork[]']:checked"), function() {
                skNetwork.push($(this).val());
            });
        }else{
            skNetwork = [];
            skCity = [];
            skZipcode = [];
            skZones = [];
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        clearLayerData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);       
    });

    // ************* Select Zone - Filter Submenu ************* //
    $(document).on("click", "#selectAllZone", function() {
        clearMap();
        skZones  = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllZone").prop("checked")) {
                $(".selectAllZone").prop("checked", true);
                $("#selectAllZone").val("Yes");
                //$(".selectAllZone").trigger('click');
                $.each($("input[name='skZones[]']:checked"), function() {
                    skZones.push($(this).val());
                });
                //alert(JSON.stringify(skZones))
            } else {
                $(".selectAllZone").prop("checked", false);
                $("#selectAllZone").val("No");
                skZones = [];
                
            }
        }else{
            skCity = [];
            skZipcode = [];
            skZones = [];
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        clearLayerData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);  
    });

    // ************* Select Zone - Filter Submenu ************* //
    $(document).on("click", ".selectAllZone", function() {
        clearMap();
        skZones = [];
        var checksone = checkNetworkSelected();

        if(checksone == true){
            if ($(".selectAllZone").prop("checked") && $(".selectAllZone").val() != 'Yes') {
                $("#selectAllZone").prop("checked", false);
            }
            $.each($("input[name='skZones[]']:checked"), function() {
                  skZones.push($(this).val());
            });


        }else{
            skCity = [];
            skZipcode = [];
            skZones = [];
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        clearLayerData();


        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select All City - Filter Submenu ************* //
    $(document).on("click", "#selectAllCity", function() {
        clearMap();
        var checksone = checkNetworkSelected();
        skCity = [];
        if(checksone == true){
            if ($("#selectAllCity").prop("checked")) {
                $(".selectAllCity").prop("checked", true);
                $("#selectAllCity").val("Yes");
                $.each($("input[name='city[]']:checked"), function() {
                    skCity.push($(this).val());
                });
            } else {
                $(".selectAllCity").prop("checked", false);
                $("#selectAllCity").val("No");
                skCity = [];
                $.each($("input[name='city[]']:checked"), function() {
                    skCity.push($(this).val());
                });
            }
        }else{
            skCity = [];
            skZipcode = [];
            skZones = [];
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        clearLayerData();
        //console.log(skCity);
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select City - Filter Submenu ************* //
    $(document).on("click",".selectAllCity",function(){
        clearMap();
        var checksone = checkNetworkSelected();
        skCity = [];
        if(checksone == true){
            if ($(".selectAllCity").prop("checked") && $(".selectAllCity").val() != 'Yes') {
                $("#selectAllCity").prop("checked", false);
            }
            $.each($("input[name='city[]']:checked"), function() {
                  skCity.push($(this).val());
            });
        }else{
            skCity = [];
            skZipcode = [];
            skZones = [];
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        clearLayerData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);       
    });

    // ************* Select All Zipcode - Filter Submenu ************* //
    $(document).on("click", "#selectAllZipcode", function() {
        clearMap();
        var checksone = checkNetworkSelected();
        skZipcode = [];
        if(checksone == true){
            if ($("#selectAllZipcode").prop("checked")) {
                $(".selectAllZipcode").prop("checked", true);
                $("#selectAllZipcode").val("Yes");
                $.each($("input[name='zipcode[]']:checked"), function() {
                    skZipcode.push($(this).val());
                });
            } else {
                $(".selectAllZipcode").prop("checked", false);
                $("#selectAllZipcode").val("No");
                skZipcode = [];
                $.each($("input[name='zipcode[]']:checked"), function() {
                    skZipcode.push($(this).val());
                });
            }
        }else{
            skZones = [];
            skCity = [];
            skZipcode = [];
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        clearLayerData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Zipcode - Filter Submenu ************* //
    $(document).on("click",".selectAllZipcode",function(){
        clearMap();
        var checksone = checkNetworkSelected();
        skZipcode = [];
        if(checksone == true){
            if ($(".selectAllZipcode").prop("checked") && $(".selectAllZipcode").val() != 'Yes') {
                $("#selectAllZipcode").prop("checked", false);
            }
            $.each($("input[name='zipcode[]']:checked"), function() {
                  skZipcode.push($(this).val());
            });
        }else{
            skCity = [];
            skZipcode = [];
            skZones = [];
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        clearLayerData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);       
    });

    // ************* END - Filter Submenu ************* //

    // ************* START - Layer Submenu ************* //
    // ************* Select All Network - Layer Submenu ************* //
    $(document).on("click", "#selectAllNetworkLayer", function() {
        //console.log("Select Network Layer");
        clearMap();
        networkLayer = [];
        if ($("#selectAllNetworkLayer").prop("checked")) {
            //console.log("Select Network Layer11");
            $(".selectAllNetworkLayer").prop("checked", true);
            $("#selectAllNetworkLayer").val("Yes");
            $.each($("input[name='networkLayer[]']:checked"), function() {
                networkLayer.push($(this).val());
            });
        } else {
            $(".selectAllNetworkLayer").prop("checked", false);
            $("#selectAllNetworkLayer").val("No");
            $.each($("input[name='networkLayer[]']:checked"), function() {
                networkLayer.push($(this).val());
            });
        }
        resetButton();
        clearFilterData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });
    
    // ************* Select Network - Layer Submenu ************* //
    $(document).on("click",".selectAllNetworkLayer",function(){
        clearMap();
        if ($("#selectAllNetworkLayer").prop("checked") && $("#selectAllNetworkLayer").val() != 'Yes') {
            $("#selectAllNetworkLayer").prop("checked", false);
        }
        networkLayer = [];
        $.each($("input[name='networkLayer[]']:checked"), function() {
              networkLayer.push($(this).val());
        });
        resetButton();
        clearFilterData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);        
    });

    // ************* Select All Custom KML - Layer Submenu ************* //
    $(document).on("click", "#selectAllCustLayer", function() {
        //console.log("Select Custom Layer");
        clearMap();
        custLayer = [];
        if ($("#selectAllCustLayer").prop("checked")) {
            $(".selectAllCustLayer").prop("checked", true);
            $("#selectAllCustLayer").val("Yes");

            $.each($("input[name='custlayer[]']:checked"), function() {
                custLayer.push($(this).val());
            });
            //console.log(custLayer);
        } else {
            $(".selectAllCustLayer").prop("checked", false);
            $("#selectAllCustLayer").val("No");
            $.each($("input[name='custlayer[]']:checked"), function() {
                custLayer.push($(this).val());
            });
        }
        resetButton();
        clearFilterData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Custom - Layer Submenu ************* //
    $(document).on("click",".selectAllCustLayer",function(){
        clearMap();
        if ($("#selectAllCustLayer").prop("checked") && $("#selectAllCustLayer").val() != 'Yes') {
            $("#selectAllCustLayer").prop("checked", false);
        }
        custLayer = [];
        $.each($("input[name='custlayer[]']:checked"), function() {
            custLayer.push($(this).val());
        });
        resetButton();
        clearFilterData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);       
    });

    // ************* Select All Zone KML - Layer Submenu ************* //
    $(document).on("click", "#selectAllZoneLayer", function() {
        //console.log("Select Custom Layer");
        clearMap();
        zoneLayer = [];
        if ($("#selectAllZoneLayer").prop("checked")) {
            $(".selectAllZoneLayer").prop("checked", true);
            $("#selectAllZoneLayer").val("Yes");

            $.each($("input[name='zoneLayer[]']:checked"), function() {
                zoneLayer.push($(this).val());
            });
            //console.log(zoneLayer);
        } else {
            $(".selectAllZoneLayer").prop("checked", false);
            $("#selectAllZoneLayer").val("No");
            $.each($("input[name='zoneLayer[]']:checked"), function() {
                zoneLayer.push($(this).val());
            });
        }
        resetButton();
        clearFilterData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Fiber Zone KML - Layer Submenu ************* //
    $(document).on("click",".selectAllZoneLayer",function(){
        clearMap();
        zoneLayer = [];
        if ($("#selectAllZoneLayer").prop("checked") && $("#selectAllZoneLayer").val() != 'Yes') {
            $("#selectAllZoneLayer").prop("checked", false);
        }
        
        $.each($("input[name='zoneLayer[]']:checked"), function() {
            zoneLayer.push($(this).val());
        });
        resetButton();
        clearFilterData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Fiber Inquiry - Layer Submenu ************* //
    $(document).on("click", "#selectAllFiberInquiries", function() {
        clearMap();
        fiberInquiryLayer = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllFiberInquiries").prop("checked")) {
                $.each($("input[name='selectAllFiberInquiries']:checked"), function() {
                    fiberInquiryLayer.push($(this).val());
                });
            } 
        } else {
            fiberInquiryLayer = [];
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Service Order - Layer Submenu ************* //
    $(document).on("click", "#selectAllServiceOrders", function() {
        clearMap();
        serviceOrderLayer = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllServiceOrders").prop("checked")) {
                $.each($("input[name='selectAllServiceOrders']:checked"), function() {
                    serviceOrderLayer.push($(this).val());
                });
            } 
        } else {
            serviceOrderLayer = [];
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);

    });

    // ************* Select Work Order - Layer Submenu ************* //
    $(document).on("click", "#selectAllWorkOrders", function() {
        clearMap();
        workOrderLayer = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllWorkOrders").prop("checked")) {
                $.each($("input[name='selectAllWorkOrders']:checked"), function() {
                    workOrderLayer.push($(this).val());
                });
            } 
        } else {
            workOrderLayer = [];
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Premise Circuit All - Layer Submenu ************* //
    $(document).on("click", "#selectAllPremiseCircuitLayer", function() {
        clearMap();
        pCircuitStatusLayer = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllPremiseCircuitLayer").prop("checked")) {
                $(".selectAllPremiseCircuitLayer").prop("checked", true);
                $(".selectAllPCircuitStatusLayer").prop("checked", true);
                $("#selectAllPremiseCircuitLayer").val("Yes");

                $.each($("input[name='pCircuitStatusLayer[]']:checked"), function() {
                    pCircuitStatusLayer.push($(this).val());
                });
            } else {
                $(".selectAllPremiseCircuitLayer").prop("checked", false);
                $(".selectAllPCircuitStatusLayer").prop("checked", false);
                $("#selectAllPremiseCircuitLayer").val("No");
                $.each($("input[name='pCircuitStatusLayer[]']:checked"), function() {
                    pCircuitStatusLayer.push($(this).val());
                });
            }
        }else {
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Premise Circuit Status All - Layer Submenu ************* //
    $(document).on("click", ".selectAllPremiseCircuitLayer", function() {
        clearMap();
        pCircuitStatusLayer = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($(".selectAllPremiseCircuitLayer").prop("checked")) {
                $(".selectAllPCircuitStatusLayer").prop("checked", true);
                $(".selectAllPremiseCircuitLayer").val("Yes");

                $.each($("input[name='pCircuitStatusLayer[]']:checked"), function() {
                    pCircuitStatusLayer.push($(this).val());
                });
            } else {
                $(".selectAllPremiseCircuitLayer").prop("checked", false);
                $(".selectAllPCircuitStatusLayer").prop("checked", false);
                $(".selectAllPremiseCircuitLayer").val("No");
                $.each($("input[name='pCircuitStatusLayer[]']:checked"), function() {
                    pCircuitStatusLayer.push($(this).val());
                });
            }
        }else {
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Premise Circuit Status  - Layer Submenu ************* //
    $(document).on("click",".selectAllPCircuitStatusLayer",function(){
        clearMap();
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllPCircuitStatusLayer").prop("checked") && $("#selectAllPCircuitStatusLayer").val() != 'Yes') {
                $("#selectAllPCircuitStatusLayer").prop("checked", false);
            }
            pCircuitStatusLayer = [];
            $.each($("input[name='pCircuitStatusLayer[]']:checked"), function() {
                  pCircuitStatusLayer.push($(this).val());
            });
        }else {
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select PremiseCircuit ConnectionType All - Layer Submenu ********** //
    $(document).on("click", "#selectAllPCircuitCTLayer", function() {
        clearMap();
        pCircuitcTypeLayer = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllPCircuitCTLayer").prop("checked")) {
                $(".selectAllPCircuitCTLayer").prop("checked", true);
                $("#selectAllPCircuitCTLayer").val("Yes");

                $.each($("input[name='pCircuitcTypeLayer[]']:checked"), function() {
                    pCircuitcTypeLayer.push($(this).val());
                });
            } else {
                $(".selectAllPCircuitCTLayer").prop("checked", false);
                $("#selectAllPCircuitCTLayer").val("No");
                $.each($("input[name='pCircuitcTypeLayer[]']:checked"), function() {
                    pCircuitcTypeLayer.push($(this).val());
                });
            }
        }else {
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select PremiseCircuit ConnectionType - Layer Submenu ********** //
    $(document).on("click",".selectAllPCircuitCTLayer",function(){
        clearMap();
        pCircuitcTypeLayer = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllPCircuitCTLayer").prop("checked") && $("#selectAllPCircuitCTLayer").val() != 'Yes') {
                $("#selectAllPCircuitCTLayer").prop("checked", false);
            }
            $.each($("input[name='pCircuitcTypeLayer[]']:checked"), function() {
                pCircuitcTypeLayer.push($(this).val());
            });
        }else {
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Premise All - Layer Submenu ************* //
    $(document).on("click", "#selectAllPremiseLayer", function() {
        clearMap();
        premiseStatusLayer = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllPremiseLayer").prop("checked")) {
                $(".selectAllPremiseLayer").prop("checked", true);
                $(".selectAllpremiseStatusLayer").prop("checked", true);
                $("#selectAllPremiseLayer").val("Yes");

                $.each($("input[name='premiseStatusLayer[]']:checked"), function() {
                    premiseStatusLayer.push($(this).val());
                });
            } else {
                $(".selectAllPremiseLayer").prop("checked", false);
                $(".selectAllPremiseLayer").prop("checked", false);
                $(".selectAllpremiseStatusLayer").prop("checked", false);
                $("#selectAllPremiseLayer").val("No");
                $.each($("input[name='premiseStatusLayer[]']:checked"), function() {
                    premiseStatusLayer.push($(this).val());
                });
            }
        }else {
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Premise All Status - Layer Submenu ************* //
    $(document).on("click", ".selectAllPremiseLayer", function() {
        clearMap();
        premiseStatusLayer = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($(".selectAllPremiseLayer").prop("checked")) {
                $(".selectAllpremiseStatusLayer").prop("checked", true);
                $(".selectAllPremiseLayer").val("Yes");

                $.each($("input[name='premiseStatusLayer[]']:checked"), function() {
                    premiseStatusLayer.push($(this).val());
                });
            } else {
                $(".selectAllPremiseLayer").prop("checked", false);
                $(".selectAllpremiseStatusLayer").prop("checked", false);
                $(".selectAllPremiseLayer").val("No");
                $.each($("input[name='premiseStatusLayer[]']:checked"), function() {
                    premiseStatusLayer.push($(this).val());
                });
            }
        }else {
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Premise Status  - Layer Submenu ************* //
    $(document).on("click",".selectAllpremiseStatusLayer",function(){
        clearMap();
        premiseStatusLayer = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllpremiseStatusLayer").prop("checked") && $("#selectAllpremiseStatusLayer").val() != 'Yes') {
                $("#selectAllpremiseStatusLayer").prop("checked", false);
            }
            $.each($("input[name='premiseStatusLayer[]']:checked"), function() {
                  premiseStatusLayer.push($(this).val());
            });
        }else {
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select Premise Attribute All - Layer Submenu ********** //
    $(document).on("click", "#selectAllpremiseAttributeLayer", function() {
        clearMap();
        premiseAttribute = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllpremiseAttributeLayer").prop("checked")) {
                $(".selectAllpremiseAttributeLayer").prop("checked", true);
                $("#selectAllpremiseAttributeLayer").val("Yes");

                $.each($("input[name='premiseAttribute[]']:checked"), function() {
                    premiseAttribute.push($(this).val());
                });
            } else {
                $(".selectAllpremiseAttributeLayer").prop("checked", false);
                $("#selectAllpremiseAttributeLayer").val("No");
                $.each($("input[name='premiseAttribute[]']:checked"), function() {
                    premiseAttribute.push($(this).val());
                });
            }
        }else {
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select Premise Attribute - Layer Submenu ********** //
    $(document).on("click",".selectAllpremiseAttributeLayer",function(){
        clearMap();
        premiseAttribute = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllpremiseAttributeLayer").prop("checked") && $("#selectAllpremiseAttributeLayer").val() != 'Yes') {
                $("#selectAllpremiseAttributeLayer").prop("checked", false);
            }
            $.each($("input[name='premiseAttribute[]']:checked"), function() {
                premiseAttribute.push($(this).val());
            });
        }else {
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select PremiseC Type All - Layer Submenu ********** //
    $(document).on("click", "#selectAllpremiseTypeLayer", function() {
        clearMap();
        premiseTypeLayer = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllpremiseTypeLayer").prop("checked")) {
                $(".selectAllpremiseTypeLayer").prop("checked", true);
                $(".selectAllpremisesubTypeLayer").prop("checked", true);
                $("#selectAllpremiseTypeLayer").val("Yes");
                $.each($("input[name='premiseTypeLayer[]']:checked"), function() {
                    premiseTypeLayer.push($(this).val());
                });
            } else {
                $(".selectAllpremiseTypeLayer").prop("checked", false);
                $(".selectAllpremisesubTypeLayer").prop("checked", false);
                $("#selectAllpremiseTypeLayer").val("No");
                $.each($("input[name='premiseTypeLayer[]']:checked"), function() {
                    premiseTypeLayer.push($(this).val());
                });
            }
        }else {
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select Premise Type - Layer Submenu ********** //
    $(document).on("click",".selectAllpremiseTypeLayer",function(){
        clearMap();
        premiseTypeLayer = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            if ($("#selectAllpremiseTypeLayer").prop("checked") && $("#selectAllpremiseTypeLayer").val() != 'Yes') {
                $("#selectAllpremiseTypeLayer").prop("checked", false);
            }
            $.each($("input[name='premiseTypeLayer[]']:checked"), function() {
                premiseTypeLayer.push($(this).val());
                $("#premisesubTypeLayer_"+$(this).val()).prop("checked", false);
            });
        }else {
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select Premise sub Type - Layer Submenu ********** //
    $(document).on("click",".selectAllpremisesubTypeLayer",function(){
        //console.log("selectAllpremisesubTypeLayer")
        clearMap();
        premisesubTypeLayer = [];
        var checksone = checkNetworkSelected();
        if(checksone == true){
            $.each($("input[name='premisesubTypeLayer[]']:checked"), function() {
                premisesubTypeLayer.push($(this).val());
            });
        }else {
            //alert('Please select atleast one network from "Filter" Submenu.');
            toastr.error('Please select atleast one network from "Filter" Submenu.');
        }    
        resetButton();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });   

});

/** if Network Layer is not selected, don't allow other filter options **/
function checkNetworkSelected(){
    var network_cnt = 0;
    $.each($("input[name='sNetwork[]']:checked"), function() {
        network_cnt++;
    });
    if(network_cnt == 0){
        //****** "Filter" Submenu Options ******/
        //$("#selectAllNetwork").prop("checked", false);
        //$("input[name='sNetwork[]']").prop("checked", false);
        $("#selectAllZone").prop("checked", false);
        $("input[name='skZones[]']").prop("checked", false);
        $("#selectAllCity").prop("checked", false);
        $("input[name='city[]").prop("checked", false);
        $("#sAllZipcode").prop("checked", false);
        $("input[name='zipcode[]").prop("checked", false);

        //****** "Layer" Submenu Options ******/
        $("#selectAllPremiseLayer").prop("checked", false);
        $("#selectAllpremiseStatusLayer").prop("checked", false);
        $("input[name='premiseStatusLayer[]").prop("checked", false);
        $("#selectAllpremiseTypeLayer").prop("checked", false);
        $("input[name='premiseTypeLayer[]").prop("checked", false);
        $("#selectAllpremiseAttributeLayer").prop("checked", false);
        $("input[name='premiseAttribute[]").prop("checked", false);
        $("#selectAllPremiseCircuitLayer").prop("checked", false);
        $("#selectAllPCircuitStatusLayer").prop("checked", false);
        $("input[name='pCircuitStatusLayer[]").prop("checked", false);
        $("#selectAllPCircuitCTLayer").prop("checked", false);
        $("input[name='pCircuitcTypeLayer[]").prop("checked", false);
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);

        return false;
    }else{
        return true;
    }
}
// search Premise Name 
(function($) {
    var cluster = new Bloodhound({
        datumTokenizer: function(d) {
            return d.tokens;
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: site_url + 'vmap/index?mode=search_site',
            replace: function(url, uriEncodedQuery) {
                var newUrl = url + '&vName=' + uriEncodedQuery;
                return newUrl;
            },
            filter: function(list) {
                if (list == null)
                    return {};
                else
                    return $.map(list, function(rawdata) {
                        return {
                            display: rawdata.display,
                            iPremiseId: rawdata.iPremiseId
                        };
                    });
            }
        }
    });

    cluster.initialize();

    select = false;
    $('#vName').typeahead({
        hint: false,
        highlight: true,
        minLength: 1
    }, {

        displayKey: 'display',
        source: cluster.ttAdapter(),
    })
    .on('typeahead:selected', onSiteClusteSelected)
    .off('blur')
    .blur(function() {
        $(".tt-dropdown-menu").hide();
    });

    /********************Search sr******************************/
    var clusterFiberInquiry1 = new Bloodhound({
      datumTokenizer: function(d) { return d.tokens; },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
        url: site_url+'vmap/index?mode=search_fiber_inquiry',
        replace: function(url, uriEncodedQuery) {
            var newUrl = url + '&serach_vFiberInquiry=' + uriEncodedQuery;
                return newUrl;
            },
        filter: function(list) {
            if(list==null)
                return {};
            else
                return $.map(list, function(rawdata) { 
                    return { display: rawdata.display, iFiberInquiryId:rawdata.iFiberInquiryId }; 
                });
        } 
      }      
    });
    
    clusterFiberInquiry1.initialize();
    
    select = false;
    $('#serach_fiber_inquiry').typeahead({hint: false, highlight: true,minLength: 2 }, 
    {
        displayKey: 'display',
        source: clusterFiberInquiry1.ttAdapter(),
    })
    .on('typeahead:selected', onFiberInquiryClusterSelected1)
    .off('blur')
    .blur(function() {
        $(".tt-dropdown-menu").hide();
    });
})(jQuery);

function onSiteClusteSelected(e, datum) {
    $("#serach_iPremiseId").val(datum['iPremiseId']);
    $("#vName").val(datum['display']);
    $("#clear_site_address_id").show();
}


function onFiberInquiryClusterSelected1(e, datum) {
    $("#serach_fiber_inquiry_id").val(datum['iFiberInquiryId']);
    $("#serach_fiber_inquiry").val(datum['display']);
    $(".clear_fiberInquiry").show();
}

$("#search_site_map").click(function() {
    var empty = 0;
    var s = 0;
    var one_filled;
    var siteData = [];
    var fInquiryData = [];
    var serviceOrderData = [];
    var workOrderData = [];
    var troubleTicketData = [];
    var maintenanceTicketData = [];
    var awarenessTaskData = [];      
    var equipmentData = []; 
    var premiseCircuitData = []; 
    var action;

    if (sitesearchMarker.length > 0) {
        for (i = 0; i < sitesearchMarker.length; i++) {
            sitesearchMarker[i].setMap(null);
        }
        sitesearchMarker.length = 0;
    }

    sitesearchMarker = [];
    sitesearchMarker.length = 0;

    var bounds = new google.maps.LatLngBounds();

    //markerClusterSiteSerach.clearMarkers();
    $('#form input[type=text]').each(function() {
        if (this.value == "") {
            empty++;
        }
    })
    var total_text = $('#form').find('input[type=text]').length;
    one_filled = total_text - empty;
    if (one_filled != 1) {
        alert("Only one textbox used at a time");
    } else {
        var data = "";

        var serach_iPremiseId = $("#serach_iPremiseId").val();
        if (serach_iPremiseId != '') {
            siteData.push(serach_iPremiseId);
        }

        var iFiberInquiryId = $("#serach_fiber_inquiry_id").val();
        if (iFiberInquiryId != '') {
            fInquiryData.push(iFiberInquiryId);
        }

        var iServiceOrderId = $("#search_iServiceOrderId").val();
        if (iServiceOrderId != '') {
            serviceOrderData.push(iServiceOrderId);
        }

        var iWorkOrderId = $("#search_iWorkOrderId").val();
        if (iWorkOrderId != '') {
            workOrderData.push(iWorkOrderId);
        }

        var iTroubleTicketId = $("#search_iTroubleTicketId").val();
        if (iTroubleTicketId != '') {
            troubleTicketData.push(iTroubleTicketId);
        }

        var iMaintenanceTicketId = $("#search_iMaintenanceTicketId").val();
        if (iMaintenanceTicketId != '') {
            maintenanceTicketData.push(iMaintenanceTicketId);
        }

        var iAwarenessTaskId = $("#search_iAwarenessId").val();
        if (iAwarenessTaskId != '') {
            awarenessTaskData.push(iAwarenessTaskId);
        }

        var iEquipmentId = $("#search_iEquipmentId").val();
        if (iEquipmentId != '') {
            equipmentData.push(iEquipmentId);
        }

        var premiseCircuitId = $("#search_iPremiseCircuitId").val();
        if (premiseCircuitId != '') {
            premiseCircuitData.push(premiseCircuitId);
        }

        var vLatitude = $("#vLatitude").val();
        var vLongitude = $("#vLongitude").val();
        var address_premiseid;
        if (vLatitude != '' && vLongitude != '') {
            $.ajax({
                async: false,
                type: "POST",
                url: site_url + "vmap/index",
                data: 'mode=serach_iPremiseId&vLatitude=' + vLatitude + '&vLongitude=' + vLongitude,
                success: function(responsedata) {
                    if(responsedata != ""){
                        address_premiseid = responsedata;
                        siteData.push(address_premiseid);
                    }
                }
            });
        }
        if (siteData && siteData.length > 0) {
            action = "getSerachSiteData";
        } else if (fInquiryData && fInquiryData.length > 0) {
            action = "getSerachFiberInquiryData";
        }else if (serviceOrderData && serviceOrderData.length > 0) {
            action = "getSerachServiceOrderData";
        }else if (workOrderData && workOrderData.length > 0) {
            action = "getSerachWorkOrderData";
        }else if (troubleTicketData && troubleTicketData.length > 0) {
            action = "getSerachTroubleTicketData";
        }else if (maintenanceTicketData && maintenanceTicketData.length > 0) {
            action = "getSerachMaintenanceTicketData";
        }else if (awarenessTaskData && awarenessTaskData.length > 0) {
            action = "getSerachAwarenessTaskData";
        }else if (equipmentData && equipmentData.length > 0) {
            action = "getSerachEquipmentData";
        }else if (premiseCircuitData && premiseCircuitData.length > 0) {
            action = "getSerachPremiseCircuitData";
        }

        clearFilterData();
        clearLayerData();
        clearMap();
        setTimeout(function () {
            if((jQuery.isEmptyObject(fInquiryData) == false && fInquiryData.length > 0) || (jQuery.isEmptyObject(siteData) == false && siteData.length > 0) || (jQuery.isEmptyObject(serviceOrderData) == false && serviceOrderData.length > 0) || (jQuery.isEmptyObject(workOrderData) == false && workOrderData.length > 0) || (jQuery.isEmptyObject(troubleTicketData) == false && troubleTicketData.length > 0) || (jQuery.isEmptyObject(maintenanceTicketData) == false && maintenanceTicketData.length > 0) || (jQuery.isEmptyObject(awarenessTaskData) == false && awarenessTaskData.length > 0) || (jQuery.isEmptyObject(equipmentData) == false && equipmentData.length > 0) || (jQuery.isEmptyObject(premiseCircuitData) == false && premiseCircuitData.length > 0)){
                $.ajax({
                    type: "POST",
                    url: 'vmap/api/',
                    data: {
                        action: action,
                        premiseId: siteData.join(),
                        fiberInquiryId: fInquiryData.join(),
                        serviceOrderId: serviceOrderData.join(),
                        workOrderId: workOrderData.join(),
                        troubleTicketId: troubleTicketData.join(),
                        maintenanceTicketId: maintenanceTicketData.join(),
                        iAwarenessTaskId: awarenessTaskData.join(),
                        iEquipmentId: equipmentData.join(),
                        premiseCircuitId: premiseCircuitData.join(),
                    },
                    cache: true,
                    beforeSend: function() {
                       $(".loading").show();
                    },
                    success: function(data) {
                        if (data) {
                            console.log('data found');
                            var siteData = JSON.parse(data);
                            console.log(siteData);
                            if(Object.keys(siteData).length > 0){
                                $.each(siteData, function(premiseid, item) {
                                    if (action == 'getSerachFiberInquiryData') {
                                        var id = premiseid;
                                        //console.log('data found-1');
                                        if (siteData[id].point !== undefined) {
                                            for (i = 0; i < siteData[id].point.length; i++) {
                                                
                                                var pointMatrix = {
                                                    lat: siteData[id].point[i]['lat']+ mathRandLat,
                                                    lng: siteData[id].point[i]['lng']+ mathRandLng
                                                };
                                                
                                                var vName = siteData[id].vName;
                                                var vAddress = siteData[id].vAddress;
                                                var premiseid1 = siteData[id].premiseid;
                                                var vPremiseName = siteData[id].vPremiseName;
                                                var vPremiseSubType = siteData[id].vPremiseSubType;
                                                var vEngagement = siteData[id].vEngagement;
                                                var vZoneName = siteData[id].vZoneName;
                                                var vNetwork = siteData[id].vNetwork;
                                                var vStatus = siteData[id].vStatus;
                                                var fiberInquiryId = siteData[id].fiberInquiryId;

                                                sitesearchMarker[s] = new google.maps.Marker({
                                                    map: map,
                                                    position: pointMatrix,
                                                    icon: siteData[id].icon,
                                                });
                                                
                                                newLocation(pointMatrix.lat,pointMatrix.lng);
                                                $sr_map = sitesearchMarker[s];

                                                fiberInquiryinfo_popup($sr_map, id, vName, vAddress, premiseid1, vPremiseName, vPremiseSubType, vEngagement, vZoneName, vNetwork, vStatus, fiberInquiryId);
                                                sitesearchMarker[s].setMap(map);
                                                if (markerSpiderfier) {
                                                    markerSpiderfier.addMarker(sitesearchMarker[s]);
                                                }
                                                //Extend each marker's position in LatLngBounds object.
                                                bounds.extend(sitesearchMarker[s].position);
                                                s++;
                                            }
                                        }
                                    }else if (action == 'getSerachServiceOrderData') {
                                        var id = premiseid;
                                        //console.log('data found-1');
                                        if (siteData[id].point !== undefined) {
                                            for (i = 0; i < siteData[id].point.length; i++) {
                                                var pointMatrix = {
                                                    lat: siteData[id].point[i]['lat']+ mathRandLat,
                                                    lng: siteData[id].point[i]['lng']+ mathRandLng
                                                };

                                                var vMasterMSA = siteData[id]['vMasterMSA'];
                                                var vServiceOrder = siteData[id]['vServiceOrder'];
                                                var vSalesRepName = siteData[id]['vSalesRepName'];
                                                var vSalesRepEmail = siteData[id]['vSalesRepEmail'];
                                                var premiseid = siteData[id]['premiseid'];
                                                var vPremiseName = siteData[id]['vPremiseName'];
                                                var vAddress = siteData[id]['vAddress'];
                                                var cityid = siteData[id]['cityid'];
                                                var stateid = siteData[id]['stateid'];
                                                var countyid = siteData[id]['countyid'];
                                                var zipcode = siteData[id]['zipcode'];
                                                var zoneid = siteData[id]['zoneid'];
                                                var vZoneName = siteData[id]['vZoneName'];
                                                var networkid = siteData[id]['networkid'];
                                                var vNetwork = siteData[id]['vNetwork'];
                                                var vPremiseType = siteData[id]['vPremiseType'];
                                                var vCompanyName = siteData[id]['vCompanyName'];
                                                var vConnectionTypeName = siteData[id]['vConnectionTypeName'];
                                                var vServiceType1 = siteData[id]['vServiceType1'];
                                                var vStatus = siteData[id]['vStatus'];

                                                sitesearchMarker[s] = new google.maps.Marker({
                                                    map: map,
                                                    position: pointMatrix,
                                                    icon: siteData[id].icon,
                                                });
                                                
                                                newLocation(pointMatrix.lat,pointMatrix.lng);
                                                $sO_map = sitesearchMarker[s];

                                                serviceOrderinfo_popup($sO_map, id, vMasterMSA, vServiceOrder, vSalesRepName, vSalesRepEmail, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vCompanyName, vConnectionTypeName, vServiceType1, vStatus)

                                                sitesearchMarker[s].setMap(map);
                                                if (markerSpiderfier) {
                                                    markerSpiderfier.addMarker(sitesearchMarker[s]);
                                                }
                                                
                                                //Extend each marker's position in LatLngBounds object.
                                                bounds.extend(sitesearchMarker[s].position);
                                                s++;
                                            }
                                        }
                                    }else if (action == 'getSerachWorkOrderData') {
                                        var id = premiseid;
                                        //console.log('data found-1');
                                        if (siteData[id].point !== undefined) {
                                            for (i = 0; i < siteData[id].point.length; i++) {

                                                var pointMatrix = {
                                                    lat: siteData[id].point[i]['lat']+ mathRandLat,
                                                    lng: siteData[id].point[i]['lng']+ mathRandLng
                                                };

                                                var premiseid = siteData[id]['premiseid'];
                                                var vPremiseName = siteData[id]['vPremiseName'];
                                                var vAddress = siteData[id]['vAddress'];
                                                var cityid = siteData[id]['cityid'];
                                                var stateid = siteData[id]['stateid'];
                                                var countyid = siteData[id]['countyid'];
                                                var zipcode = siteData[id]['zipcode'];
                                                var zoneid = siteData[id]['zoneid'];
                                                var vZoneName = siteData[id]['vZoneName'];
                                                var networkid = siteData[id]['networkid'];
                                                var vNetwork = siteData[id]['vNetwork'];
                                                var vPremiseType = siteData[id]['vPremiseType'];
                                                var vServiceOrder = siteData[id]['vServiceOrder'];
                                                var vWOProject = siteData[id]['vWOProject'];
                                                var vType = siteData[id]['vType'];
                                                var vRequestor = siteData[id]['vRequestor'];
                                                var vAssignedTo = siteData[id]['vAssignedTo'];
                                                var vStatus = siteData[id]['vStatus'];

                                                sitesearchMarker[s] = new google.maps.Marker({
                                                    map: map,
                                                    position: pointMatrix,
                                                    icon: siteData[id].icon,
                                                });
                                                
                                                newLocation(pointMatrix.lat,pointMatrix.lng);
                                                $WO_map = sitesearchMarker[s];

                                                workOrderinfo_popup($WO_map, id, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vServiceOrder, vWOProject, vType, vRequestor, vAssignedTo, vStatus)

                                                sitesearchMarker[s].setMap(map);
                                                if (markerSpiderfier) {
                                                    markerSpiderfier.addMarker(sitesearchMarker[s]);
                                                }
                                                
                                                //Extend each marker's position in LatLngBounds object.
                                                bounds.extend(sitesearchMarker[s].position);
                                                s++;
                                            }
                                        }
                                    }else if (action == 'getSerachTroubleTicketData') {
                                        //console.log('data found-1');
                                        if (siteData[premiseid].point !== undefined) {
                                            for (i = 0; i < siteData[premiseid].point.length; i++) {

                                                var pointMatrix = {
                                                    lat: siteData[premiseid].point[i]['lat']+ mathRandLat,
                                                    lng: siteData[premiseid].point[i]['lng']+ mathRandLng
                                                };

                                                var id = siteData[premiseid]['iTroubleTicketId'];
                                                var iSeverity = siteData[premiseid]['iSeverity'];
                                                var iStatus = siteData[premiseid]['iStatus'];
                                                var vServiceOrder = siteData[premiseid]['vServiceOrder'];
                                                var iTroubleTicketId = siteData[premiseid]['iTroubleTicketId'];
                                                var vPremiseName = siteData[premiseid]['vPremiseName'];
                                                var vPremiseType = siteData[premiseid]['vPremiseType'];
                                                var dTroubleStartDate = siteData[premiseid]['dTroubleStartDate'];
                                                var vAddress = siteData[premiseid]['vAddress'];

                                                var lat_long = new google.maps.LatLng(siteData[premiseid], siteData[premiseid]['vLongitude']);
                                                sitesearchMarker[s] = new google.maps.Marker({
                                                    map: map,
                                                    position: pointMatrix,
                                                    icon: siteData[premiseid].icon,
                                                });
                                                
                                                newLocation(pointMatrix.lat,pointMatrix.lng);

                                                var vPremiseData =  premiseid+" ("+vPremiseName+"; "+vPremiseType+")";
                                                var content = '';
                                                content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
                                                content += "<h5 class='border-bottom pb-2 mb-3'>Trouble Ticket #" + id +"</h5>";
                                                content += "<div class='d-flex'><h6>" + iSeverity + "</h6></div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Premise :</span>&nbsp;" + vPremiseData + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Address :</span>&nbsp;" + vAddress + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Service Order :</span>&nbsp;" + vServiceOrder + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Trouble Start Date :</span>&nbsp;" + dTroubleStartDate + "</span></div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Status :</span>&nbsp;" + iStatus + "</div>";
                                                content += "<div class='button mt-3'><a class='btn btn-primary  mr-2 text-white' href='" + site_url + "trouble_ticket/trouble_ticket_edit&mode=Update&iTroubleTicketId=" + id + "' target='_blank'>Edit Trouble Ticket</a></div>";
                                                content += "</div>";

                                                CreatePopup(content, sitesearchMarker[s], id, lat_long);
                                                sitesearchMarker[s].setMap(map);
                                                
                                                if (markerSpiderfier) {
                                                    markerSpiderfier.addMarker(sitesearchMarker[s]);
                                                }
                                                //Extend each marker's position in LatLngBounds object.
                                                bounds.extend(sitesearchMarker[s].position);
                                                s++;
                                            }
                                        }
                                    }else if (action == 'getSerachMaintenanceTicketData') {
                                        //console.log('data found-1');
                                        if (siteData[premiseid].point !== undefined) {
                                            for (i = 0; i < siteData[premiseid].point.length; i++) {

                                                var pointMatrix = {
                                                    lat: siteData[premiseid].point[i]['lat']+ mathRandLat,
                                                    lng: siteData[premiseid].point[i]['lng']+ mathRandLng
                                                };

                                                var id = siteData[premiseid]['iMaintenanceTicketId'];
                                                var iSeverity = siteData[premiseid]['iSeverity'];
                                                var iStatus = siteData[premiseid]['iStatus'];
                                                var vServiceOrder = siteData[premiseid]['vServiceOrder'];
                                                var iMaintenanceTicketId = siteData[premiseid]['iMaintenanceTicketId'];
                                                var vPremiseName = siteData[premiseid]['vPremiseName'];
                                                var vPremiseType = siteData[premiseid]['vPremiseType'];
                                                var dMaintenanceStartDate = siteData[premiseid]['dMaintenanceStartDate'];
                                                var vAddress = siteData[premiseid]['vAddress'];

                                                var lat_long = new google.maps.LatLng(siteData[premiseid], siteData[premiseid]['vLongitude']);
                                                sitesearchMarker[s] = new google.maps.Marker({
                                                    map: map,
                                                    position: pointMatrix,
                                                    icon: siteData[premiseid].icon,
                                                });
                                                
                                                newLocation(pointMatrix.lat,pointMatrix.lng);

                                                var vPremiseData =  premiseid+" ("+vPremiseName+"; "+vPremiseType+")";
                                                var content = '';
                                                content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
                                                content += "<h5 class='border-bottom pb-2 mb-3'>Maintenance Ticket #" + id +"</h5>";
                                                content += "<div class='d-flex'><h6>" + iSeverity + "</h6></div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Premise :</span>&nbsp;" + vPremiseData + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Address :</span>&nbsp;" + vAddress + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Service Order :</span>&nbsp;" + vServiceOrder + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Maintenance Start Date :</span>&nbsp;" + dMaintenanceStartDate + "</span></div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Status :</span>&nbsp;" + iStatus + "</div>";
                                                content += "<div class='button mt-3'><a class='btn btn-primary  mr-2 text-white' href='" + site_url + "maintenance_ticket/maintenance_ticket_edit&mode=Update&iMaintenanceTicketId=" + id + "' target='_blank'>Edit Maintenance Ticket</a></div>";
                                                content += "</div>";

                                                CreatePopup(content, sitesearchMarker[s], id, lat_long);
                                                sitesearchMarker[s].setMap(map);
                                                
                                                if (markerSpiderfier) {
                                                    markerSpiderfier.addMarker(sitesearchMarker[s]);
                                                }
                                                //Extend each marker's position in LatLngBounds object.
                                                bounds.extend(sitesearchMarker[s].position);
                                                s++;
                                            }
                                        }
                                    }else if (action == 'getSerachAwarenessTaskData') {
                                        var id = premiseid;
                                        //console.log('data found-1');
                                        siteInfoWindowTaskAwarenessArr = [];
                                        if (siteData[id].point !== undefined) {
                                            for (i = 0; i < siteData[id].point.length; i++) {

                                                var pointMatrix = {
                                                    lat: siteData[id].point[i]['lat']+ mathRandLat,
                                                    lng: siteData[id].point[i]['lng']+ mathRandLng
                                                };

                                                var iPremiseId = siteData[id]['iPremiseId'];
                                                var vPremiseName = siteData[id]['vPremiseName'];
                                                var vAddress = siteData[id]['vAddress'];
                                                var cityid = siteData[id]['cityid'];
                                                var stateid = siteData[id]['stateid'];
                                                var countyid = siteData[id]['countyid'];
                                                var zipcode = siteData[id]['zipcode'];
                                                var zoneid = siteData[id]['zoneid'];
                                                var vZoneName = siteData[id]['vZoneName'];
                                                var networkid = siteData[id]['networkid'];
                                                var vNetwork = siteData[id]['vNetwork'];
                                                var vPremiseType = siteData[id]['vPremiseType'];
                                                var vFiberInquiry = (siteData[id]['vFiberInquiry'] !='')?siteData[id]['vFiberInquiry']:"";
                                                var vEngagement = siteData[id]['vEngagement'];
                                                var tNotes = (siteData[id]['tNotes'] !='')?siteData[id]['tNotes']:"";
                                                var dDate = siteData[id]['dDate'];
                                                var dStartTime = siteData[id]['dStartTime'];
                                                var dEndTime = siteData[id]['dEndTime'];
                                                var vTechnicianName = siteData[id]['vTechnicianName'];

                                                var lat_long = new google.maps.LatLng(siteData[id], siteData[id]['vLongitude']);

                                                sitesearchMarker[s] = new google.maps.Marker({
                                                    map: map,
                                                    position: pointMatrix,
                                                    icon: siteData[id].icon,
                                                });
                                                
                                                newLocation(pointMatrix.lat,pointMatrix.lng);

                                                var vPremiseData =  iPremiseId+" ("+vPremiseName+"; "+vPremiseType+")";

                                                
                                                siteInfoWindowTaskAwarenessArr.push(siteData[id]['hidden_arr']);
                                                var content = '';
                                                content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
                                                content += "<h5 class='border-bottom pb-2 mb-3'>Awareness Task #" + id +"</h5>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Premise :</span>&nbsp;" + vPremiseData + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Address :</span>&nbsp;" + vAddress + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Fiber Inquiry :</span>&nbsp;" + vFiberInquiry + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Engagement :</span>&nbsp;" + vEngagement + "</span></div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Date :</span>&nbsp;" + dDate + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Start Time :</span>&nbsp;" + dStartTime + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>End Time :</span>&nbsp;" + dEndTime + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Technician :</span>&nbsp;" + vTechnicianName + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Notes :</span>&nbsp;" + tNotes + "</div>";
                                                content += "<a class='btn btn-primary  mr-2 text-white' title='Edit Awareness' onclick=addEditDataAwareness('"+id+"','edit','0')>Edit Awareness</a>";
                                                content += "</div>";

                                                CreatePopup(content, sitesearchMarker[s], id, lat_long);
                                                
                                                sitesearchMarker[s].setMap(map);
                                                if (markerSpiderfier) {
                                                    markerSpiderfier.addMarker(sitesearchMarker[s]);
                                                }
                                                
                                                //Extend each marker's position in LatLngBounds object.
                                                bounds.extend(sitesearchMarker[s].position);
                                                s++;
                                            }
                                        }
                                    }else if (action == 'getSerachEquipmentData') {
                                        var id = premiseid;
                                        if (siteData[id].point !== undefined) {
                                            for (i = 0; i < siteData[id].point.length; i++) {

                                                var pointMatrix = {
                                                    lat: siteData[id].point[i]['lat']+ mathRandLat,
                                                    lng: siteData[id].point[i]['lng']+ mathRandLng
                                                };

                                                var iPremiseId = siteData[id]['iPremiseId'];
                                                var vPremiseName = siteData[id]['vPremiseName'];
                                                var vAddress = siteData[id]['vAddress'];
                                                var cityid = siteData[id]['cityid'];
                                                var stateid = siteData[id]['stateid'];
                                                var countyid = siteData[id]['countyid'];
                                                var zipcode = siteData[id]['zipcode'];
                                                var zoneid = siteData[id]['zoneid'];
                                                var vZoneName = siteData[id]['vZoneName'];
                                                var networkid = siteData[id]['networkid'];
                                                var vNetwork = siteData[id]['vNetwork'];
                                                var vPremiseType = siteData[id]['vPremiseType'];
                                                var vModelName = siteData[id]['vModelName'];
                                                var vSerialNumber = siteData[id]['vSerialNumber'];
                                                var vMACAddress = siteData[id]['vMACAddress'];
                                                var vSize = siteData[id]['vSize'];
                                                var vWeight = siteData[id]['vWeight'];
                                                var vMaterial = siteData[id]['vMaterial'];
                                                var vPower = siteData[id]['vPower'];
                                                var dInstallByDate = siteData[id]['dInstallByDate'];
                                                var dInstalledDate = siteData[id]['dInstalledDate'];
                                                var vPurchaseCost = siteData[id]['vPurchaseCost'];
                                                var dPurchaseDate = siteData[id]['dPurchaseDate'];
                                                var dWarrantyExpiration = siteData[id]['dWarrantyExpiration'];
                                                var vWarrantyCost = siteData[id]['vWarrantyCost'];
                                                var vInstallType = siteData[id]['vInstallType'];
                                                var vLinkType = siteData[id]['vLinkType'];
                                                var dProvisionDate = siteData[id]['dProvisionDate'];
                                                var vPremiseCircuitData = siteData[id]['vPremiseCircuitData'];
                                                var vOperationalStatus = siteData[id]['vOperationalStatus'];
                                               

                                                var lat_long = new google.maps.LatLng(siteData[id], siteData[id]['vLongitude']);

                                                sitesearchMarker[s] = new google.maps.Marker({
                                                    map: map,
                                                    position: pointMatrix,
                                                    icon: siteData[id].icon,
                                                });
                                                
                                                newLocation(pointMatrix.lat,pointMatrix.lng);

                                                var vPremiseData =  iPremiseId+" ("+vPremiseName+"; "+vPremiseType+")";

                                                var content = '';
                                                content += "<div CELLPADDING=5 CELLSPACING=5 class=info_box id=info_box>";
                                                content += "<h5 class='border-bottom pb-2 mb-3'>Equipment #" + id +"</h5>";
                                                content += "<div class='d-flex'><h6>" + vModelName + "</h6></div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Serial Number :</span>&nbsp;" + vSerialNumber + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>MAC Address :</span>&nbsp;" + vMACAddress + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Size :</span>&nbsp;" + vSize + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Weight :</span>&nbsp;" + vWeight + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Material :</span>&nbsp;" + vMaterial + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Power :</span>&nbsp;" + vPower + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Install By Date :</span>&nbsp;" + dInstallByDate + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Install Date :</span>&nbsp;" + dInstalledDate + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Purchase Cost :</span>&nbsp;" + vPurchaseCost + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Purchase Date :</span>&nbsp;" + dPurchaseDate + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Warranty Expiration :</span>&nbsp;" + dWarrantyExpiration + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Warranty Cost :</span>&nbsp;" + vWarrantyCost + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Install Type :</span>&nbsp;" + vInstallType + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Link Type :</span>&nbsp;" + vLinkType + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Premise :</span>&nbsp;" + vPremiseData + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Address :</span>&nbsp;" + vAddress + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Provision Date:</span>&nbsp;" + dProvisionDate + "</div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Premise Circuit:</span>&nbsp;" + vPremiseCircuitData + "</span></div>";
                                                content += "<div class='d-flex'><span class='font-weight-bold'>Operational Status :</span>&nbsp;" + vOperationalStatus + "</div>";
                                                content += "<a class='btn btn-primary  mr-2 text-white' title='Edit Equipment' href='" + site_url + "service_order/equipment_add&mode=Update&iEquipmentId=" + id + "' target='_blank'>Edit Equipment</a>";
                                                content += "</div>";

                                                CreatePopup(content, sitesearchMarker[s], id, lat_long);
                                                
                                                sitesearchMarker[s].setMap(map);
                                                if (markerSpiderfier) {
                                                    markerSpiderfier.addMarker(sitesearchMarker[s]);
                                                }
                                                
                                                //Extend each marker's position in LatLngBounds object.
                                                bounds.extend(sitesearchMarker[s].position);
                                                s++;
                                            }
                                        }
                                    }else if (action == 'getSerachPremiseCircuitData') {
                                        var id = premiseid;
                                        //console.log('data found-1');
                                        if (siteData[id].point !== undefined) {
                                            for (i = 0; i < siteData[id].point.length; i++) {

                                                var pointMatrix = {
                                                    lat: siteData[id].point[i]['lat']+ mathRandLat,
                                                    lng: siteData[id].point[i]['lng']+ mathRandLng
                                                };

                                                var premiseid = siteData[id]['premiseid'];
                                                var vPremiseName = siteData[id]['vPremiseName'];
                                                var vAddress = siteData[id]['vAddress'];
                                                var cityid = siteData[id]['cityid'];
                                                var stateid = siteData[id]['stateid'];
                                                var countyid = siteData[id]['countyid'];
                                                var zipcode = siteData[id]['zipcode'];
                                                var zoneid = siteData[id]['zoneid'];
                                                var vZoneName = siteData[id]['vZoneName'];
                                                var networkid = siteData[id]['networkid'];
                                                var vNetwork = siteData[id]['vNetwork'];
                                                var vPremiseType = siteData[id]['vPremiseType'];
                                                var vWorkOrder = siteData[id]['vWorkOrder'];
                                                var circuitid = siteData[id]['circuitid'];
                                                var vCircuitName = siteData[id]['vCircuitName'];
                                                var connectiontypeid = siteData[id]['connectiontypeid'];
                                                var vConnectionTypeName = siteData[id]['vConnectionTypeName'];
                                                var vStatus = siteData[id]['vStatus'];

                                                sitesearchMarker[s] = new google.maps.Marker({
                                                    map: map,
                                                    position: pointMatrix,
                                                    icon: siteData[id].icon,
                                                });
                                                
                                                newLocation(pointMatrix.lat,pointMatrix.lng);
                                                $pc_map = sitesearchMarker[s];

                                                premiseCircuitinfo_popup($pc_map, id, premiseid, vPremiseName, vAddress, cityid, stateid, countyid, countyid, zipcode, zoneid, vZoneName, networkid, vNetwork, vPremiseType, vWorkOrder, circuitid, vCircuitName, connectiontypeid, vConnectionTypeName, vStatus);

                                                sitesearchMarker[s].setMap(map);
                                                if (markerSpiderfier) {
                                                    markerSpiderfier.addMarker(sitesearchMarker[s]);
                                                }
                                                
                                                //Extend each marker's position in LatLngBounds object.
                                                bounds.extend(sitesearchMarker[s].position);
                                                s++;
                                            }
                                        }
                                    }else{
                                        //console.log('premiseid + ' + premiseid);
                                        if (siteData[premiseid].point !== undefined) {
                                            for (i = 0; i < siteData[premiseid].point.length; i++) {
                                                
                                                var pointMatrix = {
                                                    lat: siteData[premiseid].point[i]['lat'] + mathRandLat,
                                                    lng: siteData[premiseid].point[i]['lng'] + mathRandLng
                                                };
                                            
                                                sitesearchMarker[s] = new google.maps.Marker({
                                                    map: map,
                                                    position: pointMatrix,
                                                    icon: siteData[premiseid].icon,
                                                });
                                                
                                                newLocation(pointMatrix.lat, pointMatrix.lng);
                                                $site_map = sitesearchMarker[s];

                                                info_popup($site_map, premiseid);
                                                sitesearchMarker[s].setMap(map);

                                                //Extend each marker's position in LatLngBounds object.
                                                bounds.extend(sitesearchMarker[s].position);

                                                s++;

                                                var vName = siteData[premiseid].vName;
                                                var vAddress = siteData[premiseid].vAddress;
                                                var vRequestType = siteData[premiseid].vRequestType;
                                                var vAssignTo = siteData[premiseid].vAssignTo;
                                                var vStatus = siteData[premiseid].vStatus;
                                            }
                                        }
                                    }
                                });
                                
                                if(bounds){
                                    /*alert(bounds);
                                    console.log(bounds);*/
                                    //Center map and adjust Zoom based on the position of all markers.
                                    map.setCenter(bounds.getCenter());
                                    map.fitBounds(bounds);
                                    
                                }
                            }
                        } else {
                            console.log('no data found');
                            //clearMap();
                        }

                        $(".loading").hide();
                    }
                });
            }
        },100);
    }
});

function clear_address() {
    $('#vLatitude').val('');
    $('#vLongitude').val('');
    $('#autofilladdress').val('');
    $('#autofilladdress').focus();
    $(".address-details").hide();
    $("#clear_address_id").hide();
}

function clear_site_address() {
    //console.log("clear_site_address");
    $('#vName').val('');
    $('#serach_iPremiseId').val('');
    $("#clear_site_address_id").hide();
}

function clear_fiberInquiry() {
    $('#serach_fiber_inquiry').val('');
    $('#serach_fiber_inquiry_id').val('');
    $(".clear_fiberInquiry").hide();
}

function resetButton() {
    $("#serach_iPremiseId").val('');
    $("#vLatitude").val('');
    $("#vLongitude").val('');
    $("#vName").val('');
    $("#autofilladdress").val('');
    $("#clear_site_address_id").trigger('click');
    $("#clear_address_id").hide();

    $("#serach_fiber_inquiry").val('');
    $("#serach_fiber_inquiry_id").val('');
    $(".clear_fiberInquiry").trigger('click');

    $("#search_iServiceOrderId").val('');
    $("#search_iWorkOrderId").val('');
    $("#search_iTroubleTicketId").val('');
    $("#search_iMaintenanceTicketId").val('');
    $("#search_iAwarenessId").val('');
    $("#search_iEquipmentId").val('');
    $("#search_iPremiseCircuitId").val('');

    //sitesearchMarker.setMap(null);
    if (sitesearchMarker.length > 0) {
        for (i = 0; i < sitesearchMarker.length; i++) {
            sitesearchMarker[i].setMap(null);
        }

        sitesearchMarker.length = 0;
    }

    sitesearchMarker = [];
    sitesearchMarker.length = 0;
}
// end

//clear map tool polyshape
function clearMapTool(){
    $("#distanceinmiles").val('');
    $("#distanceinft").val('');
    
    $("#areainmiles").val('');
    $("#areainft").val('');

    $("#rCircle").val('');
    $("#areaCircle").val('');
 
    if(typeof poly === 'object'  ){
        poly.setMap(null);
    }

    if (polylineMarker.length > 0) {
        for (let i = 0; i < polylineMarker.length; i++) {
            polylineMarker[i].setMap(null);
        }
    }

    if (polygonMarker.length > 0) {
        for (let i = 0; i < polygonMarker.length; i++) {
            polygonMarker[i].setMap(null);
        }
    }

   if (typeof cityCircle === 'object'  ) {
        
        cityCircle.setMap(null);
    }

    if (circleMarker !== undefined) {
        for (var i = 0; i < circleMarker.length; i++) {
            circleMarker[i].setMap(null);
        }
    }


    if (typeof cityCircle == "object") {
        console.log('clear Circle');
        cityCircle.setMap(null);
    }

    if (circleMarker.length > 0) {
        for (var i = 0; i < circleMarker.length; i++) {
            circleMarker[i].setMap(null);
        }
    }
    
    if(polylineCount > 0 && zCount > 0){
        for (k = 0 ;k <zCount; k++ ){
            google.maps.event.removeListener(zoneLatLngPolyline[k], 'click');
        }
    }
    
    if(polygonCount >0 && zCount > 0){
        for (k = 0 ;k <zCount; k++ ){
            google.maps.event.removeListener(zoneLatLngPoly[k], 'click');
        }
    }
  
    if(cmCount >0 && zCount > 0){
        for (k = 0 ;k <zCount; k++ ){
            google.maps.event.removeListener(zoneLatLngCircle[k], 'click');
        }
    }
    polylineCount =0;
    polygonCount =0;
    cmCount = 0;
    
    google.maps.event.removeListener(listenerLatLngCircle);
    google.maps.event.removeListener(listenerLatLngPoly);
    google.maps.event.removeListener(listenerLatLngPolyline);

    //Add site point remove
    cancleAddSite();
}

/***Add Site********/
$("#btn_map_addsite").click(function(){
    //remove object of drwa shapes 
    if ($("#showDistance").prop("checked")) {
            $("#showDistance").prop("checked", false);
    }
    if ($("#showArea").prop("checked")) {
        $("#showArea").prop("checked", false);
    }
    if ($("#showCircle").prop("checked")) {
        $("#showCircle").prop("checked", false);
    }
    clearMapTool();
    cancleAddBatchSite();
    $('.collapse').collapse('hide')
    //add site point
    $("#sitedivmsg").html('<p>Click the spot on the map where you want this site placed.</p>');
    $("#sitedivmsg").removeClass('d-none');
    $(this).removeClass('d-flex').addClass('d-none');
    //set access of zone area
    if(zCount > 0){
        for (k = 0 ;k <zCount; k++ ){
           mapAddZoneSiteListner[k] = google.maps.event.addListener(zonePolygonObj[k], 'click', ( function(args) {
                if(addSiteMarker == null){
                    var sitelatlong = args.latLng;

                    var mrk = new google.maps.Marker({
                        position: sitelatlong,
                        draggable: true
                    });
                    mrk.setMap(map);
                    addSiteMarker =mrk;
                    $("#sitedivmsg").html('<p>When Finished, press the <b>\'Done\'</b> Button below.</p><div class="d-flex"><input type="button" class=" col-sm-6  btn-primary " id="btn_done_addsite" value="Done"><button type="button" class="ml-1  col-sm-6  btn-danger " id="btn_cancle_addsite">Cancel</button></div>');

                     if($.isEmptyObject(mapAddZoneSiteListner) == false){
                        for (g = 0 ;g <zCount; g++ ){
                            google.maps.event.removeListener(mapAddZoneSiteListner[g]);
                        }
                        mapAddZoneSiteListner = [];
                    }
                    
                    $("#btn_done_addsite").click(function(){
                        addSite();  
                    });
                    $("#btn_cancle_addsite").click(function(){
                        cancleAddSite();
                    });
                }
            }));
        }
    }
    map.AddSiteListner = google.maps.event.addListener(map, "click", function (args) {
        if(addSiteMarker == null){
            var sitelatlong = args.latLng;
            var mrk = new google.maps.Marker({
                position: sitelatlong,
                draggable: true
            });
            mrk.setMap(map);
            addSiteMarker =mrk;
            $("#sitedivmsg").html('<p>When Finished, press the <b>\'Done\'</b> Button below.</p><div class="d-flex"><input type="button" class=" col-sm-6  btn-primary " id="btn_done_addsite" value="Done"><button type="button" class="ml-1  col-sm-6  btn-danger " id="btn_cancle_addsite">Cancel</button></div>');
            if(typeof map.AddSiteListner != undefined || map.AddSiteListner != null){
                google.maps.event.removeListener(map.AddSiteListner);
                map.AddSiteListner = null
            }
           $("#btn_done_addsite").click(function(){
                addSite();
            });
          $("#btn_cancle_addsite").click(function(){
            cancleAddSite();
          });
        }
    });
});

function addSite() {
    if(addSiteMarker == null){
        swal("No position found !");
        return false;
    }    
    var position = addSiteMarker.getPosition();
    /*console.log(position.lat());
    console.log(position.lng());*/
    if(addSiteMarker != null){
        addSiteMarker.setMap(null);
        addSiteMarker = null;
    } 
    window.open(site_url+"premise/add&lat="+position.lat()+"&lng="+position.lng(),"_blank");
    cancleAddSite();
}

function cancleAddSite(){
    if(addSiteMarker != null){
        addSiteMarker.setMap(null);
        addSiteMarker = null;
    }  
    $("#sitedivmsg").addClass('d-none');
    $("#sitedivmsg").html('Click the spot on the map where you want this site placed.');
    $("#btn_map_addsite").addClass('d-flex').removeClass('d-none');           
}

/**************** Add Batch-create Premises ****************/
$("#btn_map_addbatchsite").click(function() {
    //remove object of drwa shapes 
    if ($("#showDistance").prop("checked")) {
        $("#showDistance").prop("checked", false);
    }
    if ($("#showArea").prop("checked")) {
        $("#showArea").prop("checked", false);
    }
    if ($("#showCircle").prop("checked")) {
        $("#showCircle").prop("checked", false);
    }
    clearMapTool();
    cancleAddSite();
    $('.collapse').collapse('hide')
    //add site point
    $("#batchsitedivmsg").html('<p>Click the multiple spot on the map where you want to create new premises.</p>');
    $("#batchsitedivmsg").removeClass('d-none');
    $(this).removeClass('d-flex').addClass('d-none');
    //set access of zone area
    if (zCount > 0) {
        for (k = 0; k < zCount; k++) {
            mapAddZoneBatchSiteListner[k] = google.maps.event.addListener(zonePolygonObj[k], 'click', (function(args) {
                var sitelatlong = args.latLng;
                var mrk = new google.maps.Marker({
                    position: sitelatlong,
                    draggable: true
                });
                
                mrk.setMap(map);
                addBatchSiteMarker.push(mrk);
                $("#batchsitedivmsg").html('<p>When Finished, press the <b>\'Done\'</b> Button below.</p><div class="d-flex"><input type="button" class=" col-sm-6  btn-primary " id="btn_done_addbatchsite" value="Done"><button type="button" class="ml-1  col-sm-6  btn-danger " id="btn_cancle_addbatchsite">Cancel</button></div>');

                /*if ($.isEmptyObject(mapAddZoneBatchSiteListner) == false) {
                    for (g = 0; g < zCount; g++) {
                        google.maps.event.removeListener(mapAddZoneBatchSiteListner[g]);
                    }
                    mapAddZoneBatchSiteListner = [];
                }*/

                $("#btn_done_addbatchsite").click(function() {
                    addBatchSite();
                });
                $("#btn_cancle_addbatchsite").click(function() {
                    cancleAddBatchSite();
                });

            }));
        }
    }
    
    map.AddBatchSiteListner =  google.maps.event.addListener(map, 'click', function (event) {
        var marker = new google.maps.Marker({
            position: event.latLng,
            map: map
        });

        addBatchSiteMarker.push(marker);

        $("#batchsitedivmsg").html('<p>When Finished, press the <b>\'Done\'</b> Button below.</p><div class="d-flex"><input type="button" class=" col-sm-6  btn-primary " id="btn_done_addbatchsite" value="Done"><button type="button" class="ml-1  col-sm-6  btn-danger " id="btn_cancle_addbatchsite">Cancel</button></div>');
        $("#btn_done_addbatchsite").click(function() {
            addBatchSite();
        });
        $("#btn_cancle_addbatchsite").click(function() {
            cancleAddBatchSite();
        });
    });
});

function addBatchSite() {
    if (addBatchSiteMarker.length > 0) {
        var latlong = '';
        for (i = 0; i < addBatchSiteMarker.length; i++) {
            latlong += addBatchSiteMarker[i].position + '##';
        }
        //alert(lat_long_arr);return false;
        $("#batch_latlong").val(latlong)
        $("#batchPremises_box").trigger('click');

    } else {
        alert("Please select points on the map to create site");
        return false;
    }
}

function cancleAddBatchSite(){
    if (addBatchSiteMarker.length > 0) {
        for (i = 0; i < addBatchSiteMarker.length; i++) {
            addBatchSiteMarker[i].setMap(null);
        }
        addBatchSiteMarker.length = 0;
    }  
    $("#batchsitedivmsg").addClass('d-none');
    $("#batchsitedivmsg").html('Click the spot on the map where you want this site placed.');
    $("#btn_map_addbatchsite").addClass('d-flex').removeClass('d-none');           
}
/**************** Add Batch-create Premises ****************/

function clearFilterData(){
    //skNetwork = [];
    skCity = [];
    skZones = [];
    skZipcode = [];
    //$(".selectAllNetwork").prop("checked", false);
    $(".selectAllZone").prop("checked", false);
    $(".selectAllCity").prop("checked", false);
    $(".selectAllZipcode").prop("checked", false);
}

function clearLayerData(){
    networkLayer = [];
    zoneLayer = [];
    custLayer = [];
    pCircuitStatusLayer = [];
    pCircuitcTypeLayer = [];
    zoneLayer = [];
    fiberInquiryLayer = [];
    serviceOrderLayer = [];
    workOrderLayer = [];
    premiseStatusLayer = [];
    premiseAttribute = [];
    premiseTypeLayer = [];
    premisesubTypeLayer = [];
    $("#selectAllNetworkLayer").prop("checked", false);
    $(".selectAllNetworkLayer").prop("checked", false);
    $("#selectAllZoneLayer").prop("checked", false);
    $(".selectAllZoneLayer").prop("checked", false);
    $("#selectAllCustLayer").prop("checked", false);
    $(".selectAllCustLayer").prop("checked", false);
    $("#selectAllPremiseCircuitLayer").prop("checked", false);
    $(".selectAllPremiseCircuitLayer").prop("checked", false);
    $(".selectAllPCircuitStatusLayer").prop("checked", false);
    $("#selectAllPCircuitCTLayer").prop("checked", false);
    $(".selectAllPCircuitCTLayer").prop("checked", false);
    $("#selectAllPremiseLayer").prop("checked", false);
    $(".selectAllPremiseLayer").prop("checked", false);
    $(".selectAllpremiseStatusLayer").prop("checked", false);
    $("#selectAllpremiseAttributeLayer").prop("checked", false);
    $(".selectAllpremiseAttributeLayer").prop("checked", false);
    $("#selectAllpremiseTypeLayer").prop("checked", false);
    $(".selectAllpremiseTypeLayer").prop("checked", false);
    $(".selectAllpremisesubTypeLayer").prop("checked", false);
}