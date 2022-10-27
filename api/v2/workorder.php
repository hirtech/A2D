<?php
include_once($controller_path . "workorder.inc.php");
$WorkOrderObj = new WorkOrder();
if($request_type == "workorder_list"){
    $WorkOrderObj->clear_variable();
    $where_arr = array();
    if(!empty($RES_PARA)){
        $iWOTId                 = $RES_PARA['iWOTId'];
        $iPremiseId             = $RES_PARA['iPremiseId'];
        $iServiceOrderId        = $RES_PARA['iServiceOrderId'];
        $vStatus                = $RES_PARA['vStatus'];

        $page_length            = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start                  = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho                  = $RES_PARA['sEcho'];
        $display_order          = $RES_PARA['display_order'];
        $dir                    = $RES_PARA['dir'];  


        $vSPremiseNameDD        = $RES_PARA['vSPremiseNameDD'];
        $vSPremiseName          = $RES_PARA['vSPremiseName'];
        $vSAddressFilterOpDD    = $RES_PARA['vSAddressFilterOpDD'];
        $vSAddress              = $RES_PARA['vSAddress'];
        $vSCityFilterOpDD       = $RES_PARA['vSCityFilterOpDD'];
        $vSCity                 = $RES_PARA['vSCity'];
        $vSStateFilterOpDD      = $RES_PARA['vSStateFilterOpDD'];
        $vSState                = $RES_PARA['vSState'];
        $vSZipCode              = $RES_PARA['vSZipCode'];
        $iSZoneId               = $RES_PARA['iSZoneId'];
        $iSServiceOrderId       = $RES_PARA['iSServiceOrderId'];
        $vSWOProjectDD          = $RES_PARA['vSWOProjectDD'];
        $vSWOProject            = $RES_PARA['vSWOProject'];
        $iSRequestorId          = $RES_PARA['iSRequestorId'];
        $iSAssignedToId         = $RES_PARA['iSAssignedToId'];
        $iSWOSId                = $RES_PARA['iSWOSId'];
        $iFieldmapPremiseId     = $RES_PARA['iFieldmapPremiseId'];
 
    }

    if ($iWOTId != "") {
        $where_arr[] = 'workorder."iWOTId"='.$iWOTId ;
    }

    if ($iPremiseId != "") {
        $where_arr[] = 'workorder."iPremiseId"='.$iPremiseId ;
    }

    if ($iServiceOrderId != "") {
        $where_arr[] = 'workorder."iServiceOrderId"='.$iServiceOrderId ;
    }
    
    if ($vStatus != "") {
        $where_arr[] = "ws.\"vStatus\" ILIKE '%".$vStatus."%'" ;
    }

    if ($vSPremiseName != "") {
        if ($vSPremiseNameDD != "") {
            if ($vSPremiseNameDD == "Begins") {
                $where_arr[] = 's."vName" ILIKE \''.trim($vSPremiseName).'%\'';
            } else if ($vSPremiseNameDD == "Ends") {
                $where_arr[] = 's."vName" ILIKE \'%'.trim($vSPremiseName).'\'';
            } else if ($vSPremiseNameDD == "Contains") {
                $where_arr[] = 's."vName" ILIKE \'%'.trim($vSPremiseName).'%\'';
            } else if ($vSPremiseNameDD == "Exactly") {
                $where_arr[] = 's."vName" ILIKE \''.trim($vSPremiseName).'\'';
            }
        } else {
            $where_arr[] = 's."vName" ILIKE \''.trim($vSPremiseName).'%\'';
        }
    }

    if ($vSAddress != "") {
        if ($vSAddressFilterOpDD != "") {
            if ($vSAddressFilterOpDD == "Begins") {
                $where_arr[] = "s.\"vAddress1\" ILIKE '".trim($vSAddress)."%'";
            } else if ($vSAddressFilterOpDD == "Ends") {
                $where_arr[] = "s.\"vStreet\" ILIKE '%".trim($vSAddress)."'";
            } else if ($vSAddressFilterOpDD == "Contains") {
                $where_arr[] = "concat(s.\"vAddress1\", ' ', s.\"vStreet\") ILIKE '%".trim($vSAddress)."%'";
            } else if ($vSAddressFilterOpDD == "Exactly") {
                $where_arr[] = "concat(s.\"vAddress1\", ' ', s.\"vStreet\") ILIKE '".trim($vSAddress)."'";
            }
        } else {
            $where_arr[] = "concat(s.\"vAddress1\", ' ', s.\"vStreet\") ILIKE '".trim($vSAddress)."%'";
        }
    }

    if ($vSCity != "") {
        if ($vSCityFilterOpDD != "") {
            if ($vSCityFilterOpDD == "Begins") {
                $where_arr[] = 'c."vCity" ILIKE \''.trim($vSCity).'%\'';
            } else if ($vSCityFilterOpDD == "Ends") {
                $where_arr[] = 'c."vCity" ILIKE \'%'.trim($vSCity).'\'';
            } else if ($vSCityFilterOpDD == "Contains") {
                $where_arr[] = 'c."vCity" ILIKE \'%'.trim($vSCity).'%\'';
            } else if ($vSCityFilterOpDD == "Exactly") {
                $where_arr[] = 'c."vCity" ILIKE \''.trim($vSCity).'\'';
            }
        } else {
            $where_arr[] = 'c."vCity" ILIKE \''.trim($vSCity).'%\'';
        }
    }

    if ($vSState != "") {
        if ($vSStateFilterOpDD != "") {
            if ($vSStateFilterOpDD == "Begins") {
                $where_arr[] = 'sm."vState" ILIKE \''.trim($vSState).'%\'';
            } else if ($vSStateFilterOpDD == "Ends") {
                $where_arr[] = 'sm."vState" ILIKE \'%'.trim($vSState).'\'';
            } else if ($vSStateFilterOpDD == "Contains") {
                $where_arr[] = 'sm."vState" ILIKE \'%'.trim($vSState).'%\'';
            } else if ($vSStateFilterOpDD == "Exactly") {
                $where_arr[] = 'sm."vState" ILIKE \''.trim($vSState).'\'';
            }
        } else {
            $where_arr[] = 'sm."vState" ILIKE \''.trim($vSState).'%\'';
        }
    }

    if ($vSZipCode != "") {
        $where_arr[] = "zipcode_mas.\"vZipcode\" = '".$vSZipCode."'";
    }

    if ($iSZoneId != "") {
        $where_arr[] = "z.\"iZoneId\" = '".$iSZoneId."'";
    }

    if ($iSRequestorId != "") {
        $where_arr[] = "workorder.\"iRequestorId\" = '".$iSRequestorId."'";
    }

    if ($iSAssignedToId != "") {
        $where_arr[] = "workorder.\"iAssignedToId\" = '".$iSAssignedToId."'";
    }

    if ($vSWOProject != "") {
        if ($vSWOProjectDD != "") {
            if ($vSWOProjectDD == "Begins") {
                $where_arr[] = 'workorder."vWOProject" ILIKE \''.$vSWOProject.'%\'';
            } else if ($vSWOProjectDD == "Ends") {
                $where_arr[] = 'workorder."vWOProject" ILIKE \'%'.$vSWOProject.'\'';
            } else if ($vSWOProjectDD == "Contains") {
                $where_arr[] = 'workorder."vWOProject" ILIKE \'%'.$vSWOProject.'%\'';
            } else if ($vSWOProjectDD == "Exactly") {
                $where_arr[] = 'workorder."vWOProject" ILIKE \''.$vSWOProject.'\'';
            }
        } else {
            $where_arr[] = 'workorder."vWOProject" ILIKE \''.$vSWOProject.'%\'';
        }
    }

    if ($iSWOSId != "") {
        $where_arr[] = "workorder.\"iWOSId\" = '".$iSWOSId."'";
    }

    if ($iFieldmapPremiseId != "") {
        $where_arr[] = 'workorder."iPremiseId"='.$iFieldmapPremiseId ;
    }

    switch ($display_order) {
        case "0":
            $sortname = "workorder.\"iWOId\"";
            break;
        case "1":
            $sortname = "s.\"iSiteId\"";
            break;
        case "2":
            $sortname = "workorder.\"iServiceOrderId\"";
            break;
        case "3":
            $sortname = "concat(u.\"vFirstName\", ' ', u.\"vLastName\")";
            break;
        case "4":
            $sortname = "workorder.\"vWOProject\"";
            break;
        case "5":
            $sortname = "wt.\"vType\"";
            break;
        case "6":
            $sortname = "concat(u1.\"vFirstName\", ' ', u1.\"vLastName\")";
            break;
        case "7":
            $sortname = "ws.\"vStatus\"";
            break;
        default:
            $sortname = "workorder.\"iWOId\"";
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_arr = array();
    $join_fieds_arr[] = 's."vName" as "vPremiseName"';
    $join_fieds_arr[] = 's."vAddress1"';
    $join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
    $join_fieds_arr[] = 'z."vZoneName"';
    $join_fieds_arr[] = 'so."vMasterMSA"';
    $join_fieds_arr[] = 'so."vServiceOrder"';
    $join_fieds_arr[] = 'ws."vStatus"';
    $join_fieds_arr[] = 'wt."vType"';
    $join_fieds_arr[] = "concat(u.\"vFirstName\", ' ', u.\"vLastName\") as \"vRequestor\"";
    $join_fieds_arr[] = "concat(u1.\"vFirstName\", ' ', u1.\"vLastName\") as \"vAssignedTo\"";
    $join_arr[] = 'LEFT JOIN site_mas s on workorder."iPremiseId" = s."iSiteId"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $join_arr[] = 'LEFT JOIN zipcode_mas on s."iZipcode" = zipcode_mas."iZipcode"';
    $join_arr[] = 'LEFT JOIN zone z on s."iZoneId" = z."iZoneId"';
    $join_arr[] = 'LEFT JOIN city_mas c on s."iCityId" = c."iCityId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN service_order so on workorder."iServiceOrderId" = so."iServiceOrderId"';
    $join_arr[] = 'LEFT JOIN user_mas u on workorder."iRequestorId" = u."iUserId"';
    $join_arr[] = 'LEFT JOIN user_mas u1 on workorder."iAssignedToId" = u1."iUserId"';
    $join_arr[] = 'LEFT JOIN workorder_status_mas ws on workorder."iWOSId" = ws."iWOSId"';
    $join_arr[] = 'LEFT JOIN workorder_type_mas wt on workorder."iWOTId" = wt."iWOTId"';
    $WorkOrderObj->join_field = $join_fieds_arr;
    $WorkOrderObj->join = $join_arr;
    $WorkOrderObj->where = $where_arr;
    $WorkOrderObj->param['order_by'] = $sortname . " " . $dir;
    $WorkOrderObj->param['limit'] = $limit;
    $WorkOrderObj->setClause();
    $WorkOrderObj->debug_query = false;
    $rs_sorder = $WorkOrderObj->recordset_list();

    // Paging Total Records
    $total = $WorkOrderObj->recordset_total();
    // Paging Total Records

    $data = array();
    $ni = count($rs_sorder);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            
            $data[] = array(
                "iWOId"					=> $rs_sorder[$i]['iWOId'],
				"iPremiseId"            => $rs_sorder[$i]['iPremiseId'],
				"iServiceOrderId"       => $rs_sorder[$i]['iServiceOrderId'],
				"iRequestorId"			=> $rs_sorder[$i]['iRequestorId'],
				"vWOProject"            => $rs_sorder[$i]['vWOProject'],
				"iWOTId"				=> $rs_sorder[$i]['iWOTId'],
				"tDescription"			=> $rs_sorder[$i]['tDescription'],
				"iAssignedToId"         => $rs_sorder[$i]['iAssignedToId'],
				"iWOSId"				=> $rs_sorder[$i]['iWOSId'],
				"vPremiseName"			=> $rs_sorder[$i]['vPremiseName'],
                "vPremiseType"          => $rs_sorder[$i]['vPremiseType'],
				"vMasterMSA"			=> $rs_sorder[$i]['vMasterMSA'],
				"vServiceOrder"		    => $rs_sorder[$i]['vServiceOrder'],
				"vRequestor"			=> $rs_sorder[$i]['vRequestor'],
                "vAssignedTo"           => $rs_sorder[$i]['vAssignedTo'],
				"vStatus"				=> $rs_sorder[$i]['vStatus'],
                "vType"                 => $rs_sorder[$i]['vType'],
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "workorder_edit"){
	$WorkOrderObj->clear_variable();
   	$update_arr = array(
        "iWOId"					=> $RES_PARA['iWOId'],
        "iPremiseId"            => $RES_PARA['iPremiseId'],
        "iServiceOrderId"       => $RES_PARA['iServiceOrderId'],
		"iRequestorId"			=> $RES_PARA['iRequestorId'],
        "vWOProject"            => $RES_PARA['vWOProject'],
        "iWOTId"				=> $RES_PARA['iWOTId'],
        "tDescription"			=> $RES_PARA['tDescription'],
        "iAssignedToId"         => $RES_PARA['iAssignedToId'],
        "iWOSId"				=> $RES_PARA['iWOSId']
    );

   $WorkOrderObj->update_arr = $update_arr;
   $WorkOrderObj->setClause();
   $rs_db = $WorkOrderObj->update_records();
   if($rs_db){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iWOId" => $RES_PARA['iWOId']);
   }else{
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
   }
}else if($request_type == "workorder_delete"){
   	$iWOId = $RES_PARA['iWOId'];
	$WorkOrderObj->clear_variable();
    $rs_db = $WorkOrderObj->delete_records($iWOId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iWOId" => $iWOId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "workorder_add") {
    $WorkOrderObj->clear_variable();
	
    $insert_arr = array(
        "iPremiseId"            => $RES_PARA['iPremiseId'],
        "iServiceOrderId"       => $RES_PARA['iServiceOrderId'],
        "iRequestorId"			=> $RES_PARA['iRequestorId'],
        "vWOProject"            => $RES_PARA['vWOProject'],
        "iWOTId"				=> $RES_PARA['iWOTId'],
        "tDescription"			=> $RES_PARA['tDescription'],
        "iAssignedToId"         => $RES_PARA['iAssignedToId'],
        "iWOSId"				=> $RES_PARA['iWOSId']
    );

    $WorkOrderObj->insert_arr = $insert_arr;
    $WorkOrderObj->setClause();
    $iWOId = $WorkOrderObj->add_records();
    if($iWOId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iWOId" => $iWOId);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}
else {
	$r = HTTPStatus(400);
	$code = 1001;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 400 , "Message" => $message);
}
?>