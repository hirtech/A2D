$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
    listPage.init();
});

var gridtable;
/*********************************************************************/
var listPage = function(){ 
    var handleData =function(){
      
        gridtable = $('#datatable-grid').DataTable({  
            "processing": true,
                "serverSide": true,
                "orderMulti" : false,
                "ajaxSource": site_url+ajax_url,
                "aaSorting": [0,'Desc'],
                'bAutoWidth': true,
                "columns": [
                    { "data": "iPremiseCircuitId", "sortable":true, "className": "text-center"},
                    { "data": "vPremise", "sortable":true},
                    { "data": "vWorkOrder", "sortable":true},
                    { "data": "vCircuitName", "sortable":true},
                    { "data": "vConnectionTypeName", "sortable":true},
                    { "data": "vCarrierServices", "sortable":false},
                    { "data": "vEquipment", "sortable":false},
                    { "data": "iStatus", "sortable":true, "className": "text-center"},
                    { "data": "actions", "sortable":false, "className": "text-center"},
                ],            
                "autoWidth" : true,
                "lengthMenu": PageLengthMenuArr,
                "iDisplayLength": REC_LIMIT,
                //"sDom": 'Rfrtlip',
                "dom": 'Bfrtlip',
                "filter": false,
                'serverMethod': 'post',
                "pagingType": "full_numbers",
                "stateSave": true,
                "oLanguage": {
                    "oPaginate": {
                       "sNext": '<i class="fa fa-forward"></i>',
                       "sPrevious": '<i class="fa fa-backward"></i>',
                       "sFirst": '<i class="fa fa-step-backward"></i>',
                       "sLast": '<i class="fa fa-step-forward"></i>'
                    },
                    "sLengthMenu": "_MENU_",
                    "sLoadingRecords": "Please wait - loading...",

                },
                "buttons": [
                    'copy', 'print',
                ],
                fnServerData: function(sSource, aoData, fnCallback,oSettings) {
                    oSettings.jqXHR = $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": sSource+'&'+$.param(aoData),
                        "data": $(".pc_search_form").serializeArray(),
                        "success": fnCallback
                    });
                },
        });
        //Add button 
        if(access_group_var_add == '1') {
            gridtable.button().add( 0, {
                text: '<i class="fa fa-plus"></i>Add',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                     location.href = site_url+"premise_circuit/premise_circuit_add";
                }
            });
        }

        //Excel button 
        if(access_group_var_CSV == '1'){
            gridtable.button().add( 3, {
                text: 'Excel',
                className: 'btn btn-secondary',
                action: function ( e, dt, node, config ) {
                    exportExcelSheet();
                }
            });
        }
    }
    return {
        init :function () {
           handleData();
        }
    }
}();


$('#Search').click(function (){
     gridtable.ajax.reload();
    return false;
});

function delete_record(id)
{
   // alert('delete')
    swal({
        title: "Are you sure you want to delete record ?",
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
                        "mode" : "Delete",
                        "iPremiseCircuitId" : id
                    },
                    success: function(data){
                         swal.close();
                        response =JSON.parse(data);
                        if(response['error'] == "0"){
                            toastr.success(response['msg']);
                        }else{
                            toastr.error(response['msg']);
                        }
                        gridtable.ajax.reload();
                    }
                });
            } else {
                swal.close();
                //swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
        }
    );
}

function exportExcelSheet(){
    $.ajax({
        type: "POST",
        url: site_url+"premise_circuit/premise_circuit_list&mode=Excel",
        data: $("#frmlist").serializeArray(),
        success: function(data){
            res = JSON.parse(data);
            isError = res['isError'];
            if(isError == 0) {
                file_path = res['file_path'];
                file_url = res['file_url'];
                window.location = site_url+"download.php?vFileName_path="+file_path+"&vFileName_url="+file_url;
            }
        }
    });
    return false;
}

function getDropdown(vOptions) {
    if(vOptions == "iNetworkId"){
        reset_all_fields();
        $('#network_dd').show();
        $('#connection_type_dd').hide();
        $('#status_dd').hide();
    }else if(vOptions == "iConnectionTypeId"){
        reset_all_fields();
        $('#connection_type_dd').show();
        $('#network_dd').hide();
        $('#status_dd').hide();
    }else if(vOptions == "vStatus"){
        reset_all_fields();
        $('#status_dd').show();
        $('#network_dd').hide();
        $('#connection_type_dd').hide();
    }
}

function reset_all_fields(){
  $('#networkId').val('');
  $('#ConnectionTypeId').val('');
  $('#iStatus').val('');
}

$('#AdvSearchSubmit').click(function () {
    gridtable.ajax.reload();
    return false;
});

$('#AdvSearchReset').click(function () {
    //alert('1111');
    $('#premiseCircuitId').val("");
    $('#premiseId').val("");
    $('#SiteFilterOpDD').val("Contains");
    $('#siteName').val("");
    $('#workorderId').val("");
    $('#workorderTypeId').val("");
    $('#circuitId').val("");
    $('#NameFilterOpDD').val("Contains");
    $('#vName').val("");
    $('#CommentFilterOpDD').val("Contains");
    $('#tComments').val("");
    
    gridtable.ajax.reload();
    return false;
});