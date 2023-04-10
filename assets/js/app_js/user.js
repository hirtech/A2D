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
                " " : false,
                "ajaxSource": site_url+'user/list?mode=List',
                "aaSorting": [[0,'desc']],
                'bAutoWidth': true,
                "aoColumns": [
                    { "mData": "checkbox", "className": "text-center" },
                    { "mData": "name"},
                    { "mData": "vEmail"},
                    { "mData": "vUsername", "className": "text-center"},
                    { "mData": "vDepartment","bSortable":false},
                    { "mData": "vCompanyName"},
                    { "mData": "vAccessGroup"},
                    { "mData": "vLoginHistory", "bSortable":false, "className": "text-center"},
                    { "mData": "dAddedDate", "className": "text-center"},
                    { "mData": "iStatus", "className": "text-center"},
                    { "mData": "actions", "bSortable":false, "className": "text-center"},
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
                    /*{
                        text: '<i class="fa fa-plus"></i>Add',
                        className: 'btn btn-primary',
                        action: function ( e, dt, node, config ) {
                           location.href = site_url+"user/add";
                        }
                    },*/
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
                           location.href = site_url+"user/add";
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
                    url: site_url+"user/list",
                    data: {
                        "mode" : "Delete",
                        "iUserId" : id
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

$('#AdvSearchReset').click(function () {
    $('#iZoneId').val("");
    $('#vNameDD').val("Contains");
    $('#vName').val("");
    $('#vEmailDD').val("Contains");
    $('#vEmail').val("");
    $('#vUsernameDD').val("Contains");
    $('#vUsername').val("");
    $('#iDepartmentId').val("");
    $('#iAGroupId').val("");
    $('#iCompanyId').val("");
    gridtable.ajax.reload();
    //$("#grid").flexOptions({params: ''}).flexReload();
    return false;
});

$('#AdvSearchSubmit').click(function () {
 gridtable.ajax.reload();
    return false;
});
function exportExcelSheet(){
    var iDisplayLength = gridtable.rows().count();
    $.ajax({
        type: "POST",
        url: site_url+"user/list?mode=Excel&iDisplayLength="+iDisplayLength,
        //data: $("#frmlist").serializeArray(),
        data: $(".user_search_form").serializeArray(),
        success: function(data){
            res = JSON.parse(data);
           // console.log(res);
            isError = res['isError'];
            if(isError == 0) {
                file_path = res['file_path'];
                file_url = res['file_url'];
                window.location = site_url+"download.php?vFileName_path="+file_path+"&vFileName_url="+file_url;
            }
           // gridtable.ajax.reload();
        }
    });
    return false;
}

