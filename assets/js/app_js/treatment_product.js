$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
    listPage.init();

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
                    { "data": "iTPId", "className": "text-center", "width" : "2%"},
                    { "data": "vName", "width" : "20%"},
                    { "data": "vCategory", "width" : "15%"},
                    { "data": "iPesticide", "className": "text-center" , "width" : "5%"},
                    { "data": "vClass", "width" : "13%"},
                    { "data": "vEPARegNo", "sortable":false,  "width" : "10%"},
                    { "data": "iUId","className": "text-center" , "width" : "10%"},
                    { "data": "vTragetAppRate","className": "text-center" , "sortable":false, "width" : "10%"},
                    { "data": "iStatus","className": "text-center" , "width" : "5%"},
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
                    addeditTreatmentProdData(0,'add','');
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

function delete_record(id)
{
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
                    url: site_url+"master/treatment_product_list",
                    data: {
                        "mode" : "Delete",
                        "id" : id
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


$('#Search').click(function (){
     gridtable.ajax.reload();
    return false;
});