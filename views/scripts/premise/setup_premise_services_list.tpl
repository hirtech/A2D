<div class="row  no-gutters w-100">
	<div class="col-12 mt-1">
		<div class="card">
			<div class="card-header  justify-content-between align-items-center">
				<div class="user_list_header">
					<h4 class="card-title float-left">{$module_name} <span class="text-primary">{$vPremiseName} # {$iPremiseId}</span></h4>
				</div>
			</div>
			<div class="card-body">
				<div class="row"> 
		            <div class="col-12">
		                <div class="col-12 mb-3">
		                    <div class="table-responsive">
		                        <table class="table layout-primary bordered">
		                            <thead>
		                                <tr>
											<th scope="col">Service type</th>
											<th scope="col">Status</th>
											<th scope="col">Premise Circuit</th>
											<th scope="col">Carrier</th>
											<th scope="col">Related WO</th>
											<th scope="col">Related SO</th>
											<th scope="col">User</th>
											<th scope="col">Last Action</th>
											<th scope="col" class="text-center">Action</th> 
										</tr>
		                            </thead>
		                            <tbody>
		                            	{if $cnt_pservice  > 0}
			                                {section name="s" loop=$rs_pservice}
				                            	<tr>
				                                    <td>{$rs_pservice[s].vServiceType}</td>
				                                    <td>{$rs_pservice[s].vStatus}</td>
				                                    <td>{$rs_pservice[s].vCircuitName}</td>
				                                    <td>{$rs_pservice[s].vCarrier}</td>
				                                    <td>{$rs_pservice[s].vWorkOrder}</td>
				                                    <td>{$rs_pservice[s].vServiceOrder}</td>
				                                    <td>{$rs_pservice[s].vUserName}</td>
				                                    <td>{$rs_pservice[s].vLastAction}</td>
				                                    <td class="text-center">
				                                    	{if $rs_pservice[s].iStatus eq 0 || $rs_pservice[s].iStatus eq 2}
				                                    	<a class="btn btn-outline-primary" title="Start" href="javascript:void(0);" onclick="startPremiseServices('{$iPremiseId}','{$rs_pservice[s].iServiceTypeId}','premise_services_start')"><i class="fas fa-play-circle"></i></a>
				                                    	{/if}
				                                    	{if $rs_pservice[s].iStatus eq 1}
				                                    	<a class="btn btn-outline-danger" title="Suspend" href="javascript:void(0);" onclick="suspendPremiseServices('{$iPremiseId}','{$rs_pservice[s].iServiceTypeId}','premise_services_suspend')"><i class="fas fa-stop-circle"></i></a>
				                                    	{/if}
				                                    </td>
				                                </tr>
			                                {/section}
		                                {else}
			                                <tr>
			                                    <td colspan="10" class="text-center"><b>No Records Found!</b></td>
			                                </tr>
		                                {/if}
		                            </tbody>
		                        </table> 
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div> 
	</div> 
</div>
{include file="scripts/premise/premise_services_start.tpl"}
{include file="scripts/premise/premise_services_suspend.tpl"}

<script type="text/javascript">
	var ajax_url = 'premise/setup_premise_services_list?iSiteId={$iPremiseId}?mode=List';
	var vPremiseName= '{$vPremiseName}';
</script>
{include file="general/dataTables.tpl"}

<script src="assets/js/app_js/setup_premise_services.js"></script>