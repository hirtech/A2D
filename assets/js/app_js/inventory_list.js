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
                "aaSorting": [],
                'bAutoWidth': true,
                "columns": [
                    { "data": "vName", "sortable":false, "width" : "30%","className": "text-left"},
                    { "data": "estlevel", "sortable":false,"width" : "10%","className": "text-center"},
                    { "data": "lastInvCount", "sortable":false,"width" : "10%","className": "text-center"},
                    { "data": "purchInvCount", "sortable":false,"width" : "10%","className": "text-center"},
                    { "data": "usedInvCount", "sortable":false,"width" : "10%","className": "text-center"},
                    { "data": "date", "sortable":false,"width" : "10%","className": "text-center"},
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
                "buttons": [],
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
                    addEditInvCountData('','add');
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


