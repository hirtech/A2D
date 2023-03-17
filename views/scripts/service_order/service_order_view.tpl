<div class="row   no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}service_order/list">Service Order List</a></li>
                <li class="breadcrumb-item active">{$module_name}</li>
            </ol>
        </div>
    </div>
</div>
<form name="frmadd" id="frmadd" method="post" action="" class="form-horizontal needs-validation"  novalidate  enctype="multipart/form-data">
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header">                               
                <h4 class="card-title">{$module_name} - # {$iServiceOrderId} - {$mode}</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">                                           
                        <div class="col-12">
                            <input type="hidden" name="groupaction" value="groupaction">
                            <input type="hidden" name="iServiceOrderId" id="iServiceOrderId" value="{$rs_sorder[0].iServiceOrderId}">
                            <div class="form-row">
                                <div class="col-6">
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>Carrier :</strong></label>
										<div class="col-sm-8 mt-2">
											{$rs_sorder[0].vCompanyName|gen_filter_text}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>Master MSA # :</strong></label>
										<div class="col-sm-8 mt-2">
											{$rs_sorder[0].vMasterMSA|gen_filter_text}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>Premise :</strong></label>
										<div class="col-sm-8 mt-2">
											{$rs_sorder[0].iPremiseId} ({$rs_sorder[0].vPremiseName}; {$rs_sorder[0].vPremiseType})
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>SalesRep :</strong></label>
										<div class="col-sm-8 mt-2">
											{$rs_sorder[0].vSalesRepName}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>SalesRep Email :</strong></label>
										<div class="col-sm-8 mt-2">
											{$rs_sorder[0].vSalesRepEmail}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>Connection Type :</strong></label>
										<div class="col-sm-8 mt-2">
											{$rs_sorder[0].vConnectionTypeName}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>Service :</strong></label>
										<div class="col-sm-8 mt-2">
											{$rs_sorder[0].vServiceType1}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>NRC Variable :</strong></label>
										<div class="col-sm-8 mt-2">
											{$rs_sorder[0].iNRCVariable}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>MRC Fixed :</strong></label>
										<div class="col-sm-8 mt-2">
											{$rs_sorder[0].iMRCFixed}
										</div>
									</div>
                                </div>
                                <div class="col-6">
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>Service Order :</strong></label>
										<div class="col-sm-8 mt-2">
											{$rs_sorder[0].vServiceOrder}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>Service Order Status :</strong></label>
										<div class="col-sm-8 mt-1">
											{$rs_sorder[0].vSOStatus}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>Connection Status :</strong></label>
										<div class="col-sm-8 mt-1">
											{$rs_sorder[0].vCStatus}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>Service Status :</strong></label>
										<div class="col-sm-8 mt-1">
											{$rs_sorder[0].vSStatus}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>Comments :</strong></label>
										<div class="col-sm-8 mt-1">
											{$rs_sorder[0].tComments|gen_filter_text}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label"><strong>File :</strong></label>
										<div class="col-sm-8 mt-1">
											{if $rs_sorder[0].file_url neq ''}
											<span class="mt-3"><br/>
												<a href="{$rs_sorder[0].file_url}" title="Download"><i class="fa fa-download"></i>&nbsp;&nbsp;Download</a>
											</span>
											{else}
											---
											{/if}
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
                        <button type="button" onclick="location.href = site_url+'service_order/list';" class="btn  btn-secondary  ml-2" > Close </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>