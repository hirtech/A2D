<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");
$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Work Order", 'Edit');
} else {
    per_hasModuleAccess("Work Order", 'Add');
}
$access_group_var_list = per_hasModuleAccess("Work Order", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Work Order", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Work Order", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Work Order", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Work Order", 'Edit', 'N');
# ----------- Access Rule Condition -----------
include_once($controller_path . "workorder.inc.php");
$WorkOrderObj = new WorkOrder();
if($mode == "Update") {
    $iWOId = $_REQUEST['iWOId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "workorder.\"iWOId\"='".gen_add_slash($iWOId)."'";

    $join_fieds_arr[] = 's."vName" as "vPremiseName"';
    $join_fieds_arr[] = 'st."vTypeName"';
    $join_fieds_arr[] = 'so."vMasterMSA"';
    $join_fieds_arr[] = 'so."vServiceOrder"';
    $join_arr[] = 'LEFT JOIN site_mas s ON workorder."iPremiseId" = s."iSiteId"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $join_arr[] = 'LEFT JOIN service_order so ON workorder."iServiceOrderId" = so."iServiceOrderId"';
    $WorkOrderObj->join_field = $join_fieds_arr;
    $WorkOrderObj->join = $join_arr;
    $WorkOrderObj->where = $where_arr;
    $WorkOrderObj->param['limit'] = "LIMIT 1";
    $WorkOrderObj->setClause();
    $rs_sorder = $WorkOrderObj->recordset_list();
    if($rs_sorder) {
        $vPremiseDisplay = $rs_sorder[0]['iPremiseId']." (".$rs_sorder[0]['vPremiseName']."; ".$rs_sorder[0]['vTypeName'].")";
        $vSODisplay = "ID#". $rs_sorder[0]['iServiceOrderId']." | ".$rs_sorder[0]['vMasterMSA']." | ".$rs_sorder[0]['vServiceOrder'];
        $rs_sorder[0]['vPremiseDisplay'] = $vPremiseDisplay;
        $rs_sorder[0]['vSODisplay'] = $vSODisplay;
    }
    //echo "<pre>";print_r($rs_sorder);exit();
}else if($mode =="search_premise"){
    $arr_param = array();
    $vPremiseName = trim($_REQUEST['vPremiseName']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['siteName'] = $vPremiseName;
    $API_URL = $site_api_url."search_workorder_premise.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
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
    # -----------------------------------
    # Return data.
    # -----------------------------------
    echo  json_encode($result_arr['result']['data']);
    hc_exit();
    # -----------------------------------
}else if($mode =="search_service_order"){
    $arr_param = array();
    $vServiceOrder = trim($_REQUEST['vServiceOrder']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['vServiceOrder'] = $vServiceOrder;
    $API_URL = $site_api_url."search_service_order.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
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
    # -----------------------------------
    # Return data.
    # -----------------------------------
    echo  json_encode($result_arr['result']['data']);
    hc_exit();
    # -----------------------------------
}

/*-------------------------- WorkOrder Type -------------------------- */
$wotype_param = array();
$wotype_param['iStatus'] = '1';
$wotype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$wotypeAPI_URL = $site_api_url."workorder_type_dropdown.json";
//echo $wotypeAPI_URL." ".json_encode($wotype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $wotypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($wotype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_wotype = $res['result'];
$smarty->assign("rs_wotype", $rs_wotype);
//echo "<pre>";print_r($rs_carrier);exit;
/*-------------------------- WorkOrder Type -------------------------- */

/*-------------------------- User -------------------------- */
$userarr_param = array();
$userarr_param = array(
    "iStatus"        => 1,
    "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$userAPI_URL = $site_api_url."user_dropdown.json";
//echo $userAPI_URL. " ".json_encode($userarr_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $userAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userarr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_zone = curl_exec($ch);
curl_close($ch); 
$rs_user1 = json_decode($response_zone, true); 
$rs_user = $rs_user1['result'];
$smarty->assign("rs_user", $rs_user);
/*-------------------------- User -------------------------- */

/*-------------------------- Work Orer Status -------------------------- */
$status_param = array();
$status_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$statusAPI_URL = $site_api_url."workorder_status_dropdown.json";
//echo $statusAPI_URL." ".json_encode($status_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $statusAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($status_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_status = $res['result'];
$smarty->assign("rs_status", $rs_status);
//echo "<pre>";print_r($rs_status);exit;
/*-------------------------- Work Orer Status -------------------------- */

$module_name = "Work Order ";
$module_title = "Work Order";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("rs_sorder", $rs_sorder);
$smarty->assign("access_group_var_edit", $access_group_var_edit);
?>