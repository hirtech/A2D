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
            "aaSorting": [[1,'desc']],
            'bAutoWidth': true,
            "columns": [
            { "data": "checkbox", "sortable":false, "className": "text-center"},
            { "data": "iPremiseId", "className": "text-center"},
            { "data": "vName"},
            { "data": "vSiteType"},
            { "data": "vSiteSubType"},
            { "data": "vAddress", "sortable":false},
            { "data": "vCity"},
            { "data": "vState"},
            { "data": "vZoneName"},
            { "data": "vNetwork"},
            { "data": "vCircuitName", "sortable":false, "className": "text-center"},
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
                    /*{
                        text: '<i class="fa fa-plus"></i>Add',
                        className: 'btn btn-primary',
                        action: function ( e, dt, node, config ) {
                            location.href = site_url+"premise/add";
                        }
                    },*/
                    'copy', 'print',
                    ],
                    fnServerData: function(sSource, aoData, fnCallback,oSettings) {
                        oSettings.jqXHR = $.ajax({
                            "dataType": 'json',
                            "type": "POST",
                            "url": sSource+'&'+$.param(aoData),
                            "data": $(".site_search_form").serializeArray(),
                            "success": fnCallback
                        });
                    },
                });
        //Add button 
        if(access_group_var_add == '1') {
            gridtable.button().add( 0, {
                action: function ( e, dt, node, config ) {
                    location.href = site_url+"premise/add";
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

		gridtable.button().add( 4, {
			action: function ( e, dt, node, config ) {
				var site_list_id = [];
				if ($('#datatable-grid input:checked').length > 0){
					$.each($("input[class='list']:checked"), function(e)
					{
						site_list_id.push($(this).val());
						location.href = site_url+"premise/list?mode=Kml&premiseId="+site_list_id;
					});
				}
				else{
					alert("Please Select At List One PREMISE");
				}
			},
			text: 'KML',
			className: 'btn btn-warning'
		});

        gridtable.button().add( 5, {
            action: function ( e, dt, node, config ) {
                var site_list_id = [];
                if ($('#datatable-grid input:checked').length > 0){
                    $.each($("input[class='list']:checked"), function(e)
                    {
                        site_list_id.push($(this).val());
                        location.href = site_url+"vmap/index&mode=filter_sites&iPremiseId="+site_list_id;
                    });
                }
                else{
                    alert("Please Select At List One PREMISE");
                }
            },
            text: 'Map Selected',
            className: 'btn btn-warning'
        });

        gridtable.button().add( 6, {
            action: function ( e, dt, node, config ) {
                var site_list_id = [];
                if ($('#datatable-grid input:checked').length > 0){
                    $.each($("input[class='list']:checked"), function(e)
                    {
                        site_list_id.push($(this).val());
                        addEditData('','edit',site_list_id,'batch');
                    });
                }
                else{
                    alert("Please Select At least One PREMISE");
                }
            },
            text: 'Batch Edit',
            className: 'btn btn-warning'
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
                url: site_url+"premise/list",
                data: {
                    "mode" : "Delete",
                    "iPremiseId" : id
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
        }
    }
    );
}

$('#AdvSearchSubmit').click(function () {
 gridtable.ajax.reload();
 return false;
});

$('#AdvSearchReset').click(function () {
    //alert('1111');
    $('#premiseId').val("");
    $('#SiteFilterOpDD').val("Contains");
    $('#siteName').val("");
    $('#iSTypeId').val("");
    $('#iSSTypeId').val("");
    $('#AddressFilterOpDD').val("Contains");
    $('#vAddress').val("");
    $('#CityFilterOpDD').val("Contains");
    $('#vCity').val("");
    $('#StateFilterOpDD').val("Contains");
    $('#vState').val("");
    $('#iZoneId').val("");
    $('#iNetworkId').val("");
    $('#status').val("");
    gridtable.ajax.reload();
    return false;
});

function exportExcelSheet(){
  
    var info = gridtable.page.info();
    var pagenum = info.page;
    //console.log('Currently showing page '+(info.page+1)+' of '+info.pages+' pages.');
 
    //Get the total rows
    //console.log( 'Rows '+gridtable.rows().count()+' are selected' );

    var page_length = info.length; //The lengthMenuSetting
    //console.log( 'length '+lengthMenuSetting+' ' );

    var order = gridtable.order(); //Get sorting data of datatable

    var premise_id_arr = [];
    if ($('#datatable-grid input:checked').length > 0){
        $.each($("input[class='list']:checked"), function(e)
        {
            premise_id_arr.push($(this).val());
            
        });
    }
    var data = $(".site_search_form").serializeArray();
    data.push({name :'premiseid_arr' , value: premise_id_arr});
    data.push({name :'pagenum' , value: pagenum});
    data.push({name :'page_length' , value: page_length});
    data.push({name :'sort_col' , value: order[0][0]});
    data.push({name :'sort_order' , value: order[0][1]});

  $.ajax({
    type: "POST",
    url: site_url+"premise/list?mode=Excel",
    data: data,
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


function setupPremiseService(premise_id, premise_circuit_count) {
    if(premise_circuit_count > 0) {
        window.location = site_url+"premise/setup_premise_services_list&iPremiseId="+premise_id;
    }else {
        swal({
            title: "Cannot setup services. Please setup the premise-circuit for this premise first",
            text: "",
            type: "info",
            confirmButtonClass: "confirm btn btn-lg btn-warning",
            confirmButtonText: 'Okay',
            closeOnConfirm: false,
            },
            function(isConfirm) {
                swal.close();
                toastr.success(response['msg']);
                location.href =  site_url+'fiber_inquiry/list';
            }
        );
    }
}

function addEditData(id,mode,premiseid,referer){
    $("#batchfrmadd").removeClass('was-validated');
    $("#batmodaltitle").html('Edit Multiple Premises in a Single Batch');
    $("#bat_mode").val('edit_premises_single_batch');
    $("#premiseid").val(premiseid);
    $("#iSTypeId").val('');
    $("#iSSTypeId1").val('');
    $("#iStatus").val(1);
    
    $("#batch_modalbox").trigger('click');
}

function getSiteSubType(sTypeid){
   $("#iSSTypeId").html('<option value="">---Select---</option>');
   if(sTypeid != ""){
        $.ajax({
            type: "POST",
            url: site_url+"premise/add",
            data: {
                "mode" : "getSiteSubType",
                "iSiteTypeId" : sTypeid
            },
            success: function(data){
                response =JSON.parse(data);

                var option ="<option value=''>---Select---</option>";
                if(response.length > 0 ){
                    $.each(response,function(i,val){
                        option +="<option value='"+response[i]['iSSTypeId']+"'>"+response[i]['vSubTypeName']+"</option>";
                    });
                }
                console.log(option);
                //return false;
                $("#iSSTypeId1").html(option);

                $("#iSSTypeId1").focus();
            }
        });
   }
}


$("#bat_save_data").click(function(){
    var checkerr =0;
    $('#bat_save_loading').show();
    $("#bat_save_data").prop('disabled', true);

    var form = $("#batchfrmadd");
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');
    if(isError == 0){           
        var form_data = $('#batchfrmadd').serializeArray();
        $.ajax({
            type: "POST",
            //dataType: "json",
            url: site_url + "premise/list",
            data: form_data,
            cache: false,
            success: function (data) {
                $('#bat_save_loading').hide();
                $("#bat_save_data").prop('disabled', false);
                $("#closestbox").trigger('click');
                response =JSON.parse(data);
                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                }else{
                    toastr.error(response['msg']);
                }
                gridtable.ajax.reload();
            },
            error: function(xhr, textStatus, errorThrown) {
                //alert(errorThrown);
            }
        });
        return false; 
    }else{
        $('#bat_save_loading').hide();
        $("#bat_save_data").prop('disabled', false);
    }
});
