
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

    if(isError == 0){
        
       //var form_data = $("#frmadd").serializeArray();
       var form_data = new FormData($("#frmadd")[0]);
       //console.log(form_data);

        $.ajax({
            type: "POST",
            url: site_url+"network/network_list",
            data: form_data,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data){
                $('#save_loading').hide();   
                $("#save_data").prop('disabled', false);
                // console.log(data)
                response =JSON.parse(data);
                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                }else{
                    toastr.error(response['msg']);
                }
                setTimeout(function () { location.href = site_url+'network/network_list';}, 3500);
            }
        });
        return false; 
    }else{
        $('#save_loading').hide();   
        $("#save_data").prop('disabled', false);
    }
});


function initMap() {
    var maplat = parseFloat(MAP_LATITUDE);
    var maplng = parseFloat(MAP_LONGITUDE);

    map = new google.maps.Map(document.getElementById('map'), {
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
        console.log('load');
        if(this.loaded){return;}
            this.loaded = true;
        onMapLoad();
    }); 
    map.setCenter(new google.maps.LatLng(maplat, maplng));
   
}


function onMapLoad(){
    //KML Layer
    var iNetworkId = $('#iNetworkId').val();
    
    if(iNetworkId > 0){
        $.ajax({
            url: site_url+'network/network_list',
            type: 'POST',
            data: 'mode=network_map&iNetworkId='+iNetworkId,
            success: function(data){
                res = JSON.parse(data);
                   
                if(jQuery.isEmptyObject(res) == false){
                    //var json_kml = res.rs_data;
                    var json_kml = res;
                    var len = json_kml.length;
                            
                    if(len > 0){  
                        //var src = 'http://butte.vectorcontrolsystem.com/storage/kml/6/1606197216_1516332155_organic.kml';
                        var src = json_kml[0]['vFilePath'];
                        /*var kmlOptions = {
                            suppressInfoWindows: true,
                            preserveViewport: true,
                            map: map
                        };
                        var kmlLayer = new google.maps.KmlLayer(src, kmlOptions);*/
						var ctaLayer = new google.maps.KmlLayer({
                            url: src,
                            suppressInfoWindows: true,  
                            map:map,
                            zindex: 0,
                            clickable : false
                        }); 
                    }
                }  
            }
        }); 
    }
}

