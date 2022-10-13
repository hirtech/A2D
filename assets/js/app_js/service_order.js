var gridtable;
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
    listPage.init();  
});

var listPage = function(){ 
    var handleData =function(){
        gridtable = $('#datatable-grid').DataTable({  
            "processing": true,
                "serverSide": true,
                " " : false,
                "ajaxSource": site_url+'service_order/list?mode=List',
                "aaSorting": [[0,'asc']],
                'bAutoWidth': true,
                "aoColumns": [
                    { "mData": "iServiceOrderId", "sortable":true, "className": "text-center", "width" : "1%"},
                    { "mData": "vMasterMSA", "width" : "8%", "sortable":true},
                    { "mData": "vServiceOrder", "width" : "12%", "sortable":true},
                    { "mData": "iCarrierID", "width" : "12%", "sortable":true},
                    { "mData": "vSalesRepName", "width" : "15%", "sortable":true},
                    { "mData": "iPremiseId", "width" : "12%", "sortable":true},
                    { "mData": "iConnectionTypeId", "width" : "12%", "sortable":true},
                    { "mData": "iServiceDetails", "width" : "18%", "sortable":false},
                    { "mData": "actions", "sortable":false, "className": "text-center", "width" : "10%"},
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
                        "data": $(".user_search_form").serializeArray(),
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
            gridtable.button().add( 2, {
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
        url: site_url+"service_order/list?mode=Excel",
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