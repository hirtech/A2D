$("#save_data_batch_premises").click(function(){
    $('#save_loading_batch_premises').show();
    $("#save_data_batch_premises").prop('disabled', true);

    var form = $("#frmadd_batch_premises");
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if(isError == 0){
        var data_str = $("#frmadd_batch_premises").serializeArray();
		//alert(JSON.stringify(data_str))
        $.ajax({
            type: "POST",
            url: site_url+"premise/list",
            data: data_str,
            success: function(data){
                $('#save_loading_batch_premises').hide();
                $("#save_data_batch_premises").prop('disabled', false);
                $("#closestbox_batch_premises").trigger('click');
                response =JSON.parse(data);

                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                }else{
                    toastr.error(response['msg']);
                }
				var url = site_url+'/vmap/index&mode=filter_sites&iSiteId='+response['sites'];
					window.open(url);
            }
        });
    }else{
        $('#save_loading_batch_premises').hide();
        $("#save_data_batch_premises").prop('disabled', false);
    }
});

function getSiteSubType(sTypeid){

   $("#iSSMapTypeId").html('<option value="">---Select---</option>');
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
                       
                        option +="<option value='"+response[i]['iSSTypeId']+"'>"+response[i]['vSubTypeName']+"</option>";
                    });
                }
                $("#iSSMapTypeId").html(option);

                $("#iSSMapTypeId").focus();
            }
        });
   }
}
