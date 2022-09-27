<?php
include_once($controller_path . "task_trap.inc.php");
include_once($controller_path . "trap_type.inc.php");

$TaskTrapObj = new TaskTrap();

if($request_type == "task_trap_list"){

    $where_arr = array();
    if(!empty($RES_PARA)){
        $iSiteId      = trim($RES_PARA['iSiteId']);
        $iTTId        = trim($RES_PARA['iTTId']);
        $vName        = trim($RES_PARA['vName']);
        $vTypeName    = trim($RES_PARA['vTypeName']);
        $iSRId        = trim($RES_PARA['iSRId']);
        $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"10";
        $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"0";
        $display_order = isset($RES_PARA['display_order'])?trim($RES_PARA['display_order']):"";
        $dir = isset($RES_PARA['dir'])?trim($RES_PARA['dir']):"";
    }
    if ($iTTId != "") {
        $where_arr[] = 'task_trap."iTTId"='.$iTTId ;
    }
    if ($iSiteId != "") {
        $where_arr[] = 'task_trap."iSiteId"='.$iSiteId ;
    }

    if ($vName != "") {
        $where_arr[] = "s.\"vName\" ILIKE '" . $vName . "%'";
    }

    if ($vTypeName != "") {
        $where_arr[] = "st.\"vTypeName\" ILIKE '" . $vTypeName . "%'";
    }

    if ($iSRId != "") {
        $where_arr[] = 'task_trap."iSRId"='.$iSRId ;
    }
    
    switch ($display_order) {
        case "0":
            $sortname = "task_trap.\"iTTId\"";
            break;
        case "1":
            $sortname = "s.\"vName\"";
            break;
        case "4":
            $sortname = "task_trap.\"dTrapPlaced\"";
            break;
        default:
            $sortname = "task_trap.\"iTTId\"";
            break;
    }

    //$sortname = "task_trap.\"iTTId\" desc";
    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $join_fieds_arr = array();
    $join_fieds_arr[] = 's."vName"';
    $join_fieds_arr[] = 's."vAddress1"';
    $join_fieds_arr[] = 's."vStreet"';
    $join_fieds_arr[] = 'st."vTypeName"';
    $join_fieds_arr[] = 'c."vCounty"';
    $join_fieds_arr[] = 'sm."vState"';
    $join_fieds_arr[] = 'cm."vCity"';
    $join_fieds_arr[] = 'tt."vTrapName"';
    $join_fieds_arr[] = "CONCAT(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") AS \"vContactName\"";
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN trap_type_mas tt on tt."iTrapTypeId" = task_trap."iTrapTypeId"';
    $join_arr[] = 'LEFT JOIN site_mas s on s."iSiteId" = task_trap."iSiteId"';
    $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $join_arr[] = 'LEFT JOIN sr_details sd on sd."iSRId" = task_trap."iSRId"';
    $join_arr[] = 'LEFT JOIN contact_mas on  contact_mas."iCId"= sd."iCId"';
    $TaskTrapObj->join_field = $join_fieds_arr;
    $TaskTrapObj->join = $join_arr;
    $TaskTrapObj->where = $where_arr;
    $TaskTrapObj->param['order_by'] = $sortname . " " . $dir;
    $TaskTrapObj->param['limit'] = $limit;
    $TaskTrapObj->setClause();
    $TaskTrapObj->debug_query = false;
    $rs_taskTrap = $TaskTrapObj->recordset_list();

    // Paging Total Records
    $total_record = $TaskTrapObj->recordset_total();
    // Paging Total Records
    
    $data = array();
    $ni = count($rs_taskTrap);
    if($ni > 0){
        for($i=0;$i<$ni;$i++){

        	$vSite = $rs_taskTrap[$i]['vName']."- PremiseID#".$rs_taskTrap[$i]['iSiteId'];
        	$vAddress =  $rs_taskTrap[$i]['vAddress1'].' '.$rs_taskTrap[$i]['vStreet'].' '.$rs_taskTrap[$i]['vCity'].', '.$rs_taskTrap[$i]['vState'].' '.$rs_taskTrap[$i]['vCounty'];

            //$vSiteName = $rs_taskTrap[$i]['iSiteId']." (".$rs_taskTrap[$i]['vName']."; ".$rs_taskTrap[$i]['vTypeName'].")";

            $srdisplay = ($rs_taskTrap[$i]['iSRId'] != "")?$rs_taskTrap[$i]['iSRId']." (".$rs_taskTrap[$i]['vContactName'].")":"";
                
            $data[] = array(
                "iTTId" => $rs_taskTrap[$i]['iTTId'],
                "iSiteId" =>  $rs_taskTrap[$i]['iSiteId'],
                "vName" => $vSite,
                "vAddress" => $vAddress,
                "iSRId" =>  $rs_taskTrap[$i]['iSRId'],
                "sr" => $srdisplay,
                "dTrapPlaced" => $rs_taskTrap[$i]['dTrapPlaced'],
                "dTrapCollected" => $rs_taskTrap[$i]['dTrapCollected'],
                "iTrapTypeId" => $rs_taskTrap[$i]['iTrapTypeId'],
                "vTrapName" => $rs_taskTrap[$i]['vTrapName'],
                 "bMalfunction" => $rs_taskTrap[$i]['bMalfunction'],
                "tNotes" => $rs_taskTrap[$i]['tNotes'],
                "iTechnicianId" => $rs_taskTrap[$i]['iTechnicianId']       
            );
        }
    }  
    $result = array('data' => $data , 'total_record' => $total_record);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}
else if($request_type == "task_trap_add"){
   //echo "<pre>";print_r($RES_PARA);exit;

   $TaskTrapObj->clear_variable();
   $insert_arr = array(
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        "iSiteId"           => $RES_PARA['iSiteId'],
        "iSRId"             => $RES_PARA['iSRId'],
        "dTrapPlaced"       => $RES_PARA['dTrapPlaced'],
        "dTrapCollected"    => $RES_PARA['dTrapCollected'],
        "iTrapTypeId"       => $RES_PARA['iTrapTypeId'],
        "bMalfunction"      => $RES_PARA['bMalfunction'],
        "tNotes"            => $RES_PARA['tNotes'],
        "iUserId"           => $RES_PARA['iUserId'],
        "iTechnicianId"     => $RES_PARA['iTechnicianId'],
    );

   $TaskTrapObj->insert_arr = $insert_arr;
   $TaskTrapObj->setClause();
   $iTTId = $TaskTrapObj->add_records();

   if($iTTId){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_ADD, "iTTId" => $iTTId);
   }else{
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
   }
}
else if($request_type == "task_trap_edit"){
   //echo "<pre>";print_r($RES_PARA);exit;

   $TaskTrapObj->clear_variable();
   $update_arr = array(
        "sessionId"         => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
        "iTTId"             => $RES_PARA['iTTId'],
        "iSiteId"           => $RES_PARA['iSiteId'],
        "iSRId"             => $RES_PARA['iSRId'],
        "dTrapPlaced"       => $RES_PARA['dTrapPlaced'],
        "dTrapCollected"    => $RES_PARA['dTrapCollected'],
        "iTrapTypeId"       => $RES_PARA['iTrapTypeId'],
        "bMalfunction"      => $RES_PARA['bMalfunction'],
        "tNotes"            => $RES_PARA['tNotes'],
        "iUserId"           => $RES_PARA['iUserId'],
        "iTechnicianId"     => $RES_PARA['iTechnicianId'],
    );

   $TaskTrapObj->update_arr = $update_arr;
   $TaskTrapObj->setClause();
   $rs_db = $TaskTrapObj->update_records();

   if($rs_db){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iTTId" => $RES_PARA['iTTId']);
   }else{
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
   }
}
else if($request_type == "task_trap_delete"){
   //echo "<pre>";print_r($RES_PARA);exit;
   $iTTId = $RES_PARA['iTTId'];
   
    $rs_db = $TaskTrapObj->delete_records($iTTId);

    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iTTId" => $iTTId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}
else if($request_type == "trap_type_dropdown"){
    //echo "<pre>";print_r($RES_PARA);exit;
    $TrapTypeObj = new TrapType();
    
    $rs_db = $TrapTypeObj->recordset_list();

    if($rs_db){
        $response_data = array("Code" => 200, "result" => $rs_db, "total_record" => count($rs_db));
    }else{
        $response_data = array("Code" => 500);
    }
}else if($request_type == "task_trap_setLabWorkCount"){
    
    $TaskTrapObj->clear_variable();
    $update_labwork_arr = array(
        "iTTId"                 => $RES_PARA['iTTId'],
        "bLabWorkComplete"      => $RES_PARA['bLabWorkComplete']
    );

    $TaskTrapObj->update_arr = $update_labwork_arr;
    $TaskTrapObj->setClause();
    $rs_db = $TaskTrapObj->update_labwork_records();
   
    if($rs_db){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "result" => $rs_db);
   }else{
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
   }
}
else {
   $r = HTTPStatus(400);
   $code = 1001;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 400 , "Message" => $message);
}
?>