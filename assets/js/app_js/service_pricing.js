
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
                    { "data": "iCarrierId", "sortable":true},
                    { "data": "iNetworkId", "sortable":true},
                    { "data": "iConnectionTypeId", "sortable":true},
                    { "data": "iServiceTypeId", "sortable":true, "className": "text-center"},
                    { "data": "iServiceLevel", "sortable":true},
                    { "data": "iNRCVariable", "sortable":true, "className": "text-center"},
                    { "data": "iMRCFixed", "sortable":true, "className": "text-center"},
                    { "data": "vFile", "sortable":false, "className": "text-center"},
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
        //Add Button
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
        $("#stmodaltitle").html('Edit Service Pricing');
        $("#st_mode").val('Update');
        $("#service_pricing_id").val(id);
        $("#iCarrierId").val($("#iCarrierId_"+id).val());
        $("#iNetworkId").val($("#iNetworkId_"+id).val());
        $("#iConnectionTypeId").val($("#iConnectionTypeId_"+id).val());
        $("#iServiceTypeId").val($("#iServiceTypeId_"+id).val());
        $("#iServiceLevel").val($("#iServiceLevel_"+id).val());
        $("#iNRCVariable").val($("#iNRCVariable_"+id).val());
        $("#iMRCFixed").val($("#iMRCFixed_"+id).val());

        $("#vFile").val('');
        $("#vFile_old").val($("#vFile_"+id).val());

        if($("#vFile_"+id).val() != ""){
            var str = '<a href="'+$("#vFile_"+id).val()+'" title="Download"><i class="fa fa-download"></i></a>';
            $("#icon_image").html(str);
        }else{

            $("#icon_image").html('');
        }
       
    }else{
        $("#stmodaltitle").html('Add Service Pricing');
        $("#st_mode").val('Add');
        $("#iServicePricingId").val('');
        $("#iCarrierId").val('');
        $("#iNetworkId").val('');
        $("#iConnectionTypeId").val('');
        $("#iServiceTypeId").val('');
        $("#iServiceLevel").val('');
        $("#iNRCVariable").val('');
        $("#iMRCFixed").val('');
        $("#vFile").val('');
        $("#vFile_old").val('');
        $("#icon_image").html('');
    }
    $("#service_pricing_box").trigger('click');
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
        //var data_str = $("#frmadd").serializeArray();
        var formData = new FormData(form[0]);
        $.ajax({
            type: "POST",
            url: site_url+"master/service_pricing_list",
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
                    url: site_url+"master/service_pricing_list",
                    data: {
                        "mode" : "Delete",
                        "iServicePricingId" : id
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
function exportExcelSheet(){
    $.ajax({
        type: "POST",
        url: site_url+"master/service_pricing_list?mode=Excel",
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