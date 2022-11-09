<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");
$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Event", 'Edit');
} else {
    per_hasModuleAccess("Event", 'Add');
}
$access_group_var_list = per_hasModuleAccess("Event", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Event", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Event", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Event", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Event", 'Edit', 'N');
# ----------- Access Rule Condition -----------
include_once($controller_path . "event.inc.php");
$EventObj = new Event();
$dCompletedDate = date('d-m-Y');
if($mode == "Update") {
    $iEventId = $_REQUEST['iEventId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "event.\"iEventId\"='".gen_add_slash($iEventId)."'";
    $EventObj->join_field = $join_fieds_arr;
    $EventObj->join = $join_arr;
    $EventObj->where = $where_arr;
    $EventObj->param['limit'] = "LIMIT 1";
    $EventObj->setClause();
    $rs_event = $EventObj->recordset_list();
    //echo "<pre>";print_r($rs_event);exit();
    $iPremiseIdArr = $iZoneIdArr = $iZipcodeArr = $iCityIdArr = $iNetworkIdArr = array();
    if($rs_event){
        $iCampaignBy = $rs_event[0]['iCampaignBy'];
        $dCompletedDate = $rs_event[0]['dCompletedDate'];
        $iEventId = $rs_event[0]['iEventId'];
        $sql = "SELECT  * FROM event_campaign_coverage where \"iEventId\" = '".$iEventId."'";
        $rs = $sqlObj->GetAll($sql);
        $ni = count($rs);
        if($ni > 0){
            for ($i=0; $i <$ni ; $i++) { 
                if($rs[$i]['iCampaignBy'] == 1){
                    $iPremiseIdArr[] = $rs[$i]['iCampaignCoverageId'];
                }else if($rs[$i]['iCampaignBy'] == 2){
                    $iZoneIdArr[] = $rs[$i]['iCampaignCoverageId'];
                }else if($rs[$i]['iCampaignBy'] == 3){
                    $iZipcodeArr[] = $rs[$i]['iCampaignCoverageId'];
                }else if($rs[$i]['iCampaignBy'] == 4){
                    $iCityIdArr[] = $rs[$i]['iCampaignCoverageId'];
                }else if($rs[$i]['iCampaignBy'] == 5){
                    $iNetworkIdArr[] = $rs[$i]['iCampaignCoverageId'];
                }
            }
        }

    }
    //echo "<pre>";print_r($iPremiseIdArr);exit();
}
/*-------------------------- Event -------------------------- */
$arr_param = array();
$arr_param['iStatus']   = 1;
$arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$API_URL = $site_api_url."event_type_dropdown.json";
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
$res = json_decode($response, true);
$smarty->assign("rs_etype", $res['result']);
/*-------------------------- Event -------------------------- */

/*-------------------------- Zone -------------------------- */
$zone_arr_param = array();
$zone_arr_param['iStatus']   = 1;
$zone_arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$ZONE_API_URL = $site_api_url."zone_dropdown.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ZONE_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($zone_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_zone = curl_exec($ch);
curl_close($ch);  
$res_zone = json_decode($response_zone, true);
$smarty->assign("rs_zone", $res_zone['result']);
//echo "<pre>";print_r($res_zone['result']);exit;
/*-------------------------- Zone -------------------------- */

/*-------------------------- Zipcode -------------------------- */
$zipcode_arr_param = array();
$zipcode_arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$zipcodeAPI_URL = $site_api_url."zipcode_dropdown.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $zipcodeAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($zipcode_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_zipcode = curl_exec($ch);
curl_close($ch);  
$res_zipcode = json_decode($response_zipcode, true);
$smarty->assign("rs_zipcode", $res_zipcode['result']);
/*-------------------------- Zipcode -------------------------- */

/*-------------------------- City -------------------------- */
$city_arr_param = array();
$city_arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$cityAPI_URL = $site_api_url."city_dropdown.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $cityAPI_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($city_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_city = curl_exec($ch);
curl_close($ch);  
$res_city = json_decode($response_city, true);
$smarty->assign("rs_city", $res_city['result']);
/*-------------------------- City -------------------------- */

/*-------------------------- Network -------------------------- */
$ntwork_arr_param = array();
$ntwork_arr_param['iStatus']   = 1;
$ntwork_arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$ntwork_API_URL = $site_api_url."network_dropdown.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ntwork_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ntwork_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_ntwork = curl_exec($ch);
curl_close($ch);  
$res_ntwork = json_decode($response_ntwork, true);
$smarty->assign("rs_ntwork", $res_ntwork['result']);
/*-------------------------- Network -------------------------- */

/*-------------------------- Premise -------------------------- */
$premise_arr_param = array();
$premise_arr_param['iStatus']   = 1;
$premise_arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
$premise_API_URL = $site_api_url."premise_dropdown.json";
//echo $premise_API_URL." ".json_encode($premise_arr_param);exit;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $premise_API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($premise_arr_param));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Content-Type: application/json",
)); 
$response_premise = curl_exec($ch);
curl_close($ch);  
$res_premise = json_decode($response_premise, true);
$smarty->assign("rs_premise", $res_premise['result']);
//echo "<pre>";print_r($rs_premise);exit;
/*-------------------------- Premise -------------------------- */

$module_name = "Event ";
$module_title = "Event";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("rs_event", $rs_event);
$smarty->assign("access_group_var_edit", $access_group_var_edit);
$smarty->assign("EVENT_CAMPAIGN_BY_ARR", $EVENT_CAMPAIGN_BY_ARR);
$smarty->assign("dCompletedDate", $dCompletedDate);
$smarty->assign("iPremiseIdArr", $iPremiseIdArr);
$smarty->assign("iZoneIdArr", $iZoneIdArr);
$smarty->assign("iZipcodeArr", $iZipcodeArr);
$smarty->assign("iCityIdArr", $iCityIdArr);
$smarty->assign("iNetworkIdArr", $iNetworkIdArr);
$smarty->assign("iCampaignBy", $iCampaignBy);
?>