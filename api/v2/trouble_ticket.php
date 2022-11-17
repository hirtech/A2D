<?php
include_once($controller_path . "trouble_ticket.inc.php");

$TroubleTicketObj = new TroubleTicket();
if($request_type == "trouble_ticket_list"){
    $where_arr = array();
    if(!empty($RES_PARA)){
        $iTroubleTicketId   = $RES_PARA['iTroubleTicketId'];
        $vAssignedTo        = $RES_PARA['vAssignedTo'];
        $vServiceOrder      = $RES_PARA['vServiceOrder'];

        $iSAssignedToId     = $RES_PARA['iSAssignedToId'];
        $iSServiceOrderId   = $RES_PARA['iSServiceOrderId'];
        $iSSeverity         = $RES_PARA['iSSeverity'];
        $iSStatus           = $RES_PARA['iSStatus'];
        $dSCompletionDate   = $RES_PARA['dSCompletionDate'];
        $tSDescriptionDD        = $RES_PARA['tSDescriptionDD'];
        $tSDescription          = trim($RES_PARA['tSDescription']);
        $iSPremiseId        = $RES_PARA['iSPremiseId'];
        $vSPremiseNameDD    = $RES_PARA['vSPremiseNameDD'];
        $vSPremiseName      = trim($RES_PARA['vSPremiseName']);
        $vSAddressDD        = $RES_PARA['vSAddressDD'];
        $vSAddress          = trim($RES_PARA['vSAddress']);

        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
        $order_by           = $RES_PARA['order_by'];
    }
    if ($iTroubleTicketId != "") {
        $where_arr[] = 'trouble_ticket."iTroubleTicketId"='.$iTroubleTicketId ;
    }

    if ($vAssignedTo != "") {
        $where_arr[] = "(u.\"vFirstName\" ILIKE '" . $vAssignedTo . "%' OR u.\"vLastName\" ILIKE '" . $vAssignedTo . "%')";
    }

    if ($vServiceOrder != "") {
        $where_arr[] = "so.\"vServiceOrder\" ILIKE '" . $vServiceOrder . "%'";
    }

    if ($iSAssignedToId != "") {
        $where_arr[] = 'trouble_ticket."iAssignedToId"='.$iSAssignedToId ;
    }

    if ($iSServiceOrderId != "") {
        $where_arr[] = 'trouble_ticket."iServiceOrderId"='.$iSServiceOrderId ;
    }

    if ($iSSeverity != "") {
        $where_arr[] = 'trouble_ticket."iSeverity"='.$iSSeverity ;
    }

    if ($iSStatus != "") {
        $where_arr[] = 'trouble_ticket."iStatus"='.$iSStatus ;
    }

    if ($dSCompletionDate != "") {
        $where_arr[] = "(trouble_ticket.\"dCompletionDate\" = '" . $dSCompletionDate . "')";
    }

    if ($tSDescription != "") {
        if ($tSDescriptionDD != "") {
            if ($tSDescriptionDD == "Begins") {
                $where_arr[] = 'trouble_ticket."tDescription" ILIKE \''.$tSDescription.'%\'';
            } else if ($tSDescriptionDD == "Ends") {
                $where_arr[] = 'trouble_ticket."tDescription" ILIKE \'%'.$tSDescription.'\'';
            } else if ($tSDescriptionDD == "Contains") {
                $where_arr[] = 'trouble_ticket."tDescription" ILIKE \'%'.$tSDescription.'%\'';
            } else if ($tSDescriptionDD == "Exactly") {
                $where_arr[] = 'trouble_ticket."tDescription" ILIKE \''.$tSDescription.'\'';
            }
        } else {
            $where_arr[] = 'trouble_ticket."tDescription" ILIKE \''.$tSDescription.'%\'';
        }
    }

    $iTroubleTicketIdArr = array();
    // trouble_ticket_premise Filters
    $premise_where_arr = [];
    if ($iSPremiseId != "") {
        $premise_where_arr[] = "trouble_ticket_premise.\"iPremiseId\"='".$iSPremiseId."'";
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
    if(!empty($premise_where_arr)) {
        $premise_join_fieds_arr = array();
        $premise_join_fieds_arr[] = 's."vName"';
        $premise_join_fieds_arr[] = 's."vAddress1"';
        $premise_join_fieds_arr[] = 's."vStreet"';
        $premise_join_arr = array();
        $premise_join_arr[] = 'LEFT JOIN site_mas s on trouble_ticket_premise."iPremiseId" = s."iSiteId"';
        $TroubleTicketObj->join_field = $premise_join_fieds_arr;
        $TroubleTicketObj->join = $premise_join_arr;
        $TroubleTicketObj->where = $premise_where_arr;
        $TroubleTicketObj->param['order_by'] = "s.\"vName\" ASC";
        $TroubleTicketObj->setClause();
        $TroubleTicketObj->debug_query = false;
        $rs = $TroubleTicketObj->trouble_ticket_premise_recordset_list();
        if($rs) {
            $ci =count($rs);
            for($c=0; $c<$ci; $c++){
                $iTroubleTicketIdArr[] = $rs[$c]['iTroubleTicketId'];
            }
        }
    }
    array_unique($iTroubleTicketIdArr);
    if(!empty($iTroubleTicketIdArr)){
        $where_arr[] = "trouble_ticket.\"iTroubleTicketId\" IN (".implode(",", $iTroubleTicketIdArr).") ";
    }

    //echo "<pre>"; print_r($where_arr);exit;

    switch ($display_order) {
        case "0":
            $sortname = "trouble_ticket.\"iTroubleTicketId\"";
            break;
        case "1":
            $sortname = "\"vAssignedTo\"";
            break;
        case "2":
            $sortname = "s.\"iServiceOrderId\"";
            break;
        case "3":
            $sortname = "trouble_ticket.\"iSeverity\"";
            break;
        case "4":
            $sortname = "trouble_ticket.\"iStatus\"";
            break;
        case "5":
            $sortname = "trouble_ticket.\"dCompletionDate\"";
            break;
        case "6":
            $sortname = "trouble_ticket.\"tDescription\"";
            break;
        default:
            $sortname = "trouble_ticket.\"iTroubleTicketId\"";
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = "concat(u.\"vFirstName\", ' ', u.\"vLastName\") as \"vAssignedTo\" ";
    $join_fieds_arr[] = 'so."vMasterMSA"';
    $join_fieds_arr[] = 'so."vServiceOrder"';
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN user_mas u on u."iUserId" = trouble_ticket."iAssignedToId"';
    $join_arr[] = 'LEFT JOIN service_order so on so."iServiceOrderId" = trouble_ticket."iServiceOrderId"';
    $TroubleTicketObj->join_field = $join_fieds_arr;
    $TroubleTicketObj->join = $join_arr;
    $TroubleTicketObj->where = $where_arr;
    $TroubleTicketObj->param['order_by'] = $sortname . " " . $dir;
    $TroubleTicketObj->param['limit'] = $limit;
    $TroubleTicketObj->setClause();
    $TroubleTicketObj->debug_query = false;
    $rs_trouble_ticket = $TroubleTicketObj->recordset_list();
    // Paging Total Records
    $total_record = $TroubleTicketObj->recordset_total();
    // Paging Total Records
    
    $data = array();
    $ni = count($rs_trouble_ticket);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){

            $data[] = array(
                "iTroubleTicketId"  => $rs_trouble_ticket[$i]['iTroubleTicketId'],
                "iAssignedToId"     => $rs_trouble_ticket[$i]['iAssignedToId'],
                "vAssignedTo"       => $rs_trouble_ticket[$i]['vAssignedTo'],
                "iServiceOrderId"   => $rs_trouble_ticket[$i]['iServiceOrderId'],
                "vServiceOrder"     => $rs_trouble_ticket[$i]['vServiceOrder'],
                "vMasterMSA"        => $rs_trouble_ticket[$i]['vMasterMSA'],
                "iSeverity"         => $rs_trouble_ticket[$i]['iSeverity'],
                "iStatus"           => $rs_trouble_ticket[$i]['iStatus'], 
                "dCompletionDate"   => $rs_trouble_ticket[$i]['dCompletionDate'], 
                "tDescription"      => $rs_trouble_ticket[$i]['tDescription'], 
                "dAddedDate"        => $rs_trouble_ticket[$i]['dAddedDate'], 
                "dModifiedDate"     => $rs_trouble_ticket[$i]['dModifiedDate'], 
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "trouble_ticket_add"){
    $is_error = 0;
    $premise_length = $RES_PARA['premise_length'];
    if($RES_PARA['iStatus'] == 3) { //Complete 
        $dResolvedDatecnt = 0;
        for($i=0; $i<$premise_length; $i++){
            if(!empty($RES_PARA['dResolvedDate'][$i])) {
                $dResolvedDatecnt++;
            }
        }
        if($dResolvedDatecnt == 0) {
            //echo "string";
            $is_error = 1;
            $response_data = array("Code" => 500 , "Message" => 'You cannot select complete if any premise-record below has "Date - Resolved" field blank/null', "error" => 2);
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
            "iAssignedToId"     => $RES_PARA['iAssignedToId'],
            "iServiceOrderId"   => $RES_PARA['iServiceOrderId'],
            "iSeverity"         => $RES_PARA['iSeverity'],
            "iStatus"           => $RES_PARA['iStatus'],
            "dCompletionDate"   => $RES_PARA['dCompletionDate'],
            "tDescription"      => $RES_PARA['tDescription'],
            "premise_length"    => $RES_PARA['premise_length'],
            "iPremiseId"        => $RES_PARA['iPremiseId'],
            "dTroubleStartDate" => $RES_PARA['dTroubleStartDate'],
            "dResolvedDate"     => $RES_PARA['dResolvedDate'],
        );
        $TroubleTicketObj->insert_arr = $insert_arr;
        $TroubleTicketObj->setClause();
        $iTroubleTicketId = $TroubleTicketObj->add_records();

        if($iTroubleTicketId){
            $response_data = array("Code" => 200, "Message" => MSG_ADD, "error" => 0, "iTroubleTicketId" => $iTroubleTicketId);
        }else{
            $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR, "error" => 1);
        }
    }
}else if($request_type == "trouble_ticket_edit"){
    $is_error = 0;
    $premise_length = $RES_PARA['premise_length'];
    if($RES_PARA['iStatus'] == 3) { //Complete 
        $dResolvedDatecnt = 0;
        for($i=0; $i<$premise_length; $i++){
            if(!empty($RES_PARA['dResolvedDate'][$i])) {
                $dResolvedDatecnt++;
            }
        }
        if($dResolvedDatecnt == 0) {
            $is_error = 1;
            $response_data = array("Code" => 500 , "Message" => 'You cannot select complete if any premise-record below has "Date - Resolved" field blank/null', "error" => 2);
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
            "iTroubleTicketId"  => $RES_PARA['iTroubleTicketId'],
            "iAssignedToId"     => $RES_PARA['iAssignedToId'],
            "iServiceOrderId"   => $RES_PARA['iServiceOrderId'],
            "iSeverity"         => $RES_PARA['iSeverity'],
            "iStatus"           => $RES_PARA['iStatus'],
            "dCompletionDate"   => $RES_PARA['dCompletionDate'],
            "tDescription"      => $RES_PARA['tDescription'],
            "premise_length"    => $RES_PARA['premise_length'],
            "iPremiseId"        => $RES_PARA['iPremiseId'],
            "dTroubleStartDate" => $RES_PARA['dTroubleStartDate'],
            "dResolvedDate"     => $RES_PARA['dResolvedDate'],
        );
        $TroubleTicketObj->update_arr = $update_arr;
        $TroubleTicketObj->setClause();
        $rs_db = $TroubleTicketObj->update_records();

        if($rs_db){
            $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "error" => 0, "iTroubleTicketId" => $RES_PARA['iTroubleTicketId']);
        }else{
            $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR, "error" => 1);
        }
    }
}else if($request_type == "trouble_ticket_delete"){
    $iTroubleTicketId = $RES_PARA['iTroubleTicketId'];
    $rs_db = $TroubleTicketObj->delete_records($iTroubleTicketId);
    if($rs_db) {
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iTroubleTicketId" => $iTroubleTicketId);
    }
    else {
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>