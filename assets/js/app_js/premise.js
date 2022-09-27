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
            { "data": "checkbox", "sortable":false, "className": "text-center", "width" : "1%"},
            { "data": "iSiteId", "className": "text-center", "width" : "8%"},
            { "data": "vName" , "width" : "10%"},
            { "data": "vSiteType", "width" : "10%" },
            { "data": "vSiteSubType", "width" : "10%"},
            { "data": "vAddress", "sortable":false, "width" : "10%"},
            { "data": "vCity", "width" : "8%"},
            { "data": "vState", "width" : "8%"},
            { "data": "vZoneName", "width" : "8%"},
            { "data": "vNetwork", "width" : "8%"},
            { "data": "iStatus", "className": "text-center", "width" : "6%"},
            { "data": "actions", "sortable":false, "className": "text-center", "width" : "14%"},
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
						location.href = site_url+"premise/list?mode=Kml&siteId="+site_list_id;
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
                        location.href = site_url+"vmap/index&mode=filter_sites&iSiteId="+site_list_id;
                    });
                }
                else{
                    alert("Please Select At List One PREMISE");
                }
            },
            text: 'Map Selected',
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
                    "iSiteId" : id
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
    $('#siteId').val("");
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
    $('#CountryFilterOpDD').val("Contains");
    $('#vCountry').val("");
    $('#status').val("");
    $('#iGeometryType').val("");
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

    var site_id_arr = [];
    if ($('#datatable-grid input:checked').length > 0){
        $.each($("input[class='list']:checked"), function(e)
        {
            site_id_arr.push($(this).val());
            
        });
    }
    var data = $(".site_search_form").serializeArray();
    data.push({name :'siteid_arr' , value: site_id_arr});
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
