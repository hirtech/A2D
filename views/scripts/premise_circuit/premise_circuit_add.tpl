<div class="row  no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$mode} {$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}premise_circuit/premise_circuit_list">Premise Circuit List</a></li>
                <li class="breadcrumb-item active">{$module_name}</li>
            </ol>
        </div>
    </div>
</div>
<form name="frmadd" id="frmadd" method="post" class="form-horizontal needs-validation" novalidate enctype="multipart/form-data">
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
                            <input type="hidden" name="iPremiseCircuitId" id="iPremiseCircuitId" value="{$rs_data[0]['iPremiseCircuitId']}">
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="col-12 mb-3">
                                        <label for="iWOId">Work Order <span class="text-danger">*</span></label>
                                        <input type="text" name="vWorkOrder"  class="form-control " id="vWorkOrder" placeholder="Search Premise ID, Premise, Address, or Workorder ID or Workorder Type" value="{$rs_data[0].vPremiseDisplay}"  required>
                                        <input type="hidden" id="search_iWOId" name="search_iWOId" value="{$rs_data[0].iWOId}"/>
                                        <img class="clear_address" src="assets/images/icon-delete.png" style="cursor:pointer;" onclick="return clear_serach_workorder();">
                                        <div class="invalid-feedback" id="errmsg_search_workorder">Please select workorder</div>
                                    </div>
                            		<div class="col-12 mb-3">
                                        <label for="iCircuitId">Circuit <span class="text-danger">*</span></label>
                                        <select name="iCircuitId" id="iCircuitId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="c" loop=$rs_circuit}
                                                <option value="{$rs_circuit[c].iCircuitId}" {if $rs_circuit[c].iCircuitId eq $rs_data[0].iCircuitId}selected{/if}>{$rs_circuit[c].vCircuitName|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please select Circuit</div>
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
                        <div class="form-row mt-3">
                            <div class="col-12 ml-3 float-right">
                                <button type="submit" class="btn btn-primary" id="save_data" value="submit">Save </button>  
                            	<img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;">   
                                <button type="button" onclick="location.href = site_url+'premise_circuit/premise_circuit_list';" class="btn btn-secondary ml-2"> Close </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>
<script src="assets/vendors/typeahead/handlebars-v4.5.3.js"></script>
<script src="assets/vendors/typeahead/typeahead.bundle.js"></script>
<script src="assets/js/app_js/premise_circuit_add.js"></script>
<script type="text/javascript">
var access_group_var_add= '{$access_group_var_add}';
var access_group_var_CSV= '{$access_group_var_CSV}';
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