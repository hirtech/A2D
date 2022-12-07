<a class="btn btn-primary d-none" id="contact_modalbox" data-toggle="modal" href="#contactmodal">launch model</a>
<div class="modal fade " id="contactmodal" tabindex="-1" role="dialog" aria-labelledby="contactmodal1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cntmodaltitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closestbox">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
            	<form  id="contactfrmadd" name="contactfrmadd" action="" class="form-horizontal needs-validation" method="post" novalidate>
            		<input type="hidden" name="mode" id="cnt_mode" value="">
                    <input type="hidden" name="iCId" id="cid" value=""> 
                    <input type="hidden" name="iPremiseId" id="premiseid" value="" />
                    <input type="hidden" name="referer" id="referer" value="" />
            		<div class="form-group row">
            			<label class="col-sm-3" for="salutation">Salutation  <span class="text-danger"> *</span></label>
            			<div class="col-sm-6">
                		        <select class="form-control" id="salutation" name="vSalutation" required>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Mrs.">Mrs.</option>
                                </select>
                			<div class="invalid-feedback">Please select salutation</div>
                		</div>
                    </div>
                    <div class="form-group row"> 
            			<label  class="col-sm-3" for="firstName">First Name<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="firstName" placeholder="First Name" value="" name="vFirstName" required>
                            <div class="invalid-feedback"> Please enter first name</div>
                        </div>
                    </div>
                    <div class="form-group row"> 
                        <label  class="col-sm-3" for="lastName">Last Name<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="lastName" placeholder="Last Name" value="" name="vLastName" required>
                            <div class="invalid-feedback"> Please enter last name</div>
                        </div>
            		</div>
                    <div class="form-group row"> 
                        <label  class="col-sm-3" for="primaryPhone">Primary Phone<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" data-masked="" data-inputmask="'mask': '(999)-(999)-(9999)'" placeholder="Primary Phone" class="form-control" required id="primaryPhone" value="" name="vPrimaryPhone" >
                            <span>Ex. (000) - (000) - (0000)</span>
                            <div class="invalid-feedback"> Please enter primary phone</div>
                            <div id="primary_msg" class="invalid-feedback errormsg"></div>
                        </div>
                    </div>
            		<div class="form-group row">
            			 <label  class="col-sm-3" for="company">Company</label>
            			<div class="col-sm-6">
                            <input type="text" class="form-control" id="company" placeholder="Company Name" value="" name="vCompany">
                		</div>
            		</div>
            		<div class="form-group row">
            			<label  class="col-sm-3" for="position">Position</label>
            			<div class="col-sm-6">
                           <input type="text" class="form-control" id="position" placeholder="Position" value="" name="vPosition">
                		</div>
            		</div>
            		<div class="form-group row">
            			 <label  class="col-sm-3" for="email">Email</label>
            			 <div class="col-sm-6">
                			 <input type="text" class="form-control" id="email" placeholder="Email" value="" name="vEmail">
                            <div id="email_msg" class="invalid-feedback errormsg"></div>
                		</div>
                    </div>
                    <div class="form-group row">
        				<label  class="col-sm-3" for="notes">Notes</label>
        				<div class="col-sm-6">
        					<textarea class="form-control" id="notes" name="tNotes"></textarea>
        				</div>
            		</div>
            		<div class="form-group row">
            			<label  class="col-sm-3" for="status">Status</label>
            			 <div class="col-sm-6">
            			 	<input type="checkbox" id="status" name="iStatus" value="1" data-toggle="toggle" data-on="Active" data-onstyle="success" data-off="Inactive" data-offstyle="danger"  data-width="100" data-width="26" >
                             <div class="invalid-feedback">Please select status.</div>
            			 </div>
            		</div>
            	</form>
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-secondary btn_close_modal" data-dismiss="modal" id="btn_close_modal">Close</button>
                 <img src="assets/images/loading-small.gif" id="cont_save_loading" border="0" style="display:none;"> 
                <input type="submit" class="btn btn-primary" id="cont_save_data" value="Save" name="cont_save_data">
            </div>
        </div>
    </div>
</div>

<!-- START: Page Vendor JS-->
<script src="assets/vendors/jquery-inputmask/jquery.inputmask.min.js"></script>
<!-- END: Page Vendor JS-->
