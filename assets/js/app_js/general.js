$(function () {
	//Reset Search form of list 
	$('#Reset').click(function (){
		//$("#vOptions")[0].selectedIndex = 0;
		$("#vOptions").val($("#vOptions option:first").val()).trigger('change'); 
		$('#Keyword').val('');
		//$("#datatable-grid").flexOptions({params: ''}).flexReload();
		$("#datatable-grid").DataTable().draw();
		return false;
	});

	$("#top_notification").click(function(){
		//alert('1111');

		if($("#top_notification_details").is(":visible") == false ){
			$.ajax({
	            type: "POST",
	            url: site_url+"ajax.php?mode=get_top_notification",
	       		dataType: "json",
	            success: function(data){
	            	var str = "";

	            	if(jQuery.isEmptyObject(data) == false){
	            		var textclass = "text-success";
	                   for (var i = 0; i < data.length; i++) {
	                   		str += `<li>${data[i]}</li>`;
	                   }
	                }
	                /*else{
	                	str = `<li><a class='dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0' href='javascript:void(0);'>
	                			<div class="media">
	                				<div class="media-body"><h6 class="mb-0 text-success">No notification found!</h6></div>
                                </div></a>
                               </li>`;
	                }*/
	                //$("#top_notification_details").removeClass('d-none');
	               $("#top_notification_details").html(str);
	                
	            }
	        });	
	    }
	});
});
/************************************************************************/
function CreateBookmarkLink(title,url) {
	 
	if (window.sidebar) { // Mozilla Firefox Bookmark
		window.sidebar.addPanel(title, url,"");
	} else if( window.external ) { // IE Favorite
		window.external.AddFavorite( url, title); }
	else if(window.opera && window.print) { // Opera Hotlist
		return true; }
 }
function checkString(str, value, length)
{
	for(i=0;i<length;i++)
	{
		ch1=value.charAt(i);
		rtn1=str.indexOf(ch1);
		if(rtn1==-1)
			return false;
	}
	return true;
}
function phoneformate(value,length)
{
	chk1="+1234567890()- ";
	return checkString(chk1, value, length);
}
//********************* functions  for email-id validation ****************************//
function isValidEmail(emailStr, msg_id) {
	var checkTLD=1;
	var knownDomsPat=/^(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum)$/;
	var emailPat=/^(.+)@(.+)$/;
	var specialChars="\\(\\)><@,;:\\\\\\\"\\.\\[\\]";
	var validChars="\[^\\s" + specialChars + "\]";
	var quotedUser="(\"[^\"]*\")";
	var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/;
	var atom=validChars + '+';
	var word="(" + atom + "|" + quotedUser + ")";
	var userPat=new RegExp("^" + word + "(\\." + word + ")*$");
	var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$");
	var matchArray=emailStr.match(emailPat);
	if (matchArray==null) {
		//alert(" ( "+ emailStr +" ) Email address seems incorrect (check @ and .'s)");
		$('#'+msg_id).html(" ( "+ emailStr +" ) Email address seems incorrect (check @ and .'s)").show();
		return false;
	}
	var user=matchArray[1];
	var domain=matchArray[2];
	// Start by checking that only basic ASCII characters are in the strings (0-127).
	for (i=0; i<user.length; i++) {
		if (user.charCodeAt(i)>127) {
			//alert(" ( "+ emailStr +" ) Ths username contains invalid characters.");
			$('#'+msg_id).html(" ( "+ emailStr +" ) Ths username contains invalid characters.").show();
			return false;
		}
	}
	for (i=0; i<domain.length; i++) {
		if (domain.charCodeAt(i)>127) {
			//alert(" ( "+ emailStr +" ) Ths domain name contains invalid characters.");
			$('#'+msg_id).html(" ( "+ emailStr +" ) Ths domain name contains invalid characters.").show();
			return false;
		}
	}
	if (user.match(userPat)==null) {
		//alert(" ( "+ emailStr +" ) The username doesn't seem to be valid.");
		$('#'+msg_id).html(" ( "+ emailStr +" ) The username doesn't seem to be valid.").show();
		return false;
	}
	var IPArray=domain.match(ipDomainPat);
	if (IPArray!=null) {
		for (var i=1;i<=4;i++) {
			if (IPArray[i]>255) {
				//alert(" ( "+ emailStr +" ) Destination IP address is invalid!");
				$('#'+msg_id).html(" ( "+ emailStr +" ) Destination IP address is invalid!").show();
				return false;
	   		}
		}
		return true;
	}
	var atomPat=new RegExp("^" + atom + "$");
	var domArr=domain.split(".");
	var len=domArr.length;
	for (i=0;i<len;i++) {
		if (domArr[i].search(atomPat)==-1) {
			//alert(" ( "+ emailStr +" ) The domain name does not seem to be valid.");
			$('#'+msg_id).html(" ( "+ emailStr +" ) The domain name does not seem to be valid.").show();
			return false;
	   }	
	}
	if (checkTLD && domArr[domArr.length-1].length!=2 && 
		domArr[domArr.length-1].search(knownDomsPat)==-1) {
		//alert(" ( "+ emailStr +" ) The address must end in a well-known domain or two letter " + "country.");
		$('#'+msg_id).html(" ( "+ emailStr +" ) The address must end in a well-known domain or two letter " + "country.").show();
		return false;
	}

// Make sure there's a host name preceding the domain.

	if (len<2) {
		alert(" ( "+ emailStr +" ) This address is missing a hostname!");
		$('#'+msg_id).html(" ( "+ emailStr +" ) This address is missing a hostname!").show();
		return false;
	}	
	return true;
}


function Trim(s) 
{
  // Remove leading spaces and carriage returns

  while ((s.substring(0,1) == ' ') || (s.substring(0,1) == '\n') || (s.substring(0,1) == '\r'))
  {
    s = s.substring(1,s.length);
  }

  // Remove trailing spaces and carriage returns

  while ((s.substring(s.length-1,s.length) == ' ') || (s.substring(s.length-1,s.length) == '\n') || (s.substring(s.length-1,s.length) == '\r'))
  {
    s = s.substring(0,s.length-1);
  }
  return s;
}
function pollwin(url,w, h)
{

	pollwindow=window.open(url,'pollwindow','top=0,left=0,status=no,toolbars=no,scrollbars=yes,width='+w+',height='+h+',maximize=no,resizable');
	pollwindow.focus();
}
function isRadioChecked(obj)
{
	for(i=0;i< obj.length;i++)
	{
		if(obj[i].checked == true)
		return true;
	}
	return false;
}
// Added by Keyur Mistry date on 2/13/2013.....
function readURL(input, type){
	var isIE = (navigator.appName=="Microsoft Internet Explorer");

	if(isIE)
	{
		var path = $(input).val();
		var fileName = path.substring(path.lastIndexOf('\\')+1);

		if($('#img_name_'+type))
		{
			$('#img_name_'+type).html(fileName);
			$('#img_name_'+type).show();
		}
	}
	else
	{
		if (input.files && input.files[0]) {
			//maximum 100kb of size image validation.....
			if(input.files[0].size > MAX_FILE_UPLOAD_SIZE) {
				alert(image_max_sie_upload_alert_msg)
				//alert("Please Upload max size of "+Math.round(MAX_FILE_UPLOAD_SIZE / 1024)+"KB");
				return false;
			}
			// End.....
		}
	}
}
function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function print_content(obj){

	$(obj).hide();
	$('#wrappertop').hide();
	$('#wrapperbottom').hide();
	$('#wrappermiddle').css({"margin-bottom":"0px", "margin-top":"0px"});
	$('body').css({"border-top":"none", "background":"none"});
	$('.gridOuter').css({"border":"0px", "padding":"0px"});
	
	window.print();

	$(obj).show();
	$('#wrappertop').show();
	$('#wrapperbottom').show();
	$('#wrappermiddle').css({"margin-bottom":"50px", "margin-top":"35px"});
	$('body').css({"border-top":"3px solid #919191", "background":"url(images/BG.jpg) repeat"});
	$('.gridOuter').css({"border":"#CCC solid 1px", "padding":"10px"});
}

function popitup(url, name, width, height) {
	var newwindow = window.open(url, name,'height='+height,'width='+width);
	if (window.focus) {newwindow.focus()}
	return false;
}

function zoomSlider(map, minZoom, maxZoom, divId) {
    
    this.map_ = map;
    this.minZoom_ = minZoom || 0;
    this.maxZoom_ = maxZoom || 21;
    this.divId_ = divId || "zoomSlider";
    
    this.initZoomSlider();
}

/* Initialises the object, creating the two zoom bottons and the slider between them.
 * 
 * @param {object}  map         Google.maps.map object to be used for the georeports.
 * @param {int}     minZoom     Min. zoom level -> Default value is 0
 * @param {int}     maxZoom     Max. zoom level -> Default value is 21 
*/

zoomSlider.prototype.initZoomSlider = function() {
    
    var btnInc = document.createElement("div"),
        btnDec = document.createElement("div"),
        slider = document.createElement("input"),
        zoomSlider = document.getElementById(this.divId_),
        that = this;
    
    btnInc.setAttribute("id","incZoom");
    btnInc.setAttribute("class","zoomBtn");
    btnInc.onclick = function () {
        that.increaseZoom();
    };
    
    btnDec.setAttribute("id","decZoom");
    btnDec.setAttribute("class","zoomBtn");
    btnDec.onclick = function () {
        that.decreaseZoom();
    };

    slider.value = this.map_.getZoom();
    slider.setAttribute("min", this.minZoom_);
    slider.setAttribute("max", this.maxZoom_);
    slider.setAttribute("step", "1");
    slider.setAttribute("type", "range");
    slider.setAttribute("id", "slide");
    slider.setAttribute("orient", "vertical");
    
    slider.onchange=function() {
        that.updateSlider(this.value);
    }; 
    
    zoomSlider.appendChild(btnInc);
    zoomSlider.appendChild(slider);
    zoomSlider.appendChild(btnDec);

    if(page == 'm-aerial_larviciding_edit' || page == 'm-aerial_workflow_edit')
    {
    	zoomSlider.index = 1;
	  	this.map_.controls[google.maps.ControlPosition.TOP_LEFT].push(zoomSlider);
	}

    // Updates the slider when the zoom level of the map changes.
    this.map_.addListener('zoom_changed', function() {
        var sl = document.getElementById("slide");
        if (sl.value != that.map_.getZoom()) {
            sl.value = that.map_.getZoom();
        }
    });
};

// Increases the zoom level by 1
zoomSlider.prototype.increaseZoom = function() {
    var currentZoom = this.map_.getZoom();
    this.map_.setZoom(++currentZoom);
};

// Decreases the zoom level by 1
zoomSlider.prototype.decreaseZoom = function() {
    var currentZoom = this.map_.getZoom();
    this.map_.setZoom(--currentZoom);
};

// Sets the zoom level to the given value 
zoomSlider.prototype.updateSlider = function(slideAmount) {
    this.map_.setZoom(parseInt(slideAmount));
};

var specialKeys = new Array();
specialKeys.push(8); //Backspace
function isNumberKey(e) {
    var keyCode = e.which ? e.which : e.keyCode
    var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    return ret;

}

function isDecimalKey(evt, element) {
    var charCode = (evt.which) ? evt.which : event.keyCode

    if (
        (charCode != 45 || $(element).val().indexOf('-') != -1) && // “-” CHECK MINUS, AND ONLY ONE.
        (charCode != 46 || $(element).val().indexOf('.') != -1) && // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57)
       )
        return false;

    return true;
}

function checkall(obj) {
    $('.list').prop('checked', obj.checked);
    return false;
}

