<?php
include_once($site_path . "scripts/session_valid.php");
$user_id = ($_SESSION['sess_iUserId' . $admin_panel_session_suffix]);

include_once($controller_path . "user.inc.php");
$UserObj = new User();
$rs_user = array();
if(isset($_POST['mode']) && $_POST['mode'] == "Update") {
    $result = array();
    $arr_param = array(
        "iUserId"       => $_POST['iUserId'],
        "vFirstName"    => $_POST['vFirstName'],
        "vLastName"     => $_POST['vLastName'],
        "vUsername"     => $_POST['vUsername'],
        "vPassword"     => $_POST['vPassword'],
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );
    $API_URL = $site_api_url."edit_profile.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arr_param);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: multipart/form-data",
    ));
    $response = curl_exec($ch);
    curl_close($ch); 
    $result_arr = json_decode($response, true); 
    //echo "<pre>;";print_r($result_arr);exit;
    if(isset($result_arr['iUserId'])){
        if ($result_arr['iUserId'] == $user_id) {
            $_SESSION["sess_vName".$admin_panel_session_suffix]= gen_strip_slash($_POST['vFirstName'])." ".gen_strip_slash($_POST['vLastName']);
        }
        $result['msg'] = MSG_UPDATE_PROFILE;
        $result['error']= 0 ;
    }else{
        $result['msg'] = MSG_UPDATE_PROFILE_ERROR;
        $result['error']= 1 ;
    }
    echo json_encode($result);
    hc_exit();
}
$where_arr = array();
$join_fieds_arr = array();
$join_arr = array();

if(isset($user_id)) {
    $join_fieds_arr[] = "user_details.\"vCompanyName\"";
    $join_fieds_arr[] = "access_group_mas.\"vAccessGroup\"";
	$join_arr[] = "LEFT JOIN user_details ON user_mas.\"iUserId\" = user_details.\"iUserId\"";
    $join_arr[] = "LEFT JOIN access_group_mas ON user_mas.\"iAGroupId\" = access_group_mas.\"iAGroupId\"";
	$UserObj->join_field = $join_fieds_arr;
	$UserObj->join = $join_arr;
	$where_arr[] = "user_mas.\"iUserId\"='" . gen_add_slash($user_id) . "'";

	$UserObj->where = $where_arr;
	$UserObj->param['limit'] = "LIMIT 1";
	$UserObj->setClause();
	$rs_user = $UserObj->recordset_list();
	$rs_user[0]['vPassword'] = decrypt_password($rs_user);
}
//echo "<pre>";print_r($rs_user);exit;

// General Variables
$module_name = "Edit Profile";

$smarty->assign("rs_user", $rs_user);
$smarty->assign("mode", $mode);
$smarty->assign("iAGroupId", $iAGroupId);
$smarty->assign("module_name", $module_name);

?>
