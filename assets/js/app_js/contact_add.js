(function ($) {
    "use strict";
/////////////////// input mask /////////////////////////////
    $('[data-masked]').inputmask();

})(jQuery);

function addEditData(id,mode,premiseid,referer){

    $("#contactfrmadd").removeClass('was-validated');
        $("#primary_msg").html("").hide();
    $("#alternate_msg").html("").hide();
    $("#email_msg").html("").hide();
   
    if(mode == "edit"){
        $("#cntmodaltitle").html('Edit Contact');
        $("#cnt_mode").val('Update');
   
        $("#premiseid").val(premiseid);
        $("#referer").val(referer);
        $("#cid").val($('#cnt_id_'+id).val());
        $("#salutation").val($('#cnt_salution_'+id).val());
        $("#firstName").val($('#cnt_fname_'+id).val());
        $("#lastName").val($('#cnt_lname_'+id).val());
        $("#primaryPhone").val($('#cnt_phone_'+id).val());
        $("#company").val($('#cnt_company_'+id).val());
        $("#position").val($('#cnt_position_'+id).val());
        $("#email").val($('#cnt_email_'+id).val());
        $("#notes").val($('#cnt_notes_'+id).val());

        var status = $("#cnt_status_"+id).val();
        
        if(status == "1"){
            $("#status").prop('checked',true).change();
        }else if(status == "0"){
            $("#status").prop('checked',false).change();
        }else{
            $("#status").prop('checked',false).change();
        }
    }else{
        $("#cntmodaltitle").html('Add Contact');
        $("#cnt_mode").val('Add');
        $("#premiseid").val(premiseid);
        $("#referer").val(referer);
        $("#cid").val('');
        $("#salutation").val('Mr.');
        $("#firstName").val('');
        $("#lastName").val('');
        $("#primaryPhone").val('');
        $("#company").val('');
        $("#position").val('');
        $("#email").val('');
        $("#notes").val('');

        $("#status").prop('checked',true).change();

    }
    $("#contact_modalbox").trigger('click');
}


$("#cont_save_data").click(function(){
         var checkerr =0;

    $('#cont_save_loading').show();
    $("#cont_save_data").prop('disabled', true);

    $("#primary_msg").html("").hide();
    $("#alternate_msg").html("").hide();
   
   var form = $("#contactfrmadd")

    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');

    if($("#email").val() != ""){
         var filter = /^([\w-\.]+@([\w-]+\.)+[\w]{2,4})?$/;
        if(filter.test($("#email").val()) == false ){
             $('#cont_save_loading').hide();
            $("#email_msg").html("Please enter valid email").show();
            $("#email").focus();
           // return false;
           isError=1;
        }else{
            $("#email_msg").html("").hide();
        }
    }
    if($("#primaryPhone").val() != ""){
        var filter = /^\(([0-9]{3})\)-\(([0-9]{3})\)-\(([0-9]{4})\)$/;
        //console.log($("#primaryPhone").val());
        if(filter.test($("#primaryPhone").val()) == false ){
            $('#cont_save_loading').hide();
            $("#primary_msg").html("Please enter valid primary phone").show();
            $("#primaryPhone").focus();
           // return false;

           isError=1
        }else{
            $("#primary_msg").html("").hide();
        }
    }
    if(isError == 0){           
       
            var form_data = $('#contactfrmadd').serializeArray();
    
            $.ajax({
                type: "POST",
                //dataType: "json",
                url: site_url + "contact/list",
                data: form_data,
                cache: false,
                success: function (data) {
                    $('#cont_save_loading').hide();
                    $("#cont_save_data").prop('disabled', false);
                    response =JSON.parse(data);
                    if(response['error'] == "0"){
                        toastr.success(response['msg']);
                    }else{
                        toastr.error(response['msg']);
                    }
                    
                    //$('#cont_save_loading').hide();
                    setTimeout(function () {
                            if($('#referer').val() == "contact"){ 
                                  gridtable.ajax.reload();
                            }else if($('#referer').val() == "sitecontactedit"){
                                if(response['error'] == "0"){
                                    var cid = $('#cid').val();
                                    var name = $('#salutation').val()+' '+$('#firstName').val()+' '+$('#lastName').val();
                                    var phone =$('#primaryPhone').val().replace(/[\(\)]/g,'').replace(/[\-]/g,' ');
                                    $("#cont_name_"+cid).html(name);
                                    $("#cont_phone_"+cid).html(phone);
                                }
                            }else if ($('#referer').val() == "sitecontactadd" && response['error'] == "0"){
                                var cid  = response['iContactId'];
                                if(cid != null ){
                                    var name = $('#salutation').val()+' '+$('#firstName').val()+' '+$('#lastName').val();
                                    var phone =$('#primaryPhone').val().replace(/[\(\)]/g,'').replace(/[\-]/g,' ');
                                   
                                   var tr_data = '<tr><td id="cont_name_'+cid+'">' + name+'</td>';
                                        tr_data += '<td id="cont_phone_'+cid+'">' + phone + '</td>';
                                        tr_data +=  '<td align="center"><input type="hidden" name="iCId[]" value="' + cid + '">&nbsp;<a class="btn btn-outline-secondary" title="Edit Contact" href="javascript:void(0);" onclick="editContact('+cid+');"><i class="fa fa-edit"></i></a>';
                                        tr_data += '&nbsp;<a class="btn btn-outline-danger" title="Remove" href="javascript:void(0);" onclick="remove_contact_row(this);"><i class="fa fa-trash"></i></a>';
                                        tr_data +=  '</td></tr>';
                                    $('#tbl_contact').append(tr_data); 
                                }
                            } else if ($('#referer').val() == "srcontactadd" && response['error'] == "0"){
                                var cid  = response['iContactId'];
                                
                                if(cid != null ){
                                   
                                    var name = $('#salutation').val()+' '+$('#firstName').val()+' '+$('#lastName').val();
                                    var phone =$('#primaryPhone').val().replace(/[\(\)]/g,'').replace(/[\-]/g,' ');
                                    
                                    var contact_data = '';

                                    var display_name = name +" ["+$('#email').val()+" - "+ phone+"]";

                                    $("#search_contact").val(display_name);
                                        $(".vFirstName").html($('#firstName').val());
                                        $(".vLastName").html($('#lastName').val());
                                        $(".vCompany").html($('#company').val());
                                        $(".vEmail").html($('#email').val());
                                        $(".vPhone").html(phone);
                                    
                                    $('.contact-details').show();
                                    $("#iCId").val(cid);
                                }
                            }

                     $("#closestbox").trigger('click');
                        }, 3500);
                },
                error: function(xhr, textStatus, errorThrown) {
                    //alert(errorThrown);
                }
            });
            return false; 
        
    }else{
        $('#cont_save_loading').hide();
        $("#cont_save_data").prop('disabled', false);
    }
});