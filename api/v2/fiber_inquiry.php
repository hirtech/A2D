<?php
include_once($controller_path . "premise.inc.php");
include_once($controller_path . "fiber_inquiry.inc.php");
include_once($controller_path . "task_larval_surveillance.inc.php");
include_once($controller_path . "task_treatment.inc.php");
include_once($controller_path . "task_landing_rate.inc.php");
include_once($controller_path . "task_trap.inc.php");
include_once($controller_path . "task_other.inc.php");
if($request_type == "fiber_inquiry_edit"){
    //$vLatitude = number_format($RES_PARA['vLatitude'], 6, '.', '');
    //$vLongitude = number_format($RES_PARA['vLongitude'], 6, '.', '');
    //$sql_premise = "SELECT s.\"iPremiseId\", s.\"vName\" FROM site_mas s WHERE  St_Within(ST_GeometryFromText('POINT(".$vLongitude." ".$vLatitude.")', 4326)::geometry, (s.\"vPointLatLong\")::geometry)='t'ORDER BY s.\"iPremiseId\" DESC LIMIT 1";

    $sql_premise = "SELECT s.\"iPremiseId\", s.\"vName\" FROM site_mas s WHERE s.\"vLongitude\" = '".$RES_PARA['vLongitude']."' AND  s.\"vLatitude\" = '".$RES_PARA['vLatitude']."' ORDER BY s.\"iPremiseId\" DESC LIMIT 1";    
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
    //$sql_premise = "SELECT s.\"iPremiseId\", s.\"vName\" FROM site_mas s WHERE  St_Within(ST_GeometryFromText('POINT(".$vLongitude." ".$vLatitude.")', 4326)::geometry, (s.\"vPointLatLong\")::geometry)='t'ORDER BY s.\"iPremiseId\" DESC LIMIT 1"; 
    $sql_premise = "SELECT s.\"iPremiseId\", s.\"vName\" FROM site_mas s WHERE s.\"vLongitude\" = '".$RES_PARA['vLongitude']."' AND  s.\"vLatitude\" = '".$RES_PARA['vLatitude']."' ORDER BY s.\"iPremiseId\" DESC LIMIT 1";
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
        $status                 = $RES_PARA['status'];
    }

    if ($iFiberInquiryId != "") {
        $where_arr[] = 'fiberinquiry_details."iFiberInquiryId"='.$iFiberInquiryId ;
    }
    
    if ($fiberInquiryId != ""){
        $where_arr[] = 'fiberinquiry_details."iFiberInquiryId"='.$fiberInquiryId;
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

    if ($status != "") {
        $where_arr[] = "fiberinquiry_details.\"iStatus\"='" . $status  . "'";
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
         "iSRId" => $rs_sr[$i]['iFiberInquiryId'],
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
            //-------------------------- start task laravel ---------------------------//
            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            $TaskLarvalSurveillance = new TaskLarvalSurveillance();
            $TaskLarvalSurveillance->clear_variable();
            $where_arr[] = "(task_larval_surveillance.\"iSRId\" IN ($iFiberInquiryId_ids) OR task_larval_surveillance.\"iPremiseId\" IN ($iPremiseId_ids))";
            $where_arr[] = "task_larval_surveillance.\"dDate\" between '".$start_date."' and '".$end_date."'";
            $join_fieds_arr[] = " s.\"vName\" as  \"vSiteName\" ";
            $join_fieds_arr[] = " site_type_mas.\"vTypeName\"";
            $join_fieds_arr[] = " site_sub_type_mas.\"vSubTypeName\"";
            $join_fieds_arr[] = " fiberinquiry_details.\"iFiberInquiryId\"";
            $join_fieds_arr[] = " fiberinquiry_details.\"iCId\"";
            $join_fieds_arr[] = "concat(\"vFirstName\",' ', \"vLastName\") as \"vContactName\" ";
            $join_arr[] = 'LEFT JOIN site_mas s on s."iPremiseId" = task_larval_surveillance."iPremiseId"';
            $join_arr[] = 'LEFT JOIN site_type_mas on site_type_mas."iSTypeId" = s."iSTypeId"';
            $join_arr[] = 'LEFT JOIN site_sub_type_mas on site_sub_type_mas."iSSTypeId" = s."iSSTypeId"';
            $join_arr[] = 'LEFT JOIN fiberinquiry_details on fiberinquiry_details."iFiberInquiryId" = task_larval_surveillance."iSRId"';
            $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = fiberinquiry_details."iCId"';
            $TaskLarvalSurveillance = new TaskLarvalSurveillance();
            $TaskLarvalSurveillance->join_field = $join_fieds_arr;
            $TaskLarvalSurveillance->join = $join_arr;
            $TaskLarvalSurveillance->where = $where_arr;
            $TaskLarvalSurveillance->param['order_by'] = "task_larval_surveillance.\"dDate\" DESC";
            $TaskLarvalSurveillance->setClause();
            $TaskLarvalSurveillance->debug_query = false;
            $larval_surveillance_arr = $TaskLarvalSurveillance->recordset_list();
            //echo "<pre>";print_r($larval_surveillance_arr);exit();
            if(!empty($larval_surveillance_arr)) {
                $ti =count($larval_surveillance_arr);
                for($t =0; $t<$ti; $t++) {
                    $site_details = '';

                    $SiteObj->clear_variable();
                    $where_arr = array();
                    $join_fieds_arr = array();
                    $join_arr  = array();
                    $join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                    $where_arr[] = "site_attribute.\"iPremiseId\"='".$larval_surveillance_arr[$t]['iPremiseId']."'";
                    $join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                    $SiteObj->join_field = $join_fieds_arr;
                    $SiteObj->join = $join_arr;
                    $SiteObj->where = $where_arr;
                    $SiteObj->param['order_by'] = "site_attribute.\"iSAId\"";
                    $SiteObj->setClause();
                    $rs_site_attr = $SiteObj->site_attribute_list();
                    //echo "<pre>";print_r($rs_site_attr);exit();

                    $vAttributeArr = array();
                    if(!empty($rs_site_attr)) {
                        $sai = count($rs_site_attr);
                        for($sa=0; $sa<$sai; $sa++){
                            $vAttributeArr[$sa] = $rs_site_attr[$sa]['vAttribute'];
                        }
                    }

                    //$arr[$ind]['vAttribute'][] = $larval_surveillance_arr[$t]['vAttribute'];
                    $site_details .=  'Premise '.$larval_surveillance_arr[$t]['iPremiseId'].($larval_surveillance_arr[$t]['vSiteName'] ? ' ('.$larval_surveillance_arr[$t]['vSiteName'].') ' : ' ').($larval_surveillance_arr[$t]['vTypeName'] ? $larval_surveillance_arr[$t]['vTypeName'] : ' ').($larval_surveillance_arr[$t]['vSubTypeName'] ? ' ('.$larval_surveillance_arr[$t]['vSubTypeName'].')': ' ').(!empty($vAttributeArr) ? ' ('.implode(' | ',$vAttributeArr).')' : '');

                    if($larval_surveillance_arr[$t]['iFiberInquiryId'] > 0){
                        $site_details .= "<br/>Fiber Inquiry ".$larval_surveillance_arr[$t]['iFiberInquiryId'].($larval_surveillance_arr[$t]['vContactName'] ? " (".$larval_surveillance_arr[$t]['vContactName'].")" : '');
                        $fiber_inquiry_arr[] = $larval_surveillance_arr[$t]['iFiberInquiryId'];
                    }

                    $arr[$ind]['dDate'] = ($larval_surveillance_arr[$t]['dDate'] ? $larval_surveillance_arr[$t]['dDate'] : '');
                    $arr[$ind]['site_details'] =$site_details;

                    $iGenus_data=($larval_surveillance_arr[$t]['iGenus'] ? $larval_surveillance_arr[$t]['iGenus'] : '');

                    switch ($iGenus_data) {
                        case 1:
                        $iGenus = 'Ae.';
                        break;
                        case '2':
                        $iGenus = 'An.';
                        break;
                        case '3':
                        $iGenus = 'Cs.';
                        break;
                        case '4':
                        $iGenus = 'Cx.';
                        break;
                        default:
                        $iGenus = 'N/A';
                    }

                    $bEggs = ($larval_surveillance_arr[$t]['bEggs'] == 't') ? ', E' :'';
                    $bInstar1 = ($larval_surveillance_arr[$t]['bInstar1'] == 't') ? ', I1' :'';
                    $bInstar2 = ($larval_surveillance_arr[$t]['bInstar2'] == 't') ? ', I2' :'';
                    $bInstar3 = ($larval_surveillance_arr[$t]['bInstar3'] == 't') ? ', I3' :'';
                    $bInstar4 = ($larval_surveillance_arr[$t]['bInstar4'] == 't') ? ', I4' :'';
                    $bPupae = ($larval_surveillance_arr[$t]['bPupae'] == 't') ? ', P' :'';
                    $bAdult = ($larval_surveillance_arr[$t]['bAdult'] == 't') ? ', A' :'';

                    $iGenus_data2=($larval_surveillance_arr[$t]['iGenus2'] ? $larval_surveillance_arr[$t]['iGenus2'] : '');

                    switch ($iGenus_data2) {
                        case 1:
                        $iGenus2 = 'Ae.';
                        break;
                        case '2':
                        $iGenus2 = 'An.';
                        break;
                        case '3':
                        $iGenus2 = 'Cs.';
                        break;
                        case '4':
                        $iGenus2 = 'Cx.';
                        break;
                        default:
                        $iGenus = 'N/A';
                    }
                    $bEggs2 = ($larval_surveillance_arr[$t]['bEggs2'] == 't') ? ', E' :'';
                    $bInstar12 = ($larval_surveillance_arr[$t]['bInstar12'] == 't') ? ', I1' :'';
                    $bInstar22 = ($larval_surveillance_arr[$t]['bInstar22'] == 't') ? ', I2,' :'';
                    $bInstar32 = ($larval_surveillance_arr[$t]['bInstar32'] == 't') ? ', I3' :'';
                    $bInstar42 = ($larval_surveillance_arr[$t]['bInstar42'] == 't') ? ', I4' :'';
                    $bPupae2 = ($larval_surveillance_arr[$t]['bPupae2'] == 't') ? ', P' :'';
                    $bAdult2 = ($larval_surveillance_arr[$t]['bAdult2'] == 't') ? ', A' :'';

                    $vSummary ='';
                    $vSummary = 'Larval'.($larval_surveillance_arr[$t]['iDips'] ?  ' Dips'.' '.$larval_surveillance_arr[$t]['iDips'] : '').($larval_surveillance_arr[$t]['rAvgLarvel'] ? ' , Avg Larvae : '.$larval_surveillance_arr[$t]['rAvgLarvel'] : '').' <font color=red>|</font> Species 1 : '.$iGenus.' '.$larval_surveillance_arr[$t]['iCount'].$bEggs.''.$bInstar1.''.$bInstar2.''.$bInstar3.''.$bInstar4.''.$bPupae.''.$bAdult.'<font color=red> | </font>Species 2 : '.$iGenus2.' '.$larval_surveillance_arr[$t]['iCount2'].$bEggs2.''.$bInstar12.''.$bInstar22.''.$bInstar32.''.$bInstar42.''.$bPupae2.''.$bAdult2;
                    
                    $arr[$ind]['vSummary'] = $vSummary;

                    $ind++;
                }
            }

            //---------------------------- start end laravel ----------------------------//

            //-------------------------- start task treatment ---------------------------//

            $TaskTreatmentObj = new TaskTreatment();
            $TaskTreatmentObj->clear_variable();
            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            $where_arr[] = "(task_treatment.\"iSRId\" IN ($iFiberInquiryId_ids) OR task_treatment.\"iPremiseId\" IN ($iPremiseId_ids))";
            $where_arr[] = "task_treatment.\"dDate\" between '".$start_date."' and '".$end_date."'";

            $join_fieds_arr[] = " s.\"vName\" as  \"vSiteName\" ";
            $join_fieds_arr[] = " site_type_mas.\"vTypeName\"";
            $join_fieds_arr[] = " site_sub_type_mas.\"vSubTypeName\"";
            $join_fieds_arr[] = " fiberinquiry_details.\"iFiberInquiryId\"";
            $join_fieds_arr[] = " fiberinquiry_details.\"iCId\"";
            $join_fieds_arr[] = "concat(\"vFirstName\",' ', \"vLastName\") as \"vContactName\" ";
            //$join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
            $join_fieds_arr[] = " unit_mas.\"vUnit\"";
            $join_fieds_arr[] = " treatment_product.\"vName\"";
            $join_arr[] = 'LEFT JOIN site_mas s on s."iPremiseId" = task_treatment."iPremiseId"';
            $join_arr[] = 'LEFT JOIN site_type_mas on site_type_mas."iSTypeId" = s."iSTypeId"';
            //$join_arr[] = 'LEFT JOIN site_attribute on site_attribute."iPremiseId" = s."iPremiseId"';
            //$join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';

            $join_arr[] = 'LEFT JOIN site_sub_type_mas on site_sub_type_mas."iSSTypeId" = s."iSSTypeId"';

            $join_arr[] = 'LEFT JOIN fiberinquiry_details on fiberinquiry_details."iFiberInquiryId" = task_treatment."iSRID"';
            $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = fiberinquiry_details."iCId"';
            $join_arr[] = 'LEFT JOIN unit_mas on unit_mas."iUId" = task_treatment."iUId"';
            $join_arr[] = 'LEFT JOIN treatment_product on treatment_product."iTPId" = task_treatment."iTPId"';

            $TaskTreatmentObj->join_field = $join_fieds_arr;
            $TaskTreatmentObj->join = $join_arr;
            $TaskTreatmentObj->where = $where_arr;
            $TaskTreatmentObj->param['order_by'] = "task_treatment.\"dDate\" DESC";
            $TaskTreatmentObj->setClause();
            $TaskTreatmentObj->debug_query = false;
            $treatment_arr = $TaskTreatmentObj->recordset_list();
            if(!empty($treatment_arr)) {
                $ti =count($treatment_arr);
                for($t =0; $t<$ti; $t++) {
                    $site_details = '';

                    $SiteObj->clear_variable();
                    $where_arr = array();
                    $join_fieds_arr = array();
                    $join_arr  = array();
                    $join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                    $where_arr[] = "site_attribute.\"iPremiseId\"='".$treatment_arr[$t]['iPremiseId']."'";
                    $join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                    $SiteObj->join_field = $join_fieds_arr;
                    $SiteObj->join = $join_arr;
                    $SiteObj->where = $where_arr;
                    $SiteObj->param['order_by'] = "site_attribute.\"iSAId\"";
                    $SiteObj->setClause();
                    $rs_site_attr = $SiteObj->site_attribute_list();
                    //echo "<pre>";print_r($rs_site_attr);exit();

                    $vAttributeArr = array();
                    if(!empty($rs_site_attr)) {
                        $sai = count($rs_site_attr);
                        for($sa=0; $sa<$sai; $sa++){
                            $vAttributeArr[$sa] = $rs_site_attr[$sa]['vAttribute'];
                        }
                    }

                    //$arr[$ind]['vAttribute'][] = $treatment_arr[$t]['vAttribute'];
                    $site_details .=  'Premise '.$treatment_arr[$t]['iPremiseId'].($treatment_arr[$t]['vSiteName'] ? ' ('.$treatment_arr[$t]['vSiteName'].') ' :'').($treatment_arr[$t]['vTypeName'] ? $treatment_arr[$t]['vTypeName'] :'').($treatment_arr[$t]['vSubTypeName'] ? ' ('.$treatment_arr[$t]['vSubTypeName'].')': '').(!empty($vAttributeArr) ? ' ('.implode(' | ',$vAttributeArr).')' :'');

                    if($treatment_arr[$t]['iFiberInquiryId'] > 0){
                        $site_details .= "<br/>SR ".$treatment_arr[$t]['iFiberInquiryId'].($treatment_arr[$t]['vContactName'] ? " (".$treatment_arr[$t]['vContactName'].")" : '');
                        $fiber_inquiry_arr[] = $treatment_arr[$t]['iFiberInquiryId'];
                    }

                    $arr[$ind]['dDate'] = ($treatment_arr[$t]['dDate'] ? $treatment_arr[$t]['dDate'] :'');
                    $arr[$ind]['site_details'] =$site_details;
                    $vSummary ='';
                    $vSummary = 'Treated'.' '.($treatment_arr[$t]['vArea'] ? $treatment_arr[$t]['vArea'] :'').' '.($treatment_arr[$t]['vAreaTreated'] ? $treatment_arr[$t]['vAreaTreated'] : '').' With '.($treatment_arr[$t]['vArea'] ? $treatment_arr[$t]['vArea'] : '').' '.($treatment_arr[$t]['vUnit'] ? $treatment_arr[$t]['vUnit'] : '').' '.($treatment_arr[$t]['vName'] ? $treatment_arr[$t]['vName'] : '');

                    $arr[$ind]['vSummary'] = $vSummary;

                    $ind++;
                }
            }
            //-------------------------- end task treatment---------------------------//

            //---------------------- start task landing rate------------------------//

            $TaskLandingRate = new TaskLandingRate();
            $TaskLandingRate->clear_variable();
            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            $where_arr[] = "(task_landing_rate.\"iSRId\" IN ($iFiberInquiryId_ids) OR task_landing_rate.\"iPremiseId\" IN ($iPremiseId_ids))";
            $where_arr[] = "task_landing_rate.\"dDate\" between '".$start_date."' and '".$end_date."'";

            $join_fieds_arr[] = " s.\"vName\"";
            $join_fieds_arr[] = " site_type_mas.\"vTypeName\"";
            $join_fieds_arr[] = " site_sub_type_mas.\"vSubTypeName\"";
            $join_fieds_arr[] = " fiberinquiry_details.\"iFiberInquiryId\"";
            $join_fieds_arr[] = " fiberinquiry_details.\"iCId\"";
            $join_fieds_arr[] = "concat(\"vFirstName\",' ', \"vLastName\") as \"vContactName\" ";
            //$join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
            //$join_fieds_arr[] = " mosquito_species_mas.\"tDescription\"";
            $join_arr[] = 'LEFT JOIN site_mas s on s."iPremiseId" = task_landing_rate."iPremiseId"';
            $join_arr[] = 'LEFT JOIN site_type_mas on site_type_mas."iSTypeId" = s."iSTypeId"';
            $join_arr[] = 'LEFT JOIN site_sub_type_mas on site_sub_type_mas."iSSTypeId" = s."iSSTypeId"';
            $join_arr[] = 'LEFT JOIN fiberinquiry_details on fiberinquiry_details."iFiberInquiryId" = task_landing_rate."iSRId"';
            $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = fiberinquiry_details."iCId"';
            //$join_arr[] = 'LEFT JOIN site_attribute on site_attribute."iPremiseId" = s."iPremiseId"';
            //$join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';

            $TaskLandingRate->join_field = $join_fieds_arr;
            $TaskLandingRate->join = $join_arr;
            $TaskLandingRate->where = $where_arr;
            $TaskLandingRate->param['order_by'] = "task_landing_rate.\"dDate\" DESC";
            $TaskLandingRate->setClause();
            $TaskLandingRate->debug_query = false;
            $landingrate_arr = $TaskLandingRate->recordset_list();
            if(!empty($landingrate_arr)) {
                $ti =count($landingrate_arr);
                for($t =0; $t<$ti; $t++) {
                    $where_arr = array();
                    $join_fieds_arr = array();
                    $join_arr = array();
                    $TaskLandingRate->clear_variable();
                    $where_arr[] = 'task_landing_rate_species."iTLRId"='.$landingrate_arr[$t]['iTLRId'];
                    $join_fieds_arr[] = " mosquito_species_mas.\"tDescription\"";
                    $join_arr[] = 'LEFT JOIN mosquito_species_mas on mosquito_species_mas."iMSpeciesId" = task_landing_rate_species."iMSpeciesId"';

                    $TaskLandingRate->join_field = $join_fieds_arr;
                    $TaskLandingRate->join = $join_arr;
                    $TaskLandingRate->where = $where_arr;
                    $TaskLandingRate->setClause();
                    $TaskLandingRate->debug_query = false;
                    $landingrate_species_arr = $TaskLandingRate->task_landing_rate_species_list();
                    $species_arr = [];
                    if(count($landingrate_species_arr) > 0) {
                        for($s =0; $s<count($landingrate_species_arr); $s++) {
                            $species_arr[] =$landingrate_species_arr[$s]['tDescription'];
                        }
                    }

                    $site_details = '';

                    $SiteObj->clear_variable();
                    $where_arr = array();
                    $join_fieds_arr = array();
                    $join_arr  = array();
                    $join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                    $where_arr[] = "site_attribute.\"iPremiseId\"='".$landingrate_arr[$t]['iPremiseId']."'";
                    $join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                    $SiteObj->join_field = $join_fieds_arr;
                    $SiteObj->join = $join_arr;
                    $SiteObj->where = $where_arr;
                    $SiteObj->param['order_by'] = "site_attribute.\"iSAId\"";
                    $SiteObj->setClause();
                    $rs_site_attr = $SiteObj->site_attribute_list();
                    //echo "<pre>";print_r($rs_site_attr);exit();

                    $vAttributeArr = array();
                    if(!empty($rs_site_attr)) {
                        $sai = count($rs_site_attr);
                        for($sa=0; $sa<$sai; $sa++){
                            $vAttributeArr[$sa] = $rs_site_attr[$sa]['vAttribute'];
                        }
                    }

                    //$arr[$ind]['vAttribute'][] = $landingrate_arr[$t]['vAttribute'];
                    $site_details .=  'Premise '.$landingrate_arr[$t]['iPremiseId'].($landingrate_arr[$t]['vName'] ? ' ('.$landingrate_arr[$t]['vName'].') ': '').($landingrate_arr[$t]['vTypeName'] ? $landingrate_arr[$t]['vTypeName'] : ' '). ($landingrate_arr[$t]['vSubTypeName'] ? ' ('.$landingrate_arr[$t]['vSubTypeName'].')' : '').(!empty($vAttributeArr) ? '('.implode(' | ',$vAttributeArr).')':'');

                    if($landingrate_arr[$t]['iFiberInquiryId'] > 0){
                        $site_details .= "<br/>SR ".$landingrate_arr[$t]['iFiberInquiryId'].($landingrate_arr[$t]['vContactName'] ? " (".$landingrate_arr[$t]['vContactName'].")" : '');
                        $fiber_inquiry_arr[] = $landingrate_arr[$t]['iFiberInquiryId'];

                    }

                    $arr[$ind]['dDate'] = $landingrate_arr[$t]['dDate'];
                    $arr[$ind]['site_details'] =$site_details;
                    $arr[$ind]['vMaxLandingRate'] =$landingrate_arr[$t]['vMaxLandingRate'];
                    $vSummary = '';
                    $vSummary = 'Landing Rate '.$arr[$ind]['vMaxLandingRate'].' '.implode(' | ',$species_arr);
                    $arr[$ind]['vSummary'] = $vSummary;

                    $ind++;
                }

            }

            //---------------------- end task landing rate------------------------//

            //------------------------ start task trap---------------------------//


            $TaskTrap = new TaskTrap();
            $TaskTrap->clear_variable();
            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            $where_arr[] = "(task_trap.\"iSRId\" IN ($iFiberInquiryId_ids) OR task_trap.\"iPremiseId\" IN ($iPremiseId_ids))";
            $where_arr[] = "task_trap.\"dTrapPlaced\" between '".$start_date."' and '".$end_date."'";
            $join_fieds_arr[] = " s.\"vName\"";
            $join_fieds_arr[] = " site_type_mas.\"vTypeName\"";
            $join_fieds_arr[] = " site_sub_type_mas.\"vSubTypeName\"";
            $join_fieds_arr[] = " fiberinquiry_details.\"iFiberInquiryId\"";
            $join_fieds_arr[] = " fiberinquiry_details.\"iCId\"";
            $join_fieds_arr[] = "concat(\"vFirstName\",' ', \"vLastName\") as \"vContactName\" ";
            //$join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
            $join_arr[] = 'LEFT JOIN site_mas s on s."iPremiseId" = task_trap."iPremiseId"';
            $join_arr[] = 'LEFT JOIN site_type_mas on site_type_mas."iSTypeId" = s."iSTypeId"';
            $join_arr[] = 'LEFT JOIN site_sub_type_mas on site_sub_type_mas."iSSTypeId" = s."iSSTypeId"';
            $join_arr[] = 'LEFT JOIN fiberinquiry_details on fiberinquiry_details."iFiberInquiryId" = task_trap."iSRId"';
            //$join_arr[] = 'LEFT JOIN site_attribute on site_attribute."iPremiseId" = s."iPremiseId"';
            //$join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
            $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = fiberinquiry_details."iCId"';
            $join_fieds_arr[] = " trap_type_mas.\"vTrapName\",task_trap.\"dTrapPlaced\" as dDate";
            $join_arr[] = 'LEFT JOIN trap_type_mas on trap_type_mas."iTrapTypeId" = task_trap."iTrapTypeId"';

            $TaskTrap->join_field = $join_fieds_arr;
            $TaskTrap->join = $join_arr;
            $TaskTrap->where = $where_arr;
            $TaskTrap->param['order_by'] = "task_trap.\"dTrapPlaced\" DESC";
            $TaskTrap->setClause();
            $TaskTrap->debug_query = false;
            $tasktrap_arr = $TaskTrap->recordset_list();
            if(!empty($tasktrap_arr)) {
                $ti =count($tasktrap_arr);
                for($t =0; $t<$ti; $t++) {
                    $site_details = '';
                    $SiteObj->clear_variable();
                    $where_arr = array();
                    $join_fieds_arr = array();
                    $join_arr  = array();
                    $join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                    $where_arr[] = "site_attribute.\"iPremiseId\"='".$tasktrap_arr[$t]['iPremiseId']."'";
                    $join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                    $SiteObj->join_field = $join_fieds_arr;
                    $SiteObj->join = $join_arr;
                    $SiteObj->where = $where_arr;
                    $SiteObj->param['order_by'] = "site_attribute.\"iSAId\"";
                    $SiteObj->setClause();
                    $rs_site_attr = $SiteObj->site_attribute_list();
                    //echo "<pre>";print_r($rs_site_attr);exit();

                    $vAttributeArr = array();
                    if(!empty($rs_site_attr)) {
                        $sai = count($rs_site_attr);
                        for($sa=0; $sa<$sai; $sa++){
                            $vAttributeArr[$sa] = $rs_site_attr[$sa]['vAttribute'];
                        }
                    }

                    //$arr[$ind]['vAttribute'][] = $tasktrap_arr[$t]['vAttribute'];   
                    $site_details .=  'Premise '.$tasktrap_arr[$t]['iPremiseId'].($tasktrap_arr[$t]['vName'] ? ' ('.$tasktrap_arr[$t]['vName'].') ':' ').($tasktrap_arr[$t]['vTypeName'] ? $tasktrap_arr[$t]['vTypeName'] : ' ').($tasktrap_arr[$t]['vSubTypeName'] ?  ' ('.$tasktrap_arr[$t]['vSubTypeName'].')' : ' ').(!empty($vAttributeArr) ? ' ('.implode(' | ',$vAttributeArr).')' : ' ');

                    if($tasktrap_arr[$t]['iFiberInquiryId'] > 0){
                        $site_details .= "<br/>SR ".$tasktrap_arr[$t]['iFiberInquiryId'].($tasktrap_arr[$t]['vContactName'] ? " (".$tasktrap_arr[$t]['vContactName'].")" :'');
                        $fiber_inquiry_arr[] = $tasktrap_arr[$t]['iFiberInquiryId'];
                    }
                    $arr[$ind]['site_details'] =$site_details;       
                    $arr[$ind]['dDate'] = ($tasktrap_arr[$t]['dTrapPlaced'] ? $tasktrap_arr[$t]['dTrapPlaced'] : '');
                    $arr[$ind]['vSummary'] =($tasktrap_arr[$t]['vTrapName'] ? $tasktrap_arr[$t]['vTrapName'].' Placed' : '');

                    $ind++;
                }
            }

            //---------------------- end task trap------------------------//

            //---------------------- start task other------------------------//

            $TaskOther = new TaskOther();
            $TaskOther->clear_variable();
            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            $where_arr[] = "(task_other.\"iSRId\" IN ($iFiberInquiryId_ids) OR task_other.\"iPremiseId\" IN ($iPremiseId_ids))";
            $where_arr[] = "task_other.\"dDate\" between '".$start_date."' and '".$end_date."'";

            $join_fieds_arr[] = " s.\"vName\" as  \"vSiteName\" ";
            $join_fieds_arr[] = " site_type_mas.\"vTypeName\"";
            $join_fieds_arr[] = " site_sub_type_mas.\"vSubTypeName\"";
            $join_fieds_arr[] = " fiberinquiry_details.\"iFiberInquiryId\"";
            $join_fieds_arr[] = " fiberinquiry_details.\"iCId\"";
            $join_fieds_arr[] = "concat(\"vFirstName\",' ', \"vLastName\") as \"vContactName\" ";
            //$join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
            $join_arr[] = 'LEFT JOIN site_mas s on s."iPremiseId" = task_other."iPremiseId"';
            $join_fieds_arr[] = " task_type_mas.\"vTypeName\" as \"task_name\" ";
            $join_arr[] = 'LEFT JOIN site_type_mas on site_type_mas."iSTypeId" = s."iSTypeId"';
            $join_arr[] = 'LEFT JOIN site_sub_type_mas on site_sub_type_mas."iSSTypeId" = s."iSSTypeId"';

            //$join_arr[] = 'LEFT JOIN site_attribute on site_attribute."iPremiseId" = s."iPremiseId"';
            //$join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
            $join_arr[] = 'LEFT JOIN task_type_mas on task_type_mas."iTaskTypeId" = task_other."iTaskTypeId"';
            $join_arr[] = 'LEFT JOIN fiberinquiry_details on fiberinquiry_details."iFiberInquiryId" = task_other."iSRId"';
            $join_arr[] = 'LEFT JOIN contact_mas on contact_mas."iCId" = fiberinquiry_details."iCId"';

            $TaskOther->join_field = $join_fieds_arr;
            $TaskOther->join = $join_arr;
            $TaskOther->where = $where_arr;
            $TaskOther->param['order_by'] = "task_other.\"dDate\" DESC";
            $TaskOther->setClause();
            $TaskOther->debug_query = false;
            $task_other_arr = $TaskOther->recordset_list();
            //echo "<pre>";print_r($task_other_arr);exit;
            if(!empty($task_other_arr)) {
                $ti =count($task_other_arr);
                for($t =0; $t<$ti; $t++) {

                    $site_details = '';
                    $SiteObj->clear_variable();
                    $where_arr = array();
                    $join_fieds_arr = array();
                    $join_arr  = array();
                    $join_fieds_arr[] = " site_attribute_mas.\"vAttribute\"";
                    $where_arr[] = "site_attribute.\"iPremiseId\"='".$task_other_arr[$t]['iPremiseId']."'";
                    $join_arr[] = 'LEFT JOIN site_attribute_mas on site_attribute_mas."iSAttributeId" = site_attribute."iSAttributeId"';
                    $SiteObj->join_field = $join_fieds_arr;
                    $SiteObj->join = $join_arr;
                    $SiteObj->where = $where_arr;
                    $SiteObj->param['order_by'] = "site_attribute.\"iSAId\"";
                    $SiteObj->setClause();
                    $rs_site_attr = $SiteObj->site_attribute_list();
                    //echo "<pre>";print_r($rs_site_attr);exit();

                    $vAttributeArr = array();
                    if(!empty($rs_site_attr)) {
                        $sai = count($rs_site_attr);
                        for($sa=0; $sa<$sai; $sa++){
                            $vAttributeArr[$sa] = $rs_site_attr[$sa]['vAttribute'];
                        }
                    }

                    //$arr[$ind]['vAttribute'][] = $task_other_arr[$t]['vAttribute'];
                    $site_details .=  'Premise '.$task_other_arr[$t]['iPremiseId'].($task_other_arr[$t]['vSiteName'] ? ' ('.$task_other_arr[$t]['vSiteName'].') ' :'').($task_other_arr[$t]['vTypeName'] ? $task_other_arr[$t]['vTypeName'] : '').($task_other_arr[$t]['vSubTypeName'] ? ' ('.$task_other_arr[$t]['vSubTypeName'].')': '').(!empty($vAttributeArr) ? ' ('.implode(' | ',$vAttributeArr).')' : '');

                    if($task_other_arr[$t]['iFiberInquiryId'] > 0)
                    {
                        $site_details .= "<br/>Fiber Inquiry ".$task_other_arr[$t]['iFiberInquiryId']." (".$task_other_arr[$t]['vContactName'].")";
                        $fiber_inquiry_arr[] = $task_other_arr[$t]['iFiberInquiryId'];
                    }

                    $arr[$ind]['dDate'] = $task_other_arr[$t]['dDate'];
                    $arr[$ind]['site_details'] =$site_details;

                    $vSummary =$task_other_arr[$t]['task_name'];;
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
}
else {
	$r = HTTPStatus(400);
	$code = 1001;
	$message = api_getMessage($req_ext, constant($code));
	$response_data = array("Code" => 400 , "Message" => $message);
}
?>