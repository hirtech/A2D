<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {* <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"> *}
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <!-- amCharts javascript sources -->
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/amcharts.js"></script>
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/serial.js"></script>
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/dark.js"></script>

    {literal}
  <!-- amCharts javascript code -->
  <script type="text/javascript">
    AmCharts.makeChart("chartdiv",
      {
        "type": "serial",
        "categoryField": "category",
        "startDuration": 1,
        "theme": "dark",
        "categoryAxis": {
          "classNameField": "",
          "gridPosition": "start",
          "title": "Species"
        },
        "chartCursor": {
          "enabled": true
        },
        "chartScrollbar": {
          "enabled": true
        },
        "trendLines": [],
        "graphs": [
          {
            "fillAlphas": 1,
            "id": "AmGraph-1",
            "title": "graph 1",
            "type": "column",
            "valueField": "Total Count"
          },
          {
            "id": "AmGraph-2",
            "title": "graph 2"
          }
        ],
        "guides": [],
        "valueAxes": [
          {
            "id": "ValueAxis-1",
            "title": "Total Count"
          }
        ],
        "allLabels": [],
        "balloon": {},
        "titles": [
          {
            "id": "Title-1",
            "size": 15,
            "text": "Total Mosquito Count YTD"
          }
        ],
        "dataProvider": [
          {
            "category": "Ae. Aegypti",
            "Total Count": 8
          },
          {
            "category": "An. franciscanus",
            "Total Count": 16
          },
          {
            "category": "An. Anophilis",
            "Total Count": 2
          },
          {
            "category": "Ae. Washinoi",
            "Total Count": 5
          },
          {
            "category": "Cx. pipiens",
            "Total Count": 9
          },
          {
            "category": "Cx. stigmatasoma",
            "Total Count": 4
          },
          {
            "category": "An. punctipenni",
            "Total Count": "5"
          }
        ]
      }
    );
  </script>
{/literal}

</head>
<body>

<div class="row  no-gutters w-100">             
  <div class="col-12 col-sm-6 col-xl-3 mt-3">
    <div class="card">                      
      <div class="card-content">


        <div class="card-image business-card">
          <div class="background-image-maker" style="background-image: url("https://synergy2ms.com/wp-content/uploads/2018/12/mosquito-control-jackson-pest-exterminator-1024x683.jpg");"></div>
          <div class="holder-image">
            <img src="https://synergy2ms.com/wp-content/uploads/2018/12/mosquito-control-jackson-pest-exterminator-1024x683.jpg" alt="" class="img-fluid">                                        
          </div>  
          
        </div>
        <div class="card-body">  
          <h5 class="card-title mb-3 mt-2">Welcome to VectorERP</h5>
          <p class="card-text">Where work is simpler and navigation easier. Just checkout the To-Do list at the top right corner.</p>
          <div class="row mt-4 mb-3">
            <div class="col-6">
              <b><i class="ion ion-android-call"></i> Phone</b><br>
              +1-203-243-5044 
            </div>
            <div class="col-6">
              <b><i class="ion ion-android-pin"></i> Location</b><br> 
              Marietta GA
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>  

  <div class="col-12 col-sm-6 col-xl-3 mt-3">
    <div class="card">
      <div class="card-body">
        <div class="row"><h6 class="card-title font-weight-bold">Day at a Glance</h6><i class="fas fa-coffee"></i></div>
          <table class="table-sm table-striped">
            <tr>
              <td class="card-subtitle mb-2 text-muted">Trouble Tickets: <b>{$day_glance['trouble_ticket'].today} , {$day_glance['trouble_ticket'].yesterday}</b></td>
              <td>{$day_glance['trouble_ticket'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Maintenence Tickets: <b>{$day_glance['maintenance_ticket'].today} , {$day_glance['maintenance_ticket'].yesterday}</b></td>
              <td>{$day_glance['maintenance_ticket'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Service Orders: <b>{$day_glance['service_order'].today} , {$day_glance['service_order'].yesterday}</b></td>
              <td>{$day_glance['service_order'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Work Orders: <b>{$day_glance['workorder'].today} , {$day_glance['workorder'].yesterday}</b></td>
              <td>{$day_glance['workorder'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Fiber Inquiry: <b>{$day_glance['fiber_inquiry'].today} , {$day_glance['fiber_inquiry'].yesterday}</b></td>
              <td>{$day_glance['fiber_inquiry'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Events: <b>{$day_glance['events'].today} , {$day_glance['events'].yesterday}</b></td>
              <td>{$day_glance['events'].diff_ratio}</td>
            </tr>
          </table>                  
          *Compared to yesterday
       </div>
    </div>
  </div>
  <div class="col-12 col-sm-6  col-xl-3 mt-3">
    <div class="card h-100">                      
      <div class="card-content h-100">
        <div class="card-body h-100 p-0">
          <div class="info-card h-100">
            <div class="background-image-maker"></div>
            <div class="holder-image">
              <img src="https://synergy2ms.com/wp-content/uploads/2018/12/Mosquito-Control-Prevention-Ridgeland-1024x683.jpeg" alt="" class="img-fluid">                                        
            </div>    
            <div class="title px-4 text-black mb-3">
              <h3></h3>
              <h3 class="text-black">Bid farewell to Big Bites.</h3>
              <h3 class="text-black">Let Nile remain in Africa.</h3>
              <img src="https://synergy2ms.com/wp-content/uploads/2018/12/Mosquito-Control-Prevention-Ridgeland-1024x683.jpeg" alt="" class="border ml-2 img-fluid rounded-circle" width="35"> By Gia
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3 mt-3">
    <div class="card">
      <div class="card-body">
        <div class="row"><h6 class="card-title font-weight-bold">Month at a Glance</h6><i class="fas fa-calendar-alt"></i></div>            
          <table class="table-sm table-striped">
            <tr>
              <td class="card-subtitle mb-2 text-muted">Trouble Tickets: <b>{$month_glance['trouble_ticket'].curr_month} , {$month_glance['trouble_ticket'].last_month}</b></td>
              <td>{$month_glance['trouble_ticket'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Maintenence Tickets: <b>{$month_glance['maintenance_ticket'].curr_month} , {$month_glance['maintenance_ticket'].last_month}</b></td>
              <td>{$month_glance['maintenance_ticket'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Service Orders: <b>{$month_glance['service_order'].curr_month} , {$month_glance['service_order'].last_month}</b></td>
              <td>{$month_glance['service_order'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Work Orders: <b>{$month_glance['workorder'].curr_month} , {$month_glance['workorder'].last_month}</b></td>
              <td>{$month_glance['workorder'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Fiber Inquiry: <b>{$month_glance['fiber_inquiry'].curr_month} , {$month_glance['fiber_inquiry'].last_month}</b></td>
              <td>{$month_glance['fiber_inquiry'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Events: <b>{$month_glance['events'].curr_month} , {$month_glance['events'].last_month}</b></td>
              <td>{$month_glance['events'].diff_ratio}</td>
            </tr>
          </table>                  
          *Compared to last month
       </div>
    </div>
  </div>

</div>
  
<div class="row">

  <div class="col-12 col-sm-6 col-xl-3 mt-3">
    <div class="card">
      <div class="card-body">
        <div class="row"><h6 class="card-title font-weight-bold">Week at a Glance</h6><i class="fas fa-calendar-week"></i></div>            
          <table class="table-sm table-striped">
            <tr>
              <td class="card-subtitle mb-2 text-muted">Trouble Tickets: <b>{$week_glance['trouble_ticket'].curr_week} , {$week_glance['trouble_ticket'].last_week}</b></td>
              <td>{$week_glance['trouble_ticket'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Maintenence Tickets: <b>{$week_glance['maintenance_ticket'].curr_week} , {$week_glance['maintenance_ticket'].last_week}</b></td>
              <td>{$week_glance['maintenance_ticket'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Service Orders: <b>{$week_glance['service_order'].curr_week} , {$week_glance['service_order'].last_week}</b></td>
              <td>{$week_glance['service_order'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Work Orders: <b>{$week_glance['workorder'].curr_week} , {$week_glance['workorder'].last_week}</b></td>
              <td>{$week_glance['workorder'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Fiber Inquiry: <b>{$week_glance['fiber_inquiry'].curr_week} , {$week_glance['fiber_inquiry'].last_week}</b></td>
              <td>{$week_glance['fiber_inquiry'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Events: <b>{$week_glance['events'].curr_week} , {$week_glance['events'].last_week}</b></td>
              <td>{$week_glance['events'].diff_ratio}</td>
            </tr>
          </table>
                  
          *Compared to last week
       </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-6 mt-6">
    <div class="card">
      <div class="card-body text-center">
        <div id="chartdiv" style="width: 100%; height: 325px; background-color: #282828;" ></div>
      </div>
    </div>        
  </div> 
  <div class="col-12 col-sm-6 col-xl-3 mt-3">
    <div class="card">
      <div class="card-body">
        <div class="row"><h6 class="card-title font-weight-bold">Year at a Glance</h6><i class="fas fa-calendar-alt"></i></div>            
          <table class="table-sm  table-striped">
             <tr>
              <td class="card-subtitle mb-2 text-muted">Trouble Tickets: <b>{$year_glance['trouble_ticket'].curr_year} , {$year_glance['trouble_ticket'].last_year}</b></td>
              <td>{$year_glance['trouble_ticket'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Maintenence Tickets: <b>{$year_glance['maintenance_ticket'].curr_year} , {$year_glance['maintenance_ticket'].last_year}</b></td>
              <td>{$year_glance['maintenance_ticket'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Service Orders: <b>{$year_glance['service_order'].curr_year} , {$year_glance['service_order'].last_year}</b></td>
              <td>{$year_glance['service_order'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Work Orders: <b>{$year_glance['workorder'].curr_year} , {$year_glance['workorder'].last_year}</b></td>
              <td>{$year_glance['workorder'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Fiber Inquiry: <b>{$year_glance['fiber_inquiry'].curr_year} , {$year_glance['fiber_inquiry'].last_year}</b></td>
              <td>{$year_glance['fiber_inquiry'].diff_ratio}</td>
            </tr>
            <tr>
              <td class="card-subtitle mb-2 text-muted">Events: <b>{$year_glance['events'].curr_year} , {$year_glance['events'].last_year}</b></td>
              <td>{$year_glance['events'].diff_ratio}</td>
            </tr>
          </table>
                  
          *Compared to last year
       </div>
    </div>
  </div>
</div>
<div class="row">
    <div class="col-12 mt-3">
    <div class="card">
      <div class="card-body">
        <div id="timeline">
        </div>
      </div>
    </div>
  </div>
</div>
</body>
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
var timeline_arr = {$dashboard_timelinechart|json_encode};
console.log(timeline_arr);
{literal}

  google.charts.load('current', {'packages':['timeline']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var container = document.getElementById('timeline');
        var chart = new google.visualization.Timeline(container);
        var dataTable = new google.visualization.DataTable();

        dataTable.addColumn({ type: 'string', id: 'Technician' });
        dataTable.addColumn({ type: 'string', id: 'Operation' });
        dataTable.addColumn({ type: 'string', role: 'tooltip' });
        dataTable.addColumn({ type: 'date', id: 'Start' });
        dataTable.addColumn({ type: 'date', id: 'End' });

      if(jQuery.isEmptyObject(timeline_arr) == false){
        darr = new Array();
        //dataTable.addRows(JSON.parse(timeline_arr));
       // console.log(timeline_arr.length);
       for(var j=0;j<timeline_arr.length;j++){
          darr[j] = new Array(
            timeline_arr[j]['Technician'],
            timeline_arr[j]['Operation'],
            timeline_arr[j]['tooltip'],
            new Date(0,0,0,timeline_arr[j]['statrh'],timeline_arr[j]['satrti'],timeline_arr[j]['starts']),
            new Date(0,0,0,timeline_arr[j]['endh'],timeline_arr[j]['endi'],timeline_arr[j]['ends']));
       }
     //  console.log(darr);
      dataTable.addRows(darr);

        chart.draw(dataTable);
      }



      } 

</script>
{/literal}
