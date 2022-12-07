<a class="btn btn-primary d-none" id="larval_box" data-toggle="modal" href="#larvalModaltooltip">launch model</a>
<div class="modal fade" id="larvalModaltooltip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-wmax750" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
            	<form  id="frmadd" name="frmadd" action="" class="form-horizontal needs-validation" method="post" novalidate>
            		<input type="hidden" name="mode" id="mode_title" value="">
            		<input type="hidden" name="modal_iTLSId" id="modal_iTLSId" value="">
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="vSiteName">Premise <span class="text-danger"> *</span></label>
            			<div class="col-sm-4">
                			<input type="text" name="vSiteName"  class="form-control typeahead" id="vSiteName" placeholder="Search Premise Id or Premise Name" value="{if $rs_site[0].iPremiseId}{if $rs_site[0].vName neq ''}{$rs_site[0].vName|gen_strip_slash} - {/if}PremiseID# {$rs_site[0].iPremiseId}{/if}"  required>
                			<input type="hidden" id="serach_iPremiseId_larval" name="serach_iPremiseId_larval" value="{$rs_site[0].iPremiseId}"/>
                			<div class="invalid-feedback" id="errmsg_search_site">Please enter premise</div>
                		</div>
                        <label class="col-sm-2 col-form-label" for="vSR_surveillance">SR</label>
                        <div class="col-sm-4">
                            <input type="text" name="vSR_surveillance"  class="form-control " id="vSR_surveillance" placeholder="Search SR Id" value=""  >
                            <input type="hidden" id="serach_iSRId_surveillance" name="iSRId" value=""/>
                        </div>
            		</div>
            		<div class="form-group row">
            			 <label  class="col-sm-2 col-form-label" for="modal_dDate">Date<span class="text-danger"> *</span></label>
            			 <div class="col-sm-4">
                            <input type="date" class="form-control" id="modal_dDate" name="modal_dDate" value="" required> 
                			<div class="invalid-feedback">Please select date</div>
                		</div>
                		<label class="col-sm-2 col-form-label" for="iDips">Dips</label>
            			<div class="col-sm-4">
                			<input type="text" name="iDips"  class="form-control" id="iDips" value="" onchange="calculateAvgDips()">
                		</div>
            		</div>
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="">Start time<span class="text-danger"> *</span></label>
            			<div class="col-sm-4">
            				<input type="time" class="form-control" id="dStartTime" name="dStartTime" required>
                            <div class="invalid-feedback">Please enter start time.</div> 
                		</div>
            			<label  class="col-sm-2 col-form-label" for="">End time<span class="text-danger"> *</span></label>
            			 <div class="col-sm-4">
                            <input type="time" class="form-control" id="dEndTime" name="dEndTime"required> 
                            <div class="invalid-feedback" >Please enter end time.</div>
                            <div class="invalid-feedback" id="errmsg_dEndTime"></div> 
                		</div>
            		</div>  
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="iGenus">Genus 1</label>
            			<div class="col-sm-4">
                			<select name="iGenus" id="iGenus"  class="form-control">
								<option value="0">N/A</option>
								<option value="1" >Ae.</option>
								<option value="2">An.</option>
								<option value="3">Cs.</option>
								<option value="4" >Cx.</option>
							</select>
                		</div>
            			<label class="col-sm-2 col-form-label" for="iCount">Count</label>
            			<div class="col-sm-4">
                			<input type="text" name="iCount"  class="form-control" id="iCount" value="" onchange="calculateAvgDips()">
                		</div>
            		</div>
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="iCount">Stages found</label>
            			<div class="col-sm-10">
            				<div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bEggs" id="bEggs" value="1" >
                                <label class="custom-control-label" for="bEggs">Eggs</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bInstar1" id="bInstar1" value="1" >
                                <label class="custom-control-label" for="bInstar1">Instar1</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bInstar2" id="bInstar2" value="1" >
                                <label class="custom-control-label" for="bInstar2">Instar2</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bInstar3" id="bInstar3" value="1" >
                                <label class="custom-control-label" for="bInstar3">Instar3</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bInstar4" id="bInstar4" value="1" >
                                <label class="custom-control-label" for="bInstar4">Instar4</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bPupae" id="bPupae" value="1" >
                                <label class="custom-control-label" for="bPupae">Pupae</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bAdult" id="bAdult" value="1" >
                                <label class="custom-control-label" for="bAdult">Adult</label>
                            </div>
                		</div>
            		</div>
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="iGenus2">Genus 2</label>
            			<div class="col-sm-4">
                			<select name="iGenus2" id="iGenus2"  class="form-control">
								<option value="0">N/A</option>
									<option value="1" >Ae.</option>
									<option value="2">An.</option>
									<option value="3">Cs.</option>
									<option value="4">Cx.</option>
							</select>
                		</div>
            			<label class="col-sm-2 col-form-label" for="iCount2">Count 2</label>
            			<div class="col-sm-4">
                			<input type="text" name="iCount2"  class="form-control" id="iCount2" value="" onchange="calculateAvgDips()">
                		</div>
            		</div>
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="iCount">Stages found</label>
            			<div class="col-sm-10">
            				<div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bEggs2" id="bEggs2" value="1" >
                                <label class="custom-control-label" for="bEggs2">Eggs</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bInstar12" id="bInstar12" value="1" >
                                <label class="custom-control-label" for="bInstar12">Instar1</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bInstar22" id="bInstar22" value="1" >
                                <label class="custom-control-label" for="bInstar22">Instar2</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bInstar32" id="bInstar32" value="1" >
                                <label class="custom-control-label" for="bInstar32">Instar3</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bInstar42" id="bInstar42" value="1" >
                                <label class="custom-control-label" for="bInstar42">Instar4</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bPupae2" id="bPupae2" value="1" >
                                <label class="custom-control-label" for="bPupae2">Pupae</label>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bAdult2" id="bAdult2" value="1" >
                                <label class="custom-control-label" for="bAdult2">Adult</label>
                            </div>
                		</div>
            		</div>
            		
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="technician_id">Technician Name</label>
                        <div class="col-sm-4">
                            <select name="technician_id" id="technician_id" class="form-control select">
                                <option value="">Select Technician</option>
                                {section name="s" loop=$technician_user_arr}
                                <option value="{$technician_user_arr[s].iUserId}">{$technician_user_arr[s].vFirstName|gen_strip_slash} {$technician_user_arr[s].vLastName|gen_strip_slash}</option>
                                {/section}
                            </select>
                        </div>
                    
                        <label class="col-sm-2 col-form-label" for="vSiteName">Avg larvae/dip</label>
                        <div class="col-sm-4">
                            <input type="text" name="rAvgLarvel"  class="form-control readonly-color" id="rAvgLarvel" value="" readonly="">
                        </div>
                    </div>
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="tNotes">Notes</label>
            			<div class="col-sm-4">
                			<textarea class="form-control" name="tNotes" id="tNotes"></textarea>
                		</div>
            		</div>
            	</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                 <img src="assets/images/loading-small.gif" id="save_loading_ls" border="0" style="display:none;"> 
                <input type="submit" class="btn btn-primary" id="save_data" value="Save" name="save_data">
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