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
                "ajaxSource": site_url+ajax_url,
                "aaSorting": [[0,'desc']],
                'bAutoWidth': true,
                "aoColumns": [
                    { "mData": "iEquipmentId", "sortable":true, "className": "text-center", "width" : "1%"},
                    { "mData": "vModelName", "sortable":true},
                    { "mData": "vSerialNumber", "sortable":true},
                    { "mData": "vMACAddress", "sortable":true},
                    { "mData": "dPurchaseDate", "sortable":true, "className": "text-center"},
                    { "mData": "dWarrantyExpiration", "sortable":true, "className": "text-center"},
                    { "mData": "vPremise", "sortable":true},
                    { "mData": "vPremiseCircuit", "sortable":true},
                    { "mData": "vOperationalStatus", "sortable":true, "className": "text-center"},
                    { "mData": "actions", "sortable":false, "className": "text-center"},
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
                        "data": $(".sorder_search_form").serializeArray(),
                        "success": fnCallback
                    });
                },
        });
        //Add button 
        if(access_group_var_add == '1') {
            gridtable.button().add( 0, {
                action: function ( e, dt, node, config ) {
                    location.href = site_url+"service_order/equipment_add";
                },
                text: '<i class="fa fa-plus"></i>Add',
                className: 'btn btn-primary'
            });
        }

        //Excel button 
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
                    url: site_url+"service_order/equipment_list",
                    data: {
                        "mode" : "Delete",
                        "iEquipmentId" : id
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
	var iDisplayLength = gridtable.rows().count();
    $.ajax({
        type: "POST",
        url: site_url+"service_order/equipment_list?mode=Excel&iDisplayLength="+iDisplayLength,
        data: $("#frmlist").serializeArray(),
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

$('#AdvSearchSubmit').click(function () {
    gridtable.ajax.reload();
    return false;
});

$('#AdvSearchReset').click(function () {
    $('#iSPremiseId').val("");
    $('#PremiseFilterOpDD').val("Contains");
    $('#vPremiseName').val("");
    $('#vSerialNumber').val("");
    $('#vMACAddress').val("");
    $('#vIPAddress').val("");
    $('#vSize').val("");
    $('#vWeight').val("");
    $('#NameFilterOpDD').val("Contains");
    $('#vName').val("");
    $('#CommentFilterOpDD').val("Contains");
    $('#tComments').val("");
    
    gridtable.ajax.reload();
    return false;
});


function getDropdown(vOptions) {
    // alert(vOptions);
    // return false;
    $('#network_dd').show();
    if(vOptions == "vNetwork"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#network_dd').show();

    }else if(vOptions == "vOStatus"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#operational_status_dd').show();

    }else if(vOptions == "vSModelName"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#equipment_model_dd').show();

    }else if(vOptions == "vMaterial"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#material_dd').show();

    }else if(vOptions == "vPType"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#power_type_dd').show();

    }else if(vOptions == "vGrounded"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#grounded_dd').show();

    }else if(vOptions == "vIType"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#install_type_dd').show();

    }else if(vOptions == "vLType"){
        reset_all_fields();
        $('.searching_dd').hide();
        $('#link_type_dd').show();

    }
}

function reset_all_fields(){
  $('#networkId').val('');
  $('#iOStatus').val('');
  $('#iEModel').val('');
  $('#iMaterialId').val('');
  $('#iPowerId').val('');
  $('#iGrounded').val('');
  $('#iInstallTypeId').val('');
  $('#iLinkTypeId').val('');
}