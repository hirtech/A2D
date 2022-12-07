$(document).ready(function() {
    $('select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
          width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
    });

    var cluster = new Bloodhound({
        datumTokenizer: function(d) { return d.tokens; },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: site_url+'service_order/workorder_add&mode=search_premise',
            replace: function(url, uriEncodedQuery) {
                var newUrl = url + '&vPremiseName=' + uriEncodedQuery;
                return newUrl;
            },
            filter: function(list) {
                if(list==null)
                    return {};
                else
                    return $.map(list, function(rawdata) { return { display: rawdata.display, iPremiseId:rawdata.iPremiseId }; });
            } 
        }
    });
    cluster.initialize();
    select = false;
    $('#vPremiseName').typeahead({hint: false, highlight: true,minLength: 1 }, 
    {
        displayKey: 'display',
        source: cluster.ttAdapter(),
    })
    .on('typeahead:selected', onPremiseClusteSelected)
    .off('blur')
    .blur(function() {
        $(".tt-dropdown-menu").hide();
    });

    var clusterSO = new Bloodhound({
        datumTokenizer: function(d) { return d.tokens; },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: site_url+'service_order/workorder_add&mode=search_service_order',
            replace: function(url, uriEncodedQuery) {
                var newUrl = url + '&vServiceOrder=' + uriEncodedQuery;
                return newUrl;
            },
            filter: function(list) {
                if(list==null)
                    return {};
                else
                    return $.map(list, function(rawdata) { return { display: rawdata.display, iServiceOrderId:rawdata.iServiceOrderId }; });
            } 
        }
    });
    clusterSO.initialize();
    select = false;
    $('#vServiceOrder').typeahead({hint: false, highlight: true,minLength: 1 }, 
    {
        displayKey: 'display',
        source: clusterSO.ttAdapter(),
    })
    .on('typeahead:selected', onServiceOrderClusteSelected)
    .off('blur')
    .blur(function() {
        $(".tt-dropdown-menu").hide();
    });
});

function onPremiseClusteSelected(e, datum){
    $("#search_iPremiseId").val(datum['iPremiseId']);
    $("#vPremiseName").val(datum['display']);
}

function onServiceOrderClusteSelected(e, datum){
    $("#search_iServiceOrderId").val(datum['iServiceOrderId']);
    $("#vServiceOrder").val(datum['display']);
}

$("#save_data").click(function(){
    $('#save_loading').show();   
    $("#save_data").prop('disabled', true);
   // $('#save_loading').show();
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
        var form_data = new FormData($("#frmadd")[0]);
        $.ajax({
            type: "POST",
            url: site_url+"service_order/workorder_list",
            data: form_data,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data){
                $('#save_loading').hide();   
                $("#save_data").prop('disabled', false);
                response =JSON.parse(data);
                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                }else{
                    toastr.error(response['msg']);
                }
                setTimeout(function () { location.href = site_url+'service_order/workorder_list';}, 3500);
            }
        });
        return false; 
    }else{
        $('#save_loading').hide();   
        $("#save_data").prop('disabled', false);
    }
});

function clear_serach_premise(){
    $("#vPremiseName").typeahead('val','');
    $("#search_iPremiseId").val('');
}
function clear_serach_serviceorder (){
    $("#vServiceOrder").typeahead('val','');
    $("#search_iServiceOrderId").val('');
}