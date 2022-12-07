<a class="btn btn-primary d-none" id="tasktrap_box" data-toggle="modal" href="#tasktrapModaltooltip">launch model</a>
<div class="modal fade" id="tasktrapModaltooltip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-wmax750" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title_tasktrap"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox_tasktrap">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
                <form  id="frmadd_tasktrap" name="frmadd_tasktrap" action="" class="form-horizontal needs-validation" method="post" novalidate>
                    <input type="hidden" name="mode" id="mode_title_tasktrap" value="">
                    <input type="hidden" name="modal_iTTId" id="modal_iTTId" value="">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="vSiteName_tasktrap">Premise <span class="text-danger"> *</span></label>
                        <div class="col-sm-4">
                            <input type="text" name="vSiteName_tasktrap"  class="form-control " id="vSiteName_tasktrap" placeholder="Search Premise Id or Premise Name" value="{if $rs_site[0].iPremiseId}{if $rs_site[0].vName neq ''}{$rs_site[0].vName|gen_strip_slash} - {/if}PremiseID# {$rs_site[0].iPremiseId}{/if}"  required>
                            <input type="hidden" id="serach_iPremiseId_tasktrap" name="serach_iPremiseId_tasktrap" value="{$rs_site[0].iPremiseId}"/>
                            <div class="invalid-feedback" id="errmsg_search_site">Please enter premise</div>
                        </div>
                        <label class="col-sm-2 col-form-label" for="vSR_tasktrap">SR</label>
                        <div class="col-sm-4">
                            <input type="text" name="vSR_tasktrap"  class="form-control " id="vSR_tasktrap" placeholder="Search SR Id" value=""  >
                            <input type="hidden" id="serach_iSRId_tasktrap" name="serach_iSRId_tasktrap" value=""/>
                        </div>
                    </div>
                    <div class="form-group row">
                         <label  class="col-sm-2 col-form-label" for="dTrapPlaced_tasktrap">Date Set<span class="text-danger"> *</span></label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="dTrapPlaced_tasktrap" name="dTrapPlaced_tasktrap" value="" required> 
                            <div class="invalid-feedback dTrapPlaced_msg">Please select date</div>
                        </div>
                        <label  class="col-sm-2 col-form-label" for="dTrapCollected_tasktrap">Date Collected</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="dTrapCollected_tasktrap" name="dTrapCollected_tasktrap" value=""> 
                            <div class="invalid-feedback">Please select date collected</div>
                            <div class="invalid-feedback" id="errmsg_dTrapCollected_tasktrap"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label  class="col-sm-2 col-form-label" for="">Trap Type</label>
                        <div class="col-sm-4">
                            <select name="iTrapTypeId" id="iTrapTypeId" class="form-control select">
                                {section name="s" loop=$rs_trap_type}
                                <option value="{$rs_trap_type[s].iTrapTypeId}">{$rs_trap_type[s].vTrapName|gen_strip_slash}</option>
                                {/section}
                            </select>
                        </div>
                        <label class="col-sm-4 col-form-label" for="bMalfunction">Did the Trap malfunction?</label>
                        <div class="col-sm-2">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="bMalfunction" id="bMalfunction" value="1" >
                                <label class="custom-control-label" for="bMalfunction"></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                         <label class="col-sm-2 col-form-label" for="ttechnician_id">Technician Name</label>
                        <div class="col-sm-4">
                            <select name="technician_id" id="ttechnician_id" class="form-control select">
                                <option value="">Select Technician</option>
                                {section name="s" loop=$technician_user_arr}
                                <option value="{$technician_user_arr[s].iUserId}">{$technician_user_arr[s].vFirstName|gen_strip_slash} {$technician_user_arr[s].vLastName|gen_strip_slash}</option>
                                {/section}
                            </select>
                        </div>
                        <label  class="col-sm-2 col-form-label" for="">Notes</label>
                         <div class="col-sm-4">
                            <textarea class="form-control" name="tNotes_tasktrap" id="tNotes_tasktrap"></textarea>
                         </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                 <img src="assets/images/loading-small.gif" id="save_loading_tasktrap" border="0" style="display:none;"> 
                <input type="submit" class="btn btn-primary" id="save_data_tasktrap" value="Save" name="save_data">
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