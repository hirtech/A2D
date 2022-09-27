<div class="row   no-gutters w-100">
	<div class="col-12  align-self-center">
		<div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
			<div class="w-sm-100 mr-auto"><h4 class="mb-0">{$mode} {$module_name}</h4></div>
			<ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
				<li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
				<li class="breadcrumb-item"><a href="{$site_url}sr/list">SR List</a></li>
				<li class="breadcrumb-item active">{$module_name}</li>
			</ol>
		</div>
	</div>
</div>
<form name="frmadd" id="frmadd" method="post" class="form-horizontal needs-validation" novalidate enctype="multipart/form-data">
	<div class="row">
		<div class="col-12 mt-3">
			<div class="card">
				<div class="card-content">
					<div class="card-body">
						<div class="row">                                           
							<div class="col-12">
								<input type="hidden" name="groupAction" value="groupAction">
								<input type="hidden" name="mode" id="mode" value="{$mode}">
								<input type="hidden" name="iSRId" id="iSRId" value="{$rs_sr[0].iSRId}" />
								<input type="hidden" name="vAddress1" id="vAddress1" value="{$rs_sr[0].vAddress1}" />
								<input type="hidden" name="vAddress2" id="vAddress2" value="{$rs_sr[0].vAddress2}" />
								<input type="hidden" name="vStreet" id="vStreet" value="{$rs_sr[0].vStreet}" />
								<input type="hidden" name="vCrossStreet" id="vCrossStreet" value="{$rs_sr[0].vCrossStreet}" />
								<input type="hidden" name="iZipcode" id="iZipcode" value="{$rs_sr[0].iZipcode}" />
								<input type="hidden" name="iStateId" id="iStateId" value="{$rs_sr[0].iStateId}" />
								<input type="hidden" name="iCountyId" id="iCountyId" value="{$rs_sr[0].iCountyId}" />
								<input type="hidden" name="iCityId" id="iCityId" value="{$rs_sr[0].iCityId}" />
								<input type="hidden" name="iZoneId" id="iZoneId" value="{$rs_sr[0].iZoneId}" />
								<input type="hidden" name="vLatitude" id="vLatitude" value="{$rs_sr[0].vLatitude}" />
								<input type="hidden" name="vLongitude" id="vLongitude" value="{$rs_sr[0].vLongitude}" />
								<input type="hidden" name="iCId" id="iCId" value="{$rs_sr[0].iCId}" />
								<input type="hidden" name="iOldStatus" id="iOldStatus" value="{$rs_sr[0].iStatus}" />
								<div class="form-row">
									<div class="col-md-4">
										<div class="form-row">
											<div class="col-md-12 mb-2">
												<input type="button" onclick="addEditData('','add','0','srcontactadd');" class="btn btn-primary" value="Create New Contact">
											</div>
										</div>
										<div class="form-row">
											<div class="col-md-12 mb-3">
												<div class="form-row mb-2">
													<input type="text" class="form-control typeahead autofill" id="search_contact" name="search_contact" placeholder="Search Contact" value="{$rs_sr[0].contact}">
													<img class="clear_address" src="assets/images/icon-delete.png" style="cursor: pointer;" onclick="return clear_serach_contact();">
													<div class="invalid-feedback errmsg_iCId">Please search contact
													</div>
												</div>
											</div>
										</div>
										<div class="form-row mb-2">
											<div class="col-8 mb-8 contact-details">
												<table class="table layout-secondary table-responsive">
													<tbody>
														<tr>
															<th scope="col">First Name:</th>
															<td class="vFirstName">{$rs_sr[0].vFirstName}</td>
														</tr>
														<tr>
															<th scope="col">Last Name:</th>
															<td class="vLastName">{$rs_sr[0].vLastName}</td>
														</tr>
														<tr>
															<th scope="col">Company:</th>
															<td class="vCompany">{$rs_sr[0].vCompany}</td>
														</tr>
														<tr>
															<th scope="col">Email:</th>
															<td class="vEmail">{$rs_sr[0].vEmail}</td>
														</tr>
														<tr>
															<th scope="col">Phone:</th>
															<td class="vPhone">{$rs_sr[0].vPhone}</td>
														</tr>
													</tbody>    
												</table> 
											</div>
											<div id="showContactHistory" class="col-4 mb-4 contact-details">
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-row mb-2">
											<div class="col-12">
												<ul class="list-unstyled">
													<li>
														<div class=" custom-control custom-checkbox custom-control-inline">
															<input type="checkbox" class="custom-control-input" id="bMosquitoService" name="bMosquitoService" value="1"  {if $rs_sr[0].bMosquitoService eq 't'} checked {/if}>
															<label class="custom-control-label" for="bMosquitoService">Mosquito Inspection/Treatment</label>
														</div>
													</li>
													<li>
														<div class="custom-control custom-checkbox custom-control-inline">
															<input type="checkbox" class="custom-control-input" id="bCarcassService" name="bCarcassService" value="1" {if $rs_sr[0].bCarcassService eq 't'} checked {/if}>
															<label class="custom-control-label" for="bCarcassService">Carcass Removal</label>
														</div>
													</li>
												</ul>
												<div class="invalid-feedback errmsg_iSRService">Please select at least one service type
												</div>
											</div>
										</div>
										<div class="form-row mb-2">
											<div class="col-12">
												<label for="iUserId">Assigned Technician <span class="required" aria-required="true">*</span></label>
												<select name="iUserId" id="iUserId" class="select" required {if !($sess_user_iAGroupId|in_array:$Access_Group_SuperAdmin)} disabled {/if}>
													<option value="">--- Select ---</option>
													{section name="u" loop=$rs_user}
													<option value="{$rs_user[u].iUserId}"  {if $rs_sr[0].iUserId eq $rs_user[u].iUserId} selected {/if}>{$rs_user[u].vName|gen_strip_slash}</option>
													{/section}
												</select>
												<div class="invalid-feedback">
													Please choose Premise Type.
												</div>
											</div>
										</div>
										<div class="form-row mb-2">
											<div class="col-12">
												<label for="bInspectPermission">Do we have the permission to inspect, treat or plant fish for mosquito-problem or pick up carcass for dead animal issue?</label>
												<select name="bInspectPermission" id="bInspectPermission" class="select">
													<option value="1" {if $rs_sr[0].bInspectPermission eq 't'} selected {/if}>Yes</option>
													<option value="0" {if $rs_sr[0].bInspectPermission neq 't'} selected {/if}>No</option>
												</select>
											</div>
										</div>
										<div class="form-row mb-2">
											<div class="col-12">
												<label for="bAccessPermission">Do we have the permission to access the property without you present?</label>
												<select name="bAccessPermission" id="bAccessPermission" class="select">
													<option value="1" {if $rs_sr[0].bAccessPermission eq 't'} selected {/if}>Yes</option>
													<option value="0" {if $rs_sr[0].bAccessPermission neq 't'} selected {/if}>No</option>
												</select>
											</div>
										</div>
										<div class="form-row mb-2">
											<div class="col-12">
												<label for="bPets">Do you have pets on the property?</label>
												<select name="bPets" id="bPets" class="select">
													<option value="1" {if $rs_sr[0].bPets eq 't'} selected {/if}>Yes</option>
													<option value="0" {if $rs_sr[0].bPets neq 't'} selected {/if}>No</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-row mb-3">
											<label for="autofilladdress">Address</label>
											<div class="position-relative w-100">
												<input type="text" id="autofilladdress" name="autofilladdress" class="form-control" value="{$rs_sr[0].address}" required="">
												<img class="clear_address" src="assets/images/icon-delete.png" style="cursor:pointer;{if $mode neq 'Update'} display:none{/if}" onclick="return clear_address();">
											</div>
											<div class="invalid-feedback">
												Please Select Address.
											</div>
										</div>
										<div class="form-row mb-2">
											<div class="col-9 address-details">
												<table class="table layout-secondary table-responsive">
													<tbody>
														<tr>
															<th scope="col">vAddress1:</th>
															<td class="vAddress1">{$rs_sr[0].vAddress1}</td>
														</tr>
														<tr>
															<th scope="col">vStreet:</th>
															<td class="vStreet">{$rs_sr[0].vStreet}</td>
														</tr>
														<tr>
															<th scope="col">iZipcode:</th>
															<td class="iZipcode">{$rs_sr[0].iZipcode}</td>
														</tr>
														<tr>
															<th scope="col">iCountyId:</th>
															<td class="iCountyId">{$rs_sr[0].iCountyId}</td>
														</tr>
														<tr>
															<th scope="col">iCityId:</th>
															<td class="iCityId">{$rs_sr[0].iCityId}</td>
														</tr>
														<tr>
															<th scope="col">iZoneId:</th>
															<td class="iZoneId">{$rs_sr[0].iZoneId}</td>
														</tr>
													</tbody>    
												</table> 
											</div>
											<div class="col-3 address-details" id="showNearbySr">
											</div>
										</div>
										<div class="form-row mb-2">
											<div class="col-12">
												<label for="iStatus">Status</label>
												<select name="iStatus" id="iStatus" class="select">
													<option value="1"{if $rs_sr[0].iStatus eq 1} selected {/if}>Draft</option>
													<option value="2" {if $rs_sr[0].iStatus eq 2} selected {/if} >Assigned</option>
													<option value="3" {if $rs_sr[0].iStatus eq 3} selected {/if}>Review</option>
													<option value="4" {if $rs_sr[0].iStatus eq 4} selected {/if}>Complete</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="form-row mb-2">
									<div class="col-md-12">
										<label for="tProblems">Describe the problem</label>
										<textarea id="tProblems" name="tProblems" class="form-control">{$rs_sr[0].tProblems}</textarea>
									</div>
								</div>
								<div class="form-row mb-2">
									<div class="col-md-12">
										<label for="tInternalNotes">Internal Resolution Note</label>
										<textarea id="tInternalNotes" name="tInternalNotes" class="form-control">{$rs_sr[0].tInternalNotes}</textarea>
									</div>
								</div>
								<div class="form-row mb-2">
									<div class="col-md-12">
										<label for="tRequestorNotes">Resolution Notes to be shared with the Requestor</label>
										<textarea id="tRequestorNotes" name="tRequestorNotes" class="form-control">{$rs_sr[0].tRequestorNotes}</textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12 mt-3">
			<div class="card">
				<div class="card-content">
					<div class="card-body">
						<div class="row"> 
							<div class="form-row mt-3">
								<div class="col-12 ml-3 float-right">
									<button type="submit" class="btn btn-primary" id="save_data" value="submit">Save </button>  
									<img src="assets/images/loading-small.gif" id="sr_save_loading" border="0" style="display:none;">   
									<button type="button" onclick="location.href = site_url+'sr/list';" class="btn btn-secondary ml-2"> Close </button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
{include file="scripts/contact/contact_addedit_popup.tpl"}
{include file="scripts/contact/contact_history.tpl"}
{include file="scripts/sr/sr_history.tpl"}


<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>

<!-- START: Page Vendor JS-->
<script src="assets/vendors/typeahead/handlebars-v4.5.3.js"></script>
<script src="assets/vendors/typeahead/typeahead.bundle.js"></script>
<!-- END: Page Vendor JS-->
<script src="https://maps.googleapis.com/maps/api/js?key={$GOOGLE_GEOCODE_API_KEY}&libraries=geometry,drawing,places,visualization"></script>
<script type="text/javascript" src="assets/js/app_js/sr_add.js"></script>
<script type="text/javascript" src="assets/js/app_js/contact_add.js"></script>


<script src="assets/js/app_js/google_autocomplete.js"></script>
<script type="text/javascript">
	var mode = '{$mode}';
	var access_group_var_edit= '{$access_group_var_edit}';
</script>
<script src="assets/js/app_js/contact_history.js"></script>

