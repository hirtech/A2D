<div class="row   no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$module_name}</h4></div>
            <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                <li class="breadcrumb-item"><a href="{$site_url}">Home</a></li>
                <li class="breadcrumb-item active">{$module_name}</li>
            </ol>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">                                           
                        <div class="col-12">
                            <form name="frmimport" id="frmimport" action="" class="form-horizontal needs-validation" method="post"  enctype="multipart/form-data"  novalidate>
                            <input type="hidden" name="mode" id="mode" value="import_file">
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="col-12 mb-3">
                                        <label for="impOptions">Import Options<span class="text-danger">*</span></label>
                                        <select name="impOptions" id="impOptions" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="larval">Larval Treatments</option>
                                            <option value="adult">Adult Treatments</option>
                                            <option value="sr">Service Request</option>
                                        </select>
                                        <div class="invalid-feedback"> Please select import option</div>
                                    </div>
                                    <div class="col-12 mb-3 ">
                                        <label for="file">File<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="file" class="d-inline-flex form-control-file form-control h-auto" id="importfile" name="file" required>
                                        <div class="invalid-feedback"> Please select file</div>
                                        </div>
                                        <input type="hidden" name="file_old" id="file_old" value="">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary ml-2 " id="import_data"> Import </button> 
                                        <img src="assets/images/loading-small.gif" id="save_loading" border="0" style="display:none;">  
                                        <button type="reset" id="reset_import" class="btn  btn-secondary  ml-2" > Reset </button>
                                    </div>
                                </div>
                                <div class="col-4 table-responsive ml-2">
                                    <table class="table table-bordered">
                                    <tr>
                                        <th width="85%">Sample Files</th>
                                    </tr>
                                    <tr>
                                        <td><a title="Download" href="{$samplefiles_url}sample_larval_treatments.xls">Larval Treatments File&nbsp;&nbsp; <i class="fa fa-download"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td><a title="Download" href="{$samplefiles_url}sample_service_requests.xls">Service Requests File&nbsp;&nbsp; <i class="fa fa-download"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td><a title="Download" href="{$samplefiles_url}sample_adult_treatments.xls">Adult Treatments File &nbsp;&nbsp;<i class="fa fa-download"></i></a></td>
                                    </tr>
                                </table>    
                                </div>
                            </div>  
                            </form>   
                        </div>
                    </div>
                    <div class="row">                                           
                        <div class="col-12" id="import_error_records">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="assets/js/app_js/import_add.js"></script>