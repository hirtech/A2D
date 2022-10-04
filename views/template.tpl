<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
 	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<title>{$SITE_TITLE}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<base href="{$site_url}" />
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/png">
 	<!-- START: Template CSS-->
    <link rel="stylesheet" href="assets/vendors/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendors/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/vendors/jquery-ui/jquery-ui.theme.min.css">
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">        
    <link rel="stylesheet" href="assets/vendors/flags-icon/css/flag-icon.min.css"> 
    <link rel="stylesheet" href="assets/vendors/flag-select/css/flags.css">
    <!-- END Template CSS-->

    <!-- START: Page CSS-->   
    <link rel="stylesheet" href="assets/vendors/morris/morris.css"> 
    <link rel="stylesheet" href="assets/vendors/weather-icons/css/pe-icon-set-weather.min.css"> 
    <link rel="stylesheet" href="assets/vendors/chartjs/Chart.min.css"> 
    <link rel="stylesheet" href="assets/vendors/starrr/starrr.css"> 
    <link href="assets/vendors/bootstrap-tour/css/bootstrap-tour-standalone.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="assets/vendors/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/vendors/ionicons/css/ionicons.min.css"> 
    <link rel="stylesheet" href="assets/vendors/cryptofont/cryptofont.css">  
    <!-- END: Page CSS-->
    <!-- START: Custom CSS-->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <!-- END: Custom CSS-->
    <script src="assets/vendors/jquery/jquery-3.3.1.min.js"></script>
     <!-- START: Sweet alert CSS-->
    <link rel="stylesheet" href="assets/vendors/sweetalert/sweetalert.css">
    <!-- END: Sweet alert CSS-->

    <link rel="stylesheet" href="assets/vendors/toastr/toastr.min.css"/>

    <link rel="stylesheet" href="assets/vendors/bootstrap4-toggle/css/bootstrap4-toggle.min.css" />
    
    <script>
		var site_url = '{$site_url}';
		var currenttime = '{$currenttime}';
		var iAGroupId = '{$sess_iAGroupId}';
		var js_date_picker_date = '{$js_date_picker_date}';
		var map_refresh_time = '{$MAP_REFRESH_TIME}';
		var access_group_var_delete = '{$access_group_var_delete}';
		var access_group_var_status = '{$access_group_var_status}';
		var access_group_var_add = '{$access_group_var_add}';
		var access_group_var_edit = '{$access_group_var_edit}';
		var access_group_var_Respond = '{$access_group_var_Respond}';
		var access_group_var_CSV = '{$access_group_var_CSV}';
		var access_group_var_PDF = '{$access_group_var_PDF}';
		var page = '{$page}';
		var MAP_LATITUDE = '{$MAP_LATITUDE}';
		var MAP_LONGITUDE = '{$MAP_LONGITUDE}';
		var iCustomizationId = '{$iCustomizationId}';
        {if $sess_GoogleMapCountryCode neq ''}
            var GoogleMapCountryCode = '{$sess_GoogleMapCountryCode}';
        {else}
            var GoogleMapCountryCode = 'us';
        {/if}

        var sess_iUserId = '{$sess_iUserId}';
        var PageLengthMenuArr = [10, 15, 20, 30, 50, 100, 200];
		var REC_LIMIT = '{$REC_LIMIT}';

        var user_panel_theme_arr = {$user_panel_theme_arr|@json_encode};

	</script>
</head>
<body id="main-container" class="default {if $page == 'c-home'} login-screen {/if}">
	<div class="loader-section d-none" id="loader-sections">
		<div class="loader">
		  <span></span>
		  <span></span>
		  <span></span>
		  <span></span>
		</div>
	</div>
	{if $page != 'c-home' && $page != 'm-admin_login'}
	   {include file="top/top_home.tpl"}
	   {include file="left/left_menu.tpl"}
    {/if}
    <!-- START: Main Content-->
    <main>
        <div class="container-fluid">
        	{include file="$module/$module_prefix$script.tpl"}
        </div>
    </main>
    <!-- END: Content-->
    {if $page neq 'c-home' && $page != 'm-admin_login'  && isset($sess_iUserId)}
    {include file="bottom/bottom_home.tpl"}
    {/if}
    {include file="general/js.tpl"}
</body>
</html>

