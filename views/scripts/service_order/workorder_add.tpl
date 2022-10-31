<div class="row   no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}service_order/workorder_list">WorkOrder List</a></li>
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
                            <input type="hidden" name="iWOId" id="iWOId" value="{$rs_sorder[0].iWOId}">
                            <div class="form-row">
                                <div class="col-6">
                                	<div class="col-12 mb-3">
                                        <label for="iPremiseId">Premise <span class="text-danger">*</span></label>
                                        <input type="text" name="vPremiseName"  class="form-control " id="vPremiseName" placeholder="Search Premise Id or Premise Name" value="{$rs_sorder[0].vPremiseDisplay}"  required>
                                        <input type="hidden" id="search_iPremiseId" name="search_iPremiseId" value="{$rs_sorder[0].iPremiseId}"/>
                                        <img class="clear_address" src="assets/images/icon-delete.png" style="cursor:pointer;" onclick="return clear_serach_premise();">
                                        <div class="invalid-feedback" id="errmsg_search_premise">Please enter premise</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iServiceOrderId">Service Order <span class="text-danger">*</span></label>
                                        <input type="text" name="vServiceOrder"  class="form-control " id="vServiceOrder" placeholder="Search Service Order Id or Service Order Name" value="{$rs_sorder[0].vSODisplay}"  required>
                                        <input type="hidden" id="search_iServiceOrderId" name="search_iServiceOrderId" value="{$rs_sorder[0].iServiceOrderId}"/>
                                        <img class="clear_address" src="assets/images/icon-delete.png" style="cursor:pointer;" onclick="return clear_serach_serviceorder();">
                                        <div class="invalid-feedback" id="errmsg_search_serviceorder">Please enter service order</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iRequestorId">Requestor <span class="text-danger">*</span></label>
                                        <select name="iRequestorId" id="iRequestorId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="u" loop=$rs_user}
                                                <option value="{$rs_user[u].iUserId}" {if $rs_user[u].iUserId eq $rs_sorder[0].iRequestorId}selected{/if}>{$rs_user[u].vDisplay|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please select requestor</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="vWOProject">Work Order Project <span class="text-danger">*</span></label>
                                        <input type="text" id="vWOProject" name="vWOProject" value="{$rs_sorder[0].vWOProject|gen_filter_text}" class="form-control" required>
                                        <div class="invalid-feedback"> Please enter workorder project</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iWOTId">Work Order Type <span class="text-danger">*</span></label>
                                        <select name="iWOTId" id="iWOTId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="t" loop=$rs_wotype}
                                                <option value="{$rs_wotype[t].iWOTId}" {if $rs_wotype[t].iWOTId eq $rs_sorder[0].iWOTId}selected{/if}>{$rs_wotype[t].vType|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please select work order type</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="col-12 mb-3">
                                        <label for="tDescription">Work Order Description</label>
                                        <textarea class="form-control" name="tDescription" id="tDescription" rows="5">{$rs_sorder[0].tDescription|gen_filter_text}</textarea>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iAssignedToId">Assigned To <span class="text-danger">*</span></label>
                                        <select name="iAssignedToId" id="iAssignedToId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="u" loop=$rs_user}
                                                <option value="{$rs_user[u].iUserId}" {if $rs_user[u].iUserId eq $rs_sorder[0].iAssignedToId}selected{/if}>{$rs_user[u].vDisplay|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please select assgined to</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iWOSId"> Status <span class="text-danger">*</span></label>
                                        <select name="iWOSId" id="iWOSId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="s" loop=$rs_status}
                                                <option value="{$rs_status[s].iWOSId}" {if $rs_status[s].iWOSId eq $rs_sorder[0].iWOSId}selected{/if}>{$rs_status[s].vStatus|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please select Status</div>
                                    </div>
                                    {if $mode eq 'Update' && $rs_sorder[0].dClosedDate neq ''}
                                    <div class="col-12 mb-3">
                                        <label for="dClosedDate"> Closed Date</label>
                                        <br/>
                                        <strong>{$rs_sorder[0].dClosedDate}</strong>
                                    </div>
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
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <!-- <div class="w-sm-100 mr-auto"></div> -->
                        <button type="submit" class="btn btn-primary ml-2 " id="save_data"> Save </button> 
                            <img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;">  
                        <button type="button" onclick="location.href = site_url+'service_order/workorder_list';" class="btn  btn-secondary  ml-2" > Close </button>
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
<script type="text/javascript" src="assets/js/app_js/workorder_add.js"></script>
<!-- START: Page JS-->
<script type="text/javascript">
var mode = '{$mode}';
</script>
<style type="text/css">
    img.clear_address {
        position: absolute;
        right: 20px;
        top: 42px;
        width: 12px;
    }
</style>
