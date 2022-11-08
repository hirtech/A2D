<?php
//echo "<pre>";print_R($_REQUEST);exit;
include_once($site_path . "scripts/session_valid.php");

$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Fiber Zone", 'Edit');

} else {
    per_hasModuleAccess("Fiber Zone", 'Add');
}
# ----------- Access Rule Condition -----------
$access_group_var_list = per_hasModuleAccess("Fiber Zone", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Fiber Zone", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Fiber Zone", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Fiber Zone", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Fiber Zone", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "zone.inc.php");
include_once($controller_path . "network.inc.php");

$ZoneObj = new Zone();
$NetworkObj = new Network();

if($mode == "Update") {
    $iZoneId = $_REQUEST['iZoneId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "zone.\"iZoneId\"='".$iZoneId."'";
    $join_fieds_arr[] = 'network."vName" as "vNetwork"';
    $join_fieds_arr[] = 'network."iNetworkId"';
    $join_arr[] = " LEFT JOIN network ON zone.\"iNetworkId\" = network.\"iNetworkId\"";
    $ZoneObj->join_field = $join_fieds_arr;
    $ZoneObj->join = $join_arr;
    $ZoneObj->where = $where_arr;
    $ZoneObj->param['limit'] = "LIMIT 1";
    $ZoneObj->setClause();
    $rs_data = $ZoneObj->recordset_list();
    if($rs_data){
    	if(file_exists($zone_path.$rs_data[0]['vFile'])){
    		$download_path = $zone_path.$rs_data[0]['vFile'];
			$download_url = $zone_path.$rs_data[0]['vFile'];
			
			$file_name_arr = explode('_', $rs_data[0]['vFile'], 2);
			//echo "<pre>";print_r($file_name_arr);exit();
			$file_url = $site_url.'download.php?vFileName_path='.base64_encode($download_path).'&vFileName_url='.base64_encode($download_url).'&file_name='.base64_encode($file_name_arr[1]);
			$rs_data[0]['file_url'] = $file_url;
    	}
    }
   
    //echo "<pre>";print_r($rs_data);exit();
}

$network_arr_param = array();
$network_arr_param['iStatus'] = "1";
$network_arr_param['order_by'] = '"vName" asc';
$network_arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

$NETWORK_API_URL = $site_api_url."network_list.json";
//echo json_encode($network_arr_param);exit;

$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, $NETWORK_API_URL);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch1, CURLOPT_HEADER, FALSE);
curl_setopt($ch1, CURLOPT_POST, TRUE);
curl_setopt($ch1, CURLOPT_POSTFIELDS, json_encode($network_arr_param));

curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));

$network_response = curl_exec($ch1);
curl_close($ch1);  

$network_result_arr = json_decode($network_response, true);
$network_arr = $network_result_arr['result']['data'];
//echo "<pre>";print_r($network_arr);exit;

$module_name = "Fiber Zone";
$module_title = "Fiber Zone";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("GOOGLE_GEOCODE_API_KEY", $GOOGLE_GEOCODE_API_KEY);

$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);

$smarty->assign("rs_data", $rs_data);
$smarty->assign("network_arr", $network_arr);


?>