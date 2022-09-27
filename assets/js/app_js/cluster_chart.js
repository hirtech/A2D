var primarycolor;
var bodycolor;
var chart;
var xAxis,yAxis;
$(document).ready(function() {
    $('select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
          width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
    });

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

$("#vDisplayY").change(function(){
    var disY = $("#vDisplayY").val();
    $("#vDisplayX").children('option').remove();
    $("#vDisplayX1").children('option:not(:first)').remove();
    $(".date-row").addClass("d-none");
    $("#dToDate").val('');
    $("#dFromDate").val('');
   
    if (disY != ""){
        $.ajax({
            type: "POST",
            url: site_url+"reports/cluster_charts",
            data: {
                "mode" : "getDisplayXFromDisplayY",
                "vDisplayY" : disY,
            },
            dataType : "json",
            success: function(response){
                var option ="<option value=''>--- Select ---</option>";
                if(response.length > 0 ){
                    $.each(response,function(i,val){
                        option +="<option value='"+response[i]+"'>"+response[i]+"</option>";
                    });
                }
                $("#vDisplayX").html(option);

                $("#vDisplayX").focus();
            }
        });
    }else{
        var option ="<option value=''>--- Select ---</option>";
        $("#vDisplayX").html(option);
        $("#vDisplayX").focus();
        $("#vDisplayX1").html(option);
    }
});

$("#vDisplayX").change(function(){
    var disX = $("#vDisplayX").val();
    var disY = $("#vDisplayY").val();
    $("#vDisplayX1").children('option').remove();
    $(".date-row").addClass("d-none");
    $("#dToDate").val('');
    $("#dFromDate").val('');
   
    if (disX != ""){
        $.ajax({
            type: "POST",
            url: site_url+"reports/cluster_charts",
            data: {
                "mode" : "getDisplayX1FromDisplayX",
                "vDisplayX" : disX,
                "vDisplayY" : disY,
            },
            dataType : "json",
            success: function(response){
                //response =JSON.parse(data);
                var option ="<option value=''>--- Select ---</option>";
                if(response.length > 0 ){
                    $.each(response,function(i,val){
                        option +="<option value='"+response[i]+"'>"+response[i]+"</option>";
                    });
                }
                $("#vDisplayX1").html(option);

                $("#vDisplayX1").focus();
            }
        });
    }else{
        var option ="<option value=''>--- Select ---</option>";
        $("#vDisplayX1").html(option);
        $("#vDisplayX1").focus();
    }
});

$("#vDisplayX1").change(function(){
     var disX = $("#vDisplayX").val();
    var disY = $("#vDisplayY").val();
    var disX1 = $("#vDisplayX1").val();
    if(disX1 != "" &&  disY != "" && disX != ""){
        $.ajax({
            type: "POST",
            url: site_url+"reports/cluster_charts",
            data: {
                "mode" : "getDetailsFromAxes",
                "vDisplayY" : disY,
                "vDisplayX" : disX,
                "vDisplayX1" : disX1,
            },
            success: function(data){
                response =JSON.parse(data);
                var bFromTo = response[0]['bFromTo'];
                if(bFromTo == 't' || bFromTo == '1') {
                    $(".date-row").removeClass("d-none");
                }else {
                    $(".date-row").addClass("d-none");
                    $("#dToDate").val('');
                    $("#dFromDate").val('');
                }
            }
        });
    }else {
        $(".date-row").addClass("d-none");
        $("#dToDate").val('');
        $("#dFromDate").val('');
    }
});


$("#create_cluster_charts").click(function(){
    var form = $("#frmadd");
    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
   
    form.addClass('was-validated');
    if(isError == 0){

        var form_data = $("#frmadd").serializeArray();
        $("#create_cluster_charts").prop('disabled', true);
        $('#cluster_charts_save_loading').show();
        $.ajax({
            type: "POST",
            url: site_url+"reports/cluster_charts",
            data: form_data,
            cache: false,
            dataType: 'json',
            success: function(response){
                $('#cluster_charts_save_loading').hide();
                $("#create_cluster_charts").prop('disabled', false);
                $("#clusterchart").show();
                am4core.disposeAllCharts();
                chart = am4core.create('clusterchart', am4charts.XYChart);
                chart.colors.step = 2;

                chart.legend = new am4charts.Legend()
                chart.legend.position = 'top'
                chart.legend.paddingBottom = 20
                chart.legend.labels.template.maxWidth = 95

                xAxis = chart.xAxes.push(new am4charts.CategoryAxis())
                xAxis.dataFields.category = 'category'
                xAxis.renderer.cellStartLocation = 0.1
                xAxis.renderer.cellEndLocation = 0.9
                xAxis.renderer.grid.template.location = 0;

                yAxis = chart.yAxes.push(new am4charts.ValueAxis());
                yAxis.min = 0;

            
                
               if(jQuery.isEmptyObject(response['data']) == false){
                    if(jQuery.isEmptyObject(response['data']) == false){
                        //chart.data = [response['data']]  ;
                        chart.data = response['data']  ;
                    }
                    if(jQuery.isEmptyObject(response['series']) == false ){
                       for (var s = 0; s < response['series'].length; s++) {
                            createSeries(s, response['series'][s]);
                        } 
                    }
                }else{
                    /*chart.data = [];
                    createSeries('', '');
                    $("#clusterchart").hide();
                    chart.dispose();*/
                    am4core.disposeAllCharts();
                    
                }
            
            }
        });
        return false; 
    }
    
});

function createSeries(value, name) {
    var series = chart.series.push(new am4charts.ColumnSeries())
    series.dataFields.valueY = value
    series.dataFields.categoryX = 'category'
    series.name = name

    //ToolTip
    series.tooltipText = "{categoryX}: {valueY}";

    series.events.on("hidden", arrangeColumns);
    series.events.on("shown", arrangeColumns);

    var bullet = series.bullets.push(new am4charts.LabelBullet())
    bullet.interactionsEnabled = false
    bullet.dy = 30;
    bullet.label.text = '{valueY}'
    bullet.label.fill = am4core.color('#ffffff')

	chart.cursor = new am4charts.XYCursor();
    /*chart.cursor.lineY.disabled = false;
	chart.cursor.lineX.disabled = false;*/


    return series;
}

function arrangeColumns() {

    var series = chart.series.getIndex(0);

    var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
    if (series.dataItems.length > 1) {
        var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
        var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
        var delta = ((x1 - x0) / chart.series.length) * w;
        if (am4core.isNumber(delta)) {
            var middle = chart.series.length / 2;

            var newIndex = 0;
            chart.series.each(function(series) {
                if (!series.isHidden && !series.isHiding) {
                    series.dummyData = newIndex;
                    newIndex++;
                }
                else {
                    series.dummyData = chart.series.indexOf(series);
                }
            })
            var visibleCount = newIndex;
            var newMiddle = visibleCount / 2;

            chart.series.each(function(series) {
                var trueIndex = chart.series.indexOf(series);
                var newIndex = series.dummyData;

                var dx = (newIndex - trueIndex + middle - newMiddle) * delta

                series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
                series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
            })
        }
    }
}

$("#resetform").click(function(){
    $("#frmadd").removeClass('was-validated');
    $('#vDisplayY').val("").change();
     $("#vDisplayY").focus();
    am4core.disposeAllCharts();
    
    
});