<div class="row  no-gutters w-100">
	<div class="col-12 mt-1">
		<div class="card">
			<div class="card-header  justify-content-between align-items-center">
				
				<div class="user_list_header">
					 <h4 class="card-title float-left">{$module_name}</h4>
					
					 <form id="frmlist">
						<ul class="nav search-links float-right">
							<li>
								<select name="vOptions" id="vOptions" class="form-control">
									<option value="vUsername">Username</option>
                  					<option value="vIP">IP</option>
								</select>
							</li>
							<li>
							   <input type="text" name="Keyword" id="Keyword" value="" autocomplete="off">
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
				<table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table">
					<thead>
						<tr>
							<!-- <th><input type="checkbox" id="chkall" onclick="checkall(this)"/></th> -->
							<th>ID</th>
							<th>Username</th>
							<th>Name</th>
							<th>IP</th>
							<th>Login Time</th>
							<th>Logout Time</th>
							<th>Time Duration</th>
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
{include file="general/dataTables.tpl"}

<script type="text/javascript">
  var iUserId = '{$iUserId}';
  var extra_url = (jQuery.isEmptyObject(iUserId))?"":'&iUserId='+iUserId;
  var ajax_url = 'login_history/list&mode=List'+extra_url;
</script>
<script src="assets/js/app_js/login_history.js"></script>
