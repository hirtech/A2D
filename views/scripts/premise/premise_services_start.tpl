
<a class="btn btn-primary d-none" id="premise_services_start" data-toggle="modal" href="#exampleModaltooltip">launch model</a>
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
            	<form  id="frmadd" name="frmadd" action="" class="form-horizontal needs-validation" method="post" novalidate>
            		<input type="hidden" name="mode" id="st_mode" value="">
            		<input type="hidden" name="iServiceTypeId" id="iServiceTypeId" value="">
                    <input type="hidden" name="iPremiseId" id="iPremiseId" value="{$iPremiseId}">
            		<div class="form-group row">
            			<label class="col-sm-5 col-form-label" for="iWOId">Work Order <span class="text-danger"> *</span></label>
            			<div class="col-sm-7">
                			<select name="iWOId" id="iWOId" class="form-control" onchange="getServiceOrder(this.value, 'start');" required>
                            <option value="">Select Work Order </option>
                            {section name="c" loop=$rs_wo}
                                <option value="{$rs_wo[c].iWOId}">{$rs_wo[c].vWorkOrder}</option>
                            {/section}
                        </select>
                        <div class="invalid-feedback"> Please select work order</div>
                		</div>
            		</div> 
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label" for="vServiceOrder">Service Order <span class="text-danger"> *</span></label>
                        <div class="col-sm-7">
                            <input type="hidden" name="iServiceOrderId" id="iServiceOrderId" value="">
                            <input type="text" class="form-control readonly-color" name="vServiceOrder" id="vServiceOrder" value="" placeholder="Service Order" required readonly >
                        <div class="invalid-feedback"> Please select service order</div>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label" for="iCarrierId">Carrier </label>
                        <div class="col-sm-7">
                            <input type="hidden" name="iCarrierId" id="iCarrierId" value="">
                            <input type="text" class="form-control readonly-color" name="vCarrierName" id="vCarrierName" value="" placeholder="Carrier" readonly>
                        </div>
                    </div> 
            		<div class="form-group row">
            			<label class="col-sm-5 col-form-label" for="iPremiseCircuitId">Premise Circuit</label>
            			<div class="col-sm-7">
                			<select name="iPremiseCircuitId" id="iPremiseCircuitId" class="form-control">
                                <option value="">Select Premise Circuit</option>
                                {section name="n" loop=$rs_pcircuit}
                                <option value="{$rs_pcircuit[n].iPremiseCircuitId}">{$rs_pcircuit[n].vPremiseDisplay|gen_strip_slash}</option>
                                {/section}
                            </select>
                		</div>
            		</div>
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label" for="dStartDate">Start Date </label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control  readonly-color" name="dStartDate" id="dStartDate" value="{$dToday}" placeholder="Start Date" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label" for="iUserId">Started By </label>
                        <div class="col-sm-7">
                            <input type="hidden" class="form-control" name="iUserId" id="iUserId" value="{$iUserId}" >
                            <input type="text" class="form-control readonly-color" name="vStartedBy" id="vStartedBy" value="{$vUserName}" placeholder="Started By" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label" for="iNRCVariable">NRC Variable</label>
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
                        <label class="col-sm-5 col-form-label" for="iMRCFixed">MRC Fixed</label>
                        <div class="col-sm-7">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-right-0" id="basic-addon11"><i class=" fas fa-dollar-sign"></i></span>
                                </div>
                                <input type="text" class="form-control" id="iMRCFixed" name="iMRCFixed" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div> 
            	</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                 <img src="assets/images/loading-small.gif" id="save_premise_services_start_loading" border="0" style="display:none;">
                <input type="submit" class="btn btn-primary" id="save_premise_services_start_data" value="Save" name="save_premise_services_start_data" >
            </div>
        </div>
    </div>
</div>