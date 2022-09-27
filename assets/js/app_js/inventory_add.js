function addEditInvCountData(id,mode){
    $("#frmadd_invcount").removeClass('was-validated');
    
        if(mode == "editInvCount"){
            $("#invcount_modaltitle").html('Edit Inventory');
            $("#invcount_mode").val('Update');
            $("#invcount_iICId").val(id);
            
            $("#invcount_iTPId").select2().val($("#invcount_iTPId_"+id).val()).trigger('change'); 
            //$("#invcount_iTPId").select2("readonly", true);
            $("#material_div").hide();
            $("#invcount_rqty").val($("#invcount_rQty_"+id).val());
            $("#invcount_dDate").val($("#invcount_dDate_"+id).val()).attr('readonly',true);
        }else{
            $("#invcount_modaltitle").html('Add Inventory');
            $("#invcount_mode").val('Add');
            $("#invcount_iICId").val('');
            $("#invcount_iTPId").select2().val('').trigger('change'); 
            $("#invcount_rqty").val('');
            $("#invcount_dDate").val(dDate);
        }
    $("#inv_count_box").trigger('click');
}


$("#invcount_save_data").click(function(){
    $('#save_loading').show();
    $("#invcount_save_data").prop('disabled', true);
    var form = $("#frmadd_invcount")
    //alert(form[0].checkValidity())
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if(isError == 0){
        var data_str = $("#frmadd_invcount").serializeArray();
       // console.log(data_str);
        $.ajax({
            type: "POST",
            url: site_url+"inventory/inventory_list",
            data: data_str,
            success: function(data){
                $('#save_loading').hide();
                $("#invcount_save_data").prop('disabled', false);
                
                $("#invcount_closestbox").trigger('click');
                response =JSON.parse(data);
                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                }else{
                    toastr.error(response['msg']);
                }
                gridtable.ajax.reload();
            }
        });
    }else{
        $('#save_loading').hide();   
        $("#invcount_save_data").prop('disabled', false);
    }
});