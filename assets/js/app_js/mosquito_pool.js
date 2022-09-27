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
                "aaSorting": [[0,'Desc']],
                'bAutoWidth': true,
                "columns": [
                    { "data": "iTMPId", "className": "text-center", "width" : "2%"},
                    { "data": "vName", "width" : "10%"},
                    { "data": "vAddress", "sortable":false, "width" : "10%"},
                    { "data": "dTrapPlaced",  "sortable":true,"className": "text-center" , "width" : "10%"},
                    { "data": "dTrapCollected", "sortable":true, "className": "text-center" , "width" : "10%"},
                    { "data": "vTrapName", "sortable":true, "width" : "10%"},
                    { "data": "tNotes", "sortable":false, "width" : "10%"},
                    { "data": "vPool", "className": "text-center", "width" : "10%"},
                    { "data": "result", "sortable":false, "width" : "20%"},
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

