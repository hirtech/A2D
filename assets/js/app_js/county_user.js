 //$.fn.dataTable.ext.legacy.ajax = true;
 //$.fn.DataTable.ext.pager.numbers_length = 3;
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
                "orderMulti" : false,
                "ajaxSource": site_url+'master/county_user_list?mode=List',
                "aaSorting": [0,'desc'],
                'bAutoWidth': true,
                "aoColumns": [
                    { "mData": "checkbox","className": "text-center" , "width":"1%"},
                    { "mData": "countyname", "width":"15%"},
                    { "mData": "countyurl", "width":"18%"},
                    { "mData": "name", "width":"15%"},
                    { "mData": "email", "width":"15%"},
                    { "mData": "phone", "width":"10%","bSortable":false},
                    { "mData": "username","className": "text-center", "width":"10%"},
                    { "mData": "dbgenrated","className": "text-center", "width":"10%"},
                    { "mData": "status", "className": "text-center", "width":"6%"},
                    { "mData": "actions", "bSortable":false, "className": "text-center", "width":"10%"},
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
                        "data": $(".county_user_search_form").serializeArray(),
                        "success": fnCallback
                    });
                },
        });
        //Add button 
        if(access_group_var_add == '1') {
            gridtable.button().add( 0, {
                action: function ( e, dt, node, config ) {
                           location.href = site_url+"master/county_user_add";
                },
                text: '<i class="fa fa-plus"></i>Add',
                className: 'btn btn-primary'
            });
            gridtable.button().add( 3, {
                action: function ( e, dt, node, config ) {
                    sendmail();
                },
                text: '<i class="fa fa-envelope"></i> Send Mail',
                className: 'btn btn-secondary'
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
        title: "Are you sure you want to suspend record ?",
        text: "",
        type: "warning",
        showCancelButton: true,
        //confirmButtonColor: "#DD6B55",
        confirmButtonClass: 'confirm btn btn-lg btn-danger',
        cancelButtonClass : 'cancel btn btn-lg btn-default',
        confirmButtonText: 'Yes, suspended it!',
        cancelButtonText: "No, cancel plx!",
        closeOnConfirm: false,
        closeOnCancel: true,
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    url: site_url+"master/county_user_list",
                    data: {
                        "mode" : "Delete",
                        "iCountySaasId" : id
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
                        //gridtable.draw();
                    }
                });
            } else {
                swal.close();
                //swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
        }
    );
}

/*******Send Mail***********/
function sendmail(){
    if($('input.row_chk:checked').length > 0){
        var sel_check = $('input.row_chk:checked').map(function() {
                    return this.value;
                }).get().join(',');
     
        swal({
            title: "Are you sure you want to send mail ?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'confirm btn btn-lg btn-danger',
            cancelButtonClass : 'cancel btn btn-lg btn-default',
            confirmButtonText: 'Yes',
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true,
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    url: site_url+"master/county_user_list",
                    data: {
                        "mode" : "SendCountyMail",
                        "Id" : sel_check
                    },
                    success: function(data){
                        swal.close();
                        response =JSON.parse(data);
                        if(response['error'] == "0"){
                            toastr.success(response['msg']);
                        }else{
                            toastr.error(response['msg']);
                        }
                    }
                });
            } else {
                swal.close();
                //swal("Cancelled", "Your imaginary file is safe :)", "error");
            }

            $('input.row_chk').prop('checked',false);
        }
    );
      // alert(sel_check);
    }else{
        toastr.error('Please select record for send mail');

    }
}


