<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");
$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Equipment Model", 'Edit');
} else {
    per_hasModuleAccess("Equipment Model", 'Add');
}
$access_group_var_list = per_hasModuleAccess("Equipment Model", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Equipment Model", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Equipment Model", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Equipment Model", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Equipment Model", 'Edit', 'N');
# ----------- Access Rule Condition -----------
include_once($controller_path . "equipment_model.inc.php");
$EquipmentModelObj = new EquipmentModel();
if($mode == "Update") {
    $iEquipmentModelId = $_REQUEST['iEquipmentModelId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "equipment_model.\"iEquipmentModelId\"='".gen_add_slash($iEquipmentModelId)."'";
    $EquipmentModelObj->join_field = $join_fieds_arr;
    $EquipmentModelObj->join = $join_arr;
    $EquipmentModelObj->where = $where_arr;
    $EquipmentModelObj->param['limit'] = "LIMIT 1";
    $EquipmentModelObj->setClause();
    $rs_model = $EquipmentModelObj->recordset_list();
}

/*-------------------------- Equipment Type -------------------------- */
$etype_param = array();
$etype_param['iStatus'] = '1';
$etype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$etypeAPI_URL = $site_api_url."equipment_type_dropdown.json";
//echo $etypeAPI_URL." ".json_encode($etype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $etypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($etype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_etype = $res['result'];
$smarty->assign("rs_etype", $rs_etype);
//echo "<pre>";print_r($rs_etype);exit;
/*-------------------------- Equipment Type -------------------------- */

/*-------------------------- Equipment Manufacturer -------------------------- */
$emanu_param = array();
$emanu_param['iStatus'] = '1';
$emanu_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$emanuAPI_URL = $site_api_url."equipment_manufacturer_dropdown.json";
//echo $emanuAPI_URL." ".json_encode($emanu_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $emanuAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emanu_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_emanu = $res['result'];
$smarty->assign("rs_emanu", $rs_emanu);
//echo "<pre>";print_r($rs_emanu);exit;
/*-------------------------- Equipment Manufacturer -------------------------- */

$module_name = "Equipment Model ";
$module_title = "Equipment Model";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("rs_model", $rs_model);
$smarty->assign("access_group_var_edit", $access_group_var_edit);
?>