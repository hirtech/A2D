<div class="row  no-gutters w-100">
	<div class="col-12 mt-1">
		<div class="card">
			<div class="card-header  justify-content-between align-items-center">
				<div class="user_list_header">
					<h4 class="card-title float-left">{$module_name}</h4>

					<form id="frmlist">
						<ul class="nav search-links float-right">
							<li>
								<select id="vOptions" name="vOptions" class="form-control" onchange="getDropdown(this.value);">
									<option value="vCircuitType">Circuit Type</option>
									<option value="vNetwork">Network</option>
								</select>
							</li>
							<li id="circuit_type_dd" class="searching_dd">
                                <select name="circuitTypeId" id="circuitTypeId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option> {section name="c" loop=$rs_ctype} <option value="{$rs_ctype[c].iCircuitTypeId}">{$rs_ctype[c].vCircuitType|gen_strip_slash}</option> {/section}
                                </select>
                            </li>
                            <li id="network_dd" class="searching_dd" style="display: none;">
                                <select name="networkId" id="networkId" class="form-control col-md-12 search_filter_dd">
                                    <option value="">-- Select --</option> {section name="n" loop=$rs_ntwork} <option value="{$rs_ntwork[n].iNetworkId}">{$rs_ntwork[n].vName|gen_strip_slash}</option> {/section}
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
			<div class="card-body">
				<div class="table-responsive">
				<table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table" width="100%">
					<thead>
						<tr>
							<th>Id</th>
							<th>Circuit Type</th>
							<th>Network</th>
							<th>Circuit Name</th>
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
	var ajax_url = 'circuit/circuit_list&mode=List';
	var access_group_var_add= '{$access_group_var_add}';

</script>
<script src="assets/js/app_js/circuit.js"></script>
{include file="general/dataTables.tpl"}

