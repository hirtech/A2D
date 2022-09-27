$(document).ready(function(){
	$("#contact_form").validate({  
	  ignore: "",  // validate all fields including form hidden input
	  rules: {
	    contact_name:{
	        required:true,
	        minlength:2,
       		maxlength:25,
       		alphaspace:true
	    },
	    contact_email:{
	        required:true,
	        maxlength:30,
	        email:true
	    },
	    contact_subject: {
	    	required:true,
	    	minlength:5,
       		maxlength:255,
	    },
	    contact_phone: {
	    	required:true,
	    	number:true
	    },
	    contact_message:{
	    	required:true,
	    	minlength:5,
       		//maxlength:255,
	    }
	  }, 
	  messages: {
	    contact_name: {
	    	required: "Name is required.",
	    	minlength:"Name must be at least 2 characters.",
      		maxlength:"Name may not be greater than 25 characters.",
      		alphaspace:"Name format is invalid."
	    },
	    contact_email: {
      		required:"Email is required.",
      		maxlength:"Email may not be greater than 30 characters.",
      		email:"Email format is invalid."
      	},
      	contact_subject: {
      		required:"Subject is required.",
      		minlength:"Subject must be at least 5 characters.",
      		maxlength:"Subject may not be greater than 255 characters.",
      	},
      	contact_phone: {
			required:"Phone is required.",
			number: "Phone format is invalid."
      	},
      	contact_message: {
    		required:"Message field is required.",
    		minlength:"Message must be at least 5 characters.",
      		//maxlength:"Message may not be greater than 255 characters.",
      	},       
	  },
	  errorPlacement: function (error, element) {
	  	error.insertAfter(element);
	  },
	  submitHandler: function (form) {
	  	var form = $("#contact_form").serializeArray();

	  		$.ajax({
				type: "POST",
				url : 'ajax_front.php?mode=contact_us',
				data: form,
				success: function(response){
					if(response==0) {
						$("#contact_msg").html('Error while sending your mail.');
						$("#contact_msg").addClass('text-danger').removeClass('text-success d-none');
					}else{
						$("#contact_msg").html('Email has been sent succesfully.');
						$("#contact_msg").addClass('text-success').removeClass('text-danger d-none');
					}
					
					$("#contact_form")[0].reset();
					setTimeout(function(){ 
						$('#contact_msg').addClass('d-none')
						$("#contact_msg").html('');
					}, 3000);
				}
			});
	  }
	}); 

    // Regex: First name, Last name
    jQuery.validator.addMethod("alphaspace", function(value, element) {
    return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
  });
});