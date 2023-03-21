var gridtable;
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
    listPage.init();  

    if(A2D_COMPANY_ID != sess_iCompanyId){
        $(".status_buttons").hide();
    }else {
        $(".status_buttons").show();
    }    
});

var listPage = function(){ 
    var handleData =function(){
        gridtable = $('#datatable-grid').DataTable({  
            "processing": true,
                "serverSide": true,
                " " : false,
                "ajaxSource": site_url+ajax_url,
                "aaSorting": [[1,'desc']],
                'bAutoWidth': true,
                "aoColumns": [
                    { "data": "checkbox", "sortable":false, "className": "text-center"},
                    { "mData": "iServiceOrderId", "sortable":true, "className": "text-center"},
                    { "mData": "vMasterMSA", "sortable":true},
                    { "mData": "vServiceOrder", "sortable":true},
                    { "mData": "iCarrierID", "sortable":true},
                    { "mData": "vSalesRepName", "sortable":true},
                    { "mData": "iPremiseId", "sortable":true},
                    { "mData": "iConnectionTypeId", "sortable":true},
                    { "mData": "iServiceDetails", "sortable":false},
                    { "mData": "iSOStatus", "sortable":false, "className": "text-center"},
                    { "mData": "iSStatus", "sortable":false, "className": "text-center"},
                    { "mData": "actions", "sortable":false, "className": "text-center"},
                ],                
                "autoWidth" : true,
                "lengthMenu": PageLengthMenuArr,
                "iDisplayLength": REC_LIMIT,
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
                        className: 'btn btn-dark status_buttons so_status',
                        text: '<i class="far fa-edit"></i> Change SO Status',
                        buttons: [
                            { 
                                text: 'Created',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(1,"iSOStatus");
                                } 
                            },
                            { 
                                text: 'In Progress',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(2,"iSOStatus");
                                } 
                            },
                            { 
                                text: 'Delayed',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(3,"iSOStatus");
                                } 
                            },
                            { 
                                text: 'Cancelled',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(4,"iSOStatus");
                                } 
                            },
                            { 
                                text: 'Final Review',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(5,"iSOStatus");
                                } 
                            },
                            { 
                                text: 'Carrier Approved',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(6,"iSOStatus");
                                } 
                            },
                            { 
                                text: 'Final Approved',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(7,"iSOStatus");
                                } 
                            },
                        ],
                        fade: true
                    },
                    {
                        extend: 'collection',
                        className: 'btn btn-dark status_buttons',
                        text: '<i class="far fa-edit"></i> Change Connection Status',
                        buttons: [
                            { 
                                text: 'Created',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(1,"iCStatus");
                                } 
                            },
                            { 
                                text: 'In-Progress',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(2,"iCStatus");
                                } 
                            },
                            { 
                                text: 'Delayed',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(3,"iCStatus");
                                } 
                            },
                            { 
                                text: 'On-Net',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(4,"iCStatus");
                                } 
                            },
                        ],
                        fade: true
                    },
                    {
                        extend: 'collection',
                        className: 'btn btn-dark status_buttons',
                        text: '<i class="far fa-edit"></i> Change Service Status',
                        buttons: [
                            { 
                                text: 'Pending',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(0,"iSStatus");
                                } 
                            },
                            { 
                                text: 'Active',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(1,"iSStatus");
                                } 
                            },
                            { 
                                text: 'Suspended',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(2,"iSStatus");
                                } 
                            },
                            { 
                                text: 'Trouble',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(3,"iSStatus");
                                } 
                            },
                            { 
                                text: 'Disconnected',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(4,"iSStatus");
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
                        "data": $(".sorder_search_form").serializeArray(),
                        "success": fnCallback
                    });
                },
        });
        //Add button 
        if(access_group_var_add == '1') {
            gridtable.button().add( 0, {
                action: function ( e, dt, node, config ) {
                    location.href = site_url+"service_order/add";
                },
                text: '<i class="fa fa-plus"></i>Add',
                className: 'btn btn-primary'
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

function changeStatus(status, status_field){
    if ($('#datatable-grid input:checked').length > 0){
        var ids = [];
        $.each($("input[class='list']:checked"), function(e)
        {
            ids.push($(this).val());            
        });
        if(status_field == "iSOStatus" && status == 6 && sess_vCompanyAccessType != "Carrier"){
            toastr.error("\"Carrier Approved\" status can be only selected by Carrier Users.");
        }else if(status_field == "iSOStatus" && status == 7 && sess_iCompanyId != A2D_COMPANY_ID){
            toastr.error("\"Final Approved\" status can be only selected by A2D Users.");
        }else {
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
                            url: site_url+"service_order/list",
                            data: {
                                "mode" : "change_status",
                                "status" : status,
                                "status_field" : status_field,
                                "iServiceOrderIds" : ids.join(",")
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
    }
    else{
        //alert("Please select at list one record");
        toastr.error("Please select at list one record to change the status.");
    }
}

function delete_record(id)
{
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
                    url: site_url+"service_order/list",
                    data: {
                        "mode" : "Delete",
                        "iServiceOrderId" : id
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

function exportExcelSheet(){
    $.ajax({
        type: "POST",
        url: site_url+"service_order/list&mode=Excel",
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

$('#AdvSearchSubmit').click(function () {
    gridtable.ajax.reload();
    return false;
});

$('#AdvSearchReset').click(function () {
    $('#vSContactNameDD').val("Contains");
    $('#vSContactName').val("");
    $('#vSAddressFilterOpDD').val("Contains");
    $('#vSAddress').val("");
    $('#vSCityFilterOpDD').val("Contains");
    $('#vSCity').val("");
    $('#vSStateFilterOpDD').val("Contains");
    $('#vSState').val("");
    $('#vSZipCode').val("");
    $('#iSZoneId').val("");
    $('#iServiceOrderId').val("");
    $('#vServiceOrder').val("");
    $('#vSSalesRepNameDD').val("Contains");
    $('#vSSalesRepName').val("");
    $('#vSSalesRepEmailDD').val("Contains");
    $('#vSSalesRepEmail').val("");
    $('#vMasterMSA').val("");
    
    gridtable.ajax.reload();
    return false;
});

function getDropdown(vOptions) {
    $('#network_dd').show();
    if(vOptions == "vNetwork"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#network_dd').show();
    }else if(vOptions == "vCarrier"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#carrier_dd').show();
    }else if(vOptions == "vConnectionType"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#connection_type_dd').show();
    }else if(vOptions == "vServiceType"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#service_type_dd').show();
    }else if(vOptions == "iSOStatus"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#service_order_status_dd').show();
    }else if(vOptions == "iCStatus"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#connection_status_dd').show();
    }else if(vOptions == "iSStatus"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#service_status_dd').show();
    }
}

function reset_all_fields(){
  $('#iSNetworkId').val('');
  $('#iSCarrierId').val('');
  $('#iConnectionTypeId').val('');
  $('#iSServiceType').val('');
  $('#iSOStatus').val('');
  $('#iCStatus').val('');
  $('#iSStatus').val('');
}
