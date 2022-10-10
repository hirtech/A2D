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
									<option value="vCompanyType">Company Type</option>
									<option value="vCompanyName">Company Name</option>
									<option value="vNameId">Name Id</option>
									<option value="vAccessType">Access Type</option>
									<option value="vMSOYr">MSOYr</option>
									<option value="vMSANum">MSANum</option>
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
        						<button type="button" class="btn btn-danger btn-reset fas fa-times" aria-label="Close" id="Reset" style="cursor:pointer;" title="Reset" class=""/></button>
							</li>
						</ul>
					</form>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive ">
				<table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table " width="100%">
					<thead>
						<tr>
							<th width="8%">ID</th>
							<th width="12%">Company Type</th>
							<th width="12%">Company Name</th>
							<th width="8%" class="text-center">Name Id</th>
							<th width="12%">Access Type</th>
							<th width="8%" class="text-center">MSOYr</th>
							<th width="15%" class="text-center">MSANum</th>
							<th width="10%" class="text-center">Status</th>
							<th width="15%">Action</th> 
						</tr>
					</thead>
					<tbody>                            
					</tbody>
				</table>
				</div>
				<a class="btn btn-primary d-none" id="company_box" data-toggle="modal" href="#exampleModaltooltip">launch model</a>
                <div class="modal fade" id="exampleModaltooltip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="stmodaltitle"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body col-md-12">
                            	<form  id="frmadd" name="frmadd" action="" class="form-horizontal needs-validation" method="post" novalidate>
                            		<input type="hidden" name="mode" id="st_mode" value="">
                            		<input type="hidden" name="iCompanyId" id="company_id" value="">
                            		<div class="form-group row">
                            			<label class="col-sm-4 col-form-label" for="vCompanyType">Company Type <span class="text-danger"> *</span></label>
                            			<div class="col-sm-8">
	                            			<input type="text" name="vCompanyType" id="vCompanyType" placeholder="Company Type" class="form-control" value=""  required>
	                            			<div class="invalid-feedback">Please enter company type.</div>
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label class="col-sm-4 col-form-label" for="vCompanyType">Company Name <span class="text-danger"> *</span></label>
                            			<div class="col-sm-8">
	                            			<input type="text" name="vCompanyName" id="vCompanyName" placeholder="Company Name" class="form-control" value=""  required>
	                            			<div class="invalid-feedback">Please enter company name.</div>
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label class="col-sm-4 col-form-label" for="vNameId">Name Id <span class="text-danger"> *</span></label>
                            			<div class="col-sm-8">
	                            			<input type="text" name="vNameId" id="vNameId" placeholder="Name Id" class="form-control" value=""  required>
	                            			<div class="invalid-feedback">Please enter name id.</div>
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label class="col-sm-4 col-form-label" for="vAccessType">Access Type <span class="text-danger"> *</span></label>
                            			<div class="col-sm-8">
	                            			<input type="text" name="vAccessType" id="vAccessType" placeholder="Access Type" class="form-control" value=""  required>
	                            			<div class="invalid-feedback">Please enter access type.</div>
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label class="col-sm-4 col-form-label" for="vMSOYr">MSOYr <span class="text-danger"> *</span></label>
                            			<div class="col-sm-8">
	                            			<input type="text" name="vMSOYr" id="vMSOYr" placeholder="MSOYr" class="form-control" value=""  required>
	                            			<div class="invalid-feedback">Please enter MSOYr.</div>
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label class="col-sm-4 col-form-label" for="vMSANum">MSANum <span class="text-danger"> *</span></label>
                            			<div class="col-sm-8">
	                            			<input type="text" name="vMSANum" id="vMSANum" placeholder="MSANum" class="form-control" value=""  required>
	                            			<div class="invalid-feedback">Please enter MSANum.</div>
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label class="col-sm-4 col-form-label" for="iStatus">Status <span class="text-danger"> *</span></label>
                            			<div class="col-sm-8 ">
	                            			<input type="checkbox" id="iStatus" name="iStatus" value="1" data-toggle="toggle" data-on="Active" data-onstyle="success" data-off="Inactive" data-offstyle="danger"  data-width="100" data-width="26" >
	                            			<div class="invalid-feedback">Please select status.</div>
	                            		</div>
                            		</div>
                            	</form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                                 <img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;">
                                <input type="submit" class="btn btn-primary" id="save_data" value="Save" name="save_data" >
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div> 
	</div> 
</div>


<script type="text/javascript">
	var ajax_url = 'master/company_list?mode=List';
	var access_group_var_add = '{$access_group_var_add}';
</script>
{include file="general/dataTables.tpl"}


<script src="assets/js/app_js/company.js"></script>