<div class="row  no-gutters w-100" id="pageHeader"> 
	<div class="col-12 mt-1">
		<div class="card">
			<div class="card-header  ">
			<div class="align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{if $inv_data['vName'] neq ''} {$inv_data['vName']} {if $inv_data['vUnit'] neq ''} (all transactions are for unit "{$inv_data['vUnit']}"){/if} {/if}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
               <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
		                <li class="breadcrumb-item"><a href="{$site_url}inventory/inventory_list">Inventory List</a></li>
            </ol>
        
			</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				<table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table table-fixed" width="100%">
					<thead>
						<tr>
							<th  width="20%">Date</th>
							<th  width="10%">Purchased</th> 
							<th  width="10%">Uses</th> 
							<th  width="10%">Balance</th> 
							<th  width="40%"> </th> 
						</tr>
					</thead>
					<tbody>                            
					</tbody>
					<tfoot align="right">
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
				</tfoot>
				</table>
				</div>
				<a class="btn btn-primary d-none" id="invdetail_box" data-toggle="modal" href="#invdetailmodal">launch model</a>
                <div class="modal fade" id="invdetailmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="invdt_modaltitle"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body col-md-12">
                            	<form  id="frmadd_invdt" name="frmadd_invdt" action="" class="form-horizontal needs-validation" method="post" novalidate>
                            		<input type="hidden" name="mode" id="invdt_mode" value="">
                            		<input type="hidden" name="iIPId" id="invdt_iIPId" value="">
                            		<div class="form-group row">
                            			<label class="col-sm-4 col-form-label" for="invdt_iTPId">Material </label>
                            			<div class="col-sm-8">
	                            			 {$inv_data['vName']}
	                            			 <input type="hidden" name="iTPId" id="invdt_iTPId" value="{$inv_data['iTPId']}">
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label class="col-sm-4 col-form-label" for="invdt_rPurQty">Purchased Quantity<span class="text-danger"> *</span></label>
                            			<div class="col-sm-8">
	                            			<input type="text" name="rPurQty" id="invdt_rPurQty" placeholder="Purchased Quantity" class="form-control" value=""  required>
	                            			<div class="invalid-feedback">Please enter purchased quantity.</div>
	                            		</div>
                            		</div> 
                            		<div class="form-group row">
                            			<label  class="col-sm-4 col-form-label" for="invdt_dPurDate">Purchased Date <span class="text-danger"> *</span></label>
            							<div class="col-sm-8">
				                            <input type="date" class="form-control" id="invdt_dPurDate" name="dPurchDate" value="" min="{$inv_data['dDate']}" required> 
				                			<div class="invalid-feedback">Please select date</div>
	                            		</div>
                            		</div>
                            	</form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                                 <img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;"> 
                                <input type="submit" class="btn btn-primary" id="invdt_save_data" value="Save" name="save_data" >
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

{include file="scripts/tasks/task_treatment_add.tpl"}
{include file="scripts/inventory/inventory_count_popup.tpl"}
<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>
<script type="text/javascript">
	var ajax_url = 'inventory/inventory_detail?mode=List&iTPId='+{$iTPId};
	var access_group_var_add = '{$access_group_var_add}';
	var dDate= '{$dDate}';
    var dStartTime= '{$dStartTime}';
    var dEndTime= '{$dEndTime}';
    //alert(dDate);
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
{include file="general/dataTables.tpl"}
<script src="assets/js/app_js/inventory_detail.js"></script>
<script src="assets/js/app_js/task_treatment_add.js"></script>
<script src="assets/js/app_js/inventory_add.js"></script>