$(document).ready(function() {
    $('select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
          width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
    });

    var clusterp = new Bloodhound({
        datumTokenizer: function(d) { return d.tokens; },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: site_url+'premise_circuit/premise_circuit_add&mode=search_premise',
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
    clusterp.initialize();
    select = false;
    $('#vPremiseName').typeahead({hint: false, highlight: true,minLength: 1 }, 
    {
        displayKey: 'display',
        source: clusterp.ttAdapter(),
    })
    .on('typeahead:selected', onPremiseClusteSelected)
    .off('blur')
    .blur(function() {
        $(".tt-dropdown-menu").hide();
    });
    
    var cluster = new Bloodhound({
        datumTokenizer: function(d) { return d.tokens; },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: site_url+'premise_circuit/premise_circuit_add?mode=search_workorder',
            replace: function(url, uriEncodedQuery) {
                var newUrl = url + '&vWorkOrder=' + uriEncodedQuery+'&iPremiseId=' + $("#search_iPremiseId").val();
                return newUrl;
            },
            filter: function(list) {
                if(list==null)
                    return {};
                else
                    return $.map(list, function(rawdata) { return { display: rawdata.display, iWOId:rawdata.iWOId }; });
            } 
        }
    });
    cluster.initialize();
    select = false;
    $('#vWorkOrder').typeahead({hint: false, highlight: true,minLength: 1 }, 
    {
        displayKey: 'display',
        source: cluster.ttAdapter(),
    })
    .on('typeahead:selected', onWorkOrderClusteSelected)
    .off('blur')
    .blur(function() {
        $(".tt-dropdown-menu").hide();
    });
});

function onPremiseClusteSelected(e, datum){
    $("#search_iPremiseId").val(datum['iPremiseId']);
    $("#vPremiseName").val(datum['display']);
    $("#search_iWOId").val('');
    $("#vWorkOrder").val('');
}

function onWorkOrderClusteSelected(e, datum){
    $("#search_iWOId").val(datum['iWOId']);
    $("#vWorkOrder").val(datum['display']);
}

function clear_serach_premise(){
    $("#vPremiseName").typeahead('val','');
    $("#search_iPremiseId").val('');
}
function clear_serach_workorder(){
    $("#vWorkOrder").typeahead('val','');
    $("#search_iWOId").val('');
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

    if(isError == 0){
        var form_data = new FormData($("#frmadd")[0]);
        $.ajax({
            type: "POST",
            url: site_url+"premise_circuit/premise_circuit_list",
            data: form_data,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data){
                $('#save_loading').hide();   
                $("#save_data").prop('disabled', false);
                response =JSON.parse(data);
                if(response['error'] == "1"){
                    var zone_msg = "";
                    var confirm_Button_Class = "";
                    var iPremiseCircuitId = response['iPremiseCircuitId'];
                    if(response['matching_network'] == 0){
                        zone_msg = "The network from the workorder/premise do not match with the network of the circuit.";
                        confirm_Button_Class = "confirm btn btn-lg btn-danger";
                        swal({
                            title: zone_msg,
                            text: "",
                            type: "info",
                            confirmButtonClass: confirm_Button_Class,
                            confirmButtonText: 'Okay',
                            closeOnConfirm: false,
                            },
                            function(isConfirm) {
                                swal.close();
                                toastr.error(response['msg']);
                                setTimeout(function () { location.href = site_url+'premise_circuit/premise_circuit_list';}, 3500);
                            }
                        );
                    }
                }else{
                    if($("#iStatus").val() == 4 || $("#iStatus").val() == 5) {
                        var premise_id = response['iPremiseId'];
                        var msg = "Congratulations - Premise status for ID "+premise_id+" is now On-Net";
                        swal({
                            title: msg,
                            text: "",
                            type: "success",
                            confirmButtonClass: confirm_Button_Class,
                            confirmButtonText: 'Okay',
                            closeOnConfirm: false,
                            },
                            function(isConfirm) {
                                swal.close();
                                toastr.success(response['msg']);
                                setTimeout(function () { location.href = site_url+'premise_circuit/premise_circuit_list';}, 3500);
                            }
                        );
                    }else {
                        toastr.success(response['msg']);
                        setTimeout(function () { location.href = site_url+'premise_circuit/premise_circuit_list';}, 3500);
                    }
                }
            }
        });
        return false; 
    }else{
        $('#save_loading').hide();   
        $("#save_data").prop('disabled', false);
    }
});

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
                    url: site_url+"premise_circuit/premise_circuit_list",
                    data: {
                        "mode" : "delete_document",
                        "iPremiseCircuitId" : id,
                    },
                    success: function(data){
                        swal.close();
                        response =JSON.parse(data);
                        if(response['error'] == "0"){
                            toastr.success(response['msg']);
                        }else{
                            toastr.error(response['msg']);
                        }
                        setTimeout(function () { location.href = site_url+'premise_circuit/premise_circuit_edit&mode=Update&iPremiseCircuitId='+response['iPremiseCircuitId'];}, 3500);
                    }
                });
            } else {
                swal.close();
            }
        }
    );
}
