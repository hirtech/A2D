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
            url: site_url+'trouble_ticket/trouble_ticket_add?mode=search_premise',
            replace: function(url, uriEncodedQuery) {
                var newUrl = url + '&vPremiseName=' + uriEncodedQuery;
                return newUrl;
            },
            filter: function(list) {
                if(list==null)
                    return {};
                else
                    return $.map(list, function(rawdata) { return { display: rawdata.display, iPremiseId:rawdata.iPremiseId, vName:rawdata.vName, vAddress:rawdata.vAddress }; });
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

    var clusterSO = new Bloodhound({
        datumTokenizer: function(d) { return d.tokens; },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: site_url+'trouble_ticket/trouble_ticket_add?mode=search_service_order',
            replace: function(url, uriEncodedQuery) {
                var newUrl = url + '&vServiceOrder=' + uriEncodedQuery;
                return newUrl;
            },
            filter: function(list) {
                if(list==null)
                    return {};
                else
                    return $.map(list, function(rawdata) { return { display: rawdata.display, iServiceOrderId:rawdata.iServiceOrderId }; });
            } 
        }
    });
    clusterSO.initialize();
    select = false;
    $('#vServiceOrder').typeahead({hint: false, highlight: true,minLength: 1 }, 
    {
        displayKey: 'display',
        source: clusterSO.ttAdapter(),
    })
    .on('typeahead:selected', onServiceOrderClusteSelected)
    .off('blur')
    .blur(function() {
        $(".tt-dropdown-menu").hide();
    });
});

function clear_serach_premise(){
    $("#vPremiseName").typeahead('val','');
}
function clear_serach_serviceorder (){
    $("#vServiceOrder").typeahead('val','');
    $("#search_iServiceOrderId").val('');
}

function onPremiseClusteSelected(e, datum){
    $("#vPremiseName").typeahead('val','');
    var str = '';
    str += '<tr>';
        str += '<td class="text-center"><input type="hidden" name="iPremiseId[]" value="'+datum['iPremiseId']+'" class="form-control">'+datum['iPremiseId']+'</td>';
        str += '<td>'+datum['vName']+'</td>';
        str += '<td>'+datum['vAddress']+'</td>';
        str += '<td><input type="date" class="form-control" name="dTroubleStartDate[]" value="'+dTodayDate+'"></td>';
        str += '<td><input type="date" class="form-control" name="dResolvedDate[]"></td>';
        str += '<td class="text-center"><a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_row(this);"><i class="fa fa-window-close"></i></a></td>';
    str += '</tr>';
    //alert(trouble_ticket_premise_count)
    if(str !='') {
        if(trouble_ticket_premise_count == 0) {
            $(".premise_data").html(str);
        }else {
            $(".premise_data").append(str);
        }
    }
    trouble_ticket_premise_count++;
    $("#premise_length").val(trouble_ticket_premise_count)

}

function delete_row(obj) {
    swal({
        title: "Are you sure you want to delete ?",
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
                swal.close();
                trouble_ticket_premise_count--;
                $("#premise_length").val(trouble_ticket_premise_count)
                $(obj).closest("tr").remove()
            } else {
                swal.close();
            }
        }
    );
    
}


function onServiceOrderClusteSelected(e, datum){
    $("#search_iServiceOrderId").val(datum['iServiceOrderId']);
    $("#vServiceOrder").val(datum['display']);
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
    $("#errormsg_completion_date").hide();
    if($("#iStatus").val() != '' && $("#iStatus").val() != 3){
        if($("#dCompletionDate").val() != '') {
            isError = 1;
            $("#errormsg_completion_date").html('You cannot select "completion date" until the status is marked as complete.');
            $("#errormsg_completion_date").show();
            $('#save_loading').hide();   
            $("#save_data").prop('disabled', false);
            return false;
        }
    }
    //alert(isError)
    if(isError == 0){
        var form_data = new FormData($("#frmadd")[0]);
        $.ajax({
            type: "POST",
            url: site_url+"trouble_ticket/trouble_ticket_list",
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
                    setTimeout(function () { location.href = site_url+'trouble_ticket/trouble_ticket_list';}, 3500);
                }else if(response['error'] == "2"){
                    swal({
                        title: response['msg'],
                        text: "",
                        type: "info",
                        //showCancelButton: true,
                        //confirmButtonColor: "#DD6B55",
                        confirmButtonClass: "confirm btn btn-lg btn-danger",
                        //cancelButtonClass : 'cancel btn btn-lg btn-default',
                        confirmButtonText: 'Okay',
                        closeOnConfirm: false,
                        //closeOnCancel: true,
                        },
                        function(isConfirm) {
                            swal.close();
                        }
                    );
                }
                else{
                    toastr.error(response['msg']);
                    setTimeout(function () { location.href = site_url+'trouble_ticket/trouble_ticket_list';}, 3500);
                }
                
            }
        });
        return false; 
    }else{
        $('#save_loading').hide();   
        $("#save_data").prop('disabled', false);
    }
});
