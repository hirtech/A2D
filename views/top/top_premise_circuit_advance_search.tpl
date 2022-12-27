<div class="card card-body">
    <form id="advfrm" name="advfrm" class="pc_search_form">
        <div class="form-row">
			<div class="form-group col-md-4">
                <label for="premiseId">Premise Circuit Id</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="premiseCircuitId" id="premiseCircuitId" value="" class="form-control">
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
                <label for="premiseId">Workorder Id</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="workorderId" id="workorderId" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="inputEmail4">Workorder Type</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="workorderTypeId" id="workorderTypeId" class="form-control col-md-12">
                            <option value="">Select</option>
							{section name="t" loop=$rs_wotype}
								<option value="{$rs_wotype[t].iWOTId}">{$rs_wotype[t].vType|gen_strip_slash}</option>
							{/section}
							</option>
                        </select>
                    </div>
                </div>
            </div>
			<div class="form-group col-md-4">
                <label for="inputEmail4">Circuit</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="circuitId" id="circuitId" class="form-control col-md-12">
                            <option value="">Select</option>
							{section name="t" loop=$rs_circuit}
								<option value="{$rs_circuit[t].iCircuitId}">{$rs_circuit[t].vCircuitName|gen_strip_slash}</option>
							{/section}
							</option>
                        </select>
                    </div>
                </div>
            </div>
           
        </div>
        <button type="button" id="AdvSearchSubmit" name="AdvSearchSubmit" class="btn btn-outline-warning fas fa-search"></button>
        <button type="button" class="btn btn-outline-danger fas fa-times" aria-label="Close" id="AdvSearchReset" name="AdvSearchReset"></button>
    </form>
</div>