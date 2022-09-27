<div class="card card-body">
    <form id="advfrm" name="advfrm" class="site_search_form">
      	<div class="form-row">
      		 <div class="form-group col-md-4">
	          <label for="srId"> Id</label>
	          <div class="form-row">
	          	<div class="form-group col-md-12">
					<input type="text" name="srId" id="srId" value="" class="form-control" >
				</div>
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
					<input type="text" name="contactName" id="contactName" value="" class="form-control" >
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
						<input type="text" name="vAddress" id="vAddress" value="" class="form-control" >
		            </div>
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
					<input type="text" name="vCity" id="vCity" value="" class="form-control" >
	            </div>
	          </div>
	        </div>
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
					<input type="text" name="vState" id="vState" value="" class="form-control" >
	            </div>
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
					<input type="text" name="vCounty" id="vCounty" value="" class="form-control" >
	            </div>
	          </div>
	        </div>
      	</div>
      	<div class="form-row">
      		<div class="form-group col-md-4">
	          <label for="AssignToFilterOpDD">Assign To</label>
	          <div class="form-row">
	            <div class="form-group col-md-6">
	              <select name="AssignToFilterOpDD" id="AssignToFilterOpDD" class="form-control">
	                <option value="">-- Select --</option>
	                <option value="Begins">Begins With</option>
	                <option value="Ends">Ends With</option>
	                <option value="Contains" selected>Contains</option>
	                <option value="Exactly">Exactly</option>
	              </select>
	            </div>
	            <div class="form-group col-md-6">
					<input type="text" name="assignTo" id="assignTo" value="" class="form-control" >
	            </div>
	          </div>
	        </div>
	        <div class="form-group col-md-4">
	          <label for="srreqType">Request Type</label>
	          <div class="form-row">
	          	<div class="form-group col-md-12">
	               <select name="srreqType" id="srreqType" class="form-control">
						<option value="">-- Select --</option>
						<option value="CR">Carcass Removal</option>
						<option value="MIT">Mosquito Inspection/Treatment</option>
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
		                <option value="2">Assigned</option>
		                <option value="4">Complete</option>
		                <option value="1">Draft</option>
		                <option value="3">Review</option>
		              </select>
		            </div>
		        </div>
	        </div>
      	</div>
      <button type="button" id="AdvSearchSubmit" name="AdvSearchSubmit" class="btn btn-outline-warning fas fa-search">
      </button>
      <button type="button" class="btn btn-outline-danger fas fa-times" aria-label="Close" id="AdvSearchReset" name="AdvSearchReset">
      </button>
    </form>
</div>