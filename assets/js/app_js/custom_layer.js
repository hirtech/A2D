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
                    { "data": "iCLId", "sortable":true, "className": "text-center", "width" : "1%"},
                    { "data": "vName", "width" : "50%"},
                    { "data": "iStatus", "sortable":true, "className": "text-center", "width" : "15%"},
                    { "data": "actions", "sortable":false, "className": "text-center", "width" : "13%"},
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
                "buttons": [],
                fnServerData: function(sSource, aoData, fnCallback,oSettings) {
                    oSettings.jqXHR = $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": sSource+'&'+$.param(aoData),
                        "data": $("#frmlist").serializeArray(),
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
                     location.href = site_url+"custom_layer/custom_layer_add";
                }
            });
        }
        /*gridtable.button().add( 1, {
                text: 'Geo Edit',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    geoEdit();
                }
            });*/
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
                    url: site_url+"custom_layer/custom_layer_list",
                    data: {
                        "mode" : "Delete",
                        "iCLId" : id
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

/*function geoEdit(){
    var IdArr = new Array()
    $('.list').each(function(){ 
        if($(this).is(':checked')){
            IdArr.push($(this).val());
        }
    });
    if (IdArr.length == 0) {
         toastr.error(" Please select only one record to Geo Edit.");
    }else if (IdArr.length != 1) {
        toastr.error(" Please select only one record to Geo Edit.");
        $('#chkall').prop('checked', false);
    }else {
        var iCLId = IdArr;
        window.location = site_url + 'custom_layer/custom_layer_geo_edit&mode=GeoEdit&iCLId=' + iCLId;
    }
}*/