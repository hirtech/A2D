<div class="row  no-gutters w-100">
    <div class="col-12 mt-1">
        <div class="card">
            <div class="card-header  justify-content-between align-items-center">
                <div class="user_list_header">
                    <h4 class="card-title float-left">{$module_name}</h4>
                    <form id="frmlist" name="frmlist" class="sorder_search_form">
                        <ul class="nav search-links float-right">
                            <li>
                                <select id="vOptions" name="vOptions" class="form-control" onchange="getDropdown(this.value);">
                                    <option value="vNetwork">Network</option>
                                    <option value="vOStatus">Operational Status</option>
                                    <option value="vSModelName">Equipment Model</option>
                                    <option value="vMaterial">Material</option>
                                    <option value="vPType">Power Type</option>
                                    <option value="vGrounded">Grounded</option>
                                    <option value="vIType">Install Type</option>
                                    <option value="vLType">Link Type</option>
                                </select>
                            </li>
                            <li id="network_dd" class="searching_dd">
                                <select name="networkId" id="networkId" class="form-control col-md-12 search_filter_dd search_filter_dd">
                                    <option value="">-- Select --</option> {section name="n" loop=$rs_ntwork} <option value="{$rs_ntwork[n].iNetworkId}">{$rs_ntwork[n].vName|gen_strip_slash}</option> {/section}
                                </select>
                            </li>
                            <li id="operational_status_dd" style="display: none" class="searching_dd">
                                <select name="iOStatus" id="iOStatus" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option>
                                    {section name="o" loop=$rs_ostatus}
                                        <option value="{$rs_ostatus[o].iOperationalStatusId}" {if $rs_ostatus[o].iOperationalStatusId eq $rs_equipment[0].iOperationalStatusId}selected{/if}>{$rs_ostatus[o].vOperationalStatus|gen_strip_slash}</option>
                                    {/section}
                                </select>
                            </li>
                            <li id="equipment_model_dd" style="display: none" class="searching_dd">
                                <select name="iEModel" id="iEModel" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option>{section name="m" loop=$rs_model}
                                    <option value="{$rs_model[m].iEquipmentModelId}">{$rs_model[m].vModelName|gen_strip_slash}</option>{/section}
                                </select>
                            </li>
                            <li id="material_dd" style="display: none" class="searching_dd">
                                <select name="iMaterialId" id="iMaterialId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option>
                                    {section name="m" loop=$rs_material}
                                    <option value="{$rs_material[m].iMaterialId}">{$rs_material[m].vMaterial|gen_strip_slash}</option>
                                    {/section}
                                </select>
                            </li>
                            <li id="power_type_dd" style="display: none" class="searching_dd">
                                <select name="iPowerId" id="iPowerId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option>
                                    {section name="p" loop=$rs_power}
                                    <option value="{$rs_power[p].iPowerId}">{$rs_power[p].vPower|gen_strip_slash}</option>
                                    {/section}
                                </select>
                            </li>
                            <li id="grounded_dd" style="display: none" class="searching_dd">
                                <select name="iGrounded" id="iGrounded" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </li>
                            <li id="install_type_dd" style="display: none" class="searching_dd">
                                <select name="iInstallTypeId" id="iInstallTypeId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option>
                                    {section name="i" loop=$rs_itype}
                                        <option value="{$rs_itype[i].iInstallTypeId}" {if $rs_itype[i].iInstallTypeId eq $rs_equipment[0].iInstallTypeId}selected{/if}>{$rs_itype[i].vInstallType|gen_strip_slash}</option>
                                    {/section}
                                </select>
                            </li>
                            <li id="link_type_dd" style="display: none" class="searching_dd">
                                <select name="iLinkTypeId" id="iLinkTypeId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option>
                                    {section name="l" loop=$rs_ltype}
                                        <option value="{$rs_ltype[l].iLinkTypeId}" {if $rs_ltype[l].iLinkTypeId eq $rs_equipment[0].iLinkTypeId}selected{/if}>{$rs_ltype[l].vLinkType|gen_strip_slash}</option>
                                    {/section}
                                </select>
                            </li>
                            <li>
                                <button type="button" id="Search" class="btn  btn-outline-warning fas fa-search" title="Search">
                                    <span class=""></span>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-danger btn-reset fas fa-times" aria-label="Close" id="Reset" style="cursor:pointer;" title="Reset"></button>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
            <div class="drop-search">
			    <div class="drop-title">
			        <div class="center">
			            <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="anchor_adv_search">Adv. Search <i class="fas fa-caret-down"></i>
			            </a>
			            <div class="collapse" id="collapseExample"> {include file="top/top_equipment_advance_search.tpl"} </div>
			        </div>
			    </div>
			</div> 
            <div class="card-body ">
                <div>
                    <table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table  dt-responsive">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Equipment Model</th>
                                <th>Serial Number</th>
                                <th>MAC Address</th>
                                <th>Purchase Date</th>
                                <th>Warranty Expiration</th>
                                <th>Premise</th>
                                <th>Premise Circuit</th>
                                <th>Operation Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 
{include file="general/dataTables.tpl"} 
<script type="text/javascript">
    var iPremiseId = '{$iPremiseId}';
    var extra_url = (jQuery.isEmptyObject(iPremiseId))?"":'&iPremiseId='+iPremiseId;
    var ajax_url = 'service_order/equipment_list&mode=List'+extra_url;
    var access_group_var_add = '{$access_group_var_add}';
    var access_group_var_CSV = '{$access_group_var_CSV}';
</script>
<script src="assets/js/app_js/equipment.js"></script>