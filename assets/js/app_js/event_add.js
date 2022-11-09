$(document).ready(function() {
    $('select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
          width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
    });

    getCampaignCovarage(iCampaignBy);
});

function getCampaignCovarage(iCampaignBy) {
    $(".premise_data").hide();
    $(".fiber_zone_data").hide();
    $(".zipcode_data").hide();
    $(".city_data").hide();
    $(".network_data").hide();
    if(iCampaignBy == 1){ // Premise
        $(".premise_data").show();
    }else if(iCampaignBy == 2){ // Fiber zone
        $(".fiber_zone_data").show();
    }else if(iCampaignBy == 3){ // Zipcode
        $(".zipcode_data").show();
    }else if(iCampaignBy == 4){ // city
        $(".city_data").show();
    }else if(iCampaignBy == 5){ // network
        $(".network_data").show();
    }
}

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

    $("#errmsg_iPremiseId").hide();
    $("#errmsg_iZoneId").hide();
    $("#errmsg_iZipcode").hide();
    $("#errmsg_iCityId").hide();
    $("#errmsg_iNetworkId").hide();

    //alert($("#iCampaignBy").val() + " == "+$("#iZoneId").val())
    if($("#iCampaignBy").val() ==  1 && $("#iPremiseId").val() == "") {
        $("#errmsg_iPremiseId").show();
        isError = 1;
    }else if($("#iCampaignBy").val() ==  2 && $("#iZoneId").val() == "") {
        $("#errmsg_iZoneId").show();
        isError = 1;
    }else if($("#iCampaignBy").val() ==  3 && $("#iZipcode").val() == "") {
        $("#errmsg_iZipcode").show();
        isError = 1;
    }else if($("#iCampaignBy").val() ==  4 && $("#iCityId").val() == "") {
        $("#errmsg_iCityId").show();
        isError = 1;
    }else if($("#iCampaignBy").val() ==  5 && $("#iNetworkId").val() == "") {
        $("#errmsg_iNetworkId").show();
        isError = 1;
    }
    //alert(isError)
    if(isError == 0){
        var form_data = new FormData($("#frmadd")[0]);
        $.ajax({
            type: "POST",
            url: site_url+"event/event_list",
            data: form_data,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data){
                $('#save_loading').hide();   
                $("#save_data").prop('disabled', false);
                response =JSON.parse(data);
                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                }else{
                    toastr.error(response['msg']);
                }
                setTimeout(function () { location.href = site_url+'event/event_list';}, 3500);
            }
        });
        return false; 
    }else{
        $('#save_loading').hide();   
        $("#save_data").prop('disabled', false);
    }
});
