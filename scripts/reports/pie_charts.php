<?php
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Pie Charts", 'List');
# ----------- Access Rule Condition -----------

include_once($site_path . "scripts/session_valid.php");
include_once($controller_path . "pie_chart.inc.php");


$PieChartObj = new PieChart();

# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
# ------------------------------------------------------------

if($mode == "getDisplayXFromDisplayY") {
    $arr_param = array();
    $vDisplayY = $_REQUEST['vDisplayY'];

    $arr_param['vDisplayY'] = $vDisplayY;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."get_pie_chart_default_Xaxes.json";
    //echo $API_URL;exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
    ));
    //
    $response = curl_exec($ch);
    curl_close($ch);
    $result_arr = json_decode($response, true);
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result_arr['result']);
    hc_exit();
    # -----------------------------------
}else if($mode == "getDetailsFromAxes") {
    $arr_param = array();
    $vDisplayX = $_REQUEST['vDisplayX'];
    $vDisplayY = $_REQUEST['vDisplayY'];

    $arr_param['vDisplayX'] = $vDisplayX;
    $arr_param['vDisplayY'] = $vDisplayY;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    //echo "<pre>";print_r(json_encode($arr_param));exit();
    $API_URL = $site_api_url."get_pie_chart_details_from_axes.json";
    //echo $API_URL;exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
    ));
    //
    $response = curl_exec($ch);
    curl_close($ch);
    $result_arr = json_decode($response, true);
    $res_arr = $result_arr['result'];  
    # Return jSON data.
    # -----------------------------------
    echo json_encode($res_arr);
    hc_exit();
    # -----------------------------------
}
else if($mode == "create_pie_charts"){
    $arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
        //echo "<pre>";print_r($_POST);exit;
        $vDisplayY = $_POST['vDisplayY'];
        $vDisplayX = $_POST['vDisplayX'];
        $dFromDate = $_POST['dFromDate'];
        $dToDate = $_POST['dToDate'];

        $arr_param = array(
            "vDisplayY"           => $vDisplayY,
            "vDisplayX"           => $vDisplayX,
            "dFromDate"           => $dFromDate,
            "dToDate"             => $dToDate,
            'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
        );
        //echo "<pre>";print_r(json_encode($arr_param));exit();

        $API_URL = $site_api_url."create_pie_charts.json";
        //echo "<pre>";print_r($API_URL);exit();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           "Content-Type: application/json",
        ));

        $response = curl_exec($ch);
        curl_close($ch);  
        $result_arr = json_decode($response, true);
        $chart_arr = $result_arr['result'];
        echo json_encode($chart_arr);
        hc_exit();
    }
}


# ------------------ Get Default Y axes  ------------------
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."pie_chart_default_Yaxes.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
    
$response = curl_exec($ch);
curl_close($ch);  
$res= json_decode($response, true);
$default_Yaxes = $res['result'];
$smarty->assign("default_Yaxes", $default_Yaxes);
# ------------------ Get Chart type  ------------------

$module_name = "Pie / Bar Charts";
$module_title = "Pie / Bar Charts";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
?>