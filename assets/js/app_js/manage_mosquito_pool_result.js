(function ($) {
    "use strict";

    $("#jsGrid_table").jsGrid({
        height: "500px",
        width: "100%",
        editing: true,
        inserting: true,
        sorting: true,
        paging: true,
        pageLoading: true,
        autoload: true,
        pageSize: REC_LIMIT,
        pageButtonCount: 5,
        confirmDeleting: false,
        invalidMessage: "",
        invalidNotify: function(args) {
             var messages = $.map(args.errors, function(error) {
                   // return error.message+"<br>" || null;
                   console.log(error.message);
                    return error.message || null;
                });
           // toastr.error(messages);
           swal("", messages.join("\n"), "error");
            //window.alert([this.invalidMessage].concat(messages).join("\n"));
        },
        controller: {
            loadData: function(filter){
                    var deferred = $.Deferred();
                     $.ajax({
                        type: "post",
                        url:site_url+ajax_url,
                        data: filter,
                        dataType:"json",
                        success:function(datas){
                          var da = {
                            data :datas.data,
                            itemsCount : datas.totalRecords
                          }

                           //console.log(da);
                         
                          deferred.resolve(da);
                    }
                    });
                    return deferred.promise();
            },
            insertItem: function(item){
                var d = $.Deferred();

                $.ajax({
                    type: "POST",
                    url: site_url+"lab_task/manage_mosquito_pool_result&mode=Add&iTMPId="+$("#iTMPId").val(),
                    data:item
                }).done(function(data) {
                    var response =JSON.parse(data);
                        if(response['error'] == "0"){
                            toastr.success(response['msg']);
                             $.each(item, function(key, value){
                                if(key == "iTMPRId"){
                                    item[key] = response['iTMPRId'];
                                }
                            });
                            d.resolve(item);
                        }else{
                            toastr.error(response['msg']);
                        }
                    
                });

                return d.promise();
            
            },
            updateItem: function(updateitem) { 
                var d = $.Deferred();
                $.ajax({
                    type: "POST",
                    url: site_url+"lab_task/manage_mosquito_pool_result&mode=Update&iTMPId="+$("#iTMPId").val(),
                    data:updateitem
                }).done(function(data) {
                    var response =JSON.parse(data);
                        if(response['error'] == "0"){
                            toastr.success(response['msg']);
                            
                            d.resolve(updateitem);
                        }else{
                            toastr.error(response['msg']);
                        }
                    
                });

                return d.promise();
            },
         },
        fields: [
            {
                name :"iTMPRId",
                type: "hidden", 
                css : "d-none",
                
            },
            {
                name: "Agent",
                type: "select", 
                items: agent_mosquito_arr, 
                valueField: "iAMId", 
                textField: "vTitle", 
                width: "20%" ,
                //validate: "required",
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
                //validate: "required",
                css:"text-left",
                validate: {
                    validator: "required",
                    message: function(value, item) {
                        return "Please select test.";
                    }
                }
            },
            {
                name: "Value", 
               insertTemplate: function(value){
                return this._insertAuto = $("<input>").attr({"type" : "number" , "step" : "0.01" });
               },
               editTemplate: function(value){
                return this._updateAuto = $("<input>").attr({"type" : "number" , "step" : "0.01" ,"value":value});
               },
                insertValue: function(){
                return this._insertAuto.val();
               },
               editValue: function(val){
                return this._updateAuto.val();
               },
                width: "20%" , 
                css:"text-center",
                /*validate: {
                    validator: function(value) {
                        if (value >0){
                            //return "required";
                            return true;
                        }
                    },
                    message: function(value, item) {
                        return "Please enter valid value.";
                    }
                }*/
            },
            {
                name: "Result",
                type: "select", 
                items: result_arr, 
                valueField: "iResultId", 
                textField: "vResult", 
                width: "20%" ,
                //validate: "required",
                css:"text-left",
                validate: {
                    validator: "required",
                    message: function(value, item) {
                        return "Please select result.";
                    }
                }
            },
            
            {
                type: "control",
                width: "15%" , 
                editButton: true, 
                deleteButton: true,
                itemTemplate: function(value, item) {
                    var $result = jsGrid.fields.control.prototype.itemTemplate.apply(this, arguments);
                    var $customEditButton ="";
                    var $customDeleteButton = "";
                    //custome edit button
                    if(access_group_var_edit == '1') {
                      $customEditButton = $("<button>").attr({class: "customGridEditbutton jsgrid-button jsgrid-edit-button"});
                    }

                    //custome delete button
                    if(access_group_var_delete == '1') { 
                        $customDeleteButton = $("<button>").attr({class: "customGridDeletebutton jsgrid-button jsgrid-delete-button"})
                        .click(function(e) {
                            var d = $.Deferred();
                            swal({
                                title: "Are you sure you want to delete record ?",
                                text: "",
                                type: "warning",
                                showCancelButton: true,
                                //confirmButtonColor: "#DD6B55",
                                confirmButtonClass: 'confirm btn btn-lg btn-danger',
                                cancelButtonClass : 'cancel btn btn-lg btn-default',
                                confirmButtonText: 'Yes, delete it!',
                                cancelButtonText: "No, cancel plx!",
                                closeOnConfirm: false,
                                closeOnCancel: true,
                                },
                                function(isConfirm) {
                                    if (isConfirm) {
                                            $.ajax({
                                                type: "POST",
                                                url: site_url+"lab_task/manage_mosquito_pool_result&mode=Delete",
                                                data:{ 'iTMPRId' : item.iTMPRId},
                                                dataType:"json",
                                            }).done(function(response) {
                                                //var response =JSON.parse(data);
                                                    if(response['error'] == "0"){
                                                        toastr.success(response['msg']);
                                                        $("#jsGrid_table").jsGrid("deleteItem", item);
                                                        d.resolve();
                                                    }else{
                                                        toastr.error(response['msg']);
                                                    }
                                                swal.close();
                                            });
                                        return d.promise();
                                    } else {
                                        swal.close();
                                        //swal("Cancelled", "Your imaginary file is safe :)", "error");
                                    }
                                }
                            );
                            e.stopPropagation();
                        });
                    }

                    return $("<div>").append($customEditButton).append($customDeleteButton);
                    //return $result.add($customButton);
                },
            }
        ]
    });


})(jQuery);

$("#bLabWorkComplete").change(function(){

     var form_data = $("#formlabwork").serializeArray();
     console.log(form_data);
     $.ajax({
            type: "POST",
            url: site_url+"lab_task/mosquito_pool_list&mode=setLabWorkCount",
            data: form_data,
            cache: false,
            success: function(data){
                $('#save_loading').hide();   
                $("#save_data").prop('disabled', false);
                // console.log(data)
                response =JSON.parse(data);
                if(response['error'] == "0"){
                    toastr.success(response['msg']);
                    
                }else{
                    toastr.error(response['msg']);
                }
              
            }
        });
     return false;
});

