
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
                "aaSorting": [[0,'desc']],
                'bAutoWidth': true,
                "columns": [
                    { "data": "checkbox", "sortable":true, "className": "text-center"},
                    { "data": "iCustomerId", "sortable":true},
                    { "data": "vPONumber", "sortable":true},
                    { "data": "dInvoiceDate", "sortable":true, "className": "text-center"},
                    { "data": "dPaymentDate", "sortable":true, "className": "text-center"},
                    { "data": "BillingMonth", "sortable":false, "className": "text-center"},
                    { "data": "tNotes", "sortable":true},
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
                    //'copy', 'print',
                ],
                fnServerData: function(sSource, aoData, fnCallback,oSettings) {
                    oSettings.jqXHR = $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": sSource+'&'+$.param(aoData),
                        "data": $(".invoice_search_form").serializeArray(),
                        "success": fnCallback
                    });
                },
        });
        //Add button 
        if(access_group_var_add == '1') {
            gridtable.button().add( 0, {
                action: function ( e, dt, node, config ) {
                    location.href = site_url+"invoice/invoice_add";
                },
                text: '<i class="fa fa-plus"></i>Add',
                className: 'btn btn-primary'
            });
        }
        //'excel'
        /*gridtable.button().add( 2, {
            text: 'Excel',
            className: 'btn btn-secondary',
            action: function ( e, dt, node, config ) {
                exportExcelSheet();
            }
        });*/
    }
    return {
        init :function () {
           handleData();
        }
    }
}();

$('#AdvSearchSubmit').click(function () {
    gridtable.ajax.reload();
    return false;
});

$('#AdvSearchReset').click(function () {
    $('#dSInvoiceDate').val("");
    $('#dSPaymentDate').val("");
    $('#iSBillingMonth').val("");
    $('#iSBillingYear').val("");
    $('#iSPremiseId').val("");
    $('#vSPremiseNameDD').val("Contains");
    $('#vSPremiseName').val("");
    $('#iSServiceType').val("");
    $('#dSStartDate').val("");
    $('#iSStatus').val("");

    gridtable.ajax.reload();
    return false;
});

$('#Search').click(function (){
    gridtable.ajax.reload();
    return false;
});


function changeInvoiceStatus(iInvoiceId, iStatus) {
    swal({
        title: "Are you sure you want to change invoice status ?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: 'confirm btn btn-lg btn-danger',
        cancelButtonClass : 'cancel btn btn-lg btn-default',
        confirmButtonText: 'Yes, change it!',
        cancelButtonText: "No, cancel it!",
        closeOnConfirm: false,
        closeOnCancel: true,
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    url: site_url+ajax_url,
                    data: {
                        "mode" : "change_status",
                        "iInvoiceId" : iInvoiceId,
                        "iStatus" : iStatus,
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