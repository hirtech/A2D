<div class="row  no-gutters w-100">
	<div class="col-12 mt-1">
		<div class="card">
				<div class="card-header  justify-content-between align-items-center">
					<div class="user_list_header">
						 <h4 class="card-title float-left">{$module_name}</h4>
						<form id="frmlist" name="frmlist" class="contact_search_form">
							<ul class="nav search-links float-right">
								<li>
									<select id="vOptions" name="vOptions" class="form-control">
										<option value="iCId">Id</option>
										<option value="Name">Name</option>
										<option value="Company">Company</option>
										<option value="Position">Position</option>
										<option value="Phone">Primary Phone</option>
	                  					<option value="Email">Email</option>
	                  					<option value="iStatus">Status</option>
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
		      	<div class="drop-search">
		          <div class="drop-title">
		              <div class="center">
		                  <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="anchor_adv_search" >Adv. Search<i class="fas fa-caret-down"></i></a>
		                  <div class="collapse" id="collapseExample">
		                      {include file="top/top_contact_advance_search.tpl"}
		                  </div>
		              </div>
		          </div>
		      	</div>
			<div class="card-body">
				<div class="table-responsive">
				<table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table " width="100%">
					<thead>
						<tr>
							<!-- <th><input type="checkbox" id="chkall" onclick="checkall(this)"/></th> -->
							<th>ID</th>
							<th>Name</th>
							<th>Company</th>
							<th>Position</th>
							<th>Primary Phone</th>
							<th>Email</th>
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
{include file="general/dataTables.tpl"}

{include file="scripts/contact/contact_addedit_popup.tpl"}
{include file="scripts/contact/contact_history.tpl"}

<script type="text/javascript">
	var access_group_var_add= '{$access_group_var_add}';
	var access_group_var_CSV= '{$access_group_var_CSV}';
</script>

<script src="assets/js/app_js/contact.js"></script>
<script src="assets/js/app_js/contact_add.js"></script>
<script src="assets/js/app_js/contact_history.js"></script>
