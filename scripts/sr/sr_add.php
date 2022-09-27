<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");


$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Service Request", 'Edit');
} else {
    per_hasModuleAccess("Service Request", 'Add');
}
//per_hasModuleAccess("Service Request", 'List');
$access_group_var_list = per_hasModuleAccess("Service Request", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Service Request", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Service Request", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Service Request", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Service Request", 'Edit', 'N');
# ----------- Access Rule Condition -----------


include_once($controller_path . "sr.inc.php");

$SRObj = new SR();

//$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
//include_once($site_path . "scripts/session_valid.php");

$iSAttributeIdArr = array();
if($mode == "Update") {
    $iSRId = $_REQUEST['iSRId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "sr_details.\"iSRId\"='".gen_add_slash($iSRId)."'";

    $join_fieds_arr[] = 'c."vCounty"';
    $join_fieds_arr[] = 'sm."vState"';
    $join_fieds_arr[] = 'cm."vCity"';
    $join_fieds_arr[] = 'contact_mas."vSalutation"';
    $join_fieds_arr[] = 'contact_mas."vFirstName"';
    $join_fieds_arr[] = 'contact_mas."vLastName"';
    $join_fieds_arr[] = 'contact_mas."vPhone"';
    $join_fieds_arr[] = 'contact_mas."vEmail"';
    $join_fieds_arr[] = 'contact_mas."vCompany"';
    $join_arr[] = 'LEFT JOIN county_mas c on sr_details."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on sr_details."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on sr_details."iCityId" = cm."iCityId"';
    $join_arr[] = 'LEFT JOIN contact_mas ON sr_details."iCId" = contact_mas."iCId"';
    $SRObj->join_field = $join_fieds_arr;
    $SRObj->join = $join_arr;
    $SRObj->where = $where_arr;
    $SRObj->param['limit'] = "LIMIT 1";
    $SRObj->setClause();
    $rs_sr = $SRObj->recordset_list();
    if(!empty($rs_sr)){
        $rs_sr[0]['address'] = $rs_sr[0]['vAddress1'].' '.$rs_sr[0]['vStreet'].' '.$rs_sr[0]['vCity'].', '.$rs_sr[0]['vState'].' '.$rs_sr[0]['vCounty'];
        $rs_sr[0]['contact']  =  $rs_sr[0]['vSalutation'] . ' ' . $rs_sr[0]['vFirstName'] . ' ' . $rs_sr[0]['vLastName'] . ' [' . $rs_sr[0]['vEmail'] . (($rs_sr[0]['vPhone'] != "") ? ' - ' . $rs_sr[0]['vPhone'] : "") . ']';
        
    }
    //echo "<pre>";print_r($rs_sr);exit();
}
else if($_REQUEST['mode'] == "nearby_sr")
{
    $arr_param = array();
    $lat = $_REQUEST['lat'];
    $long = $_REQUEST['long'];
    $meter = $_REQUEST['meter'];

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['lat'] = $lat; 
    $arr_param['long'] = $long; 
    $arr_param['meter'] = $meter; 
    
    //$arr_param['id']= $id; 
    $API_URL = $site_api_url."nearby_sr.json";
    //echo "<pre>";print_r(json_encode($arr_param));exit;
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

    $res = curl_exec($ch);
    curl_close($ch);
    //echo "<pre>";print_r($res);exit();   
    $response = json_decode($res, true);
    echo json_encode($response['result']);
   exit();
}

//User array
$arr_param = array();
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."getUserDropdown.json";
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
$res= json_decode($response, true);
$rs_user =$res['result'];
//echo "<pre>";print_r($rs_user);exit;
$iSRId = $_REQUEST['iSRId'];
$smarty->assign("rs_user", $rs_user);


$module_name = "SR ";
$module_title = "SR";
$smarty->assign("iSRId", $iSRId);
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("rs_sr", $rs_sr);
$smarty->assign("msg", $_GET['msg']);
$smarty->assign("flag", $_GET['flag']);

$smarty->assign("access_group_var_edit", $access_group_var_edit);
$smarty->assign('Access_Group_SuperAdmin',$Access_Group_SuperAdmin);
$smarty->assign("sess_user_iAGroupId",$_SESSION["sess_iAGroupId".$admin_panel_session_suffix]);


?>