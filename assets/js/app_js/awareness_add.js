function addEditDataAwareness(id,mode,iSiteId_list){
    if (typeof(infowindow) !== 'undefined' && infowindow) {
        infowindow.close();
    }
    $("#frmadd_awareness").removeClass('was-validated');
    $("#errmsg_search_site").html('');
    $("#errmsg_search_site").hide();
    $("#errmsg_dEndTime_awareness").html('');
    $("#errmsg_dEndTime_awareness").hide();

    $("#vSiteName_awareness").typeahead('val','');
    $("#vSR_awareness").typeahead('val','');
    $("#otechnician_id").select2().val("").trigger('change');
    var iSiteId1 = iSiteId_list;
    $("#frmadd_awareness").removeClass('was-validated');
    if(mode == "edit"){
        $("#modal_title_awareness").html('Edit Awareness');
        $("#mode_title_awareness").val('Update');
        if (typeof(siteInfoWindowAwarenessArr) !== 'undefined' && siteInfoWindowAwarenessArr.length > 0) {
            var iAId_s = '';
            var vSiteName_s = '';
            var iSiteId_s = '';
            var dDate_s = '';
            var dStartTime_s = '';
            var dEndTime_s = '';
            var iEngagementId_s = '';
            var tNotes_s = '';
            var iSRId_s = '';
            var srdisplay_s = '';

            //var technicianid = sess_iUserId;
            var technicianid = '';
            $.each(siteInfoWindowAwarenessArr, function(k, v) {
                iAId_s = v['iAId'];
                if(id == iAId_s ){
                    vSiteName_s = v['vSiteName'];
                    iSiteId_s = v['iSiteId'];
                    dDate_s = v['dDate'];
                    dStartTime_s = v['dStartTime'];
                    dEndTime_s = v['dEndTime'];
                    iEngagementId_s = v['iEngagementId'];
                    tNotes_s = v['tNotes'];
                    iSRId_s = v['iSRId'];
                    srdisplay_s = v['srdisplay'];
                    technicianid = v['iTechnicianId'];
                    //break;
                    return;
                }
            });
            $("#modal_iAId").val(iAId_s);
            $("#vSiteName_awareness").val(vSiteName_s);
            $("#serach_iSiteId_awareness").val(iSiteId_s);
            $("#modal_dDate_awareness").val(dDate_s);
            $("#dStartTime_awareness").val(dStartTime_s);
            $("#dEndTime_awareness").val(dEndTime_s);
            $("#iEngagementId").select2().val(iEngagementId_s).trigger('change')
            $("#tNotes_awareness").val(tNotes_s);
            $("#serach_iSRId_awareness").val(iSRId_s);
            $("#vSR_awareness").val(srdisplay_s);
            $("#otechnician_id").val(technicianid);
            $("#otechnician_id").select2().val(technicianid).trigger('change');
        } else {
            $("#modal_iAId").val($("#iAId_"+id).val());
            $("#vSiteName_awareness").val($("#vSiteName_"+id).val());
            $("#serach_iSiteId_awareness").val($("#iSiteId_"+id).val());
            $("#modal_dDate_awareness").val($("#dDate_"+id).val());
            $("#dStartTime_awareness").val($("#dStartTime_"+id).val());
            $("#dEndTime_awareness").val($("#dEndTime_"+id).val());
            $("#iEngagementId").select2().val($("#iEngagementId_"+id).val()).trigger('change')
            $("#tNotes_awareness").val($("#tNotes_"+id).val());
            $("#serach_iSRId_awareness").val($("#iSRId_"+id).val());
            $("#vSR_awareness").val($("#srdisplay_"+id).val());
            $("#otechnician_id").val($("#iTechnicianId_"+id).val());
            $("#otechnician_id").select2().val($("#iTechnicianId_"+id).val()).trigger('change');
        }
    }else{
        $("#modal_title_awareness").html('Add Awareness');
        $("#mode_title_awareness").val('Add');
        $("#modal_iAId").val('');
        if(iSiteId1 > 0){
            $("#vSiteName_awareness").val(iSiteId1).trigger('change');;
            $("#serach_iSiteId_awareness").val(iSiteId1);
        }else {
            $("#vSiteName_awareness").val('');
            $("#serach_iSiteId_awareness").val();
        }

        $("#modal_dDate_awareness").val(dDate);
        $("#dStartTime_awareness").val(dStartTime);
        $("#dEndTime_awareness").val(dEndTime);
        $("#iEngagementId").select2().val('').trigger('change')
        $("#tNotes_awareness").val('');

        $("#serach_iSRId_awareness").typeahead('val','');
        $("#vSR_awareness").val('');

        $("#otechnician_id").val(sess_iUserId);

        $("#otechnician_id").select2().val(sess_iUserId).trigger('change');
    }
    $("#awareness_box").trigger('click');
}
/**************************************************************/

(function ($) {
   

    var cluster = new Bloodhound({
      datumTokenizer: function(d) { return d.tokens; },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
        url: site_url+'awareness/awareness_list&mode=search_site',
        replace: function(url, uriEncodedQuery) {
            var newUrl = url + '&vSiteName_awareness=' + uriEncodedQuery;
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
    $('#vSiteName_awareness').typeahead({hint: false, highlight: true,minLength: 1 }, 
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
        url: site_url+'awareness/awareness_list&mode=search_fiber_inquiry',
        replace: function(url, uriEncodedQuery) {
            var newUrl = url + '&vSR_awareness=' + uriEncodedQuery;
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
    $('#vSR_awareness').typeahead({hint: false, highlight: true,minLength: 1 }, 
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
    $("#serach_iSiteId_awareness").val(datum['iSiteId']);
    $("#vSiteName_awareness").val(datum['display']);
}

function onSRClusteSelected(e, datum){
    //alert(datum)
    $("#serach_iSRId_awareness").val(datum['iSRId']);
    $("#vSR_awareness").val(datum['display']);
}
$("#vSR_awareness").keyup(function(e) {
    if(e.keyCode == 8){
        if($("#vSR_awareness").val() == ""){
            $("#serach_iSRId_awareness").val("");
       }
    }   
});

$("#save_data_awareness").click(function(){
    $('#save_loading_awareness').show();
    $("#save_data_awareness").prop('disabled', true);

    var form = $("#frmadd_awareness");
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if($("#vSiteName_awareness").val() == "" || ($("#serach_iSiteId_awareness").val() == "" || $("#serach_iSiteId_awareness").val() == "0" )){
        $("#errmsg_search_site").html('Please select site');
        $("#errmsg_search_site").show();
        isError = 1;
    }else{
        $("#errmsg_search_site").html('');
        $("#errmsg_search_site").hide();
    }

    if($("#dStartTime_awareness").val() != "" && $("#dEndTime_awareness").val() != ""){
	    if($("#dStartTime_awareness").val() > $("#dEndTime_awareness").val()){
	    	 $("#errmsg_dEndTime_awareness").html('End time can not be less than start time.');
	        $("#errmsg_dEndTime_awareness").show();
	        isError = 1;
	    }else{
	        $("#errmsg_dEndTime_awareness").html('');
	        $("#errmsg_dEndTime_awareness").hide();
	    }    	
    }

    if(isError == 0){
        var data_str = $("#frmadd_awareness").serializeArray();
        $.ajax({
            type: "POST",
            url: site_url+"awareness/awareness_list",
            data: data_str,
            success: function(data){
                $('#save_loading_awareness').hide();
                $("#save_data_awareness").prop('disabled', false);
                $("#closestbox_awareness").trigger('click');
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
        $('#save_loading_awareness').hide();
        $("#save_data_awareness").prop('disabled', false);
    }
});