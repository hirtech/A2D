<?php
include_once ($site_path . "scripts/session_valid.php");
//echo "<pre>";print_r($_SESSION);exit();
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
//
$response = curl_exec($ch);
curl_close($ch);
$res = json_decode($response, true);
$dashbaord_glance = $res['result'];

$day_glance = $dashbaord_glance['day_galance'];
$month_glance = $dashbaord_glance['month_glance'];
$week_glance = $dashbaord_glance['week_glance'];
$year_glance = $dashbaord_glance['year_glance'];

//$smarty->assign("dashbaord_glance", $dashbaord_glance);
$smarty->assign("day_glance", $day_glance);
$smarty->assign("month_glance", $month_glance);
$smarty->assign("week_glance", $week_glance);
$smarty->assign("year_glance", $year_glance);

//echo "<pre>";print_r($result_arr);exit();
/*Timeline chart */
$API_URL = $site_url . "api/v2/dashboard_timelinechart.json";
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
//
$response = curl_exec($ch);
curl_close($ch);
$res = json_decode($response, true);
$dashboard_timelinechart = $res['result'];
//echo "<pre>";print_r($response);exit();
$smarty->assign("dashboard_timelinechart", $dashboard_timelinechart);

?>
