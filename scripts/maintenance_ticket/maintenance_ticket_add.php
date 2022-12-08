<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");
$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Maintenance Ticket", 'Edit');
} else {
    per_hasModuleAccess("Maintenance Ticket", 'Add');
}
$access_group_var_list = per_hasModuleAccess("Maintenance Ticket", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Maintenance Ticket", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Maintenance Ticket", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Maintenance Ticket", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Maintenance Ticket", 'Edit', 'N');
# ----------- Access Rule Condition -----------
include_once($controller_path . "maintenance_ticket.inc.php");
$MaintenanceTicketObj = new MaintenanceTicket();

if($mode == "Update") {
    $iMaintenanceTicketId = $_REQUEST['iMaintenanceTicketId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "maintenance_ticket.\"iMaintenanceTicketId\"='".gen_add_slash($iMaintenanceTicketId)."'";
    $join_fieds_arr[] = 'so."vMasterMSA"';
    $join_fieds_arr[] = 'so."vServiceOrder"';
    $join_arr[] = 'LEFT JOIN service_order so ON maintenance_ticket."iServiceOrderId" = so."iServiceOrderId"';
    $MaintenanceTicketObj->join_field = $join_fieds_arr;
    $MaintenanceTicketObj->join = $join_arr;
    $MaintenanceTicketObj->where = $where_arr;
    $MaintenanceTicketObj->param['limit'] = "LIMIT 1";
    $MaintenanceTicketObj->setClause();
    $rs_maintenance = $MaintenanceTicketObj->recordset_list();
    //echo "<pre>";print_r($rs_maintenance);exit();
    if($rs_maintenance){
        $vSODisplay = "ID#". $rs_maintenance[0]['iServiceOrderId']." | ".$rs_maintenance[0]['vMasterMSA']." | ".$rs_maintenance[0]['vServiceOrder'];
        $rs_maintenance[0]['vSODisplay'] = $vSODisplay;

        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr  = array();
        $MaintenanceTicketObj->clear_variable();
        $where_arr[] = "maintenance_ticket_premise.\"iMaintenanceTicketId\"='".gen_add_slash($iMaintenanceTicketId)."'";
        $join_fieds_arr[] = 's."vName" as "vPremiseName"';
        $join_fieds_arr[] = 'concat(s."vAddress1", \' \', s."vStreet") as "vAddress"';
        $join_arr[] = 'LEFT JOIN premise_mas s on maintenance_ticket_premise."iPremiseId" = s."iPremiseId"';

        $MaintenanceTicketObj->join_field = $join_fieds_arr;
        $MaintenanceTicketObj->join = $join_arr;
        $MaintenanceTicketObj->where = $where_arr;
        $MaintenanceTicketObj->param['order_by'] = 'maintenance_ticket_premise."iPremiseId"';
        $MaintenanceTicketObj->setClause();
        $rs_maintenance_premise = $MaintenanceTicketObj->maintenance_ticket_premise_recordset_list();
        $maintenance_ticket_premise_count = count($rs_maintenance_premise);
        if($maintenance_ticket_premise_count > 0){
            for ($i=0; $i <$maintenance_ticket_premise_count ; $i++) { 
                if($rs_maintenance_premise[$i]['dMaintenanceStartDate'] != '')
                    $rs_maintenance_premise[$i]['dMaintenanceStartDate'] = date("Y-m-d", strtotime($rs_maintenance_premise[$i]['dMaintenanceStartDate']));

                //echo $rs_maintenance_premise[$i]['dResolvedDate'];exit;
                if($rs_maintenance_premise[$i]['dResolvedDate'] != '')
                    $rs_maintenance_premise[$i]['dResolvedDate'] = date("Y-m-d", strtotime($rs_maintenance_premise[$i]['dResolvedDate']));
            }
        }
    }
    //echo "<pre>";print_r($rs_maintenance_premise);exit();
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
}else if($mode =="search_service_order"){
    $arr_param = array();
    $vServiceOrder = trim($_REQUEST['vServiceOrder']);
    $arr_param['sessionId'] = $_SESSION["we_api_session_id" . $admin_panel_session_suffix];
    $arr_param['vServiceOrder'] = $vServiceOrder;
    $API_URL = $site_api_url."search_service_order.json";
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
}

/*-------------------------- User -------------------------- */
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
$res = json_decode($response, true);
$smarty->assign("rs_user", $res['result']);
//echo "<pre>";print_r($res['result']);exit;
/*-------------------------- User -------------------------- */
$dTodayDate = date('Y-m-d');
$module_name = "Maintenance Ticket ";
$module_title = "Maintenance Ticket";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("rs_maintenance", $rs_maintenance);
$smarty->assign("rs_maintenance_premise", $rs_maintenance_premise);
$smarty->assign("access_group_var_edit", $access_group_var_edit);
$smarty->assign("dTodayDate", $dTodayDate);
$smarty->assign("maintenance_ticket_premise_count", $maintenance_ticket_premise_count);
?>