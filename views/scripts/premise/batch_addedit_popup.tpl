<a class="btn btn-primary d-none" id="batch_modalbox" data-toggle="modal" href="#batchmodal">launch model</a>
<div class="modal fade " id="batchmodal" tabindex="-1" role="dialog" aria-labelledby="batchmodal1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="batmodaltitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
                <div class="form-group row">
                    <span class="invalid-feedback">asdasdadasfujhkdsfagbdjfklsgk</span>
                </div>
            	<form  id="batchfrmadd" name="batchfrmadd" action="" class="form-horizontal needs-validation" method="post" novalidate>
            		<input type="hidden" name="mode" id="bat_mode" value="">
                    <input type="hidden" name="iPremiseId" id="premiseid" value="" />
            		<div class="form-group row">
            			<label class="col-sm-3" for="iSTypeId1">Premise Type </label>
            			<div class="col-sm-9">
            		        <select name="iSTypeId1" id="iSTypeId1" class="select" onchange="getSiteSubType(this.value);" >
                                <option value="">--- Select ---</option>
                                {section name="a" loop=$rs_site_type}
                                <option value="{$rs_site_type[a].iSTypeId}" {if $rs_site_type[a].iSTypeId eq $rs_site[0].iSTypeId} selected {/if}>{$rs_site_type[a].vTypeName|gen_strip_slash}</option>
                                {/section}
                            </select>
                		</div>
                    </div>
                    <div class="form-group row"> 
            			<label  class="col-sm-3" for="iSSTypeId1">Premise Sub Type</label>
                        <div class="col-sm-9">
                            <select name="iSSTypeId1" id="iSSTypeId1" class="select" >
                                <option value="">--- Select ---</option>
                            </select>
                        </div>
                    </div>
            		<div class="form-group row">
            			<label  class="col-sm-3" for="iStatus">Status</label>
            			 <div class="col-sm-9">
                            <select name="iStatus" id="iStatus" class="select">
                                <option value="" selected>Select Status</option>
                                <option value="1">On-Net</option>
                                <option value="0">Off-Net</option>
                                <option value="2">Near-Net</option>
                            </select>
            			 </div>
            		</div>
            	</form>
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                 <img src="assets/images/loading-small.gif" id="bat_save_loading" border="0" style="display:none;"> 
                <input type="submit" class="btn btn-primary" id="bat_save_data" value="Save" name="bat_save_data">
            </div>
        </div>
    </div>
</div>

<!-- START: Page Vendor JS-->
<script src="assets/vendors/jquery-inputmask/jquery.inputmask.min.js"></script>
<!-- END: Page Vendor JS-->
