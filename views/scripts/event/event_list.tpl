<div class="row  no-gutters w-100">
	<div class="col-12 mt-1">
		<div class="card">
			<div class="card-header  justify-content-between align-items-center">
				<div class="user_list_header">
					<h4 class="card-title float-left">{$module_name}</h4>

					<form id="frmlist" name="frmlist" class="event_search_form">
						<ul class="nav search-links float-right">
							<li>
								<select id="vOptions" name="vOptions" class="form-control">
                  					<option value="iEventId">ID</option>
									<option value="vEventType">Event Type</option>
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
			<div class="drop-search">
                <div class="drop-title">
                    <div class="center">
                        <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="anchor_adv_search">Adv. Search <i class="fas fa-caret-down"></i>
                        </a>
                        <div class="collapse" id="collapseExample"> {include file="top/top_event_advance_search.tpl"} </div>
                    </div>
                </div>
            </div>
			<div class="card-body">
				<div class="table-responsive">
				<table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Event Type</th>
							<th>Campaign By</th>
							<th>Campaign Coverage</th>
							<th>Status</th>
							<th>Date Completed</th>
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
	var ajax_url = 'event/event_list&mode=List';
	var access_group_var_add= '{$access_group_var_add}';
	var access_group_var_CSV= '{$access_group_var_CSV}';
</script>
<script src="assets/js/app_js/event.js"></script>
{include file="general/dataTables.tpl"}

