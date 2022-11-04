<div class="row  no-gutters w-100">
	<div class="col-12 mt-1">
		<div class="card">
			<div class="card-header  justify-content-between align-items-center">
				<div class="user_list_header">
					<h4 class="card-title float-left">{$module_name}</h4>

					<form id="frmlist">
						<ul class="nav search-links float-right">
							<li>
								<select id="vOptions" name="vOptions" class="form-control">
                  					<option value="iPremiseCircuitId">ID</option>
									<option value="iPremiseId">Premise ID</option>
									<option value="vPremise">Premise</option>
									<option value="iWOId">WorkOrder ID</option>
									<option value="vWorkOrderType">WorkOrder Type</option>
									<option value="iCircuitId">Circuit ID</option>
									<option value="vCircuitName">Circuit Name</option>
								</select>
							</li>
							<li>
							   <input type="text" name="Keyword" id="Keyword" class="form-control" value="" autocomplete="off">
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
							<th>Premise</th>
							<th>WorkOrder</th>
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
	var ajax_url = 'premise_circuit/premise_circuit_list&mode=List';
	var access_group_var_add= '{$access_group_var_add}';

</script>
<script src="assets/js/app_js/premise_circuit.js"></script>
{include file="general/dataTables.tpl"}

