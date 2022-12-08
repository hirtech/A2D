<?php
//echo "<pre>";print_r($_REQUEST);exit();
include_once($site_path . "scripts/session_valid.php");
$mode = (($_REQUEST['mode'] != "") ? $_REQUEST['mode'] : "Add");
# ----------- Access Rule Condition -----------
if ($mode == "Update") {
    per_hasModuleAccess("Trouble Ticket", 'Edit');
} else {
    per_hasModuleAccess("Trouble Ticket", 'Add');
}
$access_group_var_list = per_hasModuleAccess("Trouble Ticket", 'List', 'N');
$access_group_var_delete = per_hasModuleAccess("Trouble Ticket", 'Delete', 'N');
$access_group_var_status = per_hasModuleAccess("Trouble Ticket", 'Status', 'N');
$access_group_var_add = per_hasModuleAccess("Trouble Ticket", 'Add', 'N');
$access_group_var_edit = per_hasModuleAccess("Trouble Ticket", 'Edit', 'N');
# ----------- Access Rule Condition -----------
include_once($controller_path . "trouble_ticket.inc.php");
$TroubleTicketObj = new TroubleTicket();

if($mode == "Update") {
    $iTroubleTicketId = $_REQUEST['iTroubleTicketId'];
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $where_arr[] = "trouble_ticket.\"iTroubleTicketId\"='".gen_add_slash($iTroubleTicketId)."'";
    $join_fieds_arr[] = 'so."vMasterMSA"';
    $join_fieds_arr[] = 'so."vServiceOrder"';
    $join_arr[] = 'LEFT JOIN service_order so ON trouble_ticket."iServiceOrderId" = so."iServiceOrderId"';
    $TroubleTicketObj->join_field = $join_fieds_arr;
    $TroubleTicketObj->join = $join_arr;
    $TroubleTicketObj->where = $where_arr;
    $TroubleTicketObj->param['limit'] = "LIMIT 1";
    $TroubleTicketObj->setClause();
    $rs_trouble = $TroubleTicketObj->recordset_list();
    //echo "<pre>";print_r($rs_trouble);exit();
    if($rs_trouble){
        $vSODisplay = "ID#". $rs_trouble[0]['iServiceOrderId']." | ".$rs_trouble[0]['vMasterMSA']." | ".$rs_trouble[0]['vServiceOrder'];
        $rs_trouble[0]['vSODisplay'] = $vSODisplay;

        $where_arr = array();
        $join_fieds_arr = array();
        $join_arr  = array();
        $TroubleTicketObj->clear_variable();
        $where_arr[] = "trouble_ticket_premise.\"iTroubleTicketId\"='".gen_add_slash($iTroubleTicketId)."'";
        $join_fieds_arr[] = 's."vName" as "vPremiseName"';
        $join_fieds_arr[] = 'concat(s."vAddress1", \' \', s."vStreet") as "vAddress"';
        $join_arr[] = 'LEFT JOIN premise_mas s on trouble_ticket_premise."iPremiseId" = s."iPremiseId"';

        $TroubleTicketObj->join_field = $join_fieds_arr;
        $TroubleTicketObj->join = $join_arr;
        $TroubleTicketObj->where = $where_arr;
        $TroubleTicketObj->param['order_by'] = 'trouble_ticket_premise."iPremiseId"';
        $TroubleTicketObj->setClause();
        $rs_trouble_premise = $TroubleTicketObj->trouble_ticket_premise_recordset_list();
        $trouble_ticket_premise_count = count($rs_trouble_premise);
        if($trouble_ticket_premise_count > 0){
            for ($i=0; $i <$trouble_ticket_premise_count ; $i++) { 
                if($rs_trouble_premise[$i]['dTroubleStartDate'] != '')
                    $rs_trouble_premise[$i]['dTroubleStartDate'] = date("Y-m-d", strtotime($rs_trouble_premise[$i]['dTroubleStartDate']));

                //echo $rs_trouble_premise[$i]['dResolvedDate'];exit;
                if($rs_trouble_premise[$i]['dResolvedDate'] != '')
                    $rs_trouble_premise[$i]['dResolvedDate'] = date("Y-m-d", strtotime($rs_trouble_premise[$i]['dResolvedDate']));
            }
        }
    }
    //echo "<pre>";print_r($rs_trouble_premise);exit();
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
$module_name = "Trouble Ticket ";
$module_title = "Trouble Ticket";
$smarty->assign("module_name", $module_name);
$smarty->assign("module_title", $module_title);
$smarty->assign("mode", $mode);
$smarty->assign("rs_trouble", $rs_trouble);
$smarty->assign("rs_trouble_premise", $rs_trouble_premise);
$smarty->assign("access_group_var_edit", $access_group_var_edit);
$smarty->assign("dTodayDate", $dTodayDate);
$smarty->assign("trouble_ticket_premise_count", $trouble_ticket_premise_count);
?>