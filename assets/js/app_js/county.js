$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
    listPage.init();
});
var listmode = "List";
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
                    { "data": "checkbox", "sortable":true, "className": "text-center","width" : "5%"},
                    { "data": "vCounty", "sortable":true,"width" : "85%"},
                    { "data": "actions", "sortable":false, "className": "text-center","width" : "10%"},
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
                    addEditData('','add');
                }
            });
        }
        //'excel'
        gridtable.button().add( 3, {
                text: 'Excel',
                className: 'btn btn-secondary',
                action: function ( e, dt, node, config ) {
                    exportExcelSheet();
                }
            });
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

function addEditData(id,mode){
    $("#frmadd").removeClass('was-validated');
    if(mode == "edit"){
        $("#stmodaltitle").html('Edit County');
        $("#st_mode").val('Update');
        $("#county_id").val($("#county_id_"+id).val());
        $("#vCounty").val($("#county_name_"+id).val());
        
      
    }else{
        $("#stmodaltitle").html('Add County');
        $("#st_mode").val('Add');
        
        $("#vCounty").val('');
       
    }
    $("#county_box").trigger('click');
}


$("#save_data").click(function(){
    $('#save_loading').show();
    $("#save_data").prop('disabled', true);
    var form = $("#frmadd")
    //alert(form[0].checkValidity())
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if(isError == 0){
        var data_str = $("#frmadd").serializeArray();
        $.ajax({
            type: "POST",
            url: site_url+"master/county_list",
            data: data_str,
            success: function(data){
                $('#save_loading').hide();
                $("#save_data").prop('disabled', false);
                
                $("#closestbox").trigger('click');
                response =JSON.parse(data);
                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                }else{
                    toastr.error(response['msg']);
                }
                gridtable.ajax.reload();
            }
        });
    }else{
        $('#save_loading').hide();   
        $("#save_data").prop('disabled', false);
    }
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
                    url: site_url+"master/county_list",
                    data: {
                        "mode" : "Delete",
                        "iCountyId" : id
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
function exportExcelSheet(){
    var iDisplayLength = gridtable.rows().count();
    $.ajax({
        type: "POST",
        url: site_url+"master/county_list?mode=Excel&iDisplayLength="+iDisplayLength,
        data: $("#frmlist").serializeArray(),
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