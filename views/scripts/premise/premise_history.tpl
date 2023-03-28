<div class="row  no-gutters w-100">
	<div class="col-12 mt-1">
		<div class="card">
			<div class="card-header  justify-content-between align-items-center">
				<div class="user_list_header">
					<h4 class="card-title float-left">{$module_name} {if $iPremiseId gt 0}<span class="text-primary"> {$vName} # {$iPremiseId}</span>{/if}</h4>
					<p class="card-title float-right">
					<button class="btn btn-primary"><a href="{$site_url}premise/list"><font color="white">Back to Premise List</font></a></button>
					</p>
				</div>
			</div>
			<form id="frmlist" name="frmlist" class="site_search_form">
			<div class="card-body">
				<div class="table-responsive">
				<table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table">
					<thead>
						<tr>
							<th>Date</th>
							<th>Name</th>
							<th>Description</th>
						</tr>
					</thead>
					<tbody>                            
					</tbody>
				</table>
				</div>
			</div>
			</form>
		</div> 
	</div> 
</div>
{include file="general/dataTables.tpl"}
<script type="text/javascript">
  var iPremiseId = '{$iPremiseId}';
  var vName = '{$vName}';
  var extra_url = (jQuery.isEmptyObject(iPremiseId))?"":'&iPremiseId='+iPremiseId;
  var extra_url2 = (jQuery.isEmptyObject(vName))?"":'&vName='+vName;
  var ajax_url = 'premise/history&mode=History'+extra_url+extra_url2;
</script>
<script src="assets/js/app_js/premise_history.js"></script>
