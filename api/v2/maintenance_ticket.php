<?php
include_once($controller_path . "maintenance_ticket.inc.php");

$MaintenanceTicketObj = new MaintenanceTicket();
if($request_type == "maintenance_ticket_list"){
    $where_arr = array();
    if(!empty($RES_PARA)){
        $iMaintenanceTicketId   = $RES_PARA['iMaintenanceTicketId'];
        $vAssignedTo            = $RES_PARA['vAssignedTo'];
        $vServiceOrder          = $RES_PARA['vServiceOrder'];

        $iSAssignedToId         = $RES_PARA['iSAssignedToId'];
        $iSServiceOrderId       = $RES_PARA['iSServiceOrderId'];
        $iSSeverity             = $RES_PARA['iSSeverity'];
        $iSStatus               = $RES_PARA['iSStatus'];
        $dSCompletionDate       = $RES_PARA['dSCompletionDate'];
        $tSDescriptionDD        = $RES_PARA['tSDescriptionDD'];
        $tSDescription          = trim($RES_PARA['tSDescription']);
        $iSPremiseId            = $RES_PARA['iSPremiseId'];
        $vSPremiseNameDD        = $RES_PARA['vSPremiseNameDD'];
        $vSPremiseName          = trim($RES_PARA['vSPremiseName']);
        $vSAddressDD            = $RES_PARA['vSAddressDD'];
        $vSAddress              = trim($RES_PARA['vSAddress']);
        $iSNetworkId            = $RES_PARA['iSNetworkId'];
        $iSCarrierId            = $RES_PARA['iSCarrierId'];

        $page_length            = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start                  = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho                  = $RES_PARA['sEcho'];
        $display_order          = $RES_PARA['display_order'];
        $dir                    = $RES_PARA['dir'];
        $order_by               = $RES_PARA['order_by'];
    }
    if ($iMaintenanceTicketId != "") {
        $where_arr[] = 'maintenance_ticket."iMaintenanceTicketId"='.$iMaintenanceTicketId ;
    }

    if ($vAssignedTo != "") {
        $where_arr[] = "(u.\"vFirstName\" ILIKE '" . $vAssignedTo . "%' OR u.\"vLastName\" ILIKE '" . $vAssignedTo . "%')";
    }

    if ($vServiceOrder != "") {
        $where_arr[] = "so.\"vServiceOrder\" ILIKE '" . $vServiceOrder . "%'";
    }

    if ($iSAssignedToId != "") {
        $where_arr[] = 'maintenance_ticket."iAssignedToId"='.$iSAssignedToId ;
    }

    if ($iSServiceOrderId != "") {
        $where_arr[] = 'maintenance_ticket."iServiceOrderId"='.$iSServiceOrderId ;
    }

    if ($iSCarrierId != "") {
        $where_arr[] = 'so."iCarrierID"='.$iSCarrierId ;
    }

    if ($iSSeverity != "") {
        $where_arr[] = 'maintenance_ticket."iSeverity"='.$iSSeverity ;
    }

    if ($iSStatus != "") {
        $where_arr[] = 'maintenance_ticket."iStatus"='.$iSStatus ;
    }

    if ($dSCompletionDate != "") {
        $where_arr[] = "(maintenance_ticket.\"dCompletionDate\" = '" . $dSCompletionDate . "')";
    }

    if ($tSDescription != "") {
        if ($tSDescriptionDD != "") {
            if ($tSDescriptionDD == "Begins") {
                $where_arr[] = 'maintenance_ticket."tDescription" ILIKE \''.$tSDescription.'%\'';
            } else if ($tSDescriptionDD == "Ends") {
                $where_arr[] = 'maintenance_ticket."tDescription" ILIKE \'%'.$tSDescription.'\'';
            } else if ($tSDescriptionDD == "Contains") {
                $where_arr[] = 'maintenance_ticket."tDescription" ILIKE \'%'.$tSDescription.'%\'';
            } else if ($tSDescriptionDD == "Exactly") {
                $where_arr[] = 'maintenance_ticket."tDescription" ILIKE \''.$tSDescription.'\'';
            }
        } else {
            $where_arr[] = 'maintenance_ticket."tDescription" ILIKE \''.$tSDescription.'%\'';
        }
    }

    $iMaintenanceTicketIdArr = array();
    // maintenance_ticket_premise Filters
    $premise_where_arr = [];
    if ($iSPremiseId != "") {
        $premise_where_arr[] = "maintenance_ticket_premise.\"iPremiseId\"='".$iSPremiseId."'";
    }
    if ($vSPremiseName != "") {
        if ($vSPremiseNameDD != "") {
            if ($vSPremiseNameDD == "Begins") {
                $premise_where_arr[] = 's."vName" ILIKE \''.$vSPremiseName.'%\'';
            } else if ($vSPremiseNameDD == "Ends") {
                $premise_where_arr[] = 's."vName" ILIKE \'%'.$vSPremiseName.'\'';
            } else if ($vSPremiseNameDD == "Contains") {
                $premise_where_arr[] = 's."vName" ILIKE \'%'.$vSPremiseName.'%\'';
            } else if ($vSPremiseNameDD == "Exactly") {
                $premise_where_arr[] = 's."vName" ILIKE \''.$vSPremiseName.'\'';
            }
        } else {
            $premise_where_arr[] = 's."vName" ILIKE \''.$vSPremiseName.'%\'';
        }
    }
    if ($vSAddress != "") {
        if ($vSAddressDD != "") {
            if ($vSAddressDD == "Begins") {
                $premise_where_arr[] = '(s."vAddress1" ILIKE \''.$vSAddress.'%\' OR s."vStreet" ILIKE \''.$vSAddress.'%\')';
            } else if ($vSAddressDD == "Ends") {
                $premise_where_arr[] = '(s."vAddress1" ILIKE \'%'.$vSAddress.'\' OR s."vStreet" ILIKE \'%'.$vSAddress.'\')';
            } else if ($vSAddressDD == "Contains") {
                $premise_where_arr[] = '(s."vAddress1" ILIKE \'%'.$vSAddress.'%\' OR s."vStreet" ILIKE \'%'.$vSAddress.'%\')';
            } else if ($vSAddressDD == "Exactly") {
                $premise_where_arr[] = '(s."vAddress1" ILIKE \''.$vSAddress.'\' OR s."vStreet" ILIKE \''.$vSAddress.'\')';
            }
        } else {
            $premise_where_arr[] = '(s."vAddress1" ILIKE \''.$vSAddress.'%\' OR s."vStreet" ILIKE \''.$vSAddress.'%\')';
        }
    }
    if($iSNetworkId > 0) {
        $premise_where_arr[] = "z.\"iNetworkId\"='".$iSNetworkId."'";
    }

    if(!empty($premise_where_arr)) {
        $premise_join_fieds_arr = array();
        $premise_join_fieds_arr[] = 's."vName"';
        $premise_join_fieds_arr[] = 's."vAddress1"';
        $premise_join_fieds_arr[] = 's."vStreet"';
        $premise_join_fieds_arr[] = 'z."iNetworkId"';
        $premise_join_arr = array();
        $premise_join_arr[] = 'LEFT JOIN premise_mas s on maintenance_ticket_premise."iPremiseId" = s."iPremiseId"';
        $premise_join_arr[] = 'LEFT JOIN zone z on s."iZoneId" = z."iZoneId"';
        $MaintenanceTicketObj->join_field = $premise_join_fieds_arr;
        $MaintenanceTicketObj->join = $premise_join_arr;
        $MaintenanceTicketObj->where = $premise_where_arr;
        $MaintenanceTicketObj->param['order_by'] = "s.\"vName\" ASC";
        $MaintenanceTicketObj->setClause();
        $MaintenanceTicketObj->debug_query = false;
        $rs = $MaintenanceTicketObj->maintenance_ticket_premise_recordset_list();
        if($rs) {
            $ci =count($rs);
            for($c=0; $c<$ci; $c++){
                $iMaintenanceTicketIdArr[] = $rs[$c]['iMaintenanceTicketId'];
            }
        }
    }
    array_unique($iMaintenanceTicketIdArr);
    if(!empty($iMaintenanceTicketIdArr)){
        $where_arr[] = "maintenance_ticket.\"iMaintenanceTicketId\" IN (".implode(",", $iMaintenanceTicketIdArr).") ";
    }

    //echo "<pre>"; print_r($where_arr);exit;

    switch ($display_order) {
        case "1":
            $sortname = "maintenance_ticket.\"iMaintenanceTicketId\"";
            break;
        case "2":
            $sortname = "\"vAssignedTo\"";
            break;
        case "3":
            $sortname = "s.\"iServiceOrderId\"";
            break;
        case "4":
            $sortname = "maintenance_ticket.\"iSeverity\"";
            break;
        case "5":
            $sortname = "maintenance_ticket.\"iStatus\"";
            break;
        case "6":
            $sortname = "maintenance_ticket.\"dCompletionDate\"";
            break;
        case "7":
            $sortname = "maintenance_ticket.\"tDescription\"";
            break;
        default:
            $sortname = "maintenance_ticket.\"iMaintenanceTicketId\"";
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = "concat(u.\"vFirstName\", ' ', u.\"vLastName\") as \"vAssignedTo\" ";
    $join_fieds_arr[] = 'so."vMasterMSA"';
    $join_fieds_arr[] = 'so."vServiceOrder"';
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN user_mas u on u."iUserId" = maintenance_ticket."iAssignedToId"';
    $join_arr[] = 'LEFT JOIN service_order so on so."iServiceOrderId" = maintenance_ticket."iServiceOrderId"';
    $join_arr[] = 'LEFT JOIN company_mas cm on cm."iCompanyId" = so."iCarrierID"';
    $MaintenanceTicketObj->join_field = $join_fieds_arr;
    $MaintenanceTicketObj->join = $join_arr;
    $MaintenanceTicketObj->where = $where_arr;
    $MaintenanceTicketObj->param['order_by'] = $sortname . " " . $dir;
    $MaintenanceTicketObj->param['limit'] = $limit;
    $MaintenanceTicketObj->setClause();
    $MaintenanceTicketObj->debug_query = false;
    $rs_maintenance_ticket = $MaintenanceTicketObj->recordset_list();
    // Paging Total Records
    $total_record = $MaintenanceTicketObj->recordset_total();
    // Paging Total Records
    
    $data = array();
    $ni = count($rs_maintenance_ticket);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $data[] = array(
                "iMaintenanceTicketId"  => $rs_maintenance_ticket[$i]['iMaintenanceTicketId'],
                "iAssignedToId"         => $rs_maintenance_ticket[$i]['iAssignedToId'],
                "vAssignedTo"           => $rs_maintenance_ticket[$i]['vAssignedTo'],
                "iServiceOrderId"       => $rs_maintenance_ticket[$i]['iServiceOrderId'],
                "vServiceOrder"         => $rs_maintenance_ticket[$i]['vServiceOrder'],
                "vMasterMSA"            => $rs_maintenance_ticket[$i]['vMasterMSA'],
                "iSeverity"             => $rs_maintenance_ticket[$i]['iSeverity'],
                "iStatus"               => $rs_maintenance_ticket[$i]['iStatus'], 
                "dCompletionDate"       => $rs_maintenance_ticket[$i]['dCompletionDate'], 
                "tDescription"          => $rs_maintenance_ticket[$i]['tDescription'], 
                "dAddedDate"            => $rs_maintenance_ticket[$i]['dAddedDate'], 
                "dModifiedDate"         => $rs_maintenance_ticket[$i]['dModifiedDate'], 
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "maintenance_ticket_add"){
    $is_error = 0;
    $premise_length = $RES_PARA['premise_length'];
    if($RES_PARA['iStatus'] == 3) { //Complete 
        $dResolvedDatecnt = 0;
        for($i=0; $i<$premise_length; $i++){
            if(empty($RES_PARA['dResolvedDate'][$i])) {
                $dResolvedDatecnt++;
            }
        }
        if($dResolvedDatecnt > 0) {
            //echo "string";
            $is_error = 1;
            $response_data = array("Code" => 500 , "Message" => 'You cannot select complete if any premise-record below has "Date - Resolved" field blank/null', "error" => 2);
        }else{
            if($RES_PARA['dCompletionDate'] == ''){
                $is_error = 1;
                $response_data = array("Code" => 500 , "Message" => '"Completion Date" cannot be blank/null if status is "Completed".', "error" => 2);
            }
        }
    }

    if($RES_PARA['dCompletionDate'] != '') {
        $dCompletionDatecnt = 0;
        for($i=0; $i<$premise_length; $i++){
            if($RES_PARA['dResolvedDate'][$i] != ''){
                //echo $RES_PARA['dCompletionDate']." < ".$RES_PARA['dResolvedDate'][$i]."<br/>";
                if($RES_PARA['dCompletionDate'] < $RES_PARA['dResolvedDate'][$i]) {
                    $dCompletionDatecnt++;
                }
            }
        }
        //echo $dCompletionDatecnt;exit;
        if($dCompletionDatecnt > 0) {
            $is_error = 1;
            $response_data = array("Code" => 500 , "Message" => 'Completion Date cannot be before any "Date - Resolved" below', "error" => 2);
        }
    }
    //echo "<pre>";print_r($is_error);exit;
    if($is_error == 0){
        $insert_arr = array(
            "iAssignedToId"         => $RES_PARA['iAssignedToId'],
            "iServiceOrderId"       => $RES_PARA['iServiceOrderId'],
            "iSeverity"             => $RES_PARA['iSeverity'],
            "iStatus"               => $RES_PARA['iStatus'],
            "dCompletionDate"       => $RES_PARA['dCompletionDate'],
            "tDescription"          => $RES_PARA['tDescription'],
            "premise_length"        => $RES_PARA['premise_length'],
            "iPremiseId"            => $RES_PARA['iPremiseId'],
            "dMaintenanceStartDate" => $RES_PARA['dMaintenanceStartDate'],
            "dResolvedDate"         => $RES_PARA['dResolvedDate'],
        );
        $MaintenanceTicketObj->insert_arr = $insert_arr;
        $MaintenanceTicketObj->setClause();
        $iMaintenanceTicketId = $MaintenanceTicketObj->add_records();

        if($iMaintenanceTicketId){
            $response_data = array("Code" => 200, "Message" => MSG_ADD, "error" => 0, "iMaintenanceTicketId" => $iMaintenanceTicketId);
        }else{
            $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR, "error" => 1);
        }
    }
}else if($request_type == "maintenance_ticket_edit"){
    $is_error = 0;
    $premise_length = $RES_PARA['premise_length'];
    if($RES_PARA['iStatus'] == 3) { //Complete 
        $dResolvedDatecnt = 0;
        for($i=0; $i<$premise_length; $i++){
            if(empty($RES_PARA['dResolvedDate'][$i])) {
                $dResolvedDatecnt++;
            }
        }
        if($dResolvedDatecnt > 0) {
            $is_error = 1;
            $response_data = array("Code" => 500 , "Message" => 'You cannot select complete if any premise-record below has "Date - Resolved" field blank/null', "error" => 2);
        }else{
            if($RES_PARA['dCompletionDate'] == ''){
                $is_error = 1;
                $response_data = array("Code" => 500 , "Message" => '"Completion Date" cannot be blank/null if status is "Completed".', "error" => 2);
            }
        }
    }

    if($RES_PARA['dCompletionDate'] != '') {
        $dCompletionDatecnt = 0;
        for($i=0; $i<$premise_length; $i++){
            if($RES_PARA['dResolvedDate'][$i] != ''){
                //echo $RES_PARA['dCompletionDate']." < ".$RES_PARA['dResolvedDate'][$i]."<br/>";
                if($RES_PARA['dCompletionDate'] < $RES_PARA['dResolvedDate'][$i]) {
                    $dCompletionDatecnt++;
                }
            }
        }
        //echo $dCompletionDatecnt;exit;
        if($dCompletionDatecnt > 0) {
            $is_error = 1;
            $response_data = array("Code" => 500 , "Message" => 'Completion Date cannot be before any "Date - Resolved" below', "error" => 2);
        }
    }
    //echo "<pre>";print_r($is_error);exit;
    if($is_error == 0){
        $update_arr = array(
            "iMaintenanceTicketId"  => $RES_PARA['iMaintenanceTicketId'],
            "iAssignedToId"         => $RES_PARA['iAssignedToId'],
            "iServiceOrderId"       => $RES_PARA['iServiceOrderId'],
            "iSeverity"             => $RES_PARA['iSeverity'],
            "iStatus"               => $RES_PARA['iStatus'],
            "dCompletionDate"       => $RES_PARA['dCompletionDate'],
            "tDescription"          => $RES_PARA['tDescription'],
            "premise_length"        => $RES_PARA['premise_length'],
            "iPremiseId"            => $RES_PARA['iPremiseId'],
            "dMaintenanceStartDate" => $RES_PARA['dMaintenanceStartDate'],
            "dResolvedDate"         => $RES_PARA['dResolvedDate'],
        );
        $MaintenanceTicketObj->update_arr = $update_arr;
        $MaintenanceTicketObj->setClause();
        $rs_db = $MaintenanceTicketObj->update_records();

        if($rs_db){
            $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "error" => 0, "iMaintenanceTicketId" => $RES_PARA['iMaintenanceTicketId']);
        }else{
            $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR, "error" => 1);
        }
    }
}else if($request_type == "maintenance_ticket_delete"){
    $iMaintenanceTicketId = $RES_PARA['iMaintenanceTicketId'];
    $rs_db = $MaintenanceTicketObj->delete_records($iMaintenanceTicketId);
    if($rs_db) {
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iMaintenanceTicketId" => $iMaintenanceTicketId);
    }
    else {
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "maintenance_ticket_change_status"){
    $status = $RES_PARA['status'];
    $iMaintenanceTicketIds = $RES_PARA['iMaintenanceTicketIds'];
    $rs_db = $MaintenanceTicketObj->change_status($iMaintenanceTicketIds, $status);
    if($rs_db) {
        $response_data = array("Code" => 200, "Message" => "Status Changed Successfully.", "error" => 0, "iMaintenanceTicketId" => $iMaintenanceTicketIds);
    }
    else {
        $response_data = array("Code" => 500 , "Message" => "ERROR - in update status.", "error" => 1);
    }
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>