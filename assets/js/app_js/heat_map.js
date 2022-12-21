var heatmap;
var heatmap_arr = [];

$("#create_heat_map").click(function(){
   var form = $("#frmadd");
   var isError = 0;
   if (form[0].checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
      isError = 1;
   }
   
   form.addClass('was-validated');
   if(isError == 0){

      var form_data = $("#frmadd").serializeArray();
      var data_list = new Array();
      $('#heat_map_save_loading').show();
      $.ajax({
            type: "POST",
            url: site_url+"reports/heat_map",
            data: form_data,
            cache: false,
            dataType: 'json',
            success: function(response){
               $('#heat_map_save_loading').hide();
               console.log(response);
               // console.log("tets");
               heatmap_arr = $.map(response, function (el) {
                  return el;
               });
               //console.log(heatmap_arr);
               initMap();
               $("#floating-panel").show();
            }
      });
   }
   return false; 
});
    
function initMap() {
   var arr = [];
   for(var h=0; h<heatmap_arr.length; h++) {
      if(heatmap_arr[h].vLatitude != null && heatmap_arr[h].vLongitude != null){
         arr[h] = new google.maps.LatLng(parseFloat(heatmap_arr[h].vLatitude), parseFloat(heatmap_arr[h].vLongitude));
      }
   }
   //alert(JSON.stringify(arr));
   //alert(MAP_LATITUDE)
   map = new google.maps.Map(document.getElementById("heatmap"), {
      center: {
         lat: parseFloat(MAP_LATITUDE),
         lng: parseFloat(MAP_LONGITUDE)
      },
      zoom: 10,
      navigationControl: true,
      mapTypeControl: true,
      scaleControl: true,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      zoomControl: true,
      zoomControlOptions: {
         position: google.maps.ControlPosition.LEFT_TOP
      },
      streetViewControl: true,
      streetViewControlOptions: {
         position: google.maps.ControlPosition.LEFT_TOP
      }
      //mapTypeId: "satellite"
   });
   heatmap = new google.maps.visualization.HeatmapLayer({
      data: arr,
      map: map
   });
}

function toggleHeatmap() {
   heatmap.setMap(heatmap.getMap() ? null : map);
}

function changeGradient() {
   var gradient = [
      "rgba(0, 255, 255, 0)",
      "rgba(0, 255, 255, 1)",
      "rgba(0, 191, 255, 1)",
      "rgba(0, 127, 255, 1)",
      "rgba(0, 63, 255, 1)",
      "rgba(0, 0, 255, 1)",
      "rgba(0, 0, 223, 1)",
      "rgba(0, 0, 191, 1)",
      "rgba(0, 0, 159, 1)",
      "rgba(0, 0, 127, 1)",
      "rgba(63, 0, 91, 1)",
      "rgba(127, 0, 63, 1)",
      "rgba(191, 0, 31, 1)",
      "rgba(255, 0, 0, 1)"
   ];
   heatmap.set("gradient", heatmap.get("gradient") ? null : gradient);
}

function changeRadius() {
   heatmap.set("radius", heatmap.get("radius") ? null : 20);
}

function changeOpacity() {
   heatmap.set("opacity", heatmap.get("opacity") ? null : 0.2);
} // Heatmap data: 500 Points
  