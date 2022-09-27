var newPolygonArr = [];
var newPolylineArr = [];
$(document).ready(function() {
    $('select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
          width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
    });

    if(mode == 'Add'){
        if(tmplng == "" && tmplat == ""){
           $(".address-details").hide(); 
       }else{
            $(".clear_address").show();
            $(".address-details").show(); 
            displayMap($("#iGeometryType").val(), mode)
       }
        
    }else if(mode == 'Update'){
        //setTimeout(function(){
            getSiteSubType($("#iSTypeId").val());
            displayMap($("#iGeometryType").val(), mode)
        //}, 500);
        
    }

    if(tabid != ""){
        $("#"+tabid).trigger("click");
        var tabsection =  $("#"+tabid).attr('href');
        $("#nav-tabContent").find("div.tab-pane").removeClass("active");
        $(tabsection).addClass("active");
    }

});


$("#save_data").click(function(){
    $('#save_loading').show();   
    $("#save_data").prop('disabled', true);
   // $('#save_loading').show();
    var form = $("#frmadd");
    //alert(form[0].checkValidity())
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');
    var active_tabId = $("#nav-tab li a.active").attr('id');
    if(isError == 0){
        if(active_tabId == 'general'){
            swal("","Please confirm the address details");
            $("#confirm").trigger("click");
            $('#save_loading').hide();   
            $("#save_data").prop('disabled', false);
            return false;
        }

        var form_data = $("#frmadd").serializeArray();
       
        $.ajax({
            type: "POST",
            url: site_url+"premise/list",
            data: form_data,
            cache: false,
            success: function(data){
                $('#save_loading').hide();   
                $("#save_data").prop('disabled', false);
                // console.log(data)
                response =JSON.parse(data);
                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                    var siteId = response['iSiteId'];
                }else{
                    toastr.error(response['msg']);
                }
                setTimeout(function () {
                    if(response['error'] == "0" &&  $("#mode").val() == "Add" ) {
                        swal({
                            title: "Are you want to continue edit premise?",
                            text: "",
                            type: "info",
                            showCancelButton: true,
                            //confirmButtonColor: "#DD6B55",
                            confirmButtonClass: 'confirm btn btn-lg btn-danger',
                            cancelButtonClass : 'cancel btn btn-lg btn-default',
                            confirmButtonText: 'Yes',
                            cancelButtonText: "No",
                            closeOnConfirm: false,
                            closeOnCancel: true,
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                       swal.close();
                                       location.href = site_url+'premise/edit&mode=Update&iSiteId='+siteId+'&tabid=contact';
                                    // $("#contact").click();
                                } else {
                                    swal.close();
                                    location.href = site_url+'premise/list';
                                }
                            }
                        );
                    }else{
                        location.href = site_url+'premise/list';
                    }
                }, 3500);
            }
        });
        return false; 
    }else{
        $('#save_loading').hide();   
        $("#save_data").prop('disabled', false);
    }
});

function getSiteSubType(sTypeid){

   $("#iSSTypeId").html('<option value="">---Select---</option>');
   if(sTypeid != ""){
        $.ajax({
            type: "POST",
            url: site_url+"premise/add",
            data: {
                "mode" : "getSiteSubType",
                "iSiteTypeId" : sTypeid
            },
            success: function(data){
                response =JSON.parse(data);
                var option ="<option value=''>---Select---</option>";
                if(response.length > 0 ){
                    $.each(response,function(i,val){
                        var selected = '';
                        //alert(iSSTypeId)
                        if(iSSTypeId == response[i]['iSSTypeId']){
                            selected = ' selected';
                        }
                        option +="<option value='"+response[i]['iSSTypeId']+"'"+selected+">"+response[i]['vSubTypeName']+"</option>";
                    });
                }
                $("#iSSTypeId").html(option);

                $("#iSSTypeId").focus();
            }
        });
   }
}

function clear_address() {
    $('#autofilladdress').val('');
    $('#autofilladdress').focus();
    $(".address-details").hide();
}

function displayMap(iGeometryType, mode) {
    if(($("#vLatitude").val() != '' && $("#vLongitude").val() != '') || ($("#vNewLatitude").val() != '' && $("#vNewLongitude").val() != '')){

        if(mode == 'Update'){
            if($("#vNewLatitude").val() != '' && $("#vNewLongitude").val() != ''){
                var vLatitude = parseFloat($("#vNewLatitude").val());
                var vLongitude = parseFloat($("#vNewLongitude").val());
            }else {
                var vLatitude = parseFloat($("#vLatitude").val());
                var vLongitude = parseFloat($("#vLongitude").val());
            }
        }else {
            var vLatitude = parseFloat($("#vLatitude").val());
            var vLongitude = parseFloat($("#vLongitude").val());
        }
        
        if(iGeometryType == '1') {
            //alert('1111');
            $("#PointMap").show();
            $("#PolygonMap").hide();
            $("#PolylineMap").hide();
            (function ($) {
                "use strict";
                /////////////////////////// Marker ///////////////////////////
                function initMap() {
                    var uluru = {lat: vLatitude, lng: vLongitude};
                    // The map, centered at Uluru
                    var map = new google.maps.Map(document.getElementById('PointMap'), {zoom: 17, center: uluru, mapTypeId: 'satellite'});
                    // The marker, positioned at Uluru
                    var marker = new google.maps.Marker({position: uluru, map: map, draggable: true});

                    google.maps.event.addListener(marker, 'dragend', function(marker) {
                        var latLng = marker.latLng;
                        //alert(latLng)
                        var lat = latLng.lat();
                        var lng = latLng.lng();

                        $(".edit_address").removeClass('d-none');
                        $('#vNewLatitude').val(lat);
                        $('#vNewLongitude').val(lng);
                        $('.vNewLatitude').html(lat);
                        $('.vNewLongitude').html(lng);

                        $(".polyarea").addClass('d-none');
                    });
                }
                initMap();
            })(jQuery);
        }
        /*else if(iGeometryType == '2') {
            $("#PointMap").hide();
            $("#PolygonMap").show();
            $("#PolylineMap").hide();
            //alert(mode)
            var pathOldArr = [];
            if(mode == 'Update' && $("#vPolygonLatLong").val() != ""){
                var vPolygonLatLong =  $("#vPolygonLatLong").val();
                var str1 = vPolygonLatLong.replace('POLYGON((','');
                var str2 = str1.replace('))','');
                var str3 = str2.split(",");
                if(str3.length > 0){
                    for(var i = 0; i<str3.length; i++){
                        var str = str3[i].split(" ");
                        pathOldArr.push({lat: parseFloat(str[1]), lng: parseFloat(str[0])});
                    }
                }
                (function ($) {
                    "use strict";
                    function initMapEdit1() {
                        var uluru = {lat: vLatitude, lng: vLongitude};
                        var map = new google.maps.Map(document.getElementById('PolygonMap'), {
                           zoom: 17,
                           center: uluru,
                           mapTypeId: 'satellite'
                        });
                        var PolytoAdd = new google.maps.Polygon({
                          paths: pathOldArr,
                          strokeColor: '#FF0000',
                          strokeOpacity: 0.8,
                          strokeWeight: 2,
                          fillColor: '#FF0000',
                          fillOpacity: 0.35,
                          editable: true
                        });
                        PolytoAdd.setMap(map);

                        var polyArea = google.maps.geometry.spherical.computeArea(PolytoAdd.getPath());
                        var polyacres = (polyArea.toFixed(2)) * parseFloat(0.000247);
                        $("#polyarea").html((polyacres.toFixed(2))+" acre");
                        $(".polyarea").removeClass('d-none');
   
                        
                        if (PolytoAdd) {  
                            //setPolygonCordinates(); 
                            google.maps.event.addListener(PolytoAdd.getPath(), 'set_at', function () {
                                newPolygonArr = new Array();
                                newPolygonArr.length = 0;
                                var curLatLng = PolytoAdd.getPath().getArray();

                                var polyArea = google.maps.geometry.spherical.computeArea(PolytoAdd.getPath());
                                 var polyacres = (polyArea.toFixed(2)) * parseFloat(0.000247);
                                $("#polyarea").html((polyacres.toFixed(2))+" acre");
                                $(".polyarea").removeClass('d-none');
                                //alert(JSON.stringify(curLatLng))
                                newPolygonArr = curLatLng;
                                setPolygonCordinates();
                            });

                            google.maps.event.addListener(PolytoAdd.getPath(), 'insert_at', function () {
                                //alert("insert_at");
                                newPolygonArr = new Array();
                                newPolygonArr.length = 0;
                                var curLatLng = PolytoAdd.getPath().getArray();

                                var polyArea = google.maps.geometry.spherical.computeArea(PolytoAdd.getPath());
                                 var polyacres = (polyArea.toFixed(2)) * parseFloat(0.000247);
                                $("#polyarea").html((polyacres.toFixed(2))+" acre");
                                $(".polyarea").removeClass('d-none');
                                //alert(JSON.stringify(curLatLng))
                                newPolygonArr = curLatLng;
                                setPolygonCordinates();
                            });

                            google.maps.event.addListener(PolytoAdd.getPath(), 'remove_at', function () {
                                //alert("remove_at");
                                newPolygonArr = new Array();
                                newPolygonArr.length = 0;
                                var curLatLng = PolytoAdd.getPath().getArray();
                                
                                var polyArea = google.maps.geometry.spherical.computeArea(PolytoAdd.getPath());
                                 var polyacres = (polyArea.toFixed(2)) * parseFloat(0.000247);
                                $("#polyarea").html((polyacres.toFixed(2))+" acre");
                                $(".polyarea").removeClass('d-none');
                                //alert(JSON.stringify(curLatLng))
                                newPolygonArr = curLatLng;
                                setPolygonCordinates();
                            });
                        }
                    }
                    initMapEdit1();
                })(jQuery);
            }else {
                (function ($) {
                    "use strict";
                    function initMap1() {
                        var uluru = {lat: vLatitude, lng: vLongitude};
                        var map = new google.maps.Map(document.getElementById('PolygonMap'), {
                           zoom: 17,
                           center: uluru,
                           mapTypeId: 'satellite'
                        });

                        var pathArr = [];
                        var sub = 0.0005;
                                            
                        var firstLat , firstLng;
                        firstLat = (parseFloat(vLatitude));
                        firstLng = (parseFloat(vLongitude));
                        pathArr.push({lat: firstLat, lng: firstLng})
                        for (var i = 0; i<1; i++){
                            pathArr.push({lat: (parseFloat(vLatitude) - sub),lng: (parseFloat(vLongitude)-sub)});
                            sub = sub + 0.001;
                        }

                        var add = 0.001;
                        for (var i =0; i<1; i++){
                            pathArr.push({lat: (parseFloat(vLatitude)),lng: (parseFloat(vLongitude)-add)});
                            add = add + 0.001;
                        }

                        pathArr.push({lat: firstLat, lng: firstLng})
                        //alert(JSON.stringify(pathArr))
                        // Construct the polygon.

                        var PolytoAdd = new google.maps.Polygon({
                          paths: pathArr,
                          strokeColor: '#FF0000',
                          strokeOpacity: 0.8,
                          strokeWeight: 2,
                          fillColor: '#FF0000',
                          fillOpacity: 0.35,
                          editable: true
                        });

                        var polyArea = google.maps.geometry.spherical.computeArea(PolytoAdd.getPath());
                        var polyacres = (polyArea.toFixed(2) )* parseFloat(0.000247);
                        $("#polyarea").html((polyacres.toFixed(2))+" acre");
                        $(".polyarea").removeClass('d-none');
                        
                        PolytoAdd.setMap(map);

                        //newPolygonArr = pathArr;
                        //alert(JSON.stringify(newPolygonArr))
                        var polygon_str = '';
                        if(pathArr.length > 0){
                            polygon_str += 'POLYGON((';
                            for(var p = 0; p<pathArr.length; p++){
                                polygon_str += pathArr[p].lng+ " " + pathArr[p].lat;
                                if(p<(pathArr.length-1)){
                                    polygon_str += ',';
                                }
                            }
                            polygon_str += '))';
                        }
                        $("#vPolygonLatLong").val(polygon_str);
                        
                        if (PolytoAdd) {  
                            //setPolygonCordinates(); 
                            google.maps.event.addListener(PolytoAdd.getPath(), 'set_at', function () {
                                newPolygonArr = new Array();
                                newPolygonArr.length = 0;
                                var curLatLng = PolytoAdd.getPath().getArray();

                                var polyArea = google.maps.geometry.spherical.computeArea(PolytoAdd.getPath());
                                 var polyacres = (polyArea.toFixed(2)) * parseFloat(0.000247);
                                $("#polyarea").html((polyacres.toFixed(2))+" acre");
                                $(".polyarea").removeClass('d-none');

                                //alert(JSON.stringify(curLatLng))
                                newPolygonArr = curLatLng;
                                setPolygonCordinates();
                            });

                            google.maps.event.addListener(PolytoAdd.getPath(), 'insert_at', function () {
                                //alert("insert_at");
                                newPolygonArr = new Array();
                                newPolygonArr.length = 0;
                                var curLatLng = PolytoAdd.getPath().getArray();

                                var polyArea = google.maps.geometry.spherical.computeArea(PolytoAdd.getPath());
                                 var polyacres = (polyArea.toFixed(2)) * parseFloat(0.000247);
                                $("#polyarea").html((polyacres.toFixed(2))+" acre");
                                $(".polyarea").removeClass('d-none');

                                //alert(JSON.stringify(curLatLng))
                                newPolygonArr = curLatLng;
                                setPolygonCordinates();
                            });

                            google.maps.event.addListener(PolytoAdd.getPath(), 'remove_at', function () {
                                //alert("remove_at");
                                newPolygonArr = new Array();
                                newPolygonArr.length = 0;
                                var curLatLng = PolytoAdd.getPath().getArray();
                                var polyArea = google.maps.geometry.spherical.computeArea(PolytoAdd.getPath());
                                 var polyacres = (polyArea.toFixed(2)) * parseFloat(0.000247);
                                $("#polyarea").html((polyacres.toFixed(2))+" acre");
                                $(".polyarea").removeClass('d-none');
                                //alert(JSON.stringify(curLatLng))
                                newPolygonArr = curLatLng;
                                setPolygonCordinates();
                            });
                        }

                    }
                    initMap1();
                })(jQuery);
            }            
        }else if(iGeometryType == '3') {
            $("#PointMap").hide();
            $("#PolygonMap").hide();
            $("#PolylineMap").show();
            $(".polyarea").addClass('d-none');
            var pathOldArr = [];
            if(mode == 'Update'){
                var vPolyLineLatLong =  $("#vPolyLineLatLong").val();
                var str1 = vPolyLineLatLong.replace('LINESTRING(','');
                var str2 = str1.replace(')','');
                var str3 = str2.split(",");
                
                if(str3.length > 0){
                    for(var i = 0; i<str3.length; i++){
                        var str = str3[i].split(" ");
                        pathOldArr.push({lat: parseFloat(str[1]), lng: parseFloat(str[0])});
                    }
                }

                (function ($) {
                    "use strict";
                    function initMapEdit2() {
                        var uluru = {lat: vLatitude, lng: vLongitude};
                        var map = new google.maps.Map(document.getElementById('PolylineMap'), {
                           zoom: 17,
                           center: uluru,
                           mapTypeId: 'satellite'
                        });
                        var polyline = new google.maps.Polyline({
                            path: pathOldArr,
                            geodesic: true,
                            strokeColor: '#FF0000',
                            strokeOpacity: 1.0,
                            strokeWeight: 2,
                            editable: true
                        });
                        polyline.setMap(map);
                        if (polyline) {  
                            //setPolygonCordinates(); 
                            google.maps.event.addListener(polyline.getPath(), 'set_at', function () {
                                newPolylineArr = new Array();
                                newPolylineArr.length = 0;
                                var curLatLng = polyline.getPath().getArray();
                                //alert(JSON.stringify(curLatLng))
                                newPolylineArr = curLatLng;
                                setPolylineCordinates();
                            });

                            google.maps.event.addListener(polyline.getPath(), 'insert_at', function () {
                                //alert("insert_at");
                                newPolylineArr = new Array();
                                newPolylineArr.length = 0;
                                var curLatLng = polyline.getPath().getArray();
                                //alert(JSON.stringify(curLatLng))
                                newPolylineArr = curLatLng;
                                setPolylineCordinates();
                            });

                            google.maps.event.addListener(polyline.getPath(), 'remove_at', function () {
                                //alert("remove_at");
                                newPolylineArr = new Array();
                                newPolylineArr.length = 0;
                                var curLatLng = polyline.getPath().getArray();
                                //alert(JSON.stringify(curLatLng))
                                newPolylineArr = curLatLng;
                                setPolylineCordinates();
                            });
                        }

                    }
                    initMapEdit2();
                })(jQuery);
            }else {
                (function ($) {
                    "use strict";
                    function initMap2() {
                        var uluru = {lat: vLatitude, lng: vLongitude};
                        var map = new google.maps.Map(document.getElementById('PolylineMap'), {
                           zoom: 17,
                           center: uluru,
                           mapTypeId: 'satellite'
                        });

                        var pathArr = [];
                        pathArr.push({lat: (parseFloat(vLatitude)),lng: (parseFloat(vLongitude))});
                        var sub = 0.0005;
                        for (var i = 0; i<1; i++){
                            pathArr.push({lat: (parseFloat(vLatitude) - sub),lng: (parseFloat(vLongitude) - sub)});
                            sub = sub + 0.001;
                        }

                        var polyline_str = '';
                        if(pathArr.length > 0){
                            polyline_str += 'LINESTRING(';
                            for(var p = 0; p<pathArr.length; p++){
                                polyline_str += pathArr[p].lng+ " " + pathArr[p].lat;
                                if(p<(pathArr.length-1)){
                                    polyline_str += ',';
                                }
                            }
                            polyline_str += ')';
                        }

                        $("#vPolyLineLatLong").val(polyline_str);
                        // Construct the Polyline.
                        var polyline = new google.maps.Polyline({
                            path: pathArr,
                            geodesic: true,
                            strokeColor: '#FF0000',
                            strokeOpacity: 1.0,
                            strokeWeight: 2,
                            editable: true
                        });
                        polyline.setMap(map);
                        if (polyline) {  
                            //setPolygonCordinates(); 
                            google.maps.event.addListener(polyline.getPath(), 'set_at', function () {
                                newPolylineArr = new Array();
                                newPolylineArr.length = 0;
                                var curLatLng = polyline.getPath().getArray();
                                //alert(JSON.stringify(curLatLng))
                                newPolylineArr = curLatLng;
                                setPolylineCordinates();
                            });

                            google.maps.event.addListener(polyline.getPath(), 'insert_at', function () {
                                //alert("insert_at");
                                newPolylineArr = new Array();
                                newPolylineArr.length = 0;
                                var curLatLng = polyline.getPath().getArray();
                                //alert(JSON.stringify(curLatLng))
                                newPolylineArr = curLatLng;
                                setPolylineCordinates();
                            });

                            google.maps.event.addListener(polyline.getPath(), 'remove_at', function () {
                                //alert("remove_at");
                                newPolylineArr = new Array();
                                newPolylineArr.length = 0;
                                var curLatLng = polyline.getPath().getArray();
                                //alert(JSON.stringify(curLatLng))
                                newPolylineArr = curLatLng;
                                setPolylineCordinates();
                            });
                        }
                    }
                    initMap2();
                })(jQuery);
            }
        }*/
    }
}

function setPolygonCordinates(){
    var polygon_str = '';
    //alert(JSON.stringify(newPolygonArr))
    //alert(newPolygonArr.length)
    if(newPolygonArr.length > 0){
        polygon_str += 'POLYGON((';
        for(var p = 0; p<newPolygonArr.length; p++){
            var str=newPolygonArr[p].toString();
            var str1 = str.replace('(','');
            var str2 = str1.replace(')','');
            //alert(str2)
            var arr = str2.split(", ");
            polygon_str += arr[1]+ " " + arr[0];
            if(p<(newPolygonArr.length-1)){
                polygon_str += ',';
            }
        }
        polygon_str += '))';
    }
    $("#vPolygonLatLong").val(polygon_str);
}

function setPolylineCordinates(){
    var polyline_str = '';
    //alert(JSON.stringify(newPolylineArr))
    //alert(newPolygonArr.length)
    if(newPolylineArr.length > 0){
        polyline_str += 'LINESTRING(';
        for(var p = 0; p<newPolylineArr.length; p++){
            var str=newPolylineArr[p].toString();
            var str1 = str.replace('(','');
            var str2 = str1.replace(')','');
            //alert(str2)
            var arr = str2.split(", ");
            polyline_str += arr[1]+ " " + arr[0];
            if(p<(newPolylineArr.length-1)){
                polyline_str += ',';
            }
        }
        polyline_str += ')';
    }
    $("#vPolyLineLatLong").val(polyline_str);
}
/********************************************************/

(function ($) {
    
        var cluster = new Bloodhound({
          datumTokenizer: function(d) { return d.tokens; },
          queryTokenizer: Bloodhound.tokenizers.whitespace,
          remote: {
            url: site_url+'premise/edit&mode=searchContact',
            replace: function(url, uriEncodedQuery) {
                var newUrl = url + '&vContactName=' + uriEncodedQuery;
                return newUrl;
                },
            filter: function(list) {
                if(list==null)
                    return {};
                else
                    return $.map(list, function(rawdata) { return { display: rawdata.display, id:rawdata.iCId , name:rawdata.name , phone : rawdata.phone }; });
            } 
          }      
        });
        
        cluster.initialize();
        
        select = false;
        $('#search_contact').typeahead({hint: false, highlight: true,minLength: 1 }, 
        {
            displayKey: 'display',
            source: cluster.ttAdapter(),
        })
        .on('typeahead:selected', onClusteSelected)
        .off('blur')
        .blur(function() {
            $(".tt-dropdown-menu").hide();
        });
       
 

})(jQuery);




function onClusteSelected(e, datum){
     var tr_data = '<tr><td id="cont_name_'+datum['id']+'">' + datum['name']+'</td>';
     tr_data += '<td id="cont_phone_'+datum['id']+'">' + datum['phone'] + '</td>';
     tr_data +=  '<td align="center"><input type="hidden" name="iCId[]" value="' + datum['id'] + '">&nbsp;<a class="btn btn-outline-secondary" title="Edit Contact" href="javascript:void(0);" onclick="editContact('+datum['id']+');"><i class="fa fa-edit"></i></a>';
      tr_data += '&nbsp;<a class="btn btn-outline-danger" title="Remove" href="javascript:void(0);" onclick="remove_contact_row(this);"><i class="fa fa-trash"></i></a>';
     tr_data +=  '</td></tr>';
    $('#tbl_contact').append(tr_data);

    $("#search_contact").typeahead('val','');
}

function editContact(contactId){

    if(contactId != ""){
        $.ajax({
            type: "POST",
            url: site_url+'premise/edit&mode=getContactData&iCId='+contactId,
            cache: false,
            success: function(data){
                var response = JSON.parse(data);

                $("#contactfrmadd").removeClass('was-validated');
                $("#cntmodaltitle").html('Edit Contact');
                $("#cnt_mode").val('Update');
           
                $("#siteid").val($("#iSiteId").val());
                $("#referer").val("sitecontactedit");
                $("#cid").val(response[0].iCId);
                $("#salutation").val(response[0].vSalutation);
                $("#firstName").val(response[0].vFirstName);
                $("#lastName").val(response[0].vLastName);
                $("#primaryPhone").val(response[0].vPhone);
                $("#company").val(response[0].vCompany);
                $("#position").val(response[0].vPosition);
                $("#email").val(response[0].vEmail);
                $("#notes").val(response[0].tNotes);

                var status = response[0].iStatus;
                
                if(status == "1"){
                    $("#status").prop('checked',true).change();
                }else if(status == "0"){
                    $("#status").prop('checked',false).change();
                }else{
                    $("#status").prop('checked',false).change();
                }

                 $("#contact_modalbox").trigger('click');
            }
        });
        
    }

}

function remove_contact_row(obj){

    swal({
        title: "Are you sure you want to remove this contact?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: 'confirm btn btn-lg btn-danger',
        cancelButtonClass : 'cancel btn btn-lg btn-default',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: "No, cancel plx!",
        closeOnConfirm: false,
        closeOnCancel: true,
        },
        function(isConfirm) {
            if (isConfirm) {
                 $(obj).closest("tr").remove();
                  swal.close();
            } else {
                swal.close();
            }
        }
    );
}

function clear_serach_contact(){
     $("#search_contact").typeahead('val','');

}
/********************************************************/
$("#btn_site_document").click(function(){

    if($("#vDocumentFile").val() != ""){
        $(this).hide();
        $('#document_loading').show();

        var file_data = $('#vDocumentFile')[0].files[0];
        var form_data = new FormData();
        form_data.append("mode","upload_document");
        form_data.append('vFile', file_data);
        form_data.append('iSiteId', $('#iSiteId').val());
        form_data.append('vTitle', $('#documentTitle').val());

       // return false;
        $.ajax({
            url: site_url+"premise/list",
            data: form_data,
            type: 'POST',
            contentType: false,
            processData: false, 
            cache: false,
            success: function(data){
                $('#document_loading').hide();
                $("#btn_site_document").show();
                // console.log(data)
                response =JSON.parse(data);
                if(response['error'] == "0"){
                    toastr.success(response['file_msg']);
                }else{
                    toastr.error(response['file_msg']);
                }
                if (response['table_row'] != "") {
                    $("#tbl_document").append(response['table_row']);
                    //$("#map_photo_span").html(response['map_photo_span']);
                    $('#documentTitle').val('');
                    $('#vDocumentFile').val('');
                }

                var exif_exit = 0;
                $('#tbl_document').find('tr').each(function(){
                    var  file_exif_gps = $(this).find('input[name="file_exif_gps"]').val();
                    if (file_exif_gps == 1){
                        exif_exit = 1;
                        return;
                    }
                });

                /*if (exif_exit == 1)
                    $('#map_photo_span').show();
                else
                    $('#map_photo_span').hide();*/

            }
        });
    }else {
         toastr.error("Please select document file");
    }
});



function delete_site_document(obj, iSDId, iSiteId) {
     swal({
        title: "Are you sure you want to delete document?",
        text: "",
        type: "warning",
        showCancelButton: true,
        //confirmButtonColor: "#DD6B55",
        confirmButtonClass: 'confirm btn btn-lg btn-danger',
        cancelButtonClass : 'cancel btn btn-lg btn-default',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: "No, cancel plx!",
        closeOnConfirm: false,
        closeOnCancel: true,
        },
        function(isConfirm) {
            if (isConfirm) {
                swal.close();
                 $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: site_url + "premise/list",
                    data: {
                        'mode' : 'delete_site_docoument',
                        'iSDId':iSDId,
                         'iSiteId':iSiteId
                    },
                    success: function (response) {
                        if(response['error'] == "0"){
                            toastr.success(response['msg']);
                            $(obj).closest("tr").remove();
                                var exif_exit = 0;
                                $('#tbl_site_documents').find('tr').each(function(){
                                    var  file_exif_gps = $(this).find('input[name="file_exif_gps"]').val();
                                    if (file_exif_gps == 1){
                                        exif_exit = 1;
                                        return;
                                    }
                                });

                                /*$("#map_photo_span").html(response['map_photo_span']);

                                if (exif_exit == 1)
                                    $('#map_photo_span').show();
                                else
                                    $('#map_photo_span').hide();*/
                        }else{
                            toastr.error(response['msg']);
                        }
                        return false;
                    }
                });
            }
            else{
                swal.close();
            }
        }
    );

    return false;

}