function addEditDataTaskTrap(id,mode,iPremiseId_list){
    if (typeof(infowindow) !== 'undefined' && infowindow) {
        infowindow.close();
    }
    $("#frmadd_tasktrap").removeClass('was-validated');
    $("#errmsg_search_site").html('');
    $("#errmsg_search_site").hide();
    $("#errmsg_dTrapCollected_tasktrap").html('');
    $("#errmsg_dTrapCollected_tasktrap").hide();

      $("#vSiteName_tasktrap").typeahead('val','');
    
    $("#vSR_tasktrap").typeahead('val','');

    var iPremiseId1 = iPremiseId_list;
    $("#frmadd_tasktrap").removeClass('was-validated');
    $("#ttechnician_id").select2().val("").trigger('change');
    if(mode == "edit"){
        $("#modal_title_tasktrap").html('Edit Task Trap');
        $("#mode_title_tasktrap").val('Update');
        if (typeof(siteInfoWindowTaskTrapArr) !== 'undefined' && siteInfoWindowTaskTrapArr.length > 0) {
            var iTTId_s = '';
            var vSiteName_s = '';
            var iPremiseId_s = '';
            var dTrapPlaced_s = '';
            var dTrapCollected_s = '';
            var iTrapTypeId_s = '';
            var bMalfunction_s = '';
            var tNotes_s = '';
            var iSRId_s = '';
            var srdisplay_s = '';

            //var technicianid = sess_iUserId;
            var technicianid = '';
            $.each(siteInfoWindowTaskTrapArr, function(k, v) {
                iTTId_s = v['iTTId'];
                if(id == iTTId_s ){
                    vSiteName_s = v['vSiteName'];
                    iPremiseId_s = v['iPremiseId'];
                    dTrapPlaced_s = v['dTrapPlaced'];
                    dTrapCollected_s = v['dTrapCollected'];
                    iTrapTypeId_s = v['iTrapTypeId'];
                    bMalfunction_s = v['bMalfunction'];
                    tNotes_s = v['tNotes'];
                    iSRId_s = v['iSRId'];
                    srdisplay_s = v['srdisplay'];
                    technicianid = v['iTechnicianId'];
                    //break;
                    return;
                }
            });
            $("#modal_iTTId").val(iTTId_s);
            $("#vSiteName_tasktrap").val(vSiteName_s);
            $("#serach_iPremiseId_tasktrap").val(iPremiseId_s);

            $("#dTrapPlaced_tasktrap").val(dTrapPlaced_s);
            $("#dTrapCollected_tasktrap").val(dTrapCollected_s);
            $("#iTrapTypeId").select2().val(iTrapTypeId_s).trigger('change');
            if(bMalfunction == 't' || bMalfunction == 1) {
                $("#bMalfunction").prop('checked',true);
            }else{
                $("#bMalfunction").prop('checked',false);
            }
            $("#tNotes_tasktrap").val(tNotes_s);

            $("#serach_iSRId_tasktrap").val(iSRId_s);
            $("#vSR_tasktrap").val(srdisplay_s);
            $("#ttechnician_id").val(technicianid);
            $("#ttechnician_id").select2().val(technicianid).trigger('change');
        } else {
            $("#modal_iTTId").val($("#iTTId_"+id).val());
            $("#vSiteName_tasktrap").val($("#vSiteName_"+id).val());
            $("#serach_iPremiseId_tasktrap").val($("#iPremiseId_"+id).val());
            $("#dTrapPlaced_tasktrap").val($("#dTrapPlaced_"+id).val());
            $("#dTrapCollected_tasktrap").val($("#dTrapCollected_"+id).val());
            $("#iTrapTypeId").select2().val($("#iTrapTypeId_"+id).val()).trigger('change');
            if($("#bMalfunction_"+id).val() == 't') {
                $("#bMalfunction").prop('checked',true);
            }else{
                $("#bMalfunction").prop('checked',false);
            }
            $("#tNotes_tasktrap").val($("#tNotes_"+id).val());

            $("#serach_iSRId_tasktrap").val($("#iSRId_"+id).val());
            $("#vSR_tasktrap").val($("#srdisplay_"+id).val());
             $("#ttechnician_id").val($("#iTechnicianId_"+id).val());

            $("#ttechnician_id").select2().val($("#iTechnicianId_"+id).val()).trigger('change');
        }
    }else{
        $("#modal_title_tasktrap").html('Add Task Trap');
        $("#mode_title_tasktrap").val('Add');
        $("#modal_iTTId").val('');
        if(iPremiseId1 > 0){
            $("#vSiteName_tasktrap").val(iPremiseId1).trigger('change');;
            $("#serach_iPremiseId_tasktrap").val(iPremiseId1);
        }else {
            $("#vSiteName_tasktrap").val('');
            $("#serach_iPremiseId_tasktrap").val();
        }
        //$("#dTrapPlaced_tasktrap").val(dTrapPlaced);
        $("#dTrapPlaced_tasktrap").val(dDate);
        /*var date =new Date($('#dTrapPlaced_tasktrap').val());
        date.setDate(date.getDate() + 1);
        $('#dTrapCollected_tasktrap').val(date.toISOString().substring(0,10));*/
        $('#dTrapCollected_tasktrap').val('');
        $("#iTrapTypeId").select2().val('').trigger('change');
        $("#bMalfunction").prop('checked',false);
        $("#tNotes_tasktrap").val('');

        $("#serach_iSRId_tasktrap").val('');
        $("#vSR_tasktrap").val('');

        $("#ttechnician_id").val(sess_iUserId);

        $("#ttechnician_id").select2().val(sess_iUserId).trigger('change');
    }
    $("#tasktrap_box").trigger('click');
}
/**************************************************************/

(function ($) {
   

    var cluster = new Bloodhound({
      datumTokenizer: function(d) { return d.tokens; },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
        url: site_url+'tasks/task_trap_list&mode=search_site',
        replace: function(url, uriEncodedQuery) {
            var newUrl = url + '&vSiteName_tasktrap=' + uriEncodedQuery;
            return newUrl;
            },
        filter: function(list) {
            if(list==null)
                return {};
            else
                return $.map(list, function(rawdata) { return { display: rawdata.display, iPremiseId:rawdata.iPremiseId }; });
        } 
      }      
    });
    
    cluster.initialize();
    
    select = false;
    $('#vSiteName_tasktrap').typeahead({hint: false, highlight: true,minLength: 1 }, 
    {
        displayKey: 'display',
        source: cluster.ttAdapter(),
    })
    .on('typeahead:selected', onSiteClusteSelected)
    .off('blur')
    .blur(function() {
        $(".tt-dropdown-menu").hide();
    });

    /********************Search sr******************************/
   var clusterSR = new Bloodhound({
      datumTokenizer: function(d) { return d.tokens; },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
        url: site_url+'tasks/task_trap_list&mode=search_fiber_inquiry',
        replace: function(url, uriEncodedQuery) {
            var newUrl = url + '&vSR_search=' + uriEncodedQuery;
            return newUrl;
            },
        filter: function(list) {
            if(list==null)
                return {};
            else
                return $.map(list, function(rawdata) { return { display: rawdata.display, iSRId:rawdata.iSRId }; });
        } 
      }      
    });
    
    clusterSR.initialize();
    
    select = false;
    $('#vSR_tasktrap').typeahead({hint: false, highlight: true,minLength: 1 }, 
    {
        displayKey: 'display',
        source: clusterSR.ttAdapter(),
    })
    .on('typeahead:selected', onSRClusteSelected)
    .off('blur')
    .blur(function() {
        $(".tt-dropdown-menu").hide();
    });
})(jQuery);

function onSiteClusteSelected(e, datum){
    //////alert(datum['iPremiseId'])
    $("#serach_iPremiseId_tasktrap").val(datum['iPremiseId']);
    $("#vSiteName_tasktrap").val(datum['display']);
}

function onSRClusteSelected(e, datum){
    //alert(datum)
    $("#serach_iSRId_tasktrap").val(datum['iSRId']);
    $("#vSR_tasktrap").val(datum['display']);
}
$("#vSR_tasktrap").keyup(function(e) {
    if(e.keyCode == 8){
        if($("#vSR_tasktrap").val() == ""){
            $("#serach_iSRId_tasktrap").val("");
       }
    }   
});

$("#save_data_tasktrap").click(function(){
    $('#save_loading_tasktrap').show();
    $("#save_data_tasktrap").prop('disabled', true);

    var form = $("#frmadd_tasktrap");
    var isError = 0;
    /*if($('#dTrapPlaced_tasktrap').val() > dTrapPlaced) {
        $('#errmsg_dTrapCollected_tasktrap').html("Trap Placed date should be less than or equal to today ");
        isError = 1;
    }

    if($('#dTrapCollected_tasktrap').val() != '' && $('#dTrapPlaced_tasktrap').val() > $('#dTrapCollected_tasktrap').val()) {
        $('#errmsg_dTrapCollected_tasktrap').html("Trap Collected date can not be less than Trap Placed date");
        isError = 1;
    }*/


    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    if($("#vSiteName_tasktrap").val() == "" || ($("#serach_iPremiseId_tasktrap").val() == "" || $("#serach_iPremiseId_tasktrap").val() == "0" )){
        $("#errmsg_search_site").html('Please select site');
        $("#errmsg_search_site").show();
        isError = 1;
    }else{
        $("#errmsg_search_site").html('');
        $("#errmsg_search_site").hide();
    }


    if($('#dTrapCollected_tasktrap').val() != "" && ($('#dTrapPlaced_tasktrap').val() > $('#dTrapCollected_tasktrap').val())) {
        $("#errmsg_dTrapCollected_tasktrap").html('Trap Collected date can not be less than trap date set');
        $("#errmsg_dTrapCollected_tasktrap").show();
        isError = 1;
    }else{
        $("#errmsg_dTrapCollected_tasktrap").html('');
        $("#errmsg_dTrapCollected_tasktrap").hide();
    }  


    form.addClass('was-validated');

    if(isError == 0){
        var data_str = $("#frmadd_tasktrap").serializeArray();
        $.ajax({
            type: "POST",
            url: site_url+"tasks/task_trap_list",
            data: data_str,
            success: function(data){
                $('#save_loading_tasktrap').hide();
                $("#save_data_tasktrap").prop('disabled', false);
                $("#closestbox_tasktrap").trigger('click');
                response =JSON.parse(data);
                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                }else{
                    toastr.error(response['msg']);
                }
                if (typeof(gridtable) !== 'undefined' && gridtable) {
                    gridtable.ajax.reload();
                }
            }
        });
    }else{
        $('#save_loading_tasktrap').hide();
        $("#save_data_tasktrap").prop('disabled', false);
    }
});

$("#dTrapCollected_tasktrap").change(function(){
    var date = new Date($('#dTrapCollected_tasktrap').val());
    date.setDate(date.getDate() - 1);
    $('#dTrapPlaced_tasktrap').val(date.toISOString().substring(0,10));
});