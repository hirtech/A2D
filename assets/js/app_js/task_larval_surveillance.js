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
                "aaSorting": [0,'desc'],
                'bAutoWidth': true,
                "columns": [
                    { "data": "iTLSId", "className": "text-center", "width" : "2%"},
                    { "data": "vName", "width" : "15%"},
                    { "data": "vAddress", "sortable":false, "width" : "12%"},
                    { "data": "sr", "sortable":false, "width" : "15%"},
                    { "data": "dDate", "className": "text-center" , "width" : "8%"},
                    { "data": "dStartDate", "sortable":false, "className": "text-center" , "width" : "8%"},
                    { "data": "dEndDate", "sortable":false, "className": "text-center" , "width" : "8%"},
                    { "data": "Summary", "sortable":false, "width" : "13%"},
                    { "data": "tNotes", "sortable":false, "width" : "10%"},
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
                   /* {
                        text: '<i class="fa fa-plus"></i>Add',
                        className: 'btn btn-primary',
                        action: function ( e, dt, node, config ) {
                            addEditData('','add');
                        }
                    },*/
                    'copy', 'print',
                ],
                fnServerData: function(sSource, aoData, fnCallback,oSettings) {
                  oSettings.jqXHR = $.ajax( {
                     "dataType": 'json',
                     "type": "POST",
                     //"url": sSource+'?'+$.param(aoData),
                     "url": sSource+'&'+$.param(aoData),
                     "data": $("#frmlist").serializeArray(),
                     "success": fnCallback
                  } );
               },
        });
        //Add button 
        if(access_group_var_add == '1') {
            gridtable.button().add( 0, {
                text: '<i class="fa fa-plus"></i>Add',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    addEditDataTaskLarval(0,'add','');
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
                    url: site_url+"tasks/task_larval_surveillance_list",
                    data: {
                        "mode" : "Delete",
                        "iTLSId" : id
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