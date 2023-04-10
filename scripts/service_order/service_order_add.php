<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");
$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Service Order", 'Edit');
} else {
    per_hasModuleAccess("Service Order", 'Add');
}
$access_group_var_list = per_hasModuleAccess("Service Order", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Service Order", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Service Order", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Service Order", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Service Order", 'Edit', 'N');
# ----------- Access Rule Condition -----------
include_once($controller_path . "service_order.inc.php");

$ServiceOrderObj = new ServiceOrder();
$iPremiseId = $_REQUEST['iPremiseId'];
$iFiberInquiryId = $_REQUEST['iFiberInquiryId'];
$sess_iCompanyId = $_SESSION["sess_iCompanyId" . $admin_panel_session_suffix];
$sess_vCompanyAccessType = $_SESSION["sess_vCompanyAccessType" . $admin_panel_session_suffix];

if($iFiberInquiryId > 0 && $mode = 'Add') {
    $arr_param = array();
    $arr_param = array(
        "iFiberInquiryId"   => $_REQUEST['iFiberInquiryId'],
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
    );
    $API_URL = $site_api_url."fiber_inquiry_list.json";
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
    $res1 = json_decode($response, true); 
    $res = $res1['result']['data'];
    //echo "<pre>";print_r($res);exit;
    if($res[0]['vAddress'] != ''){
        $rs_sorder[0]['iPremiseId'] = $res[0]['iMatchingPremiseId'];
        $rs_sorder[0]['vPremiseName'] = $res[0]['vPremiseName'];
    }
}

if($mode == "Update") {
    $iServiceOrderId = $_REQUEST['iServiceOrderId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "service_order.\"iServiceOrderId\"='".gen_add_slash($iServiceOrderId)."'";

    $join_fieds_arr[] = 's."vName" as "vPremiseName"';
    $join_arr[] = 'LEFT JOIN premise_mas s ON service_order."iPremiseId" = s."iPremiseId"';
    $ServiceOrderObj->join_field = $join_fieds_arr;
    $ServiceOrderObj->join = $join_arr;
    $ServiceOrderObj->where = $where_arr;
    $ServiceOrderObj->param['limit'] = "LIMIT 1";
    $ServiceOrderObj->setClause();
    $rs_sorder = $ServiceOrderObj->recordset_list();
    //echo "<pre>";print_r($rs_sorder);exit();
    if(!empty($rs_sorder)){

        if($rs_sorder[0]['vFile'] != ""){
            if(file_exists($service_order_path.$rs_sorder[0]['vFile'])){
            
                $download_path = $service_order_path.$rs_sorder[0]['vFile'];
                $download_url = $service_order_url.$rs_sorder[0]['vFile'];
                
                $file_url = $site_url.'download.php?vFileName_path='.base64_encode($download_path).'&vFileName_url='.base64_encode($download_url).'&file_name='.base64_encode($rs_sorder[0]['vFile']);
                $rs_sorder[0]['file_url'] = $file_url;
            }
        }
        if($rs_sorder[0]['iSOStatus'] == 7){
            $msg = 'Unauthorised Access!!!!! Contact Administrator....';
            echo "<script>window.location='".$site_url."user/unauthorised?msg=".$msg."';</script>";
            exit;
        }

        if($sess_iCompanyId > 0 && $A2D_COMPANY_ID != $sess_iCompanyId){
            if($rs_sorder[0]['iCarrierID'] != $sess_iCompanyId )
            {
                $msg = 'Unauthorised Access!!!!! Contact Administrator....';
                echo "<script>window.location='".$site_url."user/unauthorised?msg=".$msg."';</script>";
                exit;
            }
        }
    }
}else if($mode =="search_premise"){
    $arr_param = array();
    $vPremiseName = trim($_REQUEST['vPremiseName']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['siteName'] = $vPremiseName;
    $API_URL = $site_api_url."search_premise_address.json";
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
    $response = curl_exec($ch);
    curl_close($ch);  
    $result_arr = json_decode($response, true);
    # -----------------------------------
    # Return data.
    # -----------------------------------
    echo  json_encode($result_arr['result']['data']);
    hc_exit();
    # -----------------------------------
}else {
    if($iPremiseId > 0){
        /******** Get Premise Name From Premise Id ********/
        $premise_param = array();
        $premise_param['iPremiseId']    = $iPremiseId;
        $premise_param['sessionId']     = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
        $premiseAPI_URL = $site_api_url."get_premise_name_from_id.json";
        //echo $premiseAPI_URL." ".json_encode($premise_param);exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $premiseAPI_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($premise_param));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           "Content-Type: application/json",
        )); 
        $response_premise = curl_exec($ch);
        curl_close($ch);  
        $res_premise = json_decode($response_premise, true);
        $vPremiseName = $res_premise['result'];
        //echo "<pre>";print_r($vPremiseName);exit;
        $rs_sorder[0]['iPremiseId'] = $iPremiseId;
        $rs_sorder[0]['vPremiseName'] = $vPremiseName;
    }
    $iLastServiceOrderId = 0;
    $sql = 'select setval(\'"public"."service_order_iServiceOrderId_seq"\'::regclass, (select MAX("iServiceOrderId") FROM "public"."service_order"))';
    $rs = (array)$sqlObj->Execute($sql);
    //echo "<pre>";print_r($rs['fields']['setval']);exit;
    if(!empty($rs)) {
        if(isset($rs['fields']['setval']) && $rs['fields']['setval'] > 0){
            $iLastServiceOrderId = $rs['fields']['setval'];
        }
    }

}
//Carrier (Company) Dropdown
$carrier_param = array();
$carrier_param['iStatus'] = '1';
$carrier_param['sess_iCompanyId'] = $sess_iCompanyId;
$carrier_param['sess_vCompanyAccessType'] = $sess_vCompanyAccessType;
$carrier_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$carrierAPI_URL = $site_api_url."company_dropdown.json";
//echo $carrierAPI_URL." ".json_encode($carrier_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $carrierAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($carrier_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response = curl_exec($ch);
curl_close($ch);  
$res = json_decode($response, true);
$rs_carrier = $res['result'];
$smarty->assign("rs_carrier", $rs_carrier);
//echo "<pre>";print_r($rs_carrier);exit;

$module_name = "Service Order ";
$module_title = "Service Order";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("iFiberInquiryId", $iFiberInquiryId);
$smarty->assign("rs_sorder", $rs_sorder);
$smarty->assign("iLastServiceOrderId", $iLastServiceOrderId);
$smarty->assign("sess_vCompanyAccessType", $sess_vCompanyAccessType);
$smarty->assign("access_group_var_edit", $access_group_var_edit);
?>