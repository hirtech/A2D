<?php 

include_once($controller_path . "user.inc.php");
include_once($controller_path . "fiber_inquiry.inc.php");
include_once($controller_path . "workorder.inc.php");
include_once($controller_path . "trouble_ticket.inc.php");
include_once($controller_path . "maintenance_ticket.inc.php");

$FiberInquiryObj = new FiberInquiry();
$WorkOrderObj = new WorkOrder();
$TroubleTicketObj = new TroubleTicket();
$MaintenanceTicketObj = new MaintenanceTicket();

if($request_type == "notification"){
	$notification_arr = array();
	
	$userid = $RES_PARA['userId'];
	$iAGroupId = $RES_PARA['iAGroupId'];

	$today = date("Y-m-d");
	$LAST_15_DAYS =  date('Y-m-d', strtotime('-15 days', strtotime($today)));
	//echo $LAST_15_DAYS;exit;

	if($SALES_ACCESS_GROUP_ID == $iAGroupId) {
	    $FiberInquiryObj->clear_variable();
	    $where_arr = array();
        $join_fieds_arr = array();
        $join_arr = array();

        $join_fieds_arr[] = "CONCAT(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") AS \"vContactName\"";;

        $join_arr[] = 'LEFT JOIN contact_mas on fiberinquiry_details."iCId" = contact_mas."iCId"';

        $where_arr[] = "fiberinquiry_details.\"dAddedDate\" >= '".$LAST_15_DAYS."'"; 
        $where_arr[] = "fiberinquiry_details.\"iLoginUserId\" = '".$userid."'"; 
        $FiberInquiryObj->join_field = $join_fieds_arr;
        $FiberInquiryObj->join = $join_arr;
        $FiberInquiryObj->where = $where_arr;
        $FiberInquiryObj->param['order_by'] = "fiberinquiry_details.\"iFiberInquiryId\" DESC";
        $FiberInquiryObj->setClause();
        $FiberInquiryObj->debug_query = false;
        $rs_fInquiry = $FiberInquiryObj->recordset_list();
		//echo "<pre>";print_r($rs_fInquiry);exit;
		$fi = count($rs_fInquiry);
		if($fi > 0) {
			for($i=0; $i< $fi; $i++){
				$vFStatus = '';
                if($rs_fInquiry[$i]['iStatus'] == 1){
                    $vFStatus = 'Draft';
                }else if($rs_fInquiry[$i]['iStatus'] == 2){
                    $vFStatus = 'Assigned';
                }else if($rs_fInquiry[$i]['iStatus'] == 3){
                    $vFStatus = 'Review';
                }else if($rs_fInquiry[$i]['iStatus'] == 4){
                    $vFStatus = 'Complete';
                }
				$notification_arr[] = array('type' => 'FiberInquiry' ,'iFiberInquiryId' => $rs_fInquiry[$i]['iFiberInquiryId'], 'dDate' => $rs_fInquiry[$i]['dAddedDate'], 'title' => "Fiber Inquiry #".$rs_fInquiry[$i]['iFiberInquiryId'].": ".date_display_report_date($rs_fInquiry[$i]['dAddedDate'])." | ".$rs_fInquiry[$i]['vContactName']." | ". $vFStatus);
			}
		}
	} else if($TECHNICIAN_ACCESS_GROUP_ID == $iAGroupId) {

		// ************ Workorder ************ //
		$WorkOrderObj->clear_variable();
    	$where_arr = array();
        $join_fieds_arr = array();
        $join_arr = array();

	    $join_fieds_arr[] = 'ws."vStatus"';
	    $join_fieds_arr[] = 'wt."vType"';
	    
	    $join_arr[] = 'LEFT JOIN workorder_status_mas ws on workorder."iWOSId" = ws."iWOSId"';
	    $join_arr[] = 'LEFT JOIN workorder_type_mas wt on workorder."iWOTId" = wt."iWOTId"';

        $where_arr[] = "workorder.\"iAssignedToId\" = '".$userid."'"; 
        $where_arr[] = "workorder.\"iWOSId\" != 2"; // Closed 

	    $WorkOrderObj->join_field = $join_fieds_arr;
	    $WorkOrderObj->join = $join_arr;
	    $WorkOrderObj->where = $where_arr;
	    $WorkOrderObj->param['order_by'] = "workorder.\"iWOId\" DESC";
	    $WorkOrderObj->setClause();
	    $WorkOrderObj->debug_query = false;
	    $rs_worder = $WorkOrderObj->recordset_list();
	    //echo "<pre>"; print_r($rs_worder);exit;
	    $wi = count($rs_worder);
	    if($wi > 0){
	    	for($i=0; $i<$wi; $i++){
	    		$notification_arr[] = array('type' => 'Workorder' ,'iWOId' => $rs_worder[$i]['iWOId'], 'dDate' => $rs_worder[$i]['dAddedDate'], 'title' => "Workorder #".$rs_worder[$i]['iWOId'].": ".date_display_report_date($rs_worder[$i]['dAddedDate'])." | ".$rs_worder[$i]['vWOProject']." | ".$rs_worder[$i]['vStatus']);
	    	}
	    }
	    // ************ Workorder ************ //

	    // ************ Trouble Ticket ************ //
	    $TroubleTicketObj->clear_variable();
	    $where_arr = array();
        $join_fieds_arr = array();
        $join_arr = array();

	    $join_fieds_arr[] = 'so."vMasterMSA"';
	    $join_fieds_arr[] = 'so."vServiceOrder"';
	    
	    $join_arr[] = 'LEFT JOIN service_order so on so."iServiceOrderId" = trouble_ticket."iServiceOrderId"';
	    $join_arr[] = 'LEFT JOIN company_mas cm on cm."iCompanyId" = so."iCarrierID"';

	    $where_arr[] = "trouble_ticket.\"iAssignedToId\" = '".$userid."'"; 
        $where_arr[] = "trouble_ticket.\"iStatus\" != 3"; // Completed 

	    $TroubleTicketObj->join_field = $join_fieds_arr;
	    $TroubleTicketObj->join = $join_arr;
	    $TroubleTicketObj->where = $where_arr;
	    $TroubleTicketObj->param['order_by'] = "trouble_ticket.\"iTroubleTicketId\" DESC";
	    $TroubleTicketObj->param['limit'] = $limit;
	    $TroubleTicketObj->setClause();
	    $TroubleTicketObj->debug_query = false;
	    $rs_trouble_ticket = $TroubleTicketObj->recordset_list();
	    $ti = count($rs_trouble_ticket);
	    if($ti > 0) {
	    	for($i=0; $i<$ti; $i++){
	    		$iSeverity = '---';
	            if($rs_trouble_ticket[$i]['iSeverity'] == 1){
	               $iSeverity = "Low"; 
	            }else if($rs_trouble_ticket[$i]['iSeverity'] == 2){
	               $iSeverity = "Medium"; 
	            }else if($rs_trouble_ticket[$i]['iSeverity'] == 3){
	               $iSeverity = "High"; 
	            }else if($rs_trouble_ticket[$i]['iSeverity'] == 4){
	               $iSeverity = "Critical"; 
	            }

	            $iStatus = '---';
	            if($rs_trouble_ticket[$i]['iStatus'] == 1){
	               $iStatus = "Not Started"; 
	            }else if($rs_trouble_ticket[$i]['iStatus'] == 2){
	               $iStatus = "In Progress"; 
	            }else if($rs_trouble_ticket[$i]['iStatus'] == 3){
	               $iStatus = "Completed"; 
	            }

	            $vServiceDetails = '';
	            if($rs_trouble_ticket[$i]['iServiceOrderId'] != ""){
	                
	                $vServiceDetails .= $rs_trouble_ticket[$i]['vMasterMSA']." | ".$rs_trouble_ticket[$i]['vServiceOrder'];
	            }

	    		$notification_arr[] = array('type' => 'TroubleTicket' ,'iTroubleTicketId' => $rs_trouble_ticket[$i]['iTroubleTicketId'], 'dDate' => $rs_trouble_ticket[$i]['dAddedDate'], 'title' => "Trouble Ticket #".$rs_trouble_ticket[$i]['iTroubleTicketId'].": ".date_display_report_date($rs_trouble_ticket[$i]['dAddedDate'])." | ".$vServiceDetails." | ".$iSeverity." | ".$iStatus);
	    	}
	    }
	    // ************ Trouble Ticket ************ //

	    // ************ Maintenance Ticket ************ //
	    $MaintenanceTicketObj->clear_variable();
	    $where_arr = array();
        $join_fieds_arr = array();
        $join_arr = array();

	    $join_fieds_arr[] = 'so."vMasterMSA"';
	    $join_fieds_arr[] = 'so."vServiceOrder"';
	    
	    $join_arr[] = 'LEFT JOIN service_order so on so."iServiceOrderId" = maintenance_ticket."iServiceOrderId"';
	    $join_arr[] = 'LEFT JOIN company_mas cm on cm."iCompanyId" = so."iCarrierID"';

	    $where_arr[] = "maintenance_ticket.\"iAssignedToId\" = '".$userid."'"; 
        $where_arr[] = "maintenance_ticket.\"iStatus\" != 3"; // Completed 

	    $MaintenanceTicketObj->join_field = $join_fieds_arr;
	    $MaintenanceTicketObj->join = $join_arr;
	    $MaintenanceTicketObj->where = $where_arr;
	    $MaintenanceTicketObj->param['order_by'] = "maintenance_ticket.\"iMaintenanceTicketId\" DESC";
	    $MaintenanceTicketObj->param['limit'] = $limit;
	    $MaintenanceTicketObj->setClause();
	    $MaintenanceTicketObj->debug_query = false;
	    $rs_maintenance_ticket = $MaintenanceTicketObj->recordset_list();
	    $ti = count($rs_maintenance_ticket);
	    if($ti > 0) {
	    	for($i=0; $i<$ti; $i++){
	    		$iSeverity = '---';
	            if($rs_maintenance_ticket[$i]['iSeverity'] == 1){
	               $iSeverity = "Low"; 
	            }else if($rs_maintenance_ticket[$i]['iSeverity'] == 2){
	               $iSeverity = "Medium"; 
	            }else if($rs_maintenance_ticket[$i]['iSeverity'] == 3){
	               $iSeverity = "High"; 
	            }else if($rs_maintenance_ticket[$i]['iSeverity'] == 4){
	               $iSeverity = "Critical"; 
	            }

	            $iStatus = '---';
	            if($rs_maintenance_ticket[$i]['iStatus'] == 1){
	               $iStatus = "Not Started"; 
	            }else if($rs_maintenance_ticket[$i]['iStatus'] == 2){
	               $iStatus = "In Progress"; 
	            }else if($rs_maintenance_ticket[$i]['iStatus'] == 3){
	               $iStatus = "Completed"; 
	            }

	            $vServiceDetails = '';
	            if($rs_maintenance_ticket[$i]['iServiceOrderId'] != ""){
	                
	                $vServiceDetails .= $rs_maintenance_ticket[$i]['vMasterMSA']." | ".$rs_maintenance_ticket[$i]['vServiceOrder'];
	            }

	    		$notification_arr[] = array('type' => 'MaintenanceTicket' ,'iMaintenanceTicketId' => $rs_maintenance_ticket[$i]['iMaintenanceTicketId'], 'dDate' => $rs_maintenance_ticket[$i]['dAddedDate'], 'title' => "Maintenance Ticket #".$rs_maintenance_ticket[$i]['iMaintenanceTicketId'].": ".date_display_report_date($rs_maintenance_ticket[$i]['dAddedDate'])." | ".$vServiceDetails." | ".$iSeverity." | ".$iStatus);
	    	}
	    }
	    // ************ Maintenance Ticket ************ //
	}

	foreach ($notification_arr as $key => $part) {
       	$sort[$key] = strtotime($part['dDate']);
	}
	array_multisort($sort, SORT_DESC, $notification_arr);

	$rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => array('notification_arr' => $notification_arr));
}else {
	$r = HTTPStatus(400);
	$code = 1001;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 400, "Message" => $message);
}
?>