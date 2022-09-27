var application_rate;
var area_treated;
function addEditDataTaskTreatment(id,mode,iSiteId_list){
    if (typeof(infowindow) !== 'undefined' && infowindow) {
        infowindow.close();
    }
    $("#frmadd_treatment").removeClass('was-validated');

    $("#errmsg_search_site").hide();
    $("#errmsg_dEndTime_treatment").hide();
    $("#errmsg_vTrProduct_treatment").hide();
    $("#errmsg_amountapplied").hide();


    $("#modal_vSiteName_treatment").typeahead('val','');
    $("#modal_vTrProduct_treatment").typeahead('val','');

    $("#vSR_treatment").typeahead('val','');
      $("#trtechnician_id").select2().val("").trigger('change');
    var iSiteId1 = iSiteId_list;
    if(mode == "edit"){
        $("#modal_title_treatment").html('Edit Task Treatment');
        $("#mode_title_treatment").val('Update');
        var iUId_s = '';
        var iParentId_s = '';
        if (typeof(siteInfoWindowTaskTreatmentArr) !== 'undefined' && siteInfoWindowTaskTreatmentArr.length > 0) {
            var iTreatmentId_s = '';
            var vSiteName_s = '';
            var iSiteId_s = '';
            var dDate_s = '';
            var dStartTime_s = '';
            var dEndTime_s = '';
            var vType_s = '';
            var iTPId_s = '';
            var vName_s = '';
            var vAppRate_s = '';
            var vArea_s = '';
            var vAreaTreated_s = '';
            var vAmountApplied_s = '';
            var iSRId_s = '';
            var srdisplay_s = '';
            //var technicianid = sess_iUserId;
            var technicianid = '';

            $.each(siteInfoWindowTaskTreatmentArr, function(k, v) {

                iTreatmentId_s = v['iTreatmentId'];
                if(id == iTreatmentId_s ){
                    vSiteName_s = v['vSiteName'];
                    iSiteId_s = v['iSiteId'];
                    dDate_s = v['dDate'];
                    dStartTime_s = v['dStartTime'];
                    dEndTime_s = v['dEndTime'];
                    vType_s = v['vType'];
                    iTPId_s = v['iTPId'];
                    vName_s = v['vName'];
                    vAppRate_s = v['vAppRate'];
                    vArea_s = v['vArea'];
                    vAreaTreated_s = v['vAreaTreated'];
                    vAmountApplied_s = v['vAmountApplied'];
                    iUId_s = v['iUId'];
                    iParentId_s = v['iParentId'];
                    iSRId_s = v['iSRId'];
                    srdisplay_s = v['srdisplay'];

                    technicianid = v['iTechnicianId'];


                    application_rate= vAppRate_s.substr(0, vAppRate_s.indexOf('(min'));
                    area_treated = vArea_s;
                    //break;
                    return;
                }
            });
            $("#modal_iTreatmentId").val(iTreatmentId_s);
     
            $("#modal_vSiteName_treatment").val(vSiteName_s);
            $("#serach_iSiteId_treatment").val(iSiteId_s);

            $("#modal_dDate_treatment").val(dDate_s);
            //$("#modal_vType_treatment").val($("#tt_vType_"+id).val());
            $("#modal_vType_treatment").select2().val(vType_s).trigger('change');
            $("#dStartTime_treatment").val(dStartTime_s);
            $("#dEndTime_treatment").val(dEndTime_s);
            $("#modal_vTrProduct_treatment").val(vName_s);
            //$("#modal_vTrProduct_treatment").typeahead('val',$("#tt_iTPName_"+id).val(),true);

            $("#serach_iTPId_treatment").val(iTPId_s);
            $("#modal_vAppRate_treatment").val(vAppRate_s);
            $("#modal_vArea_treatment").val(vArea_s);
            //$("#modal_vAreaTreated_treatment").val($("#tt_vAreaTreated_"+id).val());       
             $("#modal_vAreaTreated_treatment").select2().val(vAreaTreated_s).trigger('change');
            $("#modal_vAmountApplied_treatment").val(vAmountApplied_s);
      
            $("#serach_iSRId_treatment").val(iSRId_s);
            $("#vSR_treatment").val(srdisplay_s);
            $("#trtechnician_id").val(technicianid);
            $("#trtechnician_id").select2().val(technicianid).trigger('change');
        } else {

            $("#modal_iTreatmentId").val($("#tt_iTreatmentId_"+id).val());
     
            $("#modal_vSiteName_treatment").val($("#tt_vSiteName_"+id).val());
            $("#serach_iSiteId_treatment").val($("#tt_iSiteId_"+id).val());

            $("#modal_dDate_treatment").val($("#tt_dDate_"+id).val());
            //$("#modal_vType_treatment").val($("#tt_vType_"+id).val());
            $("#modal_vType_treatment").select2().val($("#tt_vType_"+id).val()).trigger('change');
            $("#dStartTime_treatment").val($("#tt_dStartTime_"+id).val());
            $("#dEndTime_treatment").val($("#tt_dEndTime_"+id).val());
            $("#modal_vTrProduct_treatment").val($("#tt_iTPName_"+id).val());
            //$("#modal_vTrProduct_treatment").typeahead('val',$("#tt_iTPName_"+id).val(),true);

            $("#serach_iTPId_treatment").val($("#tt_iTPId_"+id).val());

            var vAppRate = $("#tt_vAppRate_"+id).val();
            application_rate= vAppRate.substr(0, vAppRate.indexOf('(min'));
            var vAmountApplied = $("#tt_vArea_"+id).val();
            area_treated = vAmountApplied;

            $("#modal_vAppRate_treatment").val(vAppRate);
            $("#modal_vArea_treatment").val(vAmountApplied);


            //$("#modal_vAreaTreated_treatment").val($("#tt_vAreaTreated_"+id).val());       
             $("#modal_vAreaTreated_treatment").select2().val($("#tt_vAreaTreated_"+id).val()).trigger('change');

            $("#modal_vAmountApplied_treatment").val($("#tt_vAmountApplied_"+id).val());
      
            
            $("#serach_iSRId_treatment").val($("#tt_iSRId_"+id).val());
            $("#vSR_treatment").val($("#tt_srdisplay_"+id).val());

            iUId_s = $("#tt_iUId_"+id).val();
            iParentId_s = $("#tt_iUParentId_"+id).val();
             $("#trtechnician_id").val($("#iTechnicianId_"+id).val());

            $("#trtechnician_id").select2().val($("#iTechnicianId_"+id).val()).trigger('change');
        }

        setUnitData(iUId_s, iParentId_s)
     
    }else{
        $("#modal_title_treatment").html('Add Task Treatment');
        $("#mode_title_treatment").val('Add');
        $("#modal_iTreatmentId").val('');
        if(iSiteId1 > 0){
            $("#modal_vSiteName_treatment").val(iSiteId1).trigger('change');
            $("#serach_iSiteId_treatment").val(iSiteId1);
        }else {
            $("#modal_vSiteName_treatment").val('');
            $("#serach_iSiteId_treatment").val('');
        }
        $("#modal_dDate_treatment").val(dDate);
        //$("#modal_vType_treatment").val('');
        $("#modal_vType_treatment").select2().val('').trigger('change'); 
        $("#dStartTime_treatment").val(dStartTime);
        $("#dEndTime_treatment").val(dEndTime);
        $("#modal_vTrProduct_treatment").val('');
        $("#serach_iTPId_treatment").val('');
        $("#modal_vAppRate_treatment").val('');
        $("#modal_vArea_treatment").val('');
       // $("#modal_vAreaTreated_treatment").prop("selectedIndex",0).val();    
        $("#modal_vAreaTreated_treatment").select2().val('acre').trigger('change');         
        $("#modal_vAmountApplied_treatment").val('');
        //$("#modal_iUId_treatment").prop("selectedIndex",0).val();
        $("#modal_iUId_treatment").select2().val('').trigger('change');  

        $("#serach_iSRId_treatment").val('');
        $("#vSR_treatment").val('');

      
        $("#trtechnician_id").val(sess_iUserId);

        $("#trtechnician_id").select2().val(sess_iUserId).trigger('change');
    }
    $("#treatment_box").trigger('click');
}
/**************************************************************/

(function ($) {
        var cluster = new Bloodhound({
          datumTokenizer: function(d) { return d.tokens; },
          queryTokenizer: Bloodhound.tokenizers.whitespace,
          remote: {
            url: site_url+'tasks/task_treatment_list&mode=search_site',
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
        $('#modal_vSiteName_treatment').typeahead({hint: false, highlight: true,minLength: 1 }, 
        {
            displayKey: 'display',
            source: cluster.ttAdapter(),
        })
        .on('typeahead:selected', onSiteClusteSelected)
        .off('blur')
        .blur(function() {
            $(".tt-dropdown-menu").hide();
        });
    /****************************************************************************************************/
        var clusterTreatmentProduct = new Bloodhound({
          datumTokenizer: function(d) { return d.tokens; },
          queryTokenizer: Bloodhound.tokenizers.whitespace,
          remote: {
            url: site_url+'tasks/task_treatment_list&mode=search_treatment_product',
            replace: function(url, uriEncodedQuery) {
                var newUrl = url + '&trProductName=' + uriEncodedQuery;
                return newUrl;
                },
            filter: function(list) {
                if(list==null){
                    return {};
                }
                else{
                    return $.map(list, function(rawdata) { 
                        return { display: rawdata.display, 
                                    iTPId:rawdata.iTPId,
                                    unitName : rawdata.unitName,
                                    vAppRate:rawdata.vAppRate,
                                    vMinAppRate:rawdata.vMinAppRate,
                                    vMaxAppRate:rawdata.vMaxAppRate,
                                    vTragetAppRate:rawdata.vTragetAppRate,
                                    iUnitId : rawdata.iUId,
                                    iParentId : rawdata.iParentId,
                            }; 
                    });
                }
            } 
          }      
        });
        
        clusterTreatmentProduct.initialize();
        
        select = false;
        $('#modal_vTrProduct_treatment').typeahead({hint: false, highlight: true,minLength: 1 }, 
        {
            displayKey: 'display',
            source: clusterTreatmentProduct.ttAdapter(),
        })
        .on('typeahead:selected', onTrProductClusterSelected)
        .on('typeahead:change', onTrProductClusterSelected)
        .off('blur')
        .blur(function() {
            $(".tt-dropdown-menu").hide();
        });


   /********************Search sr******************************/
   var clusterSR = new Bloodhound({
      datumTokenizer: function(d) { return d.tokens; },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
        url: site_url+'tasks/task_treatment_list&mode=search_sr',
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
    $('#vSR_treatment').typeahead({hint: false, highlight: true,minLength: 1 }, 
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
    $("#serach_iSiteId_treatment").val(datum['iSiteId']);
    $("#modal_vSiteName_treatment").val(datum['display']);
}

function onSRClusteSelected(e, datum){
    //alert(datum)
    $("#serach_iSRId_treatment").val(datum['iSRId']);
    $("#vSR_treatment").val(datum['display']);
}
$("#vSR_treatment").keyup(function(e) {
    if(e.keyCode == 8){
        if($("#vSR_treatment").val() == ""){
            $("#serach_iSRId_treatment").val("");
       }
    }   
});

function onTrProductClusterSelected(e, datum){
    $("#serach_iTPId_treatment").val(datum['iTPId']);
    $("#modal_vTrProduct_treatment").val(datum['display']);  

    var appRate = (jQuery.isEmptyObject(datum['vAppRate']))?"":datum['vAppRate'];
    var minRate = (jQuery.isEmptyObject(datum['vMinAppRate']))?"":"min "+datum['vMinAppRate'];
    var maxRate = (jQuery.isEmptyObject(datum['vMaxAppRate']))?"":"- max "+datum['vMaxAppRate'];
    var tragetappRate = (jQuery.isEmptyObject(datum['vTragetAppRate']))?"":datum['vTragetAppRate'];
    var unitName = (jQuery.isEmptyObject(datum['unitName']))?"":datum['unitName'];

    var vAppRate = appRate + "("+minRate+maxRate+")"+unitName+"/"+tragetappRate;
    application_rate = appRate;
    $("#modal_vAppRate_treatment").val(vAppRate);

    setUnitData(datum['iUnitId'],datum['iParentId']);

    setAreaTreatedtreatment(tragetappRate);

    data = calculateAmountApplied();
    $("#modal_vAmountApplied_treatment").val(data);
}

function setUnitData(iUnitId,iUParentId){
    var str = "";
    $("#modal_iUId_treatment").children('option:not(:first)').remove();
     $.ajax({
            type: "POST",
            url: site_url+"tasks/task_treatment_list&mode=getUnitDataById&iUParentId="+iUParentId,
            success: function(res){
                data =jQuery.parseJSON(res);
                if(jQuery.isEmptyObject(data) == false){
                    $.each(data,function(index,val){
                        str += "<option value='"+val['iUId']+"'>"+val['vUnit']+"</option>";
                    });
                }
                $("#modal_iUId_treatment").append(str);
                //$("#modal_iUId_treatment").val(iUnitId);
                   
                $("#modal_iUId_treatment").select2().val(iUnitId).trigger('change'); 
            }
        });

}

$("#save_data_treatment").click(function(e){
    $('#save_loading_treatment').show();
    $("#save_data_treatment").prop('disabled', true);
     
    var form = $("#frmadd_treatment");
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if($("#modal_vSiteName_treatment").val() == "" || ($("#serach_iSiteId_treatment").val() == "" || $("#serach_iSiteId_treatment").val() == "0" )){
        $("#errmsg_search_site").html('Please enter site');
        $("#errmsg_search_site").show();
        isError = 1;
        //$('#save_loading_treatment').hide();
    }else{
        $("#errmsg_search_site").hide();
    }

    if($("#dStartTime_treatment").val() != "" && $("#dEndTime_treatment").val() != ""){
        if($("#dStartTime_treatment").val() > $("#dEndTime_treatment").val()){
             $("#errmsg_dEndTime_treatment").html('End time can not be less than start time.');
            $("#errmsg_dEndTime_treatment").show();
            isError = 1;
            //$('#save_loading_treatment').hide();
        }else{
            $("#errmsg_dEndTime_treatment").hide();
        }       
    }

    if($("#modal_vTrProduct_treatment").val() == "" || ($("#serach_iTPId_treatment").val() == "" || $("#serach_iTPId_treatment").val() == "0" )){
        $("#errmsg_vTrProduct_treatment").html('Please enter treatment product');
        $("#errmsg_vTrProduct_treatment").show();
        isError = 1;
        //$('#save_loading_treatment').hide();
    }else{
        $("#errmsg_vTrProduct_treatment").hide();
    }

    if($("#modal_vAmountApplied_treatment").val() == "" ){
        $("#errmsg_amountapplied").html('Please enter amount applied');
        $("#errmsg_amountapplied").show();
        isError = 1;
        //$('#save_loading_treatment').hide();
    }else if($("#modal_iUId_treatment").val() == "" ){
         $("#errmsg_amountapplied").html('Please select option');
        $("#errmsg_amountapplied").show();
        isError = 1;
        //$('#save_loading_treatment').hide();
    }else{
        $("#errmsg_amountapplied").hide();  
    }


    if(isError == 0){
        //check amount applied value
        var amountapplied = $("#modal_vAmountApplied_treatment").val();
        var calamount =calculateAmountApplied();
        if(calamount != amountapplied){
            swal({
                title: "Treatment amount applied is not as per application rate. Are you sure you want to save this record",
                text: "",
                type: "warning",
                showCancelButton: true,
                //confirmButtonColor: "#DD6B55",
                confirmButtonClass: 'confirm btn btn-lg btn-danger',
                cancelButtonClass : 'cancel btn btn-lg btn-default',
                confirmButtonText: 'Yes',
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true,
                },
                function(isConfirm) {
                    if (isConfirm) {
                       savetreatementdata();
                    } else {
                        swal.close();
                        $('#save_loading_treatment').hide();
                        $("#save_data_treatment").prop('disabled', false);
                    }
                }
            );
        }else{
            savetreatementdata();
        }
        
    }else{
        $('#save_loading_treatment').hide();
        $("#save_data_treatment").prop('disabled', false);
    }
});


function savetreatementdata(){
    var data_str = $("#frmadd_treatment").serializeArray();
    $.ajax({
        type: "POST",
        url: site_url+"tasks/task_treatment_list",
        data: data_str,
        success: function(data){
            $('#save_loading_treatment').hide();
            $("#save_data_treatment").prop('disabled', false);
            $("#closestbox_treatment").trigger('click');
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
}

function setAreaTreatedtreatment(tragetappRate){
    $("#modal_vAreaTreated_treatment").select2().val(tragetappRate).trigger('change');    
}

function calculateAmountApplied(){
    amount = "";
    if($.isNumeric(area_treated) && $.isNumeric(application_rate)){
        amount = application_rate * area_treated;
    }
     return amount;
}

$("#modal_vArea_treatment").change(function() {
   area_treated = $("#modal_vArea_treatment").val();
   data = calculateAmountApplied();
    $("#modal_vAmountApplied_treatment").val(data);
});




