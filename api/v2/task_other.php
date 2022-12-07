<?php
include_once($controller_path . "task_other.inc.php");

$TaskOtherObj = new TaskOther();
if($request_type == "task_other_list"){
	$where_arr = array();
    if(!empty($RES_PARA)){
        $iPremiseId     = trim($RES_PARA['iPremiseId']);
        $iTOId       = trim($RES_PARA['iTOId']);
        $vName       = trim($RES_PARA['vName']);
        $vTypeName   = trim($RES_PARA['vTypeName']);
        $iSRId       = trim($RES_PARA['iSRId']);
        $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"10";
        $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"0";
        $display_order = isset($RES_PARA['display_order'])?trim($RES_PARA['display_order']):"";
        $dir = isset($RES_PARA['dir'])?trim($RES_PARA['dir']):"";
    }
    if ($iTOId != "") {
        $where_arr[] = 'task_other."iTOId"='.$iTOId ;
    }
    if ($iPremiseId != "") {
        $where_arr[] = 'task_other."iPremiseId"='.$iPremiseId ;
    }
    if ($vName != "") {
        $where_arr[] = "s.\"vName\" ILIKE '" . $vName . "%'";
    }
    if ($vTypeName != "") {
        $where_arr[] = "st.\"vTypeName\" ILIKE '" . $vTypeName . "%'";
    }
    if ($iSRId != "") {
        $where_arr[] = 'task_other."iSRId"='.$iSRId ;
    }

    switch ($display_order) {
        case "0":
            $sortname = "task_other.\"iTOId\"";
            break;
        case "1":
            $sortname = "s.\"vName\"";
            break;
        case "4":
            $sortname = "task_other.\"dDate\"";
            break;
        default:
            $sortname = "task_other.\"iTOId\"";
            break;
    }

    //$sortname = "task_other.\"iTOId\" desc";
    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = 's."vName"';
    $join_fieds_arr[] = 's."vAddress1"';
    $join_fieds_arr[] = 's."vStreet"';
    $join_fieds_arr[] = 'st."vTypeName"';
    $join_fieds_arr[] = 'c."vCounty"';
    $join_fieds_arr[] = 'sm."vState"';
    $join_fieds_arr[] = 'cm."vCity"';
    $join_fieds_arr[] = 't."vTypeName"';
    $join_fieds_arr[] = "CONCAT(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") AS \"vContactName\"";
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN task_type_mas t on t."iTaskTypeId" = task_other."iTaskTypeId"';
    $join_arr[] = 'LEFT JOIN site_mas s on s."iPremiseId" = task_other."iPremiseId"';
    $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $join_arr[] = 'LEFT JOIN fiberinquiry_details sd on sd."iFiberInquiryId" = task_other."iSRId"';
    $join_arr[] = 'LEFT JOIN contact_mas on  contact_mas."iCId"= sd."iCId"';
    $TaskOtherObj->join_field = $join_fieds_arr;
    $TaskOtherObj->join = $join_arr;
    $TaskOtherObj->where = $where_arr;
    $TaskOtherObj->param['order_by'] = $sortname . " " . $dir;
    $TaskOtherObj->param['limit'] = $limit;
    $TaskOtherObj->setClause();
    $TaskOtherObj->debug_query = false;
    $rs_taskadult = $TaskOtherObj->recordset_list();
    // Paging Total Records
    $total_record = $TaskOtherObj->recordset_total();
    // Paging Total Records
    
    $data = array();
    $ni = count($rs_taskadult);

    if($ni > 0){
        for($i=0;$i<$ni;$i++){
        	$vSite = $rs_taskadult[$i]['vName']."- PremiseID#".$rs_taskadult[$i]['iPremiseId'];
        	$vAddress =  $rs_taskadult[$i]['vAddress1'].' '.$rs_taskadult[$i]['vStreet'].' '.$rs_taskadult[$i]['vCity'].', '.$rs_taskadult[$i]['vState'].' '.$rs_taskadult[$i]['vCounty'];
            if($rs_taskadult[$i]['dStartDate'] != '')
            {
                $rs_taskadult[$i]['dStartTime'] = date("H:i", strtotime($rs_taskadult[$i]['dStartDate']));
            }
            else
            {
                $rs_taskadult[$i]['dStartTime'] = date("H:i", time());
            }
            if($rs_taskadult[$i]['dEndDate'] != '')
            {
                $rs_taskadult[$i]['dEndTime'] = date("H:i", strtotime($rs_taskadult[$i]['dEndDate']));
            }
            else
            {
                $rs_taskadult[$i]['dEndTime'] = date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes"));
            }
            $vSiteName = $rs_taskadult[$i]['iPremiseId']." (".$rs_taskadult[$i]['vName']."; ".$rs_taskadult[$i]['vTypeName'].")";
            $srdisplay = ($rs_taskadult[$i]['iSRId'] != "")?$rs_taskadult[$i]['iSRId']." (".$rs_taskadult[$i]['vContactName'].")":"";

        	$data[] = array(
                "iTOId" => $rs_taskadult[$i]['iTOId'],
                "iPremiseId" => $rs_taskadult[$i]['iPremiseId'],
                "vName" => $vSite,
                "vAddress" => $vAddress,
                "iSRId" => $rs_taskadult[$i]['iSRId'],
                "sr" => $srdisplay,
                "dDate" => $rs_taskadult[$i]['dDate'],
                "dStartDate" => $rs_taskadult[$i]['dStartDate'],
                "dStartTime" => $rs_taskadult[$i]['dStartTime'],
                "dEndDate" => $rs_taskadult[$i]['dEndDate'],
                "dEndTime" => $rs_taskadult[$i]['dEndTime'],
                "iTaskTypeId" => $rs_taskadult[$i]['iTaskTypeId'],
                "vTypeName" => $rs_taskadult[$i]['vTypeName'],
                "tNotes" => $rs_taskadult[$i]['tNotes'],      
                "iTechnicianId" => $rs_taskadult[$i]['iTechnicianId'],  
                "dAddedDate" => $rs_taskadult[$i]['dAddedDate'], 
                "dModifiedDate" => $rs_taskadult[$i]['dModifiedDate'], 
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
   
}
else if($request_type == "task_other_add"){
   //echo "<pre>";print_r($RES_PARA);exit;
    $insert_arr = array(
        "iPremiseId"               => $RES_PARA['iPremiseId'],
        "iSRId"                 => $RES_PARA['iSRId'],
        "dDate"                 => $RES_PARA['dDate'],
        "dStartDate"            => $RES_PARA['dStartDate'],
        "dEndDate"              => $RES_PARA['dEndDate'],
        "iTaskTypeId"           => $RES_PARA['iTaskTypeId'],
        "tNotes"                => $RES_PARA['tNotes'],
        "iUserId"               => $RES_PARA['iUserId'],
        "iTechnicianId"         => $RES_PARA['iTechnicianId'],
    );

    $TaskOtherObj->insert_arr = $insert_arr;
    $TaskOtherObj->setClause();
    $iTOId = $TaskOtherObj->add_records();

    if($iTOId){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "iTOId" => $iTOId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}

else if($request_type == "task_other_edit"){
   //echo "<pre>";print_r($RES_PARA);exit;
   
    $update_arr = array(
        "iTOId"                 => $RES_PARA['iTOId'],
        "iPremiseId"               => $RES_PARA['iPremiseId'],
        "iSRId"                 => $RES_PARA['iSRId'],
        "dDate"                 => $RES_PARA['dDate'],
        "dStartDate"            => $RES_PARA['dStartDate'],
        "dEndDate"              => $RES_PARA['dEndDate'],
        "iTaskTypeId"           => $RES_PARA['iTaskTypeId'],
        "tNotes"                => $RES_PARA['tNotes'],
        "iUserId"               => $RES_PARA['iUserId'],
        "iTechnicianId"         => $RES_PARA['iTechnicianId'],
    );

    $TaskOtherObj->update_arr = $update_arr;
    $TaskOtherObj->setClause();
    $rs_db = $TaskOtherObj->update_records();

    if($rs_db){
        $rh = HTTPStatus(200);
        $code = 2000;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iTOId" => $RES_PARA['iTOId']);
    }else{
        $r = HTTPStatus(500);
        $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
    }
}

else if($request_type == "task_other_delete"){
   //echo "<pre>";print_r($RES_PARA);exit;
   
    $iTOId = $RES_PARA['iTOId'];
    $rs_db = $TaskOtherObj->delete_records($iTOId);

    if($rs_db) {
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iTOId" => $iTOId);
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