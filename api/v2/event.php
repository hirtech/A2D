<?php
include_once($controller_path . "event.inc.php");

$EventObj = new Event();
if($request_type == "event_list"){
    $where_arr = array();
    if(!empty($RES_PARA)){
        $iEventId           = $RES_PARA['iEventId'];
        $vEventType         = $RES_PARA['vEventType'];
        $vCampaignBy        = $RES_PARA['vCampaignBy'];
        $iStatus            = $RES_PARA['iStatus'];

        $iSCampaignBy       = $RES_PARA['iSCampaignBy'];
        $iSPremiseId        = $RES_PARA['iSPremiseId'];
        $vSPremiseNameDD    = $RES_PARA['vSPremiseNameDD'];
        $vSPremiseName      = $RES_PARA['vSPremiseName'];
        $iSZoneId           = $RES_PARA['iSZoneId'];
        $vSZipcodeDD        = $RES_PARA['vSZipcodeDD'];
        $vSZipcode          = $RES_PARA['vSZipcode'];
        $vSCityDD           = $RES_PARA['vSCityDD'];
        $vSCity             = $RES_PARA['vSCity'];
        $iSNetworkId        = $RES_PARA['iSNetworkId'];
        $iSStatus           = $RES_PARA['iSStatus'];
        $dSCompletedDate    = $RES_PARA['dSCompletedDate'];

        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
        $order_by           = $RES_PARA['order_by'];
    }
    if ($iEventId != "") {
        $where_arr[] = 'event."iEventId"='.$iEventId ;
    }

    if ($vEventType != "") {
        $where_arr[] = "e.\"vEventType\" ILIKE '" . $vEventType . "%'";
    }

    if ($iSCampaignBy != "") {
        $where_arr[] = 'event."iCampaignBy"='.$iSCampaignBy ;
    }

    if ($iSStatus != "") {
        $where_arr[] = 'event."iStatus"='.$iSStatus ;
    }

    if ($dSCompletedDate != "") {
        $where_arr[] = "event.\"dCompletedDate\"='".$dSCompletedDate."'";
    }

    $iEventIdArr = array();
    // Campaign Covarage Filters
    $premise_where_arr = [];
    if($iSPremiseId != "" || $vSPremiseName != ""){
        $premise_where_arr[] = "event_campaign_coverage.\"iCampaignBy\"='1'";
        if ($iSPremiseId != "") {
            $premise_where_arr[] = "event_campaign_coverage.\"iCampaignCoverageId\"='".$iSPremiseId."'";
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
        if(!empty($premise_where_arr)) {
            //echo "<pre>";print_r($premise_where_arr);exit;
            $premise_join_fieds_arr = array();
            $premise_join_fieds_arr[] = 's."vName"';
            $premise_join_arr = array();
            $premise_join_arr[] = 'LEFT JOIN site_mas s on event_campaign_coverage."iCampaignCoverageId" = s."iPremiseId"';
            $EventObj->join_field = $premise_join_fieds_arr;
            $EventObj->join = $premise_join_arr;
            $EventObj->where = $premise_where_arr;
            $EventObj->param['order_by'] = "s.\"vName\" ASC";
            $EventObj->setClause();
            $EventObj->debug_query = false;
            $rs = $EventObj->event_campaign_coverage_recordset_list();
            if($rs) {
                //echo "<pre>";print_r($rs);exit;
                $ci =count($rs);
                for($c=0; $c<$ci; $c++){
                    $iEventIdArr[] = $rs[$c]['iEventId'];
                }
            }
        }
    }

    $zone_where_arr = [];
    if ($iSZoneId != "") {
        $zone_where_arr[] = "event_campaign_coverage.\"iCampaignBy\"='2'";
        $zone_where_arr[] = "event_campaign_coverage.\"iCampaignCoverageId\"='".$iSZoneId."'";
    }
    if(!empty($zone_where_arr)) {
        $zone_join_fieds_arr = array();
        $zone_join_arr = array();
        $zone_join_arr[] = 'LEFT JOIN zone z on event_campaign_coverage."iCampaignCoverageId" = z."iZoneId"';
        $EventObj->join_field = $zone_join_fieds_arr;
        $EventObj->join = $zone_join_arr;
        $EventObj->where = $zone_where_arr;
        $EventObj->param['order_by'] = "z.\"iZoneId\" DESC";
        $EventObj->setClause();
        $EventObj->debug_query = false;
        $rs = $EventObj->event_campaign_coverage_recordset_list();
        if($rs) {
            //echo "<pre>";print_r($rs);exit;
            $ci =count($rs);
            for($c=0; $c<$ci; $c++){
                $iEventIdArr[] = $rs[$c]['iEventId'];
            }
        }
    }

    $zipcode_where_arr = [];
    if ($vSZipcode != "") {
        $zipcode_where_arr[] = "event_campaign_coverage.\"iCampaignBy\"='3'";
        if ($vSZipcodeDD != "") {
            if ($vSZipcodeDD == "Begins") {
                $zipcode_where_arr[] = 'z."vZipcode" ILIKE \''.$vSZipcode.'%\'';
            } else if ($vSZipcodeDD == "Ends") {
                $zipcode_where_arr[] = 'z."vZipcode" ILIKE \'%'.$vSZipcode.'\'';
            } else if ($vSZipcodeDD == "Contains") {
                $zipcode_where_arr[] = 'z."vZipcode" ILIKE \'%'.$vSZipcode.'%\'';
            } else if ($vSZipcodeDD == "Exactly") {
                $zipcode_where_arr[] = 'z."vZipcode" ILIKE \''.$vSZipcode.'\'';
            }
        } else {
            $zipcode_where_arr[] = 'z."vZipcode" ILIKE \''.$vSZipcode.'%\'';
        }
    }
    if(!empty($zipcode_where_arr)) {
        $zipcode_join_fieds_arr = array();
        $zipcode_join_fieds_arr[] = 'z."vZipcode"';
        $zipcode_join_arr = array();
        $zipcode_join_arr[] = 'LEFT JOIN zipcode_mas z on event_campaign_coverage."iCampaignCoverageId" = z."iZipcode"';
        $EventObj->join_field = $zipcode_join_fieds_arr;
        $EventObj->join = $zipcode_join_arr;
        $EventObj->where = $zipcode_where_arr;
        $EventObj->param['order_by'] = "z.\"vZipcode\" ASC";
        $EventObj->setClause();
        $EventObj->debug_query = false;
        $rs = $EventObj->event_campaign_coverage_recordset_list();
        if($rs) {
            //echo "<pre>";print_r($rs);exit;
            $ci =count($rs);
            for($c=0; $c<$ci; $c++){
                $iEventIdArr[] = $rs[$c]['iEventId'];
            }
        }
    }

    $city_where_arr = [];
    if ($vSCity != "") {
        $city_where_arr[] = "event_campaign_coverage.\"iCampaignBy\"='4'";
        if ($vSCityDD != "") {
            if ($vSCityDD == "Begins") {
                $city_where_arr[] = 'c."vCity" ILIKE \''.$vSCity.'%\'';
            } else if ($vSCityDD == "Ends") {
                $city_where_arr[] = 'c."vCity" ILIKE \'%'.$vSCity.'\'';
            } else if ($vSCityDD == "Contains") {
                $city_where_arr[] = 'c."vCity" ILIKE \'%'.$vSCity.'%\'';
            } else if ($vSCityDD == "Exactly") {
                $city_where_arr[] = 'c."vCity" ILIKE \''.$vSCity.'\'';
            }
        } else {
            $city_where_arr[] = 'c."vCity" ILIKE \''.$vSCity.'%\'';
        }
    }
    if(!empty($city_where_arr)) {
        $city_join_fieds_arr = array();
        $city_join_fieds_arr[] = 'c."vCity"';
        $city_join_arr = array();
        $city_join_arr[] = 'LEFT JOIN city_mas c on event_campaign_coverage."iCampaignCoverageId" = c."iCityId"';
        $EventObj->join_field = $city_join_fieds_arr;
        $EventObj->join = $city_join_arr;
        $EventObj->where = $city_where_arr;
        $EventObj->param['order_by'] = "c.\"vCity\" ASC";
        $EventObj->setClause();
        $EventObj->debug_query = false;
        $rs = $EventObj->event_campaign_coverage_recordset_list();
        if($rs) {
            //echo "<pre>";print_r($rs);exit;
            $ci =count($rs);
            for($c=0; $c<$ci; $c++){
                $iEventIdArr[] = $rs[$c]['iEventId'];
            }
        }
    }
    
    $network_where_arr = [];
    if ($iSNetworkId != "") {
        $network_where_arr[] = "event_campaign_coverage.\"iCampaignBy\"='5'";
        $network_where_arr[] = "event_campaign_coverage.\"iCampaignCoverageId\"='".$iSNetworkId."'";
    }
    if(!empty($network_where_arr)) {
        $network_join_fieds_arr = array();
        $network_join_arr = array();
        $network_join_arr[] = 'LEFT JOIN network n on event_campaign_coverage."iCampaignCoverageId" = n."iNetworkId"';
        $EventObj->join_field = $network_join_fieds_arr;
        $EventObj->join = $network_join_arr;
        $EventObj->where = $network_where_arr;
        $EventObj->param['order_by'] = "n.\"iNetworkId\" DESC";
        $EventObj->setClause();
        $EventObj->debug_query = false;
        $rs = $EventObj->event_campaign_coverage_recordset_list();
        if($rs) {
            //echo "<pre>";print_r($rs);exit;
            $ci =count($rs);
            for($c=0; $c<$ci; $c++){
                $iEventIdArr[] = $rs[$c]['iEventId'];
            }
        }
    }

    $iEventIdArr = array_unique($iEventIdArr);
    if(!empty($iEventIdArr)){
        $where_arr[] = "event.\"iEventId\" IN (".implode(",", $iEventIdArr).") ";
    }
    //echo "<pre>"; print_r($where_arr);exit;

    switch ($display_order) {
        case "0":
            $sortname = "event.\"iEventId\"";
            break;
        case "1":
            $sortname = "e.\"vEventType\"";
            break;
        case "4":
            $sortname = "event.\"iStatus\"";
            break;
        case "5":
            $sortname = "event.\"dCompletedDate\"";
            break;
        default:
            $sortname = "event.\"iEventId\"";
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = 'e."vEventType"';
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN event_type_mas e on e."iEventTypeId" = event."iEventTypeId"';
    $EventObj->join_field = $join_fieds_arr;
    $EventObj->join = $join_arr;
    $EventObj->where = $where_arr;
    $EventObj->param['order_by'] = $sortname . " " . $dir;
    $EventObj->param['limit'] = $limit;
    $EventObj->setClause();
    $EventObj->debug_query = false;
    $rs_event = $EventObj->recordset_list();
    // Paging Total Records
    $total_record = $EventObj->recordset_total();
    // Paging Total Records
    
    $data = array();
    $ni = count($rs_event);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $vCampaignCoverage = '';
            if($rs_event[$i]['iCampaignBy'] == 1) { // Premise
                $sql = "SELECT e.\"iCampaignCoverageId\", s.\"vName\", st.\"vTypeName\" from event_campaign_coverage e, site_mas s LEFT JOIN site_type_mas st ON s.\"iSTypeId\" = st.\"iSTypeId\" where e.\"iCampaignBy\" = 1 and e.\"iCampaignCoverageId\" = s.\"iPremiseId\" and e.\"iEventId\" = '".$rs_event[$i]['iEventId']."' Order BY s.\"iPremiseId\"";
                $rs = $sqlObj->GetAll($sql);
                //echo $sql;exit;
                $ci = count($rs);
                $vCampaignCoverage = '';
                for($c=0; $c<$ci; $c++){
                    $vCampaignCoverage .= $rs[$c]['iCampaignCoverageId']."(".$rs[$c]['vName'].";".$rs[$c]['vTypeName'].")";
                    if($ci - 1){
                        $vCampaignCoverage .= "<br/>";
                    }
                }
            }else if($rs_event[$i]['iCampaignBy'] == 2) { // Zone
               $sql = "SELECT e.\"iCampaignCoverageId\", z.\"vZoneName\" from event_campaign_coverage e LEFT JOIN zone z ON e.\"iCampaignCoverageId\" = z.\"iZoneId\" where e.\"iCampaignBy\" = 2 and e.\"iCampaignCoverageId\" =  z.\"iZoneId\" and z.\"iStatus\" = 1   and e.\"iEventId\" = '".$rs_event[$i]['iEventId']."'  Order BY z.\"iZoneId\"";
                $rs = $sqlObj->GetAll($sql);
                $ci = count($rs);
                $vCampaignCoverage = '';
                for($c=0; $c<$ci; $c++){
                    $vCampaignCoverage .= $rs[$c]['iCampaignCoverageId']."(".$rs[$c]['vZoneName'].")";
                    if($ci - 1){
                        $vCampaignCoverage .= "<br/>";
                    }
                }
            }else if($rs_event[$i]['iCampaignBy'] == 3) { // Zipcode
               $sql = "SELECT e.\"iCampaignCoverageId\", z.\"vZipcode\" from event_campaign_coverage e LEFT JOIN zipcode_mas z ON e.\"iCampaignCoverageId\" = z.\"iZipcode\" where e.\"iCampaignBy\" = 3 and e.\"iCampaignCoverageId\" =  z.\"iZipcode\" and e.\"iEventId\" = '".$rs_event[$i]['iEventId']."'  Order BY z.\"iZipcode\"";
                $rs = $sqlObj->GetAll($sql);
                $ci = count($rs);
                $vCampaignCoverage = '';
                for($c=0; $c<$ci; $c++){
                    $vCampaignCoverage .= $rs[$c]['iCampaignCoverageId']."(".$rs[$c]['vZipcode'].")";
                    if($ci - 1){
                        $vCampaignCoverage .= "<br/>";
                    }
                }
            }else if($rs_event[$i]['iCampaignBy'] == 4) { // city
               $sql = "SELECT e.\"iCampaignCoverageId\", c.\"vCity\" from event_campaign_coverage e LEFT JOIN city_mas c ON e.\"iCampaignCoverageId\" = c.\"iCityId\" where e.\"iCampaignBy\" = 4 and e.\"iCampaignCoverageId\" =  c.\"iCityId\" and e.\"iEventId\" = '".$rs_event[$i]['iEventId']."'  Order BY c.\"iCityId\"";
                $rs = $sqlObj->GetAll($sql);
                $ci = count($rs);
                $vCampaignCoverage = '';
                for($c=0; $c<$ci; $c++){
                    $vCampaignCoverage .= $rs[$c]['iCampaignCoverageId']."(".$rs[$c]['vCity'].")";
                    if($ci - 1){
                        $vCampaignCoverage .= "<br/>";
                    }
                }
            }else if($rs_event[$i]['iCampaignBy'] == 5) { // network
               $sql = "SELECT e.\"iCampaignCoverageId\", n.\"vName\" from event_campaign_coverage e LEFT JOIN network n ON e.\"iCampaignCoverageId\" = n.\"iNetworkId\" where e.\"iCampaignBy\" = 5 and e.\"iCampaignCoverageId\" =  n.\"iNetworkId\" and e.\"iEventId\" = '".$rs_event[$i]['iEventId']."'  Order BY n.\"iNetworkId\"";
                $rs = $sqlObj->GetAll($sql);
                $ci = count($rs);
                $vCampaignCoverage = '';
                for($c=0; $c<$ci; $c++){
                    $vCampaignCoverage .= $rs[$c]['iCampaignCoverageId']."(".$rs[$c]['vName'].")";
                    if($ci - 1){
                        $vCampaignCoverage .= "<br/>";
                    }
                }
            }

            $data[] = array(
                "iEventId"          => $rs_event[$i]['iEventId'],
                "iEventTypeId"      => $rs_event[$i]['iEventTypeId'],
                "vEventType"        => $rs_event[$i]['vEventType'],
                "iCampaignBy"       => $rs_event[$i]['iCampaignBy'],
                "iStatus"           => $rs_event[$i]['iStatus'], 
                "dCompletedDate"    => $rs_event[$i]['dCompletedDate'], 
                "vCampaignCoverage" => $vCampaignCoverage, 
                "dAddedDate"        => $rs_event[$i]['dAddedDate'], 
                "dModifiedDate"     => $rs_event[$i]['dModifiedDate'], 
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "event_add"){
    //echo "<pre>";print_r($RES_PARA);exit;
    $insert_arr = array(
        "iEventTypeId"      => $RES_PARA['iEventTypeId'],
        "iCampaignBy"       => $RES_PARA['iCampaignBy'],
        "iStatus"           => $RES_PARA['iStatus'],
        "dCompletedDate"    => $RES_PARA['dCompletedDate'],
        "iPremiseId"        => $RES_PARA['iPremiseId'],
        "iZoneId"           => $RES_PARA['iZoneId'],
        "iZipcode"          => $RES_PARA['iZipcode'],
        "iCityId"           => $RES_PARA['iCityId'],
        "iNetworkId"        => $RES_PARA['iNetworkId'],
    );

    $EventObj->insert_arr = $insert_arr;
    $EventObj->setClause();
    $iEventId = $EventObj->add_records();

    if($iEventId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iEventId" => $iEventId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "event_edit"){
    $update_arr = array(
        "iEventId"          => $RES_PARA['iEventId'],
        "iEventTypeId"      => $RES_PARA['iEventTypeId'],
        "iCampaignBy"       => $RES_PARA['iCampaignBy'],
        "iStatus"           => $RES_PARA['iStatus'],
        "dCompletedDate"    => $RES_PARA['dCompletedDate'],
        "iPremiseId"        => $RES_PARA['iPremiseId'],
        "iZoneId"           => $RES_PARA['iZoneId'],
        "iZipcode"          => $RES_PARA['iZipcode'],
        "iCityId"           => $RES_PARA['iCityId'],
        "iNetworkId"        => $RES_PARA['iNetworkId'],
    );

    $EventObj->update_arr = $update_arr;
    $EventObj->setClause();
    $rs_db = $EventObj->update_records();
    if($rs_db){
        $rh = HTTPStatus(200);
        $code = 2000;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iEventId" => $RES_PARA['iEventId']);
    }else{
        $r = HTTPStatus(500);
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}else if($request_type == "event_delete"){
    $iEventId = $RES_PARA['iEventId'];
    $rs_db = $EventObj->delete_records($iEventId);
    if($rs_db) {
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iEventId" => $iEventId);
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