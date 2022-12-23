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
									<option value="iServicePricingId">ID</option>
									<option value="vCarrier">Carrier</option>
									<option value="vNetwork">Network</option>
									<option value="vConnectionType">Connection Type</option>
									<option value="vServiceType">Service Type</option>
									<option value="vServiceLevel">Service Level</option>
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
					<table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table " width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Carrier</th>
								<th>Network</th>
								<th>Connection Type</th>
								<th>Service Type</th>
								<th>Service Level</th>
								<th>NRC - Variable</th>
								<th>MRC - Fixed</th>
								<th>Document</th> 
								<th>Action</th> 
							</tr>
						</thead>
						<tbody>                            
						</tbody>
					</table>
				</div>
				<a class="btn btn-primary d-none" id="service_pricing_box" data-toggle="modal" href="#exampleModaltooltip">launch model</a>
                <div class="modal fade" id="exampleModaltooltip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-wmax750" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="stmodaltitle"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body col-md-12">
                            	<form  id="frmadd" name="frmadd" action="" class="form-horizontal needs-validation" method="post"  novalidate enctype="multipart/form-data"  >
                            		<input type="hidden" name="mode" id="st_mode" value="">
                            		<input type="hidden" name="iServicePricingId" id="service_pricing_id" value="">
                            		<div class="form-group row">
                            			<label class="col-sm-5 col-form-label" for="iCarrierId">Carrier <span class="text-danger"> *</span></label>
                            			<div class="col-sm-7">
	                            			<select name="iCarrierId" id="iCarrierId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="c" loop=$rs_carrier}
                                                <option value="{$rs_carrier[c].iCompanyId}">{$rs_carrier[c].vCompanyName|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please select carrier</div>
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label class="col-sm-5 col-form-label" for="iNetworkId">Network <span class="text-danger"> *</span></label>
                            			<div class="col-sm-7">
	                            			<select name="iNetworkId" id="iNetworkId" class="form-control" required>
	                                            <option value="">Select</option>
	                                            {section name="n" loop=$rs_ntwork}
	                                            <option value="{$rs_ntwork[n].iNetworkId}">{$rs_ntwork[n].vName|gen_strip_slash}</option>
	                                            {/section}
	                                        </select>
                                        	<div class="invalid-feedback"> Please select network</div>
	                            		</div>
                            		</div>
                            		<div class="form-group row">
                            			<label class="col-sm-5 col-form-label" for="iConnectionTypeId">Connection Type <span class="text-danger"> *</span></label>
                            			<div class="col-sm-7">
	                            			<select name="iConnectionTypeId" id="iConnectionTypeId" class="form-control" required>
	                                            <option value="">Select</option>
	                                            {section name="n" loop=$rs_ctype}
	                                            <option value="{$rs_ctype[n].iConnectionTypeId}">{$rs_ctype[n].vConnectionTypeName|gen_strip_slash}</option>
	                                            {/section}
	                                        </select>
                                        	<div class="invalid-feedback"> Please select connection type</div>
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label class="col-sm-5 col-form-label" for="iServiceTypeId">Service Type <span class="text-danger"> *</span></label>
                            			<div class="col-sm-7">
	                            			<select name="iServiceTypeId" id="iServiceTypeId" class="form-control" required>
	                                            <option value="">Select</option>
	                                            {section name="s" loop=$rs_stype}
	                                                <option value="{$rs_stype[s].iServiceTypeId}">{$rs_stype[s].vServiceType}</option>
	                                            {/section}
	                                        </select>
	                                        <div class="invalid-feedback"> Please select service type</div>
	                            		</div>
                            		</div>
                            		<div class="form-group row">
                            			<label class="col-sm-5 col-form-label" for="iServiceLevel">Service Level <span class="text-danger"> *</span></label>
                            			<div class="col-sm-7">
                            				<select name="iServiceLevel" id="iServiceLevel" class="form-control">
                            					<option value="">Select</option>
	                                            <option value="1">Best Effort</option>
	                                            <option value="2">Business Class</option>
	                                            <option value="3">SLA</option>
	                                            <option value="4">High Availability</option>
	                                        </select>
	                                        <div class="invalid-feedback"> Please select service level</div>
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label class="col-sm-5 col-form-label" for="iNetworkId">NRC Variable</label>
                            			<div class="col-sm-7">
	                            			<div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0" id="basic-addon11"><i class=" fas fa-dollar-sign"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="iNRCVariable" name="iNRCVariable" aria-describedby="basic-addon1">
                                            </div>
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label class="col-sm-5 col-form-label" for="iNetworkId">MRC Fixed</label>
                            			<div class="col-sm-7">
	                            			<div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0" id="basic-addon11"><i class=" fas fa-dollar-sign"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="iMRCFixed" name="iMRCFixed" aria-describedby="basic-addon1">
                                            </div>
	                            		</div>
                            		</div>
                            		<div class="form-group row">
                            			<label class="col-sm-5 col-form-label" for="vFile">Document</label>
                            			<div class="col-sm-7 d-inline-flex">
                            				<input type="file" class="d-inline-flex form-control-file" id="vFile" name="vFile" >
                            				<span id="icon_image"></span>
                            				<input type="hidden" name="vFile_old" id="vFile_old" value="">

                            			</div>
                            				&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-danger"> [valid extension file : *.docx, *.doc; *.pdf]</span> 
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
	var ajax_url = 'master/service_pricing_list?mode=List';
	var access_group_var_add = '{$access_group_var_add}';
</script>
{include file="general/dataTables.tpl"}


<script src="assets/js/app_js/service_pricing.js"></script>