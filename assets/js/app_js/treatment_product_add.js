function addeditTreatmentProdData(id,mode){
    $("#frmadd").removeClass('was-validated');
    $("#errmsg_apprate").html('');
        $("#errmsg_apprate").hide();

    //alert(mode)
    if(mode == "edit"){
       // alert(id)
        $("#modal_title").html('Edit Treatment Product');
        $("#modal_mode").val('Update');
        $("#modal_iTPId").val($("#tp_iTPId_"+id).val());
        $("#modal_vName").val($("#tp_vName_"+id).val());
        $("#modal_vCategory").val($("#tp_vCategory_"+id).val());
        $("#modal_vClass").val($("#tp_vClass_"+id).val());
        var pesticide = $("#tp_iPesticide_"+id).val(); 
        if (pesticide == "Y"){
            $("#modal_iPesticideY").prop('checked',true);
        }else{
            $("#modal_iPesticideN").prop('checked',true);
        }
        $("#modal_vEPARegNo").val($("#tp_vEPARegNo_"+id).val());
        $("#modal_vActiveIngredient").val($("#tp_vActiveIngredient_"+id).val());
        $("#modal_vAI").val($("#tp_vAI_"+id).val());
        $("#modal_vActiveIngredient2").val($("#tp_vActiveIngredient2_"+id).val());
        $("#modal_vAI2").val($("#tp_vAI2_"+id).val());
        $("#modal_iUId").val($("#tp_iUId_"+id).val());
        $("#modal_vAppRate").val($("#tp_vAppRate_"+id).val());
        $("#modal_vTragetAppRate").val($("#tp_vTragetAppRate_"+id).val());
        $("#modal_vMinAppRate").val($("#tp_vMinAppRate_"+id).val());
        $("#modal_vMaxAppRate").val($("#tp_vMaxAppRate_"+id).val());
        var status = $("#tp_iStatus_"+id).val();        
        if(status == "Active"){
            $("#modal_iStatus").prop('checked',true).change();
        }else if(status == "Inactive"){
            $("#modal_iStatus").prop('checked',false).change();
        }else{
            $("#modal_iStatus").prop('checked',false).change();
        }
    }else{
      
        $("#modal_title").html('Add Treatment Product');
        $("#modal_mode").val('Add');
        $("#modal_iTPId").val('');
        $("#modal_vName").val('');
        $("#modal_vCategory").val('');
        $("#modal_vClass").val('');
        $("#modal_iPesticideY").prop('checked',true);
        $("#modal_vEPARegNo").val('');
        $("#modal_vActiveIngredient").val('');
        $("#modal_vAI").val('');
        $("#modal_vActiveIngredient2").val('');
        $("#modal_vAI2").val('');
        //$("#modal_iUId").val('');
        $("#modal_iUId").prop("selectedIndex",0).val();
        $("#modal_vAppRate").val('');
        $("#modal_vTragetAppRate").prop("selectedIndex",0).val();
        $("#modal_vMinAppRate").val('');
        $("#modal_vMaxAppRate").val('');

          $("#modal_iStatus").prop('checked',true).change();
    }
    $("#trprod_box").trigger('click');
}


$("#save_data").click(function(){
    $('#save_loading').show();
    $("#save_data").prop('disabled', true);
    var form = $("#frmadd");
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if($("#modal_vAppRate").val() == ""){
        $("#errmsg_apprate").html('Please enter target application rate');
        $("#errmsg_apprate").show();
        $('#save_loading').hide();
        isError = 1;
    }else{
        $("#errmsg_apprate").html('');
        $("#errmsg_apprate").hide();
    }

    if(isError == 0){
        var data_str = $("#frmadd").serializeArray();
        $.ajax({
            type: "POST",
            url: site_url+"master/treatment_product_list",
            data: data_str,
            success: function(data){
                $('#save_loading').hide();   
                $("#save_data").prop('disabled', false);

                response =JSON.parse(data);
                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                }else{
                    toastr.error(response['msg']);
                }

                $("#closestbox").trigger('click');
                gridtable.ajax.reload();
                /*setTimeout(function () {
                    $("#closestbox").trigger('click');
                }, 3500);*/
            }
        });
    }else{
        $('#save_loading').hide();   
        $("#save_data").prop('disabled', false);
    }

});