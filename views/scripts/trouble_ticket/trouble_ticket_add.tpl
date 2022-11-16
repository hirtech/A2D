<div class="row   no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}trouble_ticket/trouble_ticket_list">{$module_name} List</a></li>
                <li class="breadcrumb-item active">{$module_name}</li>
            </ol>
        </div>
    </div>
</div>
<form name="frmadd" id="frmadd" method="post" action="" class="form-horizontal needs-validation"  novalidate  enctype="multipart/form-data">
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
                            <input type="hidden" name="iTroubleTicketId" id="iTroubleTicketId" value="{$rs_trouble[0].iTroubleTicketId}">
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="col-12 mb-3">
                                        <label for="iAssignedToId">Assigned To <span class="text-danger">*</span></label>
                                        <select name="iAssignedToId" id="iAssignedToId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="u" loop=$rs_user}
                                            <option value="{$rs_user[u].iUserId}"{if $rs_trouble[0].iAssignedToId eq $rs_user[u].iUserId} selected {/if}>{$rs_user[u].vName|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please select assigned to</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iServiceOrderId">Service Order <span class="text-danger">*</span></label>
                                        <input type="text" name="vServiceOrder"  class="form-control " id="vServiceOrder" placeholder="Search Service Order Id or Service Order Name" value="{$rs_trouble[0].vSODisplay}"  required>
                                        <input type="hidden" id="search_iServiceOrderId" name="search_iServiceOrderId" value="{$rs_trouble[0].iServiceOrderId}"/>
                                        <img class="clear_address" src="assets/images/icon-delete.png" style="cursor:pointer;" onclick="return clear_serach_serviceorder();">
                                        <div class="invalid-feedback" id="errmsg_search_serviceorder">Please select service order</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iSeverity">Severity</label>
                                        <select name="iSeverity" id="iSeverity" class="form-control">
                                            <option value="">Select</option>
                                            <option value="1" {if $rs_trouble[0].iSeverity eq 1} selected {/if}>Low</option>
                                            <option value="2" {if $rs_trouble[0].iSeverity eq 2} selected {/if}>Medium</option>
                                            <option value="3" {if $rs_trouble[0].iSeverity eq 3} selected {/if}>High</option>
                                            <option value="4" {if $rs_trouble[0].iSeverity eq 4} selected {/if}>Critical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="col-12 mb-3">
                                        <label for="iStatus">Status</label>
                                        <select name="iStatus" id="iStatus" class="form-control">
                                            <option value="">Select</option>
                                            <option value="1" {if $rs_trouble[0].iStatus eq 1} selected {/if}>Not Started</option>
                                            <option value="2" {if $rs_trouble[0].iStatus eq 2} selected {/if}>In Progress</option>
                                            <option value="3" {if $rs_trouble[0].iStatus eq 3} selected {/if}>Completed</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="dCompletionDate">Completion Date</label>
                                        <input type="date" class="form-control" id="dCompletionDate" name="dCompletionDate" value="{$rs_trouble[0].dCompletionDate}"> 
                                        <div class="invalid-feedback" id="errormsg_completion_date"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="col-12 mb-3">
                                        <div class="card-header text-white bg-primary">
                                            <h5 class="card-title">Add Premise</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="col-6 mb-3">
                                        <label for="iPremiseId">&nbsp;&nbsp;</label>
                                        <input type="text" name="vPremiseName"  class="form-control " id="vPremiseName" placeholder="Search Premise Id or Premise Name or Address" value="">
                                        <input type="hidden" id="premise_length" name="premise_length" value="{$trouble_ticket_premise_count}" class="form-control">
                                        <img class="clear_address" src="{$site_url}assets/images/icon-delete.png" style="cursor:pointer;" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-12">
                            <div class="col-12 mb-3">
                                <div class="table-responsive">
                                    <table class="table layout-primary">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">Premise ID</th>
                                                <th scope="col">Premise Name</th>
                                                <th scope="col">Address</th>
                                                <th scope="col">Date - Trouble Start</th>
                                                <th scope="col">Date - Resolved</th>
                                                <th scope="col" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class ="premise_data">
                                            {if $trouble_ticket_premise_count  > 0}
                                            {section name="p" loop=$rs_trouble_premise}
                                            <tr>
                                                <td class="text-center"><input type="hidden" name="iPremiseId[]" value="{$rs_trouble_premise[p].iPremiseId}" class="form-control">{$rs_trouble_premise[p].iPremiseId}</td>
                                                <td>{$rs_trouble_premise[p].vPremiseName}</td>
                                                <td>{$rs_trouble_premise[p].vAddress}</td>
                                                <td><input type="date" class="form-control" name="dTroubleStartDate[]" value="{$rs_trouble_premise[p].dTroubleStartDate}"></td>
                                                <td><input type="date" class="form-control" name="dResolvedDate[]" value="{$rs_trouble_premise[p].dResolvedDate}"></td>
                                                <td class="text-center"><a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_row(this);"><i class="fa fa-window-close"></i></a></td>
                                            </tr>
                                            {/section}
                                            {else}
                                            <tr>
                                                <td colspan="6" class="text-center"><b>No Records Found!</b></td>
                                            </tr>
                                            {/if}
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-danger"><b>Note: Changes to this table will not be saved untill the "Save" button is clicked.</b></td>
                                            </tr>
                                        </tfoot>
                                    </table> 
                                </div>
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
                        <!-- <div class="w-sm-100 mr-auto"></div> -->
                        <button type="submit" class="btn btn-primary ml-2 " id="save_data"> Save </button> 
                            <img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;">  
                        <button type="button" onclick="location.href = site_url+'trouble_ticket/trouble_ticket_list';" class="btn  btn-secondary  ml-2" > Close </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 </form>
<!-- START: Page JS-->
<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>

<script src="assets/vendors/select2/js/select2.full.min.js"></script>
<script src="assets/vendors/typeahead/handlebars-v4.5.3.js"></script>
<script src="assets/vendors/typeahead/typeahead.bundle.js"></script>
<script type="text/javascript" src="assets/js/app_js/trouble_ticket_add.js"></script>
<!-- START: Page JS-->
<script type="text/javascript">
var mode = '{$mode}';
var dTodayDate = '{$dTodayDate}';
var trouble_ticket_premise_count = '{$trouble_ticket_premise_count}';
</script>
<style type="text/css">
    img.clear_address {
        position: absolute;
        right: 20px;
        top: 42px;
        width: 12px;
    }
</style>
