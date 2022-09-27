$("#import_data").click(function(){
	$('#save_loading').show();   
    $("#import_data").prop('disabled', true);
	var form = $("#frmimport");

    var isError = 0;
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        isError = 1;
    }
    form.addClass('was-validated');


    if(isError == 0){

    	 var formData = new FormData(form[0]);

    	$.ajax({
            type: "POST",
            url: site_url + "import/import_file",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType : 'json',
            success: function (response) {
                $('#save_loading').hide();   
                $("#import_data").prop('disabled', false);

                    if(response['error'] == "0"){
                        toastr.success(response['msg']);
                    }else{
                        if(response['error_flag'] == '2'){
                           var data = response['data'] ;

                           swal({
                                title: "",
                                text: response['msg'],
                                type: "warning",
                                showCancelButton: false,
                                closeOnConfirm: true
                            });
                           var html_tab = "";
                           var valid_tab = "";
                           var invalid_tab = "";
                            if(jQuery.isEmptyObject(data['valid_data']) == false  || jQuery.isEmptyObject(data['invalid_data']) == false){
                               if(jQuery.isEmptyObject(data['valid_data']) == false ){
                                    var valid_rec = data['valid_data'];
                                    for (i = 0; i < valid_rec.length; i++) {
                                        valid_tab += "<tr>";
                                            valid_tab += "<td>"+valid_rec[i]['zone']+"</td>";
                                            valid_tab += "<td>"+valid_rec[i]['sitename']+"</td>";
                                            valid_tab += "<td>"+valid_rec[i]['productname']+"</td>";
                                            valid_tab += "<td>"+valid_rec[i]['productcode']+"</td>";
                                        valid_tab += "</tr>";
                                    }
                               }

                               if(jQuery.isEmptyObject(data['invalid_data']) == false ){
                                    var invalid_rec = data['invalid_data'];
                                    for (i = 0; i < invalid_rec.length; i++) {
                                        invalid_tab += "<tr>";
                                            invalid_tab += "<td>"+invalid_rec[i]['zone']+"</td>";
                                            invalid_tab += "<td>"+invalid_rec[i]['sitename']+"</td>";
                                            invalid_tab += "<td>"+invalid_rec[i]['productname']+"</td>";
                                            invalid_tab += "<td>"+invalid_rec[i]['productcode']+"</td>";
                                        invalid_tab += "</tr>";
                                    }
                               }
                           
                                html_tab += '<div class="row col-12">';
                                html_tab +=  '<h5 class="ml-2 mb-3"><strong>'+$( "#impOptions option:selected" ).text()+' Data :</strong></h5>';
                           
                               if(valid_tab != ""){
                                    html_tab += '<div class="row col-12 table-responsive ml-2">';
                                        html_tab += '<p><strong>Valid Record</strong></p>';
                                        html_tab += '<table class="table table-bordered">';
                                            html_tab += '<thead><tr>';
                                                html_tab += '<th>Zone</th>';
                                                html_tab += '<th>Premise Name</th>';
                                                html_tab += '<th>Product Name</th>';
                                                html_tab += '<th>Product Code</th>';
                                            html_tab += '</tr></thead>';
                                            html_tab += '<tbody>';
                                            html_tab += valid_tab;
                                            html_tab += '</tbody>';
                                        html_tab += '</table>';
                                    html_tab += '</div>';
                               }

                               if(invalid_tab != ""){
                                    html_tab += '<div class="row col-12 table-responsive ml-2">';
                                        html_tab += '<p><strong>Invalid Record</strong></p>';
                                        html_tab += '<table class="table table-bordered">';
                                            html_tab += '<thead><tr>';
                                                html_tab += '<th>Zone</th>';
                                                html_tab += '<th>Premise Name</th>';
                                                html_tab += '<th>Product Name</th>';
                                                html_tab += '<th>Product Code</th>';
                                            html_tab += '</tr></thead>';
                                            html_tab += '<tbody>';
                                            html_tab += invalid_tab;
                                            html_tab += '</tbody>';
                                        html_tab += '</table>';
                                    html_tab += '</div>';
                               }

                            html_tab += '</div>';
                        }
                           
                            $("#import_error_records").html(html_tab);     
                           
                        }else{
                            toastr.error(response['msg']);
                        }
                        
                    }
                    //form[0].reset();
                $("#impOptions").val('');
                $("#importfile").val('');
                $("#frmimport").removeClass('was-validated');
            },
            error: function(xhr, textStatus, errorThrown) {
                    //alert('Nastala chyba. ' + errorThrown);
            }
        });
            return false;         
    }else{
        $('#save_loading').hide();   
        $("#import_data").prop('disabled', false);
    }

});

$("#reset_import").click(function(){
    $('#save_loading').hide();   
    $("#import_data").prop('disabled', false);

    $("#import_error_records").html('');  
});