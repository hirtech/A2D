<div class="card card-body">
    <form id="advfrm" name="advfrm" class="event_search_form">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputEmail4">Campaign By</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSCampaignBy" id="iSCampaignBy" class="form-control">
                            <option value="">-- Select --</option>
                            {foreach from=$EVENT_CAMPAIGN_BY_ARR key=k item=v}
                               <option value="{$k}">{$v}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Status</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSStatus" id="iSStatus" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="1">Not Started</option>
                            <option value="2">In Progress</option>
                            <option value="3">Completed</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        {*
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
                <label for="inputEmail4">Zone Name</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="vSZoneNameDD" id="vSZoneNameDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vSZoneName" id="vSZoneName" value="" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputEmail4">Zipcode</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="vSZipcodeDD" id="vSZipcodeDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vSZipcode" id="vSZipcode" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">City</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="vSCityDD" id="vSCityDD" class="form-control">
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
            <div class="form-group col-md-4">
                <label for="inputEmail4">Network</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="vSNetworkDD" id="vSNetworkDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vSNetwork" id="vSNetwork" value="" class="form-control">
                    </div>
                </div>
            </div>
        </div>*}
        <button type="button" id="AdvSearchSubmit" name="AdvSearchSubmit" class="btn btn-outline-warning fas fa-search"></button>
        <button type="button" class="btn btn-outline-danger fas fa-times" aria-label="Close" id="AdvSearchReset" name="AdvSearchReset"></button>
    </form>
</div>