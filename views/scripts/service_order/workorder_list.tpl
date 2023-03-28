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
                                    <option value="vFiberZone">Fiber Zone</option>
                                    <option value="vWOType">Work Order Type</option>
                                    <option value="vRequestor">Requestor</option>
                                    <option value="vAssignedTo">Assigned To</option>
                                    <option value="vStatus">Status</option>
                                </select>
                            </li>
                            <li id="network_dd" class="searching_dd">
                                <select name="iSNetworkId" id="iSNetworkId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option> {section name="n" loop=$rs_ntwork} <option value="{$rs_ntwork[n].iNetworkId}">{$rs_ntwork[n].vName|gen_strip_slash}</option> {/section}
                                </select>
                            </li>
                            <li id="fiber_zone_dd" style="display: none" class="searching_dd">
                                <select name="iSZoneId" id="iSZoneId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option> {section name="z" loop=$rs_zone} <option value="{$rs_zone[z].iZoneId}">{$rs_zone[z].vZoneName|gen_strip_slash}</option> {/section}
                                </select>
                            </li>
                            <li id="work_order_type_dd" style="display: none" class="searching_dd">
                                <select name="iSWOTId" id="iSWOTId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">Select</option>
                                    {section name="t" loop=$rs_wotype}
                                        <option value="{$rs_wotype[t].iWOTId}" {if $rs_wotype[t].iWOTId eq $rs_sorder[0].iWOTId}selected{/if}>{$rs_wotype[t].vType|gen_strip_slash}</option>
                                    {/section}
                                </select>
                            </li>
                            <li id="requestor_dd" style="display: none" class="searching_dd">
                                <select name="iSRequestorId" id="iSRequestorId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option> 
                                    {section name="u" loop=$rs_user} 
                                    <option value="{$rs_user[u].iUserId}">{$rs_user[u].vName|gen_strip_slash}</option> 
                                    {/section}
                                </select>
                            </li>
                            <li id="assigned_to_dd" style="display: none" class="searching_dd">
                                <select name="iSAssignedToId" id="iSAssignedToId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option> 
                                    {section name="u" loop=$rs_user} 
                                    <option value="{$rs_user[u].iUserId}">{$rs_user[u].vName|gen_strip_slash}</option> 
                                    {/section}
                                </select>
                            </li>
                            <li id="status_dd" style="display: none" class="searching_dd">
                                <select name="iSWOSId" id="iSWOSId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option> 
                                    {section name="s" loop=$rs_status} 
                                    <option value="{$rs_status[s].iWOSId}">{$rs_status[s].vStatus|gen_strip_slash}</option> 
                                    {/section}
                                </select>
                            </li>
                            <li>
                                <button type="button" id="Search" class="btn  btn-outline-warning fas fa-search" title="Search"><span class=""></span></button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-danger btn-reset fas fa-times" aria-label="Close" id="Reset" style="cursor:pointer;" title="Reset" class=""/></button>
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
                        <div class="collapse" id="collapseExample"> {include file="top/top_workorder_advance_search.tpl"} </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive ">
                <table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="chkall" onclick="checkall(this)"/></th>
                            <th>ID</th>
                            <th>Premise</th>
                            <th>Service Order</th>
                            <th>Requestor</th>
                            <th>Work Order Project  </th>
                            <th>Work Order Type</th>
                            <th>Assigned To </th>
                            <th>Status </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>                            
                    </tbody>
                </table>
                </div>
            </div>
        </div> 
    </div> 
</div>


<script type="text/javascript">
    var iPremiseId = '{$iPremiseId}';
    var extra_url = (jQuery.isEmptyObject(iPremiseId))?"":'&iPremiseId='+iPremiseId;
    var ajax_url = 'service_order/workorder_list&mode=List'+extra_url;
    var access_group_var_add = '{$access_group_var_add}';
</script>
{include file="general/dataTables.tpl"}
<script src="assets/js/app_js/workorder.js"></script>