<div class="card card-body">
    <form id="advfrm" name="advfrm" class="user_search_form">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputEmail4">Name</label>
          <div class="form-row">
            <div class="form-group col-md-6">
              <select name="vNameDD" id="vNameDD" class="form-control">
                <option value="">-- Select --</option>
                <option value="Begins">Begins With</option>
                <option value="Ends">Ends With</option>
                <option value="Contains" selected>Contains</option>
                <option value="Exactly">Exactly</option>
              </select>
            </div>
             <div class="form-group col-md-6">
              <input type="text" name="vName" id="vName" class="form-control" placeholder="Name">
            </div>
          </div>
        </div>
        <div class="form-group col-md-6">
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
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="inputEmail4">Username</label>
          <div class="form-row">
            <div class="form-group col-md-6">
              <select name="vUsernameDD" id="vUsernameDD" class="form-control">
                <option value="">-- Select --</option>
                <option value="Begins">Begins With</option>
                <option value="Ends">Ends With</option>
                <option value="Contains" selected>Contains</option>
                <option value="Exactly">Exactly</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <input type="text" name="vUsername" id="vUsername" class="form-control" placeholder="Username">
            </div>
          </div>
        </div>
        <div class="form-group col-md-4">
          <label for="inputEmail4">Department</label>
          <div class="form-row">
            <div class="form-group col-md-12">
              <select name="iDepartmentId" id="iDepartmentId" class="form-control">
                <option value="">-- Select --</option>
                {section name="d" loop=$rs_department}
                  <option value="{$rs_department[d].iDepartmentId}">{$rs_department[d].vDepartment|gen_strip_slash}</option>
                {/section}
              </select>
            </div>
          </div>
        </div>
        <div class="form-group col-md-4">
          <label for="inputEmail4">Access Group:</label>
          <div class="form-row">
            <div class="form-group col-md-12">
              <select name="iAGroupId" id="iAGroupId" class="form-control">
                <option value="">-- Select --</option>
                {section name="a" loop=$rs_agroup}
                <option value="{$rs_agroup[a].iAGroupId}">{$rs_agroup[a].vAccessGroup|gen_strip_slash}</option>
                {/section}
              </select>
            </div>
          </div>
        </div>
      </div>
      
      <button type="button" id="AdvSearchSubmit" name="AdvSearchSubmit" class="btn btn-outline-warning fas fa-search" title="Search"></button>
      <button type="button" class="btn btn-outline-danger fas fa-times  ml-1" aria-label="Close" id="AdvSearchReset" name="AdvSearchReset" title="Reset">
      </button>
    </form>
</div>