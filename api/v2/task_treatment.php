<?php

include_once($controller_path . "task_treatment.inc.php");
include_once($controller_path . "treatment_product.inc.php");

$TaskTreatmentObj = new TaskTreatment();

$TProdObj = new TreatmentProduct();
if($request_type == "task_treatment_list"){
	$where_arr = array();
    
   if(!empty($RES_PARA)){
        $iTreatmentId       = trim($RES_PARA['iTreatmentId']);
        $iSiteId            = trim($RES_PARA['iSiteId']);
        $vName              = trim($RES_PARA['vName']);
        $vTypeName          = trim($RES_PARA['vTypeName']);
        $vType              = trim($RES_PARA['vType']);
        $vTPName            = trim($RES_PARA['vTPName']);
        $iSRId              = trim($RES_PARA['iSRId']);
        $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"10";
        $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"0";
        $display_order = isset($RES_PARA['display_order'])?trim($RES_PARA['display_order']):"";
        $dir = isset($RES_PARA['dir'])?trim($RES_PARA['dir']):"";
    }
   
    if ($iTreatmentId != "") {
        $where_arr[] = 'task_treatment."iTreatmentId"='.$iTreatmentId ;
    }
    
    if ($iSiteId != "") {
        $where_arr[] = 'task_treatment."iSiteId"='.$iSiteId ;
    }

    if ($vName != "") {
        $where_arr[] = "s.\"vName\" ILIKE '" . $vName . "%'";
    }

    if ($vType != "") {
        $where_arr[] = "task_treatment.\"vType\" ILIKE '" . $vType . "%'";
    }

    if($vTPName != ""){
        $where_arr[] = "t.\"vName\" ILIKE '" . $vTPName . "%'";
    }

    if ($iSRId != "") {
        $where_arr[] = 'task_treatment."iSRId"='.$iSRId ;
    }

    switch ($display_order) {
        case "0":
            $sortname = "task_treatment.\"iTreatmentId\"";
            break;
        case "1":
            $sortname = "s.\"vName\"";
            break;
        case "3":
            $sortname = "task_treatment.\"vType\"";
            break;
        case "4":
            $sortname = "task_treatment.\"dDate\"";
            break;
        case "7":
            $sortname = "t.\"vName\"";
            break;
        default:
            $sortname = "task_treatment.\"iTreatmentId\"";
            break;
    }

    //$sortname = "task_treatment.\"iTreatmentId\" desc";

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = 's."vName"';
    $join_fieds_arr[] = 'st."vTypeName"';
    $join_fieds_arr[] = 't."vName" as "vTPName" ';
    $join_fieds_arr[] = 'unit_mas."iParentId"';
    $join_fieds_arr[] = 'unit_mas."vUnit"';
    $join_fieds_arr[] = "CONCAT(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") AS \"vContactName\"";
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN site_mas s on s."iSiteId" = task_treatment."iSiteId"';
    $join_arr[] = 'LEFT JOIN treatment_product t on t."iTPId" = task_treatment."iTPId"';
    $join_arr[] = 'LEFT JOIN unit_mas  on unit_mas."iUId" = task_treatment."iUId"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $join_arr[] = 'LEFT JOIN sr_details sd on sd."iSRId" = task_treatment."iSRId"';
    $join_arr[] = 'LEFT JOIN contact_mas on  contact_mas."iCId"= sd."iCId"';
    $TaskTreatmentObj->join_field = $join_fieds_arr;
    $TaskTreatmentObj->join = $join_arr;
    $TaskTreatmentObj->where = $where_arr;
    $TaskTreatmentObj->param['order_by'] = $sortname . " " . $dir;
    $TaskTreatmentObj->param['limit'] = $limit;
    $TaskTreatmentObj->setClause();
    $TaskTreatmentObj->debug_query = false;
    $rs_data = $TaskTreatmentObj->recordset_list();
   
    $total_record = $TaskTreatmentObj->recordset_total();
    
    $data = array();
    $ni = count($rs_data);
    
    if(!empty($rs_data)){
        for($i=0;$i<$ni;$i++){

            $TProdObj->clear_variable();

            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr = array();
            $join_fieds_arr[] = 'unit_mas."vUnit"';
            $where_arr[] = 'treatment_product."iTPId" = '.$rs_data[$i]['iTPId'].'';
            $join_arr[] = 'LEFT JOIN unit_mas  on unit_mas."iUId" = treatment_product."iUId"';
            $TProdObj->join_field = $join_fieds_arr;
            $TProdObj->join = $join_arr;
            $TProdObj->where = $where_arr;
            $TProdObj->param['limit'] = "LIMIT 1";
            $TProdObj->param['order_by'] = 'treatment_product."iTPId" DESC';
            $TProdObj->setClause();
            $rs_trtproduct = $TProdObj->recordset_list();

            $appRate = (isset($rs_trtproduct[0]['vAppRate']))?$rs_trtproduct[0]['vAppRate']:"";
            $minRate = (isset($rs_trtproduct[0]['vMinAppRate']))?"min ".$rs_trtproduct[0]['vMinAppRate']:"";
            $maxRate = (isset($rs_trtproduct[0]['vMaxAppRate']))?"- max ".$rs_trtproduct[0]['vMaxAppRate']:"";
            $tragetappRate = (isset($rs_trtproduct[0]['vTragetAppRate']))?$rs_trtproduct[0]['vTragetAppRate']:"";
            $unitName = (isset($rs_trtproduct[0]['vUnit']))?$rs_trtproduct[0]['vUnit']:"";

            $applicationRate = $appRate . "(".$minRate.$maxRate.")".$unitName."/".$tragetappRate;

        	$vSite = $rs_data[$i]['vName']."- PremiseID#".$rs_data[$i]['iSiteId'];

            if($rs_data[$i]['dStartDate'] != '')
            {
                $rs_data[$i]['dStartTime'] = date("H:i", strtotime($rs_data[$i]['dStartDate']));
            }
            else
            {
                $rs_data[$i]['dStartTime'] = date("H:i", time());
            }

             if($rs_data[$i]['dEndDate'] != '')
            {
                $rs_data[$i]['dEndTime'] = date("H:i", strtotime($rs_data[$i]['dEndDate']));
            }
            else
            {
                $rs_data[$i]['dEndTime'] = date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes"));
            }

            $vSiteName = $rs_data[$i]['iSiteId']." (".$rs_data[$i]['vName']."; ".$rs_data[$i]['vTypeName'].")";

            $srdisplay = ($rs_data[$i]['iSRId'] != "")?$rs_data[$i]['iSRId']." (".$rs_data[$i]['vContactName'].")":"";

        	$data[] = array(
                "iTreatmentId" => $rs_data[$i]['iTreatmentId'],
                "iSiteId" => $rs_data[$i]['iSiteId'],
                "vSiteName" => $vSite,
                "iSRId" => $rs_data[$i]['iSRId'],
                "sr" => $srdisplay,
                "vType" =>$rs_data[$i]['vType'],
                "dDate" => $rs_data[$i]['dDate'],
                "dStartDate" => $rs_data[$i]['dStartDate'],
                "dStartTime" => $rs_data[$i]['dStartTime'],
                "dEndDate" => $rs_data[$i]['dEndDate'],
                "dEndTime" => $rs_data[$i]['dEndTime'],
                "iTPId" => $rs_data[$i]['iTPId'],
                "vTPName" => $rs_data[$i]['vTPName'],
                "vAppRate" => $appRate,
                "vArea" => $rs_data[$i]['vArea'],
                "vAreaTreated" => $rs_data[$i]['vAreaTreated'],
                "vAmountApplied" => $rs_data[$i]['vAmountApplied'],
                "ApplicationRate" => $applicationRate,
                "iUId" => $rs_data[$i]['iUId'],
                "vUnit" => $rs_data[$i]['vUnit'],
                "iUnitParentId" => $rs_data[$i]['iParentId'],
                "bJustification" => $rs_data[$i]['bJustification'],
                "iTechnicianId" => $rs_data[$i]['iTechnicianId'],
            );
       }    
    }
    
    $result = array('data' => $data , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
   
}else if($request_type =="task_treatment_add"){
    $insert_arr = array(
        "iSiteId"          => $RES_PARA['iSiteId'],
        "iSRId"            => $RES_PARA['iSRId'],
        "dDate"            => $RES_PARA['dDate'],
        "vType"            => $RES_PARA['vType'],
        "dStartDate"       => $RES_PARA['dStartDate'],
        "dEndDate"         => $RES_PARA['dEndDate'],
        "iTPId"            => $RES_PARA['iTPId'],
        "vArea"            => $RES_PARA['vArea'],
        "vAreaTreated"     => $RES_PARA['vAreaTreated'],
        "vAmountApplied"   => $RES_PARA['vAmountApplied'],
        "iUId"             => $RES_PARA['iUId'],
        "iUserId"           => $RES_PARA['iUserId'],
        "iTechnicianId" => $RES_PARA['iTechnicianId'],
    );

    $TaskTreatmentObj->insert_arr = $insert_arr;
    $TaskTreatmentObj->setClause();
    $iTreatmentId = $TaskTreatmentObj->add_records();

    if($iTreatmentId > 0){
        $result = array('iTreatmentId' => $iTreatmentId );
        $rh = HTTPStatus(200);
        $code = 2000;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
    }else {
       $r = HTTPStatus(200);
       $code = 1023;
       $message = api_getMessage($req_ext, constant($code));
       $response_data = array("Code" => 200 , "Message" => $message);
    }
}else if($request_type == "task_treatment_delete"){
    $iTreatmentId = trim($RES_PARA['iTreatmentId']);
    if($iTreatmentId == "")
    {
        $r = HTTPStatus(200);
        $code = 1002;
        $message = api_getMessage($req_ext, sprintf(constant($code), 'iTreatmentId'));
        $response_data = array("Code" => 200 , "Message" => $message);
    }else{
        $rs_tot = $TaskTreatmentObj->delete_records($iTreatmentId);
        
        $rh = HTTPStatus(200);
        $code = 2000;
        $message = api_getMessage($req_ext, constant($code));
        $response_data = array("Code" => $code, "Message" => $message);
    }
}else if($request_type == "task_treatment_edit"){
    if(trim($RES_PARA['iTreatmentId']) == "")
    {
        $r = HTTPStatus(200);
        $code = 1023;
        $message = api_getMessage($req_ext, sprintf(constant($code), 'iTreatmentId'));
        $response_data = array("Code" => 200 , "Message" => $message);
    }else{
        $update_arr = array(
            "iTreatmentId"     => $RES_PARA['iTreatmentId'],
            "iSiteId"          => $RES_PARA['iSiteId'],
            "iSRId"            => $RES_PARA['iSRId'],
            "dDate"            => $RES_PARA['dDate'],
            "vType"            => $RES_PARA['vType'],
            "dStartDate"       => $RES_PARA['dStartDate'],
            "dEndDate"         => $RES_PARA['dEndDate'],
            "iTPId"            => $RES_PARA['iTPId'],
            "vArea"            => $RES_PARA['vArea'],
            "vAreaTreated"     => $RES_PARA['vAreaTreated'],
            "vAmountApplied"   => $RES_PARA['vAmountApplied'],
            "iUId"             => $RES_PARA['iUId'],
            "iUserId"           => $RES_PARA['iUserId'],
            "iTechnicianId" => $RES_PARA['iTechnicianId'],
        );

        $TaskTreatmentObj->update_arr = $update_arr;
        $TaskTreatmentObj->setClause();
        $rs_db = $TaskTreatmentObj->update_records();
        
        if($rs_db){
            $rh = HTTPStatus(200);
            $code = 2000;
            $message = api_getMessage($req_ext, constant($code));
            $response_data = array("Code" => $code, "Message" => $message, "result" => $rs_db);
        }else {
           $r = HTTPStatus(200);
           $code = 1023;
           $message = api_getMessage($req_ext, constant($code));
           $response_data = array("Code" => 200 , "Message" => $message);
        }
    }
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}

?>