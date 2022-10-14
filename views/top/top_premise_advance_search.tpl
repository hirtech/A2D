<div class="card card-body">
    <form id="advfrm" name="advfrm" class="site_search_form">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="siteId">Premise Id</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="siteId" id="siteId" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="">Premise Name</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="SiteFilterOpDD" id="SiteFilterOpDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="siteName" id="siteName" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="iSTypeId">Premise Type</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSTypeId" id="iSTypeId" class="form-control">
                            <option value="">-- Select --</option> {section name="z" loop=$rs_site_type} <option value="{$rs_site_type[z].iSTypeId}">{$rs_site_type[z].vTypeName|gen_strip_slash}</option> {/section}
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputEmail4">Premise Sub Type</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSSTypeId" id="iSSTypeId" class="form-control col-md-12">
                            <option value="">-- Select --</option> {section name="z" loop=$rs_site_sub_type} <option value="{$rs_site_sub_type[z].iSSTypeId}">{$rs_site_sub_type[z].vSubTypeName|gen_strip_slash}</option> {/section}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Address</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="AddressFilterOpDD" id="AddressFilterOpDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vAddress" id="vAddress" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">City</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="CityFilterOpDD" id="CityFilterOpDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vCity" id="vCity" value="" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputEmail4">State</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="StateFilterOpDD" id="StateFilterOpDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vState" id="vState" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Zone</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iZoneId" id="iZoneId" class="form-control col-md-12">
                            <option value="">-- Select --</option> {section name="z" loop=$rs_zone} <option value="{$rs_zone[z].iZoneId}">{$rs_zone[z].vZoneName|gen_strip_slash}</option> {/section}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Network</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iNetworkId" id="iNetworkId" class="form-control col-md-12">
                            <option value="">-- Select --</option> {section name="n" loop=$rs_ntwork} <option value="{$rs_ntwork[n].iNetworkId}">{$rs_ntwork[n].vName|gen_strip_slash}</option> {/section}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Status</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="status" id="status" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" id="AdvSearchSubmit" name="AdvSearchSubmit" class="btn btn-outline-warning fas fa-search"></button>
        <button type="button" class="btn btn-outline-danger fas fa-times" aria-label="Close" id="AdvSearchReset" name="AdvSearchReset"></button>
    </form>
</div>