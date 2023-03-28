<div class="row  no-gutters w-100">
	<div class="col-12 mt-1">
		<div class="card">
			<div class="card-header  justify-content-between align-items-center">
				<div class="user_list_header">
					<h4 class="card-title float-left">{$module_name}</h4>

					<form id="frmlist" class="pc_search_form">
						<ul class="nav search-links float-right">
							<li>
								<select id="vOptions" name="vOptions" class="form-control" onchange="getDropdown(this.value);">
                  					<option value="iNetworkId">Network</option>
                  					<option value="iConnectionTypeId">Connection Type</option>
                  					<option value="vStatus">Status</option>
								</select>
							</li>
							<li id="network_dd">
		                        <select name="networkId" id="networkId" class="form-control col-md-12 search_filter_dd">
		                            <option value="">-- Select --</option> {section name="n" loop=$rs_ntwork} <option value="{$rs_ntwork[n].iNetworkId}">{$rs_ntwork[n].vName|gen_strip_slash}</option> {/section}
		                        </select>
							</li>
							<li id="connection_type_dd" style="display: none">
		                        <select name="ConnectionTypeId" id="ConnectionTypeId" class="form-control col-md-12 search_filter_dd">
		                            <option value="">-- Select --</option> {section name="c" loop=$rs_cntype}<option value="{$rs_cntype[c].iConnectionTypeId}">{$rs_cntype[c].vConnectionTypeName|gen_strip_slash}</option>
                                    {/section}
		                        </select>
							</li>
							<li id="status_dd" style="display:none">
								<select name="iStatus" id="iStatus" class="form-control col-md-12 search_filter_dd">
									<option value="">-- Select --</option> 
                                    <option value="1">Created</option>
                                    <option value="2">In Progress</option>
                                    <option value="3">Delayed</option>
                                    <option value="4">Connected</option>
                                    <option value="5">Active</option>
                                    <option value="6">Suspended</option>
                                    <option value="7">Trouble</option>
                                    <option value="8">Disconnected</option>
                                </select>
							</li>

							<li>
								<button type="button" id="Search" class="btn  btn-outline-warning fas fa-search" title="Search"><span class=""></span></button>
							</li>
							<li>
        						<button type="button" class="btn btn-danger btn-reset fas fa-times" aria-label="Close" id="Reset" style="cursor:pointer;" title="Reset" /></button>
							</li>
						</ul>
					</form>
				</div>
			</div>
			<div class="drop-search">
	          	<div class="drop-title">
	              	<div class="center">
	                  	<a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="anchor_adv_search" >Adv. Search<i class="fas fa-caret-down"></i></a>
	                  	<div class="collapse" id="collapseExample">
	                      	{include file="top/top_premise_circuit_advance_search.tpl"}
	                  	</div>
	              	</div>
	          	</div>
	      	</div>
			<div class="card-body">
				<div class="table-responsive">
				<table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table">
					<thead>
						<tr>
							<th width="1%">Id</th>
							<th width="20%">Premise</th>
							<th width="7%">WorkOrder</th>
							<th width="7%">Circuit Name</th>
							<th width="7%">Connection Type</th>
							<th width="25%">Carrier Services</th>
							<th width="20%">Equipment</th>
							<th width="7%">Status</th>
							<th width="6%">Action</th> 
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
	var ajax_url = 'premise_circuit/premise_circuit_list&mode=List';
	var access_group_var_add= '{$access_group_var_add}';

</script>
<script src="assets/js/app_js/premise_circuit.js"></script>
{include file="general/dataTables.tpl"}

