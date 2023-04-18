<div class="row  no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$mode} {$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}network/network_list">Network List</a></li>
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
                            <input type="hidden" name="iNetworkId" id="iNetworkId" value="{$rs_data[0]['iNetworkId']}">
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="col-12 mb-3">
                                        <label for="vName">Name  <span class="text-danger">*</span></label>
                                        <input type="text" id="vName" name="vName" value="{$rs_data[0]['vName']}" class="form-control" required>
                                        <div class="invalid-feedback"> Please enter name</div>
                                    </div>
                                    {if $mode neq 'Update'}
                                    <div class="col-12 mb-3">
                                        <label for="vFile">File <span class="text-danger"> *</span></label>
                            				 <input type="file" class="d-inline-flex form-control-file" id="vFile" name="vFile" {if $mode neq 'Update'} required {/if}>
                            				 
                                        <div class="invalid-feedback"> Please select file</div>
                                        &nbsp;&nbsp;<span class="text-danger"> [valid extension file : kml,kmz]</span>
                                    </div>
                                    {elseif $mode eq 'Update' &&  $rs_data[0].file_url neq ''}
                                    <div class="col-12 mb-3">
                                        <label >File</label>
                                        &nbsp;&nbsp;<a href="{$rs_data[0].file_url}" title="Download"><i class="fa fa-download"></i>&nbsp;&nbsp;Download</a>
                                    </div>
                                    {/if}

                                    <input type="hidden" name="vFile_old" id="vFile_old" value="{$rs_data[0]['vFile']}">
                            			
                                    <div class="col-12 mb-3">
                                        <label for="iStatus">Status</label>
                                        <select name="iStatus" id="iStatus" class="form-control">
                                            <option value="1" {if $rs_data[0]['iStatus'] eq '1' } selected {/if}>Active</option>
                                            <option value="0" {if $rs_data[0]['iStatus'] eq '0' } selected {/if}>Inactive</option>
                                            <option value="2" {if $rs_data[0]['iStatus'] eq '2' } selected {/if}>Created</option>
                                            <option value="3" {if $rs_data[0]['iStatus'] eq '3' } selected {/if}>Planning</option>
                                        </select>
                                	</div>
                                </div>
                                <div class="col-6 ">
                                     <div id="map" style="width: 500px; height: 500px"></div>
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
                        <div class="form-row mt-3">
                            <div class="col-12 ml-3 float-right">
                                <button type="submit" class="btn btn-primary" id="save_data" value="submit">Save </button>  
                            	<img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;">   
                                <button type="button" onclick="location.href = site_url+'network/network_list';" class="btn btn-secondary ml-2"> Close </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<script type="text/javascript">
var access_group_var_edit= '{$access_group_var_edit}';
var mode = '{$mode}';
let map;
$(document).ready(function() {
    $('select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
          width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
    });
});
</script>
{if $mode eq 'Update'}
<script src="https://maps.googleapis.com/maps/api/js?key={$GOOGLE_GEOCODE_API_KEY}&libraries=geometry,drawing,places,visualization"></script>
<script type="text/javascript">
    $(window).on('load',function(){
        setTimeout(function(){
            initMap();
        }, 1000);
    });
</script>
{/if}

<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>
<script src="assets/js/app_js/network_add.js"></script>

