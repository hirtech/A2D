<?php
//echo "<pre>";print_R($_REQUEST);exit;
include_once($site_path . "scripts/session_valid.php");

$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("User", 'Edit');
} else {
    per_hasModuleAccess("User", 'Add');
}
# ----------- Access Rule Condition -----------
$access_group_var_list = per_hasModuleAccess("User", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("User", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("User", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("User", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("User", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "user.inc.php");
$UserObj = new User();
$rs_user = array();
$iDepartmentId_arr = array();
$iNetworkId_arr = array();
//echo $mode;exit();
if ($mode == "Update") {
	//echo "<pre>";print_R($_REQUEST);exit;
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();

    if(isset($_REQUEST['iUserId'])) {
    	$join_fieds_arr[] = "user_details.\"vCompanyName\", user_details.\"vCompanyNickName\", user_details.\"vPhone\", user_details.\"vCell\", user_details.\"vFax\", user_details.\"vAddress1\", user_details.\"vAddress2\", user_details.\"vStreet\", user_details.\"vCrossStreet\", user_details.\"iZipcode\", user_details.\"iStateId\", user_details.\"iCountyId\", user_details.\"iCityId\", user_details.\"iZoneId\", user_details.\"vLatitude\", user_details.\"vLongitude\"";
    	$join_fieds_arr[] = 'c."vCounty"';
    	$join_fieds_arr[] = 'sm."vState"';
   		$join_fieds_arr[] = 'cm."vCity"';
	    $join_arr[] = "LEFT JOIN user_details ON user_mas.\"iUserId\" = user_details.\"iUserId\"";
    	$join_arr[] = 'LEFT JOIN county_mas c on user_details."iCountyId" = c."iCountyId"';
    	$join_arr[] = 'LEFT JOIN state_mas sm on user_details."iStateId" = sm."iStateId"';
    	$join_arr[] = 'LEFT JOIN city_mas cm on user_details."iCityId" = cm."iCityId"';
	    $UserObj->join_field = $join_fieds_arr;
	    $UserObj->join = $join_arr;
	    $where_arr[] = "user_mas.\"iUserId\"='" . gen_add_slash($_REQUEST['iUserId']) . "'";

	    $UserObj->where = $where_arr;
	    $UserObj->param['limit'] = "LIMIT 1";
	    $UserObj->setClause();
	    $rs_user = $UserObj->recordset_list();
	    $rs_user[0]['vPassword'] = decrypt_password($rs_user);

        $rs_user[0]['address'] = ($rs_user[0]['vAddress1']!= "")?$rs_user[0]['vAddress1'].' '.$rs_user[0]['vStreet'].' '.$rs_user[0]['vCity'].', '.$rs_user[0]['vState'].' '.$rs_user[0]['vCounty']:"";
	    // echo "<pre>";print_R($rs_user);exit;
		#####  User Department
		$UserObj->user_clear_variable();
		$where_arr = array();
		$join_fields_arr = array();
		$join_arr = array();
		
		$where_arr[] = "user_department.\"iUserId\" = '".gen_add_slash($_REQUEST['iUserId'])."'";
		$UserObj->join_field = $join_fields_arr;
		$UserObj->join = $join_arr;
		$UserObj->where = $where_arr;
		$UserObj->param['limit'] = 0;
		$UserObj->setClause();
		$rs_user_dept = $UserObj->user_department_list();
		$di = count($rs_user_dept);
		
		if($di > 0){
			for($d=0;$d<$di;$d++){
				$iDepartmentId_arr[] = $rs_user_dept[$d]['iDepartmentId'];
			}
		}


	    $UserObj->user_clear_variable();
	    $where_arr = array();
	    $join_arr = array();
	    $join_fieds_arr = array();
	    $where_arr[] = "user_network.\"iUserId\"='".gen_add_slash($_REQUEST['iUserId'])."'";
	    $UserObj->join_field = $join_fieds_arr;
	    $UserObj->join = $join_arr;
	    $UserObj->where = $where_arr;
	    $UserObj->param['limit'] = 0;
	    $UserObj->setClause();
	    $rs_user_ntwork = $UserObj->user_network_list();
	    $pi = count($rs_user_ntwork);
	    if($pi > 0){
	        for($p=0;$p<$pi;$p++){
	            $iNetworkId_arr[] = $rs_user_ntwork[$p]['iNetworkId'];
	        }
	    }
    }
	//echo "<pre>";print_r($iNetworkId_arr);exit;
}

// access group dropdown
$access_group_arr_param = array();
$access_group_arr_param = array(
    "iState"    => 1,
    "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$access_group_API_URL = $site_api_url."access_group_dropdown.json";
//echo $access_group_API_URL." ".json_encode($access_group_arr_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $access_group_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($access_group_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_access_group = curl_exec($ch);
curl_close($ch); 
$rs_access_group1 = json_decode($response_access_group, true); 
$rs_access_group = $rs_access_group1['result'];
//echo "<pre>";print_r($rs_access_group);exit;

// department dropdown
$department_arr_param = array();
$department_arr_param = array(
    "iState"    => 1,
    "sessionId" => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$department_API_URL = $site_api_url."department_dropdown.json";
//echo $department_API_URL." ".json_encode($department_arr_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $department_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($department_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_department = curl_exec($ch);
curl_close($ch); 
$rs_department1 = json_decode($response_department, true); 
$rs_department = $rs_department1['result'];
//echo "<pre>";print_r($rs_department);exit;

# Network Dropdown
$network_arr_param = array();
$network_arr_param = array(
    "iStatus"        => 1,
    "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
);
$network_API_URL = $site_api_url."network_dropdown.json";
//echo $network_API_URL." ".json_encode($network_arr_param);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $network_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($network_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
));
$response_network = curl_exec($ch);
curl_close($ch); 
$rs_network = json_decode($response_network, true); 
$rs_ntwork = $rs_network['result'];
$smarty->assign("rs_ntwork", $rs_ntwork);
## --------------------------------
// General Variables
$module_name = "User";

$smarty->assign("rs_access_group", $rs_access_group);
$smarty->assign("rs_department", $rs_department);
$smarty->assign("rs_user", $rs_user);
$smarty->assign("mode", $mode);
$smarty->assign("module_name", $module_name);
$smarty->assign("iDepartmentId_arr", $iDepartmentId_arr);
$smarty->assign("rs_zone", $rs_zone);
$smarty->assign("iNetworkId_arr", $iNetworkId_arr);

?>
