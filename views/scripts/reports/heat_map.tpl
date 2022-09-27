<div class="row  no-gutters w-100">
    <div class="col-12  align-self-center">
        <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
            <div class="w-sm-100 mr-auto"><h4 class="mb-0">{$mode} {$module_name}</h4></div>
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
                          <form name="frmadd" id="frmadd" method="post" class="form-horizontal needs-validation" novalidate enctype="multipart/form-data">
                             <input type="hidden" name="mode" id="mode" value="create_heat_map">
                             <div class="form-row">
                                <div class="col-6 mb-3">
                                    <label for="vLayer">Layer <span class="required  text-danger" aria-required="true">*</span></label>
                                    <select name="vLayer" id="vLayer" class="form-control" required >
                                       <option value="">--- Select ---</option>
                                       <option value="1">Service Request</option>
                                       <option value="2">Landing Rates</option>
                                       <option value="3">Larval Surveillance</option>
                                       <option value="4">Treatment</option>
                                       <option value="5">Other Tasks</option>
                                       <option value="6">Trap Collected</option>
                                       <option value="7">Mosquito Count</option>
                                       <option value="8">Positive Pools</option>
                                   </select>
                                   <div class="invalid-feedback">
                                      Please Select Layer.
                                  </div>
                              </div>
                              <div class="col-6 mb-3 date-row"> 
                                <label for="username">From Date</label>
                                <input type="date" class="form-control" id="dFromDate" name="dFromDate">
                            </div>
                            <div class="col-6 mb-3 date-row"> 
                                <label for="dToDate">To Date</label>
                                <input type="date" class="form-control" id="dToDate" name="dToDate">
                            </div> 
                            <div class="col-12">
                                <button type="submit" id="create_heat_map" class="btn btn-primary">Create Heat Map</button>  
                                <img src="assets/images/loading-small.gif" id="heat_map_save_loading" border="0" style="display:none;">  
                                <button type="button" class="btn btn-warning" id="resetform">Reset</button>
                            </div>
                        </div>
                    </form>  
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12 mb-2" id="floating-panel" style="display:none">
                    <button onclick="toggleHeatmap()" class="btn btn-info">Toggle Heatmap</button>
                    <button onclick="changeGradient()" class="btn btn-info">Change gradient</button>
                    <button onclick="changeRadius()" class="btn btn-info">Change radius</button>
                    <button onclick="changeOpacity()" class="btn btn-info">Change opacity</button>
                </div>                                           
                <div class="col-12"> 
                  <div id="heatmap" class="" style="height: 500px;"></div>
              </div>
           </div>
      </div>
  </div>
</div>
</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key={$GOOGLE_GEOCODE_API_KEY}&libraries=visualization&v=weekly" defer>
</script>        
<script type="text/javascript" src="assets/js/app_js/heat_map.js"></script>
<script type="text/javascript">
    var mode = '{$mode}';
</script>

