<div class="row   no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item"><a href="{$site_url}invoice/invoice_list">{$module_name} List</a></li>
                <li class="breadcrumb-item active">{$module_name}</li>
            </ol>
        </div>
    </div>
</div>
<form name="frmadd" id="frmadd" method="post" action="" class="form-horizontal needs-validation"  novalidate  enctype="multipart/form-data">
    <input type="hidden" name="mode" id="mode" value="{$mode}">
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header">                               
                <h4 class="card-title">{$module_name} {$mode}</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-6">
                            <div class="col-12 mb-3">
                                <label for="iCustomerId">Customer <span class="text-danger"> *</span></label>
                                <select name="iCustomerId" id="iCustomerId" class="form-control" required>
                                    <option value="">Select</option>
                                    {section name="c" loop=$rs_carrier}
                                        <option value="{$rs_carrier[c].iCompanyId}">{$rs_carrier[c].vCompanyName|gen_strip_slash}</option>
                                    {/section}
                                </select>
                                <div class="invalid-feedback"> Please select customer</div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="vPONumber">P.O. / S.O. <span class="text-danger">*</span></label>
                                <input type="text" name="vPONumber" id="vPONumber" value="{$rs_invoice[c].vPONumber}" class="form-control" placeholder="P.O. / S.O." required>
                                    <div class="invalid-feedback"> Please enter P.O. / S.O. </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="dInvoiceDate">Invoice Date</label>
                                <input type="date" class="form-control" id="dInvoiceDate" name="dInvoiceDate" value="{$rs_trouble[0].dInvoiceDate}" required> 
                                    <div class="invalid-feedback"> Please select invoice date</div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="dPaymentDate">Payment Date</label>
                                <input type="date" class="form-control" id="dPaymentDate" name="dPaymentDate" value="{$rs_invoice[0].dPaymentDate}" required> 
                                <div class="invalid-feedback"> Please select payment date</div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="vBillingMonth">Billing Month</label>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        {$dMonth}        
                                    </div>
                                    <div class="col-md-6 col-12">
                                        {$dYear}        
                                    </div>
                                </div>
                                
                                
                                <div class="invalid-feedback"> Please select billing month & year</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="col-12 mb-3">
                                <label for="tNotes">Notes</label>
                                <textarea class="form-control" name="tNotes" id="tNotes" rows="5">{$rs_invoice[0].tNotes|gen_filter_text}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <!-- <div class="w-sm-100 mr-auto"></div> -->
                        <button type="submit" class="btn btn-primary ml-2 " id="save_data"> Save </button> 
                            <img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;">  
                        <button type="button" onclick="location.href = site_url+'invoice/invoice_list';" class="btn  btn-secondary  ml-2" > Close </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 </form>
<!-- START: Page JS-->
<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>

<script src="assets/vendors/select2/js/select2.full.min.js"></script>
<script src="assets/vendors/typeahead/handlebars-v4.5.3.js"></script>
<script src="assets/vendors/typeahead/typeahead.bundle.js"></script>
<script type="text/javascript" src="assets/js/app_js/invoice_add.js"></script>
<!-- START: Page JS-->
<script type="text/javascript">
var mode = '{$mode}';
</script>

