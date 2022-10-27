<div class="card card-body">
    <form id="advfrm" name="advfrm" class="sorder_search_form">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputEmail4">Premise Name</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="vSPremiseNameDD" id="vSPremiseNameDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vSPremiseName" id="vSPremiseName" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Address</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="vSAddressFilterOpDD" id="vSAddressFilterOpDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vSAddress" id="vSAddress" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">City</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="vSCityFilterOpDD" id="vSCityFilterOpDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vSCity" id="vSCity" value="" class="form-control">
                    </div>
                </div>
            </div>
            
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputEmail4">State</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="vSStateFilterOpDD" id="vSStateFilterOpDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vSState" id="vSState" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-2">
                <label for="inputEmail4">Zip Code</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="vSZipCode" id="vSZipCode" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-2">
                <label for="inputEmail4">Zone</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSZoneId" id="iSZoneId" class="form-control col-md-12">
                            <option value="">-- Select --</option> {section name="z" loop=$rs_zone} <option value="{$rs_zone[z].iZoneId}">{$rs_zone[z].vZoneName|gen_strip_slash}</option> {/section}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Service Order</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSServiceOrderId" id="iSServiceOrderId" class="form-control col-md-12">
                            <option value="">-- Select --</option> 
                            {section name="s" loop=$rs_so} 
                            <option value="{$rs_so[s].iServiceOrderId}">{$rs_so[s].vSODetails|gen_strip_slash}</option> 
                            {/section}
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputEmail4">WorkOrder Project</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="vSWOProjectDD" id="vSWOProjectDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vSWOProject" id="vSWOProject" value="" class="form-control">
                    </div>
                </div>
            </div> 
            <div class="form-group col-md-2">
                <label for="inputEmail4">Requestor</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSRequestorId" id="iSRequestorId" class="form-control">
                            <option value="">-- Select --</option> 
                            {section name="u" loop=$rs_user} 
                            <option value="{$rs_user[u].iUserId}">{$rs_user[u].vName|gen_strip_slash}</option> 
                            {/section}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-2">
                <label for="inputEmail4">Assigned To</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSAssignedToId" id="iSAssignedToId" class="form-control">
                            <option value="">-- Select --</option> 
                            {section name="u" loop=$rs_user} 
                            <option value="{$rs_user[u].iUserId}">{$rs_user[u].vName|gen_strip_slash}</option> 
                            {/section}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Status</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSWOSId" id="iSWOSId" class="form-control">
                            <option value="">-- Select --</option> 
                            {section name="s" loop=$rs_status} 
                            <option value="{$rs_status[s].iWOSId}">{$rs_status[s].vStatus|gen_strip_slash}</option> 
                            {/section}
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            
        </div>
        <button type="button" id="AdvSearchSubmit" name="AdvSearchSubmit" class="btn btn-outline-warning fas fa-search"></button>
        <button type="button" class="btn btn-outline-danger fas fa-times" aria-label="Close" id="AdvSearchReset" name="AdvSearchReset"></button>
    </form>
</div>