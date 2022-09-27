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
                    { "data": "Date" , "width" : "1%"},
                    { "data": "Name", "width" : "4%","sortable":false},
                    { "data": "Description" , "width" : "5%","sortable":false},
                ],
                "autoWidth" : true,
                "iDisplayLength": REC_LIMIT,
                 "lengthMenu": PageLengthMenuArr,
               //  "iDisplayLength": 5,
               // "lengthMenu": [ [2, 4, 5, -1], [2, 4,5, "All"] ],
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
                    // 'copy', 'print', 'excel',
                ],
                fnServerData: function(sSource, aoData, fnCallback,oSettings) {
                    //console.log(sSource+'&'+$.param(aoData));
                    oSettings.jqXHR = $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": sSource+'&'+$.param(aoData),
                       // "data": $(".site_search_form").serializeArray(),
                        "success": fnCallback
                    });
                },
        });
        //Add excel button
        /*if(access_group_var_CSV == '1'){
            gridtable.button().add( 2, {
                text: 'Excel',
                className: 'btn btn-secondary',
                action: function ( e, dt, node, config ) {
                    exportExcelSheet();
                }
            });
        }*/
    }
    return {
        init :function () {
           handleData();
        }
    }
}();

// function exportExcelSheet(){
//   //  console.log('11111');
//     $.ajax({
//         type: "POST",
//         url: site_url+"premise/list?mode=Excel",
//         data: $(".site_search_form").serializeArray(),
//         success: function(data){
//             res = JSON.parse(data);
//            // console.log(res);
//             isError = res['isError'];
//             if(isError == 0) {
//                 file_path = res['file_path'];
//                 file_url = res['file_url'];
//                 window.location = site_url+"download.php?vFileName_path="+file_path+"&vFileName_url="+file_url;
//             }
//            // gridtable.ajax.reload();
//         }
//     });
//     return false;
// }




