<a class="btn btn-primary d-none" id="inv_count_box" data-toggle="modal" href="#invcountmodal">launch model</a>
<div class="modal fade" id="invcountmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invcount_modaltitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="invcount_closestbox">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
            	<form  id="frmadd_invcount" name="frmadd_invcount" action="" class="form-horizontal needs-validation" method="post" novalidate>
            		<input type="hidden" name="mode" id="invcount_mode" value="">
            		<input type="hidden" name="iICId" id="invcount_iICId" value="">
            		<div class="form-group row" id="material_div">
            			<label class="col-sm-4 col-form-label" for="invcount_iTPId">Material <span class="text-danger"> *</span></label>
            			<div class="col-sm-8">
                			 <select name="iTPId" id="invcount_iTPId" class="form-control col-12 select" required>
                                <option value="">-- Select --</option>
	                            {section name="tp" loop=$treat_prod_arr}
                                    <option value="{$treat_prod_arr[tp].iTPId}">{$treat_prod_arr[tp].vName|gen_strip_slash}</option>
                                {/section}
                            </select>
                			<div class="invalid-feedback">Please select material.</div>
                		</div>
            		</div> 
            		<div class="form-group row">
            			<label class="col-sm-4 col-form-label" for="invcount_rqty">Purchased Quantity<span class="text-danger"> *</span></label>
            			<div class="col-sm-8">
                			<input type="text" name="rqty" id="invcount_rqty" placeholder="Purchased Quantity" class="form-control" value=""  required>
                			<div class="invalid-feedback">Please enter purchased quantity.</div>
                		</div>
            		</div> 
            		<div class="form-group row">
            			<label  class="col-sm-4 col-form-label" for="invcount_dDate">Date <span class="text-danger"> *</span></label>
						<div class="col-sm-8">
                            <input type="date" class="form-control" id="invcount_dDate" name="dDate" value="" required> 
                			<div class="invalid-feedback">Please select date</div>
                		</div>
            		</div>
            	</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                <img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;">
                <input type="submit" class="btn btn-primary" id="invcount_save_data" value="Save" name="save_data" >
            </div>
        </div>
    </div>
</div>