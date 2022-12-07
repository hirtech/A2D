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
                  					<option value="iTTId">ID</option>
									<option value="iPremiseId">Premise Id</option>
									<option value="vName">Premise Name</option>
									<option value="vTypeName">Premise Type</option>
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
							<th>Premise Name</th>
							<th>Address </th>
							<th>Trap Date</th>
							<th>Trap Collected Date</th>
							<th>Trap Type</th>
							<th>Notes</th>
							<th>Male Count</th>
							<th>Female Count</th>
							<th>Total Count</th>
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
	var ajax_url = 'lab_task/mosquito_count_list&mode=List';
</script>
<script src="assets/js/app_js/mosquito_count.js"></script>
{include file="general/dataTables.tpl"}

