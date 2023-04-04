$(document).ready(function() {
    $('select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
          width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
    });

    getPremiseCircuitData(iPremiseId);

    var cluster = new Bloodhound({
        datumTokenizer: function(d) { return d.tokens; },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: site_url+'service_order/equipment_add&mode=search_premise',
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
});

function onPremiseClusteSelected(e, datum){
    $("#search_iPremiseId").val(datum['iPremiseId']);
    $("#vPremiseName").val(datum['display']);
    getPremiseCircuitData(datum['iPremiseId']);
}

function getPremiseCircuitData(iPremiseId) {
    $("#iPremiseCircuitId").html('<option value="">Select</option>');
    if(iPremiseId != ""){
        $.ajax({
            type: "POST",
            url: site_url+"service_order/equipment_add",
            data: {
                "mode" : "getPremiseCircuitData",
                "iPremiseId" : iPremiseId
            },
            success: function(data){
                response =JSON.parse(data);
                var option ="<option value=''>Select</option>";
                if(response.length > 0 ){
                    $.each(response,function(i,val){
                        var selected = '';
                        //alert(iPremiseCircuitId)
                        if(iPremiseCircuitId == response[i]['iPremiseCircuitId']){
                            selected = ' selected';
                        }
                        option +="<option value='"+response[i]['iPremiseCircuitId']+"'"+selected+">"+response[i]['vPremiseDisplay']+"</option>";
                    });
                }
                $("#iPremiseCircuitId").html(option);

                $("#iPremiseCircuitId").focus();
            }
        });
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

    if(isError == 0){
        var form_data = new FormData($("#frmadd")[0]);
        $.ajax({
            type: "POST",
            url: site_url+"service_order/equipment_list",
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
                setTimeout(function () { location.href = site_url+'service_order/equipment_list';}, 3500);
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

function delete_file(id){
   // alert('delete')
    swal({
        title: "Are you sure you want to delete document ?",
        text: "",
        type: "warning",
        showCancelButton: true,
        //confirmButtonColor: "#DD6B55",
        confirmButtonClass: 'confirm btn btn-lg btn-danger',
        cancelButtonClass : 'cancel btn btn-lg btn-default',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: "No, cancel plx!",
        closeOnConfirm: false,
        closeOnCancel: true,
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    url: site_url+"service_order/equipment_list",
                    data: {
                        "mode" : "delete_document",
                        "iEquipmentId" : id,
                    },
                    success: function(data){
                        swal.close();
                        response =JSON.parse(data);
                        if(response['error'] == "0"){
                            toastr.success(response['msg']);
                        }else{
                            toastr.error(response['msg']);
                        }
                        setTimeout(function () { location.href = site_url+'service_order/equipment_add&mode=Update&iEquipmentId='+response['iEquipmentId'];}, 3500);
                    }
                });
            } else {
                swal.close();
            }
        }
    );
}
