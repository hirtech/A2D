<div class="row  no-gutters w-100">
	<div class="col-12 mt-1">
		<div class="card">
			<div class="card-header  justify-content-between align-items-center">
				<div class="user_list_header">
					<h4 class="card-title float-left">{$module_name}</h4>

					<form id="frmlist">
						<ul class="nav search-links float-right">
							<li>
								<select id="vOptions" name="vOptions" class="form-control">
									<option value="vName">Material</option>
								</select>
							</li>
							<li>
							   <input type="text" name="Keyword" id="Keyword" class="form-control" value="" autocomplete="off">
							</li>
							<li>
								<button type="button" id="Search" class="btn  btn-outline-warning fas fa-search" title="Search"><span class=""></span></button>
							</li>
							<li>
        						<button type="button" class="btn btn-danger btn-reset fas fa-times" aria-label="Close" id="Reset" style="cursor:pointer;" title="Reset"></button>
							</li>
						</ul>
					</form>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
				<table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table" width="100%">
					<thead>
						<tr>
							<th  width="40%">Material</th>
							<th  width="10%">Estimated Current Level</th> 
							<th  width="10%">Level At Last Inventory Count</th> 
							<th  width="10%">Purchased Made Since Last Inventory Count</th> 
							<th  width="10%">Used In Tretment Since Last Inventory Count</th> 
							<th  width="10%">Date Of Last Inventory Count</th> 
							<th  width="10%"> </th> 
						</tr>
					</thead>
					<tbody>                            
					</tbody>
				</table>
				</div>
			</div>
		</div> 
	</div> 
</div>
{include file="scripts/inventory/inventory_count_popup.tpl"}
<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>
<script type="text/javascript">
	var ajax_url = 'inventory/inventory_list?mode=List';
	//var ajax_url = 'master/county_list';
	var access_group_var_add = '{$access_group_var_add}';
	var dDate= '{$dDate}';
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
<script src="assets/js/app_js/inventory_list.js"></script>
<script src="assets/js/app_js/inventory_add.js"></script>

