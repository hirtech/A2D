<div class="row  no-gutters w-100">
    <div class="col-12 mt-1">
        <div class="card">
            <div class="card-header  justify-content-between align-items-center">
                <div class="user_list_header">
                    <h4 class="card-title float-left">{$module_name}</h4>
                    <form id="frmlist" name="frmlist" class="sorder_search_form">
                        <ul class="nav search-links float-right">
                            <li>
                                <select id="vOptions" name="vOptions" class="form-control">
                                    <option value="iEquipmentModelId">ID</option>
                                    <option value="vModelName">Model Name</option>
                                    <option value="vModelNumber">Model Number</option>
                                    <option value="vPartNumber">Part Number</option>
                                    <option value="iUnitQuantity">Unit Quantity</option>
                                    <option value="rUnitCost">Unit Cost</option>
                                    <option value="vEquipmentType">Equipment Type</option>
                                    <option value="vEquipmentManufacturer">Manufacturer</option>
                                </select>
                            </li>
                            <li>
                               <input type="text" name="Keyword" id="Keyword" class="form-control" value="" autocomplete="off">
                            </li>
                            <li>
                                <button type="button" id="Search" class="btn  btn-outline-warning fas fa-search" title="Search"><span class=""></span></button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-danger btn-reset fas fa-times" aria-label="Close" id="Reset" style="cursor:pointer;" title="Reset" class=""/></button>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive ">
                <table id="datatable-grid" class="display table dataTable table-striped table-bordered editable-table " width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Model Name</th>
                            <th>Model Number</th>
                            <th>Part Number</th>
                            <th>Unit Quantity</th>
                            <th>Unit Cost </th>
                            <th>Equipment Type</th>
                            <th>Manufacturer</th>
                            <th>Action</th>
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


<script type="text/javascript">
    //var iPremiseId = '{$iPremiseId}';
    //var extra_url = (jQuery.isEmptyObject(iPremiseId))?"":'&iPremiseId='+iPremiseId;
    //var ajax_url = 'service_order/workorder_list&mode=List'+extra_url;
    var ajax_url = 'service_order/equipment_model_list&mode=List';
    var access_group_var_add = '{$access_group_var_add}';
</script>
{include file="general/dataTables.tpl"}
<script src="assets/js/app_js/equipment_model.js"></script>