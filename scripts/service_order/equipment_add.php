<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");
$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Equipment", 'Edit');
} else {
    per_hasModuleAccess("Equipment", 'Add');
}
$access_group_var_list = per_hasModuleAccess("Equipment", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Equipment", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Equipment", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Equipment", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Equipment", 'Edit', 'N');
# ----------- Access Rule Condition -----------
include_once($controller_path . "equipment.inc.php");
$EquipmentObj = new Equipment();
if($mode == "Update") {
    $iEquipmentId = $_REQUEST['iEquipmentId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "equipment.\"iEquipmentId\"='".gen_add_slash($iEquipmentId)."'";

    $join_fieds_arr[] = 's."vName" as "vPremiseName"';
    $join_arr[] = 'LEFT JOIN site_mas s ON equipment."iPremiseId" = s."iSiteId"';
    $EquipmentObj->join_field = $join_fieds_arr;
    $EquipmentObj->join = $join_arr;
    $EquipmentObj->where = $where_arr;
    $EquipmentObj->param['limit'] = "LIMIT 1";
    $EquipmentObj->setClause();
    $rs_equipment = $EquipmentObj->recordset_list();
    //echo "<pre>";print_r($rs_equipment);exit();
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

/*************** Equipment Model Dropdown ***************/
$model_param = array();
$model_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$modelAPI_URL = $site_api_url."equipment_model_dropdown.json";
//echo $modelAPI_URL." ".json_encode($model_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $modelAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($model_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_model = $res['result'];
$smarty->assign("rs_model", $rs_model);
/*************** Equipment Model Dropdown ***************/

/*************** Material Dropdown ***************/
$material_param = array();
$material_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$materialAPI_URL = $site_api_url."material_dropdown.json";
//echo $materialAPI_URL." ".json_encode($material_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $materialAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($material_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_material = $res['result'];
$smarty->assign("rs_material", $rs_material);
/*************** Power Dropdown ***************/

/*************** Power Dropdown ***************/
$power_param = array();
$power_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$powerAPI_URL = $site_api_url."power_dropdown.json";
//echo $powerAPI_URL." ".json_encode($power_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $powerAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($power_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_power = $res['result'];
$smarty->assign("rs_power", $rs_power);
/*************** Power Dropdown ***************/

/*************** Install Type Dropdown ***************/
$itype_param = array();
$itype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$itypeAPI_URL = $site_api_url."install_type_dropdown.json";
//echo $itypeAPI_URL." ".json_encode($itype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $itypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($itype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_itype = $res['result'];
$smarty->assign("rs_itype", $rs_itype);
/*************** Install Type Dropdown ***************/

/*************** Link Type Dropdown ***************/
$ltype_param = array();
$ltype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$ltypeAPI_URL = $site_api_url."link_type_dropdown.json";
//echo $ltypeAPI_URL." ".json_encode($ltype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ltypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ltype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_ltype = $res['result'];
$smarty->assign("rs_ltype", $rs_ltype);
//echo"<pre>";print_r($rs_ltype);exit;
/*************** Link Type Dropdown ***************/


/*************** Operational Status Dropdown ***************/
$otype_param = array();
$otype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$otypeAPI_URL = $site_api_url."operational_status_dropdown.json";
//echo $otypeAPI_URL." ".json_encode($otype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $otypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($otype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_ostatus = $res['result'];
$smarty->assign("rs_ostatus", $rs_ostatus);
//echo"<pre>";print_r($rs_ostatus);exit;
/*************** Operational Status Dropdown ***************/

$module_name = "Equipment ";
$module_title = "Equipment";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("rs_equipment", $rs_equipment);
$smarty->assign("access_group_var_edit", $access_group_var_edit);
?>