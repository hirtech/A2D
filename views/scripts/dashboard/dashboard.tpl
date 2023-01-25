<div class="row "> <!-- no-gutters w-100 -->
    <div class="col-12 col-lg-8 col-xl-4 mt-3">
        <div class="card">
            <div class="card-content">
                <div class="card-image business-card">
                    <div class="background-image-maker" style="background-image: url(" {$site_url}assets/images/usa-map-image.jpeg");"></div>
                    <div class="holder-image">
                        <img src="{$site_url}assets/images/usa-map-image.jpeg" alt="" class="img-fluid">
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title mb-3 mt-2">HELP US LIGHT YOUR COMMUNITY!</h5>
                    <p class="card-text">Check availability of service <br>Signup with a provider <br>We will do the installation. </p>
                    <div class="row mt-4 mb-3">
                        <div class="col-6">
                            <b>
                                <i class="ion ion-android-call"></i> Phone </b>
                            <br> +1-203-243-5044
                        </div>
                        <div class="col-6">
                            <b>
                                <i class="ion ion-android-pin"></i> Location </b>
                            <br> Marietta GA
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8 col-xl-8 mt-3">
        <div class="card h-100">
            <div class="card-content h-100">
                <div class="card-body h-100 p-0">
                    <div class="info-card h-100">
                        <div id="dashboard_map" class="w-100 " style="height:490px"></div>
                        <!-- <div class="holder-image text-center">
                            <img src="{$site_url}assets/images/modern-equipment-image.jpeg" alt="" class="img-fluid">
                        </div>
                        <div class="title px-4 text-black mb-3 ">
                            <h5 class="card-title mb-3 mt-2">The choice is all yours.</h5>
                            <p class="card-text">Connect with the world with the provider of your choice for all services or have different providers deliver à la carte.</p>
                        </div> -->
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
                    <h6 class="card-title font-weight-bold">Month at a Glance <i class="fas fa-calendar-alt"></i></h6>
                </div>
                <table class="table table-striped">
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Trouble Tickets: <b>{$month_glance['trouble_ticket'].curr_month} , {$month_glance['trouble_ticket'].last_month}</b>
                        </td>
                        <td>{$month_glance['trouble_ticket'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Maintenence Tickets: <b>{$month_glance['maintenance_ticket'].curr_month} , {$month_glance['maintenance_ticket'].last_month}</b>
                        </td>
                        <td>{$month_glance['maintenance_ticket'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Service Orders: <b>{$month_glance['service_order'].curr_month} , {$month_glance['service_order'].last_month}</b>
                        </td>
                        <td>{$month_glance['service_order'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Work Orders: <b>{$month_glance['workorder'].curr_month} , {$month_glance['workorder'].last_month}</b>
                        </td>
                        <td>{$month_glance['workorder'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Fiber Inquiry: <b>{$month_glance['fiber_inquiry'].curr_month} , {$month_glance['fiber_inquiry'].last_month}</b>
                        </td>
                        <td>{$month_glance['fiber_inquiry'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Events: <b>{$month_glance['events'].curr_month} , {$month_glance['events'].last_month}</b>
                        </td>
                        <td>{$month_glance['events'].diff_ratio}</td>
                    </tr>
                </table> *Compared to last month
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3 mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row ml-1">
                    <h6 class="card-title font-weight-bold">Week at a Glance <i class="fas fa-calendar-week"></i></h6>
                </div>
                <table class="table table-striped">
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Trouble Tickets: <b>{$week_glance['trouble_ticket'].curr_week} , {$week_glance['trouble_ticket'].last_week}</b>
                        </td>
                        <td>{$week_glance['trouble_ticket'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Maintenence Tickets: <b>{$week_glance['maintenance_ticket'].curr_week} , {$week_glance['maintenance_ticket'].last_week}</b>
                        </td>
                        <td>{$week_glance['maintenance_ticket'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Service Orders: <b>{$week_glance['service_order'].curr_week} , {$week_glance['service_order'].last_week}</b>
                        </td>
                        <td>{$week_glance['service_order'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Work Orders: <b>{$week_glance['workorder'].curr_week} , {$week_glance['workorder'].last_week}</b>
                        </td>
                        <td>{$week_glance['workorder'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Fiber Inquiry: <b>{$week_glance['fiber_inquiry'].curr_week} , {$week_glance['fiber_inquiry'].last_week}</b>
                        </td>
                        <td>{$week_glance['fiber_inquiry'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Events: <b>{$week_glance['events'].curr_week} , {$week_glance['events'].last_week}</b>
                        </td>
                        <td>{$week_glance['events'].diff_ratio}</td>
                    </tr>
                </table> *Compared to last week
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3 mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row ml-1">
                    <h6 class="card-title font-weight-bold">Year at a Glance <i class="fas fa-calendar-alt"></i></h6>
                </div>
                <table class="table  table-striped">
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Trouble Tickets: <b>{$year_glance['trouble_ticket'].curr_year} , {$year_glance['trouble_ticket'].last_year}</b>
                        </td>
                        <td>{$year_glance['trouble_ticket'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Maintenence Tickets: <b>{$year_glance['maintenance_ticket'].curr_year} , {$year_glance['maintenance_ticket'].last_year}</b>
                        </td>
                        <td>{$year_glance['maintenance_ticket'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Service Orders: <b>{$year_glance['service_order'].curr_year} , {$year_glance['service_order'].last_year}</b>
                        </td>
                        <td>{$year_glance['service_order'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Work Orders: <b>{$year_glance['workorder'].curr_year} , {$year_glance['workorder'].last_year}</b>
                        </td>
                        <td>{$year_glance['workorder'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Fiber Inquiry: <b>{$year_glance['fiber_inquiry'].curr_year} , {$year_glance['fiber_inquiry'].last_year}</b>
                        </td>
                        <td>{$year_glance['fiber_inquiry'].diff_ratio}</td>
                    </tr>
                    <tr>
                        <td class="card-subtitle mb-2 text-muted">Events: <b>{$year_glance['events'].curr_year} , {$year_glance['events'].last_year}</b>
                        </td>
                        <td>{$year_glance['events'].diff_ratio}</td>
                    </tr>
                </table> *Compared to last year
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
<script src="{$site_url}assets/js/app_js/dashboard.js"></script>
<script type="text/javascript">
var dashboard_amchart_arr = {$dashboard_amchart|json_encode};
let MAP_LONG = '{$MAP_LONGITUDE}';
let MAP_LAT = '{$MAP_LATITUDE}';
var markerSpiderfier;
var infowindow;
var serviceorder_arr = [];
</script>
