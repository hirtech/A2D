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
                    { "data": "checkbox", "sortable":true, "className": "text-center", "width" : "1%"},
                    { "data": "vTypeName", "width" : "71%"},
                    { "data": "icon", "sortable":false, "className": "text-center", "width" : "10%"},
                    { "data": "iStatus", "className": "text-center", "width" : "10%"},
                    { "data": "actions", "sortable":false, "className": "text-center", "width" : "8%"},
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
        $("#stmodaltitle").html('Edit Premise Type');
        $("#st_mode").val('Update');
        $("#sitetype_id").val($("#stype_id_"+id).val());
        $("#vTypeName").val($("#stype_name_"+id).val());
        var status = $("#stype_status_"+id).val();
        if(status == "Active"){
            $("#iStatus").prop('checked',true).change();
        }else if(status == "Inactive"){
            $("#iStatus").prop('checked',false).change();
        }else{
            $("#iStatus").prop('checked',false).change();
        }
        $("#siteIcon").val('');
        $("#siteIcon_old").val($("#stype_icon_"+id).val());


        if($("#stype_icon_url_"+id).val() != ""){
            var str = '<img src="'+$("#stype_icon_url_"+id).val()+'" alt="" class="d-inline-flex img-fluid rounded-circle ml-2">'
            $("#icon_image").html(str);
        }else{

            $("#icon_image").html('');
        }
        
    }else{
        $("#stmodaltitle").html('Add Premise Type');
        $("#st_mode").val('Add');
        $("#sitetype_id").val('');
        $("#vTypeName").val('');
        $("#siteIcon").val('');
        $("#siteIcon_old").val('');
        $("#icon_image").html('');
        $("#icon_image").html('');
        $("#iStatus").prop('checked',true).change();

    }
    $("#sitetype_box").trigger('click');
}


$("#save_data").click(function(){
    $('#save_loading').show();
    $("#save_data").prop('disabled', true);
    
    var form = $("#frmadd");
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if(isError == 0){
      //  var data_str = $("#frmadd").serializeArray();
       
        var formData = new FormData(form[0]);

        $.ajax({
            type: "POST",
            url: site_url+"master/premise_type_list",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType : 'json',
            success: function(response){
                $('#save_loading').hide();   
                $("#save_data").prop('disabled', false);

                $("#closestbox").trigger('click');
                //response =JSON.parse(data);
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
                    url: site_url+"master/premise_type_list",
                    data: {
                        "mode" : "Delete",
                        "iSTypeId" : id
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
  //  console.log('11111');
    $.ajax({
        type: "POST",
        url: site_url+"master/premise_type_list?mode=Excel",
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