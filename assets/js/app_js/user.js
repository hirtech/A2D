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
                "aaSorting": [[7,'desc']],
                'bAutoWidth': true,
                "aoColumns": [
                    { "mData": "checkbox", "bSortable":true, "className": "text-center" , "width":"1%"},
                    { "mData": "name", "width":"12%"},
                    { "mData": "vEmail", "width":"12%","bSortable":false},
                    { "mData": "vUsername", "className": "text-center", "width":"10%"},
                    { "mData": "vDepartment", "width":"15%","bSortable":false},
                    { "mData": "vAccessGroup", "width":"10%"},
                    { "mData": "vLoginHistory", "bSortable":false, "className": "text-center", "width":"6%"},
                    { "mData": "dDate", "className": "text-center", "width":"10%"},
                    { "mData": "iStatus", "className": "text-center", "width":"6%"},
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

function getCountyFromState(obj, iCountyId, iCityId) {
    if (iCountyId == "" || iCountyId == "undefined")
        iCountyId = 0;

    $('#iCountyId').after('<img src="assets/images/loading-small.gif" border="0" class="loading">');
    $.ajax({
        type: "POST",
        dataType: "json",
        url: site_url + "user/list",
        data: 'mode=GetCountyFromState&pageMode=' + $("#mode").val() + '&iStateId=' + $(obj).val() + '&iCountyId=' + iCountyId,
        success: function (data) {
            $('#iCountyId').next().remove();
            {
                $('#iCountyId').html(data.county);
                getCityFromCounty($('#iCountyId'), iCityId);
            }
        }
    });
}

function getCityFromCounty(obj, iCityId) {
    if (iCityId == "" || iCityId == "undefined")
        iCityId = 0;

    $('#iCityId').after('<img src="assets/images/loading-small.gif" border="0" class="loading">');
    $.ajax({
        type: "POST",
        dataType: "json",
        url: site_url + "user/list",
        data: 'mode=GetCityFromCounty&pageMode=' + $("#mode").val() + '&iCountyId=' + $(obj).val() + '&iCityId=' + iCityId,
        success: function (data) {
            $('#iCityId').next().remove();
            $('#iCityId').html(data.city);
        }
    });
}

function checkDuplicateUser() {
    var vUsername = Trim($('#vUsername').val());
    $.ajax({
        type: "POST",
        dataType: "json",
        url: site_url + "user/list?mode=DuplicateUsernameCheck",
        data: "vUsername=" + vUsername,
        success: function (data) {
            if (data.total == 1) {
                $('#duplicate_msg').html("Username already exist.").show();
                //$('#vUsername').focus();
                //return false;
            }
            else {
                $('#duplicate_msg').html("").hide();
            }
        }
    });
}

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
   gridtable.ajax.reload();
    //$("#grid").flexOptions({params: ''}).flexReload();
    return false;
});

$('#AdvSearchSubmit').click(function () {
 gridtable.ajax.reload();
    return false;
});
function exportExcelSheet(){
  //  console.log('11111');
    $.ajax({
        type: "POST",
        url: site_url+"user/list?mode=Excel",
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
/*****************************************************************************/
/*(function ($) {
    "use strict";

    $('#datatable-grid').DataTable({  
       // "stateSave": true,     
       // "processing": true,
       // "serverSide": true,
       // "info": true,
       // "bLengthChange": false,   
       //  "ajax":{
       //      "url":  site_url+'user/user_list?mode=List',
       //      "type": "GET",
       //  },
       //  //"aaSorting": [[ 0, "DESC" ]],
       //  "columns": [
       //      { "data": "checkbox", "name": "checkbox",  "orderable": false},
       //      { "data": "name", "name": "name", "orderable": true },
       //      { "data": "vEmail", "name": "vEmail", "orderable": true },
       //      { "data": "vUsername", "name": "vUsername", "orderable": true },
       //      { "data": "vDepartment", "orderable": false },
       //      { "data": "vAccessGroup", "orderable": true },
       //      { "data": "vLoginHistory", "orderable": true},
       //      { "data": "dDate", "orderable": true },
       //      { "data": "iStatus", "orderable": true },
       //      { "data": "actions", "orderable": false },
       //  ],
       //  //"pagingType": "full_numbers",
       //  //"lengthMenu": [ 10, 15, 20, 30, 50, 100, 200 ],
       //  "sDom": 'Rfrtlip',

        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": site_url+'user/list?mode=List',
        "aaSorting": [[7,'desc']],
        "aoColumns": [
            { "mData": "checkbox", "bSortable":false, "className": "text-center" , "width":"1%"},
            { "mData": "name", "width":"12%"},
            { "mData": "vEmail", "width":"12%"},
            { "mData": "vUsername", "className": "text-center", "width":"10%"},
            { "mData": "vDepartment", "width":"15%"},
            { "mData": "vAccessGroup", "width":"10%"},
            { "mData": "vLoginHistory", "bSortable":false, "className": "text-center", "width":"6%"},
            { "mData": "dDate", "className": "text-center", "width":"10%"},
            { "mData": "iStatus", "className": "text-center", "width":"6%"},
            { "mData": "actions", "bSortable":false, "className": "text-center", "width":"10%"},
        ],
        //"sDom": 'Rfrtlip',
        "sDom": 'Bfrtlip',
        "bFilter": false,
        "pagingType": "full_numbers",
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
            'copy', 'excel', 'print'
        ],
        // "drawCallback": function (settings) {
        //     $('#datatable-grid').editableTableWidget({ 
        //         editor: $('<input>' , '<select>'), 
        //         activeColumns: [2, 6] 
        //     }).on('change', function(evt, newValue) {
        //         //alert($(this).closest(".list").val())
        //         $.post( site_url+'user/user_list?mode=edit_cell', { value: newValue })
        //             .done(function( data ) {
        //                 //alert( "Data Loaded: " + data );
        //             }); 
        //         ;
        //     });
        // }
    });
})(jQuery);*/
