$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
    listPage.init();
});

var gridtable;
var myData ={};
/*********************************************************************/
/*(function ($) {
    "use strict";
    $('#datatable-grid').DataTable({  
    "bProcessing": true,
    "bServerSide": true,
    "sAjaxSource": site_url+'login_history/list&mode=List&iUserId='+iUserId,
    "aaSorting": [[5,'desc']],
    "aoColumns": [
        { "mData": "checkbox", "bSortable":false, "className": "text-center" , "width":"1%"},
        { "mData": "vUsername", "width":"10%"},
        { "mData": "Name", "width":"20%"},
        { "mData": "vIP", "className": "text-center", "width":"15%"},
        { "mData": "dLoginDate", "className": "text-center","width":"15%"},
        { "mData": "dLogoutDate", "className": "text-center","width":"15%"},
        { "mData": "date_diff","className": "text-center", "width":"45%"},
       
    ],
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
    "buttons": [],
    });
})(jQuery);*/

var listPage = function(){ 
    var handleData =function(){

     myData = $('#frmlist').serializeArray();
        gridtable = $('#datatable-grid').DataTable({  
            "bProcessing": true,
            "bServerSide": true,
           "sAjaxSource": site_url+ajax_url,
           "sAjaxSourceData": myData,
                'serverMethod': 'post',
          /* "ajax": {
                "type" : "POST",
                "url": site_url+ajax_url,
                "data": function ( d ) {
                   return  $.extend(d, myData);
                },
                "success" : function(res){
                    console.log(res);
                    return res;
                }
            },*/
            "aaSorting": [[0,'desc']],
            "aoColumns": [
                { "mData": "checkbox", "bSortable":true, "className": "text-center" , "width":"1%"},
                { "mData": "vUsername", "width":"10%"},
                { "mData": "Name", "width":"20%"},
                { "mData": "vIP", "className": "text-center", "width":"15%"},
                { "mData": "dLoginDate", "className": "text-center","width":"15%"},
                { "mData": "dLogoutDate", "className": "text-center","width":"15%"},
                { "mData": "date_diff","className": "text-center", "width":"45%","bSortable":false},
            ],
            "sDom": 'Bfrtlip',
            "bFilter": false,
            "lengthMenu": PageLengthMenuArr,
            "iDisplayLength": REC_LIMIT,
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
            "buttons": [],
            fnServerData: function(sSource, aoData, fnCallback,oSettings) {
              oSettings.jqXHR = $.ajax( {
                 "dataType": 'json',
                 "type": "POST",
                 //"url": sSource+'?'+$.param(aoData),
                 "url": sSource+'&'+$.param(aoData),
                 "data": $("#frmlist").serializeArray(),
                 "success": fnCallback
              } );
           },
        });
    }
    return {
        init :function () {
           handleData();
        }
    }
}();

/*$('#Search').click(function () {
    var formData = $('#frmlist').serializeArray();
    //datatable1.clear();
    //datatable1.destroy();
    $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: site_url+'login_history/list&mode=List',
            data: formData,
            //cache: false,
            success: function(response) {
                var resp = JSON.stringify(response);
                console.log(resp)
                //table.ajax.reload(null, false);
                // datatable.clear();
                // datatable.rows.add(response);
                // datatable.draw();
              $('#datatable-grid').DataTable().ajax.reload();
               //datatable1.fnClearData();
              // $('#datatable-grid').DataTable().fnAddData(response);
               // datatable1.row.add(response).draw();  
               // datatable.fnAddData(response);
                //$("#datatable-grid").DataTable().ajax.reload();
                 //$('#datatable-grid').DataTable().ajax.reload(null, false);
                //$('#datatable-grid').DataTable().ajax.reload(response,true)
                //$('#datatable-grid').DataTable().clear().rows.add(resp.aaData).draw();


                // var table = $("#datatable-grid").dataTable();
                // oSettings = table.fnSettings();
                //  table.fnClearTable(this);
                // for (var i=0; i < Object.keys(objJson.detail).length; i++)
                // {
                //     table.oApi._fnAddData(oSettings, objJson.detail[i]);
                //     //this part always send error DataTables warning: table id=tbDataTable - Requested unknown parameter '0' for row 0.
                // }
                // oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
                // table.fnDraw();
            }
        });
    return false;
});*/
$('#Search').click(function (){
    // myData = $('#frmlist').serializeArray();
    // gridtable.setAjaxParam('Keyword',$("#Keyword").val());
    // gridtable.setAjaxParam('vOptions',$("#vOptions").val());
    // gridtable.ajax.reload();
    // gridtable.clearAjaxParams();
    // gridtable.ajax.params('Keyword',$("#Keyword").val());
    // gridtable.ax.params('vOptions',$("#vOptions").val());
    // gridtable.ajax.reload();
    // gridtable.clearAjaxParams();
   //var gridtable1 = $('#datatable-grid').DataTable(); //create new object of the datatable
   
    // $.ajax({
    //     type: "POST",
    //     dataType: "json",
    //     url: site_url+ajax_url,
    //     data: dt,
    //     success: function(table_data){
    //         //gridtable.ajax.reload();.
    //          gridtable.clear();
    //        gridtable.rows.add(table_data); // Add new data
    //         gridtable.draw(); 
    //     }
    // });
    gridtable.ajax.reload();
    //$('#datatable-grid').DataTable().destroy();
    //listPage.init();
    return false;
});