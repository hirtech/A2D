$(document).ready(function() {
    $('select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
          width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
    });

    var cluster = new Bloodhound({
        datumTokenizer: function(d) { return d.tokens; },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: site_url+'service_order/add&mode=search_premise',
            replace: function(url, uriEncodedQuery) {
                var newUrl = url + '&vPremiseName=' + uriEncodedQuery;
                return newUrl;
            },
            filter: function(list) {
                if(list==null)
                    return {};
                else
                    return $.map(list, function(rawdata) { return { display: rawdata.display, iPremiseId:rawdata.iPremiseId }; });
            } 
        }
    });
    cluster.initialize();
    select = false;
    $('#vPremiseName').typeahead({hint: false, highlight: true,minLength: 1 }, 
    {
        displayKey: 'display',
        source: cluster.ttAdapter(),
    })
    .on('typeahead:selected', onPremiseClusteSelected)
    .off('blur')
    .blur(function() {
        $(".tt-dropdown-menu").hide();
    });

    if(mode == "Update"){
        getMasterMSAFromCarrier($("#iCarrierID").val())
        if($("#iNRCVariable").val() == "" && $("#iMRCFixed").val() == "") {
            setTimeout(function () {
                getNRCMRCValue($("#iService1").val());
            }, 200);
            
        }
    } 
});

function onPremiseClusteSelected(e, datum){
    $("#search_iPremiseId").val(datum['iPremiseId']);
    $("#vPremiseName").val(datum['display']);
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

    if(isError == 0){
        var form_data = new FormData($("#frmadd")[0]);
        $.ajax({
            type: "POST",
            url: site_url+"service_order/list",
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
                setTimeout(function () { location.href = site_url+'service_order/list';}, 3500);
            }
        });
        return false; 
    }else{
        $('#save_loading').hide();   
        $("#save_data").prop('disabled', false);
    }
});

function clear_serach_premise(){
    $("#vPremiseName").typeahead('val','');
    $("#search_iPremiseId").val('');
}

function getMasterMSAFromCarrier(iCarrierID){
    $.ajax({
        type: "POST",
        url: site_url+"service_order/list",
        data: {
            "mode" : "get_master_msa_from_carrier",
            "iCarrierId" : iCarrierID
        },
        success: function(data){
            response =JSON.parse(data);
            $("#vMasterMSA").val(response['vMSANum']);
            $("#vNameId").val(response['vNameId']);
        }
    });
    getSalesRepDropDownFromCarrier(iCarrierID);
    getConnectionTypeDropDownFromCarrier(iCarrierID);
    getServiceTypeDropDownFromCarrier(iCarrierID);
}

function getSalesRepDropDownFromCarrier(iCarrierID){
    $.ajax({
        type: "POST",
        url: site_url+"service_order/list",
        data: {
            "mode" : "get_user_details_from_carrier",
            "iCarrierId" : iCarrierID
        },
        success: function(data){
            response =JSON.parse(data);
            var user_data = response.user_data;

            var option ="<option value=''>--- Select ---</option>";
            var selected = '';

            if(user_data.length > 0 ){
                $.each(user_data,function(i,val){
					if(iSalesRepId == user_data[i].iUserId) {
						selected = "selected";
					}else{
						selected = '';
					}
					//alert(selected)
                    option +="<option value='"+user_data[i].iUserId+"' "+selected+">"+user_data[i].vName+"</option>";
                });
            }
            $("#iSalesRepId").html(option);
        }
    });
}

function getConnectionTypeDropDownFromCarrier(iCarrierID){
    $.ajax({
        type: "POST",
        url: site_url+"service_order/list",
        data: {
            "mode" : "get_connection_type_from_carrier",
            "iCarrierId" : iCarrierID
        },
        success: function(data){
            response =JSON.parse(data);
            var ctype_data = response.ctype_data;

            var option ="<option value=''>--- Select ---</option>";
            var selected = '';

            if(ctype_data.length > 0 ){
                $.each(ctype_data,function(i,val){
                    if(iConnectionTypeId == ctype_data[i].iConnectionTypeId) {
                        selected = "selected";
                    }else{
                        selected = '';
                    }
                    //alert(selected)
                    option +="<option value='"+ctype_data[i].iConnectionTypeId+"' "+selected+">"+ctype_data[i].vConnectionTypeName+"</option>";
                });
            }
            $("#iConnectionTypeId").html(option);
        }
    });
}

function getServiceTypeDropDownFromCarrier(iCarrierID){
    $.ajax({
        type: "POST",
        url: site_url+"service_order/list",
        data: {
            "mode" : "get_service_type_from_carrier",
            "iCarrierId" : iCarrierID
        },
        success: function(data){
            response =JSON.parse(data);
            var stype_data = response.stype_data;

            var option ="<option value=''>--- Select ---</option>";
            var selected = '';

            if(stype_data.length > 0 ){
                $.each(stype_data,function(i,val){
                    if(iService1 == stype_data[i].iServiceTypeId) {
                        selected = "selected";
                    }else{
                        selected = '';
                    }
                    //alert(selected)
                    option +="<option value='"+stype_data[i].iServiceTypeId+"' "+selected+">"+stype_data[i].vServiceType+"</option>";
                });
            }

            $("#iService1").html(option);

        }
    });
}

function getUserDetailsFromUser(iUserId){
    $.ajax({
        type: "POST",
        url: site_url+"service_order/list",
        data: {
            "mode" : "get_user_details_from_user",
            "iUserId" : iUserId
        },
        success: function(data){
            response =JSON.parse(data);
            $("#vSalesRepEmail").val(response['vEmail']);
        }
    });
}

function getNRCMRCValue(iService1){
    $.ajax({
        type: "POST",
        url: site_url+"service_order/list",
        data: {
            "mode" : "get_nrc_mrc_from_service_type",
            "iServiceTypeId" : iService1,
            "iCarrierId" : $("#iCarrierID").val(),
            "iConnectionTypeId" : $("#iConnectionTypeId").val(),
        },
        success: function(data){
            response =JSON.parse(data);
            $("#iNRCVariable").val(response['iNRCVariable']);
            $("#iMRCFixed").val(response['iMRCFixed']);
            if(mode == 'Add'){
                var iNewServiceOrderId = parseInt($("#iLastServicePricingId").val()) + 1;

                var vServiceOrder = $("#vNameId").val() + "-" + response['iServicePricingId'] + "-" + iNewServiceOrderId;
                $("#vServiceOrder").val(vServiceOrder)
            }
        }
    });
}


function addSOValidation(iSOStatus){

    if(iSOStatus == 6) {

        if(sess_vCompanyAccessType != "Carrier"){
            toastr.error("\"Carrier Approved\" status can be only selected by Carrier Users.");
        }else {
            swal({
                title: "Are you sure you want to change the service order status as \"Carrier Approved\" ?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'confirm btn btn-lg btn-danger',
                cancelButtonClass : 'cancel btn btn-lg btn-default',
                confirmButtonText: 'Yes!',
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: true,
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $("#iSOStatus").val(iSOStatus) 
                        swal.close(); 
                    } else {
                        $("#iSOStatus").val("").trigger('change');
                        swal.close();
                    }
                }
            ); 
        }
    }else if(iSOStatus == 7) {
        if(sess_iCompanyId != A2D_COMPANY_ID){
            toastr.error("\"Final Approved\" status can be only selected by A2D Users.");
        }else {
            swal({
                title: "Are you sure you want to change the service order status as \"Final Approved\" ?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'confirm btn btn-lg btn-danger',
                cancelButtonClass : 'cancel btn btn-lg btn-default',
                confirmButtonText: 'Yes!',
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: true,
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $("#iSOStatus").val(iSOStatus);
                        swal.close(); 
                    } else {
                        $("#iSOStatus").val("").trigger('change');
                        swal.close();
                    }
                }
            ); 
        }
    }
}