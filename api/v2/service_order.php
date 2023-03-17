<?php
include_once($controller_path . "service_order.inc.php");
include_once($controller_path . "user.inc.php");
$ServiceOrderObj = new ServiceOrder();
$UserObj = new User();
if($request_type == "service_order_list"){
    $ServiceOrderObj->clear_variable();
    $where_arr = array();
    if(!empty($RES_PARA)){

        $vNetwork               = $RES_PARA['vNetwork'];
        $vCarrier               = $RES_PARA['vCarrier'];
        $vConnectionType        = $RES_PARA['vConnectionType'];
        $vServiceType           = $RES_PARA['vServiceType'];
        $iSOStatus              = $RES_PARA['iSOStatus'];
        $iCStatus               = $RES_PARA['iCStatus'];
        $iSStatus               = $RES_PARA['iSStatus'];

        $page_length            = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start                  = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho                  = $RES_PARA['sEcho'];
        $display_order          = $RES_PARA['display_order'];
        $dir                    = $RES_PARA['dir'];
        $order_by               = $RES_PARA['order_by'];  
        $iFieldmapPremiseId     = $RES_PARA['iFieldmapPremiseId'];    

        $vSContactNameDD        = $RES_PARA['vSContactNameDD'];
        $vSContactName          = $RES_PARA['vSContactName'];
        $vSAddressFilterOpDD    = $RES_PARA['vSAddressFilterOpDD'];
        $vSAddress              = $RES_PARA['vSAddress'];
        $vSvSCityFilterOpDD     = $RES_PARA['vSvSCityFilterOpDD'];
        $vSCity                 = $RES_PARA['vSCity'];
        $vvSStateFilterOpDD     = $RES_PARA['vvSStateFilterOpDD'];
        $vSState                = $RES_PARA['vSState'];
        $vSZipCode              = $RES_PARA['vSZipCode'];
        $iSZoneId               = $RES_PARA['iSZoneId'];
        $iServiceOrderId        = $RES_PARA['iServiceOrderId'];
        $vMasterMSA             = $RES_PARA['vMasterMSA'];
        $vSSalesRepNameDD       = $RES_PARA['vSSalesRepNameDD'];
        $vSSalesRepName         = $RES_PARA['vSSalesRepName'];
        $vSSalesRepEmailDD      = $RES_PARA['vSSalesRepEmailDD'];
        $vSSalesRepEmail        = $RES_PARA['vSSalesRepEmail'];
        $vServiceOrder          = $RES_PARA['vServiceOrder'];   
        $sess_iCompanyId        = $RES_PARA['sess_iCompanyId'];   
        $A2D_COMPANY_ID         = $RES_PARA['A2D_COMPANY_ID'];   
    }

    if ($iFieldmapPremiseId != "") {
        $where_arr[] = 'service_order."iPremiseId"='.$iFieldmapPremiseId ;
    }

    if ($iServiceOrderId != "") {
        $where_arr[] = 'service_order."iServiceOrderId"='.$iServiceOrderId ;
    }

    if ($vMasterMSA != "") {
        $where_arr[] = "service_order.\"vMasterMSA\" = '".$vMasterMSA."'";
    }

    if ($vServiceOrder != "") {
        $where_arr[] = "service_order.\"vServiceOrder\" = '".$vServiceOrder."'";
    }

    if ($vNetwork != "") {
        $where_arr[] = "n.\"iNetworkId\" = '".$vNetwork."'";
    }

    if ($vCarrier != "") {
        $where_arr[] = "service_order.\"iCarrierID\" = '".$vCarrier."'";
    }

    if ($vServiceType != "") {
        $where_arr[] = "(service_order.\"iService1\" = '".$vServiceType."')";
    }

    if ($vConnectionType != "") {
            $where_arr[] = "(service_order.\"iConnectionTypeId\" = '".$vConnectionType."')";
        }

    if ($iSOStatus != "") {
            $where_arr[] = "(service_order.\"iSOStatus\" = '".$iSOStatus."')";
        }

    if ($iCStatus != "") {
            $where_arr[] = "(service_order.\"iCStatus\" = '".$iCStatus."')";
        }

    if ($iSStatus != "") {
            $where_arr[] = "(service_order.\"iSStatus\" = '".$iSStatus."')";
        }

    if ($vSContactName != "") {
        if ($vSContactNameDD != "") {
            if ($vSContactNameDD == "Begins") {
                $where_arr[] = ' CONCAT(contact_mas."vFirstName", \' \', contact_mas."vLastName") ILIKE \'' . trim($vSContactName) . '%\'';
            } else if ($vSContactNameDD == "Ends") {
                $where_arr[] = ' CONCAT(contact_mas."vFirstName", \' \', contact_mas."vLastName") ILIKE \'%' . trim($vSContactName) . '\'';
            } else if ($vSContactNameDD == "Contains") {
                $where_arr[] = ' CONCAT(contact_mas."vFirstName", \' \', contact_mas."vLastName") ILIKE \'%' . trim($vSContactName) . '%\'';
            } else if ($vSContactNameDD == "Exactly") {
                $where_arr[] =  ' CONCAT(contact_mas."vFirstName", \' \', contact_mas."vLastName")  = \'' . trim($vSContactName) . '\'';
            }
        } else {
            $where_arr[] = ' CONCAT(contact_mas."vFirstName", \' \', contact_mas."vLastName")  ILIKE \'' . trim($vSContactName) . '%\'';
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

    if ($vSSalesRepName != "") {
        if ($vNameDD != "") {
            if ($vSSalesRepNameDD == "Begins") {
                $where_arr[] = "user_mas.\"vFirstName\" LIKE '".$vSSalesRepName."%'";
            } else if ($vSSalesRepNameDD == "Ends") {
                $where_arr[] = "user_mas.\"vLastName\" LIKE '%".$vSSalesRepName."'";
            } else if ($vSSalesRepNameDD == "Contains") {
                $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") LIKE '%".$vSSalesRepName."%'";
            } else if ($vSSalesRepNameDD == "Exactly") {
                $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") = '".$vSSalesRepName."'";
            }
        } else {
            $where_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\") LIKE '".$vSSalesRepName."%'";
        }
    }

    if ($vSSalesRepEmail != "") {
        if ($vSSalesRepEmailDD != "") {
            if ($vSSalesRepEmailDD == "Begins") {
                $where_arr[] = 'service_order."vSalesRepEmail" ILIKE \''.trim($vSSalesRepEmail).'%\'';
            } else if ($vSSalesRepEmailDD == "Ends") {
                $where_arr[] = 'service_order."vSalesRepEmail" ILIKE \'%'.trim($vSSalesRepEmail).'\'';
            } else if ($vSSalesRepEmailDD == "Contains") {
                $where_arr[] = 'service_order."vSalesRepEmail" ILIKE \'%'.trim($vSSalesRepEmail).'%\'';
            } else if ($vSSalesRepEmailDD == "Exactly") {
                $where_arr[] = 'service_order."vSalesRepEmail" ILIKE \''.trim($vSSalesRepEmail).'\'';
            }
        } else {
            $where_arr[] = 'service_order."vSalesRepName" ILIKE \''.trim($vSSalesRepEmail).'%\'';
        }
    }

    if($sess_iCompanyId > 0 && $A2D_COMPANY_ID != $sess_iCompanyId){
		$where_arr[] = "service_order.\"iCarrierID\" = '".$sess_iCompanyId."'";
    }

    switch ($display_order) {
        case "1":
            $sortname = "service_order.\"iServiceOrderId\"";
            break;
        case "2":
            $sortname = "service_order.\"vMasterMSA\"";
            break;
        case "3":
            $sortname = "service_order.\"vServiceOrder\"";
            break;
        case "4":
            $sortname = "cm.\"vCompanyName\"";
            break;
        case "5":
            $sortname = "user_mas.\"vFirstName\"";
            break;
        case "6":
            $sortname = "s.\"vName\"";
            break;
        case "7":
            $sortname = "ct.\"vConnectionTypeName\"";
            break;
        default:
            $sortname = "service_order.\"iServiceOrderId\"";
            break;
    }

    

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = 's."vName" as "vPremiseName"';
    $join_fieds_arr[] = 's."vAddress1"';
	$join_fieds_arr[] = 'st."vTypeName" as "vPremiseType"';
    $join_fieds_arr[] = 'cm."vCompanyName"';
    $join_fieds_arr[] = 'z."vZoneName"';
    $join_fieds_arr[] = 'n."vName" as "vNetwork"';
    $join_fieds_arr[] = 'ct."vConnectionTypeName"';
    $join_fieds_arr[] = 'st1."vServiceType" as "vServiceType1"';
    $join_fieds_arr[] = "concat(user_mas.\"vFirstName\", ' ', user_mas.\"vLastName\" ) as \"vSalesRepName\"";
    
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN user_mas on service_order."iSalesRepId" = user_mas."iUserId"';
    $join_arr[] = 'LEFT JOIN premise_mas s on service_order."iPremiseId" = s."iPremiseId"';
    $join_arr[] = 'LEFT JOIN zipcode_mas on s."iZipcode" = zipcode_mas."iZipcode"';
    if($vSContactName != '') {
        $join_arr[] = 'LEFT JOIN site_contact sc on s."iPremiseId" = sc."iPremiseId"';
        $join_arr[] = 'LEFT JOIN contact_mas on sc."iCId" = contact_mas."iCId"';
    }
    $join_arr[] = 'LEFT JOIN zone z on s."iZoneId" = z."iZoneId"';
    $join_arr[] = 'LEFT JOIN city_mas c on s."iCityId" = c."iCityId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN network n on z."iNetworkId" = n."iNetworkId"';
	$join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $join_arr[] = 'LEFT JOIN company_mas cm on service_order."iCarrierID" = cm."iCompanyId"';
    $join_arr[] = 'LEFT JOIN connection_type_mas ct on service_order."iConnectionTypeId" = ct."iConnectionTypeId"';
    $join_arr[] = 'LEFT JOIN service_type_mas st1 on service_order."iService1" = st1."iServiceTypeId"';
    $ServiceOrderObj->join_field = $join_fieds_arr;
    $ServiceOrderObj->join = $join_arr;
    $ServiceOrderObj->where = $where_arr;
    if($vSContactName != '') {
        $ServiceOrderObj->param['group_by'] = 'sc."iCId", sc."iPremiseId"';
    }
    $ServiceOrderObj->param['order_by'] = $sortname . " " . $dir;
    $ServiceOrderObj->param['limit'] = $limit;
    $ServiceOrderObj->setClause();
    $ServiceOrderObj->debug_query = false;
    $rs_sorder = $ServiceOrderObj->recordset_list();

    // Paging Total Records
    $total = $ServiceOrderObj->recordset_total();
    // Paging Total Records

    $data = array();
    $ni = count($rs_sorder);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            
            $data[] = array(
                "iServiceOrderId"       => $rs_sorder[$i]['iServiceOrderId'],
                "vMasterMSA"            => $rs_sorder[$i]['vMasterMSA'],
                "vServiceOrder"         => $rs_sorder[$i]['vServiceOrder'],
                "iCarrierID"            => $rs_sorder[$i]['iCarrierID'],
                "vCompanyName"          => $rs_sorder[$i]['vCompanyName'],
                "vSalesRepName"         => $rs_sorder[$i]['vSalesRepName'],
                "vSalesRepEmail"        => $rs_sorder[$i]['vSalesRepEmail'],
                "iPremiseId"            => $rs_sorder[$i]['iPremiseId'],
                "vPremiseName"          => $rs_sorder[$i]['vPremiseName'],
                "vPremiseType"          => $rs_sorder[$i]['vPremiseType'],
                "iConnectionTypeId"     => $rs_sorder[$i]['iConnectionTypeId'],
                "vConnectionTypeName"   => $rs_sorder[$i]['vConnectionTypeName'],
                "iService1"             => $rs_sorder[$i]['iService1'],
                "iService2"             => $rs_sorder[$i]['iService2'],
                "iService3"             => $rs_sorder[$i]['iService3'],
                "vServiceType1"         => $rs_sorder[$i]['vServiceType1'],
                "tComments"             => $rs_sorder[$i]['tComments'],
                "iSOStatus"             => $rs_sorder[$i]['iSOStatus'],
                "iCStatus"              => $rs_sorder[$i]['iCStatus'],
                "iSStatus"              => $rs_sorder[$i]['iSStatus'],
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "service_order_edit"){
	$ServiceOrderObj->clear_variable();
   	$update_arr = array(
        "iServiceOrderId"       => $RES_PARA['iServiceOrderId'],
        "vMasterMSA"            => $RES_PARA['vMasterMSA'],
        "vNameId"               => $RES_PARA['vNameId'],
        "vServiceOrder"         => $RES_PARA['vServiceOrder'],
        "iCarrierID"            => $RES_PARA['iCarrierID'],
        "iSalesRepId"           => $RES_PARA['iSalesRepId'],
        "vSalesRepEmail"        => $RES_PARA['vSalesRepEmail'],
        "iPremiseId"            => $RES_PARA['iPremiseId'],
        "iConnectionTypeId"     => $RES_PARA['iConnectionTypeId'],
        "iService1"             => $RES_PARA['iService1'],
        "iOldSOStatus"          => $RES_PARA['iOldSOStatus'],
        "iSOStatus"             => $RES_PARA['iSOStatus'],
        "iCStatus"              => $RES_PARA['iCStatus'],
        "iSStatus"              => $RES_PARA['iSStatus'],
        "iNRCVariable"          => $RES_PARA['iNRCVariable'],
        "iMRCFixed"             => $RES_PARA['iMRCFixed'],
        "vServiceOrder"         => $RES_PARA['vServiceOrder'],
        "tComments"             => $RES_PARA['tComments'],
        "vFile"                 => $RES_PARA['vFile'],
        "iUserModifiedBy"       => $RES_PARA['iUserModifiedBy'],
    );

   $ServiceOrderObj->update_arr = $update_arr;
   $ServiceOrderObj->setClause();
   $rs_db = $ServiceOrderObj->update_records();
   if($rs_db){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iServiceOrderId" => $RES_PARA['iServiceOrderId']);
   }else{
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
   }
}else if($request_type == "service_order_delete"){
   	$iServiceOrderId = $RES_PARA['iServiceOrderId'];
	$ServiceOrderObj->clear_variable();
    $rs_db = $ServiceOrderObj->delete_records($iServiceOrderId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iServiceOrderId" => $iServiceOrderId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "service_order_add") {
    $ServiceOrderObj->clear_variable();
    $insert_arr = array(
        "vMasterMSA"            => $RES_PARA['vMasterMSA'],
        "vNameId"               => $RES_PARA['vNameId'],
        "iCarrierID"            => $RES_PARA['iCarrierID'],
        "iSalesRepId"           => $RES_PARA['iSalesRepId'],
        "vSalesRepEmail"        => $RES_PARA['vSalesRepEmail'],
        "iPremiseId"            => $RES_PARA['iPremiseId'],
        "iConnectionTypeId"     => $RES_PARA['iConnectionTypeId'],
        "iService1"             => $RES_PARA['iService1'],
        "iSOStatus"             => $RES_PARA['iSOStatus'],
        "iCStatus"              => $RES_PARA['iCStatus'],
        "iSStatus"              => $RES_PARA['iSStatus'],
        "iNRCVariable"          => $RES_PARA['iNRCVariable'],
        "iMRCFixed"             => $RES_PARA['iMRCFixed'],
        "vServiceOrder"         => $RES_PARA['vServiceOrder'],
        "tComments"             => $RES_PARA['tComments'],
        "vFile"                 => $RES_PARA['vFile'],
        "iUserCreatedBy"		=> $RES_PARA['iUserCreatedBy'],
    );

    $ServiceOrderObj->insert_arr = $insert_arr;
    $ServiceOrderObj->setClause();
    $iServiceOrderId = $ServiceOrderObj->add_records();
    if($iServiceOrderId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iServiceOrderId" => $iServiceOrderId);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "search_service_order"){
    $rs_arr  = array();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();
    $vServiceOrder = $RES_PARA['vServiceOrder'];
     
    $ServiceOrderObj->clear_variable();

    $letters = str_replace("'","",$vSiteName_other);
    $exp_keyword = explode("\\",$letters);
  
    $ext_where_arr =array();
    foreach($exp_keyword as $vl){
        if(trim($vl) != '')
            $ext_where_arr[] = " (service_order.\"vMasterMSA\" ILIKE '%".trim($vl)."%' OR service_order.\"vServiceOrder\" ILIKE '%".trim($vl)."%'' OR concat(s.\"vAddress1\", ' ', s.\"vStreet\") ILIKE '%".trim($vl)."%') ";
    }
    if(count($ext_where_arr) > 0){
        $ext_where = implode(" AND ", $ext_where_arr);
        $where_arr[] = $ext_where;
    }else{
        $where_arr[] = " (service_order.\"iServiceOrderId\" = '".intval($vServiceOrder)."' OR service_order.\"vMasterMSA\" ILIKE '%".trim($vServiceOrder)."%' OR service_order.\"vServiceOrder\" ILIKE '%".trim($vServiceOrder)."%' OR concat(s.\"vAddress1\", ' ', s.\"vStreet\") ILIKE '%".trim($vServiceOrder)."%') ";
    } 
    $join_fieds_arr[] = "concat(s.\"vAddress1\", ' ', s.\"vStreet\") as \"vPremiseAddress\"";
    $join_arr[] = 'LEFT JOIN premise_mas s on s."iPremiseId" = service_order."iPremiseId"';

    $ServiceOrderObj->join_field = $join_fieds_arr;
    $ServiceOrderObj->join = $join_arr;
    $ServiceOrderObj->where = $where_arr;
    $ServiceOrderObj->param['limit'] = "0";
    $ServiceOrderObj->param['order_by'] = 'service_order."iServiceOrderId" DESC';
    
    $ServiceOrderObj->setClause();
    $rs_so = $ServiceOrderObj->recordset_list();
    for ($i = 0; $i < count($rs_so); $i++) {
        $rs_arr[] = array(
            'display' =>"ID#". $rs_so[$i]['iServiceOrderId']." | ".$rs_so[$i]['vMasterMSA']." | ".$rs_so[$i]['vServiceOrder'],
            "iServiceOrderId" => $rs_so[$i]['iServiceOrderId'],
            "vMasterMSA" => $rs_so[$i]['vMasterMSA'],
            "vServiceOrder" => $rs_so[$i]['vServiceOrder']
        );
    }

    $result = array('data' => $rs_arr);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "service_order_change_status"){
    $status_field = $RES_PARA['status_field'];
    $status = $RES_PARA['status'];
    $iServiceOrderIds = $RES_PARA['iServiceOrderIds'];
    $iUserId = $RES_PARA['iUserId'];
    $rs_db = $ServiceOrderObj->change_status($iServiceOrderIds, $status, $status_field, $iUserId);
    if($rs_db) {
        $response_data = array("Code" => 200, "Message" => "Status Changed Successfully.", "error" => 0, "iServiceOrderId" => $iServiceOrderIds);
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