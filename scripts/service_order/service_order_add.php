<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");
$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Service Order", 'Edit');
} else {
    per_hasModuleAccess("Service Order", 'Add');
}
$access_group_var_list = per_hasModuleAccess("Service Order", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Service Order", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Service Order", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Service Order", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Service Order", 'Edit', 'N');
# ----------- Access Rule Condition -----------
include_once($controller_path . "service_order.inc.php");
$ServiceOrderObj = new ServiceOrder();
if($mode == "Update") {
    $iServiceOrderId = $_REQUEST['iServiceOrderId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "service_order.\"iServiceOrderId\"='".gen_add_slash($iServiceOrderId)."'";

    $join_fieds_arr[] = 's."vName" as "vPremiseName"';
    $join_arr[] = 'LEFT JOIN premise_mas s ON service_order."iPremiseId" = s."iPremiseId"';
    $ServiceOrderObj->join_field = $join_fieds_arr;
    $ServiceOrderObj->join = $join_arr;
    $ServiceOrderObj->where = $where_arr;
    $ServiceOrderObj->param['limit'] = "LIMIT 1";
    $ServiceOrderObj->setClause();
    $rs_sorder = $ServiceOrderObj->recordset_list();
    //echo "<pre>";print_r($rs_sorder);exit();
}else if($mode =="search_premise"){
    $arr_param = array();
    $vPremiseName = trim($_REQUEST['vPremiseName']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['siteName'] = $vPremiseName;
    $API_URL = $site_api_url."search_premise_address.json";
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

//Carrier (Company) Dropdown
$carrier_param = array();
$carrier_param['iStatus'] = '1';
$carrier_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$carrierAPI_URL = $site_api_url."company_dropdown.json";
//echo $carrierAPI_URL." ".json_encode($carrier_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $carrierAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($carrier_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_carrier = $res['result'];
$smarty->assign("rs_carrier", $rs_carrier);
//echo "<pre>";print_r($rs_carrier);exit;

//Connection Type Dropdown
$cntype_param = array();
$cntype_param['iStatus'] = '1';
$cntype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$cntypeAPI_URL = $site_api_url."connection_type_dropdown.json";
//echo $cntypeAPI_URL." ".json_encode($cntype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $cntypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($cntype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_cntype = curl_exec($ch);
curl_close($ch);  
$res_cntype = json_decode($response_cntype, true);
$rs_cntype = $res_cntype['result'];
$smarty->assign("rs_cntype", $rs_cntype);
//echo "<pre>";print_r($rs_cntype);exit;

//Service Type Dropdown
$stype_param = array();
$stype_param['iStatus'] = '1';
$stype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$stypeAPI_URL = $site_api_url."service_type_dropdown.json";
//echo $stypeAPI_URL." ".json_encode($stype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $stypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($stype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_stype = curl_exec($ch);
curl_close($ch);  
$res_stype = json_decode($response_stype, true);
$rs_stype = $res_stype['result'];
$smarty->assign("rs_stype", $rs_stype);
//echo "<pre>";print_r($rs_stype);exit;

$module_name = "Service Order ";
$module_title = "Service Order";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("rs_sorder", $rs_sorder);
$smarty->assign("access_group_var_edit", $access_group_var_edit);
?>