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

include_once($controller_path . "access_group.inc.php");
include_once($controller_path . "user.inc.php");
//include_once($controller_path . "state.inc.php");
include_once($controller_path . "department.inc.php");

include_once($controller_path . "zone.inc.php");


$iAGroupId = $_REQUEST['iAGroupId'];

$AccessGroupObj = new AccessGroup();
$DepartmentObj = new Department();
//$StateObj = new State();
$UserObj = new User();
$ZoneObj = new Zone();

$rs_user = array();
$iDepartmentId_arr = array();
$iZoneId_arr = array();


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
		    $where_arr[] = "user_zone.\"iUserId\"='".gen_add_slash($_REQUEST['iUserId'])."'";
		    $UserObj->join_field = $join_fieds_arr;
		    $UserObj->join = $join_arr;
		    $UserObj->where = $where_arr;
		    $UserObj->param['limit'] = 0;
		    $UserObj->setClause();
		    $rs_user_zone = $UserObj->user_zone_list();
		    $pi = count($rs_user_zone);
		    if($pi > 0){
		        for($p=0;$p<$pi;$p++){
		            $iZoneId_arr[] = $rs_user_zone[$p]['iZoneId'];
		        }
		    }
    }
    
	//echo "<pre>";print_r($rs_user);exit;
	#####  User Department
}
// access group dropdown
$where_arr = array();
$where_arr[] = "\"iStatus\"='1'";
$AccessGroupObj->where = $where_arr;
$AccessGroupObj->param['order_by'] = "\"vAccessGroup\"";
$AccessGroupObj->setClause();
$rs_access_group = $AccessGroupObj->recordset_list();

// department dropdown
$where_arr = array();
$where_arr[] = "department_mas.\"iStatus\"='1'";
$DepartmentObj->where = $where_arr;
$DepartmentObj->param['order_by'] = "department_mas.\"vDepartment\"";
$DepartmentObj->setClause();
$rs_department = $DepartmentObj->recordset_list();
//echo "<pre>";print_r($rs_department);exit;

// state dropdown
/*$where_arr_state = array();
$StateObj->where = $where_arr_state;
$StateObj->param['order_by'] = "state_mas.\"vState\"";
$StateObj->setClause();
$rs_state = $StateObj->recordset_list();*/

// zone dropdown
$where_arr = array();
$where_arr[] = "zone.\"iStatus\"='1'";
$ZoneObj->where = $where_arr;
$ZoneObj->param['order_by'] = "zone.\"iZoneId\"";
$ZoneObj->setClause();
$rs_zone = $ZoneObj->recordset_list();

// General Variables
$module_name = "User";

$smarty->assign("rs_access_group", $rs_access_group);
$smarty->assign("rs_department", $rs_department);
$smarty->assign("rs_state", $rs_state);
$smarty->assign("rs_user", $rs_user);
$smarty->assign("mode", $mode);
$smarty->assign("iAGroupId", $iAGroupId);
$smarty->assign("module_name", $module_name);
$smarty->assign("iDepartmentId_arr", $iDepartmentId_arr);
$smarty->assign("rs_zone", $rs_zone);
$smarty->assign("iZoneId_arr", $iZoneId_arr);

?>
