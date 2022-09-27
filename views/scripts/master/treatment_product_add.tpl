<a class="btn btn-primary d-none" id="trprod_box" data-toggle="modal" href="#trprodModaltooltip">launch model</a>
<div class="modal fade" id="trprodModaltooltip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
            	<form  id="frmadd" name="frmadd" action="" class="form-horizontal needs-validation" method="post" novalidate>
            		<input type="hidden" name="mode" id="modal_mode" value="">
            		<input type="hidden" name="modal_iTPId" id="modal_iTPId" value="">
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="modal_vName">Name  <span class="text-danger"> *</span></label>
            			<div class="col-sm-4">
                			<input type="text" name="modal_vName"  class="form-control " id="modal_vName" placeholder="Name" value=""  required>
                			<div class="invalid-feedback">Please enter name</div>
                		</div>
                        <label class="col-sm-2 col-form-label" >Status <span class="text-danger"> *</span></label>
                        <div class="col-sm-4">
                            <input type="checkbox" id="modal_iStatus" name="modal_iStatus" value="1" data-toggle="toggle" data-on="Active" data-onstyle="success" data-off="Inactive" data-offstyle="danger"  data-width="100" data-width="26" >
                            <div class="invalid-feedback">Please select status.</div>
                        </div>
            		</div>
            		<div class="form-group row">
            			 <label  class="col-sm-2 col-form-label" for="modal_vCategory">Category</label>
            			 <div class="col-sm-4">
                            <input type="text" class="form-control" id="modal_vCategory" name="modal_vCategory" value="" placeholder="Category" > 
                		</div>
                		<label class="col-sm-2 col-form-label" for="modal_vClass">Class</label>
            			<div class="col-sm-4">
                			<input type="text" name="modal_vClass"  class="form-control" id="modal_vClass" value="" placeholder="Class" >
                		</div>
            		</div>
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="">Include in Pesticide Reporting</label>
            			<div class="col-sm-4">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="modal_iPesticideY" name="modal_iPesticide" value="Y">
                                <label class="custom-control-label" for="modal_iPesticideY">Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="modal_iPesticideN" name="modal_iPesticide" value="N">
                                <label class="custom-control-label" for="modal_iPesticideN">No</label>
                            </div>
                		</div>
            			<label  class="col-sm-2 col-form-label" for="modal_vEPARegNo">EPA Reg No.</label>
            			 <div class="col-sm-4">
                            <input type="text" class="form-control" id="modal_vEPARegNo" name="modal_vEPARegNo" placeholder="EPA Reg No." > 
                		</div>
            		</div>  
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="modal_vActiveIngredient">Active Ingredient</label>
            			<div class="col-sm-4">
                			<input type="text" class="form-control"  name="modal_vActiveIngredient" id="modal_vActiveIngredient" placeholder="Active Ingredient" >
                		</div>
            			<label class="col-sm-2 col-form-label" for="modal_vAI">% AI (enter 20 if AI is 20%)</label>
            			<div class="col-sm-4">
                			<input type="text" name="modal_vAI"  class="form-control" id="modal_vAI" value="" placeholder="% AI" >
                		</div>
            		</div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="modal_vActiveIngredient2">2nd Active Ingredient</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control"  name="modal_vActiveIngredient2" id="modal_vActiveIngredient2" placeholder="2nd Active Ingredient" >
                        </div>
                        <label class="col-sm-2 col-form-label" for="modal_vAI2">% 2nd-AI (enter 20 if AI is 20%)</label>
                        <div class="col-sm-4">
                            <input type="text" name="modal_vAI2"  class="form-control" id="modal_vAI2" value="" placeholder="% 2nd-AI" >
                        </div>
                    </div>
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="modal_iUId">Default Unit <span class="text-danger">*</span></label>
            			<div class="col-sm-4">
                			<select name="modal_iUId" id="modal_iUId"  class="form-control" required>
								<option value="">-- Select --</option>
                                {if $unit_arr|@count gt 0}
                                    {foreach from=$unit_arr key=k item=val}
                                        <optgroup label="{$k}">
                                        {if $val gt 0}
                                        {section name="u" loop=$val}
                                        <option value="{$val[u].iUId}">{$val[u].vUnit}</option>
                                        {/section}
                                        {/if}
                                        </optgroup>
                                    {/foreach}
                                {/if}
							</select>
                            <div class="invalid-feedback" >Please select default unit</div>
                		</div>
            			<label class="col-sm-2 col-form-label" for="modal_vAppRate">Traget Application Rate <span class="text-danger"> *</span> </label>
            			<div class="col-sm-4">
                                <div class="form-check-inline col-sm-6">
                    			   <input type="text" name="modal_vAppRate"  class="form-control " id="modal_vAppRate" value="" placeholder="Traget Application Rate" required>

                                </div>
                                <div class="form-check-inline col-sm-5">
                                     <select name="modal_vTragetAppRate" id="modal_vTragetAppRate"  class="form-control">
                                        <option value="acre">Per acre</option>
                                        <option value="sqft">Per sqft</option>
                                    </select>
                                </div>
                                <div class="invalid-feedback" id="errmsg_apprate"></div>
                		</div>
            		</div>
            		<div class="form-group row">
            			<label class="col-sm-2 col-form-label" for="modal_vMinAppRate">Minimum Application Rate <span class="text-danger"> *</span></label>
            			<div class="col-sm-4">
                			<input type="text" name="modal_vMinAppRate"  class="form-control" id="modal_vMinAppRate" value="" placeholder="Minimum Application Rate"  required>
                            <div class="invalid-feedback">Please enter minimum application rate</div>
                		</div>
                        <label class="col-sm-2 col-form-label" for="modal_vMaxAppRate">Maximum Application Rate <span class="text-danger"> *</span></label>
                        <div class="col-sm-4">
                            <input type="text" name="modal_vMaxAppRate" class="form-control" id="modal_vMaxAppRate" value=""  placeholder="Maximum Appication Rate" required>
                            <div class="invalid-feedback">Please enter max appication rate</div>
                        </div>
            		</div>
            	</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                 <img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;"> 
                <input type="submit" class="btn btn-primary" id="save_data" value="Save" name="save_data">
            </div>
        </div>
    </div>
</div>
<script src="assets/js/app_js/treatment_product_add.js"></script>