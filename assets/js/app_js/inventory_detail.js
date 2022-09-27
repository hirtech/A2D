$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
    listPage.init();

});
var listmode = "List";
var gridtable;
var total;
/*********************************************************************/
var listPage = function(){ 
    var handleData =function(){
        gridtable = $('#datatable-grid').DataTable({  
            "processing": true,
                "serverSide": true,
                "orderMulti" : false,
                "paging":   true,
                "ordering": false,
                "ajaxSource": site_url+ajax_url,
                "aaSorting": [],
                'bAutoWidth': true,
                "columns": [
                    { "data": "dDate", "sortable":false, "width" : "10%","className": "text-center"},
                    { "data": "purchase", "sortable":false,"width" : "10%","className": "text-right"},
                    { "data": "uses", "sortable":false,"width" : "10%" ,"className": "text-right"},
                    { "data": "balance", "sortable":false,"width" : "10%" ,"className": "text-right"},
                    { "data": "actions", "sortable":false,"width" : "10%" ,"className": "text-left"},
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
                    /*"oPaginate": {
                       "sNext": '<i class="fa fa-forward"></i>',
                       "sPrevious": '<i class="fa fa-backward"></i>',
                       "sFirst": '<i class="fa fa-step-backward"></i>',
                       "sLast": '<i class="fa fa-step-forward"></i>'
                    },*/
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
                colReorder: true, 
                footerCallback: function ( row, data, start, end, display) {
                        // console.log(data);
                   
                    var apidata = data;
                    var api = this.api(), data;
                    // converting to interger to find total
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
 
                    var purchTotal = intVal(apidata[0]['total_purchase']);
                    var blnTotal = intVal(apidata[0]['total_balance']);
                    var usesTotal = intVal(apidata[0]['total_uses']);
                    // computing column Total of the complete result 
                   /* var purchTotal = api
                        .column( 1 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );*/

                    
                    /*var usesTotal = api
                            .column( 2 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                    
                    var blnTotal = api
                        .column( 3 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );*/
                
                    // Update footer by showing the total with the reference of the column index 
                   $( api.column( 0 ).footer() ).html('Total');
                    $( api.column( 1 ).footer() ).html(purchTotal.toFixed(2));
                    $( api.column( 2 ).footer() ).html(usesTotal.toFixed(2));
                    $( api.column( 3 ).footer() ).html(blnTotal.toFixed(2));
                },
        });
        
        //Add button 
        if(access_group_var_add == '1') {
            gridtable.button().add( 0, {
                text: 'New Purchase',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    addEditPurchaseData('','addPurchase');
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


function addEditPurchaseData(id,mode){
    $("#frmadd_invdt").removeClass('was-validated');

    if(mode=="edit"){
        $("#invdt_modaltitle").html('Edit Inventory Purchase Detail');
        $("#invdt_mode").val('EditPurchaseData');
        $("#invdt_iIPId").val(id);
        $("#invdt_rPurQty").val($("#ind_rPurQty_"+id).val());
        $("#invdt_dPurDate").val($("#ind_dPurDate_"+id).val());
        $("#invdetail_box").trigger('click'); 
    }else{
        $("#invdt_modaltitle").html('Add Inventory Purchase Detail');
        $("#invdt_mode").val('AddPurchaseData');
        $("#invdt_iIPId").val('');
        $("#invdt_rPurQty").val('');
        //$("#invdt_dPurDate").val('');
        $("#invdt_dPurDate").val(dDate);
        $("#invdetail_box").trigger('click');       
    }

}


$("#invdt_save_data").click(function(){
    $('#save_loading').show();
    $("#invdt_save_data").prop('disabled', true);
    var form = $("#frmadd_invdt")
    //alert(form[0].checkValidity())
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if(isError == 0){
        var data_str = $("#frmadd_invdt").serializeArray();
        $.ajax({
            type: "POST",
            url: site_url+"inventory/inventory_detail",
            data: data_str,
            success: function(data){
                $('#save_loading').hide();
                $("#invdt_save_data").prop('disabled', false);
                
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
        $("#invdt_save_data").prop('disabled', false);
    }
});