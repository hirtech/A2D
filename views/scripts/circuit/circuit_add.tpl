<div class="row  no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$mode} {$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}circuit/circuit_list">Circuit List</a></li>
                <li class="breadcrumb-item active">{$module_name}</li>
            </ol>
        </div>
    </div>
</div>
<form name="frmadd" id="frmadd" method="post" class="form-horizontal needs-validation" novalidate enctype="multipart/form-data">
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header">                               
               <h4 class="card-title">{$module_name} {$mode}</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">                                           
                        <div class="col-12">
                            <input type="hidden" name="groupaction" value="groupaction">
                            <input type="hidden" name="mode" id="mode" value="{$mode}">
                            <input type="hidden" name="iCircuitId" id="iCircuitId" value="{$rs_data[0]['iCircuitId']}">
                            <div class="form-row">
                                <div class="col-5">
                                    <div class="col-12 mb-3">
                                        <label for="iCircuitTypeId">Circuit Type <span class="text-danger">*</span></label>
                                        <select name="iCircuitTypeId" id="iCircuitTypeId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="c" loop=$rs_ctype}
                                                <option value="{$rs_ctype[c].iCircuitTypeId}" {if $rs_ctype[c].iCircuitTypeId eq $rs_data[0].iCircuitTypeId}selected{/if}>{$rs_ctype[c].vCircuitType|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please select circuit type</div>
                                    </div>
                            		<div class="col-12 mb-3">
                                        <label for="iNetworkId">Network <span class="text-danger">*</span></label>
                                        <select name="iNetworkId" id="iNetworkId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="n" loop=$network_arr}
                                                <option value="{$network_arr[n].iNetworkId}" {if $network_arr[n].iNetworkId eq $rs_data[0].iNetworkId}selected{/if}>{$network_arr[n].vName|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please select network</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="vCircuitName">Circuit Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="vCircuitName" name="vCircuitName" placeholder="Circuit name" value="{$rs_data[0].vCircuitName}" required>
                                        <div class="invalid-feedback">
                                            Please enter circuit name
                                        </div>
                                    </div>
                                </div>
                                {if $mode eq 'Update'}
                                <div class="col-7">
                                    <div class="col-12 mb-3">
                                        <h5 class="text-dark font-weight-bold">Premise Circuit</h5>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <table class="table layout-primary" border="1">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">Premise Circuit</th>
                                                    <th scope="col" class="text-center">Premise</th>
                                                    <th scope="col">Address</th>
                                                    <th scope="col" class="text-center">Premise Circuit Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class ="premise_data">
                                                {if $cnt_premise_circuit  > 0}
                                                {section name="p" loop=$premise_circuit_arr}
                                                <tr>
                                                    <td class="text-center">{$premise_circuit_arr[p].iPremiseCircuitId}</td>
                                                    <td class="text-center">{$premise_circuit_arr[p].iPremiseId}</td>
                                                    <td>{$premise_circuit_arr[p].vAddress}</td>
                                                    <td class="text-center">{$premise_circuit_arr[p].vStatus}</td>
                                                </tr>
                                                {/section}
                                                {else}
                                                <tr>
                                                    <td colspan="6" class="text-center"><b>No Records Found!</b></td>
                                                </tr>
                                                {/if}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row"> 
                        <div class="form-row mt-3">
                            <div class="col-12 ml-3 float-right">
                                <button type="submit" class="btn btn-primary" id="save_data" value="submit">Save </button>  
                            	<img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;">   
                                <button type="button" onclick="location.href = site_url+'circuit/circuit_list';" class="btn btn-secondary ml-2"> Close </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>
<script src="assets/js/app_js/circuit_add.js"></script>
<script type="text/javascript">
var access_group_var_add= '{$access_group_var_add}';
var access_group_var_CSV= '{$access_group_var_CSV}';
var mode = '{$mode}';
</script>




