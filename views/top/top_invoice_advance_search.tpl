<div class="card card-body">
    <form id="advfrm" name="advfrm" class="invoice_search_form">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="dSInvoiceDate">Invoice Date</label>
                <div class="form-row">
                    <div class="form-group col-md-11">
                        <input type="date" class="form-control" id="dSInvoiceDate" name="dSInvoiceDate" > 
                    </div>
                    <div class="form-group col-md-1 mt-2">
                        <img src="{$site_url}assets/images/icon-delete.png" style="cursor:pointer;" onclick="$('#dSInvoiceDate').val('');">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="dSPaymentDate">Payment Date</label>
                <div class="form-row">
                    <div class="form-group col-md-11">
                        <input type="date" class="form-control" id="dSPaymentDate" name="dSPaymentDate" > 
                    </div>
                    <div class="form-group col-md-1 mt-2">
                        <img src="{$site_url}assets/images/icon-delete.png" style="cursor:pointer;" onclick="$('#dSPaymentDate').val('');">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="iSBillingMonth">Billing Month</label>
                <div class="form-row">
                    <div class="form-group col-md-11">
                        {$dSMonth} 
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="iSBillingYear">Billing Year</label>
                <div class="form-row">
                    <div class="form-group col-md-11">
                        {$dSYear} 
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="iSPremiseId">Premise Id</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <input type="text" name="iSPremiseId" id="iSPremiseId" value="" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="vSPremiseNameDD">Premise Name</label>
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
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="iSServiceType">Service Type</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSServiceType" id="iSServiceType" class="form-control col-md-12">
                            <option value="">-- Select --</option> {section name="s" loop=$rs_stype} <option value="{$rs_stype[s].iServiceTypeId}">{$rs_stype[s].vServiceType|gen_strip_slash}</option> {/section}
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="dSStartDate">Start Date</label>
                <div class="form-row">
                    <div class="form-group col-md-11">
                        <input type="date" class="form-control" id="dSStartDate" name="dSStartDate" > 
                    </div>
                    <div class="form-group col-md-1 mt-2">
                        <img src="{$site_url}assets/images/icon-delete.png" style="cursor:pointer;" onclick="$('#dSStartDate').val('');">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="iSStatus">Status</label>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <select name="iSStatus" id="iSStatus" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="0">Draft</option>
                            <option value="1">Sent</option>
                            <option value="2">Paid</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" id="AdvSearchSubmit" name="AdvSearchSubmit" class="btn btn-outline-warning fas fa-search"></button>
        <button type="button" class="btn btn-outline-danger fas fa-times" aria-label="Close" id="AdvSearchReset" name="AdvSearchReset"></button>
    </form>
</div>