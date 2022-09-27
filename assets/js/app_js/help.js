$(document).ready(function(){
	var iHLId;
	$("#myModal").on('show.bs.modal', function (event) {
		var button	= $(event.relatedTarget);
		var modal		= $(this);
		var title=button.data('title');
		modal.find('.modal-title').text(title);
		iHLId = button.data('id');
		$.ajax({
			type:"POST",
			url: site_url+"help/list",
			data: {
				"mode" : "slider_listing",
				"iHLId" : iHLId
			},
			cache: false,
			success:function(data){
				//console.log(data);
				var json = $.parseJSON(data);
				if ( json.length != 0 ) {
				var active='';
				html='<div class="card-body p-0"><div id="carouselExampleCaption" class="carousel slide" data-ride="carousel">';
				html+='<div class="carousel-inner">';
				for (var i=0;i<json.length;++i)
				{
					var active='';
					if(i==0)
					{
						var active='active';
					}
					html+='<div class="carousel-item '+active+'">';
						if(json[i].vImage != ''){
							html+='<img class="d-block w-100" src="'+json[i].vImageUrl+'" alt='+json[i].vSlideLabel+'>';
						}
						html+='<div class="carousel-caption d-none d-md-block">';
							html+='<h5>'+json[i].vSlideLabel+'</h5>';
							if(json[i].vSlideDescription != ''){
								html+='<p>'+json[i].vSlideDescription+'</p>';
							}
						html+='</div>';
					html+='</div>';
				}
				html +='<a class="carousel-control-prev" href="#carouselExampleCaption" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">Previous</span></a><a class="carousel-control-next" href="#carouselExampleCaption" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">Next</span></a></div></div>';
			}
			else
			{
				html="<font color=red>No Data Found</font>";
			}
				$('#accordion_details').html(html);
				$('#dataModal').modal('show');
			}
		});
	});
});  
