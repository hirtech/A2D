<div class="card card-body">
    <form id="advfrm" name="advfrm" class="contact_search_form">
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="inputEmail4">Salutation</label>
          <div class="form-row">
              <select name="vSalutation" id="vSalutationDD" class="form-control">
                <option value="">-- Select --</option>
                <option value="Mr.">Mr.</option>
                <option value="Mrs.">Mrs.</option>
              </select>
            </div>
          </div>
        <div class="form-group col-md-4">
          <label for="inputEmail4">First Name</label>
          <div class="form-row">
            <div class="form-group col-md-6">
              <select name="vFirstNameDD" id="vFirstNameDD" class="form-control">
                <option value="">-- Select --</option>
                <option value="Begins">Begins With</option>
                <option value="Ends">Ends With</option>
                <option value="Contains" selected>Contains</option>
                <option value="Exactly">Exactly</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <input type="text" name="vFirstName" id="vFirstName" class="form-control" placeholder="First name">
            </div>
          </div>
        </div>
        <div class="form-group col-md-4">
          <label for="inputEmail4">Last Name</label>
          <div class="form-row">
            <div class="form-group col-md-6">
              <select name="vLastNameDD" id="vLastNameDD" class="form-control">
                <option value="">-- Select --</option>
                <option value="Begins">Begins With</option>
                <option value="Ends">Ends With</option>
                <option value="Contains" selected>Contains</option>
                <option value="Exactly">Exactly</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <input type="text" name="vLastName" id="vLastName" class="form-control" placeholder="Last name">
            </div>
          </div>
        </div>

      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="inputEmail4">Email</label>
          <div class="form-row">
            <div class="form-group col-md-6">
              <select name="vEmailDD" id="vEmailDD" class="form-control">
                <option value="">-- Select --</option>
                <option value="Begins">Begins With</option>
                <option value="Ends">Ends With</option>
                <option value="Contains" selected>Contains</option>
                <option value="Exactly">Exactly</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <input type="text" name="vEmail" id="vEmail" class="form-control" placeholder="Email">
            </div>
          </div>
        </div>
        <div class="form-group col-md-4">
          <label for="inputEmail4">Company</label>
          <div class="form-row">
            <div class="form-group col-md-6">
              <select name="vCompanyDD" id="vCompanyDD" class="form-control">
                <option value="">-- Select --</option>
                <option value="Begins">Begins With</option>
                <option value="Ends">Ends With</option>
                <option value="Contains" selected>Contains</option>
                <option value="Exactly">Exactly</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <input type="text" name="vCompany" id="vCompany" class="form-control" placeholder="Company">
            </div>
          </div>
        </div>
        <div class="form-group col-md-4">
          <label for="inputEmail4">Position</label>
          <div class="form-row">
            <div class="form-group col-md-6">
              <select name="vPositionDD" id="vPositionDD" class="form-control">
                <option value="">-- Select --</option>
                <option value="Begins">Begins With</option>
                <option value="Ends">Ends With</option>
                <option value="Contains" selected>Contains</option>
                <option value="Exactly">Exactly</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <input type="text" name="vPosition" id="vPosition" class="form-control" placeholder="Position">
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