<?php

//echo "<pre>";print_R($_REQUEST);exit;
include_once($site_path . "scripts/session_valid.php");

$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Custom Layer", 'Edit');

} else {
    per_hasModuleAccess("Custom Layer", 'Add');
}
# ----------- Access Rule Condition -----------
$access_group_var_list = per_hasModuleAccess("Custom Layer", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Custom Layer", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Custom Layer", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Custom Layer", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Custom Layer", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "custom_layer.inc.php");
$country_id = $_SESSION["sess_iCountySaasId" . $admin_panel_session_suffix];

$CustomLayerObj = new CustomLayer();

if($mode == "Update") {
    $iCLId = $_REQUEST['iCLId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "\"iCLId\"='".$iCLId."'";
    $CustomLayerObj->join_field = $join_fieds_arr;
    $CustomLayerObj->join = $join_arr;
    $CustomLayerObj->where = $where_arr;
    $CustomLayerObj->param['limit'] = "LIMIT 1";
    $CustomLayerObj->setClause();
    $rs_data = $CustomLayerObj->recordset_list();

    if($rs_data[0]['vFile'] != ""){
    	$filepath = $custom_layer_path.$country_id."/";
    	if(file_exists($filepath.$rs_data[0]['vFile'])){
    	

    		$download_path = $custom_layer_path.$country_id."/".$rs_data[0]['vFile'];
			$download_url = $custom_layer_url.$country_id."/".$rs_data[0]['vFile'];
			
			$file_name_arr = explode('_', $rs_data[0]['vFile'], 2);
			
			$file_url = $site_url.'download.php?vFileName_path='.base64_encode($download_path).'&vFileName_url='.base64_encode($download_url).'&file_name='.base64_encode($file_name_arr[1]);
			$rs_data[0]['file_url'] = $file_url;
    	}
    }
   
    //echo "<pre>";print_r($rs_sr);exit();
}


$module_name = "Custom Layer";
$module_title = "Custom Layer";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("GOOGLE_GEOCODE_API_KEY", $GOOGLE_GEOCODE_API_KEY);
$smarty->assign("rs_data", $rs_data);
?>