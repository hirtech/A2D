(function ($) {
    "use strict";

    $("#jsGrid_table").jsGrid({
        height: "500px",
        width: "100%",
        editing: true,
        inserting: false,
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
            /*insertItem: function(item){
                var d = $.Deferred();

                $.ajax({
                    type: "POST",
                    url: site_url+"lab_task/task_mosquito_count&mode=Add&iTTId="+$("#iTTId").val(),
                    data:item
                }).done(function(data) {
                    var response =JSON.parse(data);
                        if(response['error'] == "0"){
                            toastr.success(response['msg']);
                             $.each(item, function(key, value){
                                if(key == "iTMCId"){
                                    item[key] = response['iTMCId'];
                                }
                            });
                            d.resolve(item);
                        }else{
                            toastr.error(response['msg']);
                        }
                    
                });
                return d.promise();
            
            },*/
            updateItem: function(updateitem) {
                var d = $.Deferred();
                $.ajax({
                    type: "POST",
                    url: site_url+"lab_task/task_mosquito_count&mode=Update&iTTId="+$("#iTTId").val(),
                    data:updateitem
                }).done(function(data) {
                    var response =JSON.parse(data);
                        if(response['error'] == "0"){
                            toastr.success(response['msg']);
                            if(response['iTMCId'] !== undefined){
                                $.each(updateitem, function(key, value){
                                    if(key == "iTMCId"){
                                        updateitem[key] = response['iTMCId'];
                                    }
                                });
                            }
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
                name :"iTMCId",
                type: "hidden", 
                css : "d-none",
                
            },
            {
                name :"iMSpeciesId",
                type: "hidden", 
                css : "d-none",
                
            },
            {
                name: "Species",
                type: "",  
                width: "55%" ,
                css:"text-left",
            },
            {
                name: "Male", 
                type: "text", 
                width: "15%",  
                validate: {
                    validator: function(value) {
                        if (value >=0 && $.isNumeric(value) && (value.indexOf('.') == -1)){
                            //return "required";
                            return true;
                        }
                    },
                    message: function(value, item) {
                        return "please enter valid count";
                    }
                },
                editTemplate: function(value,item) {
                    var data = value;
                    if(data == 0){
                        data = "";
                    }
                    return this._editPicker = $("<input>").attr('type',"text").val(data);
                },
                editValue: function() {
                    return this._editPicker.val().toString();
                }
            },
            {
                name: "Female", 
                type: "text", 
                width: "15%" , 
                validate:{
                    validator: function(value) {
                        if (value >=0 && $.isNumeric(value) && (value.indexOf('.') == -1)){
                            //return "required";
                            return true;
                        }
                    },
                    message: function(value, item) {
                        return "please enter valid count";
                    }
                },

                editTemplate: function(value,item) {
                    var data = value;
                    if(data == 0){
                        data = "";
                    }
                    return this._editPicker = $("<input>").attr('type',"text").val(data);
                },
                editValue: function() {
                    return this._editPicker.val().toString();
                }
            },
            {
                type: "control",
                width: "15%" , 
                editButton: true, 
                /*deleteButton: true,*/
                itemTemplate: function(value, item) {
                    var $result = jsGrid.fields.control.prototype.itemTemplate.apply(this, arguments);
                    var $customEditButton ="";
                    var $customDeleteButton = "";
                    var $customAddButton = "";
                    //custome edit button
                    if(access_group_var_edit == '1') {
                      $customEditButton = $("<button>").attr({class: "customGridEditbutton jsgrid-button jsgrid-edit-button"});
                    }
                    //custom pool add button
                    if(mosq_pool_access_group_var_add == '1'){
                        if(item.iTMCId > 0 && (item.Male > 0 || item.Female >0)){
                            $customAddButton = $("<button>").attr({class: "jsgrid-button jsgrid-insert-button" , title : "Add Pool"})
                              .click(function(e) {
                                    /*console.log(item.iTMCId);
                                    console.log(iTTId);*/
                                    addMosquitoPoolData(item.iTMCId,iTTId,'AddPool',item.Male,item.Female);
                                
                                e.stopPropagation();
                                
                            });
                        }
                    }
                    //custome delete button
                    /*if(access_group_var_delete == '1') { 
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
                                                url: site_url+"lab_task/task_mosquito_count&mode=Delete&iTTId="+$("#iTTId").val(),
                                                data:{ 'iTMCId' : item.iTMCId},
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
                    }*/

                    return $("<div>").append($customEditButton).append($customAddButton);
                    //return $("<div>").append($customEditButton).append($customDeleteButton).append($customAddButton);
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
            url: site_url+"tasks/task_trap_list&mode=setLabWorkCount",
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
