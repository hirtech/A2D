<div class="card card-body">
	<form id="advfrm" name="advfrm" class="site_search_form">
		<div class="form-row">
			<div class="form-group col-md-4">
				<label for="fiberInquiryId"> Id</label>
				<div class="form-row">
					<div class="form-group col-md-12">
						<input type="text" name="fiberInquiryId" id="fiberInquiryId" value="" class="form-control"> </div>
				</div>
			</div>
			<div class="form-group col-md-4">
				<label for="contactNameFilterOpDD">Name</label>
				<div class="form-row">
					<div class="form-group col-md-6">
						<select name="contactNameFilterOpDD" id="contactNameFilterOpDD" class="form-control">
							<option value="">-- Select --</option>
							<option value="Begins">Begins With</option>
							<option value="Ends">Ends With</option>
							<option value="Contains" selected>Contains</option>
							<option value="Exactly">Exactly</option>
						</select>
					</div>
					<div class="form-group col-md-6">
						<input type="text" name="contactName" id="contactName" value="" class="form-control"> </div>
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
						<input type="text" name="vAddress" id="vAddress" value="" class="form-control"> </div>
				</div>
			</div>
		</div>
		<div class="form-row">
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
						<input type="text" name="vCity" id="vCity" value="" class="form-control"> </div>
				</div>
			</div>
			<div class="form-group col-md-4">
				<label for="CountyFilterOpDD">County</label>
				<div class="form-row">
					<div class="form-group col-md-6">
						<select name="CountyFilterOpDD" id="CountyFilterOpDD" class="form-control">
							<option value="">-- Select --</option>
							<option value="Begins">Begins With</option>
							<option value="Ends">Ends With</option>
							<option value="Contains" selected>Contains</option>
							<option value="Exactly">Exactly</option>
						</select>
					</div>
					<div class="form-group col-md-6">
						<input type="text" name="vCounty" id="vCounty" value="" class="form-control"> </div>
				</div>
			</div>
			<div class="form-group col-md-4">
				<label for="inputEmail4">Zipcode</label>
				<div class="form-row">
					<div class="form-group col-md-6">
						<select name="ZipcodeFilterOpDD" id="ZipcodeFilterOpDD" class="form-control">
							<option value="">-- Select --</option>
							<option value="Begins">Begins With</option>
							<option value="Ends">Ends With</option>
							<option value="Contains" selected>Contains</option>
							<option value="Exactly">Exactly</option>
						</select>
					</div>
					<div class="form-group col-md-6">
						<input type="text" name="vZipcode" id="vZipcode" value="" class="form-control"> </div>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-4">
				<label for="ZoneNameFilterOpDD">Zone Name</label>
				<div class="form-row">
					<div class="form-group col-md-6">
						<select name="ZoneNameFilterOpDD" id="ZoneNameFilterOpDD" class="form-control">
							<option value="">-- Select --</option>
							<option value="Begins">Begins With</option>
							<option value="Ends">Ends With</option>
							<option value="Contains" selected>Contains</option>
							<option value="Exactly">Exactly</option>
						</select>
					</div>
					<div class="form-group col-md-6">
						<input type="text" name="zoneName" id="zoneName" value="" class="form-control"> </div>
				</div>
			</div>
			<div class="form-group col-md-4">
				<label for="NetworkFilterOpDD">Network</label>
				<div class="form-row">
					<div class="form-group col-md-6">
						<select name="NetworkFilterOpDD" id="NetworkFilterOpDD" class="form-control">
							<option value="">-- Select --</option>
							<option value="Begins">Begins With</option>
							<option value="Ends">Ends With</option>
							<option value="Contains" selected>Contains</option>
							<option value="Exactly">Exactly</option>
						</select>
					</div>
					<div class="form-group col-md-6">
						<input type="text" name="networkName" id="networkName" value="" class="form-control"> </div>
				</div>
			</div>
			<div class="form-group col-md-4">
				<label for="NetworkFilterOpDD">Suite/Apt/Unit#</label>
				<div class="form-row">
					<div class="form-group col-md-6">
						<select name="suitAptUnitFilterOpDD" id="suitAptUnitFilterOpDD" class="form-control">
							<option value="">-- Select --</option>
							<option value="Begins">Begins With</option>
							<option value="Ends">Ends With</option>
							<option value="Contains" selected>Contains</option>
							<option value="Exactly">Exactly</option>
						</select>
					</div>
					<div class="form-group col-md-6">
						<input type="text" name="suitAptUnit" id="suitAptUnit" value="" class="form-control"> </div>
				</div>
			</div>
		</div>
		<button type="button" id="AdvSearchSubmit" name="AdvSearchSubmit" class="btn btn-outline-warning fas fa-search"></button>
		<button type="button" class="btn btn-outline-danger fas fa-times" aria-label="Close" id="AdvSearchReset" name="AdvSearchReset"></button>
	</form>
</div>