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
                "aaSorting": [1,'Desc'],
                'bAutoWidth': true,
                "columns": [
                    { "data": "checkbox", "sortable":false, "className": "text-center"},
                    { "data": "iTroubleTicketId", "className": "text-center", "sortable":true},
                    { "data": "vAssignedTo", "sortable":true},
                    { "data": "vServiceOrder", "sortable":false},
                    { "data": "iSeverity", "sortable":false},
                    { "data": "iStatus", "className": "text-center", "sortable":true},
                    { "data": "dCompletionDate", "sortable":true, "className": "text-center"},
                    { "data": "tDescription", "sortable":true},
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
                    {
                        extend: 'collection',
                        className: 'btn btn-dark',
                        text: '<i class="far fa-edit"></i> Change Status',
                        buttons: [
                            { 
                                text: 'Not Started',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(1);
                                } 
                            },
                            { 
                                text: 'In Progress',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(2);
                                } 
                            },
                            { 
                                text: 'Completed',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(3);
                                } 
                            },
                        ],
                        fade: true
                    }
                ],
                fnServerData: function(sSource, aoData, fnCallback,oSettings) {
                    oSettings.jqXHR = $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": sSource+'&'+$.param(aoData),
                        "data": $(".trouble_ticket_search_form").serializeArray(),
                        "success": fnCallback
                    });
                },
        });
        //Add button 
        if(access_group_var_add == '1') {
            gridtable.button().add( 0, {
                action: function ( e, dt, node, config ) {
                    location.href = site_url+"trouble_ticket/trouble_ticket_add";
                },
                text: '<i class="fa fa-plus"></i>Add',
                className: 'btn btn-primary'
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

function changeStatus(status){
    if ($('#datatable-grid input:checked').length > 0){
        var ids = [];
        $.each($("input[class='list']:checked"), function(e)
        {
            ids.push($(this).val());            
        });
        swal({
            title: "Are you sure you want to change the status for selected record(s) ?",
            text: "",
            type: "warning",
            showCancelButton: true,
            //confirmButtonColor: "#DD6B55",
            confirmButtonClass: 'confirm btn btn-lg btn-danger',
            cancelButtonClass : 'cancel btn btn-lg btn-default',
            confirmButtonText: 'Yes!',
            cancelButtonText: "No, cancel plx!",
            closeOnConfirm: false,
            closeOnCancel: true,
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        url: site_url+"trouble_ticket/trouble_ticket_list",
                        data: {
                            "mode" : "change_status",
                            "status" : status,
                            "iTroubleTicketIds" : ids.join(",")
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
                }
            }
        );
    }
    else{
        alert("Please select at list one record");
    }
}

function delete_record(id) {
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
                    url: site_url+"trouble_ticket/trouble_ticket_list",
                    data: {
                        "mode" : "Delete",
                        "iTroubleTicketId" : id
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
            }
        }
    );
}

$('#AdvSearchSubmit').click(function () {
    gridtable.ajax.reload();
    return false;
});

$('#AdvSearchReset').click(function () {
    $('#iSAssignedToId').val("");
    $('#iSServiceOrderId').val("");
    $('#iSSeverity').val("");
    $('#iSStatus').val("");
    $('#dSCompletionDate').val("");
    $('#tSDescriptionDD').val("Contains");
    $('#tSDescription').val("");
    $('#iSPremiseId').val("");
    $('#vSPremiseNameDD').val("Contains");
    $('#vSPremiseName').val("");
    $('#vSAddressDD').val("Contains");
    $('#vSAddress').val("");
    $('#iSNetworkId').val("");
    $('#iSCarrierId').val("");

    gridtable.ajax.reload();
    return false;
});