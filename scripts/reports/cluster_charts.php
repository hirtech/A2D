<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");

# ----------- Access Rule Condition -----------
per_hasModuleAccess("Cluster Chart", 'List');
# ----------- Access Rule Condition -----------

# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
# ------------------------------------------------------------
if($mode == "getDisplayXFromDisplayY") {
	//echo "<pre>";print_r($_REQUEST);exit();
    $arr_param = array();
    $vDisplayY = $_REQUEST['vDisplayY'];

    $arr_param['vDisplayY'] = $vDisplayY;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."get_cluster_chart_default_Xaxes.json";
   // echo "<pre>";print_r(json_encode($arr_param));exit();
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
    //echo "<pre>";print_r($response);exit();
    $result_arr = json_decode($response, true);
    //echo "<pre>";print_r(($result_arr['result']));exit();
    
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result_arr['result']);
    hc_exit();
    # -----------------------------------
}else if($mode == "getDisplayX1FromDisplayX"){
	//echo "<pre>";print_r($_REQUEST);exit();
    $arr_param = array();
    $vDisplayY= $_REQUEST['vDisplayY'];
    $vDisplayX= $_REQUEST['vDisplayX'];

    $arr_param['vDisplayY'] = $vDisplayY;
    $arr_param['vDisplayX'] = $vDisplayX;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."get_cluster_chart_default_X1axes.json";
    //echo "<pre>";print_r(json_encode($arr_param));exit();
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
    //echo "<pre>";print_r($response);exit();
    $result_arr = json_decode($response, true);
    //echo "<pre>";print_r(($result_arr['result']));exit();
    
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result_arr['result']);
    hc_exit();
    # -----------------------------------
}else if($mode == "create_cluster_charts"){
    $arr_param = array();
    if (isset($_POST) && count($_POST) > 0) {
        //echo "<pre>";print_r($_POST);exit;
        $vDisplayY = $_POST['vDisplayY'];
        $vDisplayX = $_POST['vDisplayX'];
        $vDisplayX1 = $_POST['vDisplayX1'];
        $dFromDate = $_POST['dFromDate'];
        $dToDate = $_POST['dToDate'];

        $arr_param = array(
            "vDisplayY"           => $vDisplayY,
            "vDisplayX"           => $vDisplayX,
            "vDisplayX1"           => $vDisplayX1,
            "dFromDate"           => $dFromDate,
            "dToDate"             => $dToDate,
            'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
        );
       // echo "<pre>";print_r(json_encode($arr_param));exit();

        $API_URL = $site_api_url."create_cluster_charts.json";
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
        // echo "<pre>";print_r($result_arr['chart_arr']);exit();
        $chart_arr =  $result_arr['result'];
        //echo "<pre>";print_r($chart_arr);exit();
        echo json_encode($chart_arr);
        hc_exit();
    }
}else if($mode == "getDetailsFromAxes") {
    $arr_param = array();
    $vDisplayX = $_REQUEST['vDisplayX'];
    $vDisplayY = $_REQUEST['vDisplayY'];
    $vDisplayX1 = $_REQUEST['vDisplayX1'];

    $arr_param['vDisplayX'] = $vDisplayX;
    $arr_param['vDisplayY'] = $vDisplayY;
    $arr_param['vDisplayX1'] = $vDisplayX1;
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

   // echo "<pre>";print_r(json_encode($arr_param));exit();
    $API_URL = $site_api_url."get_cluster_chart_details_from_axes.json";
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
    //echo "<pre>";print_r($response);exit();
    $result_arr = json_decode($response, true);
    $res_arr = $result_arr['result'];
    //echo "<pre>";print_r($res_arr);exit();    
    # Return jSON data.
    # -----------------------------------
    echo json_encode($res_arr);
    hc_exit();
    # -----------------------------------
}


# ------------------ Get Default Y axes  ------------------
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."cluster_chart_default_Yaxes.json"; 
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
//echo "<pre>";print_r($response);exit();
curl_close($ch);  
$res= json_decode($response, true);
$default_Yaxes =$res['result'];

$smarty->assign("default_Yaxes", $default_Yaxes);
# ------------------ Get Chart type  ------------------

$module_name = "Cluster Charts";
$module_title = "Cluster Charts";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
?>