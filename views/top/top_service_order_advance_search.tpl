<div class="card card-body">
    <form id="advfrm" name="advfrm" class="sorder_search_form">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="">Contact Name</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="vSContactNameDD" id="vContactNameDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vSContactName" id="vSContactName" value="" class="form-control">
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
            <div class="form-group col-md-2">
                <label for="inputEmail4">ID</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="iServiceOrderId" id="iServiceOrderId" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-2">
                <label for="inputEmail4">Master MSA#</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="vMasterMSA" id="vMasterMSA" value="" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputEmail4">SalesRep Name</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="vSSalesRepNameDD" id="vSSalesRepNameDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vSSalesRepName" id="vSSalesRepName" value="" class="form-control">
                    </div>
                </div>
            </div> 
            <div class="form-group col-md-4">
                <label for="inputEmail4">SalesRep Email</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="vSSalesRepEmailDD" id="vSSalesRepEmailDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vSSalesRepEmail" id="vSSalesRepEmail" value="" class="form-control">
                    </div>
                </div>
            </div>  
            <div class="form-group col-md-4">
                <label for="inputEmail4">Service Order</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="vServiceOrder" id="vServiceOrder" value="" class="form-control">
                    </div>
                </div>
            </div> 
        </div>
        <button type="button" id="AdvSearchSubmit" name="AdvSearchSubmit" class="btn btn-outline-warning fas fa-search"></button>
        <button type="button" class="btn btn-outline-danger fas fa-times" aria-label="Close" id="AdvSearchReset" name="AdvSearchReset"></button>
    </form>
</div>