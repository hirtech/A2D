<?php
include_once($site_path . "scripts/session_valid.php");
$user_id = ($_SESSION['sess_iUserId' . $admin_panel_session_suffix]);

# ----------- Access Rule Condition -----------
// $access_group_var_delete = per_hasModuleAccess("view User", 'Delete', 'N');
// $access_group_var_status = per_hasModuleAccess("view User", 'Status', 'N');
// $access_group_var_add = per_hasModuleAccess("view User", 'Add', 'N');
// $access_group_var_edit = per_hasModuleAccess("view User", 'Edit', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "access_group.inc.php");
include_once($controller_path . "user.inc.php");

$iAGroupId = $_REQUEST['iAGroupId'];
$AccessGroupObj = new AccessGroup();
$UserObj = new User();

$rs_user = array();
if(isset($_POST['mode']) && $_POST['mode'] == "Update") {
    $result = array();

	$update_array = array("iUserId" => $_POST['iUserId'],
        "vFirstName" => addslashes($_POST['vFirstName']),
        "vLastName" => addslashes($_POST['vLastName']),
        "vUsername" => addslashes($_POST['vUsername']),
        "vPassword" => $_POST['vPassword']
    	);
    $UserObj->update_arr = $update_array;
    $rs_db = $UserObj->update_user();
    
    if($rs_db){
       if($_SESSION["sess_iUserId".$admin_panel_session_suffix]!= '')
        {
            $_SESSION["sess_vName".$admin_panel_session_suffix]= gen_strip_slash($_POST['vFirstName'])." ".gen_strip_slash($_POST['vLastName']);
        }
        $result['error'] = 0 ;
        $result['msg'] = MSG_UPDATE_PROFILE;
    }else{
        $result['error'] = 1 ;
        $result['msg'] = MSG_UPDATE_PROFILE_ERROR;
    }
    echo json_encode($result);
    hc_exit();
}
$where_arr = array();
$join_fieds_arr = array();
$join_arr = array();

if(isset($user_id)) {
    $join_fieds_arr[] = "user_details.\"vCompanyName\"";
	$join_arr[] = "LEFT JOIN user_details ON user_mas.\"iUserId\" = user_details.\"iUserId\"";
	$UserObj->join_field = $join_fieds_arr;
	$UserObj->join = $join_arr;
	$where_arr[] = "user_mas.\"iUserId\"='" . gen_add_slash($user_id) . "'";

	$UserObj->where = $where_arr;
	$UserObj->param['limit'] = "LIMIT 1";
	$UserObj->setClause();
	$rs_user = $UserObj->recordset_list();
	$rs_user[0]['vPassword'] = decrypt_password($rs_user);
}
$rs_access_group =array();

 //Access Group
$where_arr = array();
$where_arr[] = "access_group_mas.\"iStatus\"='1'";
$AccessGroupObj->where = $where_state;
$AccessGroupObj->param['order_by'] = "access_group_mas.\"vAccessGroup\"";
$AccessGroupObj->setClause();
$rs_access_group = $AccessGroupObj->recordset_list();   


// General Variables
$module_name = "Edit Profile";

$smarty->assign("rs_access_group", $rs_access_group);
$smarty->assign("rs_user", $rs_user);
$smarty->assign("mode", $mode);
$smarty->assign("iAGroupId", $iAGroupId);
$smarty->assign("module_name", $module_name);

?>
