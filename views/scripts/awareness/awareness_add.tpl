<a class="btn btn-primary d-none" id="awareness_box" data-toggle="modal" href="#awarenessModaltooltip">launch model</a>
<div class="modal fade" id="awarenessModaltooltip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-wmax750" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title_awareness"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox_awareness">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
            	<form  id="frmadd_awareness" name="frmadd_awareness" action="" class="form-horizontal needs-validation" method="post" novalidate>
            		<input type="hidden" name="mode" id="mode_title_awareness" value="">
                    <input type="hidden" name="modal_iAId" id="modal_iAId" value="">
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="vSiteName_awareness">Premise<span class="text-danger"> *</span></label>
            			<div class="col-sm-4">
                			<input type="text" name="vSiteName_awareness"  class="form-control " id="vSiteName_awareness" placeholder="Search Premise Id or Premise Name" value="{if $rs_site[0].iSiteId}{if $rs_site[0].vName neq ''}{$rs_site[0].vName|gen_strip_slash} - {/if}PremiseID# {$rs_site[0].iSiteId}{/if}"  required>
                			<input type="hidden" id="serach_iSiteId_awareness" name="serach_iSiteId_awareness" value="{$rs_site[0].iSiteId}"/>
                			<div class="invalid-feedback" id="errmsg_search_site">Please enter premise</div>
                		</div>
                        <label class="col-sm-2 col-form-label" for="vSR_awareness">Fiber Inquiry</label>
                        <div class="col-sm-4">
                            <input type="text" name="vSR_awareness"  class="form-control " id="vSR_awareness" placeholder="Search Fiber Inquiry Id" value=""  >
                            <input type="hidden" id="serach_iSRId_awareness" name="serach_iSRId_awareness" value=""/>
                        </div>
            		</div>
            		<div class="form-group row">
            			 <label  class="col-sm-2 col-form-label" for="modal_dDate_awareness">Date<span class="text-danger"> *</span></label>
            			<div class="col-sm-6">
                            <input type="date" class="form-control" id="modal_dDate_awareness" name="modal_dDate_awareness" value="" required> 
                			<div class="invalid-feedback">Please select date</div>
                		</div>
            		</div>
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="">Start time<span class="text-danger"> *</span></label>
            			<div class="col-sm-4">
            				<input type="time" class="form-control" id="dStartTime_awareness" name="dStartTime_awareness" required> 
                            <div class="invalid-feedback">Please enter start time.</div>
                		</div>
            			<label  class="col-sm-2 col-form-label" for="">End time<span class="text-danger"> *</span></label>
            			 <div class="col-sm-4">
                            <input type="time" class="form-control" id="dEndTime_awareness" name="dEndTime_awareness" required> 
                            <div class="invalid-feedback" >Please enter end time.</div>
                            <div class="invalid-feedback" id="errmsg_dEndTime_awareness"></div>
                		</div>
            		</div> 
                    <div class="form-group row">
        				<label  class="col-sm-2 col-form-label" for="">Type</label>
        				<div class="col-sm-4">
        					<select name="iEngagementId" id="iEngagementId" class="form-control select">
								<option value="">Select Type</option>
                                {section name="e" loop=$rs_engagement}
                                <option value="{$rs_engagement[e].iEngagementId}">{$rs_engagement[e].vEngagement|gen_strip_slash}</option>
                                {/section}
        					</select>
        				</div>
                        <label class="col-sm-2 col-form-label" for="otechnician_id">Technician Name</label>
                        <div class="col-sm-4">
                            <select name="technician_id" id="otechnician_id" class="form-control select">
                                <option value="">Select Technician</option>
                                {section name="s" loop=$technician_user_arr}
                                <option value="{$technician_user_arr[s].iUserId}">{$technician_user_arr[s].vFirstName|gen_strip_slash} {$technician_user_arr[s].vLastName|gen_strip_slash}</option>
                                {/section}
                            </select>
                        </div>
            		</div>
            		<div class="form-group row">
            			<label  class="col-sm-2 col-form-label" for="">Notes</label>
            			 <div class="col-sm-10">
            			 	<textarea class="form-control" name="tNotes_awareness" id="tNotes_awareness"></textarea>
            			 </div>
            		</div>
            	</form>
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                 <img src="assets/images/loading-small.gif" id="save_loading_awareness" border="0" style="display:none;"> 
                <input type="submit" class="btn btn-primary" id="save_data_awareness" value="Save" name="save_data">
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>
<script type="text/javascript">
{literal}
$(document).ready(function() {
    $('.select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
          //width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
    });
});
{/literal}
</script>