function addEditDataTaskLarval(id,mode,iSiteId_list){
    if (typeof(infowindow) !== 'undefined' && infowindow) {
        infowindow.close();
    }
    $("#frmadd").removeClass('was-validated');
    $("#errmsg_search_site").html('');
    $("#errmsg_search_site").hide(); 
    $("#errmsg_dEndTime").html('');
    $("#errmsg_dEndTime").hide();

    $("#vSiteName").typeahead('val','');
    //alert("addEditDataTaskLarval")
        $("#vSR_surveillance").typeahead('val','');
    var iSiteId1 = iSiteId_list;
    $("#technician_id").select2().val("").trigger('change');
    //alert(mode)
    if(mode == "edit"){
       // alert(id)
        $("#modal_title").html('Edit Task Larval Surveillance');
        $("#mode_title").val('Update');
        if (typeof(siteInfoWindowTaskLarvalArr) !== 'undefined' && siteInfoWindowTaskLarvalArr.length > 0) {
            var iTLSId_s = '';
            var vSiteName_s = '';
            var iSiteId_s = '';
            var dDate_s = '';
            var dStartTime_s = '';
            var dEndTime_s = '';
            var iDips_s = '';
            var iCount_s = '';
            var iCount2_s = '';
            var rAvgLarvel_s = '';
            var iGenus_s = '';
            var iGenus2_s = '';
            var bEggs_s = '';
            var bInstar1_s = '';
            var bInstar2_s = '';
            var bInstar3_s = '';
            var bInstar4_s = '';
            var bPupae_s = '';
            var bAdult_s = '';
            var bEggs2_s = '';
            var bInstar12_s = '';
            var bInstar22_s = '';
            var bInstar32_s = '';
            var bInstar42_s = '';
            var bPupae2_s = '';
            var bAdult2_s = '';
            var tNotes_s = '';
            var iSRId_s = '';
            var srdisplay_s = '';
            //var technicianid = sess_iUserId;
            var technicianid = '';
            //alert(JSON.stringify(siteInfoWindowTaskLarvalArr))

            $.each(siteInfoWindowTaskLarvalArr, function(k, v) {
                iTLSId_s = v['iTLSId'];
                if(id == iTLSId_s ){
                    vSiteName_s = v['vSiteName'];
                    iSiteId_s = v['iSiteId'];
                    dDate_s = v['dDate'];
                    dStartTime_s = v['dStartTime'];
                    dEndTime_s = v['dEndTime'];
                    iDips_s = v['iDips'];
                    iCount_s = v['iCount'];
                    iCount2_s = v['iCount2'];
                    rAvgLarvel_s = v['rAvgLarvel'];
                    iGenus_s = v['iGenus'];
                    iGenus2_s = v['iGenus2'];
                    bEggs_s = v['bEggs'];
                    bInstar1_s = v['bInstar1'];
                    bInstar2_s = v['bInstar2'];
                    bInstar3_s = v['bInstar3'];
                    bInstar4_s = v['bInstar4'];
                    bPupae_s = v['bPupae'];
                    bAdult_s = v['bAdult'];
                    bEggs2_s = v['bEggs2'];
                    bInstar12_s = v['bInstar12'];
                    bInstar22_s = v['bInstar22'];
                    bInstar32_s = v['bInstar32'];
                    bInstar42_s = v['bInstar42'];
                    bPupae2_s = v['bPupae2'];
                    bAdult2_s = v['bAdult2'];
                    tNotes_s = v['tNotes'];
                    iSRId_s = v['iSRId'];
                    srdisplay_s = v['srdisplay'];
                    technicianid = v['iTechnicianId'];
                    //break;
                    return;
                }
            });

            $("#modal_iTLSId").val(iTLSId_s);
            $("#vSiteName").val(vSiteName_s);
            $("#serach_iSiteId_larval").val(iSiteId_s);
            $("#modal_dDate").val(dDate_s);
            $("#dStartTime").val(dStartTime_s);
            $("#dEndTime").val(dEndTime_s);
            $("#iDips").val(iDips_s);
            $("#iCount").val(iCount_s);
            $("#iCount2").val(iCount2_s);
            $("#rAvgLarvel").val(rAvgLarvel_s);
            $("#iGenus").val(iGenus_s);
            $("#iGenus2").val(iGenus2_s);

            if(bEggs_s == 't' || bEggs_s == '1') {
                $("#bEggs").prop('checked',true);
            }else{
                $("#bEggs").prop('checked',false);
            }

            if(bInstar1_s == 't' || bInstar1_s == '1') {
                $("#bInstar1").prop('checked',true);
            }else{
                $("#bInstar1").prop('checked',false);
            }

            if(bInstar2_s == 't' || bInstar2_s == '1') {
                $("#bInstar2").prop('checked',true);
            }else{
                $("#bInstar2").prop('checked',false);
            }

            if(bInstar3_s == 't' || bInstar3_s == '1') {
                $("#bInstar3").prop('checked',true);
            }else{
                $("#bInstar3").prop('checked',false);
            }
            
            if(bInstar4_s == 't' || bInstar4_s == '1') {
                $("#bInstar4").prop('checked',true);
            }else{
                $("#bInstar4").prop('checked',false);
            }

            if(bPupae_s == 't' || bPupae_s == '1') {
                $("#bPupae").prop('checked',true);
            }else{
                $("#bPupae").prop('checked',false);
            }

            if(bAdult_s == 't' || bAdult_s == '1') {
                $("#bAdult").prop('checked',true);
            }else{
                $("#bAdult").prop('checked',false);
            }
            

            if(bEggs2_s == 't' || bEggs2_s == '1') {
                $("#bEggs2").prop('checked',true);
            }else{
                $("#bEggs2").prop('checked',false);
            }

            if(bInstar12_s == 't' || bInstar12_s == '1') {
                $("#bInstar12").prop('checked',true);
            }else{
                $("#bInstar12").prop('checked',false);
            }

            if(bInstar22_s == 't' || bInstar22_s == '1') {
                $("#bInstar22").prop('checked',true);
            }else{
                $("#bInstar22").prop('checked',false);
            }

            if(bInstar32_s == 't' || bInstar32_s == '1') {
                $("#bInstar32").prop('checked',true);
            }else{
                $("#bInstar32").prop('checked',false);
            }
            
            if(bInstar42_s == 't' || bInstar42_s == '1') {
                $("#bInstar42").prop('checked',true);
            }else{
                $("#bInstar42").prop('checked',false);
            }

            if(bPupae2_s == 't' || bPupae2_s == '1') {
                $("#bPupae2").prop('checked',true);
            }else{
                $("#bPupae2").prop('checked',false);
            }

            if(bAdult2_s == 't' || bAdult2_s == '1') {
                $("#bAdult2").prop('checked',true);
            }else{
                $("#bAdult2").prop('checked',false);
            }

            $("#tNotes").val(tNotes_s);

            $("#serach_iSRId_surveillance").val(iSRId_s);
            $("#vSR_surveillance").val(srdisplay_s);

            $("#technician_id").val(technicianid);
            $("#technician_id").select2().val(technicianid).trigger('change');
        } else {
            $("#modal_iTLSId").val($("#iTLSId_"+id).val());
            $("#vSiteName").val($("#vSiteName_"+id).val());
            $("#serach_iSiteId_larval").val($("#iSiteId_"+id).val());
            $("#modal_dDate").val($("#dDate_"+id).val());
            $("#dStartTime").val($("#dStartTime_"+id).val());
            $("#dEndTime").val($("#dEndTime_"+id).val());
            $("#iDips").val($("#iDips_"+id).val());
            $("#iCount").val($("#iCount_"+id).val());
            $("#iCount2").val($("#iCount2_"+id).val());
            $("#rAvgLarvel").val($("#rAvgLarvel_"+id).val());
            $("#iGenus").val($("#iGenus_"+id).val());
            $("#iGenus2").val($("#iGenus2_"+id).val());

            if($("#bEggs_"+id).val() == 't') {
                $("#bEggs").prop('checked',true);
            }else{
                $("#bEggs").prop('checked',false);
            }

            if($("#bInstar1_"+id).val() == 't') {
                $("#bInstar1").prop('checked',true);
            }else{
                $("#bInstar1").prop('checked',false);
            }

            if($("#bInstar2_"+id).val() == 't') {
                $("#bInstar2").prop('checked',true);
            }else{
                $("#bInstar2").prop('checked',false);
            }

            if($("#bInstar3_"+id).val() == 't') {
                $("#bInstar3").prop('checked',true);
            }else{
                $("#bInstar3").prop('checked',false);
            }
            
            if($("#bInstar4_"+id).val() == 't') {
                $("#bInstar4").prop('checked',true);
            }else{
                $("#bInstar4").prop('checked',false);
            }

            if($("#bPupae_"+id).val() == 't') {
                $("#bPupae").prop('checked',true);
            }else{
                $("#bPupae").prop('checked',false);
            }

            if($("#bAdult_"+id).val() == 't') {
                $("#bAdult").prop('checked',true);
            }else{
                $("#bAdult").prop('checked',false);
            }
            

            if($("#bEggs2_"+id).val() == 't') {
                $("#bEggs2").prop('checked',true);
            }else{
                $("#bEggs2").prop('checked',false);
            }

            if($("#bInstar12_"+id).val() == 't') {
                $("#bInstar12").prop('checked',true);
            }else{
                $("#bInstar12").prop('checked',false);
            }

            if($("#bInstar22_"+id).val() == 't') {
                $("#bInstar22").prop('checked',true);
            }else{
                $("#bInstar22").prop('checked',false);
            }

            if($("#bInstar32_"+id).val() == 't') {
                $("#bInstar32").prop('checked',true);
            }else{
                $("#bInstar32").prop('checked',false);
            }
            
            if($("#bInstar42_"+id).val() == 't') {
                $("#bInstar42").prop('checked',true);
            }else{
                $("#bInstar42").prop('checked',false);
            }

            if($("#bPupae2_"+id).val() == 't') {
                $("#bPupae2").prop('checked',true);
            }else{
                $("#bPupae2").prop('checked',false);
            }

            if($("#bAdult2_"+id).val() == 't') {
                $("#bAdult2").prop('checked',true);
            }else{
                $("#bAdult2").prop('checked',false);
            }

            $("#tNotes").val($("#tNotes_"+id).val());
            //$("#tNotes").select2().val($("#tNotes_"+id).val()).trigger('change');

            $("#serach_iSRId_surveillance").val($("#iSRId_"+id).val());
            $("#vSR_surveillance").val($("#srdisplay_"+id).val());

            $("#technician_id").val($("#iTechnicianId_"+id).val());

            $("#technician_id").select2().val($("#iTechnicianId_"+id).val()).trigger('change');
        }

        calculateAvgDips();
    }else{
        //alert(iSiteId1)
        $("#modal_title").html('Add Task Larval Surveillance');
        $("#mode_title").val('Add');
        $("#modal_iTLSId").val('');
        if(iSiteId1 > 0){
            $("#vSiteName").val(iSiteId1).trigger('change');;
            $("#serach_iSiteId_larval").val(iSiteId1);
        }else {
            $("#vSiteName").val('');
            $("#serach_iSiteId_larval").val('');
        }
        $("#modal_dDate").val(dDate);
        $("#dStartTime").val(dStartTime);
        $("#dEndTime").val(dEndTime);
        $("#iDips").val("");
        $("#iCount").val("");
        $("#iCount2").val("");
        $("#rAvgLarvel").val("");
        $("#iGenus").val("");
        $("#iGenus2").val("");
        $("#bEggs").prop('checked',false);
        $("#bInstar1").prop('checked',false);
        $("#bInstar2").prop('checked',false);
        $("#bInstar3").prop('checked',false);
        $("#bInstar4").prop('checked',false);
        $("#bPupae").prop('checked',false);
        $("#bAdult").prop('checked',false);

        $("#bEggs2").prop('checked',false);
        $("#bInstar12").prop('checked',false);
        $("#bInstar22").prop('checked',false);
        $("#bInstar32").prop('checked',false);
        $("#bInstar42").prop('checked',false);
        $("#bPupae2").prop('checked',false);
        $("#bAdult2").prop('checked',false);

        $("#tNotes").val("");

        $("#serach_iSRId_surveillance").val('');
        $("#vSR_surveillance").val('');


        $("#technician_id").val(sess_iUserId);

        $("#technician_id").select2().val(sess_iUserId).trigger('change');
    }
    $("#larval_box").trigger('click');
}
/**************************************************************/

(function ($) {

    if ($('#iCount').val() != '' && $('#iCount2').val() != '' && $('#iDips').val() != '') {
        calculateAvgDips();
    }    

    var cluster = new Bloodhound({
      datumTokenizer: function(d) { return d.tokens; },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
        url: site_url+'tasks/task_larval_surveillance_list&mode=search_site',
        replace: function(url, uriEncodedQuery) {
            var newUrl = url + '&vSiteName=' + uriEncodedQuery;
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
    $('#vSiteName').typeahead({hint: false, highlight: true,minLength: 1 }, 
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
        url: site_url+'tasks/task_larval_surveillance_list&mode=search_fiber_inquiry',
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
    $('#vSR_surveillance').typeahead({hint: false, highlight: true,minLength: 1 }, 
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
    $("#serach_iSiteId_larval").val(datum['iSiteId']);
    $("#vSiteName").val(datum['display']);
}

function onSRClusteSelected(e, datum){
    //alert(datum)
    $("#serach_iSRId_surveillance").val(datum['iSRId']);
    $("#vSR_surveillance").val(datum['display']);
}
$("#vSR_surveillance").keyup(function(e) {
	if(e.keyCode == 8){
		if($("#vSR_surveillance").val() == ""){
	   		$("#serach_iSRId_surveillance").val("");
	   }
	}   
});


function calculateAvgDips()
{

    var count1 = ($.isNumeric($('#iCount').val()))?$('#iCount').val():0;
    var count2 = ($.isNumeric($('#iCount2').val()))?$('#iCount2').val():0;
    var iDips = ($.isNumeric($('#iDips').val()))?$('#iDips').val():0;

    //var tot = (parseInt(count1) + parseInt(count2)) / iDips;
    var tot = (parseFloat(count1) + parseFloat(count2)) / iDips;
    /*if (tot > 0) {
        $('#rAvgLarvel').val(tot.toFixed(2));
    }*/
    if ($.isNumeric(tot)){
        $('#rAvgLarvel').val(tot.toFixed(2));
    }
}

$("#save_data").click(function(){
    $('#save_loading_ls').show();
    $("#save_data").prop('disabled', true);


    var form = $("#frmadd");
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if($("#vSiteName").val() == "" || ($("#serach_iSiteId_larval").val() == ""  || $("#serach_iSiteId_larval").val() == "0" )){
        $("#errmsg_search_site").html('Please select site');
        $("#errmsg_search_site").show();
        isError = 1;
    }else{
        $("#errmsg_search_site").html('');
        $("#errmsg_search_site").hide();
    }
    
    if($("#dStartTime").val() != "" && $("#dEndTime").val() != ""){
        if($("#dStartTime").val() > $("#dEndTime").val()){
             $("#errmsg_dEndTime").html('End time can not be less than start time.');
            $("#errmsg_dEndTime").show();
            isError = 1;
        }else{
            $("#errmsg_dEndTime").html('');
            $("#errmsg_dEndTime").hide();
        }       
    }

    if(isError == 0){
        var data_str = $("#frmadd").serializeArray();
        $.ajax({
            type: "POST",
            url: site_url+"tasks/task_larval_surveillance_list",
            data: data_str,
            success: function(data){
                $('#save_loading_ls').hide();   
                $("#save_data").prop('disabled', false);

                $("#closestbox").trigger('click');
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
        $('#save_loading_ls').hide();   
        $("#save_data").prop('disabled', false);
    }
});
