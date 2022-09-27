<a class="btn btn-primary d-none" id="treatment_box" data-toggle="modal" href="#treatmentModaltooltip">launch model</a>
<div class="modal fade" id="treatmentModaltooltip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-wmax750" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title_treatment"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox_treatment">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
            	<form  id="frmadd_treatment" name="frmadd_treatment" action="" class="form-horizontal needs-validation" method="post" novalidate>
            		<input type="hidden" name="mode" id="mode_title_treatment" value="">
                    <input type="hidden" name="modal_iTreatmentId" id="modal_iTreatmentId" value="">
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="modal_vSiteName_treatment">Premise  <span class="text-danger"> *</span></label>
            			<div class="col-sm-4">
                			<input type="text" name="vSiteName_treatment"  class="form-control " id="modal_vSiteName_treatment" placeholder="Search Premise Id or Premise Name" value="{if $rs_site[0].iSiteId}{if $rs_site[0].vName neq ''}{$rs_site[0].vName|gen_strip_slash} - {/if}PremiseID# {$rs_site[0].iSiteId}{/if}"  required>
                			<input type="hidden" id="serach_iSiteId_treatment" name="serach_iSiteId_treatment" value="{$rs_site[0].iSiteId}"/>
                            <div class="invalid-feedback" id="errmsg_search_site">Please enter premise</div>
                		</div>
                        <label class="col-sm-2 col-form-label" for="vSR_other">SR</label>
                        <div class="col-sm-4">
                            <input type="text" name="vSR_treatment"  class="form-control " id="vSR_treatment" placeholder="Search SR Id" value=""  >
                            <input type="hidden" id="serach_iSRId_treatment" name="serach_iSRId_treatment" value=""/>
                        </div>
            		</div>
            		<div class="form-group row">
            			<label  class="col-sm-2 col-form-label" for="modal_dDate_treatment">Date <span class="text-danger"> *</span></label>
            			<div class="col-sm-4">
                            <input type="date" class="form-control" id="modal_dDate_treatment" name="dDate_treatment" value="" required> 
                			<div class="invalid-feedback">Please select date</div>
                		</div>
                        <label  class="col-sm-2 col-form-label" for="modal_vType_treatment">Type <span class="text-danger"> *</span></label>
                        <div class="col-sm-4"> 
                            <select name="vType_treatment" id="modal_vType_treatment" class="form-control col-12 select" required>
                                <option value="">-- Select --</option>
                                <option value="Spot Treatment">Spot Treatment</option>
                                <option value="Aerial Larviciding">Aerial Larviciding</option>
                                <option value="Aerial Adulticiding">Aerial Adulticiding</option>
                                <option value="Ground Larviciding">Ground Larviciding</option>
                                <option value="Ground Adulticiding">Ground Adulticiding</option>
                                <option value="ATV/UTV Mounted ULV">ATV/UTV Mounted ULV</option>
                                <option value="Backpack ULV">Backpack ULV</option>
                                <option value="Handheld ULV">Handheld ULV</option>
                                <option value="Hand Application">Hand Application</option>
                                <option value="Handheld Thermal">Handheld Thermal</option>
                                <option value="Truck Mounted ULV">Truck Mounted ULV</option>
                            </select>
                            <div class="invalid-feedback">Please select type</div>
                        </div>
            		</div>
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="dStartTime_treatment">Start time<span class="text-danger"> *</span></label>
            			<div class="col-sm-4">
            				<input type="time" class="form-control" id="dStartTime_treatment" name="dStartTime_treatment" required> 
                            <div class="invalid-feedback">Please enter start time.</div>
                		</div>
            			<label  class="col-sm-2 col-form-label" for="dEndTime_treatment">End time<span class="text-danger"> *</span></label>
            			 <div class="col-sm-4">
                            <input type="time" class="form-control" id="dEndTime_treatment" name="dEndTime_treatment"  required>
                            <div class="invalid-feedback" >Please enter end time.</div>
                            <div class="invalid-feedback" id="errmsg_dEndTime_treatment"></div> 
                		</div>
            		</div> 
                    <div class="form-group row">
        				<label  class="col-sm-2 col-form-label" for="modal_vTrProduct_treatment">Treatment Product <span class="text-danger"> *</span></label>
        				<div class="col-sm-4">
                            <input type="text" name="vTrProduct_treatment"  class="form-control " id="modal_vTrProduct_treatment" placeholder="Search Treatment Product" value=""  required>
                            <input type="hidden" id="serach_iTPId_treatment" name="serach_iTPId_treatment" value="">
                            <div class="invalid-feedback" id="errmsg_vTrProduct_treatment">Please enter treatment product</div>
                        </div>
                        <label  class="col-sm-2 col-form-label" for="modal_vAppRate_treatment">Application Rate</label>
                        <div class="col-sm-4">
                            <input type="text" name="vAppRate_treatment"  class="form-control readonly-color" id="modal_vAppRate_treatment" placeholder="" value=""  >
                        </div>
            		</div>
            		<div class="form-group row">
            			<label  class="col-sm-2 col-form-label" for="modal_vArea_treatment">Area Treated</label>
            			 <div class="col-sm-10">
            			 	  <div class="form-check-inline col-sm-5">
                                   <input type="text" name="vArea_treatment"  class="form-control " id="modal_vArea_treatment" value="" placeholder="Area Treated">

                                </div>
                                <div class="form-check-inline col-sm-6">
                                     <select name="vAreaTreated_treatment" id="modal_vAreaTreated_treatment"  class="form-control col-12 select">
                                        <option value="acre">acre</option>
                                        <option value="sqft">sqft</option>
                                    </select>
                                </div>
            			 </div>
                    </div>
                    <div class="form-group row">
                         <label  class="col-sm-2 col-form-label" for="modal_vAmountApplied_treatment">Amount Applied <span class="text-danger"> *</span></label>
                         <div class="col-sm-10">
                              <div class="form-check-inline col-sm-5">
                                   <input type="text" name="vAmountApplied_treatment"  class="form-control " id="modal_vAmountApplied_treatment" value="" placeholder="Amount Applied" required>
                                    
                                </div>
                                <div class="form-check-inline col-sm-6">
                                    <select name="iUId_treatment" id="modal_iUId_treatment"  class="form-control col-12 select" required>
                                        <option value="">-- Select --</option>
                                    </select>
                                </div>
                                <div class="invalid-feedback" id="errmsg_amountapplied"></div>
                         </div>
            		</div>
                    <div class="form-group row">
                         <label class="col-sm-2 col-form-label" for="trtechnician_id">Technician Name</label>
                        <div class="col-sm-4">
                            <select name="technician_id" id="trtechnician_id" class="form-control select">
                                <option value="">Select Technician</option>
                                {section name="s" loop=$technician_user_arr}
                                <option value="{$technician_user_arr[s].iUserId}">{$technician_user_arr[s].vFirstName|gen_strip_slash} {$technician_user_arr[s].vLastName|gen_strip_slash}</option>
                                {/section}
                            </select>
                        </div>
                    </div>
            	</form>
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal_treatment">Close</button>
                 <img src="assets/images/loading-small.gif" id="save_loading_treatment" border="0" style="display:none;"> 
                <input type="submit" class="btn btn-primary" id="save_data_treatment" value="Save" name="save_data">
            </div>
        </div>
    </div>
</div>

<!-- START: Page Vendor JS-->
<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>
<script src="assets/vendors/typeahead/handlebars-v4.5.3.js"></script>
<script src="assets/vendors/typeahead/typeahead.bundle.js"></script>
<!-- END: Page Vendor JS-->
<script type="text/javascript">
{literal}
$(document).ready(function() {
    $('.select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
         // width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
    });
});
{/literal}
</script>