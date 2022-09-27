function addEditDataTaskAdult(id,mode,iSiteId_list){
    if (typeof(infowindow) !== 'undefined' && infowindow) {
        infowindow.close();
    }
    $("#frmadd_adult").removeClass('was-validated');
    $("#errmsg_search_site").html('');
    $("#errmsg_search_site").hide();
    $("#errmsg_dEndTime_adult").html('');
    $("#errmsg_dEndTime_adult").hide();
    //alert("addEditDataTaskAdult")

    var iSiteId1 = iSiteId_list;
    $("#frmadd_adult").removeClass('was-validated');
    $("#vSiteName_adult").typeahead('val','');
      $("#vSR_adult").typeahead('val','');
      $("#lrtechnician_id").select2().val("").trigger('change');
    if(mode == "edit"){
        $("#modal_title_adult").html('Edit Task Landing Rate');
        $("#mode_title_adult").val('Update');
        if (typeof(siteInfoWindowTaskLandingArr) !== 'undefined' && siteInfoWindowTaskLandingArr.length > 0) {
            var iTLRId_s = '';
            var vSiteName_s = '';
            var iSiteId_s = '';
            var dDate_s = '';
            var dStartTime_s = '';
            var dEndTime_s = '';
            var vMaxLandingRate_s = '';
            var iMSpeciesIds_s = '';
            var tNotes_s = '';
            var iSRId_s = '';
            var srdisplay_s = '';
            //var technicianid = sess_iUserId;
            var technicianid = '';
            //alert(JSON.stringify(siteInfoWindowTaskLandingArr))

            $.each(siteInfoWindowTaskLandingArr, function(k, v) {
                iTLRId_s = v['iTLRId'];
                if(id == iTLRId_s ){
                    vSiteName_s = v['vSiteName'];
                    iSiteId_s = v['iSiteId'];
                    dDate_s = v['dDate'];
                    dStartTime_s = v['dStartTime'];
                    dEndTime_s = v['dEndTime'];
                    vMaxLandingRate_s = v['vMaxLandingRate'];
                    iMSpeciesIds_s = v['iMSpeciesId'];
                    tNotes_s = v['tNotes'];
                    iSRId_s = v['iSRId'];
                    srdisplay_s = v['srdisplay'];
                    technicianid = v['iTechnicianId'];
                    
                    //break;
                    return;
                }
            });
            if(iMSpeciesIds_s.indexOf("|||") != '-1'){
                var iMSpeciesId = iMSpeciesIds_s.split("|||");
            }else {
                var iMSpeciesId = iMSpeciesIds_s;
            }

            $("#modal_iTLRId").val(iTLRId_s);
            $("#vSiteName_adult").val(vSiteName_s);
            $("#serach_iSiteId_adult").val(iSiteId_s);
            $("#modal_dDate_adult").val(dDate_s);
            $("#dStartTime_adult").val(dStartTime_s);
            $("#dEndTime_adult").val(dEndTime_s);
            $("#vMaxLandingRate").select2().val(vMaxLandingRate_s).trigger('change');
            $("#iMSpeciesId").select2().val(iMSpeciesId).trigger('change');
            $("#tNotes_other").val(tNotes_s);
            $("#serach_iSRId_adult").val(iSRId_s);
            $("#vSR_adult").val(srdisplay_s);
            $("#lrtechnician_id").val(technicianid);
            $("#lrtechnician_id").select2().val(technicianid).trigger('change');
        } else {
            var iMSpeciesIds = $("#iMSpeciesId_"+id).val();
       
            var iMSpeciesId = iMSpeciesIds.split("|||");
            //alert($("#iSiteId_"+id).val())
            //alert($("#vMaxLandingRate_"+id).val())
            $("#modal_iTLRId").val($("#iTLRId_"+id).val());
            $("#vSiteName_adult").val($("#vSiteName_"+id).val());
            $("#serach_iSiteId_adult").val($("#iSiteId_"+id).val());
            $("#modal_dDate_adult").val($("#dDate_"+id).val());
            $("#dStartTime_adult").val($("#dStartTime_"+id).val());
            $("#dEndTime_adult").val($("#dEndTime_"+id).val());
            $("#vMaxLandingRate").val($("#vMaxLandingRate_"+id).val());
            //$("#vMaxLandingRate").select2().val($("#vMaxLandingRate_"+id).val()).trigger('change')
            //$("#iMSpeciesId").val(iMSpeciesId);
            $("#iMSpeciesId").select2().val(iMSpeciesId).trigger('change')
            $("#tNotes_adult").val($("#tNotes_"+id).val());

            $("#serach_iSRId_adult").val($("#iSRId_"+id).val());
            $("#vSR_adult").val($("#srdisplay_"+id).val());
             $("#lrtechnician_id").val($("#iTechnicianId_"+id).val());

            $("#lrtechnician_id").select2().val($("#iTechnicianId_"+id).val()).trigger('change');
        }
    }else{
        $("#modal_title_adult").html('Add Task Landing Rate');
        $("#mode_title_adult").val('Add');
        $("#modal_iTLRId").val('');
        if(iSiteId1 > 0){
            $("#vSiteName_adult").val(iSiteId1).trigger('change');;
            $("#serach_iSiteId_adult").val(iSiteId1);
        }else {
            $("#vSiteName_adult").val('');
            $("#serach_iSiteId_adult").val('');
        }
        $("#modal_dDate_adult").val(dDate);
        $("#dStartTime_adult").val(dStartTime);
        $("#dEndTime_adult").val(dEndTime);
       // $("#vMaxLandingRate").select2().val('').trigger('change');
        $("#vMaxLandingRate").val('');
        $("#iMSpeciesId").select2().val('').trigger('change');
        //$("#iMSpeciesId").val('');
        $("#tNotes_adult").val('');

        $("#serach_iSRId_adult").val('');
        $("#vSR_adult").val('');

        $("#lrtechnician_id").val(sess_iUserId);

        $("#lrtechnician_id").select2().val(sess_iUserId).trigger('change');
    }
    $("#adult_box").trigger('click');
}
/**************************************************************/
var selectedsr     = null;
(function ($) {
   

    var cluster = new Bloodhound({
      datumTokenizer: function(d) { return d.tokens; },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
        url: site_url+'tasks/task_landing_rate_list&mode=search_site',
        replace: function(url, uriEncodedQuery) {
            var newUrl = url + '&vSiteName_adult=' + uriEncodedQuery;
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
    $('#vSiteName_adult').typeahead({hint: false, highlight: true,minLength: 1 }, 
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
        url: site_url+'tasks/task_landing_rate_list&mode=search_sr',
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
    $('#vSR_adult').typeahead({hint: false, highlight: true,minLength: 1 }, 
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
    $("#serach_iSiteId_adult").val(datum['iSiteId']);
    $("#vSiteName_adult").val(datum['display']);
}

function onSRClusteSelected(e, datum){
    //alert(datum)
    $("#serach_iSRId_adult").val(datum['iSRId']);
    $("#vSR_adult").val(datum['display']);
}
$("#vSR_adult").keyup(function(e) {
	if(e.keyCode == 8){
		if($("#vSR_adult").val() == ""){
	   		$("#serach_iSRId_adult").val("");
	   }
	}   
});

$("#save_data_adult").click(function(){
    $('#save_loading_adult').show();
    $("#save_data_adult").prop('disabled', true);

    var form = $("#frmadd_adult");
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
        form.addClass('was-validated');
        
    if($("#vSiteName_adult").val() == "" || ($("#serach_iSiteId_adult").val() == "" || $("#serach_iSiteId_adult").val() == "0" )){
        $("#errmsg_search_site").html('Please select site');
        $("#errmsg_search_site").show();
        isError = 1;
    }else{
        $("#errmsg_search_site").html('');
        $("#errmsg_search_site").hide();
    }

    if($("#dStartTime_adult").val() != "" && $("#dEndTime_adult").val() != ""){
        if($("#dStartTime_adult").val() > $("#dEndTime_adult").val()){
             $("#errmsg_dEndTime_adult").html('End time can not be less than start time.');
            $("#errmsg_dEndTime_adult").show();
            isError = 1;
        }else{
            $("#errmsg_dEndTime_adult").html('');
            $("#errmsg_dEndTime_adult").hide();
        }       
    }




    if(isError == 0){
        var data_str = $("#frmadd_adult").serializeArray();
        $.ajax({
            type: "POST",
            url: site_url+"tasks/task_landing_rate_list",
            data: data_str,
            success: function(data){
                 $('#save_loading_adult').hide();
                $("#save_data_adult").prop('disabled', false);
                $("#closestbox_adult").trigger('click');
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
        $('#save_loading_adult').hide();
        $("#save_data_adult").prop('disabled', false);
    }
});