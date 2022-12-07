<?php
include_once($controller_path . "task_awareness.inc.php");

$TaskAwarenessObj = new TaskAwareness();
if($request_type == "task_awareness_list"){
    $where_arr = array();
    if(!empty($RES_PARA)){
        $iAId               = $RES_PARA['iAId'];
        $iPremiseId         = $RES_PARA['iPremiseId'];
        $vName              = $RES_PARA['vName'];
        $vEngagement        = $RES_PARA['vEngagement'];
        $iFiberInquiryId    = $RES_PARA['iFiberInquiryId'];
        $page_length        = isset($RES_PARA['page_length']) ? trim($RES_PARA['page_length']) : "10";
        $start              = isset($RES_PARA['start']) ? trim($RES_PARA['start']) : "0";
        $sEcho              = $RES_PARA['sEcho'];
        $display_order      = $RES_PARA['display_order'];
        $dir                = $RES_PARA['dir'];
        $order_by           = $RES_PARA['order_by'];
    }
    if ($iAId != "") {
        $where_arr[] = 'awareness."iAId"='.$iAId ;
    }
    if ($iPremiseId != "") {
        $where_arr[] = 'awareness."iPremiseId"='.$iPremiseId ;
    }
    if ($vName != "") {
        $where_arr[] = "s.\"vName\" ILIKE '" . $vName . "%'";
    }
    if ($vEngagement != "") {
        $where_arr[] = "e.\"vEngagement\" ILIKE '".$vEngagement."%'";
    }
    if ($iFiberInquiryId != "") {
        $where_arr[] = 'awareness."iFiberInquiryId"='.$iFiberInquiryId ;
    }

    switch ($display_order) {
        case "0":
            $sortname = "awareness.\"iAId\"";
            break;
        case "1":
            $sortname = "s.\"vName\"";
            break;
        case "4":
            $sortname = "awareness.\"dDate\"";
            break;
        default:
            $sortname = "awareness.\"iAId\"";
            break;
    }

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = 's."vName"';
    $join_fieds_arr[] = 's."vAddress1"';
    $join_fieds_arr[] = 's."vStreet"';
    $join_fieds_arr[] = 'c."vCounty"';
    $join_fieds_arr[] = 'sm."vState"';
    $join_fieds_arr[] = 'cm."vCity"';
    $join_fieds_arr[] = 'e."vEngagement"';
    $join_fieds_arr[] = "CONCAT(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") AS \"vContactName\"";
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN engagement_mas e on e."iEngagementId" = awareness."iEngagementId"';
    $join_arr[] = 'LEFT JOIN site_mas s on s."iPremiseId" = awareness."iPremiseId"';
    $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
    $join_arr[] = 'LEFT JOIN fiberinquiry_details sd on sd."iFiberInquiryId" = awareness."iFiberInquiryId"';
    $join_arr[] = 'LEFT JOIN contact_mas on  contact_mas."iCId"= sd."iCId"';
    $TaskAwarenessObj->join_field = $join_fieds_arr;
    $TaskAwarenessObj->join = $join_arr;
    $TaskAwarenessObj->where = $where_arr;
    $TaskAwarenessObj->param['order_by'] = $sortname . " " . $dir;
    $TaskAwarenessObj->param['limit'] = $limit;
    $TaskAwarenessObj->setClause();
    $TaskAwarenessObj->debug_query = false;
    $rs_awareness = $TaskAwarenessObj->recordset_list();
    // Paging Total Records
    $total_record = $TaskAwarenessObj->recordset_total();
    // Paging Total Records
    
    $data = array();
    $ni = count($rs_awareness);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
            $vSite = $rs_awareness[$i]['vName']."- PremiseID#".$rs_awareness[$i]['iPremiseId'];
            $vAddress =  $rs_awareness[$i]['vAddress1'].' '.$rs_awareness[$i]['vStreet'].' '.$rs_awareness[$i]['vCity'].', '.$rs_awareness[$i]['vState'].' '.$rs_awareness[$i]['vCounty'];

            if($rs_awareness[$i]['dStartDate'] != '') {
                $rs_awareness[$i]['dStartTime'] = date("H:i", strtotime($rs_awareness[$i]['dStartDate']));
            } else {
                $rs_awareness[$i]['dStartTime'] = date("H:i", time());
            }
            if($rs_awareness[$i]['dEndDate'] != '') {
                $rs_awareness[$i]['dEndTime'] = date("H:i", strtotime($rs_awareness[$i]['dEndDate']));
            } else {
                $rs_awareness[$i]['dEndTime'] = date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes"));
            }
            $vSiteName = $rs_awareness[$i]['iPremiseId']." (".$rs_awareness[$i]['vName']."; ".$rs_awareness[$i]['vTypeName'].")";
            $fiberinquiry_display = ($rs_awareness[$i]['iFiberInquiryId'] != "")?$rs_awareness[$i]['iFiberInquiryId']." (".$rs_awareness[$i]['vContactName'].")":"";

            $data[] = array(
                "iAId"              => $rs_awareness[$i]['iAId'],
                "iPremiseId"        => $rs_awareness[$i]['iPremiseId'],
                "vName"             => $vSite,
                "vAddress"          => $vAddress,
                "iFiberInquiryId"   => $rs_awareness[$i]['iFiberInquiryId'],
                "vFiberInquiry"     => $fiberinquiry_display,
                "dDate"             => $rs_awareness[$i]['dDate'],
                "dStartDate"        => $rs_awareness[$i]['dStartDate'],
                "dStartTime"        => $rs_awareness[$i]['dStartTime'],
                "dEndDate"          => $rs_awareness[$i]['dEndDate'],
                "dEndTime"          => $rs_awareness[$i]['dEndTime'],
                "iEngagementId"     => $rs_awareness[$i]['iEngagementId'],
                "vEngagement"       => $rs_awareness[$i]['vEngagement'],
                "tNotes"            => $rs_awareness[$i]['tNotes'],      
                "iTechnicianId"     => $rs_awareness[$i]['iTechnicianId'],  
                "dAddedDate"        => $rs_awareness[$i]['dAddedDate'], 
                "dModifiedDate"     => $rs_awareness[$i]['dModifiedDate'], 
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}else if($request_type == "task_awareness_add"){
    //echo "<pre>";print_r($RES_PARA);exit;
    $insert_arr = array(
        "iPremiseId"            => $RES_PARA['iPremiseId'],
        "iFiberInquiryId"       => $RES_PARA['iFiberInquiryId'],
        "dDate"                 => $RES_PARA['dDate'],
        "dStartDate"            => $RES_PARA['dStartDate'],
        "dEndDate"              => $RES_PARA['dEndDate'],
        "iEngagementId"         => $RES_PARA['iEngagementId'],
        "tNotes"                => $RES_PARA['tNotes'],
        "iLoginUserId"          => $RES_PARA['iLoginUserId'],
        "iTechnicianId"         => $RES_PARA['iTechnicianId'],
    );

    $TaskAwarenessObj->insert_arr = $insert_arr;
    $TaskAwarenessObj->setClause();
    $iAId = $TaskAwarenessObj->add_records();

    if($iAId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iAId" => $iAId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}else if($request_type == "task_awareness_edit"){
    $update_arr = array(
        "iAId"                  => $RES_PARA['iAId'],
        "iPremiseId"            => $RES_PARA['iPremiseId'],
        "iFiberInquiryId"       => $RES_PARA['iFiberInquiryId'],
        "dDate"                 => $RES_PARA['dDate'],
        "dStartDate"            => $RES_PARA['dStartDate'],
        "dEndDate"              => $RES_PARA['dEndDate'],
        "iEngagementId"         => $RES_PARA['iEngagementId'],
        "tNotes"                => $RES_PARA['tNotes'],
        "iLoginUserId"          => $RES_PARA['iLoginUserId'],
        "iTechnicianId"         => $RES_PARA['iTechnicianId'],
    );

    $TaskAwarenessObj->update_arr = $update_arr;
    $TaskAwarenessObj->setClause();
    $rs_db = $TaskAwarenessObj->update_records();
    if($rs_db){
        $rh = HTTPStatus(200);
        $code = 2000;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iAId" => $RES_PARA['iAId']);
    }else{
        $r = HTTPStatus(500);
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}else if($request_type == "task_awareness_delete"){
    $iAId = $RES_PARA['iAId'];
    $rs_db = $TaskAwarenessObj->delete_records($iAId);
    if($rs_db) {
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iAId" => $iAId);
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