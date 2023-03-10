<div class="row  no-gutters w-100">
	<div class="col-12 mt-1">
		<div class="card">
			<div class="card-header  justify-content-between align-items-center">
				<div class="user_list_header">
					<h4 class="card-title float-left">{$module_name}</h4>

					<form id="frmlist"  class="awareness_search_form">
						<ul class="nav search-links float-right">
							<li>
								<select id="vOptions" name="vOptions" class="form-control" onchange="getDropdown(this.value);">
									<option value="iNetworkId">Network</option>
                  					<option value="iEngagementId">Engagement</option>
                  					<option value="iTechnicianId">Technician</option>
								</select>
							</li>
							<li id="network_dd">
		                        <select name="networkId" id="networkId" class="form-control col-md-12 search_filter_dd">
		                            <option value="">-- Select --</option> {section name="n" loop=$rs_ntwork} <option value="{$rs_ntwork[n].iNetworkId}">{$rs_ntwork[n].vName|gen_strip_slash}</option> {/section}
		                        </select>
							</li>
							<li id="technician_dd" style="display: none">
		                        <select name="technicianId" id="technicianId" class="form-control col-md-12 search_filter_dd">
		                            <option value="">-- Select --</option> {section name="s" loop=$technician_user_arr}
	                                <option value="{$technician_user_arr[s].iUserId}">{$technician_user_arr[s].vName|gen_strip_slash}</option>
	                                {/section}
		                        </select>
							</li>
							<li id="engagement_dd" style="display: none">
		                        <select name="engagementId" id="engagementId" class="form-control col-md-12 search_filter_dd">
		                            <option value="">-- Select --</option> {section name="e" loop=$rs_engagement}<option value="{$rs_engagement[e].iEngagementId}">{$rs_engagement[e].vEngagement|gen_strip_slash}</option>
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
	                  	<a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="anchor_adv_search" >Adv. Search<i class="fas fa-caret-down"></i></a>
	                  	<div class="collapse" id="collapseExample">
	                      	{include file="top/top_task_awareness_advance_search.tpl"}
	                  	</div>
	              	</div>
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
	var access_group_var_CSV= '{$access_group_var_CSV}';
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

