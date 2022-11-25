<?php
// echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Premise", 'List');


$page = $_REQUEST['page'];
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

$iPremiseId = $_REQUEST['iPremiseId'];

if($mode == "getServiceOrder"){
	$iWOId = $_REQUEST['iWOId'];
    $iServiceTypeId = $_REQUEST['iServiceTypeId'];
	//Service Type Dropdown
	$so_param = array();
	$so_param['iServiceTypeId'] = $iServiceTypeId;
    $so_param['iWOId'] = $iWOId;
	$so_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
	$soAPI_URL = $site_api_url."get_workorder_for_premise_services.json";
	//echo $soAPI_URL." ".json_encode($so_param);exit;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $soAPI_URL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($so_param));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	   "Content-Type: application/json",
	)); 
	$response_so = curl_exec($ch);
	curl_close($ch);  
	$res_so = json_decode($response_so, true);
	$rs_so = $res_so['result'];

	echo json_encode($rs_so);
    hc_exit();
}else if($mode == "Start"){
    //echo "<pre>";print_r($_POST);exit;
	$arr_param = array();
    $arr_param = array(
        "iPremiseId"       	   => $_POST['iPremiseId'],
        "iServiceTypeId"       => $_POST['iServiceTypeId'],
        "iWOId"                => $_POST['iWOId'],
        "iServiceOrderId"      => $_POST['iServiceOrderId'],
        "iCarrierId"           => $_POST['iCarrierId'],
        "iPremiseCircuitId"    => $_POST['iPremiseCircuitId'],
        "iUserId"              => $_POST['iUserId'],
        "iNRCVariable"         => $_POST['iNRCVariable'],
        "iMRCFixed"            => $_POST['iMRCFixed'],
        "dStartDate"           => $_POST['dStartDate'],
        "sessionId"            => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."premise_services_start.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arr_param);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: multipart/form-data",
    ));
    $response = curl_exec($ch);
    curl_close($ch); 
    $result_arr = json_decode($response, true); 
    if(isset($result_arr['iPremiseServiceId'])){
        $result['iPremiseServiceId']    = $result_arr['iPremiseServiceId'];
        $result['iServiceTypeId']       = $result_arr['iServiceTypeId'];
        $result['iPremiseId']           = $result_arr['iPremiseId'];
        $result['msg']                  = $result_arr['Message'];
        $result['error']                = 0 ;
    }else{
        $result['iServiceTypeId']       = $result_arr['iServiceTypeId'];
        $result['iPremiseId']           = $result_arr['iPremiseId'];
        $result['msg']      = $result_arr['Message'];
        $result['error']    = 1 ;
    }
    
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # ----------------------------------- 
}else if($mode == "Suspend"){
    $arr_param = array();
    $arr_param = array(
        "iPremiseId"           => $_POST['iPremiseId'],
        "iServiceTypeId"       => $_POST['iSuspendServiceTypeId'],
        "iWOId"                => $_POST['iWOId'],
        "iServiceOrderId"      => $_POST['iSuspendServiceOrderId'],
        "iCarrierId"           => $_POST['iSuspendCarrierId'],
        "iPremiseCircuitId"    => $_POST['iSuspendPremiseCircuitId'],
        "iUserId"              => $_POST['iSuspendUserId'],
        "dSuspendDate"         => $_POST['dSuspendDate'],
        "sessionId"            => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );

    $API_URL = $site_api_url."premise_services_suspend.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arr_param);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: multipart/form-data",
    ));
    $response = curl_exec($ch);
    curl_close($ch); 
    $result_arr = json_decode($response, true); 
    if(isset($result_arr['iPremiseServiceId'])){
        $result['iPremiseServiceId']    = $result_arr['iPremiseServiceId'];
        $result['iServiceTypeId']       = $result_arr['iServiceTypeId'];
        $result['iPremiseId']           = $result_arr['iPremiseId'];
        $result['msg']                  = $result_arr['Message'];
        $result['error']                = 0 ;
    }else{
        $result['iServiceTypeId']       = $result_arr['iServiceTypeId'];
        $result['iPremiseId']           = $result_arr['iPremiseId'];
        $result['msg']                  = $result_arr['Message'];
        $result['error']                = 1 ;
    }
    
    # Return jSON data.
    # -----------------------------------
    echo json_encode($result);
    hc_exit();
    # ----------------------------------- 
}

/******** Get Premise Name From Premise Id ********/
$premise_param = array();
$premise_param['iPremiseId'] = $iPremiseId;
$premise_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$premiseAPI_URL = $site_api_url."get_premise_name_from_id.json";
//echo $premiseAPI_URL." ".json_encode($premise_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $premiseAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($premise_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_premise = curl_exec($ch);
curl_close($ch);  
$res_premise = json_decode($response_premise, true);
$vPremiseName = $res_premise['result'];
//echo "<pre>";print_r($vPremiseName);exit;
$smarty->assign("iPremiseId", $iPremiseId);
$smarty->assign("vPremiseName", $vPremiseName);
/******** Get Premise Name From Premise Id ********/

/******** Get Premise Service List with service type ********/
$pservice_param = array();
$pservice_param['iPremiseId'] = $iPremiseId;
$pservice_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$pserviceAPI_URL = $site_api_url."premise_services_list.json";
//echo $pserviceAPI_URL." ".json_encode($pservice_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $pserviceAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($pservice_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_pservice = curl_exec($ch);
curl_close($ch);  
$res_pservice = json_decode($response_pservice, true);
$rs_pservice = $res_pservice['result']['data'];
$cnt_pservice = count($rs_pservice);
$smarty->assign("rs_pservice", $rs_pservice);
$smarty->assign("cnt_pservice", $cnt_pservice);
//echo "<pre>";print_r($rs_pservice);exit;
/******** Get Premise Service List with service type ********/

/******* get workorder data where status = open *******/
$wo_param = array();
$wo_param['iPremiseId'] = $iPremiseId;
$wo_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$woAPI_URL = $site_api_url."get_open_workorder_for_premise.json";
//echo $woAPI_URL." ".json_encode($wo_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $woAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($wo_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_wo = curl_exec($ch);
curl_close($ch);  
$res_wo = json_decode($response_wo, true);
$rs_wo = $res_wo['result']['data'];
$smarty->assign("rs_wo", $rs_wo);
//echo "<pre>";print_r($rs_wo);exit;
/******* get workorder data where status = open *******/

/******* get Premise Circuit Data *******/
$pcircuit_param['iPremiseId'] = $iPremiseId;
$pcircuit_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$pcircuitAPI_URL = $site_api_url."premise_circuit_dropdown.json";
//echo $pcircuitAPI_URL. " ".json_encode($pcircuit_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $pcircuitAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($pcircuit_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_pcircuit = curl_exec($ch);
curl_close($ch);  
$res_pcircuit = json_decode($response_pcircuit, true);
$rs_pcircuit = $res_pcircuit['result'];
$smarty->assign("rs_pcircuit", $rs_pcircuit);
//echo "<pre>";print_r($rs_pcircuit);exit;
/******* get Premise Circuit Data *******/
//echo "<pre>";print_r($_SESSION);exit;
$iUserId = $_SESSION['sess_iUserId' . $admin_panel_session_suffix];
$vUserName = $_SESSION['sess_vName' . $admin_panel_session_suffix];

$module_name = "Setup Premise Services";
$module_title = "Setup Premise Services";

$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("dToday", date('Y-m-d H:i:s'));
$smarty->assign("iUserId", $iUserId);
$smarty->assign("vUserName", $vUserName);
?>