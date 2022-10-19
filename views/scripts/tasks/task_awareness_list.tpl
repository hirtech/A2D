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
                  					<option value="iAId">ID</option>
									<option value="iPremiseId">Premise Id</option>
									<option value="vName">Premise Name</option>
									<option value="vEngagement">Engagement</option>
									<option value="iFiberInquiryId">FiberInquiry Id</option>
								</select>
							</li>
							<li>
							   <input type="text" name="Keyword" id="Keyword" class="form-control" value="" autocomplete="off">
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
			<div class="card-body">
				<div class="table-responsive">
				<table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Premise Name</th>
							<th>Address </th>
							<th>Fiber Inquiry </th>
							<th>Date</th>
							<th>Start Time</th>
							<th>End Time</th>
							<th>Engagement</th>
							<th>Notes</th>
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
{include file="scripts/tasks/task_awareness_add.tpl"}
<script type="text/javascript">
	// var iPremiseId = '{$iPremiseId}';
	// var extra_url = (jQuery.isEmptyObject(iPremiseId))?"":'&iPremiseId='+iPremiseId;
	// var ajax_url = 'tasks/task_awareness_list&mode=List'+extra_url;
	var ajax_url = 'tasks/task_awareness_list&mode=List';
	var access_group_var_add= '{$access_group_var_add}';
	var dDate= '{$dDate}';
    var dStartTime= '{$dStartTime}';
    var dEndTime= '{$dEndTime}';
</script>
<!-- START: Page Vendor JS-->
<script src="assets/vendors/typeahead/handlebars-v4.5.3.js"></script>
<script src="assets/vendors/typeahead/typeahead.bundle.js"></script>
<!-- END: Page Vendor JS-->
<script src="assets/js/app_js/task_awareness.js"></script>
<script src="assets/js/app_js/task_awareness_add.js"></script>
{include file="general/dataTables.tpl"}

