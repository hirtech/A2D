function addEditDataTaskOther(id,mode,iSiteId_list){
    if (typeof(infowindow) !== 'undefined' && infowindow) {
        infowindow.close();
    }
    $("#frmadd_other").removeClass('was-validated');
    $("#errmsg_search_site").html('');
    $("#errmsg_search_site").hide();
    $("#errmsg_dEndTime_other").html('');
    $("#errmsg_dEndTime_other").hide();

    $("#vSiteName_other").typeahead('val','');
    $("#vSR_other").typeahead('val','');
      $("#otechnician_id").select2().val("").trigger('change');
    //alert("addEditDataTaskother")
    //alert(JSON.stringify(siteInfoWindowTaskArr))
    var iSiteId1 = iSiteId_list;
    $("#frmadd_other").removeClass('was-validated');
    if(mode == "edit"){
        $("#modal_title_other").html('Edit Task Other');
        $("#mode_title_other").val('Update');
        if (typeof(siteInfoWindowTaskOtherArr) !== 'undefined' && siteInfoWindowTaskOtherArr.length > 0) {
            var iTOId_s = '';
            var vSiteName_s = '';
            var iSiteId_s = '';
            var dDate_s = '';
            var dStartTime_s = '';
            var dEndTime_s = '';
            var iTaskTypeId_s = '';
            var tNotes_s = '';
            var iSRId_s = '';
            var srdisplay_s = '';

            //var technicianid = sess_iUserId;
            var technicianid = '';
            $.each(siteInfoWindowTaskOtherArr, function(k, v) {
                iTOId_s = v['iTOId'];
                if(id == iTOId_s ){
                    vSiteName_s = v['vSiteName'];
                    iSiteId_s = v['iSiteId'];
                    dDate_s = v['dDate'];
                    dStartTime_s = v['dStartTime'];
                    dEndTime_s = v['dEndTime'];
                    iTaskTypeId_s = v['iTaskTypeId'];
                    tNotes_s = v['tNotes'];
                    iSRId_s = v['iSRId'];
                    srdisplay_s = v['srdisplay'];
                    technicianid = v['iTechnicianId'];
                    //break;
                    return;
                }
            });
            $("#modal_iTOId").val(iTOId_s);
            $("#vSiteName_other").val(vSiteName_s);
            $("#serach_iSiteId_other").val(iSiteId_s);
            $("#modal_dDate_other").val(dDate_s);
            $("#dStartTime_other").val(dStartTime_s);
            $("#dEndTime_other").val(dEndTime_s);
            $("#iTaskTypeId").select2().val(iTaskTypeId_s).trigger('change')
            $("#tNotes_other").val(tNotes_s);
            $("#serach_iSRId_other").val(iSRId_s);
            $("#vSR_other").val(srdisplay_s);
            $("#otechnician_id").val(technicianid);
            $("#otechnician_id").select2().val(technicianid).trigger('change');
        } else {
            $("#modal_iTOId").val($("#iTOId_"+id).val());
            $("#vSiteName_other").val($("#vSiteName_"+id).val());
            $("#serach_iSiteId_other").val($("#iSiteId_"+id).val());
            $("#modal_dDate_other").val($("#dDate_"+id).val());
            $("#dStartTime_other").val($("#dStartTime_"+id).val());
            $("#dEndTime_other").val($("#dEndTime_"+id).val());
            $("#iTaskTypeId").select2().val($("#iTaskTypeId_"+id).val()).trigger('change')
            $("#tNotes_other").val($("#tNotes_"+id).val());
            $("#serach_iSRId_other").val($("#iSRId_"+id).val());
            $("#vSR_other").val($("#srdisplay_"+id).val());
             $("#otechnician_id").val($("#iTechnicianId_"+id).val());
            $("#otechnician_id").select2().val($("#iTechnicianId_"+id).val()).trigger('change');
        }
    }else{
        $("#modal_title_other").html('Add Task Other');
        $("#mode_title_other").val('Add');
        $("#modal_iTOId").val('');
        if(iSiteId1 > 0){
            $("#vSiteName_other").val(iSiteId1).trigger('change');;
            $("#serach_iSiteId_other").val(iSiteId1);
        }else {
            $("#vSiteName_other").val('');
            $("#serach_iSiteId_other").val();
        }

        $("#modal_dDate_other").val(dDate);
        $("#dStartTime_other").val(dStartTime);
        $("#dEndTime_other").val(dEndTime);
        $("#iTaskTypeId").select2().val('').trigger('change')
        $("#tNotes_other").val('');

        $("#serach_iSRId_other").typeahead('val','');
        $("#vSR_other").val('');

        $("#otechnician_id").val(sess_iUserId);

        $("#otechnician_id").select2().val(sess_iUserId).trigger('change');
    }
    $("#other_box").trigger('click');
}
/**************************************************************/

(function ($) {
   

    var cluster = new Bloodhound({
      datumTokenizer: function(d) { return d.tokens; },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
        url: site_url+'tasks/task_other_list&mode=search_site',
        replace: function(url, uriEncodedQuery) {
            var newUrl = url + '&vSiteName_other=' + uriEncodedQuery;
            return newUrl;
            },
        filter: function(list) {
            if(list==null)
                return {};
            else
                return $.map(list, function(rawdata) { return { display: rawdata.display, iSiteId:rawdata.iSiteId }; });
        } 
      }      
    });
    
    cluster.initialize();
    
    select = false;
    $('#vSiteName_other').typeahead({hint: false, highlight: true,minLength: 1 }, 
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
        url: site_url+'tasks/task_other_list&mode=search_sr',
        replace: function(url, uriEncodedQuery) {
            var newUrl = url + '&vSR_other=' + uriEncodedQuery;
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
    $('#vSR_other').typeahead({hint: false, highlight: true,minLength: 1 }, 
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
    //////alert(datum['iSiteId'])
    $("#serach_iSiteId_other").val(datum['iSiteId']);
    $("#vSiteName_other").val(datum['display']);
}

function onSRClusteSelected(e, datum){
    //alert(datum)
    $("#serach_iSRId_other").val(datum['iSRId']);
    $("#vSR_other").val(datum['display']);
}
$("#vSR_other").keyup(function(e) {
    if(e.keyCode == 8){
        if($("#vSR_other").val() == ""){
            $("#serach_iSRId_other").val("");
       }
    }   
});

$("#save_data_other").click(function(){
    $('#save_loading_other').show();
    $("#save_data_other").prop('disabled', true);

    var form = $("#frmadd_other");
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if($("#vSiteName_other").val() == "" || ($("#serach_iSiteId_other").val() == "" || $("#serach_iSiteId_other").val() == "0" )){
        $("#errmsg_search_site").html('Please select site');
        $("#errmsg_search_site").show();
        isError = 1;
    }else{
        $("#errmsg_search_site").html('');
        $("#errmsg_search_site").hide();
    }

    if($("#dStartTime_other").val() != "" && $("#dEndTime_other").val() != ""){
	    if($("#dStartTime_other").val() > $("#dEndTime_other").val()){
	    	 $("#errmsg_dEndTime_other").html('End time can not be less than start time.');
	        $("#errmsg_dEndTime_other").show();
	        isError = 1;
	    }else{
	        $("#errmsg_dEndTime_other").html('');
	        $("#errmsg_dEndTime_other").hide();
	    }    	
    }

    if(isError == 0){
        var data_str = $("#frmadd_other").serializeArray();
        $.ajax({
            type: "POST",
            url: site_url+"tasks/task_other_list",
            data: data_str,
            success: function(data){
                $('#save_loading_other').hide();
                $("#save_data_other").prop('disabled', false);
                $("#closestbox_other").trigger('click');
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
        $('#save_loading_other').hide();
        $("#save_data_other").prop('disabled', false);
    }
});