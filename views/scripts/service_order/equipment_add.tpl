<div class="row   no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}service_order/equipment_list">Equipment List</a></li>
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
                            <input type="hidden" name="iEquipmentId" id="iEquipmentId" value="{$rs_equipment[0].iEquipmentId}">
                            <div class="form-row">
                                <div class="col-4">
                                	<div class="col-12 mb-3">
                                        <label for="iEquipmentModelId">Equipment Model <span class="text-danger">*</span></label>
                                        <select name="iEquipmentModelId" id="iEquipmentModelId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="m" loop=$rs_model}
                                                <option value="{$rs_model[m].iEquipmentModelId}" {if $rs_model[m].iEquipmentModelId eq $rs_equipment[0].iEquipmentModelId}selected{/if}>{$rs_model[m].vModelName|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please enter equipment model</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="vSerialNumber">Serial Number <span class="text-danger">*</span></label>
                                        <input type="text" id="vSerialNumber" name="vSerialNumber" value="{$rs_equipment[0].vSerialNumber|gen_filter_text}" class="form-control" required>
                                        <div class="invalid-feedback"> Please enter serial number</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="vMACAddress">MAC Address <span class="text-danger">*</span></label>
                                        <input type="text" id="vMACAddress" name="vMACAddress" value="{$rs_equipment[0].vMACAddress|gen_filter_text}" class="form-control" required>
                                        <div class="invalid-feedback"> Please enter mac address</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="vIPAddress">IP Address <span class="text-danger">*</span></label>
                                        <input type="text" id="vIPAddress" name="vIPAddress" value="{$rs_equipment[0].vIPAddress|gen_filter_text}" class="form-control" required>
                                        <div class="invalid-feedback"> Please enter ip address</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="vSize">Size <span class="text-danger">*</span></label>
                                        <input type="text" id="vSize" name="vSize" value="{$rs_equipment[0].vSize|gen_filter_text}" class="form-control" required>
                                        <div class="invalid-feedback"> Please enter size</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="vWeight">Weight <span class="text-danger">*</span></label>
                                        <input type="text" id="vWeight" name="vWeight" value="{$rs_equipment[0].vWeight|gen_filter_text}" class="form-control" required>
                                        <div class="invalid-feedback"> Please enter weight</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iMaterialId">Material <span class="text-danger">*</span></label>
                                        <select name="iMaterialId" id="iMaterialId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="m" loop=$rs_material}
                                                <option value="{$rs_material[m].iMaterialId}" {if $rs_material[m].iMaterialId eq $rs_equipment[0].iMaterialId}selected{/if}>{$rs_material[m].vMaterial|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please enter equipment model</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="col-12 mb-3">
                                        <label for="iPowerId">Power Type <span class="text-danger">*</span></label>
                                        <select name="iPowerId" id="iPowerId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="p" loop=$rs_power}
                                                <option value="{$rs_power[p].iPowerId}" {if $rs_power[p].iPowerId eq $rs_equipment[0].iPowerId}selected{/if}>{$rs_power[p].vPower|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please enter equipment model</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iGrounded">Grounded <span class="text-danger">*</span></label>
                                        <select name="iGrounded" id="iGrounded" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="1" {if $rs_equipment[0].iGrounded eq 1}selected{/if}>Yes</option>
                                            <option value="0" {if $rs_equipment[0].iGrounded eq 0}selected{/if}>No</option>
                                            
                                        </select>
                                        <div class="invalid-feedback"> Please enter equipment model</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="dInstallByDate">Install By  <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="dInstallByDate" name="dInstallByDate" value="{$rs_equipment[0].dInstallByDate}" required=""> 
                                        <div class="invalid-feedback"> Please enter install by</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="dInstalledDate">Date Installed  <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="dInstalledDate" name="dInstalledDate" value="{$rs_equipment[0].dInstalledDate}" required=""> 
                                        <div class="invalid-feedback">Please select date installed</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="vPurchaseCost">Purchase cost <span class="text-danger">*</span></label>
                                        <input type="text" id="vPurchaseCost" name="vPurchaseCost" value="{$rs_equipment[0].vPurchaseCost|gen_filter_text}" class="form-control" required>
                                        <div class="invalid-feedback"> Please enter purchase cost</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="dPurchaseDate">Purchase Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="dPurchaseDate" name="dPurchaseDate" value="{$rs_equipment[0].dPurchaseDate}" required=""> 
                                        <div class="invalid-feedback">Please select purchase date</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="dWarrantyExpiration">Warranty Expiration <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="dWarrantyExpiration" name="dWarrantyExpiration" value="{$rs_equipment[0].dWarrantyExpiration}" required=""> 
                                        <div class="invalid-feedback">Please select warranty expiration</div>
                                    </div>                                    
                                </div>
                                <div class="col-4">
                                    <div class="col-12 mb-3">
                                        <label for="vWarrantyCost">Warranty Cost <span class="text-danger">*</span></label>
                                        <input type="text" id="vWarrantyCost" name="vWarrantyCost" value="{$rs_equipment[0].vWarrantyCost|gen_filter_text}" class="form-control" required>
                                        <div class="invalid-feedback"> Please enter warranty cost</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iPremiseId">Premise <span class="text-danger">*</span></label>
                                        <input type="text" name="vPremiseName"  class="form-control " id="vPremiseName" placeholder="Search Premise Id or Premise Name" value="{if $rs_equipment[0].iPremiseId}{if $rs_equipment[0].vPremiseName neq ''}{$rs_equipment[0].vPremiseName|gen_strip_slash} - {/if}PremiseID# {$rs_equipment[0].iPremiseId}{/if}"  required>
                                        <input type="hidden" id="search_iPremiseId" name="search_iPremiseId" value="{$rs_equipment[0].iPremiseId}"/>
                                        <img class="clear_address" src="assets/images/icon-delete.png" style="cursor:pointer;" onclick="return clear_serach_premise();">
                                        <div class="invalid-feedback" id="errmsg_search_premise">Please enter premise</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iInstallTypeId">Install Type<span class="text-danger">*</span></label>
                                        <select name="iInstallTypeId" id="iInstallTypeId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="i" loop=$rs_itype}
                                                <option value="{$rs_itype[i].iInstallTypeId}" {if $rs_itype[i].iInstallTypeId eq $rs_equipment[0].iInstallTypeId}selected{/if}>{$rs_itype[i].vInstallType|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please enter install type</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iPremiseCircuitId">Premise Circuit </label>
                                        <select name="iPremiseCircuitId" id="iPremiseCircuitId" class="form-control">
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iLinkTypeId">Link Type<span class="text-danger">*</span></label>
                                        <select name="iLinkTypeId" id="iLinkTypeId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="l" loop=$rs_ltype}
                                                <option value="{$rs_ltype[l].iLinkTypeId}" {if $rs_ltype[l].iLinkTypeId eq $rs_equipment[0].iLinkTypeId}selected{/if}>{$rs_ltype[l].vLinkType|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please enter install type</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="dProvisionDate">Provision Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="dProvisionDate" name="dProvisionDate" value="{$rs_equipment[0].dProvisionDate}" required=""> 
                                        <div class="invalid-feedback">Please select provision date</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iOperationalStatusId">Operation Status <span class="text-danger">*</span></label>
                                        <select name="iOperationalStatusId" id="iOperationalStatusId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="o" loop=$rs_ostatus}
                                                <option value="{$rs_ostatus[o].iOperationalStatusId}" {if $rs_ostatus[o].iOperationalStatusId eq $rs_equipment[0].iOperationalStatusId}selected{/if}>{$rs_ostatus[o].vOperationalStatus|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please enter install type</div>
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
                        <button type="button" onclick="location.href = site_url+'service_order/equipment_list';" class="btn  btn-secondary  ml-2" > Close </button>
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
<script type="text/javascript" src="assets/js/app_js/equipment_add.js"></script>
<!-- START: Page JS-->
<script type="text/javascript">
var mode = '{$mode}';
var iPremiseId = '{$rs_equipment[0].iPremiseId}'; 
var iPremiseCircuitId = '{$rs_equipment[0].iPremiseCircuitId}'; 
</script>
<style type="text/css">
    img.clear_address {
        position: absolute;
        right: 20px;
        top: 42px;
        width: 12px;
    }
</style>
