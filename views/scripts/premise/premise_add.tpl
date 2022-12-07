<div class="row  no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$mode} {$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}premise/list">Premise List</a></li>
                <li class="breadcrumb-item active">{$module_name}</li>
            </ol>
        </div>
    </div>
</div>
<form name="frmadd" id="frmadd" method="post" class="form-horizontal needs-validation" novalidate enctype="multipart/form-data">
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header">                               
                <nav>
					<ul class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
					    <li><a id="general" class="nav-item nav-link active" data-toggle="tab" href="#tab1">General</a></li>
					    <li><a id="confirm" class="nav-item nav-link " data-toggle="tab" href="#tab2">Confirm</a></li>
					    {if $mode == "Update"}
					    <li><a id="contact" class="nav-item nav-link " data-toggle="tab" href="#tab3">Contact</a></li>
					    <li><a id="document" class="nav-item nav-link " data-toggle="tab" href="#tab4">Document</a></li>
					    {/if}
					</ul>
				</nav>                            
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">                                           
                        <div class="col-12">
                    		<input type="hidden" name="groupAction" value="groupAction">
                            <input type="hidden" name="mode" id="mode" value="{$mode}">
							<input type="hidden" name="iPremiseId" id="iPremiseId" value="{$rs_site[0].iPremiseId}" />
							<input type="hidden" name="iGeometryType" id="iGeometryType" value="1" />
							<input type="hidden" name="vAddress1" id="vAddress1" value="{$rs_site[0].vAddress1}" />
							<input type="hidden" name="vAddress2" id="vAddress2" value="{$rs_site[0].vAddress2}" />
							<input type="hidden" name="vStreet" id="vStreet" value="{$rs_site[0].vStreet}" />
							<input type="hidden" name="vCrossStreet" id="vCrossStreet" value="{$rs_site[0].vCrossStreet}" />
							<input type="hidden" name="iZipcode" id="iZipcode" value="{$rs_site[0].iZipcode}" />
							<input type="hidden" name="iStateId" id="iStateId" value="{$rs_site[0].iStateId}" />
							<input type="hidden" name="iCountyId" id="iCountyId" value="{$rs_site[0].iCountyId}" />
							<input type="hidden" name="iCityId" id="iCityId" value="{$rs_site[0].iCityId}" />
							<input type="hidden" name="iZoneId" id="iZoneId" value="{$rs_site[0].iZoneId}" />
							<input type="hidden" name="vLatitude" id="vLatitude" value="{$rs_site[0].vLatitude}" />
							<input type="hidden" name="vLongitude" id="vLongitude" value="{$rs_site[0].vLongitude}" />
							<input type="hidden" name="vNewLatitude" id="vNewLatitude" value="{$rs_site[0].vNewLatitude}" />
							<input type="hidden" name="vNewLongitude" id="vNewLongitude" value="{$rs_site[0].vNewLongitude}" />
							<input type="hidden" name="vPolygonLatLong" id="vPolygonLatLong" value="{$rs_site[0].vPolygonLatLong}" style="width:700px" />
							<input type="hidden" name="vPolyLineLatLong" id="vPolyLineLatLong" value="{$rs_site[0].vPolyLineLatLong}" style="width:700px" />
							<div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
							    <div id="tab1" class="tab-pane fade show active">
							       <div class="form-row">
							       		<div class="col-md-6 mb-3">
							       			<div class="form-row mb-2">
							       				<div class="col-12">
								       				<label for="vName">Premise Name <span class="text-danger">*</span></label>
					                            	<input type="text" class="form-control" id="vName" name="vName" placeholder="Premise name" value="{$rs_site[0].vName}" required>
						                           	<div class="invalid-feedback">
						                                Please enter premise name
						                            </div>
					                        	</div>
							       			</div>
							       			<div class="form-row mb-2">
							       				<div class="col-12">
								       				<label for="iSTypeId">Premise Type <span class="text-danger">*</span></label>
					                                <select name="iSTypeId" id="iSTypeId" class="select" required onchange="getSiteSubType(this.value);" >
														<option value="">--- Select ---</option>
														{section name="a" loop=$rs_sitetype}
														<option value="{$rs_sitetype[a].iSTypeId}" {if $rs_sitetype[a].iSTypeId eq $rs_site[0].iSTypeId} selected {/if}>{$rs_sitetype[a].vTypeName|gen_strip_slash}</option>
														{/section}
													</select>
					                                <div class="invalid-feedback">
					                                    Please choose premise type.
					                                </div>
				                            	</div>
				                            </div>
							       			<div class="form-row mb-2">
								       			<div class="col-12">
								       				<label for="iSSTypeId">Premise Sub Type</label>
					                                <select name="iSSTypeId" id="iSSTypeId" class="select" >
														<option value="">--- Select ---</option>
													</select>	
								       			</div>
							       			</div>
							       			<div class="form-row mb-2">
								       			<div class="col-12">
								       				<label for="iSAttributeId">Premise Attribute</label>
					                                <select name="iSAttributeId[]" id="iSAttributeId" class="select"  multiple>
														<option value="">--- Select ---</option>
														{section name="a" loop=$rs_siteattr}
														<option value="{$rs_siteattr[a].iSAttributeId}"  {if $rs_siteattr[a].iSAttributeId|in_array:$iSAttributeIdArr}selected{/if}>{$rs_siteattr[a].vAttribute|gen_strip_slash}</option>
														{/section}
													</select>
								       			</div>
							       			</div>
							       		</div>
							       		<div class="col-md-6 mb-3">
							       			<div class="form-row mb-2">
							       				<div class="col-12">
							       				 	<label for="autofilladdress">Address</label>
							       				 	  <div class="position-relative w-100 autofill-row">
							       				 		<input type="text" id="autofilladdress" name="autofilladdress" class="form-control autofill" value="{$rs_site[0].address}">
							       				 	
							       				 		<img class="clear_address" src="assets/images/icon-delete.png" style="cursor:pointer;{if $mode neq 'Update'} display:none{/if}" onclick="return clear_address();">
							       				 	
													 </div> 
												</div>
							       			</div>
							       			<div class="form-row mb-2">
							       				<div class="col-12">
							       				 	<label for="iStatus">Status</label>
					                                <select name="iStatus" id="iStatus" class="select">
														<option value="1"  {if $rs_site[0].iStatus eq '1'} selected {/if} {if $mode eq 'Add'} selected{/if}>Active</option>
														<option value="0" {if $rs_site[0].iStatus eq '0'} selected {/if}>Inactive</option>
														{if $rs_site[0].iStatus eq '2'}
														<option value="2" selected>Deleted</option>
														{/if}
													</select>
												</div>
							       			</div>
							       		</div>
							       	</div>
							    </div>
							    <div id="tab2" class="tab-pane fade">
							     	<div class="form-row">
							     		<div class="col-md-6 mb-6">
				                            <div id="PointMap" class="w-100" style="height:350px"></div>
				                            <div id="PolygonMap" class="w-100" style="height:350px"></div> 
				                            <div id="PolylineMap" class="w-100" style="height:350px"></div> 
				                        </div>
							     		<div class="col-md-6 mb-6 address-details">
					                        <div class="card">
					                            <div class="card-header  justify-content-between align-items-center">                               
					                                <h4 class="card-title">Address details</h4> 
					                            </div>
					                            <div class="card-body">
					                                <dl class="row mb-0 redial-line-height-2_5">
					                                    <dt class="col-sm-5">vAddress1:</dt>
					                                    <dd class="col-sm-7 vAddress1">{$rs_site[0].vAddress1}</dd>

					                                    <dt class="col-sm-5">vStreet:</dt>
					                                    <dd class="col-sm-7 vStreet">{$rs_site[0].vStreet}</dd>

					                                    <dt class="col-sm-5">iZipcode:</dt>
					                                    <dd class="col-sm-7 iZipcode">{$rs_site[0].iZipcode}</dd>

					                                    <dt class="col-sm-5">iCountyId:</dt>
					                                    <dd class="col-sm-7 iCountyId">{$rs_site[0].iCountyId}</dd>

					                                    <dt class="col-sm-5">iStateId:</dt>
					                                    <dd class="col-sm-7 iStateId">{$rs_site[0].iStateId}</dd>

					                                    <dt class="col-sm-5">iCityId:</dt>
					                                    <dd class="col-sm-7 iCityId">{$rs_site[0].iCityId}</dd>

					                                    <dt class="col-sm-5">iZoneId:</dt>
					                                    <dd class="col-sm-7 iZoneId">{$rs_site[0].iZoneId}</dd>

					                                    <dt class="col-sm-5">vLatitude:</dt>
					                                    <dd class="col-sm-7 vLatitude">{$rs_site[0].vLatitude}</dd>

					                                    <dt class="col-sm-5">vLongitude:</dt>
					                                    <dd class="col-sm-7 vLongitude">{$rs_site[0].vLongitude}</dd>

					                                    <dt class="col-sm-5 {if $rs_site[0].vNewLatitude == ''} d-none {/if} edit_address">vNewLatitude:</dt>
					                                    <dd class="col-sm-7 {if $rs_site[0].vNewLatitude == ''} d-none {/if}  edit_address vNewLatitude">{$rs_site[0].vNewLatitude}</dd>

					                                    <dt class="col-sm-5 {if $rs_site[0].vNewLongitude == ''} d-none {/if}  edit_address">vNewLongitude:</dt>
					                                    <dd class="col-sm-7 {if $rs_site[0].vNewLongitude == ''} d-none {/if} edit_address vNewLongitude">{$rs_site[0].vNewLongitude}</dd>

					                                    <dt class="col-sm-5 {if $rs_site[0].iGeometryType neq 2} d-none {/if}  edit_address polyarea">Area:</dt>
					                                    <dd class="col-sm-7 {if $rs_site[0].iGeometryType neq 2} d-none {/if} edit_address polyarea" id="polyarea"></dd>
					                                </dl>
					                            </div>
					                        </div>
					                    </div>
							     	</div>
							    </div>
							    <div id="tab3" class="tab-pane fade">
							    	<div class="form-row">
							    		<div class="col-md-2 mb-2">
											<input type="button" onclick="addEditData('','add',{$rs_site[0].iPremiseId},'sitecontactadd');" class="btn btn-info btn-block btn-sm" value="Create New Contact">
							    		</div>
							    	</div>
							    	<div class="form-row">
							       		<div class="col-md-6 mb-3">
							       			<div class="form-row mb-2">
							       				<div class="col-12">
					                            	<input type="text" class="form-control typeahead" id="search_contact" name="search_contact" placeholder="Search Contact" value="" >
					                            	&nbsp;&nbsp;
													<img class="clear_address" src="assets/images/icon-delete.png" style="cursor:pointer;" onclick="return clear_serach_contact();">
					                        	</div>
							       			</div>
							       		</div>
							       		<div class="col-md-6 mb-3">
							       			<div class="table-responsive">
								       			<table cellspacing="0" cellpadding="0" width="100%"  class="table layout-secondary bordered" width="100%">
													<thead>	
														<tr>
															<th width="50%">Name</th>
															<th width="30%">Number</th>
															<th style="text-align:center;" width="20%" >Action</th>
														</tr>
													</thead>
													<tbody id="tbl_contact">
														{if $rs_site_contact|@count gt 0}
														{section name="c" loop=$rs_site_contact}
														<tr>
															<td id="cont_name_{$rs_site_contact[c].iCId}">{$rs_site_contact[c].vName}</td>
															<td id="cont_phone_{$rs_site_contact[c].iCId}">{$rs_site_contact[c].vPhone}</td>
															<td class="action"><input type="hidden" name="iCId[]" value="{$rs_site_contact[c].iCId}">
																&nbsp;<a class="btn btn-outline-secondary" href="javascript:void(0);"  onclick="editContact({$rs_site_contact[c].iCId})"  title="Edit Contact" ><i class="fa fa-edit"></i></a>
																&nbsp;
																<a class="btn btn-outline-danger" title="Remove" href="javascript:void(0);" onclick="remove_contact_row(this);"><i class="fa fa-trash"></i></a>
																
															</td>
														</tr>
														{/section}
														{/if}
													</tbody>
												</table>
											</div>
							       		</div>
							       	</div>
							    </div>
							    <div id="tab4" class="tab-pane fade">
							    	<div class="form-row">
							       		<div class="col-md-6 mb-3">
							       			<div class="form-group row">
		                            			<label class="col-sm-4 col-form-label" for="documentTitle">Title</label>
		                            			<div class="col-sm-8">
			                            			<input type="text" name="documentTitle" id="documentTitle" placeholder="Document Title" class="form-control" value=""  >
			                            		</div>
                            				</div>
                            				<div class="form-group row">
		                            			<label class="col-sm-4 col-form-label" for="documentTitle">File</label>
		                            			<div class="col-sm-8">
			                            			<input type="file" class="d-inline-flex form-control-file" id="vDocumentFile" name="vDocumentFile" >
			                            		</div>
                            				</div> 
                            				<div class="form-group row">
                            					<div class="col-12  float-right">
		                            				<input type="button" id="btn_site_document" class="btn btn-info" value="Upload" >
		                            				<img src="assets/images/loading.gif" id="document_loading" border="0" style="display:none;"> 
		                            			</div>  
                            				</div> 
							       		</div>
							       	</div>
							       	<div class="form-row">
							       		<div class="col-md-12 mb-3">
							       			<div class="table-responsive">
								       			<table cellspacing="0" cellpadding="0" width="100%"  class="table layout-secondary bordered" width="100%">
													<thead>	
														<tr>
															<th width="30%">Tilte</th>
															<th width="15%" class="text-center" align="center">File</th>
															<th width="15%" class="text-center" align="center">Date</th>
															<th width="20%">Name</th>
															<th align="center" class="text-center" width="10%" >Action</th>
														</tr>
													</thead>
													<tbody id="tbl_document">
														{if $rs_site_doc|@count gt 0}
															{section name="d" loop=$rs_site_doc}
															<tr>
																<td>{$rs_site_doc[d].vTitle|stripslashes}<input type="hidden" name="file_exif_gps" id="file_exif_gps_{$rs_site_doc[d].iSDId}" value="{$rs_site_doc[d].file_exif_gps}"></td>
																<td align="center" class="text-center" ><a href="{$rs_site_doc[d].file_url}" title="Download">Download</a></td>
																<td align="center" class="text-center" >{$rs_site_doc[d].dAddedDate}</td>
																<td>{$rs_site_doc[d].vLoginUserName}</td>
																<td class="action text-center" align="center"><a class="btn btn-outline-danger" title="Delete" href="javascript:void(0);" onclick="delete_site_document(this,  '{$rs_site_doc[d].iSDId}', '{$rs_site_doc[d].iPremiseId}');" ><i class="fa fa-trash"></i></a>
																</td>
															</tr>
															{/section}
														{/if}
													</tbody>
												</table>
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
                            	<img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;">   
                                <button type="button" onclick="location.href = site_url+'premise/list';" class="btn btn-secondary ml-2"> Close </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

{* include file="general/display_full_screen_loader.tpl" *}

{include file="scripts/contact/contact_addedit_popup.tpl"}

<script type="text/javascript">
	var access_group_var_edit= '{$access_group_var_edit}';
	var tabid ='{$tabid}';
</script>
<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>

<!-- START: Page Vendor JS-->
<script src="assets/vendors/typeahead/handlebars-v4.5.3.js"></script>
<script src="assets/vendors/typeahead/typeahead.bundle.js"></script>
<!-- END: Page Vendor JS-->

<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={$GOOGLE_GEOCODE_API_KEY}&libraries=geometry,drawing,places,visualization"></script>
<script type="text/javascript">
var mode = '{$mode}';
var iSSTypeId = '{$rs_site[0].iSSTypeId}';
var tmplat = '' ;
var tmplng = '' ;
{if $rs_site[0].vLatitude neq '' && $rs_site[0].vLongitude neq ''}
	tmplat = '{$rs_site[0].vLatitude}';
	tmplng = '{$rs_site[0].vLongitude}';
{/if}

{literal}
$(document).ready(function() {
    $('select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
          width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
    });

    if(mode == 'Add'){
    	$(".address-details").hide();
    }else if (mode == 'Update'){
    	//google.maps.event.trigger(autocomplete, 'place_changed');
    	google.maps.event.addDomListener(window, 'load', initialize);
    }
});
{/literal}
</script>

<script type="text/javascript" src="assets/js/app_js/premise_add.js"></script>

<script src="assets/js/app_js/contact_add.js"></script>

<script src="assets/js/app_js/premise_google_autocomplete.js"></script>


