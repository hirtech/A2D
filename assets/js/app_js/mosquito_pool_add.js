var poolMaleCount = 0;
var poolFemaleCount = 0;
var pool_agent_test_arr = [];

$(document).ready(function(){
    pool_agent_test_jsgrid.init();
});;

var pool_agent_test_jsgrid = function(){
    var handleData =function(){
         pool_agent_test_arr = [];
  
        $("#pool_agenttest_jsGrid").jsGrid({
            height: "500px",
            width: "100%",
            editing: true,
            inserting: true,
            sorting: true,
            paging: true,
            autoload: true,
            pageSize: 50,
            pageButtonCount: 5,
            confirmDeleting: false,
            invalidMessage: "",
            invalidNotify: function(args) {
                 var messages = $.map(args.errors, function(error) {
                        return error.message || null;
                    });
               swal("", messages.join("\n"), "error");
            },
            controller: {

                loadData: function(filter) {
                    return $.grep(pool_agent_test_arr, function(client) {
                        return (!filter.Agent || client.Agent === filter.Agent)
                            && (!filter.Test || client.Test === filter.Test);
                    });
                },
                insertItem: function(insertingClient) {
                    pool_agent_test_arr.push(insertingClient);
                },
                updateItem: function(updatingClient) { },
                deleteItem: function(deletingClient) {

                    var clientIndex = $.inArray(deletingClient,pool_agent_test_arr);
                    pool_agent_test_arr.splice(clientIndex, 1);
                                           
                }

            },
            fields: [
                {
                    name: "Agent",
                    type: "select", 
                    items: agent_mosquito_arr, 
                    valueField: "iAMId", 
                    textField: "vTitle", 
                    width: "20%" ,
                    css:"text-left",
                    validate: {
                        validator: "required",
                        message: function(value, item) {
                            return "Please select agent.";
                        }
                    }
                },
                {
                    name: "Test",
                    type: "select", 
                    items: test_method_arr, 
                    valueField: "iTMMId", 
                    textField: "vMethodTitle", 
                    width: "20%" ,
                    css:"text-left",
                    validate: {
                        validator: "required",
                        message: function(value, item) {
                            return "Please select test.";
                        }
                    }
                },
                { type: "control"},
            ]
        });
   }
    return {
        init :function () {
           handleData();
        }
    }
}();


function addMosquitoPoolData(iTMCId,iTTId,mode,maleCount,femaleCount){
	$("#frmadd").removeClass('was-validated');
     $("#errms_countpool").html('');
    $("#errms_numinpool").html('');
    $("#errms_countpool").hide();
    $("#errms_numinpool").hide();
	$("#mp_modaltitle").html('Add Pool');
        $("#mp_mode").val('Add');
        $("#mp_iTMCId").val(iTMCId);
        $("#mp_iTTId").val(iTTId);
        $("#mp_vPool").val('');
        $("#mp_cmmp").val('');
        $("#mp_tnp").val('');
        if(maleCount != ""){
            poolMaleCount = maleCount;
        }else{
            poolMaleCount = 0 ;
        }
        if(femaleCount != ""){
            poolFemaleCount = femaleCount;
        }else{
            poolFemaleCount = 0 ;
        }
       
       $("#poolgridchk").prop('checked',false);
        $("#pool_result_grid").addClass('d-none');
        pool_agent_test_jsgrid.init();

	 $("#mpsquitopool_box").trigger('click');

}

$("#poolgridchk").change(function(){
    if($("#poolgridchk").prop('checked') == true){
        $("#pool_result_grid").removeClass('d-none');
    }else{
        $("#pool_result_grid").addClass('d-none');
    }
});


$("#save_data").click(function(){

    $('#save_loading').show();
    $("#save_data").prop('disabled', true);

    $("#errms_countpool").html('');
    $("#errms_numinpool").html('');
    $("#errms_countpool").hide();
    $("#errms_numinpool").hide();

    var form = $("#frmadd");
    
   var isError = 0;
   var iscountpoolError = 0;
    var isnumtotError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
        iscountpoolError =1;
        isnumtotError = 1;
    }
    form.addClass('was-validated');
    //check count of mosquito value valid or not
    if(isNaN($("#mp_cmmp").val())){
        $("#errms_countpool").html('Please enter number. Example: Enter 37 in this field, if 37 mosquito are to be divided among 8 pools');
        $("#errms_countpool").show();
        isError = 1;
        iscountpoolError =1;
    }else{
        if(parseInt($("#mp_cmmp").val()) <= 0){
            $("#errms_countpool").html('Please enter number greater than zero. Example: Enter 37 in this field, if 37 mosquito are to be divided among 8 pools');
            $("#errms_countpool").show();
            isError = 1;
            iscountpoolError = 1;
        }else{
            $("#errms_countpool").html('');
            $("#errms_countpool").hide();
        }
    }
    //check total number value valid or not
    if(isNaN($("#mp_tnp").val())){
        $("#errms_numinpool").html('Please enter number. Example: Enter 8 in this field, if 37 mosquito are to be divided among 8 pools');
        $("#errms_numinpool").show();
        isError = 1;
        isnumtotError = 1;
    }else{
        if(parseInt($("#mp_tnp").val()) <= 0){
            $("#errms_numinpool").html('Please enter number. Example: Enter 8 in this field, if 37 mosquito are to be divided among 8 pools');
            $("#errms_numinpool").show();
            isError = 1;
            isnumtotError = 1;
        }else{
            $("#errms_numinpool").html('');
            $("#errms_numinpool").hide();
        }
    }
    //check count of mosquito value is accrding male and female count
    if(iscountpoolError == 0 && isnumtotError  == 0 ){
        if($("#mp_vPool").val() == "Male" && (parseInt($("#mp_cmmp").val()) > poolMaleCount )){
             $("#errms_countpool").html('Please enter a value of MALE count to be divided among pools less than or equal to '+poolMaleCount);
                $("#errms_countpool").show();
                isError = 1;
        }else if($("#mp_vPool").val() == "Female" && (parseInt($("#mp_cmmp").val()) > poolFemaleCount )){
                $("#errms_countpool").html('Please enter a value of FEMALE count to be divided among pools less than or equal to '+poolFemaleCount);
                $("#errms_countpool").show(); 
                isError = 1;
        }else{
            //check total number pool is not greater than count of mosquito pool
            if(parseInt($("#mp_tnp").val()) > parseInt($("#mp_cmmp").val())){
                $("#errms_numinpool").html('The total count of mosquito to be divided into pools cannot be less than the number of pools being created');
                $("#errms_numinpool").show();
                isError = 1;
            }else{
                $("#errms_numinpool").html('');
                $("#errms_numinpool").hide();
            }
        }
    }

    if(isError == 0){
        var data_str = $("#frmadd").serializeArray();
        data_str.push({name: 'pool_agent_test_arr', value: JSON.stringify(pool_agent_test_arr)});

        $.ajax({
            type: "POST",
            url: site_url+"lab_task/mosquito_pool_list",
            data: data_str,
            dataType : 'json',
            success: function(response){
                $('#save_loading').hide();   
                $("#save_data").prop('disabled', false);

                
                if(typeof response.mosquitocount_error != "undefined" && response.mosquitocount_error != 0 ){
                        $("#errms_countpool").html(response['msg']);
                        $("#errms_countpool").show();
                        $("#mp_cmmp").focus();
                }else{
                    $("#closestbox").trigger('click');
                   if(response['error'] == "0"){
                        toastr.success(response['msg']);
                   }else{
                        toastr.error(response['msg']);
                    } 
                }
            
               
            }
        });
    }else{
        $('#save_loading').hide();   
        $("#save_data").prop('disabled', false);
    }
});