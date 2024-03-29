<div class="row   no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}service_order/list">Service Order List</a></li>
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
                            <input type="hidden" name="iServiceOrderId" id="iServiceOrderId" value="{$rs_sorder[0].iServiceOrderId}">
                            <input type="hidden" name="iFiberInquiryId" id="iFiberInquiryId" value="{$iFiberInquiryId}">
                            <input type="hidden" name="vNameId" id="vNameId" value="">
                            <input type="hidden" name="iLastServicePricingId" id="iLastServicePricingId" value="{$iLastServiceOrderId}">
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="col-12 mb-3">
                                        <label for="iCarrierID">Carrier <span class="text-danger">*</span></label>
                                        <select name="iCarrierID" id="iCarrierID" class="form-control" required onchange="getMasterMSAFromCarrier(this.value)">
                                            <option value="">Select</option>
                                            {section name="c" loop=$rs_carrier}
                                                <option value="{$rs_carrier[c].iCompanyId}" {if $mode eq 'Add' && $rs_carrier[c].iCompanyId eq $sess_iCompanyId} selected {else if $rs_carrier[c].iCompanyId eq $rs_sorder[0].iCarrierID}selected{/if}>{$rs_carrier[c].vCompanyName|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please select carrier</div>
                                    </div>
                                	<div class="col-12 mb-3">
                                        <label for="vMasterMSA">Master MSA # <span class="text-danger">*</span></label>
                                        <input type="text" id="vMasterMSA" name="vMasterMSA" value="{$rs_sorder[0].vMasterMSA|gen_filter_text}" class="form-control readonly-color" readonly required>
                                        <div class="invalid-feedback"> Please enter master msa</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iPremiseId">Premise <span class="text-danger">*</span></label>
                                        <input type="text" name="vPremiseName"  class="form-control " id="vPremiseName" placeholder="Search Premise Id or Premise Name" value="{if $rs_sorder[0].iPremiseId neq ''}{$rs_sorder[0].iPremiseId} ({$rs_sorder[0].vPremiseName|gen_strip_slash}){/if}"  required>
                                        <input type="hidden" id="search_iPremiseId" name="search_iPremiseId" value="{$rs_sorder[0].iPremiseId}"/>
                                        <img class="clear_address" src="assets/images/icon-delete.png" style="cursor:pointer;" onclick="return clear_serach_premise();">
                                        <div class="invalid-feedback" id="errmsg_search_premise">Please enter premise</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iSalesRepId">Sales Rep <span class="text-danger">*</span></label>
                                        <select name="iSalesRepId" id="iSalesRepId" class="form-control" required onchange="getUserDetailsFromUser(this.value);">
                                            <option value="">--- Select ---</option>
                                        </select>
                                        <div class="invalid-feedback"> Please select Sales Rep</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="vSalesRepEmail">Sales Rep Email <span class="text-danger">*</span></label>
                                        <input type="text" id="vSalesRepEmail" name="vSalesRepEmail" value="{$rs_sorder[0].vSalesRepEmail|gen_filter_text}" class="form-control readonly-color" readonly="" required>
                                        <div class="invalid-feedback"> Please enter Sales Rep email</div>
                                    </div>                                    
                                    <div class="col-12 mb-3">
                                        <label for="iConnectionTypeId">Connection Type <span class="text-danger">*</span></label>
                                        <select name="iConnectionTypeId" id="iConnectionTypeId" class="form-control" required>
                                            <option value="">-- Select --</option>
                                        </select>
                                        <div class="invalid-feedback"> Please select connection type</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iService1">Service<span class="text-danger">*</span></label>
                                        <select name="iService1" id="iService1" class="form-control" onchange="getNRCMRCValue(this.value)">
                                            <option value="">Select</option>
                                        </select>
                                        <div class="invalid-feedback"> Please select service type</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iNRCVariable">NRC Variable</label>
                                        <input type="text" id="iNRCVariable" name="iNRCVariable" value="{$rs_sorder[0].iNRCVariable}" class="form-control" >
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iMRCFixed">MRC Fixed</label>
                                        <input type="text" id="iMRCFixed" name="iMRCFixed" value="{$rs_sorder[0].iMRCFixed}" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="col-12 mb-3">
                                        <label for="vServiceOrder">Service Order # <span class="text-danger">*</span></label>
                                        <input type="text" id="vServiceOrder" name="vServiceOrder" value="{$rs_sorder[0].vServiceOrder|gen_filter_text}" class="form-control readonly-color" readonly="" required>
                                        <div class="invalid-feedback"> Please enter service order #</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iSOStatus">Service Order Status <span class="text-danger">*</span></label>
                                        <input type="hidden" id="iOldSOStatus" name="iOldSOStatus" value="{$rs_sorder[0].iSOStatus}" class="form-control">
                                            <select name="iSOStatus" id="iSOStatus" class="select" onchange="addSOValidation(this.value);" required="">
                                                <option value=""> --- Select ---</option>
                                                <option value="1"  {if $rs_sorder[0].iSOStatus eq 1 || $mode eq 'Add'} selected {/if}>Created</option>
                                                <option value="2" {if $rs_sorder[0].iSOStatus eq 2} selected {/if} >In Progress</option>
                                                <option value="3" {if $rs_sorder[0].iSOStatus eq 3} selected {/if}>Delayed</option>
                                                <option value="4" {if $rs_sorder[0].iSOStatus eq 4} selected {/if}>Cancelled</option>
                                                <option value="5" {if $rs_sorder[0].iSOStatus eq 5} selected {/if}>Final Review</option>
                                                <option value="6" {if $rs_sorder[0].iSOStatus eq 6} selected {/if}>Carrier Approved</option>
                                                <option value="7" {if $rs_sorder[0].iSOStatus eq 7} selected {/if}>Final Approved</option>
                                            </select>
                                        <div class="invalid-feedback"> Please select service order status</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iCStatus">Connection Status <span class="text-danger">*</span></label>
                                        <select name="iCStatus" id="iCStatus" class="select">
                                                <option value="1"  {if $rs_sorder[0].iCStatus eq 1} selected {/if}>Created</option>
                                                <option value="2" {if $rs_sorder[0].iCStatus eq 2} selected {/if}>In-Progress</option>
                                                <option value="3" {if $rs_sorder[0].iCStatus eq 3} selected {/if}>Delayed</option>
                                                <option value="4" {if $rs_sorder[0].iCStatus eq 4} selected {/if}>On-Net</option>
                                            </select>
                                        <div class="invalid-feedback"> Please select connection status</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iSStatus">Service Status <span class="text-danger">*</span></label>
                                        <select name="iSStatus" id="iSStatus" class="select">
                                                <option value="0" {if $rs_sorder[0].iSStatus eq 0} selected {/if} >Pending</option>
                                                <option value="1"  {if $rs_sorder[0].iSStatus eq 1} selected {/if}>Active</option>
                                                <option value="2" {if $rs_sorder[0].iSStatus eq 2} selected {/if}>Suspended</option>
                                                <option value="3" {if $rs_sorder[0].iSStatus eq 3} selected {/if}>Trouble</option>
                                                <option value="4" {if $rs_sorder[0].iSStatus eq 4} selected {/if}>Disconnected</option>
                                            </select>
                                        <div class="invalid-feedback"> Please select service status</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="tComments">Comments</label>
                                        <textarea class="form-control" name="tComments" id="tComments" rows="4">{$rs_sorder[0].tComments|gen_filter_text}</textarea>
                                    </div>
                                    <div class="col-12 mb-3 ">
                                        <label for="vFile">File</label>
                                        <div class="input-group">
                                            <input type="file" class="d-inline-flex form-control-file form-control h-auto" id="vFile" name="vFile">
                                        </div>
                                        <input type="hidden" name="vFile_old" id="vFile_old" value="{$rs_sorder[0].vFile}">
                                        <span class="text-danger"> [valid extension file : *.docx, *.doc; *.xlsx; *.xls; *.pdf;]</span>
                                        {if $rs_sorder[0].file_url neq ''}
                                        <span class="mt-3"><br/>
                                            <a href="{$rs_sorder[0].file_url}" title="Download"><i class="fa fa-download"></i>&nbsp;&nbsp;Download</a>
                                        </span>
                                        {/if}
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
                        <button type="button" onclick="location.href = site_url+'service_order/list';" class="btn  btn-secondary  ml-2" > Close </button>
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
<script src="assets/vendors/typeahead/handlebars-v4.5.3.js"></script>
<script src="assets/vendors/typeahead/typeahead.bundle.js"></script>
<script type="text/javascript" src="assets/js/app_js/service_order_add.js"></script>
<!-- START: Page JS-->
<script type="text/javascript">
var mode = '{$mode}';
var A2D_COMPANY_ID = '{$A2D_COMPANY_ID}';
var sess_iCompanyId = '{$sess_iCompanyId}';
var sess_vCompanyAccessType = '{$sess_vCompanyAccessType}';
var iSalesRepId = '{$rs_sorder[0].iSalesRepId}';
var iConnectionTypeId = '{$rs_sorder[0].iConnectionTypeId}';
var iService1 = '{$rs_sorder[0].iService1}';
</script>
<style type="text/css">
    img.clear_address {
        position: absolute;
        right: 20px;
        top: 42px;
        width: 12px;
    }
</style>
