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
                    			<input type="hidden" name="mode" id="mode" value="{$mode}">
                                <div class="form-row">
                                    <div class="col-4 mb-3"> 
                                        <label for="vChartType">Chart Type <span class="required" aria-required="true">*</span></label>
                                        <select name="vChartType" id="vChartType" class="select" required>
                                            <option value="">--- Select ---</option>
                                            <option value="Pie-Chart">Pie-Chart</option>
                                            <option value="Bar-Chart">Bar-Chart</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please Select DisplayX.
                                        </div>
                                    </div>                                    
                                    <div class="col-4 mb-3">
                                        <label for="vDisplayY">DisplayY <span class="required" aria-required="true">*</span></label>
                                        <select name="vDisplayY" id="vDisplayY" class="select" required onchange="getDisplayX(this.value)">
                                        	<option value="">--- Select ---</option>
                                            {foreach from=$default_Yaxes item=ctype}
                                            <option value="{$ctype}">{$ctype}</option>
                                            {/foreach}
                                        </select>
                                        <div class="invalid-feedback">
		                                    Please Select DisplayY.
		                                </div>
                                    </div>
                                    <div class="col-4 mb-3"> 
                                        <label for="vDisplayX">DisplayX <span class="required" aria-required="true">*</span></label>
                                        <select name="vDisplayX" id="vDisplayX" class="select" required onchange="showDateFilter(this.value)">
                                        	<option value="">--- Select ---</option>
                                        </select>
                                        <div class="invalid-feedback">
		                                    Please Select DisplayX.
		                                </div>
                                    </div>
                                    <div class="col-4 mb-3 date-row d-none"> 
                                        <label for="username">From Date</label>
                                         <input type="date" class="form-control" id="dFromDate" name="dFromDate">
                                    </div>
                                    <div class="col-4 mb-3 date-row d-none"> 
                                        <label for="dToDate">To Date</label>
                                         <input type="date" class="form-control" id="dToDate" name="dToDate">
                                    </div> 
									<div class="col-4 mb-3"> 
                                        &nbsp;
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" id="create_pie_charts" class="btn btn-primary">Create Pie Chart</button>  
                                        <img src="assets/images/loading-small.gif" id="pie_charts_save_loading" border="0" style="display:none;">  
                                        <button type="reset" class="btn btn-outline-warning">Reset</button>
                                    </div>
                                </div>
                            </form>  
                    	</div>
                    </div>
                    <div class="row">                                           
                    	<div class="col-12"> 
                    		<div id="piechart" class="d-none" style="height: 500px;"></div>
                            <div id="column_with_rotated_series" class="height-350 d-none"></div>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="assets/vendors/select2/css/select2.min.css"/>
<link rel="stylesheet" href="assets/vendors/select2/css/select2-bootstrap.min.css"/>
<script src="assets/vendors/select2/js/select2.full.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script src="assets/vendors/amcharts/core.js"></script>
<script src="assets/vendors/amcharts/charts.js"></script>
<script src="assets/vendors/amcharts/animated.js"></script>
<script src="assets/vendors/amcharts/amchartsdark.js"></script>
        
<script type="text/javascript" src="assets/js/app_js/pie_chart.js"></script>


<script type="text/javascript">
var mode = '{$mode}';
</script>

