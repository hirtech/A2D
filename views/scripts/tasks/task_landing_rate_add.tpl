<a class="btn btn-primary d-none" id="adult_box" data-toggle="modal" href="#adultModaltooltip">launch model</a>
<div class="modal fade" id="adultModaltooltip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-wmax750" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title_adult"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox_adult">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
            	<form  id="frmadd_adult" name="frmadd_adult" action="" class="form-horizontal needs-validation" method="post" novalidate>
            		<input type="hidden" name="mode" id="mode_title_adult" value="">
                    <input type="hidden" name="modal_iTLRId" id="modal_iTLRId" value="">
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="vSiteName_adult">Premise <span class="text-danger"> *</span></label>
            			<div class="col-sm-4">
                			<input type="text" name="vSiteName_adult"  class="form-control " id="vSiteName_adult" placeholder="Search Premise Id or Premise Name" value="{if $rs_site[0].iSiteId}{if $rs_site[0].vName neq ''}{$rs_site[0].vName|gen_strip_slash} - {/if}PremiseID# {$rs_site[0].iSiteId}{/if}"  required>
                			<input type="hidden" id="serach_iSiteId_adult" name="serach_iSiteId_adult" value="{$rs_site[0].iSiteId}"/>
                			<div class="invalid-feedback" id="errmsg_search_site">Please enter premise</div>
                		</div>
                        <label class="col-sm-2 col-form-label" for="vSR_adult">SR</label>
                        <div class="col-sm-4">
                            <input type="text" name="vSR_adult"  class="form-control " id="vSR_adult" placeholder="Search SR Id" value=""  >
                            <input type="hidden" id="serach_iSRId_adult" name="serach_iSRId_adult" value=""/>
                        </div>
            		</div>
            		<div class="form-group row">
            			 <label  class="col-sm-2 col-form-label" for="modal_dDate_adult">Date<span class="text-danger"> *</span></label>
            			<div class="col-sm-6">
                            <input type="date" class="form-control" id="modal_dDate_adult" name="modal_dDate_adult" value="" required> 
                			<div class="invalid-feedback">Please select date</div>
                		</div>
            		</div>
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="">Start time<span class="text-danger"> *</span></label>
            			<div class="col-sm-4">
            				<input type="time" class="form-control" id="dStartTime_adult" name="dStartTime_adult" required>
                            <div class="invalid-feedback">Please enter start time.</div>
                		</div>
            			<label  class="col-sm-2 col-form-label" for="">End time<span class="text-danger"> *</span></label>
            			 <div class="col-sm-4">
                            <input type="time" class="form-control" id="dEndTime_adult" name="dEndTime_adult" required> 
                            <div class="invalid-feedback" >Please enter end time.</div>
                            <div class="invalid-feedback" id="errmsg_dEndTime_adult"></div>
                		</div>
            		</div> 
            		<div class="form-group row">
            			 <label  class="col-sm-2 col-form-label" for="">Max Landing Rate</label>
            			 <div class="col-sm-4">
                			 <select name="vMaxLandingRate" id="vMaxLandingRate" class="form-control">
                			 	<option value="">Select Landing Rate</option>
                			 	<option value="0-Found">0-Found</option>
                			 	<option value="1-5">1-5</option>
                			 	<option value="6-10">6-10</option>
                			 	<option value="11-20">11-20</option>
                			 	<option value="21-50">21-50</option>
                			 	<option value="51-100">51-100</option>
                			 	<option value="100+">100+</option>
                			 </select>
                		</div>
                    </div> 
                    <div class="form-group row">
        				<label  class="col-sm-2 col-form-label" for="">Species</label>
        				<div class="col-sm-4">
        					<select name="iMSpeciesId[]" id="iMSpeciesId" class="form-control select" multiple>
                                {section name="s" loop=$rs_species}
                                <option value="{$rs_species[s].iMSpeciesId}">{$rs_species[s].tDescription|gen_strip_slash}</option>
                                {/section}
        					</select>
        				</div>
                         <label class="col-sm-2 col-form-label" for="lrtechnician_id">Technician Name</label>
                        <div class="col-sm-4">
                            <select name="technician_id" id="lrtechnician_id" class="form-control select">
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
            			 	<textarea class="form-control" name="tNotes_adult" id="tNotes_adult"></textarea>
            			 </div>
            		</div>
            	</form>
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                 <img src="assets/images/loading-small.gif" id="save_loading_adult" border="0" style="display:none;"> 
                <input type="submit" class="btn btn-primary" id="save_data_adult" value="Save" name="save_data">
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