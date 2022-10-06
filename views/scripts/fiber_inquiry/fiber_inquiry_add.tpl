<div class="row   no-gutters w-100">
	<div class="col-12  align-self-center">
		<div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
			<div class="w-sm-100 mr-auto"><h4 class="mb-0">{$mode} {$module_name}</h4></div>
			<ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
				<li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
				<li class="breadcrumb-item"><a href="{$site_url}fiber_inquiry/list">Fiber Inquiry List</a></li>
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
								<input type="hidden" name="iFiberInquiryId" id="iFiberInquiryId" value="{$rs_sr[0].iFiberInquiryId}" />
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
												<label for="iPremiseSubTypeId">Premise Sub Type </label>
												<select name="iPremiseSubTypeId" id="iPremiseSubTypeId" class="select">
												<option value="">--- Select ---</option>
													{section name="p" loop=$rs_premise_sub_type}
													<option value="{$rs_premise_sub_type[p].iSSTypeId}" {if $rs_premise_sub_type[p].iSSTypeId eq $rs_sr[0].iPremiseSubTypeId} selected {/if}>{$rs_premise_sub_type[p].vSubTypeName|gen_strip_slash}</option>
													{/section}
											</select>
											</div>
										</div>
										<div class="form-row mb-2">
											<div class="col-12">
												<label for="iEngagementId">Engagement </label>
												<select name="iEngagementId" id="iEngagementId" class="select">
												<option value="">--- Select ---</option>
													{section name="a" loop=$rs_engagement}
													<option value="{$rs_engagement[a].iEngagementId}" {if $rs_engagement[a].iEngagementId eq $rs_sr[0].iEngagementId} selected {/if}>{$rs_engagement[a].vEngagement|gen_strip_slash}</option>
													{/section}
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
											<div class="col-8 address-details">
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
											<div class="col-4 address-details" id="showNearbySr">
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
									<button type="button" onclick="location.href = site_url+'fiber_inquiry/list';" class="btn btn-secondary ml-2"> Close </button>
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
{include file="scripts/fiber_inquiry/fiber_inquiry_history.tpl"}


<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>

<!-- START: Page Vendor JS-->
<script src="assets/vendors/typeahead/handlebars-v4.5.3.js"></script>
<script src="assets/vendors/typeahead/typeahead.bundle.js"></script>
<!-- END: Page Vendor JS-->
<script src="https://maps.googleapis.com/maps/api/js?key={$GOOGLE_GEOCODE_API_KEY}&libraries=geometry,drawing,places,visualization"></script>
<script type="text/javascript" src="assets/js/app_js/fiber_inquiry_add.js"></script>
<script type="text/javascript" src="assets/js/app_js/contact_add.js"></script>


<script src="assets/js/app_js/google_autocomplete.js"></script>
<script type="text/javascript">
	var mode = '{$mode}';
	var access_group_var_edit= '{$access_group_var_edit}';
</script>
<script src="assets/js/app_js/contact_history.js"></script>
