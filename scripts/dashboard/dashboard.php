<?php
include_once ($site_path . "scripts/session_valid.php");
//echo "<pre>";print_r($_REQUEST);exit();
$userid =  $_SESSION["sess_iUserId".$admin_panel_session_suffix];
$iAGroupId =  $_SESSION["sess_iAGroupId".$admin_panel_session_suffix];
$iAccessType =  $_SESSION["sess_iAccessType".$admin_panel_session_suffix];
$iCompanyId = $_SESSION["sess_iCompanyId" . $admin_panel_session_suffix];
$vCompanyAccessType = $_SESSION["sess_vCompanyAccessType" . $admin_panel_session_suffix];

$mode = $_REQUEST['mode'];
if($mode == "dashboard_map"){
	//echo "<pre>";print_r($_REQUEST);exit();
	$MAP_API_URL = $site_url . "api/v2/dashboard_accesgroup_map.json";

	$arr_param = array();
    $arr_param['userId'] = $userid;
    $arr_param['iAGroupId'] = $iAGroupId;
    $arr_param['iAccessType'] = $iAccessType;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
	//echo $MAP_API_URL." ". json_encode($arr_param);exit;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $MAP_API_URL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    "Content-Type: application/json",
	));
	$response_map = curl_exec($ch);
	curl_close($ch);
	$res_map = json_decode($response_map, true);
	$site_arr = $res_map['result'];
	//echo "<pre>";print_r($site_arr);exit();
	echo json_encode($site_arr);
    hc_exit();
}
/************ Dashboard Glance ************/ 
$glance_arr_param['iCompanyId'] 			= $iCompanyId;
$glance_arr_param['vCompanyAccessType'] 	= $vCompanyAccessType;
$glance_arr_param['sessionId'] 			= $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$GLANCE_API_URL = $site_url . "api/v2/dashboard_glance.json";
//echo json_encode($glance_arr_param). " ".$GLANCE_API_URL;exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $GLANCE_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($glance_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
));
$response = curl_exec($ch);
curl_close($ch);
$res = json_decode($response, true);
$dashbaord_glance = $res['result'];
//echo "<pre>";print_r($dashbaord_glance);exit;
$day_glance = $dashbaord_glance['day_galance'];
$month_glance = $dashbaord_glance['month_glance'];
$week_glance = $dashbaord_glance['week_glance'];
$year_glance = $dashbaord_glance['year_glance'];
$smarty->assign("day_glance", $day_glance);
$smarty->assign("month_glance", $month_glance);
$smarty->assign("week_glance", $week_glance);
$smarty->assign("year_glance", $year_glance);
/************ Dashboard Glance ************/

/************ AM chart ************/
$CHART_API_URL = $site_url . "api/v2/dashboard_amchart.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $CHART_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix])));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
));
$response_chart = curl_exec($ch);
curl_close($ch);
$res_chart = json_decode($response_chart, true);
$dashboard_amchart = $res_chart['result'];
//echo "<pre>";print_r($dashboard_amchart);exit();
$smarty->assign("dashboard_amchart", $dashboard_amchart);
/************ AM chart ************/

/************ SO Bar chart ************/
$sobar_arr_param['iCompanyId'] 			= $iCompanyId;
$sobar_arr_param['vCompanyAccessType'] 	= $vCompanyAccessType;
$sobar_arr_param['sessionId'] 			= $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$SOBARCHART_API_URL = $site_url . "api/v2/dashboard_serviceorder_barchart.json";
//echo $SOBARCHART_API_URL." ".json_encode($sobar_arr_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $SOBARCHART_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sobar_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
));
$response_SObarchart = curl_exec($ch);
curl_close($ch);
$res_SObarchart = json_decode($response_SObarchart, true);
$dashboard_SObarchart = $res_SObarchart['result'];
//echo "<pre>";print_r($dashboard_SObarchart);exit();
$smarty->assign("dashboard_SObarchart", $dashboard_SObarchart);
/************ SO Bar chart ************/

/************ WO Bar chart ************/
$wobar_arr_param['iCompanyId'] 			= $iCompanyId;
$wobar_arr_param['vCompanyAccessType'] 	= $vCompanyAccessType;
$wobar_arr_param['sessionId'] 			= $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$WOBARCHART_API_URL = $site_url . "api/v2/dashboard_workorder_barchart.json";
//echo $WOBARCHART_API_URL." ".json_encode($wobar_arr_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $WOBARCHART_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($wobar_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
));
$response_WObarchart = curl_exec($ch);
curl_close($ch);
$res_WObarchart = json_decode($response_WObarchart, true);
$dashboard_WObarchart = $res_WObarchart['result'];
//echo "<pre>";print_r($dashboard_SObarchart);exit();
$smarty->assign("dashboard_WObarchart", $dashboard_WObarchart);
/************ WO Bar chart ************/

/************ My Profile Data ************/
$PROFILE_API_URL = $site_url . "api/v2/dashboard_profile_data.json";
$arr_param = array();
$arr_param['userId'] = $userid;
$arr_param['iAGroupId'] = $iAGroupId;
$arr_param['iCompanyId'] 			= $iCompanyId;
$arr_param['vCompanyAccessType'] 	= $vCompanyAccessType;
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
//echo $PROFILE_API_URL." ". json_encode($arr_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $PROFILE_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
));
$response_profile = curl_exec($ch);
curl_close($ch);
$res_profile = json_decode($response_profile, true);
$dashboard_profile = $res_profile['result']['site'];
$dashboard_serviceorder = $res_profile['result']['site']['Serviceorder'];
$dashboard_workorder = $res_profile['result']['site']['Workorder'];
$dashboard_fiberinquiry = $res_profile['result']['site']['FiberInquiry'];
//echo "<pre>";print_r($dashboard_serviceorder);exit();
$smarty->assign("dashboard_serviceorder", $dashboard_serviceorder);
$smarty->assign("dashboard_workorder", $dashboard_workorder);
$smarty->assign("dashboard_fiberinquiry", $dashboard_fiberinquiry);
/************  My Profile Data  ************/

$smarty->assign("MAP_LATITUDE",$MAP_LATITUDE);
$smarty->assign("MAP_LONGITUDE",$MAP_LONGITUDE);
?>
