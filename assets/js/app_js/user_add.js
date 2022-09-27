function getCountyFromState(obj, iCountyId, iCityId) {
    if (iCountyId == "" || iCountyId == "undefined")
        iCountyId = 0;

    $('#iCountyId').after('<img src="assets/images/loading-small.gif" border="0" class="loading">');
    $.ajax({
        type: "POST",
        dataType: "json",
        url: site_url + "user/list",
        data: 'mode=GetCountyFromState&pageMode=' + $("#mode").val() + '&iStateId=' + $(obj).val() + '&iCountyId=' + iCountyId,
        success: function (data) {
            $('#iCountyId').next().remove();
            {
                $('#iCountyId').html(data.county);
                getCityFromCounty($('#iCountyId'), iCityId);
            }
        }
    });
}

function getCityFromCounty(obj, iCityId) {
    if (iCityId == "" || iCityId == "undefined"){
        iCityId = 0;
    }

    $('#iCityId').after('<img src="assets/images/loading-small.gif" border="0" class="loading">');
    $.ajax({
        type: "POST",
        dataType: "json",
        url: site_url + "user/list",
        data: 'mode=GetCityFromCounty&pageMode=' + $("#mode").val() + '&iCountyId=' + $(obj).val() + '&iCityId=' + iCityId,
        success: function (data) {
            $('#iCityId').next().remove();
            $('#iCityId').html(data.city);
        }
    });
}

function checkDuplicateUser() {
    var vUsername = Trim($('#vUsername').val());
    $.ajax({
        type: "POST",
        dataType: "json",
        url: site_url + "user/list",
        data: "mode=DuplicateUsernameCheck&vUsername=" + vUsername,
        cache: false,
        success: function (data) {
            if (data.total == 1) {
                $('#duplicate_msg').html("Username already exist.").show();
                //$('#vUsername').focus();
                //return false;
            }
            else {
                $('#duplicate_msg').html("").hide();
            }
        }
    });
}

$("#save_data").click(function(){
    $('#save_loading').show();   
    $("#save_data").prop('disabled', true);
   // console.log('111');
  // console.log($('#groupaction').attr());
   
   var form = $("#frmadd")
    //alert(form[0].checkValidity())
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if(isError == 0){

        if($("#vPassword").val() != ""){
            if($("#vPassword").val() != $("#vConfPassword").val()){
                $("#conf_psw_msg").html("Confirm password doesn't match with password").show();
                $("#vConfPassword").focus();
                $('#save_loading').hide();   
                $("#save_data").prop('disabled', false);
                return false;
            }
        }

            $("#conf_psw_msg").html("");
// console.log($('#vImage')[0].files[0]);
        var imagedata = $('#vImage')[0].files[0];
        var form_data = new FormData();
        //var form_data1 = $('#frmadd').serializeArray();
        
            form_data.append("mode","Update");
         form_data.append("vImage",imagedata);
         
            form_data.append("iDepartmentId",$('[name="iDepartmentId[]"]').val());
         form_data.append("iAGroupId",$('[name="iAGroupId"]').val());
         form_data.append("groupaction",$('[name="groupaction"]').val());
         form_data.append("mode",$('[name="mode"]').val());
         form_data.append("vCountry",$('[name="vCountry"]').val());
         form_data.append("iUserId",$('[name="iUserId"]').val());
         form_data.append("vAddress1",$('[name="vAddress1"]').val());
         form_data.append("vAddress2",$('[name="vAddress2"]').val());
         form_data.append("vStreet",$('[name="vStreet"]').val());
         form_data.append("vCrossStreet",$('[name="vCrossStreet"]').val());
         form_data.append("iZipcode",$('[name="iZipcode"]').val());
         form_data.append("iStateId",$('[name="iStateId"]').val());
         form_data.append("iCountyId",$('[name="iCountyId"]').val());
         form_data.append("iCityId",$('[name="iCityId"]').val());
         form_data.append("zoneId_arr",$('[name="zoneId_arr[]"]').val());
         form_data.append("vLatitude",$('[name="vLatitude"]').val());
         form_data.append("vLongitude",$('[name="vLongitude"]').val());
         form_data.append("vFirstName",$('[name="vFirstName"]').val());
         form_data.append("vLastName",$('[name="vLastName"]').val());
         form_data.append("vUsername",$('[name="vUsername"]').val());
         form_data.append("vPassword",$('[name="vPassword"]').val());

         form_data.append("vConfPassword",$('[name="vConfPassword"]').val());
         form_data.append("vEmail",$('[name="vEmail"]').val());
         form_data.append("iStatus",$('[name="iStatus"]').val());
         form_data.append("vCompanyName",$('[name="vCompanyName"]').val());
         form_data.append("autofilladdress",$('[name="autofilladdress"]').val());
         form_data.append("vPhone",$('[name="vPhone"]').val());
         form_data.append("vCell",$('[name="vCell"]').val());
         form_data.append("vFax",$('[name="vFax"]').val());
         form_data.append("vImage_old",$('[name="vImage_old"]').val());
         form_data.append("iType",$("input[name='iType']:checked").val());
         //console.log(form_data);
         //console.log($('#groupaction').val());
//console.log(form_data)
        // var form_data = new FormData($('#frmadd')[0]);   
           // var fd = new FormData();
           // var image_data = $('#vImage')[0].files[0]; // for multiple files
            //
             //   fd.append("vImage", image_data);
            
            /*var other_data = $('#frmadd').serializeArray();
            $.each(other_data,function(key,input){
                fd.append(input.name,input.value);
            });*/
           // var data = $('#frmadd').serializeArray();
           // form_data.push({name: 'imagedata', value: imagedata});
            /*$.each(data,function(key,input){
                            console.log(input.name+'=>'+input.value);
                        });*/
                        console.log(form_data);

            $.ajax({
                type: "POST",
                dataType: "json",
                url: site_url + "user/list",
                data: form_data,
                processData: false,
                contentType: false,
              //  cache: false,
                success: function (response) {
                    $('#save_loading').hide();   
                    $("#save_data").prop('disabled', false);

                    if(typeof response.duplicate_check_tot != "undefined" && response.duplicate_check_tot != 0 ){
                        toastr.error("Username already exist");
                    }else{

                        if(response['error'] == "0"){
                            toastr.success(response['msg']);
                        }else{
                            toastr.error(response['msg']);
                        }
                        setTimeout(function () {
                                location.href = site_url+'user/list';
                            }, 3500);
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    //alert('Nastala chyba. ' + errorThrown);
                }
            });
            return false;         
    }else{
        $('#save_loading').hide();   
        $("#save_data").prop('disabled', false);
    }
});

function clear_address() {
    $('#autofilladdress').val('');
    $('#autofilladdress').focus();
    $(".address-details").hide();
    $(".clear_address").hide();
    $('#iZoneId').val('');
    $('#vLatitude').val('');
    $('#vLongitude').val('');
    $('#vAddress1').val('');
    $('#vStreet').val('');
    $('#iStateId').val('');
    $('#iCountyId').val('');
    $('#iCityId').val('');
    $('#iZipcode').val('');

}