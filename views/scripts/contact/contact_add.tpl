<div class="row ">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}contact/list">Contact List</a></li>
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
                            <input type="hidden" name="iCId" id="iCId" value="{$rs_contact[0].iCId}"> 
                            <input type="hidden" name="iPremiseId" id="iPremiseId" value="{$iPremiseId}" />
                            <input type="hidden" name="referer" id="referer" value="{$referer}" />
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="col-12 mb-3">
                                        <label for="vSalutation">Salutation</label>
                                            <select class="form-control" id="vSalutation" name="vSalutation" >
                                                <option value="Mr." {if $rs_contact[0].vSalutation eq 'Mr.'}selected{/if}>Mr.</option>
                                                <option value="Mrs." {if $rs_contact[0].vSalutation eq 'Mrs.'}selected{/if}>Mrs.</option>
                                            </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="vFirstName">First Name<span class="required" aria-required="true">*</span></label>
                                        <input type="text" class="form-control" id="vFirstName" placeholder="First Name" value="{$rs_contact[0].vFirstName|gen_filter_text}" name="vFirstName" required>
                                        <div class="invalid-feedback"> Please enter first name</div>
                                    </div>
                                
                                    <div class="col-12 mb-3">
                                        <label for="vLastName">Last Name<span class="required" aria-required="true">*</span></label>
                                        <input type="text" class="form-control" id="vLastName" placeholder="Last Name" value="{$rs_contact[0].vLastName|gen_filter_text}" name="vLastName" required>
                                        <div class="invalid-feedback"> Please enter last name</div>
                                    </div>
                                
                                    <div class="col-12 mb-3">
                                        <label for="vCompany">Company</label>
                                        <input type="text" class="form-control" id="vCompany" placeholder="Company Name" value="{$rs_contact[0].vCompany|gen_filter_text}" name="vCompany">
                                    </div>
                              
                                    <div class="col-12 mb-3">
                                        <label for="vPosition">Position</label>
                                        <input type="text" class="form-control" id="vPosition" placeholder="Position" value="{$rs_contact[0].vPosition|gen_filter_text}" name="vPosition">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="vEmail">Email</label>
                                        <input type="text" class="form-control" id="vEmail" placeholder="Email" value="{$rs_contact[0].vEmail|gen_filter_text}" name="vEmail">
                                    </div>
                            
                                </div>
                            <div class="col-6">
                                <div class="col-12 mb-3">
                                    <label for="vPrimaryPhone">Primary Phone<span class="required" aria-required="true">*</span></label>
                                     <input type="text" data-masked="" data-inputmask="'mask': '(999)-(999)-(9999)'" placeholder="Primary Phone" class="form-control" required id="vPrimaryPhone" value="{$primary_phone_num}" name="vPrimaryPhone" >
                                    <span>Ex. (000) - (000) - (0000)</span>
                                    <div class="invalid-feedback"> Please enter primary phone</div>
                                    <div id="primary_msg" class="invalid-feedback errormsg"></div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="vAlternatePhone">Alternate Phone</label>
                                    <input type="text" data-masked="" data-inputmask="'mask': '(999)-(999)-(9999)'" placeholder="Alternate Phone" class="form-control" id="vAlternatePhone" value="{$alternate_phone_num}" name="vAlternatePhone" >
                                    <span>Ex. (000) - (000) - (0000)</span>
                                    <div id="alternate_msg" class="invalid-feedback errormsg"></div>
                                </div>
                        
                                <div class="col-12 mb-3">
                                    <label for="tNotes">Notes</label>
                                    <textarea class="form-control" id="tNotes" name="tNotes">{$rs_contact[0].tNotes|gen_filter_text}</textarea>
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
                        <button type="button" onclick="location.href = site_url+'contact/list';" class="btn  btn-secondary  ml-2" > Close </button>
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

<!-- START: Page Vendor JS-->
<script src="assets/vendors/jquery-inputmask/jquery.inputmask.min.js"></script>
<!-- END: Page Vendor JS-->
<!-- <script src="http://s.codepen.io/assets/libs/modernizr.js" type="text/javascript"></script>  -->



<script type="text/javascript" src="assets/js/app_js/contact_add.js"></script>

<!-- START: Page JS-->

