<?php
//echo "<pre>";print_r($_REQUEST);exit();
# ----------- Access Rule Condition -----------
per_hasModuleAccess("Help", 'List');
$access_group_var_delete = per_hasModuleAccess("Help", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Help", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Help", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Help", 'Edit', 'N');
$access_group_var_PDF = per_hasModuleAccess("Help", 'PDF', 'N');
$access_group_var_CSV = per_hasModuleAccess("Help", 'CSV', 'N');
$access_group_var_Respond = per_hasModuleAccess("Help", 'Respond', 'N');
# ----------- Access Rule Condition -----------

include_once($site_path . "scripts/session_valid.php");
include_once($controller_path . "help.inc.php");


$HELPObj = new HELP();

# General Variables
# ------------------------------------------------------------
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
# ------------------------------------------------------------
//echo "<pre>";print_r($access_group_var_delete);exit();

if($mode == "list"){
    $API_URL = $site_api_url."help_headers.json";
    //echo $API_URL;exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix])));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       "Content-Type: application/json",
       ));
    $response = curl_exec($ch);
    curl_close($ch);  
    //echo "<pre>";print_r($response);exit();
    $result_arr = json_decode($response, true);
    # Return jSON data.
    # -----------------------------------
    /*echo $result_arr['result'];
    hc_exit();*/
    //echo $response;
    # -----------------------------------
}
if($mode=="slider_listing")
{
   $arr_param = array();
   $arr_param = array(
       "iHLId"=> $_POST['iHLId'],
       'sessionId' => $_SESSION["we_api_session_id" . $admin_panel_session_suffix]
    );
   $API_URL = $site_api_url."help_sliders.json";
    //echo $API_URL;exit;
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
   echo json_encode($result_arr['result']);
   hc_exit();
}
$module_name = "Help List";
$module_title = "Help List";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);
$smarty->assign("result_arr", $result_arr['result']['header_list']);
$smarty->assign("result_arr2", $result_arr['result']['help_list']);
$smarty->assign("access_group_var_add", $access_group_var_add);
?>