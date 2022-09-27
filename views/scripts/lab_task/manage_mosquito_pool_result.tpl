<div class="row   no-gutters w-100">
	<div class="col-12 mt-1">
		<div class="card">
			<div class="card-header  justify-content-between align-items-center">
				<div class="user_list_header">
					<h4 class="card-title float-left">{$module_name}</h4>

				</div>
			</div>
			<div class="card-body" >
				{if $trap_pool_data|@count gt 0}
				<div class="col-12">
						<p> Pool Id : {$trap_pool_data.iTMPId} ({$trap_pool_data.vPool}; Number in Pool: {$trap_pool_data.iNumberinPool}) </p>
						<p>Trap Id : {$trap_pool_data.iTTId} ({$trap_pool_data.vTrapName};Placed {$trap_pool_data.dTrapPlaced}; Collected {$trap_pool_data.dTrapCollected})</p>
						<p>Premise Id: {$trap_pool_data.iSiteId} ({$trap_pool_data.vSiteName})</p>
						<p>Premise Id: {$trap_pool_data.vSiteAddress}</p>
					</div>
					<div class="col-12 mt-3">
	            		<form name="formlabwork" id="formlabwork"  method="post" action="" class="form-horizontal" >
	            			<input type="hidden" name="iTMPId" value="{$trap_pool_data.iTMPId}">
	            			<div class="form-group row">
			                    <label for="bLabWorkComplete"  class="col-sm-3 col-form-label mr-0 pr-0" >Lab Work Complete</label>
			                    <div class="col-sm-4 ml-0">
					                <select name="bLabWorkComplete" id="bLabWorkComplete"  class="form-control" >
			                            <option value="0" {if $trap_pool_data.bLabWorkComplete eq 0} selected {/if}>No</option>
			                            <option value="1" {if $trap_pool_data.bLabWorkComplete eq 1} selected {/if}>Yes</option>
			                        </select>
			                    </div>
	                    	</div>
			            </form>
	            	</div>
            	{/if}

				
				<input type="hidden" id="iTTId" value="{$iTTId}">
				<input type="hidden" id="iTMPId" value="{$iTMPId}">
				<div id="jsGrid_table" class="col-12"></div>
				
			</div>
		</div> 
	</div> 
</div>
{include file="general/jsGrid.tpl"}
<script type="text/javascript">
	var iTTId = '{$iTTId}';
	var iTMPId = '{$iTMPId}';
	var extra_url = (jQuery.isEmptyObject(iTMPId))?"":'&iTMPId='+iTMPId;
	var ajax_url = 'lab_task/manage_mosquito_pool_result&mode=List'+extra_url;
	var access_group_var_edit= '{$access_group_var_edit}';
	var access_group_var_delete= '{$access_group_var_delete}';
	var agent_mosquito_arr = {$agent_mosquito_arr};
	var test_method_arr = {$test_method_arr};
	var result_arr = {$result_arr};

</script>

<script src="assets/js/app_js/manage_mosquito_pool_result.js"></script>

