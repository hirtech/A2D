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
    
    //alert(isError)
    if(isError == 0){
        var form_data = new FormData($("#frmadd")[0]);
        $.ajax({
            type: "POST",
            url: site_url+"invoice/invoice_list",
            data: form_data,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data){
                $('#save_loading').hide();   
                $("#save_data").prop('disabled', false);
                response =JSON.parse(data);
                // console.log(response);
                // return false;
                if(response['error'] == "0"){
                    // setTimeout(function () { location.href = site_url+'invoice/invoice_list';}, 3500);
                    // toastr.success(response['msg']);
                    location.href = site_url+'invoice/invoice_list';
                    setTimeout(function () {toastr.success(response['msg']); }, 3500);
                    
                }
                else{
                    toastr.error(response['msg']);
                    setTimeout(function () { location.href = site_url+'invoice/invoice_list';}, 3500);
                }
                
            }
        });
        return false; 
    }else{
        $('#save_loading').hide();   
        $("#save_data").prop('disabled', false);
    }
});
