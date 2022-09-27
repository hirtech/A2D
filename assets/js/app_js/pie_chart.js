var primarycolor;
var bodycolor;
$(document).ready(function() {
    $('select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
          width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
    });

    if(mode == 'Add'){
        $(".address-details").hide();
        $(".contact-details").hide();
    }

    primarycolor = getComputedStyle(document.body).getPropertyValue('--primarycolor');
    bodycolor = getComputedStyle(document.body).getPropertyValue('--bodycolor');
    bordercolor = getComputedStyle(document.body).getPropertyValue('--bordercolor');

    if ($('body').hasClass('dark')) {
        am4core.useTheme(am4themes_amchartsdark);
    }
    if ($('body').hasClass('dark-alt')) {
        am4core.useTheme(am4themes_amchartsdark);
    }
    am4core.useTheme(am4themes_animated);

});

var pie_arr;

$("#create_pie_charts").click(function(){
    $("#mode").val('create_pie_charts');

    //$("#piechart").addClass("d-none");
    //$("#column_with_rotated_series").addClass("d-none");

    var vChartType =  $("#vChartType").val();
    var form = $("#frmadd");
    //alert(form[0].checkValidity())
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
   
    form.addClass('was-validated');
    if(isError == 0){
        //alert(isError);return false;
        var form_data = $("#frmadd").serializeArray();
        $("#create_pie_charts").prop('disabled', true);
        $('#pie_charts_save_loading').show();
        $.ajax({
            type: "POST",
            url: site_url+"reports/pie_charts",
            data: form_data,
            cache: false,
            success: function(res){
                $('#pie_charts_save_loading').hide();
                $("#create_pie_charts").prop('disabled', false);
                response =JSON.parse(res);
                //alert(JSON.stringify(response))
                if(vChartType == 'Pie-Chart'){
                    $("#piechart").removeClass("d-none");
                    $("#column_with_rotated_series").addClass("d-none");
                    pie_arr = $.map(response, function (el) {
                        return el;
                    });
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);
                } else if(vChartType == 'Bar-Chart'){
                    $("#column_with_rotated_series").removeClass("d-none");
                    $("#piechart").addClass("d-none");
                    var arr = $.map(response, function (el) {
                        return el;
                    });

                    var bar_arr = [];
                    var column_with_rotated_series = document.getElementById("column_with_rotated_series");
                    if (column_with_rotated_series) {

                        // Create chart instance
                        var chart = am4core.create("column_with_rotated_series", am4charts.XYChart);
                        chart.scrollbarX = new am4core.Scrollbar();

                        // Add data
                        chart.data = arr;

                        // Create axes
                        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                        categoryAxis.dataFields.category = "vFieldX";
                        categoryAxis.renderer.grid.template.location = 0;
                        categoryAxis.renderer.minGridDistance = 30;
                        categoryAxis.renderer.labels.template.horizontalCenter = "right";
                        categoryAxis.renderer.labels.template.verticalCenter = "middle";
                        categoryAxis.renderer.labels.template.rotation = 270;
                        categoryAxis.tooltip.disabled = true;
                        categoryAxis.renderer.minHeight = 110;

                        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                        valueAxis.renderer.minWidth = 50;

                        // Create series
                        var series = chart.series.push(new am4charts.ColumnSeries());
                        series.sequencedInterpolation = true;
                        series.dataFields.valueY = "vFieldY";
                        series.dataFields.categoryX = "vFieldX";
                        series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
                        series.columns.template.strokeWidth = 0;

                        series.tooltip.pointerOrientation = "vertical";

                        series.columns.template.column.cornerRadiusTopLeft = 10;
                        series.columns.template.column.cornerRadiusTopRight = 10;
                        series.columns.template.column.fillOpacity = 0.8;

                        // on hover, make corner radiuses bigger
                        var hoverState = series.columns.template.column.states.create("hover");
                        hoverState.properties.cornerRadiusTopLeft = 0;
                        hoverState.properties.cornerRadiusTopRight = 0;
                        hoverState.properties.fillOpacity = 1;

                        series.columns.template.adapter.add("fill", function (fill, target) {
                            return chart.colors.getIndex(target.dataItem.index);
                        });

                        // Cursor
                        chart.cursor = new am4charts.XYCursor();
                    }
                }
            }
        });
        return false; 
    }
    
});



function getDisplayX(vDisplayY) {

    $.ajax({
        type: "POST",
        url: site_url+"reports/pie_charts",
        data: {
            "mode" : "getDisplayXFromDisplayY",
            "vDisplayY" : vDisplayY,
        },
        success: function(data){
            response =JSON.parse(data);
            var option ="<option value=''>---Select---</option>";
            if(response.length > 0 ){
                $.each(response,function(i,val){
                    option +="<option value='"+response[i]+"'>"+response[i]+"</option>";
                });
            }
            $("#vDisplayX").html(option);

            $("#vDisplayX").focus();
        }
    });    
}

function showDateFilter(vDisplayX) {
    var vDisplayY = $("#vDisplayY").val();
    $.ajax({
        type: "POST",
        url: site_url+"reports/pie_charts",
        data: {
            "mode" : "getDetailsFromAxes",
            "vDisplayX" : vDisplayX,
            "vDisplayY" : vDisplayY,
        },
        success: function(data){
            response =JSON.parse(data);
                
            var bFromTo = response[0]['bFromTo'];
            //alert(bFromTo)
            if(bFromTo == 't' || bFromTo == '1') {
                $(".date-row").removeClass("d-none");
            }else {
                $(".date-row").addClass("d-none");
            }
        }
    });
}

function drawChart() {
    //var data = google.visualization.arrayToDataTable([pie_arr]);
    var data = new google.visualization.DataTable();
    data.addColumn('string', $("#vDisplayX").val());
    data.addColumn('number',  $("#vDisplayY").val());

    for (var i = 0; i < pie_arr.length; i++) {
        data.addRow([pie_arr[i].vFieldX, parseInt(pie_arr[i].vFieldY)]);
    }
    var options = {
        title: 'Pie Chart'
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);
}