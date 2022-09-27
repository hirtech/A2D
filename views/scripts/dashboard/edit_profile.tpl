<div class="row ">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
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
                <h4 class="card-title">{$module_name}</h4>                                
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">                                           
                        <div class="col-12">
                            <input type="hidden" name="groupaction" value="groupaction">
                            <input type="hidden" name="mode" id="mode" value="Update">
                            <input type="hidden" name="iUserId" id="iUserId" value="{$rs_user[0].iUserId}"> 
                            <div class="col-10">
                                <form>
                                    <div class="form-group row">
                                        <label for="vFirstName" class="col-sm-2 col-form-label">Access Group</label>
                                        <div class="col-sm-8">
                                            <input type="hidden" class="form-control" id="iAGroupId" placeholder="" value="{$rs_access_group[0].vAccessGroup|gen_filter_text}" name="iAGroupId">
                                            <span style="display:inline-block;border-color: white;" class="form-control"><strong>{$rs_access_group[0].vAccessGroup|gen_filter_text}</strong> </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="vFirstName" class="col-sm-2 col-form-label">First Name<span class="required" aria-required="true">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="vFirstName" placeholder="First Name" value="{$rs_user[0].vFirstName|gen_filter_text}" name="vFirstName" required>
                                        </div>
                                        <div class="invalid-feedback"> Please enter first name</div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="vLastName" class="col-sm-2 col-form-label">Last Name<span class="required" aria-required="true">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="vLastName" placeholder="Last Name" value="{$rs_user[0].vLastName|gen_filter_text}" name="vLastName" required>
                                        </div>
                                        <div class="invalid-feedback"> Please enter last name</div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="vFirstName" class="col-sm-2 col-form-label">User Name </label>
                                        <div class="col-sm-8">
                                            <input type="hidden" class="form-control" id="vUsername" value="{$rs_user[0].vUsername|gen_filter_text}" name="vUsername">
                                            <span class="form-control" style="display:inline-block;border-color: white;"><strong>{$rs_user[0].vUsername|gen_filter_text}</strong> </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="vEmail" class="col-sm-2 col-form-label">Email </label>
                                        <div class="col-sm-8">
                                            <input type="hidden" class="form-control" id="vEmail" value="{$rs_user[0].vEmail|gen_filter_text}" name="vEmail">
                                            <span class="form-control" style="display:inline-block;border-color: white;" class="form-control"><strong>{$rs_user[0].vEmail|gen_filter_text}</strong> </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="vPassword" class="col-sm-2 col-form-label">Password</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" name="vPassword" id="vPassword" placeholder="Password">
                                        </div>
                                        <meter max="4" id="password-strength-meter"></meter>
                                            <p id="password-strength-text"></p>
                                    </div>
                                    <div class="form-group row">
                                        <label for="vConfPassword" class="col-sm-2 col-form-label">Confirm Password</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" name="vConfPassword" id="vConfPassword" placeholder="Confirm Password">
                                        </div>
                                        <div id="conf_psw_msg" class="invalid-feedback errormsg"></div>
                                    </div>                                                
                                    
                                </form>
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
                        <button type="button" onclick="location.href = site_url+'dashboard/editprofile';" class="btn  btn-secondary  ml-2" > Close </button>
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
<script src="http://s.codepen.io/assets/libs/modernizr.js" type="text/javascript"></script> 


<script type="text/javascript" src="assets/js/app_js/edit_profile.js"></script>

<!-- START: Page JS-->
<script type="text/javascript">
var iCountyId = '{$rs_user[0].iCountyId}';
var iCityId = '{$rs_user[0].iCityId}';
{literal}
$(document).ready(function() {
    
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

    //getCountyFromState($('#iStateId'), iCountyId, iCityId);    
});
{/literal}
</script>
