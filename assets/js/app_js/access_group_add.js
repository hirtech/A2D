function checkAllRow(rowObj) {
	var actIndex = rowObj.value;
	var class_nm = (rowObj.checked) ? "checked" : "";
//console.log(actIndex);
	$('#eList_'+actIndex).prop('checked', class_nm);
	$('#eAdd_'+actIndex).prop('checked', class_nm);
	$('#eEdit_'+actIndex).prop('checked', class_nm);
	$('#eDelete_'+actIndex).prop('checked', class_nm);
	$('#eStatus_'+actIndex).prop('checked', class_nm);
	$('#eRespond_'+actIndex).prop('checked', class_nm);
	$('#eCSV_'+actIndex).prop('checked', class_nm);
	$('#ePDF_'+actIndex).prop('checked', class_nm);
	$('#eCalsurv_'+actIndex).prop('checked', class_nm);

}
function checkSubChck(objaction){
	var sectionclass = class_nm = "";
	switch(objaction){
		case 'list' :
			class_nm = ($("#main_"+objaction).prop('checked') == true)?"checked":"";
			sectionclass = "case_list"
			break;
		case 'add' :
			class_nm = ($("#main_"+objaction).prop('checked') == true)?"checked":"";
			sectionclass = "case_add"
			break;
		case 'edit' :
			class_nm = ($("#main_"+objaction).prop('checked') == true)?"checked":"";
			sectionclass = "case_edit"
			break;
		case 'delete' :
			class_nm = ($("#main_"+objaction).prop('checked') == true)?"checked":"";
			sectionclass = "case_delete"
			break;
		case 'status' :
			class_nm = ($("#main_"+objaction).prop('checked') == true)?"checked":"";
			sectionclass = "case_status"
			break;
		case 'respond' :
			class_nm = ($("#main_"+objaction).prop('checked') == true)?"checked":"";
			sectionclass = "case_respond"
			break;
		case 'csv' :
			class_nm = ($("#main_"+objaction).prop('checked') == true)?"checked":"";
			sectionclass = "case_csv"
			break;
		case 'pdf' :
			class_nm = ($("#main_"+objaction).prop('checked') == true)?"checked":"";
			sectionclass = "case_pdf"
			break;
		case 'calsurv' :
			class_nm = ($("#main_"+objaction).prop('checked') == true)?"checked":"";
			sectionclass = "case_calsurv"
			break;
	}
//console.log(class_nm);
	if(sectionclass != ""){
		$("."+sectionclass).prop('checked',class_nm);
	}
}

$("#save_data").click(function(){
    $('#save_loading').show();
    $("#save_data").prop('disabled', true);
   // console.log('111');

    var form_data = $('#frmadd').serializeArray();
    // return false;
    $.ajax({
        type: "POST",
        dataType: "json",
        url: site_url + "access_group/access_group_list",
        data: form_data,
        cache: false,
        success: function (response) {
              $('#save_loading').hide();
              $("#save_data").prop('disabled', false);

            if(typeof response.duplicate_check_tot != "undefined" && response.duplicate_check_tot != 0 ){
                toastr.error("Username already exist");
            }else{

            if(response['error'] == "0"){
                toastr.success(response['msg']);
            }else{
                toastr.error(response['msg']);
            }
            setTimeout(function () {
                    location.href = site_url+'access_group/access_group_list';
                }, 3500); 
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            //alert('Nastala chyba. ' + errorThrown);
        }
    });
    return false; 
});

function getAccessRights()
{
    var allVals = new Array()
    $('.list_check').each(function(){ 
        if($(this).is(':checked')){
            allVals.push($(this).val());
        }
    });

    window.location.href = "access_group/access_group_add&mode=Manage&iAGroupId="+$('#iAGroupId').val()+'&iDefault='+allVals;
}