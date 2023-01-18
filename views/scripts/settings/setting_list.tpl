<div class="row  no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
        </div>
    </div>
</div>
<form name="frmadd" id="frmadd" method="post" action="" class="form-horizontal needs-validation"  novalidate  enctype="multipart/form-data">
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
         
            <div class="card-content">
                <div class="card-body">
                    <div class="row">                                           
                        <div class="col-12">
                            <input type="hidden" name="groupaction" value="groupaction">
                            <input type="hidden" name="mode" id="mode" value="Update">
                            <input type="hidden" name="iUserId" id="iUserId" value="{$rs_user[0].iUserId}"> 
                            <div class="col-12">
                                <form>
                                    {section name="i" loop=$num_totrec}
                                        {if $type_old neq $db_res[i].vConfigType}
                                        <div class="form-group row"><label for="Listing Record" class="col-sm-6 col-form-label"><strong>{$db_res[i].vConfigType}</strong></div>
                                        {/if}
                                        <div class="form-group row">
                                            {if $db_res[i].vDisplayType neq 'hidden'}
                                                <label for="Listing Record" class="col-sm-4 col-form-label">{$db_res[i].vDesc}</label>
                                            {/if}
                                            <div class="col-sm-8">
                                                {if $db_res[i].vDisplayType eq 'text'}
                                                    <input type="Text" name="{$db_res[i].vName}" size="66" value="{$db_res[i].vValue}" class="form-control" />
                                                {elseif $db_res[i].vDisplayType eq 'textarea'}
                                                    <textarea rows="5" cols="10" class="form-control" name="{$db_res[i].vName}">{$db_res[i].vValue|gen_strip_slash}</textarea>
                                                {elseif $db_res[i].vDisplayType eq 'checkbox'}
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" name="{$db_res[i].vName}" id="{$db_res[i].vName}" value="Y" {if $db_res[i].vValue eq 'Y'}checked{/if} class="custom-control-input" />
                                                    <label class="custom-control-label" for="{$db_res[i].vName}"></label>
                                                </div>
                                                {if $db_res[i].vDisplayType eq 'selectbox'}
                                                    {if $db_res[i].vSource eq 'List'}
                                                        {if $db_res[i].vSelectType eq 'Single'}                 
                                                            <Select class="form-control" name="{$db_res[i].vName}">
                                                            <option value="-9"><< Select {$db_res[i].vDesc} >></option>
                                                            {if $db_res[i].vSourceValue|is_array}
                                                                {assign var="opt" value=$db_res[i].vSourceValue}
                                                                {section name="o" loop=$opt}
                                                                <option value="{$opt[o].0}" {if $opt[o].0 eq $db_res[i].vValue}selected{/if}>{$opt[o].1}</option>
                                                                {/section}
                                                            {/if}
                                                            </select>
                                                        {/if}
                                                    {/if}
                                                {elseif $db_res[i].vDisplayType eq 'Query'}
                                                    <select class="form-control" name="{$db_res[i].vName}">
                                                        <option value="-9"><< Select {$db_res[i].vDesc} >></option>
                                                        {section name="p" loop=$nSource_Query}
                                                        <option {if $db_selectSource_rs[p][0] eq $db_res[i].vValue}selected{/if} value="{$db_selectSource_rs[p][0]}">{$db_selectSource_rs[p][1]}</option>
                                                        {/section}
                                                    </select>
                                                {elseif $db_res[i].vDisplayType eq 'hidden'}
                                                    <input type="hidden" name="{$db_res[i].vName}" size="66" value="{$db_res[i].vValue}" class="form-control" id="{$db_res[i].vName}" />
                                                {/if}
                                            </div>
                                        </div>
                                        {assign var="type_old" value=$db_res[i].vConfigType}
                                    {/section}
                                </form>
                            </div>
                            <input  type="hidden" name="no" value="{$cnt}" />
                            <p align="right"><font size="1" FACE="Verdana, Arial, Helvetica, sans-serif" color="black"></font></p>
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
                        <button type="submit" class="btn btn-primary ml-2 "  id="save_data"> Save </button> 
                            <img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;">  
                        <button type="button" onclick="location.href = site_url+'settings/setting_list';" class="btn  btn-secondary  ml-2" > Reset </button>
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
<script>
{literal}
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

{/literal}
</script>

