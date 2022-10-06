<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");
$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Fiber Inquiry", 'Edit');
} else {
    per_hasModuleAccess("Fiber Inquiry", 'Add');
}
//per_hasModuleAccess("Fiber Inquiry", 'List');
$access_group_var_list = per_hasModuleAccess("Fiber Inquiry", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Fiber Inquiry", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Fiber Inquiry", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Fiber Inquiry", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Fiber Inquiry", 'Edit', 'N');
# ----------- Access Rule Condition -----------
include_once($controller_path . "fiber_inquiry.inc.php");
$FiberInquiryObj = new FiberInquiry();
$iFiberInquiryId = $_REQUEST['iFiberInquiryId'];
if($mode == "Update") {
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "fiberinquiry_details.\"iFiberInquiryId\"='".gen_add_slash($iFiberInquiryId)."'";

    $join_fieds_arr[] = 'c."vCounty"';
    $join_fieds_arr[] = 'sm."vState"';
    $join_fieds_arr[] = 'cm."vCity"';
    $join_fieds_arr[] = 'contact_mas."vSalutation"';
    $join_fieds_arr[] = 'contact_mas."vFirstName"';
    $join_fieds_arr[] = 'contact_mas."vLastName"';
    $join_fieds_arr[] = 'contact_mas."vPhone"';
    $join_fieds_arr[] = 'contact_mas."vEmail"';
    $join_fieds_arr[] = 'contact_mas."vCompany"';
    $join_arr[] = 'LEFT JOIN county_mas c on fiberinquiry_details."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on fiberinquiry_details."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on fiberinquiry_details."iCityId" = cm."iCityId"';
    $join_arr[] = 'LEFT JOIN contact_mas ON fiberinquiry_details."iCId" = contact_mas."iCId"';
    $FiberInquiryObj->join_field = $join_fieds_arr;
    $FiberInquiryObj->join = $join_arr;
    $FiberInquiryObj->where = $where_arr;
    $FiberInquiryObj->param['limit'] = "LIMIT 1";
    $FiberInquiryObj->setClause();
    $rs_sr = $FiberInquiryObj->recordset_list();
    if(!empty($rs_sr)){
        $rs_sr[0]['address'] = $rs_sr[0]['vAddress1'].' '.$rs_sr[0]['vStreet'].' '.$rs_sr[0]['vCity'].', '.$rs_sr[0]['vState'].' '.$rs_sr[0]['vCounty'];
        $rs_sr[0]['contact']  =  $rs_sr[0]['vSalutation'] . ' ' . $rs_sr[0]['vFirstName'] . ' ' . $rs_sr[0]['vLastName'] . ' [' . $rs_sr[0]['vEmail'] . (($rs_sr[0]['vPhone'] != "") ? ' - ' . $rs_sr[0]['vPhone'] : "") . ']';
        
    }
    //echo "<pre>";print_r($rs_sr);exit();
}
else if($_REQUEST['mode'] == "nearby_sr")
{
    $arr_param = array();
    $lat        = $_REQUEST['lat'];
    $long       = $_REQUEST['long'];
    $meter      = $_REQUEST['meter'];

    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['lat']       = $lat; 
    $arr_param['long']      = $long; 
    $arr_param['meter']     = $meter; 
    $API_URL = $site_api_url."nearby_fiber_inquiry.json";
    //echo $API_URL." ".json_encode($arr_param);exit;
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

//engagement_dropdown
$engagementarr_param = array();
$engagementarr_param['iStatus'] = '1';
$engagementarr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$engagementAPI_URL = $site_api_url."engagement_dropdown.json";
//echo $engagementAPI_URL." ".json_encode($engagementarr_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $engagementAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($engagementarr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res= json_decode($response, true);
$rs_engagement = $res['result'];
$smarty->assign("rs_engagement", $rs_engagement);
//echo "<pre>";print_r($rs_engagement);exit;

//Premise sub type
$psubtype_param = array();
$psubtype_param['iStatus'] = '1';
$psubtype_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$psubtypeAPI_URL = $site_api_url."premise_sub_type_dropdown.json";
//echo $psubtypeAPI_URL." ".json_encode($psubtype_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $psubtypeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($psubtype_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_psubtype = curl_exec($ch);
curl_close($ch);  
$res_psubtype= json_decode($response_psubtype, true);
$rs_premise_sub_type = $res_psubtype['result'];
$smarty->assign("rs_premise_sub_type", $rs_premise_sub_type);
//echo "<pre>";print_r($rs_premise_sub_type);exit;


$module_name = "Fiber Inquiry ";
$module_title = "Fiber Inquiry";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("rs_sr", $rs_sr);
$smarty->assign("iFiberInquiryId", $iFiberInquiryId);

$smarty->assign("access_group_var_edit", $access_group_var_edit);
?>