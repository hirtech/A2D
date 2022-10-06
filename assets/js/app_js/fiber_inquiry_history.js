function showNearbySr(lat,long,meter)
{	
	$('#nearsrmodal').modal('show');
	$.ajax({
		type:"POST",
		url: site_url+"fiber_inquiry/add",
		data: {
			"mode" : "nearby_sr",
			"lat" : lat,
			"long" : long,
			"meter" : meter
		},
		cache: false,
		success:function(data){
			//console.log(data);
			var json = $.parseJSON(data);
			
			nearsr_list=json['nearfiber_inquiry_list'];
			var html='';
            if ( nearsr_list.length != 0 ) {
             	for (var i=0;i<nearsr_list.length;++i)
             	{
             		if(nearsr_list[i].Description==null)
             		{
             			nearsr_list[i].Description='-';
             		}
             		html+='<tr><td class="text-center">'+nearsr_list[i].Date+'</td><td>'+nearsr_list[i].Name+'</td><td>'+nearsr_list[i].Description+'</td></tr>';
             	}
            }
            else
            {
             	html+='<tr><td colspan=3>No record found</tr>';
            }
            $('#nearsr_tbody2').html(html);
            console.log(nearsr_list.length);
         }
     });
}