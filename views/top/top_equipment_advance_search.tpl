<div class="card card-body">
    <form id="advfrm" name="advfrm" class="sorder_search_form">
        <div class="form-row">
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
            <div class="form-group col-md-4">
                <label for="inputEmail4">Serial Number</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="vSerialNumber" id="vSerialNumber" value="" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputEmail4">MAC Address</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="vMACAddress" id="vMACAddress" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">IP Address</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="vIPAddress" id="vIPAddress" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">Size</label>
                        <input type="text" name="vSize" id="vSize" value="" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Weight</label>
                        <input type="text" name="vWeight" id="vWeight" value="" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <button type="button" id="AdvSearchSubmit" name="AdvSearchSubmit" class="btn btn-outline-warning fas fa-search"></button>
        <button type="button" class="btn btn-outline-danger fas fa-times" aria-label="Close" id="AdvSearchReset" name="AdvSearchReset"></button>
    </form>
</div>