<?php
include_once ($site_path . "scripts/session_valid.php");
//echo "<pre>";print_r($_SESSION);exit();

/************ Dashboard Glance ************/ 
$API_URL = $site_url . "api/v2/dashboard_glance.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix])));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
));
$response = curl_exec($ch);
curl_close($ch);
$res = json_decode($response, true);
$dashbaord_glance = $res['result'];
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
?>
