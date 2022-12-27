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
                    { "data": "iAId", "className": "text-center", "width" : "2%"},
                    { "data": "vName", "width" : "12%"},
                    { "data": "vAddress", "sortable":false, "width" : "12%"},
                    { "data": "vFiberInquiry", "sortable":false, "width" : "10%"},
                    { "data": "dDate", "className": "text-center" , "width" : "10%"},
                    { "data": "dStartDate", "sortable":false, "className": "text-center" , "width" : "10%"},
                    { "data": "dEndDate", "sortable":false, "className": "text-center" , "width" : "10%"},
                    { "data": "vEngagement", "sortable":false, "width" : "10%"},
                    { "data": "tNotes", "sortable":false, "width" : "15%"},
                    { "data": "actions", "sortable":false, "className": "text-center", "width" : "9%"},
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
                        "data": $(".awareness_search_form").serializeArray(),
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
                    addEditDataAwareness(0,'add', '');
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
                    url: site_url+"tasks/task_awareness_list",
                    data: {
                        "mode" : "Delete",
                        "iAId" : id
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

function getDropdown(vOptions) {
    if(vOptions == "iNetworkId"){
        reset_all_fields();
        $('#network_dd').show();
        $('#engagement_dd').hide();
        $('#technician_dd').hide();
    }else if(vOptions == "iEngagementId"){
        reset_all_fields();
        $('#engagement_dd').show();
        $('#network_dd').hide();
        $('#technician_dd').hide();
    }else if(vOptions == "iTechnicianId"){
        reset_all_fields();
        $('#technician_dd').show();
        $('#network_dd').hide();
        $('#engagement_dd').hide();
    }
}

function reset_all_fields(){
  $('#networkId').val('');
  $('#engagementId').val('');
  $('#technicianId').val('');
}

$('#AdvSearchSubmit').click(function () {
    gridtable.ajax.reload();
    return false;
});

$('#AdvSearchReset').click(function () {
    //alert('1111');
    $('#aId').val("");
    $('#premiseId').val("");
    $('#SiteFilterOpDD').val("Contains");
    $('#siteName').val("");
    $('#AddressFilterOpDD').val("Contains");
    $('#vAddress').val("");
    $('#fiberInquiryId').val("");
   
    gridtable.ajax.reload();
    return false;
});