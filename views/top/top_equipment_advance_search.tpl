<div class="card card-body">
    <form id="advfrm" name="advfrm" class="sorder_search_form">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputEmail4">Equipment Model</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSEquipmentModelId" id="iSEquipmentModelId" class="form-control col-md-12">
                            <option value="">-- Select --</option>{section name="m" loop=$rs_model}
                            <option value="{$rs_model[m].iEquipmentModelId}">{$rs_model[m].vModelName|gen_strip_slash}</option>{/section}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Material</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSMaterialId" id="iSMaterialId" class="form-control col-md-12">
                            <option value="">-- Select --</option>
                            {section name="m" loop=$rs_material}
                            <option value="{$rs_material[m].iMaterialId}">{$rs_material[m].vMaterial|gen_strip_slash}</option>
                            {/section}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Power Type</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSPowerId" id="iSPowerId" class="form-control col-md-12">
                            <option value="">-- Select --</option>
                            {section name="p" loop=$rs_power}
                            <option value="{$rs_power[p].iPowerId}">{$rs_power[p].vPower|gen_strip_slash}</option>
                            {/section}
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputEmail4">Grounded</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSGrounded" id="iSGrounded" class="form-control col-md-12">
                            <option value="">-- Select --</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="iSPremiseId">Premise Id</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="iSPremiseId" id="iSPremiseId" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="">Premise Name</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="PremiseFilterOpDD" id="PremiseFilterOpDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vPremiseName" id="vPremiseName" value="" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputEmail4">Install Type</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSInstallTypeId" id="iSInstallTypeId" class="form-control col-md-12">
                            <option value="">-- Select --</option>
                            {section name="i" loop=$rs_itype}
                                <option value="{$rs_itype[i].iInstallTypeId}" {if $rs_itype[i].iInstallTypeId eq $rs_equipment[0].iInstallTypeId}selected{/if}>{$rs_itype[i].vInstallType|gen_strip_slash}</option>
                            {/section}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Link Type</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSLinkTypeId" id="iSLinkTypeId" class="form-control col-md-12">
                            <option value="">-- Select --</option>
                            {section name="l" loop=$rs_ltype}
                                <option value="{$rs_ltype[l].iLinkTypeId}" {if $rs_ltype[l].iLinkTypeId eq $rs_equipment[0].iLinkTypeId}selected{/if}>{$rs_ltype[l].vLinkType|gen_strip_slash}</option>
                            {/section}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Operation Status</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSOperationalStatusId" id="iSOperationalStatusId" class="form-control col-md-12">
                            <option value="">-- Select --</option>
                            {section name="o" loop=$rs_ostatus}
                                <option value="{$rs_ostatus[o].iOperationalStatusId}" {if $rs_ostatus[o].iOperationalStatusId eq $rs_equipment[0].iOperationalStatusId}selected{/if}>{$rs_ostatus[o].vOperationalStatus|gen_strip_slash}</option>
                            {/section}
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" id="AdvSearchSubmit" name="AdvSearchSubmit" class="btn btn-outline-warning fas fa-search"></button>
        <button type="button" class="btn btn-outline-danger fas fa-times" aria-label="Close" id="AdvSearchReset" name="AdvSearchReset"></button>
    </form>
</div>