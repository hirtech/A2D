function startPremiseServices(iPremiseId, iServiceTypeId,mode){
    $("#frmadd").removeClass('was-validated');
    $("#stmodaltitle").html('Start Premise Service - <span class="text-primary">'+vPremiseName+' # '+iPremiseId+'</span>');
    $("#st_mode").val('Start');
    $("#iServiceTypeId").val(iServiceTypeId);
    $("#iWOId").val('');
    $("#iServiceOrderId").val('');
    $("#vServiceOrder").val('');
    $("#iCarrierId").val('');
    $("#vCarrierName").val('');
    $("#iPremiseCircuitId").val('');
    $("#iNRCVariable").val('');
    $("#iMRCFixed").val('');
    
    $("#premise_services_start").trigger('click');
}

function suspendPremiseServices(iPremiseId, iServiceTypeId,mode){
    $("#frmadd").removeClass('was-validated');
    $("#stmodaltitlesuspend").html('Suspend Premise Service - <span class="text-primary">'+vPremiseName+' # '+iPremiseId+'</span>');
    $("#st_modesuspend").val('Suspend');
    $("#iSuspendServiceTypeId").val(iServiceTypeId);
    $("#iWOId").val('');
    $("#iSuspendServiceOrderId").val('');
    $("#vSuspendServiceOrder").val('');
    $("#iSuspendCarrierId").val('');
    $("#vSuspendCarrierName").val('');
    $("#iSuspendPremiseCircuitId").val('');
    
    $("#premise_services_suspend").trigger('click');
}

function getServiceOrder(iWOId,mode){
    var iServiceTypeId = $("#iServiceTypeId").val();
    $.ajax({
        type: "POST",
        url: site_url+"premise/setup_premise_services_list",
        data: {
            "mode": "getServiceOrder",
            "iWOId": iWOId,
            "iServiceTypeId": iServiceTypeId,
        },
        success: function(data){
            response =JSON.parse(data);
            var iServiceOrderId = response.data[0].iServiceOrderId;
            var vServiceOrder = response.data[0].vServiceOrder;
            var iCarrierId = response.data[0].iCarrierId;
            var vCarrierName = response.data[0].vCarrierName;
            var iNRCVariable = response.data[0].iNRCVariable;
            var iMRCFixed = response.data[0].iMRCFixed;

            if(mode == "start"){
                $('#iServiceOrderId').val(iServiceOrderId);
                $('#vServiceOrder').val(vServiceOrder);
                $('#iCarrierId').val(iCarrierId);
                $('#vCarrierName').val(vCarrierName);
                $('#iNRCVariable').val(iNRCVariable);
                $('#iMRCFixed').val(iMRCFixed);
            }else if(mode == "suspend"){
                $('#iSuspendServiceOrderId').val(iServiceOrderId);
                $('#vSuspendServiceOrder').val(vServiceOrder);
                $('#iSuspendCarrierId').val(iCarrierId);
                $('#vSuspendCarrierName').val(vCarrierName);
            }
        }
    });
}


$("#save_premise_services_start_data").click(function(){
    $('#save_premise_services_start_loading').show();
    $("#save_premise_services_start_data").prop('disabled', true);

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
        var data_str = $("#frmadd").serializeArray();
        $.ajax({
            type: "POST",
            url: site_url+"premise/setup_premise_services_list",
            data: data_str,
            success: function(data){
                $('#save_premise_services_start_loading').hide();
                $("#save_premise_services_start_data").prop('disabled', false);
                
                $("#closestbox").trigger('click');
                response =JSON.parse(data);
                var premiseId = response['iPremiseId'];
                //alert(premiseId)
                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                }else{
                    toastr.error(response['msg']);
                }
                setTimeout(function () { location.href = site_url+'premise/setup_premise_services_list&iPremiseId='+premiseId;}, 3500);
            }
        });
    }else{
        $('#save_premise_services_start_loading').hide();   
        $("#save_premise_services_start_data").prop('disabled', false);
    }
});

$("#save_premise_services_suspend_data").click(function(){
    $('#save_premise_services_suspend_loading').show();
    $("#save_premise_services_suspend_data").prop('disabled', true);

    var form = $("#frmsuspendadd")
    //alert(form[0].checkValidity())
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if(isError == 0){
        var data_str = $("#frmsuspendadd").serializeArray();
        $.ajax({
            type: "POST",
            url: site_url+"premise/setup_premise_services_list",
            data: data_str,
            success: function(data){
                $('#save_premise_services_suspend_loading').hide();
                $("#save_premise_services_suspend_data").prop('disabled', false);
                
                $("#suspendclosestbox").trigger('click');
                response =JSON.parse(data);
                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                }else{
                    toastr.error(response['msg']);
                }
                // gridtable.ajax.reload();
            }
        });
    }else{
        $('#save_premise_services_suspend_loading').hide();   
        $("#save_premise_services_suspend_data").prop('disabled', false);
    }
});