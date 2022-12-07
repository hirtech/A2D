<div class="row   no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}event/event_list">Event List</a></li>
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
                            <input type="hidden" name="iEventId" id="iEventId" value="{$rs_event[0].iEventId}">
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="col-12 mb-3">
                                        <label for="iEventTypeId">Event Type <span class="text-danger">*</span></label>
                                        <select name="iEventTypeId" id="iEventTypeId" class="form-control" required>
                                            <option value="">Select</option>
                                            {section name="e" loop=$rs_etype}
                                            <option value="{$rs_etype[e].iEventTypeId}"{if $rs_event[0].iEventTypeId eq $rs_etype[e].iEventTypeId} selected {/if}>{$rs_etype[e].vEventType|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback"> Please select equipment type</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iCampaignBy">Campaign By <span class="text-danger">*</span></label>
                                        <select name="iCampaignBy" id="iCampaignBy" class="form-control" required onchange="getCampaignCovarage(this.value)">
                                            <option value="">Select</option>
                                            {foreach from=$EVENT_CAMPAIGN_BY_ARR key=k item=v}
                                               <option value="{$k}"  {if $rs_event[0].iCampaignBy eq $k} selected {/if}>{$v}</option>
                                            {/foreach}
                                        </select>
                                        <div class="invalid-feedback"> Please select campaign by </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="iStatus">Status <span class="text-danger">*</span></label>
                                        <select name="iStatus" id="iStatus" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="1" {if $rs_event[0].iStatus eq 1} selected {/if}>Not Started</option>
                                            <option value="2" {if $rs_event[0].iStatus eq 2} selected {/if}>In Progress</option>
                                            <option value="3" {if $rs_event[0].iStatus eq 3} selected {/if}>Completed</option>
                                        </select>
                                        <div class="invalid-feedback"> Please select campaign by </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="dCompletedDate">Completed Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="dCompletedDate" name="dCompletedDate" value="{$dCompletedDate}" required> 
                                        <div class="invalid-feedback">Please select completed date</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="col-12 mb-3 premise_data">
                                        <label for="iPremiseId">Premise<span class="text-danger">*</span></label>
                                        <select name="iPremiseId[]" id="iPremiseId" class="form-control select" multiple>
                                            <option value="">Select</option>
                                            {section name="p" loop=$rs_premise}
                                            <option value="{$rs_premise[p].iPremiseId}"  {if $rs_premise[p].iPremiseId|in_array:$iPremiseIdArr}selected{/if}>{$rs_premise[p].vName|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback" id="errmsg_iPremiseId"> Please select premise</div>
                                    </div>
                                    <div class="col-12 mb-3 fiber_zone_data">
                                        <label for="iZoneId">Fiber Zone<span class="text-danger">*</span></label>
                                        <select name="iZoneId[]" id="iZoneId" class="form-control select" multiple>
                                            <option value="">Select</option>
                                            {section name="z" loop=$rs_zone}
                                            <option value="{$rs_zone[z].iZoneId}"  {if $rs_zone[z].iZoneId|in_array:$iZoneIdArr}selected{/if}>{$rs_zone[z].vZoneName|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback" id="errmsg_iZoneId"> Please select fiber zone</div>
                                    </div>
                                    <div class="col-12 mb-3 zipcode_data">
                                        <label for="iZipcode">Zipcode<span class="text-danger">*</span></label>
                                        <select name="iZipcode[]" id="iZipcode" class="form-control select" multiple>
                                            <option value="">Select</option>
                                            {section name="z" loop=$rs_zipcode}
                                            <option value="{$rs_zipcode[z].iZipcode}"  {if $rs_zipcode[z].iZipcode|in_array:$iZipcodeArr}selected{/if}>{$rs_zipcode[z].vZipcode|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback" id="errmsg_iZipcode"> Please select zipcode</div>
                                    </div>
                                    <div class="col-12 mb-3 city_data">
                                        <label for="iCityId">City<span class="text-danger">*</span></label>
                                        <select name="iCityId[]" id="iCityId" class="form-control select" multiple>
                                            <option value="">Select</option>
                                            {section name="c" loop=$rs_city}
                                            <option value="{$rs_city[c].iCityId}"  {if $rs_city[c].iCityId|in_array:$iCityIdArr}selected{/if}>{$rs_city[c].vCity|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback" id="errmsg_iCityId"> Please select city</div>
                                    </div>
                                    <div class="col-12 mb-3 network_data">
                                        <label for="iNetworkId">Network<span class="text-danger">*</span></label>
                                        <select name="iNetworkId[]" id="iNetworkId" class="form-control select" multiple>
                                            <option value="">Select</option>
                                            {section name="n" loop=$rs_ntwork}
                                            <option value="{$rs_ntwork[n].iNetworkId}"  {if $rs_ntwork[n].iNetworkId|in_array:$iNetworkIdArr}selected{/if}>{$rs_ntwork[n].vName|gen_strip_slash}</option>
                                            {/section}
                                        </select>
                                        <div class="invalid-feedback" id="errmsg_iNetworkId"> Please select network</div>
                                    </div>
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
                        <button type="button" onclick="location.href = site_url+'event/event_list';" class="btn  btn-secondary  ml-2" > Close </button>
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
<script type="text/javascript" src="assets/js/app_js/event_add.js"></script>
<!-- START: Page JS-->
<script type="text/javascript">
var mode = '{$mode}';
var iCampaignBy = '{$iCampaignBy}';
var dCompletedDate = '{$dCompletedDate}';
</script>
