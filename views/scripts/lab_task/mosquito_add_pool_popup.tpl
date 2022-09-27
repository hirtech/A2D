<a class="btn btn-primary d-none" id="mpsquitopool_box" data-toggle="modal" href="#exampleModaltooltip">launch model</a>
<div class="modal fade" id="exampleModaltooltip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-wmax750" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mp_modaltitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
            	<form  id="frmadd" name="frmadd" action="" class="form-horizontal needs-validation" method="post" novalidate>
            		<input type="hidden" name="mode" id="mp_mode" value="">
                    <input type="hidden" name="iTMCId" id="mp_iTMCId" value="">
                    <input type="hidden" name="iTTId" id="mp_iTTId" value="">
            		<div class="form-group row">
            			<label class="col-sm-4 " for="mp_vPool">Create Pool<span class="text-danger"> *</span></label>
            			<div class="col-sm-8">
                			<select name="vPool" id="mp_vPool" class="form-control" required>
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                			<div class="invalid-feedback">Please select create pool.</div>
                		</div>
            		</div> 
                    <div class="form-group row">
                        <label class="col-sm-4" for="mp_cmmp">Total Mosquito Count to be divided among all these pools<span class="text-danger"> *</span></label>
                        <div class="col-sm-8">
                            <input type="number" name="iCountMosqperpool" id="mp_cmmp" class="form-control" value="" required>
                            <div class="invalid-feedback">Please enter value.</div>
                            <div class="invalid-feedback" id="errms_countpool"></div>
                        </div>
                    </div> 
            		<div class="form-group row">
                        <label class="col-sm-4 " for="mp_tnp">Total Number of pools<span class="text-danger"> *</span></label>
                        <div class="col-sm-8">
                            <input type="number" name="iNumberinPool" id="mp_tnp" class="form-control"  value="" required>
                            <div class="invalid-feedback">Please enter total number of pool.</div>
                            <div class="invalid-feedback" id="errms_numinpool"></div>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <div class="col-sm-8 ">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input"  name="poolgridchk" id="poolgridchk" value="1" >
                                <label class="custom-control-label" for="poolgridchk">For faster data-entry of pool-results, do you want to mark these pools as "Negative" results for now<br>(you can manually change the results later if its different)</label>
                            </div>
                        </div>
                    </div>
                    <div id="pool_result_grid" class="form-group row d-none">
                        <div id="pool_agenttest_jsGrid" class="col-12"></div>
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
