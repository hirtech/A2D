
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
            "ajaxSource": site_url+'contact/list?mode=List',
            "aaSorting": [[0,'desc']],
            'bAutoWidth': true,
            "aoColumns": [
            { "mData": "checkbox", "bSortable":true, "className": "text-center" , "width":"1%"},
            { "mData": "name", "width":"30%"},
            { "mData": "vCompany","bSortable":true, "width":"15%"},
            { "mData": "vPosition","bSortable":true, "className": "text-center", "width":"13%"},
            { "mData": "vPhone", "width":"13%","bSortable":false},
            { "mData": "vEmail", "bSortable":true, "width":"10%"},
            { "mData": "status", "className": "text-center", "width":"10%"},
            { "mData": "actions", "bSortable":false, "className": "text-center", "width":"8%"},
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
           'copy', 'print',
           ],
           fnServerData: function(sSource, aoData, fnCallback,oSettings) {
            oSettings.jqXHR = $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource+'&'+$.param(aoData),
                "data": $(".contact_search_form").serializeArray(),
                "success": fnCallback
            });
        },
    });
        //Add button 
        if(access_group_var_add == '1') {
            gridtable.button().add( 0, {
                action: function ( e, dt, node, config ) {
                    addEditData('','add','','contact');
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
                url: site_url+"contact/list",
                data: {
                    "mode" : "Delete",
                    "iCId" : id
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

$('#AdvSearchReset').click(function () {

    $("#vSalutationDD").val("");
    $('#vFirstNameDD').val("Contains");
    $('#vFirstName').val("");
    $('#vLastNameDD').val("Contains");
    $('#vLastName').val("");
    $('#vEmailDD').val("Contains");
    $('#vEmail').val("");
    $('#vCompanyDD').val("Contains");
    $('#vCompany').val("");
    $('#vPositionDD').val("Contains");
    $('#vPosition').val("");
    
    gridtable.ajax.reload();
    return false;
});

$('#AdvSearchSubmit').click(function () {
 gridtable.ajax.reload();
 return false;
});

function exportExcelSheet(){
    $.ajax({
        type: "POST",
        url: site_url+"contact/list?mode=Excel",
        data: $(".contact_search_form").serializeArray(),
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

