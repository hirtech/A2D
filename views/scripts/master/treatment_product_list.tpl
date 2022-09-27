<div class="row  no-gutters w-100">
	<div class="col-12 mt-1">
		<div class="card">
			<div class="card-header  justify-content-between align-items-center">
				<div class="user_list_header">
					<h4 class="card-title float-left">{$module_name}</h4>
					<form id="frmlist" name="frmlist" class="site_search_form">
						<ul class="nav search-links float-right">
							<li>
								<select id="vOptions" name="vOptions" class="form-control">
									<option value="iTPId">ID</option>
									<option value="vName">Name</option>
									<option value="vCategory">Category</option>
									<option value="vClass">Class</option>
									<option value="iPesticide">Pesticide Reporting</option>
									<option value="iUId">Default Unit</option>
									<option value="iStatus">Status</option>
								</select>
							</li>
							<li>
							   <input type="text" name="Keyword" id="Keyword" class="form-control" value="" autocomplete="off">
							</li>
							<li>
								<button type="button" id="Search" class="btn  btn-outline-warning fas fa-search" title="Search"><span class=""></span></button>
							</li>
							<li>
        						<button type="button" class="btn btn-danger btn-reset fas fa-times" aria-label="Close" id="Reset" style="cursor:pointer;" title="Reset" ></button>
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
							<th>ID</th>
							<th>Name</th>
							<th>Category</th>
							<th>Include in Pesticide Reporting</th>
							<th>Class</th>
							<th>EPA Reg No</th>
							<th>Default Unit</th>
							<th>Target Application Rate</th>
							<th>Status</th>
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
{include file="scripts/master/treatment_product_add.tpl"}

<script type="text/javascript">
	var ajax_url = 'master/treatment_product_list&mode=List';
	var access_group_var_add= '{$access_group_var_add}';
</script>
<script src="assets/js/app_js/treatment_product.js"></script>

{include file="general/dataTables.tpl"}
