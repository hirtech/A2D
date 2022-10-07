<div class="row  no-gutters w-100 ">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}access_group/access_group_list">{$module_name} List</a></li>
                <li class="breadcrumb-item active">{$module_name}</li>
            </ol>
        </div>
    </div>
</div>
<form name="frmadd" id="frmadd" method="post" action="" class="form-horizontal needs-validation"  novalidate>
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header">                               
                <h4 class="card-title">{$module_title} - {$vAccessGroup}</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">                                           
                        <div class="col-12">
							<input type="hidden" name="mode" id="mode" value="{$mode}">
							<input type="hidden" name="iAGroupId" id="iAGroupId" value="{$iAGroupId}">	
                            <div class="form-row">
                                {if $iDefault eq 0}
                                    {section name="i" loop=$rs_access}
                                     <span class="mr-2"><input type="checkbox" class="list_check" id="iAGroupId_check_{$rs_access[i].iAGroupId}" name="iAGroupId_check" value="{$rs_access[i].iAGroupId}"  onclick="getAccessRights();"  {if $rs_access[i].iAGroupId|in_array:$iDefault_arr}checked{/if}/><label for="iAGroupId_check_{$rs_access[i].iAGroupId}"> {$rs_access[i].vAccessGroup}  </label></span>
                                    {/section}
                                {/if}
                            </div>
                            <div class="form-row table-responsive" >
                            	<table  class="display table dataTable table-striped table-bordered editable-table  table-hover sticky-header-table" width="100%" >
									<thead>
										<tr>
											<th width="1%" class="text-center">#</th>
											<th  width="49%">Access Module</th>
											<th  width="5%" class="text-center"></th>
											<th  width="5%" class="text-center">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                <input type="Checkbox" class="custom-control-input"  id="main_list" value="1"  onclick="checkSubChck('list');"><label class="custom-control-label" for="main_list">List</label>
                                                </div>
                                            </th>
											<th  width="5%" class="text-center">
                                                 <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="Checkbox" class="custom-control-input"  id="main_add" value="1"  onclick="checkSubChck('add');"><label class="custom-control-label" for="main_add">Add</label>
                                                </div>
                                            </th>
											<th  width="5%" class="text-center">
                                                 <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="Checkbox" class="custom-control-input"  id="main_edit" value="1"  onclick="checkSubChck('edit');"><label class="custom-control-label" for="main_edit">Edit</label>
                                                </div>
                                            </th> 
											<th  width="5%" class="text-center">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="Checkbox" class="custom-control-input"  id="main_delete" value="1"  onclick="checkSubChck('delete');"><label class="custom-control-label" for="main_delete">Delete</label>
                                                </div>
                                            </th> 
											<th  width="5%" class="text-center">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="Checkbox" class="custom-control-input"  id="main_status" value="1"  onclick="checkSubChck('status');"><label class="custom-control-label" for="main_status">Status</label>
                                                </div>
                                            </th> 
											{* <th  width="5%" class="text-center">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="Checkbox" class="custom-control-input"  id="main_respond" value="1"  onclick="checkSubChck('respond');"><label class="custom-control-label" for="main_respond">Respond</label>
                                                </div>
                                            </th> *}
											<th  width="5%" class="text-center">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="Checkbox"  class="custom-control-input"  id="main_csv" value="1"  onclick="checkSubChck('csv');"><label class="custom-control-label" for="main_csv">CSV</label>
                                                </div>
                                             </th> 
											{* <th  width="5%" class="text-center">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="Checkbox"  class="custom-control-input"  id="main_pdf" value="1"  onclick="checkSubChck('pdf');"><label class="custom-control-label" for="main_pdf">PDF</label>
                                                </div>
                                            </th> *}
											{* <th  width="5%" class="text-center">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="Checkbox" class="custom-control-input"  id="main_calsurv" value="1"  onclick="checkSubChck('calsurv');"><label class="custom-control-label" for="main_calsurv">Calsurv</label>
                                                </div>
                                            </th> *}
										</tr>
									</thead>
									<tbody>  
									 {section name="a" loop=$rs_access_group} 
											<tr>
												<td class="text-center">{$rs_access_group[a].id}</td>
												<td>{$rs_access_group[a].vAccessModule}</td>
												<td class="text-center">{$rs_access_group[a].chck}</td>
												<td class="text-center">{$rs_access_group[a].listchck}</td>
												<td class="text-center">{$rs_access_group[a].addchck}</td>
												<td class="text-center">{$rs_access_group[a].editchck}</td>
												<td class="text-center">{$rs_access_group[a].deletechck}</td>
												<td class="text-center">{$rs_access_group[a].statuschck}</td>
												{* <td class="text-center">{$rs_access_group[a].respondchck}</td> *}
												<td class="text-center">{$rs_access_group[a].csvchck}</td>
												{* <td class="text-center">{$rs_access_group[a].pdfchck}</td> *}
												{* <td class="text-center">{$rs_access_group[a].calsurvchck}</td> *}
											</tr>
										{/section}                   
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
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <!-- <div class="w-sm-100 mr-auto"></div> -->
                        <button type="submit" class="btn btn-primary ml-2 " id="save_data"> Save </button> 
                            <img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;">  
                        <button type="button" onclick="location.href = site_url+'access_group/access_group_list';" class="btn  btn-secondary  ml-2" > Close </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 </form>
<!-- START: Page JS-->
<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>

<script type="text/javascript" src="assets/js/app_js/access_group_add.js"></script>
