<a class="btn btn-primary d-none" id="batchPremises_box" data-toggle="modal" href="#batchPremisesModaltooltip">launch model</a>
<div class="modal fade" id="batchPremisesModaltooltip" tabindex="-1" role="dialog" aria-labelledby="batchPremisesexampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-wmax500" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title_batch_premises">Create Multiple Premises in a Single Batch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox_batch_premises">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
            	<form  id="frmadd_batch_premises" name="frmadd_batch_premises" action="" class="form-horizontal needs-validation" method="post" novalidate>
            		<input type="hidden" name="mode" id="mode" value="multiple_batch_premises">
					<input type="hidden" name="batch_latlong" id="batch_latlong" value="">
					<div class="form-group row">
        				<label  class="col-sm-4 col-form-label" for="iSMapTypeId">Premise Type</label>
        				<div class="col-sm-8">
        					<select name="iSMapTypeId" id="iSMapTypeId" class="select" required onchange="getSiteSubType(this.value);" >
								<option value="">--- Select ---</option>
								{section name="a" loop=$rs_sitetype}
								<option value="{$rs_sitetype[a].iSTypeId}">{$rs_sitetype[a].vTypeName|gen_strip_slash}</option>
								{/section}
							</select>
							<div class="invalid-feedback">
								Please choose premise type.
							</div>
        				</div>
					</div>
					<div class="form-group row">
        				<label  class="col-sm-4 col-form-label" for="iSSMapTypeId">Premise Sub Type</label>
        				<div class="col-sm-8">
        					<select name="iSSMapTypeId" id="iSSMapTypeId" class="select">
								<option value="">--- Select ---</option>
							</select>
        				</div>
					</div>
					<div class="form-group row">
        				<label  class="col-sm-4 col-form-label" for="iSMapAttributeId">Premise Attribute</label>
        				<div class="col-sm-8">
        					<select name="iSMapAttributeId[]" id="iSMapAttributeId" class="select"  multiple >
								<option value="">--- Select ---</option>
								{section name="a" loop=$rs_siteattr}
								<option value="{$rs_siteattr[a].iSAttributeId}">{$rs_siteattr[a].vAttribute|gen_strip_slash}</option>
								{/section}
							</select>
        				</div>
					</div>
            	</form>
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                 <img src="assets/images/loading-small.gif" id="save_loading_batch_premises" border="0" style="display:none;"> 
                <input type="submit" class="btn btn-primary" id="save_data_batch_premises" value="Save" name="save_data">
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