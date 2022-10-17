fieldmap_sr_arr = [];
landing_rate = [];
larval = [];
LarvalFieldtask = [];
LandingFieldtask = [];
positive = [];
siteFilter = [];
srFilter = [];
custLayer = [];
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
    console.log("Api ready!");
   // alert(mode);
    initMap();
    
    if (mode == 'filter_sites') {
        var iSiteId = $.urlParam('iSiteId');
        siteFilter.push(iSiteId);
        getSiteSRFilterData(siteFilter, srFilter);
       
    } else if (mode == 'filter_fiberInquiry') {
        var iFiberInquiryId = $.urlParam('iFiberInquiryId');
        srFilter.push(iFiberInquiryId);
        getSiteSRFilterData(siteFilter, srFilter);
     
    }
    
    	generateJson();
    	generateSRJson();
    	generatelandingrateJson();
    	getlarvalJson();
    	getpositiveJson();
    	getCustomLayerJson();
       
    $(document).on("click", "#showDistance", function() {
        console.log("Distance Ready!!");
      
        if ($("#showArea").prop("checked")) {
            $("#showArea").prop("checked", false);
        }
        if ($("#showCircle").prop("checked")) {
            $("#showCircle").prop("checked", false);
        }

        clearMapTool();

        clearMap();
        resetButton();
        clearFilterData();
        clearLayersData();

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
        /*else{
            $("#distanceinmiles").val('');
            $("#distanceinft").val('');
            poly.setMap(null);
            if (polylineMarker.length > 0) {
                for (let i = 0; i < polylineMarker.length; i++) {
                    polylineMarker[i].setMap(null);
                }
            }
        }*/
    });
    $(document).on("click", "#showArea", function() {
        console.log("Polygon Ready!!");
   
        if ($("#showDistance").prop("checked")) {
            $("#showDistance").prop("checked", false);
        }
        if ($("#showCircle").prop("checked")) {
            $("#showCircle").prop("checked", false);
        }

        clearMapTool();

        clearMap();
        resetButton();
        clearFilterData();
        clearLayersData();

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
        /*else{
            $("#areainmiles").val('');
            $("#areainft").val('');
        }*/
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
        clearFilterData();
        clearLayersData();
      
        if ($("#showCircle").prop("checked")) {
            listenerLatLngCircle= map.addListener('click', addLatLngCircle);

            //Add a listener event for zone draw polyline
            if(zCount > 0){
                for (k = 0 ;k <zCount; k++ ){
                    zoneLatLngCircle[k] = google.maps.event.addListener(zonePolygonObj[k], 'click', addLatLngCircle);
                }
            }
        }
        /*else{
            $("#rCircle").val('');
            $("#areaCircle").val('');
        }*/

    });
    $(document).on("click", ".map-tool-checkbox", function() {
        
        $(".selectSiteData").each(function() {
            if ($(this).prop("checked")) {
                $(this).prop("checked", false);
            }

        });
        
        if ($("#selectAllsType").prop("checked") && $("#selectAllsType").val() != 'Yes') {
            $("#selectAllsType").prop("checked", false);
        }
        if ($("#selectAllsAttr").prop("checked") && $("#selectAllsAttr").val() != 'Yes') {
            $("#selectAllsAttr").prop("checked", false);
        }
        if ($("#selectAllCity").prop("checked") && $("#selectAllCity").val() != 'Yes') {
            $("#selectAllCity").prop("checked", false);
        }
        if ($("#selectAllZone").prop("checked") && $("#selectAllZone").val() != 'Yes') {
            $("#selectAllZone").prop("checked", false);
        }
    });

    $(document).on("click", ".selectSiteData", function() {
        /*console.log('check Premise Type')*/
        //initMap();
        clearMap();
        var checksone = checkZoneSelected();

        if(checksone == true){
            if ($("#selectAllsType").prop("checked") && $("#selectAllsType").val() != 'Yes') {
                $("#selectAllsType").prop("checked", false);
            }
            if ($("#selectAllsAttr").prop("checked") && $("#selectAllsAttr").val() != 'Yes') {
                $("#selectAllsAttr").prop("checked", false);
            }
            if ($("#selectAllCity").prop("checked") && $("#selectAllCity").val() != 'Yes') {
                $("#selectAllCity").prop("checked", false);
            }
            if ($("#selectAllZone").prop("checked") && $("#selectAllZone").val() != 'Yes') {
                $("#selectAllZone").prop("checked", false);
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

            siteTypes = [];
            siteSubTypes = [];
            sAttr = []; 
            skCity = [];
            skZones = [];
    
            $.each($("input[name='sType[]']:checked"), function() {
                    siteTypes.push($(this).val());
            });
            $.each($("input[name='sSType[]']:checked"), function() {
                    siteSubTypes.push($(this).val());
            });
            $.each($("input[name='sAttr[]']:checked"), function() {
               
                sAttr.push($(this).val());
            });
            $.each($("input[name='city[]']:checked"), function() {
               
                skCity.push($(this).val());
            });
            $.each($("input[name='skZones[]']:checked"), function() {
               
                skZones.push($(this).val());
            });

            // $.each($("input[name='selectAllsServices']:checked"), function(){
            //     fieldmap_sr_arr.push($(this).val());
            // });
            // $.each($("input[name='selectAllslandingrate']:checked"), function(){
            //     landing_rate.push($(this).val());
            // });
            // $.each($("input[name='selectAllslarval']:checked"), function(){
            //     larval.push($(this).val());
            // });
        }else{
            siteTypes = [];
            siteSubTypes = [];
            sAttr = []; 
            skCity = [];
            skZones = [];

            alert('Please select zone');
        }
        resetButton();
        clearFilterData();
        getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr,  LarvalFieldtask, LandingFieldtask, positive, custLayer, siteSubTypes);
    });
    $(document).on("click", ".selectAllZone", function() {
        /*console.log('check zone')*/
        //initMap();
        clearMap();
        var checksone = checkZoneSelected();

        if(checksone == true){
            if ($("#selectAllsType").prop("checked") && $("#selectAllsType").val() != 'Yes') {
                $("#selectAllsType").prop("checked", false);
            }
            if ($("#selectAllsAttr").prop("checked") && $("#selectAllsAttr").val() != 'Yes') {
                $("#selectAllsAttr").prop("checked", false);
            }
            if ($("#selectAllCity").prop("checked") && $("#selectAllCity").val() != 'Yes') {
                $("#selectAllCity").prop("checked", false);
            }
            if ($("#selectAllZone").prop("checked") && $("#selectAllZone").val() != 'Yes') {
                $("#selectAllZone").prop("checked", false);
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
            siteTypes = [];
            siteSubTypes = [];
            sAttr = []; 
            skCity = [];
            skZones = [];
            $.each($("input[name='sType[]']:checked"), function() {
                    siteTypes.push($(this).val());
            });
            $.each($("input[name='sSType[]']:checked"), function() {
                    siteSubTypes.push($(this).val());
            });
            $.each($("input[name='sAttr[]']:checked"), function() {
               
                sAttr.push($(this).val());
            });
            //alert("Selected Sit types: " + sAttr.join(", "));
            $.each($("input[name='city[]']:checked"), function() {
               
                skCity.push($(this).val());
            });
            $.each($("input[name='skZones[]']:checked"), function() {
               
                skZones.push($(this).val());
            });
        }else{
            siteTypes = [];
            siteSubTypes = [];
            sAttr = []; 
            skCity = [];
            skZones = [];
        }
        resetButton();
        clearFilterData();
        getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr,  LarvalFieldtask, LandingFieldtask, positive, custLayer, siteSubTypes);
    });

    $(document).on("click", "#selectAllsType", function() {
        console.log("Select Premise Types");
       
        clearMap();
        var checksone = checkZoneSelected();
        siteTypes = [];
        if(checksone == true){
            if ($("#selectAllsType").prop("checked")) {
                $(".selectAllsType").prop("checked", true);
                $("#selectAllsType").val("Yes");

                //$(".selectAllsType").trigger('click');
                $.each($("input[name='sType[]']:checked"), function() {
                    siteTypes.push($(this).val());
                });
                //console.log(siteTypes);
               
            } else {
                $(".selectAllsType").prop("checked", false);
                $("#selectAllsType").val("No");
                $.each($("input[name='sType[]']:checked"), function() {
                    siteTypes.push($(this).val());
                });
                //clearMap();
                //getMapData(siteTypes, sAttr, skCity, skZones);
            }
        }else{
            siteTypes = [];
            siteSubTypes = [];
            sAttr = []; 
            skCity = [];
            skZones = [];

            alert('Please select zone');
        }
        resetButton();
        clearFilterData();
        getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr,  LarvalFieldtask, LandingFieldtask, positive,  custLayer, siteSubTypes);

    });
    $(document).on("click", "#selectAllsAttr", function() {
        console.log("Select Site Attr");
       
        clearMap();
        var checksone = checkZoneSelected();
        sAttr = [];
        if(checksone == true){
            if ($("#selectAllsAttr").prop("checked")) {
                $(".selectAllsAttr").prop("checked", true);
                $("#selectAllsAttr").val("Yes");
                // $(".selectAllsAttr").trigger('click');
                $.each($("input[name='sAttr[]']:checked"), function() {
                    sAttr.push($(this).val());
                });
            } else {
                $(".selectAllsAttr").prop("checked", false);
                $("#selectAllsAttr").val("No");
               
                $.each($("input[name='sAttr[]']:checked"), function() {
                    sAttr.push($(this).val());
                });
                //clearMap();
                //getMapData(siteTypes, sAttr, skCity, skZones);
            }
        }else{
            siteTypes = [];
            siteSubTypes = [];
            sAttr = []; 
            skCity = [];
            skZones = [];

            alert('Please select zone');
        }
        resetButton();
        clearFilterData();
		getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr, LarvalFieldtask, LandingFieldtask, positive, custLayer, siteSubTypes);
    });
    $(document).on("click", "#selectAllCity", function() {
        console.log("Select Site Attr");
      
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
                //clearMap();
                // getMapData(siteTypes, sAttr, skCity, skZones);
            }
        }else{
            siteTypes = [];
            siteSubTypes = [];
            sAttr = []; 
            skCity = [];
            skZones = [];

            alert('Please select zone');
        }
        resetButton();
        clearFilterData();
        getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr,  LarvalFieldtask, LandingFieldtask, positive, custLayer, siteSubTypes);

    });
    $(document).on("click", "#selectAllZone", function() {
        console.log("Select Site Zones");
       // initMap();
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
                    siteTypes = [];
                    siteSubTypes = [];
                    sAttr = []; 
                    skCity = [];
                }
                //getMapData(siteTypes, sAttr, skCity, skZones);
            }
            resetButton();
        clearFilterData();
        getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr,  LarvalFieldtask, LandingFieldtask, positive, custLayer, siteSubTypes);    
    });

    $(document).on("click", ".selectAllsServices", function() {
        
        clearMap();
        if ($("#selectAllsServices").prop("checked")) {
            $.each($("input[name='selectAllsServices']:checked"), function() {
                fieldmap_sr_arr.push($(this).val());
            });
        } else {

            fieldmap_sr_arr = [];
      
        }
        resetButton();
        clearLayersData();
        getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr,  LarvalFieldtask, LandingFieldtask, positive, custLayer, siteSubTypes);
        
    });
    $(document).on("click", ".selectAllslandingrate", function() {
       
        clearMap();
      
        landing_rate = [];
        larval =[];
        //var fieldtask_arr;

        if (this.checked) {
            $.each($("input[name='selectAllslandingrate']:checked"), function() {
                landing_rate.push($(this).val());
            });
           LandingFieldtask = landing_rate;
        } else {
      
            landing_rate = [];
         
            /*****************/
                if ($(".selectAllslarval").prop('checked')){
                    
                    $.each($("input[name='selectAllslarval']:checked"), function() {
                        larval.push($(this).val());
                    });
					LarvalFieldtask= larval;
                }
             LandingFieldtask = [];  
            /*****************/
        }
        resetButton();
        clearLayersData();

       // getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr, landing_rate, positive, siteFilter, srFilter,custLayer);
        getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr,  LarvalFieldtask, LandingFieldtask, positive, custLayer, siteSubTypes);

    });
    $(document).on("click", ".selectAllslarval", function() {
        
        clearMap();
        
        larval =[];
        landing_rate =[]; 
        //var fieldtask_arr;

        if (this.checked) {
            $.each($("input[name='selectAllslarval']:checked"), function() {
                larval.push($(this).val());
            });
            LarvalFieldtask= larval;
        } else {
            //markerCluster.clearMarkers();
            larval = [];
            /*****************/
                if ($(".selectAllslandingrate").prop('checked')){
                    
                    $.each($("input[name='selectAllslandingrate']:checked"), function() {
                        landing_rate.push($(this).val());
                    });
					LandingFieldtask = landing_rate;
                }
               LarvalFieldtask= [];
            /*****************/
        }
        resetButton();
        clearLayersData();
        //getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr, larval, positive, siteFilter, srFilter,custLayer);
        getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr,  LarvalFieldtask, LandingFieldtask, positive, custLayer, siteSubTypes);


    });
    $(document).on("click", ".selectAllspositive", function() {
        
        clearMap();

        if (this.checked) {
            $.each($("input[name='selectAllspositive']:checked"), function() {
                positive.push($(this).val());
            });
        } else {
           // markerCluster.clearMarkers();
           positive = [];
        }
        resetButton();
        clearLayersData();
        getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr,  LarvalFieldtask, LandingFieldtask, positive, custLayer, siteSubTypes);
        
    });

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
            //clearMap();
            //getMapData(siteTypes, sAttr, skCity, skZones);
        }
        resetButton();
        clearLayersData();
        getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr,  LarvalFieldtask, LandingFieldtask, positive, custLayer, siteSubTypes);

    });

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
        clearLayersData();
        getMapData(siteTypes, sAttr, skCity, skZones, fieldmap_sr_arr,  LarvalFieldtask, LandingFieldtask, positive, custLayer);
   
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
        $("#selectAllsType").prop("checked", false);
        $("#selectAllsAttr").prop("checked", false);
        $("#selectAllCity").prop("checked", false);
        $("input[name='sType[]']").prop("checked", false);
        $("input[name='sSType[]").prop("checked", false);
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
                            iSiteId: rawdata.iSiteId
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
    $("#serach_iSiteId").val(datum['iSiteId']);
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
        var iSiteId = $("#iSiteId").val();
        if (iSiteId != '') {
            siteData.push(iSiteId);
        }

        var serach_iSiteId = $("#serach_iSiteId").val();
        if (serach_iSiteId != '') {
            siteData.push(serach_iSiteId);
        }

        var iSRId = $("#iSRId").val();
        if (iSRId != '') {
            srData.push(iSRId);
        }

        var vLatitude = $("#vLatitude").val();
        var vLongitude = $("#vLongitude").val();
        var address_siteid;
        if (vLatitude != '' && vLongitude != '') {
            $.ajax({
                async: false,
                type: "POST",
                url: site_url + "vmap/index",
                data: 'mode=serach_iSiteId&vLatitude=' + vLatitude + '&vLongitude=' + vLongitude,
                success: function(responsedata) {
                    if(responsedata != ""){
                        address_siteid = responsedata;
                        siteData.push(address_siteid);
                    }
                }
            });

        }

        if (siteData && siteData.length > 0) {
            action = "getSerachSiteData";
        } else if (srData && srData.length > 0) {
            action = "getSerachSRData";
        }
        clearLayersData();
        clearFilterData();
        clearMap();
        setTimeout(function () {

            if((jQuery.isEmptyObject(srData) == false && srData.length > 0) || (jQuery.isEmptyObject(siteData) == false && siteData.length > 0)){
               
                $.ajax({
                    type: "POST",
                    url: 'vmap/api/',
                    data: {
                        action: action,
                        siteId: siteData.join(),
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
                                $.each(siteData, function(siteid, item) {
                                    if (action == 'getSerachSRData' ) {
                                    //console.log('data found-1');
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
                                                var vName = siteData[siteid].vName;
                                                var vAddress = siteData[siteid].vAddress;
                                                var vRequestType = siteData[siteid].vRequestType;
                                                var vAssignTo = siteData[siteid].vAssignTo;
                                                var vStatus = siteData[siteid].vStatus;
                              

                                                    sitesearchMarker[s] = new google.maps.Marker({
                                                        map: map,
                                                        position: pointMatrix,
                                                        icon: siteData[siteid].icon,
                                                    });
                                                    
                                                    newLocation(pointMatrix.lat,pointMatrix.lng);
                                                    $sr_map = sitesearchMarker[s];

                                                    srinfo_popup($sr_map, siteid, vName, vAddress, vRequestType, vAssignTo, vStatus);
                                                    sitesearchMarker[s].setMap(map);
                                                
                                                //Extend each marker's position in LatLngBounds object.
                                                bounds.extend(sitesearchMarker[s].position);
                                                
                                                s++;
                                            }
                                        }
                                    }else{
                                        if (siteData[siteid].polygon !== undefined) {
                                                sitesearchMarker[s] = new google.maps.Polygon({
                                                    path: siteData[siteid].polygon,
                                                    strokeColor: '#FF0000',
                                                    strokeOpacity: 0.8,
                                                    strokeWeight: 2,
                                                    fillColor: '#FF0000',
                                                    fillOpacity: 0.35,
                                                    icon: siteData[siteid].icon,
                                                });

                                                $site_map = sitesearchMarker[s];
                                                info_popup($site_map, siteid);
                                                

                                                //show polygon area
                                                infoPolygonArea($site_map, siteid);
                                                
        
                                            
                                                sitesearchMarker[s].setMap(map);


                                                //gmarkers.push($site_map);
                                                

                                                //Extend each marker's position in LatLngBounds object.
                                                sitesearchMarker[s].getPath().forEach(function (path, index) {
                                                    bounds.extend(path);
                                                });

                                                s++;
                                                

                                                if (siteData[siteid].polyCenter !== undefined) {
                                                    /*var centerPoint = {
                                                        lat: siteData[siteid].polyCenter['lat'] + (Math.random() / 10000),
                                                        lng: siteData[siteid].polyCenter['lng'] + (Math.random() / 10000)
                                                    };*/
                                                    var centerPoint = {
                                                        lat: siteData[siteid].polyCenter['lat'] + mathRandLat,
                                                        lng: siteData[siteid].polyCenter['lng'] + mathRandLng
                                                    };
                                                    
                                                    sitesearchMarker[s] = new google.maps.Marker({
                                                        position: centerPoint,
                                                        map: map,
                                                        icon: siteData[siteid].icon,
                                                    });
                                                    
                                                    //newLocation(centerPoint.lat, centerPoint.lng);
                                                    $site_map = sitesearchMarker[s];
                                                    info_popup($site_map, siteid);
                                                    sitesearchMarker[s].setMap(map);
                                                    
                                                    //Extend each marker's position in LatLngBounds object.
                                                    bounds.extend(sitesearchMarker[s].position);

                                                    s++;

                                                }
                                        }
                                        if (siteData[siteid].poly_line !== undefined) {
                                                
                                                sitesearchMarker[s] = new google.maps.Polyline({
                                                    path: siteData[siteid].poly_line,
                                                    strokeColor: '#FF0000',
                                                    strokeOpacity: 0.8,
                                                    strokeWeight: 2,
                                                    fillColor: '#FF0000',
                                                    fillOpacity: 0.35,
                                                    icon: siteData[siteid].icon,
                                                });
                                                    //alert(siteData[siteid].icon);
                                               
                                                //newLocation(siteData[siteid].poly_line.lat, siteData[siteid].poly_line.lng);
                                                
                                                $site_map = sitesearchMarker[s];
                                                sitesearchMarker[s].setMap(map);
                                                info_popup($site_map, siteid);
                                                
                                                //gmarkers.push($site_map);

                                                //Extend each marker's position in LatLngBounds object.
                                                /*latlngbounds.extend(sitesearchMarker[s].position);*/
                                                sitesearchMarker[s].getPath().forEach(function (path, index) {
                                                   
                                                    bounds.extend(path);

                                                });
                                                s++;
                                        }
                                        if (siteData[siteid].point !== undefined) {
                                            //console.log('23333');
                                                for (i = 0; i < siteData[siteid].point.length; i++) {
                                                    /*var pointMatrix = {
                                                        lat: siteData[siteid].point[i]['lat'] + (Math.random() / 10000),
                                                        lng: siteData[siteid].point[i]['lng'] + (Math.random() / 10000)
                                                    };*/
                                                    var pointMatrix = {
                                                        lat: siteData[siteid].point[i]['lat'] + mathRandLat,
                                                        lng: siteData[siteid].point[i]['lng'] + mathRandLng
                                                    };
                                                
                                                    sitesearchMarker[s] = new google.maps.Marker({
                                                        map: map,
                                                        position: pointMatrix,
                                                        icon: siteData[siteid].icon,
                                                    });
                                                    /*if (siteData[siteid].length != 0) {
                                                        newLocation(pointMatrix.lat, pointMatrix.lng);
                                                    }*/
                                                    
                                                    newLocation(pointMatrix.lat, pointMatrix.lng);
                                                    $site_map = sitesearchMarker[s];

                                                    //gmarkers.push($site_map);

                                                    info_popup($site_map, siteid);
                                                    sitesearchMarker[s].setMap(map);

                                                    //Extend each marker's position in LatLngBounds object.
                                                    bounds.extend(sitesearchMarker[s].position);

                                                    s++;

                                                    var vName = siteData[siteid].vName;
                                                    var vAddress = siteData[siteid].vAddress;
                                                    var vRequestType = siteData[siteid].vRequestType;
                                                    var vAssignTo = siteData[siteid].vAssignTo;
                                                    var vStatus = siteData[siteid].vStatus;
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
    console.log("clear_site_address");
    $('#vName').val('');
    $('#serach_iSiteId').val('');
    $("#clear_site_address_id").hide();
}

function resetButton() {
    //deleteMarkers();
    $("#iSiteId").val('');
    $("#serach_iSiteId").val('');
    $("#iSRId").val('');
    $("#vLatitude").val('');
    $("#vLongitude").val('');
    $("#vName").val('');
    $("#autofilladdress").val('');
    $("#clear_site_address_id").trigger('click');
    $("#clear_address_id").hide();
    $("#serach_iSiteId").val('');

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
function  addSite() {
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

                if ($.isEmptyObject(mapAddZoneBatchSiteListner) == false) {
                    for (g = 0; g < zCount; g++) {
                        google.maps.event.removeListener(mapAddZoneBatchSiteListner[g]);
                    }
                    mapAddZoneBatchSiteListner = [];
                }

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


function clearLayersData(){
    siteTypes = [];
    siteSubTypes = [];
    sAttr = []; 
    skCity = [];     
    skZones  = [];
    //$("input[name='sType[]']").prop("checked", false);
    $(".selectSiteData").prop("checked", false);
    $(".selectAllsType").prop("checked", false);
    //$("input[name='sSType[]']']").prop("checked", false);
    $(".selectAllsAttr").prop("checked", false);
    $(".selectAllCity").prop("checked", false);
    $(".selectAllZone").prop("checked", false);
    $("#nearbysite").prop("checked", false);
    //$("input[name='sAttr[]']").prop("checked", false);
    //$("input[name='city[]']").prop("checked", false);
    //$("input[name='skZones[]']").prop("checked", false);
}

function clearFilterData(){
    fieldmap_sr_arr =[];
    LarvalFieldtask =[];
    LandingFieldtask=[];
    positive=[];
    custLayer=[];

    $(".selectAllsServices").prop("checked", false);
    $(".selectAllslandingrate").prop("checked", false);
    $(".selectAllslarval").prop("checked", false);
    $(".selectAllspositive").prop("checked", false);
    $("#selectAllCustLayer").prop("checked", false);
    $(".selectAllCustLayer").prop("checked", false);
}