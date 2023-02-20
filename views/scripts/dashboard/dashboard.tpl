<div class="row "> <!-- no-gutters w-100 -->
    <div class="col-12 col-lg-8 col-xl-6 mt-3">
        <div class="card">
            <div class="card-content">
                <div class="card-body text-center">
                    <div id="serviceorder_chart"></div>
                    <div id="workorder_chart"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8 col-xl-6 mt-3">
        <div class="card h-100">
            <div class="card-content h-100">
                <div class="card-body h-100 p-0">
                    <div class="info-card h-100">
                        <div id="dashboard_map" class="w-100 " style="height:634px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6 col-xl-3 mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row ml-1">
                    <h6 class="card-title font-weight-bold">Day at a Glance <i class="fas fa-coffee"></i></h6>
                </div>
                <table class="table table-striped">
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Trouble Tickets: <b>{$day_glance['trouble_ticket'].today} , {$day_glance['trouble_ticket'].yesterday}</b>
                        </td>
                        <td>{$day_glance['trouble_ticket'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Maintenence Tickets: <b>{$day_glance['maintenance_ticket'].today} , {$day_glance['maintenance_ticket'].yesterday}</b>
                        </td>
                        <td>{$day_glance['maintenance_ticket'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Service Orders: <b>{$day_glance['service_order'].today} , {$day_glance['service_order'].yesterday}</b>
                        </td>
                        <td>{$day_glance['service_order'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Work Orders: <b>{$day_glance['workorder'].today} , {$day_glance['workorder'].yesterday}</b>
                        </td>
                        <td>{$day_glance['workorder'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Fiber Inquiry: <b>{$day_glance['fiber_inquiry'].today} , {$day_glance['fiber_inquiry'].yesterday}</b>
                        </td>
                        <td>{$day_glance['fiber_inquiry'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Events: <b>{$day_glance['events'].today} , {$day_glance['events'].yesterday}</b>
                        </td>
                        <td>{$day_glance['events'].diff_ratio}</td>
                    </tr>
                </table> *Compared to yesterday
            </div>
        </div>
    </div> 
    <div class="col-12 col-sm-6 col-xl-3 mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row ml-1">
                    <h6 class="card-title font-weight-bold">My Service Orders</h6>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="text-center">ID</th>
                                <th scope="col">Premise</th>
                                <th scope="col">Carrier</th>
                                <th scope="col" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="service_order_data"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3 mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row ml-1">
                    <h6 class="card-title font-weight-bold">My Work Orders</h6>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="text-center">ID</th>
                                <th scope="col">Premise</th>
                                <th scope="col">Service Order #</th>
                                <th scope="col" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="work_order_data"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3 mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row ml-1">
                    <h6 class="card-title font-weight-bold">My Fiber Inquiry</h6>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="text-center">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Address</th>
                                <th scope="col" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="fiberinquiry_data"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row chartdiv_row">
    <div class="col-12 col-lg-12 col-xl-12 mt-3">
        <div class="card">
            <div class="card-body text-center">
                <div id="chartdiv" style="width: 100%; height: 325px; background-color: #282828;"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/serial.js"></script>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/dark.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={$GOOGLE_GEOCODE_API_KEY}&libraries=geometry,drawing,places,visualization"></script>
<script src="{$site_url}assets/js/mapjs/oms.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="{$site_url}assets/js/app_js/dashboard.js"></script>
<script type="text/javascript">
var dashboard_amchart_arr = {$dashboard_amchart|json_encode};
var dashboard_serviceorder = {$dashboard_serviceorder|json_encode};
var dashboard_workorder = {$dashboard_workorder|json_encode};
var dashboard_fiberinquiry = {$dashboard_fiberinquiry|json_encode};
var dashboard_SObarchart = new Array({$dashboard_SObarchart});
var dashboard_WObarchart = new Array({$dashboard_WObarchart});
var primarycolor = '{$user_panel_theme_arr["template_color"]}';
let MAP_LONG = '{$MAP_LONGITUDE}';
let MAP_LAT = '{$MAP_LATITUDE}';
var markerSpiderfier;
var infowindow;
var serviceorder_arr = [];
var fiberInquiry_arr = [];
var workorder_arr = [];
var trouble_ticket_arr = [];
var maintenance_ticket_arr = [];
</script>
