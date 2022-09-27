(function ($) {
    "use strict";
    /////////////////////////// Marker ///////////////////////////
    function initMap() {
        var uluru = {lat: 26.642487, lng: -81.709854};
        // The map, centered at Uluru
        var map = new google.maps.Map(document.getElementById('marker'), {zoom: 10, center: uluru});
        // The marker, positioned at Uluru
        var marker = new google.maps.Marker({position: uluru, map: map});
    }
    initMap();
})(jQuery);