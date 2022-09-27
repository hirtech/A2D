
function checkDuplicateUser() {
    var vUsername = Trim($('#vUsername').val());
    $.ajax({
        type: "POST",
        dataType: "json",
        url: site_url + "dashboard/editprofile",
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
    //$('#save_data').html(' Save <img src="assets/images/loading-small.gif" border="0" class="loading">');
   $('#save_loading').show();
   // console.log('111');
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
                //toastr.error("Confirm password doesn't match with password");
                $("#vConfPassword").focus();
                // $('#save_data').html(' Save ');
                $('#save_loading').hide();
                return false;
            }
        }
        //else{
            $("#conf_psw_msg").html("");

            var form_data = $('#frmadd').serializeArray();
            console.log(form_data);
            // return false;
            $.ajax({
                type: "POST",
                dataType: "json",
                url: site_url + "dashboard/editprofile",
                data: form_data,
                cache: false,
                success: function (response) {
                    // $('#save_data').html(' Save ');
                    $('#save_loading').hide();
                   //console.log(response);
                    //response =JSON.parse(data);
                    if(typeof response.duplicate_check_tot != "undefined" && response.duplicate_check_tot != 0 ){
                        toastr.error("Username already exist");
                    }else{

                        if(response['error'] == "0"){
                            toastr.success(response['msg']);
                        }else{
                            toastr.error(response['msg']);
                        }
                        setTimeout(function () {
                                location.href = site_url+'dashboard/editprofile';
                            }, 3500);
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    //alert('Nastala chyba. ' + errorThrown);
                }
            });
            return false; 
        //}
        
    }else{
        $('#save_loading').hide();
    }
});