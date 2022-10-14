<?php
//echo"<pre>";print_r($_REQUEST);exit;
include_once($site_path . "scripts/session_valid.php");
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Login History", 'List');
$access_group_var_delete = per_hasModuleAccess("Login History", 'Delete', 'N');
$access_group_var_PDF = per_hasModuleAccess("Login History", 'PDF', 'N');
$access_group_var_CSV = per_hasModuleAccess("Login History", 'CSV', 'N');
# ----------- Access Rule Condition -----------

include_once($controller_path . "login_history.inc.php");
# ------------------------------------------------------------
# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
$page_length = isset($_REQUEST['iDisplayLength']) ? $_REQUEST['iDisplayLength'] : '10';
$start = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'] : '0';
$sEcho = (isset($_REQUEST["sEcho"]) ? $_REQUEST["sEcho"] : '0');
$display_order = (isset($_REQUEST["iSortCol_0"]) ? $_REQUEST["iSortCol_0"] : '0');
$dir = (isset($_REQUEST["sSortDir_0"]) ? $_REQUEST["sSortDir_0"] : 'desc');
# ------------------------------------------------------------
$iUserId = $_REQUEST['iUserId'];

if ($mode == "List") {
    $arr_param = array();
    $vOptions = $_REQUEST['vOptions'];
    $Keyword = addslashes(trim($_REQUEST['Keyword']));

    if ($Keyword != "") {
        $arr_param[$vOptions] = $Keyword;
    }

    $arr_param['iUserId']       = $iUserId;
    $arr_param['page_length']   = $page_length;
    $arr_param['start']         = $start;
    $arr_param['sEcho']         = $sEcho;
    $arr_param['display_order'] = $display_order;
    $arr_param['dir']           = $dir;

    $arr_param['access_group_var_edit'] = $access_group_var_edit;
    $arr_param['access_group_var_delete'] = $access_group_var_delete;

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

    $API_URL = $site_api_url."login_history_list.json";
    //echo $API_URL. " ".json_encode($arr_param);exit;
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

    $total = $result_arr['result']['total_record'];
    $jsonData = array('sEcho' => $sEcho, 'iTotalDisplayRecords' => $total, 'iTotalRecords' => $total, 'aaData' => array());
    $entry = $hidden_arr = array();
    $rs_login_history = $result_arr['result']['data'];
    $ni = count($rs_login_history);

    if ($ni > 0) {
        for ($i = 0; $i < $ni; $i++) {
            $delete = '';
            if ($access_group_var_delete == '1') {
                $delete = '<a class="btn btn-outline-danger" title="Delete" href="javascript:;" onclick="delete_record('.$rs_login_history[$i]['iLLogsId'].');"><i class="fa fa-trash"></i></a>';
            }

            $one_date = strtotime($rs_login_history[$i]['dLoginDate']);
            $two_date = strtotime($rs_login_history[$i]['dLogoutDate']);
            $date_diff = date_timeBetween($one_date, $two_date);
            if ($date_diff=='0 seconds')
                $date_diff='---';

            $entry[$i]['checkbox'] =  $rs_login_history[$i]['iLLogsId'];
            $entry[$i]['vUsername'] = gen_strip_slash($rs_login_history[$i]['vUsername']);
            $entry[$i]['Name'] = gen_strip_slash($rs_login_history[$i]['vFirstName']) . " " . gen_strip_slash($rs_login_history[$i]['vLastName']). " - " . gen_strip_slash($rs_login_history[$i]['vAccessGroup']);
            $entry[$i]['vIP'] = $rs_login_history[$i]['vIP'];
            $entry[$i]['dLoginDate'] = date_getDateTime($rs_login_history[$i]['dLoginDate']);
            $entry[$i]['dLogoutDate'] = date_getDateTime($rs_login_history[$i]['dLogoutDate']);
            $entry[$i]['date_diff'] = $date_diff;
        }
        $jsonData['aaData'] = $entry;
    }
    # -----------------------------------
    # Return jSON data.
    # -----------------------------------
    //echo"<pre>";print_r($jsonData);exit;
    echo json_encode($jsonData);
    hc_exit();
    # -----------------------------------
}


#Get User details from Id
$arr_param['iUserId']       = $iUserId;
$arr_param['sessionId']     = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];

$API_URL = $site_api_url."getUserDetailsFromUserId.json";
//echo $API_URL. " ".json_encode($arr_param);exit;
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
$rs_user = $result_arr['result'];
$vName = '';
if(!empty($rs_user)){
    $vName = $rs_user[0]['vFirstName']." ".$rs_user[0]['vLastName']."(User Id#".$rs_user[0]['iUserId'].")";
}
if($iUserId > 0) {
    $module_name = $vName."'s Login History List";
}else {
    $module_name = "Login History List";
}
$module_title = "Login History";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("iUserId",$iUserId);