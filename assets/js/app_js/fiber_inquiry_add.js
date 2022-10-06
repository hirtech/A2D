$(document).ready(function() {
	// start contry history popup and nearbysr history  for update time
	var iCId='';
	var vFirstName='';
	var vLastName='';
	var iCId=$("#iCId").val();
	var vLatitude_nearbysr ='';
	var vLongitude_nearbysr ='';
	var vLatitude_nearbysr = $('#vLatitude').val();
	var vLongitude_nearbysr = $('#vLongitude').val();
	var meter = '402.336';
		//alert(iCId);
	if(iCId!=''){
		var iCId=iCId;
		var vFirstName=$(".vFirstName").text();
		var vLastName=$(".vLastName").text();
		var html="<input type='button' onclick='showContactHistory("+iCId+',"'+vFirstName+'"'+',"'+vLastName+'"'+")' class='btn btn-primary' value='Contact History'>";
		$("#showContactHistory").html(html);
	}

	if(vLatitude_nearbysr!='' && vLongitude_nearbysr!='')
	{
		var html="<input type='button' onclick='showNearbySr("+vLatitude_nearbysr+","+vLongitude_nearbysr+","+meter+")' class='btn btn-primary' value='Fiber Inquiry History'>";
		$("#showNearbySr").html(html);
	}
	// end popup 
	$('select').each(function () {
		$(this).select2({
		  theme: 'bootstrap4',
		  width: 'style',
		  placeholder: $(this).attr('placeholder'),
		  allowClear: Boolean($(this).data('allow-clear')),
	  });
	});

if(mode == 'Add'){
    $(".address-details").hide();
    $(".contact-details").hide();
}

});


$("#save_data").click(function(e){

    $('#sr_save_loading').show();
    $("#save_data").prop('disabled', true);
    var form = $("#frmadd");
    //alert(form[0].checkValidity())
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }

    if($("#iCId").val() == ""  || $("#iCId").val() == "0" ){
        swal("","Contact is needed for creating an SR");
        $(".errmsg_iCId").html('Please search contact');
        $(".errmsg_iCId").show();
        isError = 1;
    }else{
        $(".errmsg_iCId").html('');
        $(".errmsg_iCId").hide();
    }

    /*if($("input[name=bMosquitoService]").prop("checked") == false || $("input[name=bCarcassService]").prop("checked") == false){
        $(".errmsg_iSRService").html('Please select at least one service type');
        $(".errmsg_iSRService").show();
        isError = 1;
    }else{
        $(".errmsg_iSRService").html('');
        $(".errmsg_iSRService").hide();
        isError = 0;
    }*/
    //alert(isError);
    form.addClass('was-validated');
    if(isError == 0){
        //alert(isError);return false;
        var form_data = $("#frmadd").serializeArray();
        
        $.ajax({
            type: "POST",
            url: site_url+"fiber_inquiry/list",
            data: form_data,
            cache: false,
            success: function(data){
                $('#sr_save_loading').show();
                $("#save_data").prop('disabled', false);
                response =JSON.parse(data);
                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                }else{
                    toastr.error(response['msg']);
                }
                location.href = site_url+'fiber_inquiry/list';
            }
        });
        return false; 
    }
    $('#sr_save_loading').hide();
    $("#save_data").prop('disabled', false);
    e.preventDefault();
});

function clear_address() {
    $('#autofilladdress').val('');
    $('#autofilladdress').focus();
    $(".address-details").hide();

    $('#iZoneId').val('');
    $('#vLatitude').val('');
    $('#vLongitude').val('');
    $('#vAddress1').val('');
    $('#vStreet').val('');
    $('#iStateId').val('');
    $('#iCountyId').val('');
    $('#iCityId').val('');
    $('#iZipcode').val('');

}

(function ($) {

    var cluster = new Bloodhound({
        datumTokenizer: function(d) { return d.tokens; },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: site_url+'fiber_inquiry/list&mode=searchContact',
            replace: function(url, uriEncodedQuery) {
                var newUrl = url + '&vContactName=' + uriEncodedQuery;
                return newUrl;
            },
            filter: function(list) {
                if(list==null)
                    return {};
                else
                    return $.map(list, function(rawdata) { return { display: rawdata.display, id:rawdata.iCId , name:rawdata.name, vPhone : rawdata.vPhone,vFirstName : rawdata.vFirstName, vLastName : rawdata.vLastName, vCompany : rawdata.vCompany , vEmail : rawdata.vEmail }; });
            } 
        }      
    });
    
    cluster.initialize();
    
    select = false;
    $('#search_contact').typeahead({hint: false, highlight: true,minLength: 1 }, 
    {
        displayKey: 'display',
        source: cluster.ttAdapter(),
    })
    .on('typeahead:selected', onClusteSelected)
    .off('blur')
    .blur(function() {
        $(".tt-dropdown-menu").hide();
    });
    

})(jQuery);

function onClusteSelected(e, datum){
    
    $(".contact-details").show();
    $("#iCId").val(datum['id']);
    $(".vFirstName").html(datum['vFirstName']);
    $(".vLastName").html(datum['vLastName']);
    $(".vCompany").html(datum['vCompany']);
    $(".vEmail").html(datum['vEmail']);
    $(".vPhone").html(datum['vPhone']);
    $(".contact-details").show();
// start contry history popup for add time

var html="<input type='button' onclick='showContactHistory("+datum['id']+',"'+datum['vFirstName']+'"'+',"'+datum['vLastName']+'"'+")' class='btn btn-primary' value='Contact History'>";
$("#showContactHistory").html(html);
// end contry history    
}

function clear_serach_contact(){
 $("#search_contact").typeahead('val','');
 $("#iCId").val('');
 $(".vFirstName").html('');
 $(".vLastName").html('');
 $(".vCompany").html('');
 $(".vEmail").html('');
 $(".vPhone").html('');
 $(".contact-details").hide();
}
