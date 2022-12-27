<div class="card card-body">
    <form id="advfrm" name="advfrm" class="awareness_search_form">
        <div class="form-row">
			<div class="form-group col-md-4">
                <label for="premiseId">Awareness Id</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="aId" id="aId" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="premiseId">Premise Id</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="premiseId" id="premiseId" value="" class="form-control">
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
        </div>
		<div class="form-row">
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
                <label for="premiseId">Fiber Inquiry Id</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="fiberInquiryId" id="fiberInquiryId" value="" class="form-control">
                    </div>
                </div>
            </div>
		</div>
        <button type="button" id="AdvSearchSubmit" name="AdvSearchSubmit" class="btn btn-outline-warning fas fa-search"></button>
        <button type="button" class="btn btn-outline-danger fas fa-times" aria-label="Close" id="AdvSearchReset" name="AdvSearchReset"></button>
    </form>
</div>