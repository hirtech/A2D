<div class="card card-body">
    <form id="advfrm" name="advfrm" class="zone_search_form">
        <div class="form-row">
			<div class="form-group col-md-4">
                <label for="premiseId">Fiber Zone Id</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="iZoneId" id="iZoneId" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="">Fiber Zone Name</label>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select name="vZoneNameFilterOpDD" id="vZoneNameFilterOpDD" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Begins">Begins With</option>
                            <option value="Ends">Ends With</option>
                            <option value="Contains" selected>Contains</option>
                            <option value="Exactly">Exactly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" name="vZoneName" id="vZoneName" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="">Is Fiber Zone boundary uploaded?</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="isFile" id="isFile" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" id="AdvSearchSubmit" name="AdvSearchSubmit" class="btn btn-outline-warning fas fa-search"></button>
        <button type="button" class="btn btn-outline-danger fas fa-times" aria-label="Close" id="AdvSearchReset" name="AdvSearchReset"></button>
    </form>
</div>