<div class="row   no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}service_order/equipment_model_list">Equipment Model List</a></li>
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
                            <input type="hidden" name="iEquipmentModelId" id="iEquipmentModelId" value="{$rs_model[0].iEquipmentModelId}">
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="col-12 mb-3">
                                        <label for="vModelName">Model Name <span class="text-danger">*</span></label>
                                        <input type="text" id="vModelName" name="vModelName" value="{$rs_model[0].vModelName|gen_filter_text}" class="form-control" required>
                                        <div class="invalid-feedback"> Please enter model name</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="vModelNumber">Model Number </label>
                                        <input type="text" id="vModelNumber" name="vModelNumber" value="{$rs_model[0].vModelNumber|gen_filter_text}" class="form-control">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="vPartNumber">Part Number </label>
                                        <input type="text" id="vPartNumber" name="vPartNumber" value="{$rs_model[0].vPartNumber|gen_filter_text}" class="form-control">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="iUnitQuantity">Unit Quantity <span class="text-danger">*</span></label>
                                        <input type="text" id="iUnitQuantity" name="iUnitQuantity" value="{$rs_model[0].iUnitQuantity}" class="form-control" required>
                                        <div class="invalid-feedback"> Please enter unit quantity</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="rUnitCost">Unit Cost <span class="text-danger">*</span></label>
                                        <input type="text" id="rUnitCost" name="rUnitCost" value="{$rs_model[0].rUnitCost}" class="form-control" required>
                                        <div class="invalid-feedback"> Please enter unit cost</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="col-12 mb-3">
                                        <label for="iEquipmentTypeId">Equipment Type <span class="text-danger">*</span></label>
                                        <select name="iEquipmentTypeId" id="iEquipmentTypeId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="e" loop=$rs_etype}
                                                <option value="{$rs_etype[e].iEquipmentTypeId}" {if $rs_etype[e].iEquipmentTypeId eq $rs_model[0].iEquipmentTypeId}selected{/if}>{$rs_etype[e].vEquipmentType|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please select equipment type</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iEquipmentManufacturerId"> Manufacturer <span class="text-danger">*</span></label>
                                        <select name="iEquipmentManufacturerId" id="iEquipmentManufacturerId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="m" loop=$rs_emanu}
                                                <option value="{$rs_emanu[m].iEquipmentManufacturerId}" {if $rs_emanu[m].iEquipmentManufacturerId eq $rs_model[0].iEquipmentManufacturerId}selected{/if}>{$rs_emanu[m].vEquipmentManufacturer|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please select manufacturer</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="tDescription">Description</label>
                                        <textarea class="form-control" name="tDescription" id="tDescription" rows="5">{$rs_model[0].tDescription|gen_filter_text}</textarea>
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
                        <button type="button" onclick="location.href = site_url+'service_order/equipment_model_list';" class="btn  btn-secondary  ml-2" > Close </button>
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
<script type="text/javascript" src="assets/js/app_js/equipment_model_add.js"></script>
<!-- START: Page JS-->
<script type="text/javascript">
var mode = '{$mode}';
</script>

