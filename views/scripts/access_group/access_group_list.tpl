<div class="row  no-gutters w-100">
	<div class="col-12 mt-1">
		<div class="card">
			<div class="card-header  justify-content-between align-items-center">
				<div class="user_list_header">
					<h4 class="card-title float-left">{$module_name}</h4>

					<form id="frmlist" name="frmlist" class="frmlistsearch">
						<ul class="nav search-links float-right">
							<li>
								<select id="vOptions" name="vOptions" class="form-control">
									<option value="vAccessGroup">Access Group</option>
									<option value="tDescription">Description</option>
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
        						<button type="button" class="btn btn-outline-danger btn-reset fas fa-times" aria-label="Close" id="Reset" style="cursor:pointer;" title="Reset" /></button>
							</li>
						</ul>
					</form>
				</div>
			</div>
			<div class="card-body  ">
				<div class="table-responsive">
				<table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table" width="100%">
					<thead>
						<tr>
							<th width="10%">ID</th>
							<th width="20%">Access Group</th>
							<th width="20%">Description</th>
							<th width="20%">Access Right</th>
							<th width="20%">Status</th>
							<th width="10%">Action</th> 
						</tr>
					</thead>
					<tbody>                            
					</tbody>
				</table>
				</div>
				<a class="btn btn-primary d-none" id="addedit_box" data-toggle="modal" href="#exampleModaltooltip">launch model</a>
                <div class="modal fade" id="exampleModaltooltip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addeditmodaltitle"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body col-md-12">
                            	<form  id="frmadd" name="frmadd" action="" class="form-horizontal needs-validation" method="post" novalidate>
                            		<input type="hidden" name="mode" id="modal_mode" value="">
                            		<input type="hidden" name="iAGroupId" id="iAGroupId" value="">
                            		<div class="form-group row">
                            			<label class="col-sm-4 col-form-label" for="vAccessGroup">Access Group<span class="text-danger"> *</span></label>
                            			<div class="col-sm-8">
	                            			<input type="text" name="vAccessGroup" id="vAccessGroup" placeholder="Access Group Name" class="form-control" value=""  required>
	                            			<div class="invalid-feedback">Please enter acess group.</div>
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label class="col-sm-4 col-form-label" for="tDescription">Description</label>
                            			<div class="col-sm-8">
                            				<textarea name="tDescription" id="tDescription" placeholder="Description " class="form-control" ></textarea>
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label class="col-sm-4 col-form-label" >Status <span> *</span></label>
                            			<div class="col-sm-8">
	                            			<input type="checkbox" id="iStatus" name="iStatus" value="1" data-toggle="toggle" data-on="Active" data-onstyle="success" data-off="Inactive" data-offstyle="danger"  data-width="100" data-width="26" >
	                            			<div class="invalid-feedback">Please select status.</div>
	                            		</div>
                            		</div>
                            	</form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                                 <img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;"> 
                                <input type="submit" class="btn btn-primary" id="save_data" value="Save" name="save_data">
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div> 
	</div> 
</div>


<script type="text/javascript">
	var ajax_url = 'access_group/access_group_list?mode=List';
	var access_group_var_add = '{$access_group_var_add}';
</script>
{include file="general/dataTables.tpl"}



<script src="assets/js/app_js/access_group.js"></script>