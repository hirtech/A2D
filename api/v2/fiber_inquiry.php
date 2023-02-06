<?php
include_once($controller_path . "premise.inc.php");
include_once($controller_path . "fiber_inquiry.inc.php");
include_once($controller_path . "task_awareness.inc.php");
if($request_type == "fiber_inquiry_edit"){
    //$vLatitude = number_format($RES_PARA['vLatitude'], 6, '.', '');
    //$vLongitude = number_format($RES_PARA['vLongitude'], 6, '.', '');
    //$sql_premise = "SELECT s.\"iPremiseId\", s.\"vName\" FROM premise_mas s WHERE  St_Within(ST_GeometryFromText('POINT(".$vLongitude." ".$vLatitude.")', 4326)::geometry, (s.\"vPointLatLong\")::geometry)='t'ORDER BY s.\"iPremiseId\" DESC LIMIT 1";

    $sql_premise = "SELECT s.\"iPremiseId\", s.\"vName\" FROM premise_mas s WHERE s.\"vLongitude\" = '".$RES_PARA['vLongitude']."' AND  s.\"vLatitude\" = '".$RES_PARA['vLatitude']."' ORDER BY s.\"iPremiseId\" DESC LIMIT 1";    
    $rs_premise = $sqlObj->GetAll($sql_premise);
    $iMatchingPremiseId = 0;
    if(!empty($rs_premise)){
        $iMatchingPremiseId = $rs_premise[0]['iPremiseId'];
    }
    //echo "<pre>";print_r($rs_premise);exit;
   	$FiberInquiryObj = new FiberInquiry();
	$FiberInquiryObj->clear_variable();

   	$update_arr = array(
        "sessionId"         	=> $_SESSION["we_api_session_id".$admin_panel_session_suffix],
        "iFiberInquiryId"       => $RES_PARA['iFiberInquiryId'],
        "vAddress1"             => $RES_PARA['vAddress1'],
        "vAddress2"             => $RES_PARA['vAddress2'],
        "vStreet"               => $RES_PARA['vStreet'],
        "vCrossStreet"          => $RES_PARA['vCrossStreet'],
        "iZipcode"              => $RES_PARA['iZipcode'],
        "iStateId"              => $RES_PARA['iStateId'],
        "iCountyId"             => $RES_PARA['iCountyId'],
        "iCityId"               => $RES_PARA['iCityId'],
        "iZoneId"               => $RES_PARA['iZoneId'],
        "vLatitude"             => $RES_PARA['vLatitude'],
        "vLongitude"            => $RES_PARA['vLongitude'],
        "iCId"                  => $RES_PARA['iCId'],
        "iStatus"               => $RES_PARA['iStatus'],
        "iOldStatus"            => $RES_PARA['iOldStatus'],
        "iPremiseSubTypeId"     => $RES_PARA['iPremiseSubTypeId'],
        "iEngagementId"         => $RES_PARA['iEngagementId'],
        "iLoginUserId"          => $RES_PARA['iLoginUserId'],
        "iMatchingPremiseId"    => $iMatchingPremiseId,
    );

   $FiberInquiryObj->update_arr = $update_arr;
   $FiberInquiryObj->setClause();
   $rs_db = $FiberInquiryObj->update_records();

   if($rs_db){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iFiberInquiryId" => $RES_PARA['iFiberInquiryId'], "iMatchingPremiseId" => $iMatchingPremiseId);
   }else{
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
   }
}else if($request_type == "fiber_inquiry_delete"){
   	//echo "<pre>";print_r($RES_PARA);exit;
   	$iFiberInquiryId = $RES_PARA['iFiberInquiryId'];
   	$FiberInquiryObj = new FiberInquiry();
	$FiberInquiryObj->clear_variable();
    $rs_db = $FiberInquiryObj->delete_records($iFiberInquiryId);
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iFiberInquiryId" => $iFiberInquiryId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "fiber_inquiry_add") {
    //$vLatitude = number_format($RES_PARA['vLatitude'], 6, '.', '');
    //$vLongitude = number_format($RES_PARA['vLongitude'], 6, '.', '');
    //$sql_premise = "SELECT s.\"iPremiseId\", s.\"vName\" FROM premise_mas s WHERE  St_Within(ST_GeometryFromText('POINT(".$vLongitude." ".$vLatitude.")', 4326)::geometry, (s.\"vPointLatLong\")::geometry)='t'ORDER BY s.\"iPremiseId\" DESC LIMIT 1"; 
    $sql_premise = "SELECT s.\"iPremiseId\", s.\"vName\" FROM premise_mas s WHERE s.\"vLongitude\" = '".$RES_PARA['vLongitude']."' AND  s.\"vLatitude\" = '".$RES_PARA['vLatitude']."' ORDER BY s.\"iPremiseId\" DESC LIMIT 1";
    $rs_premise = $sqlObj->GetAll($sql_premise);
    $iMatchingPremiseId = 0;
    if(!empty($rs_premise)){
        $iMatchingPremiseId = $rs_premise[0]['iPremiseId'];
    }       
    $FiberInquiryObj = new FiberInquiry();
    $FiberInquiryObj->clear_variable();
    $insert_arr = array(
        "vAddress1"             => $RES_PARA['vAddress1'],
        "vAddress2"             => $RES_PARA['vAddress2'],
        "vStreet"               => $RES_PARA['vStreet'],
        "vCrossStreet"          => $RES_PARA['vCrossStreet'],
        "iZipcode"              => $RES_PARA['iZipcode'],
        "iStateId"              => $RES_PARA['iStateId'],
        "iCountyId"             => $RES_PARA['iCountyId'],
        "iCityId"               => $RES_PARA['iCityId'],
        "iZoneId"               => $RES_PARA['iZoneId'],
        "vLatitude"             => $RES_PARA['vLatitude'],
        "vLongitude"            => $RES_PARA['vLongitude'],
        "iCId"                  => $RES_PARA['iCId'],
        "iStatus"               => $RES_PARA['iStatus'],
        "iOldStatus"            => $RES_PARA['iOldStatus'],
        "iPremiseSubTypeId"     => $RES_PARA['iPremiseSubTypeId'],
        "iEngagementId"         => $RES_PARA['iEngagementId'],
        "iLoginUserId"          => $RES_PARA['iLoginUserId'],
        "iMatchingPremiseId"    => $iMatchingPremiseId,
    );

    $FiberInquiryObj->insert_arr = $insert_arr;
    $FiberInquiryObj->setClause();
    $rs_db = $FiberInquiryObj->add_records();
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iFiberInquiryId" => $rs_db, "iMatchingPremiseId" => $iMatchingPremiseId);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "fiber_inquiry_list"){

    $FiberInquiryObj = new FiberInquiry();
    $FiberInquiryObj->clear_variable();
    $where_arr = array();

    if(!empty($RES_PARA)){
        $iFiberInquiryId        = $RES_PARA['iFiberInquiryId'];

        $vNetwork               = $RES_PARA['vNetwork'];
        $vFiberZone             = $RES_PARA['vFiberZone'];
        $vStatus                = $RES_PARA['vStatus'];

        $page_length            = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start                  = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho                  = $RES_PARA['sEcho'];
        $display_order          = $RES_PARA['display_order'];
        $dir                    = $RES_PARA['dir'];

        $fiberInquiryId         = $RES_PARA['fiberInquiryId'];
        $contactName            = $RES_PARA['contactName'];
        $contactNameFilterOpDD  = $RES_PARA['contactNameFilterOpDD'];
        $vAddress               = $RES_PARA['vAddress'];
        $AddressFilterOpDD      = $RES_PARA['AddressFilterOpDD'];
        $vCity                  = $RES_PARA['vCity'];
        $CityFilterOpDD         = $RES_PARA['CityFilterOpDD'];
        $vState                 = $RES_PARA['vState'];
        $StateFilterOpDD        = $RES_PARA['StateFilterOpDD'];
        $vCounty                = $RES_PARA['vCounty'];
        $CountyFilterOpDD       = $RES_PARA['CountyFilterOpDD'];
        $zoneName               = $RES_PARA['zoneName'];
        $ZoneNameFilterOpDD     = $RES_PARA['ZoneNameFilterOpDD'];
        $networkName            = $RES_PARA['networkName'];
        $NetworkFilterOpDD      = $RES_PARA['NetworkFilterOpDD'];
    }

    if ($iFiberInquiryId != "") {
        $where_arr[] = 'fiberinquiry_details."iFiberInquiryId"='.$iFiberInquiryId ;
    }
    
    if ($fiberInquiryId != ""){
        $where_arr[] = 'fiberinquiry_details."iFiberInquiryId"='.$fiberInquiryId;
    }

    if ($vNetwork != "") {
        $where_arr[] = "n.\"iNetworkId\" = '".$vNetwork."'";
    }

    if ($vFiberZone != "") {
        $where_arr[] = "z.\"iZoneId\" = '".$vFiberZone."'";
    }

    if ($vStatus != "") {
        $where_arr[] = "fiberinquiry_details.\"iStatus\" = '".$vStatus."'";
    }

    if ($contactName != "") {
        if ($contactNameFilterOpDD != "") {
            if ($contactNameFilterOpDD == "Begins") {
                $where_arr[] = ' CONCAT(contact_mas."vFirstName", \' \', contact_mas."vLastName") ILIKE \'' . trim($contactName) . '%\'';
            } else if ($contactNameFilterOpDD == "Ends") {
                $where_arr[] = ' CONCAT(contact_mas."vFirstName", \' \', contact_mas."vLastName") ILIKE \'%' . trim($contactName) . '\'';
            } else if ($SRNameFilterOpDD == "Contains") {
                $where_arr[] = ' CONCAT(contact_mas."vFirstName", \' \', contact_mas."vLastName") ILIKE \'%' . trim($contactName) . '%\'';
            } else if ($contactNameFilterOpDD == "Exactly") {
                $where_arr[] =  ' CONCAT(contact_mas."vFirstName", \' \', contact_mas."vLastName")  = \'' . trim($contactName) . '\'';
            }
        } else {
            $where_arr[] = ' CONCAT(contact_mas."vFirstName", \' \', contact_mas."vLastName")  ILIKE \'' . trim($contactName) . '%\'';
        }
    }

    if ($vAddress != "") {
        if ($AddressFilterOpDD != "") {
            if ($AddressFilterOpDD == "Begins") {
                $where_arr[] = "concat(fiberinquiry_details.\"vAddress1\", ' ', fiberinquiry_details.\"vStreet\",' ',cm.\"vCity\",' ',sm.\"vState\",' ',c.\"vCounty\") IILIKE '" . trim($vAddress) . "%'";
            } else if ($AddressFilterOpDD == "Ends") {
                $where_arr[] = "concat(fiberinquiry_details.\"vAddress1\", ' ', fiberinquiry_details.\"vStreet\",' ',cm.\"vCity\",' ',sm.\"vState\",' ',c.\"vCounty\") ILIKE '%" . trim($vAddress) . "'";
            } else if ($AddressFilterOpDD == "Contains") {
                $where_arr[] = "concat(fiberinquiry_details.\"vAddress1\", ' ', fiberinquiry_details.\"vStreet\",' ',cm.\"vCity\",' ',sm.\"vState\",' ',c.\"vCounty\") ILIKE '%" . trim($vAddress) . "%'";
            } else if ($AddressFilterOpDD == "Exactly") {
                $where_arr[] = "concat(fiberinquiry_details.\"vAddress1\", ' ', fiberinquiry_details.\"vStreet\",' ',cm.\"vCity\",' ',sm.\"vState\",' ',c.\"vCounty\") = '" . trim($vAddress) . "'";
            }
        } else {
            $where_arr[] = "concat(fiberinquiry_details.\"vAddress1\", ' ', fiberinquiry_details.\"vStreet\",' ',cm.\"vCity\",' ',sm.\"vState\",' ',c.\"vCounty\") ILIKE '" . trim($vAddress) . "%'";
        }
    }

    if ($vCity != "") {
        if ($CityFilterOpDD != "") {
            if ($CityFilterOpDD == "Begins") {
                $where_arr[] = 'cm."vCity" ILIKE \'' . trim($vCity) . '%\'';
            } else if ($CityFilterOpDD == "Ends") {
                $where_arr[] = 'cm."vCity" ILIKE \'%' . trim($vCity) . '\'';
            } else if ($CityFilterOpDD == "Contains") {
                $where_arr[] = 'cm."vCity" ILIKE \'%' . trim($vCity) . '%\'';
            } else if ($CityFilterOpDD == "Exactly") {
                $where_arr[] = 'cm."vCity" = \'' . trim($vCity) . '\'';
            }
        } else {
            $where_arr[] = 'cm."vCity" ILIKE \'' . trim($vCity) . '%\'';
        }
    }

    if ($vState != "") {
        if ($StateFilterOpDD != "") {
            if ($StateFilterOpDD == "Begins") {
                $where_arr[] = 'sm."vState" ILIKE \'' . trim($vState) . '%\'';
            } else if ($StateFilterOpDD == "Ends") {
                $where_arr[] = 'sm."vState" ILIKE \'%' . trim($vState) . '\'';
            } else if ($StateFilterOpDD == "Contains") {
                $where_arr[] = 'sm."vState" ILIKE \'%' . trim($vState) . '%\'';
            } else if ($StateFilterOpDD == "Exactly") {
                $where_arr[] = 'sm."vState" = \'' . trim($vState) . '\'';
            }
        } else {
            $where_arr[] = 'sm."vState" ILIKE \'' . trim($vState) . '%\'';
        }
    }

    if ($vCountry != "") {
        if ($CountryFilterOpDD != "") {
            if ($CountryFilterOpDD == "Begins") {
                $where_arr[] = 'c."vCounty" ILIKE \'' . trim($vCountry) . '%\'';
            } else if ($CountryFilterOpDD == "Ends") {
                $where_arr[] = 'c."vCounty" ILIKE \'%' . trim($vCountry) . '\'';
            } else if ($CountryFilterOpDD == "Contains") {
                $where_arr[] = 'c."vCounty" ILIKE \'%' . trim($vCountry) . '%\'';
            } else if ($CountryFilterOpDD == "Exactly") {
                $where_arr[] = 'c."vCounty" = \'' . trim($vCountry) . '\'';
            }
        } else {
            $where_arr[] = 'c."vCounty" ILIKE \'' . trim($vCountry) . '%\'';
        }
    }

    if ($zoneName != "") {
        if ($ZoneNameFilterOpDD != "") {
            if ($ZoneNameFilterOpDD == "Begins") {
                $where_arr[] = 'z."vZoneName" ILIKE \'' . trim($zoneName) . '%\'';
            } else if ($ZoneNameFilterOpDD == "Ends") {
                $where_arr[] = 'z."vZoneName"  ILIKE \'%' . trim($zoneName) . '\'';
            } else if ($ZoneNameFilterOpDD == "Contains") {
                $where_arr[] = 'z."vZoneName"  ILIKE \'%' . trim($zoneName) . '%\'';
            } else if ($ZoneNameFilterOpDD == "Exactly") {
                $where_arr[] = 'z."vZoneName" = \'' . trim($zoneName) . '\'';
            }
        } else {
            $where_arr[] = 'z."vZoneName" ILIKE \'' . trim($zoneName) . '%\'';
        }
    }

    if ($networkName != "") {
        if ($NetworkFilterOpDD != "") {
            if ($NetworkFilterOpDD == "Begins") {
                $where_arr[] = 'n."vName" ILIKE \''.$networkName.'%\'';
            } else if ($NetworkFilterOpDD == "Ends") {
                $where_arr[] = 'n."vName"  ILIKE \'%'.$networkName.'\'';
            } else if ($NetworkFilterOpDD == "Contains") {
                $where_arr[] = 'n."vName"  ILIKE \'%'.$networkName.'%\'';
            } else if ($NetworkFilterOpDD == "Exactly") {
                $where_arr[] = 'n."vName" = \''.$networkName.'\'';
            }
        } else {
            $where_arr[] = 'n."vName" ILIKE \''.$networkName.'%\'';
        }
    }


    switch ($display_order) {
        case "1":
            $sortname = "fiberinquiry_details.\"iFiberInquiryId\"";
            break;
        case "2":
            $sortname = "\"vContactName\"";
            break;
        case "4":
            $sortname = "cm.\"vCity\"";
            break;
        case "5":
            $sortname = "sm.\"vState\"";
            break;
        case "6":
            $sortname = "c.\"vCounty\"";
            break;
        case "7":
            $sortname = "z.\"vZoneName\"";
            break;
        case "8":
            $sortname = "n.\"vName\"";
            break;
        case "9":
            $sortname = "fiberinquiry_details.\"iStatus\"";
            break;
        default:
            $sortname = "fiberinquiry_details.\"iFiberInquiryId\"";
            break;
        }

        $limit = "LIMIT ".$page_length." OFFSET ".$start."";

        $join_fieds_arr = array();
        $join_fieds_arr[] = "CONCAT(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") AS \"vContactName\"";;
        $join_fieds_arr[] = 'c."vCounty"';
        $join_fieds_arr[] = 'sm."vState"';
        $join_fieds_arr[] = 'cm."vCity"';
        $join_fieds_arr[] = 'z."vZoneName"';
        $join_fieds_arr[] = 'n."vName" as "vNetwork"';

        $join_arr = array();
        $join_arr[] = 'LEFT JOIN contact_mas on fiberinquiry_details."iCId" = contact_mas."iCId"';
        $join_arr[] = 'LEFT JOIN county_mas c on fiberinquiry_details."iCountyId" = c."iCountyId"';
        $join_arr[] = 'LEFT JOIN state_mas sm on fiberinquiry_details."iStateId" = sm."iStateId"';
        $join_arr[] = 'LEFT JOIN city_mas cm on fiberinquiry_details."iCityId" = cm."iCityId"';
        $join_arr[] = 'LEFT JOIN zone z on fiberinquiry_details."iZoneId" = z."iZoneId"';
        $join_arr[] = 'LEFT JOIN network n on z."iNetworkId" = n."iNetworkId"';
        $FiberInquiryObj->join_field = $join_fieds_arr;
        $FiberInquiryObj->join = $join_arr;
        $FiberInquiryObj->where = $where_arr;
        $FiberInquiryObj->param['order_by'] = $sortname . " " . $dir;
        $FiberInquiryObj->param['limit'] = $limit;
        $FiberInquiryObj->setClause();
        $FiberInquiryObj->debug_query = false;
        $rs_sr = $FiberInquiryObj->recordset_list();

        // Paging Total Records
        $total = $FiberInquiryObj->recordset_total();
        // Paging Total Records

        $data = array();
        $ni = count($rs_sr);

        if($ni > 0){
            for($i=0;$i<$ni;$i++){
                $vAddress =  $rs_sr[$i]['vAddress1'].' '.$rs_sr[$i]['vStreet'].' '.$rs_sr[$i]['vCity'].', '.$rs_sr[$i]['vState'].' '.$rs_sr[$i]['vCounty'];

                $vStatus = '';
                if($rs_sr[$i]['iStatus'] == 1){
                    $vStatus = 'Draft';
                }else if($rs_sr[$i]['iStatus'] == 2){
                    $vStatus = 'Assigned';
                }else if($rs_sr[$i]['iStatus'] == 3){
                    $vStatus = 'Review';
                }else if($rs_sr[$i]['iStatus'] == 4){
                    $vStatus = 'Complete';
                }
                $data[] = array(
                    "iFiberInquiryId" => $rs_sr[$i]['iFiberInquiryId'],
                    "vContactName" => $rs_sr[$i]['vContactName'],
                    "vAddress" => $vAddress,
                    "vCity" => $rs_sr[$i]['vCity'],
                    "vState" => $rs_sr[$i]['vState'],
                    "vCounty" => $rs_sr[$i]['vCounty'],
                    "vZoneName" => $rs_sr[$i]['vZoneName'],
                    "vNetwork" => $rs_sr[$i]['vNetwork'],
                    "iStatus" => $rs_sr[$i]['iStatus'],
                    "vStatus" => $vStatus
                );
            }
        }

    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "search_fiber_inquiry"){
    $rs_arr  = array();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();
    $fiberInquiryId = $RES_PARA['srId'];
        
    $join_fieds_arr[] = "CONCAT(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") AS \"vContactName\"";
    $where_arr[] = '(fiberinquiry_details."iFiberInquiryId" = '.$fiberInquiryId.')';
    $join_arr[] = 'LEFT JOIN contact_mas on fiberinquiry_details."iCId" = contact_mas."iCId"';

    $FiberInquiryObj = new FiberInquiry();
    $FiberInquiryObj->clear_variable();
    $FiberInquiryObj->join_field = $join_fieds_arr;
    $FiberInquiryObj->join = $join_arr;
    $FiberInquiryObj->where = $where_arr;
    $FiberInquiryObj->param['limit'] = "0";
    $FiberInquiryObj->param['order_by'] = 'fiberinquiry_details."iFiberInquiryId" DESC';
    $FiberInquiryObj->setClause(); 
    $rs_sr = $FiberInquiryObj->recordset_list();
    for ($i = 0; $i < count($rs_sr); $i++) {
        $rs_arr[] = array(
         'display' => $rs_sr[$i]['iFiberInquiryId']." (".$rs_sr[$i]['vContactName'].")",
         "iFiberInquiryId" => $rs_sr[$i]['iFiberInquiryId'],
        );
    }

    $result = array('data' => $rs_arr);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "nearby_fiber_inquiry"){
    $lat = $RES_PARA['lat'];
    $long = $RES_PARA['long'];
    $meter = $RES_PARA['meter'];
    $entry = array();
    $arr = array();
    $ind = 0;

    if($lat != ""  && $long != "" && $meter != ""){
        $where_arr = array();
        $where_arr[] = 'ST_DWithin(ST_SetSRID(ST_MakePoint(fiberinquiry_details."vLongitude",fiberinquiry_details."vLatitude"), 4326)::geography, ST_MakePoint(' . $long . ', ' . $lat . ')::geography, '.$meter.') ';
        $FiberInquiryObj = new FiberInquiry();
        $FiberInquiryObj->where = $where_arr;
        $FiberInquiryObj->param['order_by'] = "fiberinquiry_details.\"iFiberInquiryId\" DESC";
        $FiberInquiryObj->setClause();
        $FiberInquiryObj->debug_query = false;
        $rs_nearsr =  $FiberInquiryObj->recordset_list();
        $iFiberInquiryId_list = array();
        $count_list = count($rs_nearsr);

        for($i=0; $i < $count_list; $i++)
        {
            $iFiberInquiryId_list[] = $rs_nearsr[$i]['iFiberInquiryId'];
        }
        if(count($iFiberInquiryId_list) > 0) {
            $iFiberInquiryId_ids = implode(',', $iFiberInquiryId_list);
        }else {
            $iFiberInquiryId_ids = "99999999999999999999";
        }
        //echo "<pre>";print_r($iFiberInquiryId_list);exit;
        // end near by sr list
        // for near by site list
        $where_arr = array();
        $SiteObj = new Site();
        $SiteObj->clear_variable();
        $where_arr[] = 'ST_DWithin(ST_SetSRID(ST_MakePoint(s."vLongitude",s."vLatitude"), 4326)::geography, ST_MakePoint(' . $long . ', ' . $lat . ')::geography, '.$meter.') ';
        $SiteObj->where = $where_arr;
        $SiteObj->param['order_by'] = "s.\"iPremiseId\" DESC";
        $SiteObj->setClause();
        $SiteObj->debug_query = false;
        $rs_nearsite =  $SiteObj->recordset_list();
        $iPremiseId_list = array();
        $count_list2 = count($rs_nearsite);
        for($i=0; $i < $count_list2; $i++)
        {
            $iPremiseId_list[] = $rs_nearsite[$i]['iPremiseId'];
        }
        $iPremiseId_ids = implode(',', $iPremiseId_list);
        if(count($iPremiseId_ids) > 0) {
            $iPremiseId_ids = implode(',', $iPremiseId_list);
        }else {
            $iPremiseId_ids = "99999999999999999999";
        }
        //echo "<pre>";print_r($iPremiseId_list);exit;
        // end near by site list

        $end_date = date("Y-m-d");
        $start_date = date("Y-m-d", strtotime( date( 'Y-m-d' )." -3 months"));
        if(count($iPremiseId_list) > 0 || count($iFiberInquiryId_list) > 0) {
            //---------------------- start task awareness ------------------------//
            $TaskAwarenessObj = new TaskAwareness();
            $TaskAwarenessObj->clear_variable();
            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            $where_arr[] = "(awareness.\"iFiberInquiryId\" IN ($iFiberInquiryId_ids) OR awareness.\"iPremiseId\" IN ($iPremiseId_ids))";
            $where_arr[] = "awareness.\"dDate\" between '".$start_date."' and '".$end_date."'";
            $join_fieds_arr[] = " e.\"vEngagement\"";
            $join_fieds_arr[] = " s.\"vName\" as  \"vPremiseName\" ";
            $join_fieds_arr[] = " site_type_mas.\"vTypeName\"";
            $join_fieds_arr[] = " site_sub_type_mas.\"vSubTypeName\"";
            $join_fieds_arr[] = " fiberinquiry_details.\"iFiberInquiryId\"";
            $join_fieds_arr[] = " fiberinquiry_details.\"iCId\"";
            $join_fieds_arr[] = "concat(\"vFirstName\",' ', \"vLastName\") as \"vContactName\" ";
            $join_arr[] = 'LEFT JOIN engagement_mas e on e."iEngagementId" = awareness."iEngagementId"';
            $join_arr[] = 'LEFT JOIN premise_mas s on s."iPremiseId" = awareness."iPremiseId"';
            $join_arr[] = 'LEFT JOIN site_type_mas on site_type_mas."iSTypeId" = s."iSTypeId"';
            $join_arr[] = 'LEFT JOIN site_sub_type_mas on site_sub_type_mas."iSSTypeId" = s."iSSTypeId"';
            $join_arr[] = 'LEFT JOIN fiberinquiry_details on fiberinquiry_details."iFiberInquiryId" = awareness."iFiberInquiryId"';
            $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = fiberinquiry_details."iCId"';

            $TaskAwarenessObj->join_field = $join_fieds_arr;
            $TaskAwarenessObj->join = $join_arr;
            $TaskAwarenessObj->where = $where_arr;
            $TaskAwarenessObj->param['order_by'] = "awareness.\"dDate\" DESC";
            $TaskAwarenessObj->setClause();
            $TaskAwarenessObj->debug_query = false;
            $awareness_arr = $TaskAwarenessObj->recordset_list();
            //echo "<pre>";print_r($awareness_arr);exit;
            if(!empty($awareness_arr)) {
                $ti =count($awareness_arr);
                for($t =0; $t<$ti; $t++) {
                    $site_details = '';
                    $vSummary = '';

                    $vPremiseName = $awareness_arr[$t]['iPremiseId'] . " (" . $awareness_arr[$t]['vPremiseName'] . "; " . $awareness_arr[$t]['vTypeName'] . ")";

                    $site_details .= 'Premise #' . $vPremiseName;

                    if($awareness_arr[$t]['iFiberInquiryId'] > 0){
                        $site_details .= "<br/>Fiber Inquiry #".$awareness_arr[$t]['iFiberInquiryId']." (".$awareness_arr[$t]['vContactName'].")";
                    }
                    $vSummary .= 'Awareness #'.$awareness_arr[$t]['iAId'].":".$awareness_arr[$t]['vEngagement'];
                    
                    $arr[$ind]['dDate'] = $awareness_arr[$t]['dDate'];
                    $arr[$ind]['site_details'] =$site_details;
                    $arr[$ind]['vSummary'] = $vSummary;

                    $ind++;
                }
            } 
            //---------------------- end task other------------------------// 
        }

        $ni = count($arr);
        $entry = array();
        if($ni > 0) {
            for($i=0;$i<$ni;$i++){
                $entry[] = array(
                    "Date" => $arr[$i]['dDate'],
                    "Name" => $arr[$i]['site_details'],
                    "Description" => $arr[$i]['vSummary'],
                    );
            }
        }
        $rh = HTTPStatus(200);
        $code = 2000;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 200 , "Message" => $message, "result" => array('nearfiber_inquiry_list' => $entry));
    }else{
       
        $response_data = array("Code" => 500 , "Message" => "LatLong are missing.");
    }
}else if($request_type == "search_fiber_inquiry_address"){
    $rs_arr  = array();
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();

    $serach_vFiberInquiry = $RES_PARA['serach_vFiberInquiry'];
     
    $FiberInquiryObj = new FiberInquiry();
    $FiberInquiryObj->clear_variable();

    $letters = str_replace("'","",$serach_vFiberInquiry);
    $exp_keyword = explode("\\",$letters);
  
    $ext_where_arr =array();
    foreach($exp_keyword as $vl){
        if(trim($vl) != '')
            $ext_where_arr[] = " (concat(fiberinquiry_details.\"vAddress1\", ' ', fiberinquiry_details.\"vStreet\") ILIKE '%".trim($vl)."%' OR CAST(fiberinquiry_details.\"iFiberInquiryId\" AS TEXT) LIKE '".intval($vl)."%')";
    }
    if(count($ext_where_arr) > 0){
        $ext_where = implode(" AND ", $ext_where_arr);
        $where_arr[] = $ext_where;
    }else{
        $where_arr[] = " (concat(fiberinquiry_details.\"vAddress1\", ' ', fiberinquiry_details.\"vStreet\") ILIKE '%".trim($serach_vFiberInquiry)."%'  OR CAST(fiberinquiry_details.\"iFiberInquiryId\" AS TEXT) LIKE '".intval($serach_vFiberInquiry)."%')";
    }     
    $join_fieds_arr[] = 'c."vCity"';
    $join_fieds_arr[] = 'sm."vState"';
    $join_fieds_arr[] = 'cm."vCounty"';
    $join_arr[] = 'LEFT JOIN city_mas c on fiberinquiry_details."iCityId" = c."iCityId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on fiberinquiry_details."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN county_mas cm on fiberinquiry_details."iCountyId" = cm."iCountyId"';
    $FiberInquiryObj->join_field = $join_fieds_arr;
    $FiberInquiryObj->join = $join_arr;
    $FiberInquiryObj->where = $where_arr;
    $FiberInquiryObj->param['limit'] = "0";
    $FiberInquiryObj->param['order_by'] = 'fiberinquiry_details."iFiberInquiryId" DESC';
    
    $FiberInquiryObj->setClause();
    $rs_fInquiry = $FiberInquiryObj->recordset_list();
    for ($i = 0; $i < count($rs_fInquiry); $i++) {
        $rs_arr[] = array(
            'display' => $rs_fInquiry[$i]['iFiberInquiryId']." (".$rs_fInquiry[$i]['vAddress1']." ".$rs_fInquiry[$i]['vStreet']." ".$rs_fInquiry[$i]['vCity'].", ".$rs_fInquiry[$i]['vState'].", ".$rs_fInquiry[$i]['vCounty'].")",
            "iFiberInquiryId" => $rs_fInquiry[$i]['iFiberInquiryId'],
            "vAddress" => $rs_fInquiry[$i]['vAddress1']. " ".$rs_fInquiry[$i]['vStreet']
        );
    }

    $result = array('data' => $rs_arr);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}
else {
	$r = HTTPStatus(400);
	$code = 1001;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 400 , "Message" => $message);
}
?>