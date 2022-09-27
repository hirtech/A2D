function addEditDataInstaTreatment(imode){
    //alert(imode);

    if(imode == "add"){

        $("#instatreatedit").addClass('d-none');
        $("#instamodal_vTrProduct_treatment").val('');  
        $("#instaserach_iTPId_treatment").val('');
        $("#instamodal_vAppRate_treatment").val('');
        $("#instamodal_vArea_treatment").val('');       
        $("#instamodal_vAreaTreated_treatment").select2().val('acre').trigger('change');
        $("#instamodal_vAmountApplied_treatment").val('');
        $("#instamodal_unit_parentid").val('');
        $("#instamodal_unit_id").val('');

        $("#INSTA_TREATMENT_PRODUCT_ID").val('');
        $("#INSTA_TREATMENT_AREA").val('');
        $("#INSTA_TREATMENT_AREA_TREATED").val('');
        $("#INSTA_TREATMENT_AMOUNT_APPLIED").val('');
        $("#INSTA_TREATMENT_UNIT_ID").val('');

        setUnitData('','');

        if($("#ENABLE_INSTA_TREATMENT").is(":checked") == false){
           
        }else{
            $("#instatreatedit").removeClass('d-none');
            $("#insta_treatment_box").trigger('click');   
        }
        $("#show_insta_data").html('');
    }else{
        $("#instatreatedit").removeClass('d-none');
     
       setUnitData($("#instamodal_unit_id").val(),$("#instamodal_unit_parentid").val());

        $("#insta_treatment_box").trigger('click');  
    }
    
}
$("#save_data_instatreatment").click(function(){
    $('#save_loading_instatreatment').show();
    $("#save_data_instatreatment").prop('disabled', true);
     
    var form = $("#frmadd_instatreatment");
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if($("#instamodal_vTrProduct_treatment").val() == "" || ($("#instaserach_iTPId_treatment").val() == "" || $("#instaserach_iTPId_treatment").val() == "0" )){
        $("#instaerrmsg_vTrProduct_treatment").html('Please enter treatment product');
        $("#instaerrmsg_vTrProduct_treatment").show();
        isError = 1;
        //$('#save_loading_treatment').hide();
    }else{
        $("#instaerrmsg_vTrProduct_treatment").hide();
    }

    if($("#instamodal_vAmountApplied_treatment").val() == "" ){
        $("#instaerrmsg_amountapplied").html('Please enter amount applied');
        $("#instaerrmsg_amountapplied").show();
        isError = 1;
        //$('#save_loading_treatment').hide();
    }else if($("#instamodal_iUId_treatment").val() == "" ){
         $("#instaerrmsg_amountapplied").html('Please select option');
        $("#instaerrmsg_amountapplied").show();
        isError = 1;
        //$('#save_loading_treatment').hide();
    }else{
        $("#instaerrmsg_amountapplied").hide();  
    }

    var unitname =  $('#instamodal_iUId_treatment').select2('data')[0].text;
   
    if(isError == 0){
        var data_str = $("#frmadd_instatreatment").serializeArray();
        //console.log(data_str);
       $.ajax({
            type: "POST",
            url: site_url+"settings/setting_list",
            data: data_str,
            success: function(data){
                response =JSON.parse(data);
                if(response['error'] == "0"){
                  //  alert('success');
                    //console.log(data_str[0].value);

                    $("#INSTA_TREATMENT_PRODUCT_ID").val(response['data']['serach_iTPId_treatment']);
                    $("#INSTA_TREATMENT_AREA").val(response['data']['vArea_treatment']);
                    $("#INSTA_TREATMENT_AREA_TREATED").val(response['data']['vAreaTreated_treatment']);
                    $("#INSTA_TREATMENT_AMOUNT_APPLIED").val(response['data']['vAmountApplied_treatment']);
                    $("#INSTA_TREATMENT_UNIT_ID").val(response['data']['iUId_treatment']);

                    $("#instamodal_vTrProduct_treatment").val(response['data']['vTrProduct_treatment']);  
                    $("#instaserach_iTPId_treatment").val(response['data']['serach_iTPId_treatment']);
                    $("#instamodal_vAppRate_treatment").val(response['data']['vAppRate_treatment']);
                    $("#instamodal_vArea_treatment").val(response['data']['vArea_treatment']);       
                    $("#instamodal_vAreaTreated_treatment").select2().val(response['data']['vAreaTreated_treatment']).trigger('change');
                    $("#instamodal_vAmountApplied_treatment").val(response['data']['vAmountApplied_treatment']);
                    $("#instamodal_unit_parentid").val(response['data']['unit_parentid']);
                    $("#instamodal_unit_id").val(response['data']['iUId_treatment']);


                    var table=`<table width="100%" class="table">
                                    <tr>
                                        <td>Insta Treatment product</td>
                                        <td>${response['data']['vTrProduct_treatment']}</td>
                                    </tr>
                                     <tr>
                                        <td>Insta Treatment Area Treated</td>
                                        <td>${response['data']['vArea_treatment']} ${response['data']['vAreaTreated_treatment']}</td>
                                    </tr>
                                    <tr>
                                        <td>Insta Treatment Amount Applied</td>
                                        <td>${response['data']['vAmountApplied_treatment']} ${unitname}</td>
                                    </tr>
                            </table>`;
                    $("#show_insta_data").html(table);
                   
                }else{
                   //alert('error');
                }
                $('#save_loading_instatreatment').hide();
                $("#save_data_instatreatment").prop('disabled', false);
                $("#closestbox_instatreatment").trigger('click');                
            }
        });
    }else{
        $('#save_loading_instatreatment').hide();
        $("#save_data_instatreatment").prop('disabled', false);
    }
});

(function ($) {
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
        $('#instamodal_vTrProduct_treatment').typeahead({hint: false, highlight: true,minLength: 1 }, 
        {
            displayKey: 'display',
            source: clusterTreatmentProduct.ttAdapter(),
        })
        .on('typeahead:selected', onTrProductClusterSelected)
        .on('typeahead:change', onTrProductClusterSelected)
        .off('blur')
        .blur(function() {
            $(".tt-dropdown-menu").hide();
            $(".tt-menu").hide();
        });

})(jQuery);

function onTrProductClusterSelected(e, datum){
    $("#instaserach_iTPId_treatment").val(datum['iTPId']);
    $("#instamodal_vTrProduct_treatment").val(datum['display']);  

    var appRate = (jQuery.isEmptyObject(datum['vAppRate']))?"":datum['vAppRate'];
    var minRate = (jQuery.isEmptyObject(datum['vMinAppRate']))?"":"min "+datum['vMinAppRate'];
    var maxRate = (jQuery.isEmptyObject(datum['vMaxAppRate']))?"":"- max "+datum['vMaxAppRate'];
    var tragetappRate = (jQuery.isEmptyObject(datum['vTragetAppRate']))?"":datum['vTragetAppRate'];
    var unitName = (jQuery.isEmptyObject(datum['unitName']))?"":datum['unitName'];

    var vAppRate = appRate + "("+minRate+maxRate+")"+unitName+"/"+tragetappRate;

    $("#instamodal_vAppRate_treatment").val(vAppRate);

    $("#instamodal_unit_parentid").val(datum['iParentId']);  

    setUnitData(datum['iUnitId'],datum['iParentId']);
}

function setUnitData(iUnitId,iUParentId){
    var str = "";

    $("#instamodal_iUId_treatment").children('option:not(:first)').remove();
    if(iUParentId != ""){
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
                $("#instamodal_iUId_treatment").append(str);
                //$("#instamodal_iUId_treatment").val(iUnitId);
                   
                $("#instamodal_iUId_treatment").select2().val(iUnitId).trigger('change'); 
            }
        });
    }
}