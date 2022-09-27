function showContactHistory(id,fname,lname){
    var id=id;
    var fname=fname;
    var lname=lname;
    var titlename=fname+" "+lname+"#"+id;

    $('#contacthistorymodaltitle').val(titlename);
    $('#contacthistorymodal').modal('show');
    $('#contacthistorymodaltitle').html(titlename);

    $.ajax({
        type:"POST",
        url: site_url+"contact/list",
        data: {
            "mode" : "contact_history",
            "contact_history_id" : id
        },
        cache: false,
        success:function(data){
            var json = $.parseJSON(data);
            console.log(json['site_list']);
            site_list=json['site_list'];
            site_details_list=json['site_details_list'];

            // for site deteails //
            var html='';
            if ( site_details_list.length > 0 ) {
                for (var i=0;i<site_details_list.length;++i)
                {
                    html +='<tr><td class="text-center">'+site_details_list[i].iSiteId+'</td><td>'+site_details_list[i].vName+'</td></tr>';
                }
            }
            else
            {
                html='<tr><td colspan=2>No record found</tr>';
            }
            $('#contacthistory_tbody').html(html);

            // for sr deteails //
            var html2='';   
            if (site_list.length > 0) {
                for (var l=0;l<site_list.length;++l)
                {
                    if(site_list[l].tRequestorNotes==null)
                    {
                        site_list[l].tRequestorNotes='';
                    }
                    html2+='<tr><td class="text-center">'+site_list[l].iSRId+'</td><td>'+site_list[l].tRequestorNotes+'</td></tr>';
                }
            }
            else
            {
                html2+='<tr><td colspan=2>No record found</tr>';
            }
            $('#contacthistory_tbody2').html(html2);
        }
    });
}