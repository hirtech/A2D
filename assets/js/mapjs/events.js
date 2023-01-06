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
    
    if (mode == 'filter_sites') {
        var iPremiseId = $.urlParam('iPremiseId');
        siteFilter.push(iPremiseId);
        getSiteSRFilterData(siteFilter, srFilter);
       
    } else if (mode == 'filter_fiberInquiry') {
        var iFiberInquiryId = $.urlParam('iFiberInquiryId');
        srFilter.push(iFiberInquiryId);
        getSiteSRFilterData(siteFilter, srFilter);
    }else {
        setTimeout(function(){
            if(typeof user_zones !== 'undefined' && user_zones.length > 0){
                skNetwork = [];
                skCity = [];
                skZones = [];
                skZipcode = [];
                $.each($("input[name='skZones[]']:checked"), function() {
                    skZones.push($(this).val());
                });
                resetButton();
                clearLayerData();
                getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
            }
        },2000);
    }  

	generateJson();
	getnetworkLayerJson();
    getZoneLayerJson();
    getCustomLayerJson();
    getFiberInquiryJson();
    getServiceOrderJson();
    getWorkOrderJson();
    getPremiseCircuitJson();
       
    $(document).on("click", "#showDistance", function() {
        //console.log("Distance Ready!!");
      
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
        $(".selectSiteData").each(function() {
            if ($(this).prop("checked")) {
                $(this).prop("checked", false);
            }
        });
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
    // ************* Select all Zone - Filter Submenu ************* //
    $(document).on("click", ".selectAllZone", function() {
        /*console.log('check zone')*/
        //initMap();
        clearMap();
        var checksone = checkZoneSelected();
        if(checksone == true){
            if ($("#selectAllNetwork").prop("checked") && $("#selectAllNetwork").val() != 'Yes') {
                $("#selectAllNetwork").prop("checked", false);
            }
            if ($("#selectAllCity").prop("checked") && $("#selectAllCity").val() != 'Yes') {
                $("#selectAllCity").prop("checked", false);
            }
            if ($("#selectAllZone").prop("checked") && $("#selectAllZone").val() != 'Yes') {
                $("#selectAllZone").prop("checked", false);
            }
            if ($("#selectAllZipcode").prop("checked") && $("#selectAllZone").val() != 'Yes') {
                $("#selectAllZipcode").prop("checked", false);
            }
            if ($("#showDistance").prop("checked")) {
                $("#showDistance").prop("checked", false);
            }
            if ($("#showCircle").prop("checked")) {
                $("#showCircle").prop("checked", false);
            }
            if ($("#showArea").prop("checked")) {
                $("#showArea").prop("checked", false);
            }
            clearMapTool();
            skNetwork = [];
            skCity = [];
            skZones = [];
            skZipcode = [];
            //alert("Selected Sit types: " + sAttr.join(", "));
            $.each($("input[name='sNetwork[]']:checked"), function() {
                skNetwork.push($(this).val());
            });
            $.each($("input[name='city[]']:checked"), function() {
                skCity.push($(this).val());
            });
            $.each($("input[name='skZones[]']:checked"), function() {
               
                skZones.push($(this).val());
            });
            $.each($("input[name='zipcode[]']:checked"), function() {
                skZipcode.push($(this).val());
            });
        }else{
            skNetwork = [];
            skCity = [];
            skZones = [];
            skZipcode = [];
        }
        resetButton();
        clearLayerData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Zone - Filter Submenu ************* //
    $(document).on("click", "#selectAllZone", function() {
        clearMap();
        skZones  = [];
        if ($("#selectAllZone").prop("checked")) {
            $(".selectAllZone").prop("checked", true);
            $("#selectAllZone").val("Yes");
            //$(".selectAllZone").trigger('click');
            $.each($("input[name='skZones[]']:checked"), function() {
                skZones.push($(this).val());
            });
        } else {
            $(".selectAllZone").prop("checked", false);
            $("#selectAllZone").val("No");
            skZones = [];
            $.each($("input[name='skZones[]']:checked"), function() {
                skZones.push($(this).val());
            });
            var checkzone = checkZoneSelected();
            if(checkzone == false){
                skNetwork = [];
                skCity = [];
                skZipcode = [];
            }
        }
        resetButton();
        clearLayerData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);  
    });

    // ************* Select Network - Filter Submenu ************* //
    $(document).on("click", "#selectAllNetwork", function() {
        clearMap();
        var checksone = checkZoneSelected();
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

            alert('Please select zone');
        }
        resetButton();
        clearLayerData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select City - Filter Submenu ************* //
    $(document).on("click", "#selectAllCity", function() {
        clearMap();
        var checksone = checkZoneSelected();
        skCity = [];
        if(checksone == true){
            if ($("#selectAllCity").prop("checked")) {
                $(".selectAllCity").prop("checked", true);
                $("#selectAllCity").val("Yes");
                //$(".selectAllCity").trigger('click');
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
            skNetwork = [];
            skCity = [];
            skZipcode = [];
            skZones = [];

            alert('Please select zone');
        }
        resetButton();
        clearLayerData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Zipcode - Filter Submenu ************* //
    $(document).on("click", "#selectAllZipcode", function() {
        clearMap();
        var checksone = checkZoneSelected();
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
            skNetwork = [];
            skZones = [];
            skCity = [];
            skZipcode = [];
            alert('Please select zone');
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
        $("#selectAllZoneLayer").prop("checked", false);
        $(".selectAllZoneLayer").prop("checked", false);
        $("#selectAllCustLayer").prop("checked", false);
        $(".selectAllCustLayer").prop("checked", false);
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
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
        //clearLayerData();
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
        $("#selectAllZoneLayer").prop("checked", false);
        $(".selectAllZoneLayer").prop("checked", false);
        $("#selectAllCustLayer").prop("checked", false);
        $(".selectAllCustLayer").prop("checked", false);
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
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
        clearFilterData();
        //clearLayerData();
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
        networkLayer = [];
        zoneLayer = [];
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
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
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
        clearFilterData();
        //clearLayerData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Custom - Layer Submenu ************* //
    $(document).on("click",".selectAllCustLayer",function(){
        clearMap();
        if ($("#selectAllCustLayer").prop("checked") && $("#selectAllCustLayer").val() != 'Yes') {
            $("#selectAllCustLayer").prop("checked", false);
        }
        custLayer = [];
        $.each($("input[name='custLayer[]']:checked"), function() {
              custLayer.push($(this).val());
        });
        resetButton();
        networkLayer = [];
        zoneLayer = [];
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
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
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
        clearFilterData();
        //clearLayerData();
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

            $.each($("input[name='custlayer[]']:checked"), function() {
                zoneLayer.push($(this).val());
            });
            //console.log(zoneLayer);
        } else {
            $(".selectAllZoneLayer").prop("checked", false);
            $("#selectAllZoneLayer").val("No");
            $.each($("input[name='custlayer[]']:checked"), function() {
                zoneLayer.push($(this).val());
            });
        }
        resetButton();
        networkLayer = [];
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
        $("#selectAllCustLayer").prop("checked", false);
        $(".selectAllCustLayer").prop("checked", false);
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
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
        clearFilterData();
        //clearLayerData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Fiber Zone KML - Layer Submenu ************* //
    $(document).on("click",".selectAllZoneLayer",function(){
        clearMap();
        if ($("#selectAllZoneLayer").prop("checked") && $("#selectAllZoneLayer").val() != 'Yes') {
            $("#selectAllZoneLayer").prop("checked", false);
        }
        zoneLayer = [];
        $.each($("input[name='zoneLayer[]']:checked"), function() {
              zoneLayer.push($(this).val());
        });
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
        pCircuitStatusLayer = [];
        pCircuitcTypeLayer = [];
        fiberInquiryLayer = [];
        serviceOrderLayer = [];
        workOrderLayer = [];
        premiseStatusLayer = [];
        premiseAttribute = [];
        premiseTypeLayer = [];
        premisesubTypeLayer = [];

        $("#selectAllNetworkLayer").prop("checked", false);
        $(".selectAllNetworkLayer").prop("checked", false);
        $("#selectAllCustLayer").prop("checked", false);
        $(".selectAllCustLayer").prop("checked", false);
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
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
        clearFilterData();
        //clearLayerData();
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Fiber Inquiry - Layer Submenu ************* //
    $(document).on("click", "#selectAllFiberInquiries", function() {
        clearMap();
        if ($("#selectAllFiberInquiries").prop("checked")) {
            $.each($("input[name='selectAllFiberInquiries']:checked"), function() {
                fiberInquiryLayer.push($(this).val());
            });
        } else {
            fiberInquiryLayer = [];
        }
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
        pCircuitStatusLayer = [];
        pCircuitcTypeLayer = [];
        zoneLayer = [];
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
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
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
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Service Order - Layer Submenu ************* //
    $(document).on("click", "#selectAllServiceOrders", function() {
        clearMap();
        if ($("#selectAllServiceOrders").prop("checked")) {
            $.each($("input[name='selectAllServiceOrders']:checked"), function() {
                serviceOrderLayer.push($(this).val());
            });
        } else {
            serviceOrderLayer = [];
        }
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
        pCircuitStatusLayer = [];
        pCircuitcTypeLayer = [];
        zoneLayer = [];
        fiberInquiryLayer = [];
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
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
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
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);

    });

    // ************* Select Work Order - Layer Submenu ************* //
    $(document).on("click", "#selectAllWorkOrders", function() {
        clearMap();
        if ($("#selectAllWorkOrders").prop("checked")) {
            $.each($("input[name='selectAllWorkOrders']:checked"), function() {
                workOrderLayer.push($(this).val());
            });
        } else {
            workOrderLayer = [];
        }
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
        pCircuitStatusLayer = [];
        pCircuitcTypeLayer = [];
        zoneLayer = [];
        fiberInquiryLayer = [];
        serviceOrderLayer = [];
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
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
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
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Premise Circuit All - Layer Submenu ************* //
    $(document).on("click", "#selectAllPremiseCircuitLayer", function() {
        clearMap();
        pCircuitStatusLayer = [];
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
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
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
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
        $("#selectAllPremiseLayer").prop("checked", false);
        $(".selectAllPremiseLayer").prop("checked", false);
        $(".selectAllpremiseStatusLayer").prop("checked", false);
        $("#selectAllpremiseAttributeLayer").prop("checked", false);
        $(".selectAllpremiseAttributeLayer").prop("checked", false);
        $("#selectAllpremiseTypeLayer").prop("checked", false);
        $(".selectAllpremiseTypeLayer").prop("checked", false);
        $(".selectAllpremisesubTypeLayer").prop("checked", false);
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Premise Circuit Status All - Layer Submenu ************* //
    $(document).on("click", ".selectAllPremiseCircuitLayer", function() {
        clearMap();
        pCircuitStatusLayer = [];
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
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
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
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
        $("#selectAllPremiseLayer").prop("checked", false);
        $(".selectAllPremiseLayer").prop("checked", false);
        $(".selectAllpremiseStatusLayer").prop("checked", false);
        $("#selectAllpremiseAttributeLayer").prop("checked", false);
        $(".selectAllpremiseAttributeLayer").prop("checked", false);
        $("#selectAllpremiseTypeLayer").prop("checked", false);
        $(".selectAllpremiseTypeLayer").prop("checked", false);
        $(".selectAllpremisesubTypeLayer").prop("checked", false);
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Premise Circuit Status  - Layer Submenu ************* //
    $(document).on("click",".selectAllPCircuitStatusLayer",function(){
        clearMap();
        if ($("#selectAllPCircuitStatusLayer").prop("checked") && $("#selectAllPCircuitStatusLayer").val() != 'Yes') {
            $("#selectAllPCircuitStatusLayer").prop("checked", false);
        }
        pCircuitStatusLayer = [];
        $.each($("input[name='pCircuitStatusLayer[]']:checked"), function() {
              pCircuitStatusLayer.push($(this).val());
        });
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
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
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
        $("#selectAllPremiseLayer").prop("checked", false);
        $(".selectAllPremiseLayer").prop("checked", false);
        $(".selectAllpremiseStatusLayer").prop("checked", false);
        $("#selectAllpremiseAttributeLayer").prop("checked", false);
        $(".selectAllpremiseAttributeLayer").prop("checked", false);
        $("#selectAllpremiseTypeLayer").prop("checked", false);
        $(".selectAllpremiseTypeLayer").prop("checked", false);
        $(".selectAllpremisesubTypeLayer").prop("checked", false);
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select PremiseCircuit ConnectionType All - Layer Submenu ********** //
    $(document).on("click", "#selectAllPCircuitCTLayer", function() {
        clearMap();
        pCircuitcTypeLayer = [];
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
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
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
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
        $("#selectAllPremiseLayer").prop("checked", false);
        $(".selectAllPremiseLayer").prop("checked", false);
        $(".selectAllpremiseStatusLayer").prop("checked", false);
        $("#selectAllpremiseAttributeLayer").prop("checked", false);
        $(".selectAllpremiseAttributeLayer").prop("checked", false);
        $("#selectAllpremiseTypeLayer").prop("checked", false);
        $(".selectAllpremiseTypeLayer").prop("checked", false);
        $(".selectAllpremisesubTypeLayer").prop("checked", false);
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select PremiseCircuit ConnectionType - Layer Submenu ********** //
    $(document).on("click",".selectAllPCircuitCTLayer",function(){
        clearMap();
        if ($("#selectAllPCircuitCTLayer").prop("checked") && $("#selectAllPCircuitCTLayer").val() != 'Yes') {
            $("#selectAllPCircuitCTLayer").prop("checked", false);
        }
        pCircuitcTypeLayer = [];
        $.each($("input[name='pCircuitcTypeLayer[]']:checked"), function() {
            pCircuitcTypeLayer.push($(this).val());
        });
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
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
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
        $("#selectAllPremiseLayer").prop("checked", false);
        $(".selectAllPremiseLayer").prop("checked", false);
        $(".selectAllpremiseStatusLayer").prop("checked", false);
        $("#selectAllpremiseAttributeLayer").prop("checked", false);
        $(".selectAllpremiseAttributeLayer").prop("checked", false);
        $("#selectAllpremiseTypeLayer").prop("checked", false);
        $(".selectAllpremiseTypeLayer").prop("checked", false);
        $(".selectAllpremisesubTypeLayer").prop("checked", false);
        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Premise All - Layer Submenu ************* //
    $(document).on("click", "#selectAllPremiseLayer", function() {
        clearMap();
        premiseStatusLayer = [];
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
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
        pCircuitStatusLayer = [];
        pCircuitcTypeLayer = [];
        fiberInquiryLayer = [];
        serviceOrderLayer = [];
        workOrderLayer = [];
        $("#selectAllNetworkLayer").prop("checked", false);
        $(".selectAllNetworkLayer").prop("checked", false);
        $("#selectAllZoneLayer").prop("checked", false);
        $(".selectAllZoneLayer").prop("checked", false);
        $("#selectAllCustLayer").prop("checked", false);
        $(".selectAllCustLayer").prop("checked", false);
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
        $("#selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPCircuitStatusLayer").prop("checked", false);
        $("#selectAllPCircuitCTLayer").prop("checked", false);
        $(".selectAllPCircuitCTLayer").prop("checked", false);

        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Premise All Status - Layer Submenu ************* //
    $(document).on("click", ".selectAllPremiseLayer", function() {
        clearMap();
        premiseStatusLayer = [];
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
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
        pCircuitStatusLayer = [];
        pCircuitcTypeLayer = [];
        fiberInquiryLayer = [];
        serviceOrderLayer = [];
        workOrderLayer = [];
        $("#selectAllNetworkLayer").prop("checked", false);
        $(".selectAllNetworkLayer").prop("checked", false);
        $("#selectAllZoneLayer").prop("checked", false);
        $(".selectAllZoneLayer").prop("checked", false);
        $("#selectAllCustLayer").prop("checked", false);
        $(".selectAllCustLayer").prop("checked", false);
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
        $("#selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPCircuitStatusLayer").prop("checked", false);
        $("#selectAllPCircuitCTLayer").prop("checked", false);
        $(".selectAllPCircuitCTLayer").prop("checked", false);

        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ************* Select Premise Status  - Layer Submenu ************* //
    $(document).on("click",".selectAllpremiseStatusLayer",function(){
        clearMap();
        if ($("#selectAllpremiseStatusLayer").prop("checked") && $("#selectAllpremiseStatusLayer").val() != 'Yes') {
            $("#selectAllpremiseStatusLayer").prop("checked", false);
        }
        premiseStatusLayer = [];
        $.each($("input[name='premiseStatusLayer[]']:checked"), function() {
              premiseStatusLayer.push($(this).val());
        });
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
        pCircuitStatusLayer = [];
        pCircuitcTypeLayer = [];
        fiberInquiryLayer = [];
        serviceOrderLayer = [];
        workOrderLayer = [];
        $("#selectAllNetworkLayer").prop("checked", false);
        $(".selectAllNetworkLayer").prop("checked", false);
        $("#selectAllZoneLayer").prop("checked", false);
        $(".selectAllZoneLayer").prop("checked", false);
        $("#selectAllCustLayer").prop("checked", false);
        $(".selectAllCustLayer").prop("checked", false);
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
        $("#selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPCircuitStatusLayer").prop("checked", false);
        $("#selectAllPCircuitCTLayer").prop("checked", false);
        $(".selectAllPCircuitCTLayer").prop("checked", false);

        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select Premise Attribute All - Layer Submenu ********** //
    $(document).on("click", "#selectAllpremiseAttributeLayer", function() {
        clearMap();
        premiseAttribute = [];
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
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
        pCircuitStatusLayer = [];
        pCircuitcTypeLayer = [];
        fiberInquiryLayer = [];
        serviceOrderLayer = [];
        workOrderLayer = [];
        $("#selectAllNetworkLayer").prop("checked", false);
        $(".selectAllNetworkLayer").prop("checked", false);
        $("#selectAllZoneLayer").prop("checked", false);
        $(".selectAllZoneLayer").prop("checked", false);
        $("#selectAllCustLayer").prop("checked", false);
        $(".selectAllCustLayer").prop("checked", false);
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
        $("#selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPCircuitStatusLayer").prop("checked", false);
        $("#selectAllPCircuitCTLayer").prop("checked", false);
        $(".selectAllPCircuitCTLayer").prop("checked", false);

        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select Premise Attribute - Layer Submenu ********** //
    $(document).on("click",".selectAllpremiseAttributeLayer",function(){
        clearMap();
        if ($("#selectAllpremiseAttributeLayer").prop("checked") && $("#selectAllpremiseAttributeLayer").val() != 'Yes') {
            $("#selectAllpremiseAttributeLayer").prop("checked", false);
        }
        premiseAttribute = [];
        $.each($("input[name='premiseAttribute[]']:checked"), function() {
            premiseAttribute.push($(this).val());
        });
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
        pCircuitStatusLayer = [];
        pCircuitcTypeLayer = [];
        fiberInquiryLayer = [];
        serviceOrderLayer = [];
        workOrderLayer = [];
        $("#selectAllNetworkLayer").prop("checked", false);
        $(".selectAllNetworkLayer").prop("checked", false);
        $("#selectAllZoneLayer").prop("checked", false);
        $(".selectAllZoneLayer").prop("checked", false);
        $("#selectAllCustLayer").prop("checked", false);
        $(".selectAllCustLayer").prop("checked", false);
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
        $("#selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPCircuitStatusLayer").prop("checked", false);
        $("#selectAllPCircuitCTLayer").prop("checked", false);
        $(".selectAllPCircuitCTLayer").prop("checked", false);

        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select PremiseC Type All - Layer Submenu ********** //
    $(document).on("click", "#selectAllpremiseTypeLayer", function() {
        clearMap();
        premiseTypeLayer = [];
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
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
        pCircuitStatusLayer = [];
        pCircuitcTypeLayer = [];
        fiberInquiryLayer = [];
        serviceOrderLayer = [];
        workOrderLayer = [];
        $("#selectAllNetworkLayer").prop("checked", false);
        $(".selectAllNetworkLayer").prop("checked", false);
        $("#selectAllZoneLayer").prop("checked", false);
        $(".selectAllZoneLayer").prop("checked", false);
        $("#selectAllCustLayer").prop("checked", false);
        $(".selectAllCustLayer").prop("checked", false);
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
        $("#selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPCircuitStatusLayer").prop("checked", false);
        $("#selectAllPCircuitCTLayer").prop("checked", false);
        $(".selectAllPCircuitCTLayer").prop("checked", false);

        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select Premise Type - Layer Submenu ********** //
    $(document).on("click",".selectAllpremiseTypeLayer",function(){
        clearMap();
        if ($("#selectAllpremiseTypeLayer").prop("checked") && $("#selectAllpremiseTypeLayer").val() != 'Yes') {
            $("#selectAllpremiseTypeLayer").prop("checked", false);
        }
        premiseTypeLayer = [];
        $.each($("input[name='premiseTypeLayer[]']:checked"), function() {
            premiseTypeLayer.push($(this).val());
            $("#premisesubTypeLayer_"+$(this).val()).prop("checked", false);
        });
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
        pCircuitStatusLayer = [];
        pCircuitcTypeLayer = [];
        fiberInquiryLayer = [];
        serviceOrderLayer = [];
        workOrderLayer = [];
        $("#selectAllNetworkLayer").prop("checked", false);
        $(".selectAllNetworkLayer").prop("checked", false);
        $("#selectAllZoneLayer").prop("checked", false);
        $(".selectAllZoneLayer").prop("checked", false);
        $("#selectAllCustLayer").prop("checked", false);
        $(".selectAllCustLayer").prop("checked", false);
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
        $("#selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPCircuitStatusLayer").prop("checked", false);
        $("#selectAllPCircuitCTLayer").prop("checked", false);
        $(".selectAllPCircuitCTLayer").prop("checked", false);

        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });

    // ********** Select Premise sub Type - Layer Submenu ********** //
    $(document).on("click",".selectAllpremisesubTypeLayer",function(){
        console.log("selectAllpremisesubTypeLayer")
        clearMap();
        
        premisesubTypeLayer = [];
        $.each($("input[name='premisesubTypeLayer[]']:checked"), function() {
            premisesubTypeLayer.push($(this).val());
        });
        resetButton();
        networkLayer = [];
        zoneLayer = [];
        custLayer = [];
        pCircuitStatusLayer = [];
        pCircuitcTypeLayer = [];
        fiberInquiryLayer = [];
        serviceOrderLayer = [];
        workOrderLayer = [];
        $("#selectAllNetworkLayer").prop("checked", false);
        $(".selectAllNetworkLayer").prop("checked", false);
        $("#selectAllZoneLayer").prop("checked", false);
        $(".selectAllZoneLayer").prop("checked", false);
        $("#selectAllCustLayer").prop("checked", false);
        $(".selectAllCustLayer").prop("checked", false);
        $("#selectAllFiberInquiries").prop("checked", false);
        $("#selectAllServiceOrders").prop("checked", false);
        $("#selectAllWorkOrders").prop("checked", false);
        $("#selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPremiseCircuitLayer").prop("checked", false);
        $(".selectAllPCircuitStatusLayer").prop("checked", false);
        $("#selectAllPCircuitCTLayer").prop("checked", false);
        $(".selectAllPCircuitCTLayer").prop("checked", false);

        getMapData(skNetwork, skCity, skZipcode, skZones, networkLayer, zoneLayer, custLayer, fiberInquiryLayer, serviceOrderLayer, workOrderLayer, pCircuitStatusLayer, pCircuitcTypeLayer, premiseStatusLayer, premiseAttribute, premiseTypeLayer, premisesubTypeLayer);
    });   

});

/*Check Zone's */
function checkZoneSelected(){
    var zone_cnt = 0;
    //console.log('skZones.length=>'+skZones.length);
    $.each($("input[name='skZones[]']:checked"), function() {
        zone_cnt++;
    });
    if(zone_cnt == 0){
        $("#selectAllNetwork").prop("checked", false);
        $("#selectAllCity").prop("checked", false);
        $("input[name='sNetwork[]']").prop("checked", false);
        $("input[name='sAttr[]").prop("checked", false);
        $("input[name='city[]").prop("checked", false);
        return false;
    }else{
        return true;
    }
}
// search Premise Name 
var selectedsr = null;
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
})(jQuery);

function onSiteClusteSelected(e, datum) {
    $("#serach_iPremiseId").val(datum['iPremiseId']);
    $("#vName").val(datum['display']);
    $("#clear_site_address_id").show();
}

$("#search_site_map").click(function() {
    var empty = 0;
    var s = 0;
    var one_filled;
    var siteData = [];
    var srData = [];
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
        var iPremiseId = $("#iPremiseId").val();
        if (iPremiseId != '') {
            siteData.push(iPremiseId);
        }

        var serach_iPremiseId = $("#serach_iPremiseId").val();
        if (serach_iPremiseId != '') {
            siteData.push(serach_iPremiseId);
        }

        var iSRId = $("#iSRId").val();
        if (iSRId != '') {
            srData.push(iSRId);
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
        } else if (srData && srData.length > 0) {
            action = "getSerachSRData";
        }
        clearFilterData();
        clearLayerData();
        clearMap();
        setTimeout(function () {
            if((jQuery.isEmptyObject(srData) == false && srData.length > 0) || (jQuery.isEmptyObject(siteData) == false && siteData.length > 0)){
               
                $.ajax({
                    type: "POST",
                    url: 'vmap/api/',
                    data: {
                        action: action,
                        premiseId: siteData.join(),
                        srId: srData.join()
                    },
                    cache: true,
                    beforeSend: function() {
                       $(".loading").show();
                    },
                    success: function(data) {
                        if (data) {
                            console.log('data found');
                   
                            var siteData = JSON.parse(data);
                            if(Object.keys(siteData).length > 0){
                                $.each(siteData, function(premiseid, item) {
                                    if (action == 'getSerachSRData' ) {
                                    //console.log('data found-1');
                                        if (siteData[premiseid].point !== undefined) {
                                            for (i = 0; i < siteData[premiseid].point.length; i++) {
                                                
                                                var pointMatrix = {
                                                    lat: siteData[premiseid].point[i]['lat']+ mathRandLat,
                                                    lng: siteData[premiseid].point[i]['lng']+ mathRandLng
                                                };
                                                var vName = siteData[premiseid].vName;
                                                var vAddress = siteData[premiseid].vAddress;
                                                var vRequestType = siteData[premiseid].vRequestType;
                                                var vAssignTo = siteData[premiseid].vAssignTo;
                                                var vStatus = siteData[premiseid].vStatus;

                                                sitesearchMarker[s] = new google.maps.Marker({
                                                    map: map,
                                                    position: pointMatrix,
                                                    icon: siteData[premiseid].icon,
                                                });
                                                
                                                newLocation(pointMatrix.lat,pointMatrix.lng);
                                                $sr_map = sitesearchMarker[s];

                                                fiberInquiryinfo_popup($sr_map, premiseid, vName, vAddress, vRequestType, vAssignTo, vStatus);
                                                sitesearchMarker[s].setMap(map);
                                                
                                                //Extend each marker's position in LatLngBounds object.
                                                bounds.extend(sitesearchMarker[s].position);
                                                s++;
                                            }
                                        }
                                    }else{
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

function resetButton() {
    $("#iPremiseId").val('');
    $("#serach_iPremiseId").val('');
    $("#iSRId").val('');
    $("#vLatitude").val('');
    $("#vLongitude").val('');
    $("#vName").val('');
    $("#autofilladdress").val('');
    $("#clear_site_address_id").trigger('click');
    $("#clear_address_id").hide();
    $("#serach_iPremiseId").val('');

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
    skNetwork = [];
    skCity = [];
    skZones = [];
    skZipcode = [];
    $(".selectAllNetwork").prop("checked", false);
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