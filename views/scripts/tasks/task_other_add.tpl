<a class="btn btn-primary d-none" id="other_box" data-toggle="modal" href="#otherModaltooltip">launch model</a>
<div class="modal fade" id="otherModaltooltip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-wmax750" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title_other"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox_other">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
            	<form  id="frmadd_other" name="frmadd_other" action="" class="form-horizontal needs-validation" method="post" novalidate>
            		<input type="hidden" name="mode" id="mode_title_other" value="">
                    <input type="hidden" name="modal_iTOId" id="modal_iTOId" value="">
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="vSiteName_other">Premise<span class="text-danger"> *</span></label>
            			<div class="col-sm-4">
                			<input type="text" name="vSiteName_other"  class="form-control " id="vSiteName_other" placeholder="Search Premise Id or Premise Name" value="{if $rs_site[0].iPremiseId}{if $rs_site[0].vName neq ''}{$rs_site[0].vName|gen_strip_slash} - {/if}PremiseID# {$rs_site[0].iPremiseId}{/if}"  required>
                			<input type="hidden" id="serach_iPremiseId_other" name="serach_iPremiseId_other" value="{$rs_site[0].iPremiseId}"/>
                			<div class="invalid-feedback" id="errmsg_search_site">Please enter premise</div>
                		</div>
                        <label class="col-sm-2 col-form-label" for="vSR_other">SR</label>
                        <div class="col-sm-4">
                            <input type="text" name="vSR_other"  class="form-control " id="vSR_other" placeholder="Search SR Id" value=""  >
                            <input type="hidden" id="serach_iSRId_other" name="serach_iSRId_other" value=""/>
                        </div>
            		</div>
            		<div class="form-group row">
            			 <label  class="col-sm-2 col-form-label" for="modal_dDate_other">Date<span class="text-danger"> *</span></label>
            			<div class="col-sm-6">
                            <input type="date" class="form-control" id="modal_dDate_other" name="modal_dDate_other" value="" required> 
                			<div class="invalid-feedback">Please select date</div>
                		</div>
            		</div>
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="">Start time<span class="text-danger"> *</span></label>
            			<div class="col-sm-4">
            				<input type="time" class="form-control" id="dStartTime_other" name="dStartTime_other" required> 
                            <div class="invalid-feedback">Please enter start time.</div>
                		</div>
            			<label  class="col-sm-2 col-form-label" for="">End time<span class="text-danger"> *</span></label>
            			 <div class="col-sm-4">
                            <input type="time" class="form-control" id="dEndTime_other" name="dEndTime_other" required> 
                            <div class="invalid-feedback" >Please enter end time.</div>
                            <div class="invalid-feedback" id="errmsg_dEndTime_other"></div>
                		</div>
            		</div> 
                    <div class="form-group row">
        				<label  class="col-sm-2 col-form-label" for="">Task Type</label>
        				<div class="col-sm-4">
        					<select name="iTaskTypeId" id="iTaskTypeId" class="form-control select">
                                {section name="s" loop=$rs_type}
                                <option value="{$rs_type[s].iTaskTypeId}">{$rs_type[s].vTypeName|gen_strip_slash}</option>
                                {/section}
        					</select>
        				</div>
                        <label class="col-sm-2 col-form-label" for="otechnician_id">Technician Name</label>
                        <div class="col-sm-4">
                            <select name="technician_id" id="otechnician_id" class="form-control select">
                                <option value="">Select Technician</option>
                                {section name="s" loop=$technician_user_arr}
                                <option value="{$technician_user_arr[s].iUserId}">{$technician_user_arr[s].vName|gen_strip_slash}</option>
                                {/section}
                            </select>
                        </div>
            		</div>
            		<div class="form-group row">
            			<label  class="col-sm-2 col-form-label" for="">Notes</label>
            			 <div class="col-sm-10">
            			 	<textarea class="form-control" name="tNotes_other" id="tNotes_other"></textarea>
            			 </div>
            		</div>
            	</form>
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                 <img src="assets/images/loading-small.gif" id="save_loading_other" border="0" style="display:none;"> 
                <input type="submit" class="btn btn-primary" id="save_data_other" value="Save" name="save_data">
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