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
                                    <option value="vCarrier">Carrier</option>
                                    <option value="vConnectionType">Connection Type</option>
                                    <option value="vServiceType">Service Type</option>
                                    <option value="iSOStatus">Service Order Status</option>
                                    <option value="iCStatus">Connection Status</option>
                                    <option value="iSStatus">Service Status</option>
                                </select>
                            </li>
                            <li id="network_dd" class="searching_dd">
                                <select name="iSNetworkId" id="iSNetworkId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option> {section name="n" loop=$rs_ntwork} <option value="{$rs_ntwork[n].iNetworkId}">{$rs_ntwork[n].vName|gen_strip_slash}</option> {/section}
                                </select>
                            </li>
                            <li id="carrier_dd" style="display: none" class="searching_dd">
                                <select name="iSCarrierId" id="iSCarrierId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option> {section name="c" loop=$rs_carrier} <option value="{$rs_carrier[c].iCompanyId}">{$rs_carrier[c].vCompanyName|gen_strip_slash}</option> {/section}
                                </select>
                            </li>
                            <li id="connection_type_dd" style="display: none" class="searching_dd">
                                <select name="iConnectionTypeId" id="iConnectionTypeId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">Select</option>
                                    {section name="c" loop=$rs_cntype}
                                        <option value="{$rs_cntype[c].iConnectionTypeId}" {if $rs_cntype[c].iConnectionTypeId eq $rs_sorder[0].iConnectionTypeId}selected{/if}>{$rs_cntype[c].vConnectionTypeName|gen_strip_slash}</option>
                                    {/section}
                                </select>
                            </li>
                            <li id="service_type_dd" style="display: none" class="searching_dd">
                                <select name="iSServiceType" id="iSServiceType" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option> {section name="s" loop=$rs_stype} <option value="{$rs_stype[s].iServiceTypeId}">{$rs_stype[s].vServiceType|gen_strip_slash}</option> {/section}
                                </select>
                            </li>
                            <li id="service_order_status_dd" style="display: none" class="searching_dd">
                                <select name="iSOStatus" id="iSOStatus" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option>
                                    <option value="1">Created</option>
                                    <option value="2">In-Review</option>
                                    <option value="3">Approved</option>
                                    <option value="4">Cancelled</option>
                                    <option value="5">Final Review</option>
                                    <option value="6">Carrier Approved</option>
                                    <option value="7">Final Approved</option>
                                </select>
                            </li>
                            <li id="connection_status_dd" style="display: none" class="searching_dd">
                                <select name="iCStatus" id="iCStatus" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option>
                                    <option value="1">Created</option>
                                    <option value="2">In-Progress</option>
                                    <option value="3">Delayed</option>
                                    <option value="4">On-Net</option>
                                </select>
                            </li>
                            <li id="service_status_dd" style="display: none" class="searching_dd">
                                <select name="iSStatus" id="iSStatus" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Active</option>
                                    <option value="2">Suspended</option>
                                    <option value="3">Trouble</option>
                                    <option value="4">Disconnected</option>
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
			            <div class="collapse" id="collapseExample"> {include file="top/top_service_order_advance_search.tpl"} </div>
			        </div>
			    </div>
			</div>
            <div class="card-body ">
                <div class="table-responsive">
                    <table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table dt-responsive">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="chkall" onclick="checkall(this)"/></th>
                                <th>ID</th>
                                <th>Master MSA #</th>
                                <th>Service Order #</th>
                                <th>Carrier</th>
                                <th>Sales Rep</th>
                                <th>Location B</th>
                                <th>Connection Type</th>
                                <th>Service Type</th>
                                <th>SO Status</th>
                                <th>Service Status</th>
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
    var ajax_url = 'service_order/list&mode=List'+extra_url;
    var access_group_var_add = '{$access_group_var_add}';
    var access_group_var_CSV = '{$access_group_var_CSV}';
    var A2D_COMPANY_ID = '{$A2D_COMPANY_ID}';
    var sess_iCompanyId = '{$sess_iCompanyId}';
    var sess_vCompanyAccessType = '{$sess_vCompanyAccessType}';
</script>
<script src="assets/js/app_js/service_order.js"></script>