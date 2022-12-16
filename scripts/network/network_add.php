<?php

//echo "<pre>";print_R($_REQUEST);exit;
include_once($site_path . "scripts/session_valid.php");

$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Network", 'Edit');

} else {
    per_hasModuleAccess("Network", 'Add');
}
# ----------- Access Rule Condition -----------
$access_group_var_list = per_hasModuleAccess("Network", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Network", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Network", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Network", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Network", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "network.inc.php");

$NetworkObj = new Network();

if($mode == "Update") {
    $iNetworkId = $_REQUEST['iNetworkId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "\"iNetworkId\"='".$iNetworkId."'";
    $NetworkObj->join_field = $join_fieds_arr;
    $NetworkObj->join = $join_arr;
    $NetworkObj->where = $where_arr;
    $NetworkObj->param['limit'] = "LIMIT 1";
    $NetworkObj->setClause();
    $rs_data = $NetworkObj->recordset_list();

    if($rs_data[0]['vFile'] != ""){
    	$filepath = $network_path."/";
    	if(file_exists($filepath.$rs_data[0]['vFile'])){
    	

    		$download_path = $network_path."/".$rs_data[0]['vFile'];
			$download_url = $network_url."/".$rs_data[0]['vFile'];
			
			$file_name_arr = explode('_', $rs_data[0]['vFile'], 2);
			
			$file_url = $site_url.'download.php?vFileName_path='.base64_encode($download_path).'&vFileName_url='.base64_encode($download_url).'&file_name='.base64_encode($file_name_arr[1]);
			$rs_data[0]['file_url'] = $file_url;
    	}
    }
   
    //echo "<pre>";print_r($rs_sr);exit();
}


$module_name = "Network";
$module_title = "Network";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("GOOGLE_GEOCODE_API_KEY", $GOOGLE_GEOCODE_API_KEY);
$smarty->assign("rs_data", $rs_data);
?>