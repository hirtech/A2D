<div class="row   no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}user/list">User List</a></li>
                <li class="breadcrumb-item active">{$module_name}</li>
            </ol>
        </div>
    </div>
</div>
<form name="frmadd" id="frmadd" method="post" action="" class="form-horizontal needs-validation"  novalidate  enctype="multipart/form-data">
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header">                               
                <h4 class="card-title">{$module_name} {$mode}</h4>                                
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">                                           
                        <div class="col-12">
                                <input type="hidden" name="groupaction" value="groupaction">
                                <input type="hidden" name="mode" id="mode" value="{$mode}">
                                <input type="hidden" name="vCountry" id="vCountry" value="US">
                                <input type="hidden" name="iUserId" id="iUserId" value="{$rs_user[0].iUserId}"> 
                                <input type="hidden" name="vAddress1" id="vAddress1" value="{$rs_user[0].vAddress1}" />
                                <input type="hidden" name="vAddress2" id="vAddress2" value="{$rs_user[0].vAddress2}" />
                                <input type="hidden" name="vStreet" id="vStreet" value="{$rs_user[0].vStreet}" />
                                <input type="hidden" name="vCrossStreet" id="vCrossStreet" value="{$rs_user[0].vCrossStreet}" />
                                <input type="hidden" name="iZipcode" id="iZipcode" value="{$rs_user[0].iZipcode}" />
                                <input type="hidden" name="iStateId" id="iStateId" value="{$rs_user[0].iStateId}" />
                                <input type="hidden" name="iCountyId" id="iCountyId" value="{$rs_user[0].iCountyId}" />
                                <input type="hidden" name="iCityId" id="iCityId" value="{$rs_user[0].iCityId}" />
                                <input type="hidden" name="iZoneId" id="iZoneId" value="{$rs_user[0].iZoneId}" />
                                <input type="hidden" name="vLatitude" id="vLatitude" value="{$rs_user[0].vLatitude}" />
                                <input type="hidden" name="vLongitude" id="vLongitude" value="{$rs_user[0].vLongitude}" />
                                <div class="form-row">
                                    <div class="col-6">
                                        <div class="col-12 mb-3">
                                            <label for="iAGroupId">Access Group <span class="text-danger">*</span></label>
                                            <select name="iAGroupId" id="iAGroupId" class="form-control" required>
                                                <option value="">Select</option>
                                                {section name="ag" loop=$rs_access_group}
                                                    <option value="{$rs_access_group[ag].iAGroupId}" {if $rs_access_group[ag].iAGroupId eq $rs_user[0].iAGroupId}selected{/if}>{$rs_access_group[ag].vAccessGroup|gen_strip_slash}</option>
                                                {/section}
                                            </select>
                                            <div class="invalid-feedback"> Please select access group</div>
                                        </div>
                                        <div class="col-12 mb-3"> 
                                            <label for="iDepartmentId">Department</label>
                                            <select name="iDepartmentId[]" id="iDepartmentId" class="form-control" multiple >
                                                {section name="d" loop=$rs_department}
                                                <option value="{$rs_department[d].iDepartmentId}" {if $rs_department[d].iDepartmentId|in_array:$iDepartmentId_arr}selected{/if}>{$rs_department[d].vDepartment|gen_strip_slash}</option>
                                                {/section}
                                            </select>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="vFirstName">First Name <span class="text-danger">*</span></label>
                                            <input type="text" id="vFirstName" name="vFirstName" value="{$rs_user[0].vFirstName|gen_filter_text}" class="form-control" required>
                                            <div class="invalid-feedback"> Please enter first name</div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="vLastName">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" id="vLastName" name="vLastName" value="{$rs_user[0].vLastName|gen_filter_text}" class="form-control" required>
                                            <div class="invalid-feedback"> Please enter last name</div>
                                        </div>
                                        <div class="col-12 mb-3" id="li_vUsername"> 
                                            <label for="vUsername">Username <span class="text-danger">*</span></label>
                                            {if $mode == 'Add'}
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-transparent border-right-0" id="basic-vUsername"><i class="icon-user"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" aria-label="Username" id="vUsername" name="vUsername" value="{$rs_user[0].vUsername|gen_filter_text}" onblur="checkDuplicateUser();" aria-describedby="basic-addon1" required>
                                                    <div id="duplicate_msg" class="invalid-feedback errormsg"></div>
                                                    <div class="invalid-feedback"> Please enter user name</div>
                                                </div>
                                            {else} &nbsp;
                                                <input type="hidden" id="vUsername" name="vUsername" value="{$rs_user[0].vUsername|gen_filter_text}">
                                                <span style="display:inline-block;"><strong>{$rs_user[0].vUsername|gen_filter_text}</strong> </span>
                                            {/if}
                                        </div>
                                        <div class="col-12 mb-3" id="li_vPassword"> 
                                            <label for="vPassword">Password <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0" id="basic-password1"><i class="icon-options"></i></span>
                                                </div>
                                                <input type="password" class="form-control" id="vPassword" name="vPassword" aria-label="Password" aria-describedby="basic-password"  {if $mode eq 'Ad'} required {/if}>
                                                <div class="invalid-feedback"> Please enter password</div>
                                            </div>
                                            <meter max="4" id="password-strength-meter"></meter>
                                            <p id="password-strength-text"></p>
                                        </div>
                                        <div class="col-12 mb-3" id="li_vConfPassword">
                                            <label for="vConfPassword">Confirm Password <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0" id="basic-password1"><i class="icon-options"></i></span>
                                                </div>
                                                <input type="password" class="form-control" id="vConfPassword" name="vConfPassword" aria-label="Password" aria-describedby="basic-password" {if $mode eq 'Add'} required {/if}>
                                                <div class="invalid-feedback"> Please enter confirm password</div>
                                                <div id="conf_psw_msg" class="invalid-feedback errormsg"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3"> 
                                            <label for="vEmail">Email</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0" ><i class="icon-envelope"></i></span>
                                                </div>
                                                <input type="email" class="form-control" aria-label="Email" aria-describedby="basic-email" id="vEmail" name="vEmail" value="{$rs_user[0].vEmail|gen_filter_text}">
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3"> 
                                            <label for="iType">Employee profile</label>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input"  {if $rs_user[0].iType eq '1'}checked{/if}  name="iType" value="1" class="form-check-input" id="iType1" checked="">
                                                <label class="custom-control-label" for="iType1">Permanent</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input"  {if $rs_user[0].iType eq '2'}checked{/if}  name="iType" value="2" class="form-check-input" id="iType2">
                                                <label class="custom-control-label" for="iType2">Seasonal</label>
                                            </div>
                                        </div>
                                        {if $mode eq 'Add'}
                                        <div class="col-12 mb-3">
                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                <input type="checkbox" class="custom-control-input" checked="checked" id="notify" name="notify" checked value="1">
                                                <label class="custom-control-label" for="notify">Notify via email ?</label>
                                            </div>
                                        </div>
                                        {/if}
                                        <div class="col-12 mb-3">
                                            <label for="rVacationAccrual">Status</label>
                                            <select name="iStatus" id="iStatus" class="form-control">
                                                <option value="1" {if $rs_user[0].iStatus eq '1'}selected{/if}>Active</option>
                                                <option value="0" {if $rs_user[0].iStatus eq '0'}selected{/if}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="col-12 mb-3">
                                            <label for="vCompanyName">Company</label>
                                            <input type="text" id="vCompanyName" name="vCompanyName" value="{$rs_user[0].vCompanyName|gen_filter_text}"  class="form-control">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="autofilladdress">Address</label>
                                            <div class="position-relative w-100">
                                                <input type="text" id="autofilladdress" name="autofilladdress" class="form-control" value="{$rs_user[0].address}" placeholder="">
                                                <img class="clear_address" src="assets/images/icon-delete.png" style="cursor:pointer;{if $mode neq 'Update' || $rs_user[0].address eq "" } display:none{/if}" onclick="return clear_address();">
                                                <img src="assets/images/loading-small.gif" class="clear_address" id="address_loading" border="0" style="display:none;"> 
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3 address-details" style="{if $rs_user[0].address eq ""} display:none; {/if}">
                                            <table class="table layout-secondary table-responsive" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <th scope="col">vAddress1:</th>
                                                        <td class="vAddress1">{$rs_user[0].vAddress1}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col">vStreet:</th>
                                                        <td class="vStreet">{$rs_user[0].vStreet}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col">iZipcode:</th>
                                                        <td class="iZipcode">{$rs_user[0].iZipcode}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col">iCountyId:</th>
                                                        <td class="iCountyId">{$rs_user[0].iCountyId}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col">iCityId:</th>
                                                        <td class="iCityId">{$rs_user[0].iCityId}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col">iZoneId:</th>
                                                        <td class="iZoneId">{$rs_user[0].iZoneId}</td>
                                                    </tr>
                                                </tbody>    
                                            </table> 
                                        </div>
                                         <div class="col-12 mb-3"> 
                                            <label for="networkId_arr">Network</label>
                                            <select name="networkId_arr[]" id="networkId_arr" class="form-control" multiple >
                                                {section name="n" loop=$rs_ntwork} 
                                                <option value="{$rs_ntwork[n].iNetworkId}" {if $rs_ntwork[n].iNetworkId|in_array:$iNetworkId_arr}selected{/if}>{$rs_ntwork[n].vName|gen_strip_slash}</option> {/section}
                                            </select>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="vPhone">Phone</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0"><i class="fa fa-phone-alt"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="vPhone" name="vPhone" value="{$rs_user[0].vPhone|gen_filter_text}">
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="vCell">Cell</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0"><i class="fa fa-mobile-alt"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="vCell" name="vCell" value="{$rs_user[0].vCell|gen_filter_text}">
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="vFax">Fax</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0"><i class="fa fa-fax"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="vFax" name="vFax" value="{$rs_user[0].vFax|gen_filter_text}">
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3 ">
                                            <label for="vImage">Image</label>
                                            <div class="input-group">
                                                <input type="file" class="d-inline-flex form-control-file form-control h-auto" id="vImage" name="vImage">
                                                <img src="{$user_url}{$rs_user[0].vImage}" alt="" class="d-inline-flex img-fluid rounded-circle ml-2" width="45">
                                            </div>
                                            <input type="hidden" name="vImage_old" id="vImage_old" value="{$rs_user[0].vImage}">
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <!-- <div class="w-sm-100 mr-auto"></div> -->
                        <button type="submit" class="btn btn-primary ml-2 " id="save_data"> Save </button> 
                            <img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;">  
                        <button type="button" onclick="location.href = site_url+'user/list';" class="btn  btn-secondary  ml-2" > Close </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 </form>
<!-- START: Page JS-->
<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
<!-- <script src="http://s.codepen.io/assets/libs/modernizr.js" type="text/javascript"></script>  -->
<script src="https://maps.googleapis.com/maps/api/js?key={$GOOGLE_GEOCODE_API_KEY}&libraries=geometry,drawing,places,visualization"></script>

<script src="assets/js/app_js/google_autocomplete.js"></script>

<script type="text/javascript" src="assets/js/app_js/user_add.js"></script>

<!-- START: Page JS-->
<script type="text/javascript">
var iCountyId = '{$rs_user[0].iCountyId}';
var iCityId = '{$rs_user[0].iCityId}';
var mode = '{$mode}';
{literal}
$(document).ready(function() {
    $('select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
          width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
    });
    $('#dStartDate').datepicker({
        showOn: "both",
        dateFormat: "yy-mm-dd",
        firstDay: 0,
        changeFirstDay: true,
        changeMonth: true,
        changeYear: true
    });
    $('#dEndDate').datepicker({
        showOn: "both",
        dateFormat: "yy-mm-dd",
        firstDay: 0,
        changeFirstDay: true,
        changeMonth: true,
        changeYear: true
    });

    var strength = new Array("Worst &#9785;", "Bad &#9785;", "Weak &#9785;", "Good &#9786;", "Strong &#9787;");
    $("#vPassword").keyup(function () {
        $("#password-strength-meter").show();
        var meter = $("#password-strength-meter");
        var text = $("#password-strength-text");
        var val = $(this).val();

        var result = zxcvbn(val);

        // Update the password strength meter
        $("#password-strength-meter").val(result.score);
        // Update the text indicator
        if (val !== "") {
            $("#password-strength-text").html("Strength: " + "<strong>" + strength[result.score] + "</strong>" + "<span class='feedback'>" + result.feedback.warning + " " + result.feedback.suggestions + "</span>");
        }
        else {
            $("#password-strength-text").html("");
        }
    });

    if(mode == 'Add'){
        $(".address-details").hide();
    }   
});

{/literal}
</script>
