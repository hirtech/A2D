<a class="btn btn-primary d-none" id="insta_treatment_box" data-toggle="modal" href="#instatreatmentModaltooltip">launch model</a>
<div class="modal fade" id="instatreatmentModaltooltip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-wmax750" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title_treatment">Insta Treat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox_instatreatment">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
            	<form  id="frmadd_instatreatment" name="frmadd_instatreatment" action="" class="form-horizontal needs-validation" method="post" novalidate>
                    <input type="hidden" name="mode" value="UpdateInsta_Treat_Setting">
                    <input type="hidden" id="instamodal_unit_parentid" name="unit_parentid" value="{$tmpinsta_db_res['unit_parentid']}">
                    <input type="hidden" id="instamodal_unit_id"   value="{$tmpinsta_db_res['INSTA_TREATMENT_UNIT_ID']}">

                    <div class="form-group row">
        				<label  class="col-sm-2 col-form-label" for="instamodal_vTrProduct_treatment">Treatment Product <span class="text-danger"> *</span></label>
        				<div class="col-sm-4">
                            <input type="text" name="vTrProduct_treatment"  class="form-control " id="instamodal_vTrProduct_treatment" placeholder="Search Treatment Product" value="{$tmpinsta_db_res['treatment_product']}"  required>
                            <input type="hidden" id="instaserach_iTPId_treatment" name="serach_iTPId_treatment" value="{$tmpinsta_db_res['INSTA_TREATMENT_PRODUCT_ID']}">
                            <div class="invalid-feedback" id="instaerrmsg_vTrProduct_treatment">Please enter treatment product</div>
                        </div>
                        <label  class="col-sm-2 col-form-label" for="instamodal_vAppRate_treatment">Application Rate</label>
                        <div class="col-sm-4">
                            <input type="text" name="vAppRate_treatment"  class="form-control readonly-color" id="instamodal_vAppRate_treatment" placeholder="" value="{$tmpinsta_db_res['appRate']}"  >
                        </div>
            		</div>
            		<div class="form-group row">
            			<label  class="col-sm-2 col-form-label" for="instamodal_vArea_treatment">Area Treated</label>
            			 <div class="col-sm-10">
            			 	  <div class="form-check-inline col-sm-5">
                                   <input type="text" name="vArea_treatment"  class="form-control " id="instamodal_vArea_treatment" value="{$tmpinsta_db_res['INSTA_TREATMENT_AREA']}" placeholder="Area Treated">

                                </div>
                                <div class="form-check-inline col-sm-6">
                                     <select name="vAreaTreated_treatment" id="instamodal_vAreaTreated_treatment"  class="form-control col-12 select">
                                        <option value="acre" {if $tmpinsta_db_res['INSTA_TREATMENT_AREA_TREATED'] eq 'acre'} selected {/if}>acre</option>
                                        <option value="sqft" {if $tmpinsta_db_res['INSTA_TREATMENT_AREA_TREATED'] eq 'sqft'} selected {/if} >sqft</option>
                                    </select>
                                </div>
            			 </div>
                    </div>
                    <div class="form-group row">
                         <label  class="col-sm-2 col-form-label" for="instamodal_vAmountApplied_treatment">Amount Applied <span class="text-danger"> *</span></label>
                         <div class="col-sm-10">
                              <div class="form-check-inline col-sm-5">
                                   <input type="text" name="vAmountApplied_treatment"  class="form-control " id="instamodal_vAmountApplied_treatment" value="{$tmpinsta_db_res['INSTA_TREATMENT_AMOUNT_APPLIED']}" placeholder="Amount Applied" required>
                                    
                                </div>
                                <div class="form-check-inline col-sm-6">
                                    <select name="iUId_treatment" id="instamodal_iUId_treatment"  class="form-control col-12 select" required>
                                        <option value="">-- Select --</option>
                                    </select>
                                </div>
                                <div class="invalid-feedback" id="instaerrmsg_amountapplied"></div>
                         </div>
            		</div>
            	</form>
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal_instatreatment">Close</button>
                 <img src="assets/images/loading-small.gif" id="save_loading_instatreatment" border="0" style="display:none;"> 
                <input type="submit" class="btn btn-primary" id="save_data_instatreatment" value="Save" name="save_data">
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

<script src="assets/js/app_js/insta_treat_popup.js"></script>