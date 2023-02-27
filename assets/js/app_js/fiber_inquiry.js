$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
    listPage.init();
    $('#dDate2').datepicker({
        showOn: "both",
        dateFormat: "yy-mm-dd",
        firstDay: 0,
        changeFirstDay: true,
        changeMonth: true,
        changeYear: true
    });
});

var gridtable;
/*********************************************************************/
var listPage = function(){ 
    var handleData =function(){
        myData = $('#frmlist').serializeArray();
        gridtable = $('#datatable-grid').DataTable({  
            "processing": true,
                "serverSide": true,
                "orderMulti" : false,
                "ajaxSource": site_url+ajax_url,
                "sAjaxSourceData": myData,
                'serverMethod': 'post',
                "aaSorting": [[0,'Desc']],
                'bAutoWidth': true,
                "columns": [
                    { "data": "checkbox", "sortable":false, "className": "text-center"},
                    { "data": "iFiberInquiryId", "className": "text-center"},
                    { "data": "vContactName"},
                    { "data": "vAddress", "sortable":false},
                    { "data": "vCity"},
                    { "data": "vCounty"},
                    { "data": "vZipcode"},
                    { "data": "vZoneName"},
                    { "data": "vNetwork"},
                    { "data": "vStatus", "className": "text-center"},
                    { "data": "iInquiryType", "className": "text-center"},
                    { "data": "dAddedDate", "className": "text-center"},
                    { "data": "actions", "sortable":false, "className": "text-center", "width" : "10%"},
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
                   /* {
                        text: '<i class="fa fa-plus"></i>Add',
                        className: 'btn btn-primary',
                        action: function ( e, dt, node, config ) {
                            addEditData('','add');
                        }
                    },*/
                    'copy', 'print',
                    {
                        extend: 'collection',
                        className: 'btn btn-dark',
                        text: '<i class="far fa-edit"></i> Change Status',
                        buttons: [
                            { 
                                text: 'Draft',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(1);
                                } 
                            },
                            { 
                                text: 'Assigned',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(2);
                                } 
                            },
                            { 
                                text: 'Review',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(3);
                                } 
                            },
                            { 
                                text: 'Complete',    
                                action: function ( e, dt, node, config ) {
                                    changeStatus(4);
                                } 
                            },
                        ],
                        fade: true
                    }
                ],
                fnServerData: function(sSource, aoData, fnCallback,oSettings) {
                  oSettings.jqXHR = $.ajax( {
                     "dataType": 'json',
                     "type": "POST",
                     //"url": sSource+'?'+$.param(aoData),
                     "url": sSource+'&'+$.param(aoData),
                     "data": $(".site_search_form").serializeArray(),
                     "success": fnCallback
                  } );
               },
        });
        //Add button 
        if(access_group_var_add == '1') {
            gridtable.button().add( 0, {
                action: function ( e, dt, node, config ) {
                    location.href = site_url+"fiber_inquiry/add";
                },
                text: '<i class="fa fa-plus"></i>Add',
                className: 'btn btn-primary'
            });
        }
        //'excel'
        if(access_group_var_CSV == '1'){
            gridtable.button().add( 3, {
                text: 'Excel',
                className: 'btn btn-secondary',
                action: function ( e, dt, node, config ) {
                    exportExcelSheet();
                }
            });
        }
        gridtable.button().add( 4, {
            action: function ( e, dt, node, config ) {
                var sr_list_id = [];
                if ($('#datatable-grid input:checked').length > 0){
                    $.each($("input[class='list']:checked"), function(e)
                    {
                        sr_list_id.push($(this).val());
                        location.href = site_url+"vmap/index&mode=filter_fiberInquiry&iFiberInquiryId="+sr_list_id;
                    });
                }
                else{
                    alert("Please Select At List One Fiber Inquiry");
                }
            },
            text: 'Map Selected',
            className: 'btn btn-warning'
        });
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

$('#AdvSearchSubmit').click(function () {
 gridtable.ajax.reload();
 return false;
});

$('#AdvSearchReset').click(function () {
    //alert('1111');
    $('#fiberInquiryId').val("");
    $('#contactNameFilterOpDD').val("Contains");
    $('#contactName').val("");
    $('#AddressFilterOpDD').val("Contains");
    $('#vAddress').val("");
    $('#CityFilterOpDD').val("Contains");
    $('#vCity').val("");
    $('#CountryFilterOpDD').val("Contains");
    $('#vCountry').val("");
    $('#ZipcodeFilterOpDD').val("Contains");
    $('#vZipcode').val("");
    $('#AssignToFilterOpDD').val("Contains");
    $('#assignTo').val("");
    $('#reqType').val("");
    gridtable.ajax.reload();
    return false;
});

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
                    url: site_url+"fiber_inquiry/list",
                    data: {
                        "mode" : "Delete",
                        "iFiberInquiryId" : id
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
    }else if(vOptions == "vStatus"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#status_dd').show();
    }else if(vOptions == "vInquiryType"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#inquiry_type_dd').show();
    }
}

function reset_all_fields(){
  $('#iSNetworkId').val('');
  $('#iSZoneId').val('');
  $('#iStatus').val('');
  $('#iInquiryType').val('');
}

function exportExcelSheet(){
    $.ajax({
        type: "POST",
        url: site_url+"fiber_inquiry/list&mode=Excel",
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
                        url: site_url+"fiber_inquiry/list",
                        data: {
                            "mode" : "change_status",
                            "status" : status,
                            "iFiberInquiryIds" : ids.join(",")
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