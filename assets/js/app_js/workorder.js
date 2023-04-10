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
                "aaSorting": [[1,'desc']],
                'bAutoWidth': true,
                "columns": [
                    { "data": "checkbox", "sortable":false, "className": "text-center"},
                    { "data": "iWOId", "sortable":true, "className": "text-center"},
                    { "data": "vPremise", "sortable":true},
                    { "data": "vServiceDetails", "sortable":true},
                    { "data": "vRequestor", "sortable":true},
                    { "data": "vWOProject", "sortable":true},
                    { "data": "vType", "sortable":true},
                    { "data": "vAssignedTo", "sortable":true},
                    { "data": "vStatus", "className": "text-center", "sortable":true},
                    { "data": "actions", "sortable":false, "className": "text-center"},
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
                    'copy',  'print',
                    {
                        extend: 'collection',
                        className: 'btn btn-dark',
                        text: '<i class="far fa-edit"></i> Change Status',
                        buttons: [
                            { 
                                text: 'Open',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(1);
                                } 
                            },
                            { 
                                text: 'Closed',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(2);
                                } 
                            },
                            { 
                                text: 'Suspended',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(3);
                                } 
                            },
                            { 
                                text: 'Planning',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(4);
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
                    location.href = site_url+"service_order/workorder_add";
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

$("#save_data").click(function(){
    $('#save_loading').show();
    $("#save_data").prop('disabled', true);
    var form = $("#frmadd")
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
            url: site_url+"service_order/workorder_list",
            data: data_str,
            success: function(data){
                $('#save_loading').hide();
                $("#save_data").prop('disabled', false);
                $("#closestbox").trigger('click');
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
        $("#save_data").prop('disabled', false);
    }
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
                        url: site_url+"service_order/workorder_list",
                        data: {
                            "mode" : "change_status",
                            "status" : status,
                            "iWOIds" : ids.join(",")
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

function delete_record(id){
    swal({
        title: "Are you sure you want to delete record ?",
        text: "",
        type: "warning",
        showCancelButton: true,
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
                    url: site_url+"service_order/workorder_list",
                    data: {
                        "mode" : "Delete",
                        "iWOId" : id
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
	var iDisplayLength = gridtable.rows().count();
    $.ajax({
        type: "POST",
        url: site_url+"service_order/workorder_list?mode=Excel&iDisplayLength="+iDisplayLength,
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
    $('#vSPremiseNameDD').val("Contains");
    $('#vSPremiseName').val("");
    $('#vSAddressFilterOpDD').val("Contains");
    $('#vSAddress').val("");
    $('#vSCityFilterOpDD').val("Contains");
    $('#vSCity').val("");
    $('#vSStateFilterOpDD').val("Contains");
    $('#vSState').val("");
    $('#vSZipCode').val("");
    $('#iSServiceOrderId').val("");
    $('#vSWOProjectDD').val("Contains");
    $('#vSWOProject').val("");
    $('#iWOTId').val("");
    $('#iPremiseId').val("");
    $('#iServiceOrderId').val("");
    gridtable.ajax.reload();
    return false;
});

function getDropdown(vOptions) {
    $('#network_dd').show();
    if(vOptions == "vNetwork"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#network_dd').show();
    }else if(vOptions == "vFiberZone"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#fiber_zone_dd').show();
    }else if(vOptions == "vWOType"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#work_order_type_dd').show();
    }else if(vOptions == "vRequestor"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#requestor_dd').show();
    }else if(vOptions == "vAssignedTo"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#assigned_to_dd').show();
    }else if(vOptions == "vStatus"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#status_dd').show();
    }
}

function reset_all_fields(){
  $('#iSNetworkId').val('');
  $('#iSZoneId').val('');
  $('#iSWOTId').val('');
  $('#iSRequestorId').val('');
  $('#iSAssignedToId').val('');
  $('#iSWOSId').val('');
}