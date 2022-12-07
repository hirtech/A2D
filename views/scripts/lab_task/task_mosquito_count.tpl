<div class="row   no-gutters w-100">
    <div class="col-12 mt-1">
        <div class="card">
            <div class="card-header  justify-content-between align-items-center">
                <div class="user_list_header">
                    <h4 class="card-title float-left">{$module_name}</h4>

                </div>
            </div>
            <div class="card-body" >

                {if $trap_data|@count gt 0}
                <div class="col-12">
                    <p>Trap Id : {$trap_data.iTTId} ({$trap_data.vTrapName};Placed {$trap_data.dTrapPlaced}; Collected {$trap_data.dTrapCollected})</p>
                    <p>Premise Id: {$trap_data.iPremiseId} ({$trap_data.vSiteName})</p>
                    <p>{$trap_data.vSiteAddress}</p>
                </div>
                <div class="col-12 mt-3">
                    <form name="formlabwork" id="formlabwork"  method="post" action="" class="form-horizontal" >
                        <input type="hidden" name="iTTId" value="{$iTTId}">
                        <div class="form-group row">
                            <label for="bLabWorkComplete"  class="col-sm-3 col-form-label mr-0 pr-0" >Lab Work Complete</label>
                            <div class="col-sm-4 ml-0">
                                <select name="bLabWorkComplete" id="bLabWorkComplete"  class="form-control" >
                                    <option value="0" {if $trap_data.bLabWorkComplete eq 0} selected {/if}>No</option>
                                    <option value="1" {if $trap_data.bLabWorkComplete eq 1} selected {/if}>Yes</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                {/if}
            </div>
                <input type="hidden" id="iTTId" value="{$iTTId}">
                <div id="jsGrid_table" class="col-12"></div>            
            </div>
        </div> 
    </div>
{include file="general/jsGrid.tpl"}
{include file="scripts/lab_task/mosquito_add_pool_popup.tpl"}
<script type="text/javascript">
    var iTTId = '{$iTTId}';
    var extra_url = (jQuery.isEmptyObject(iTTId))?"":'&iTTId='+iTTId;
    var ajax_url = 'lab_task/task_mosquito_count&mode=List'+extra_url;
    var access_group_var_edit= '{$access_group_var_edit}';
    var access_group_var_delete= '{$access_group_var_delete}';
    var access_group_var_add= '{$access_group_var_add}';
    var mosq_pool_access_group_var_add= '{$mosq_pool_access_group_var_add}';
    var species_drp = {$db_species};
        var agent_mosquito_arr = {$agent_mosquito_arr};
    var test_method_arr = {$test_method_arr};
</script>

<script src="assets/js/app_js/task_mosquito_count.js"></script>
<script src="assets/js/app_js/mosquito_pool_add.js"></script>

