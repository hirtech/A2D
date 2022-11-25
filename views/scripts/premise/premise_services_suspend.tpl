
<a class="btn btn-primary d-none" id="premise_services_suspend" data-toggle="modal" href="#exampleModaltooltip1">launch model</a>
<div class="modal fade" id="exampleModaltooltip1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-wmax750" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stmodaltitlesuspend"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="suspendclosestbox">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
            	<form  id="frmsuspendadd" name="frmsuspendadd" action="" class="form-horizontal needs-validation" method="post" novalidate>
            		<input type="hidden" name="mode" id="st_modesuspend" value="">
            		<input type="hidden" name="iSuspendServiceTypeId" id="iSuspendServiceTypeId" value="">
                    <input type="hidden" name="iPremiseId" id="iPremiseId" value="{$iPremiseId}">
            		<div class="form-group row">
            			<label class="col-sm-5 col-form-label" for="iWOId">Work Order <span class="text-danger"> *</span></label>
            			<div class="col-sm-7">
                			<select name="iWOId" id="iWOId" class="form-control" onchange="getServiceOrder(this.value, 'suspend');" required>
                            <option value="">Select Work Order </option>
                            {section name="c" loop=$rs_wo}
                                <option value="{$rs_wo[c].iWOId}">{$rs_wo[c].vWorkOrder}</option>
                            {/section}
                        </select>
                        <div class="invalid-feedback"> Please select work order</div>
                		</div>
            		</div> 
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label" for="iSuspendServiceOrderId">Service Order </label>
                        <div class="col-sm-7">
                            <input type="hidden" name="iSuspendServiceOrderId" id="iSuspendServiceOrderId" value="">
                            <input type="text" class="form-control readonly-color" name="vSuspendServiceOrder" id="vSuspendServiceOrder" value="" placeholder="Enter Service Order" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label" for="iSuspendCarrierId">Carrier </label>
                        <div class="col-sm-7">
                            <input type="hidden" name="iSuspendCarrierId" id="iSuspendCarrierId" value="">
                            <input type="text" class="form-control readonly-color" name="vSuspendCarrierName" id="vSuspendCarrierName" value="" placeholder="Enter Carrier" readonly>
                        </div>
                    </div> 
            		<div class="form-group row">
            			<label class="col-sm-5 col-form-label" for="iSuspendPremiseCircuitId">Premise Circuit</label>
            			<div class="col-sm-7">
                			<select name="iSuspendPremiseCircuitId" id="iSuspendPremiseCircuitId" class="form-control">
                                <option value="">Select Premise Circuit</option>
                                {section name="n" loop=$rs_pcircuit}
                                <option value="{$rs_pcircuit[n].iPremiseCircuitId}">{$rs_pcircuit[n].vPremiseDisplay|gen_strip_slash}</option>
                                {/section}
                            </select>
                		</div>
            		</div>
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label" for="dSuspendDate">Suspend Date </label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control readonly-color" name="dSuspendDate" id="dSuspendDate" value="{$dToday}" placeholder="Enter Suspend Date" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label" for="iSuspendUserId">Started By </label>
                        <div class="col-sm-7">
                            <input type="hidden" class="form-control" name="iSuspendUserId" id="iSuspendUserId" value="{$iUserId}" >
                            <input type="text" class="form-control readonly-color" name="vSuspendStartedBy" id="vSuspendStartedBy" value="{$vUserName}" placeholder="Started By" readonly>
                        </div>
                    </div> 
            	</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                 <img src="assets/images/loading-small.gif" id="save_premise_services_suspend_loading" border="0" style="display:none;">
                <input type="submit" class="btn btn-primary" id="save_premise_services_suspend_data" value="Save" name="save_premise_services_suspend_data" >
            </div>
        </div>
    </div>
</div>