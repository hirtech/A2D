<?php
include_once($controller_path . "task_landing_rate.inc.php");
include_once($controller_path . "fieldmap.inc.php");
include_once($function_path."image.inc.php");

if($request_type == "task_landing_rate_list"){
   $where_arr = array();

   if(!empty($RES_PARA)){
        $iSiteId            = trim($RES_PARA['iSiteId']);
        $iTLRId             = trim($RES_PARA['iTLRId']);
        $vName              = trim($RES_PARA['vName']);
        $vTypeName          = trim($RES_PARA['vTypeName']);
        $iSRId              = trim($RES_PARA['iSRId']);
        $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"10";
        $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"0";
        $display_order = isset($RES_PARA['display_order'])?trim($RES_PARA['display_order']):"";
        $dir = isset($RES_PARA['dir'])?trim($RES_PARA['dir']):"";
    }
    if ($iTLRId != "") {
        $where_arr[] = 'task_landing_rate."iTLRId"='.$iTLRId ;
    }
    
    if ($iSiteId != "") {
        $where_arr[] = 'task_landing_rate."iSiteId"='.$iSiteId ;
    }

    if ($vName != "") {
        $where_arr[] = "s.\"vName\" ILIKE '" . $vName . "%'";
    }

    if ($vTypeName != "") {
        $where_arr[] = "st.\"vTypeName\" ILIKE '" . $vTypeName . "%'";
    }

    if ($iSRId != "") {
        $where_arr[] = 'task_landing_rate."iSRId"='.$iSRId ;
    }

    switch ($display_order) {
        case "0":
            $sortname = "task_landing_rate.\"iTLRId\"";
            break;
        case "1":
            $sortname = "s.\"vName\"";
            break;
        case "4":
            $sortname = "task_landing_rate.\"dDate\"";
            break;
        default:
            $sortname = "task_landing_rate.\"iTLRId\"";
            break;
    }

   //$sortname = "task_landing_rate.\"iTLRId\" desc";
            
   $TaskLandingRateObj = new TaskLandingRate();

   $limit = "LIMIT ".$page_length." OFFSET ".$start."";
   
   $join_fieds_arr = array();
   $join_fieds_arr[] = 's."vName"';
   $join_fieds_arr[] = 's."vAddress1"';
   $join_fieds_arr[] = 's."vStreet"';
   $join_fieds_arr[] = 'st."vTypeName"';
   $join_fieds_arr[] = 'c."vCounty"';
   $join_fieds_arr[] = 'sm."vState"';
   $join_fieds_arr[] = 'cm."vCity"';
   $join_fieds_arr[] = "CONCAT(contact_mas.\"vFirstName\", ' ', contact_mas.\"vLastName\") AS \"vContactName\"";
   $join_arr = array();
   $join_arr[] = 'LEFT JOIN site_mas s on s."iSiteId" = task_landing_rate."iSiteId"';
   $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
   $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
   $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
   $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
   $join_arr[] = 'LEFT JOIN sr_details sd on sd."iSRId" = task_landing_rate."iSRId"';
   $join_arr[] = 'LEFT JOIN contact_mas on  contact_mas."iCId"= sd."iCId"';
   $TaskLandingRateObj->join_field = $join_fieds_arr;
   $TaskLandingRateObj->join = $join_arr;
   $TaskLandingRateObj->where = $where_arr;
   $TaskLandingRateObj->param['order_by'] = $sortname . " " . $dir;
   $TaskLandingRateObj->param['limit'] = $limit;
   $TaskLandingRateObj->setClause();
   $TaskLandingRateObj->debug_query = false;
   $rs_taskland = $TaskLandingRateObj->recordset_list();
   // Paging Total Records
   $total_record = $TaskLandingRateObj->recordset_total();
   // Paging Total Records

   $data = array();
   $ni = count($rs_taskland);

   if($ni > 0){
      for($i=0;$i<$ni;$i++){
         $TaskLandingRateObj->clear_variable();
         $where_arr = array();
         $join_fieds_arr = array();
         $join_arr  = array();
         $where_arr[] = "task_landing_rate_species.\"iTLRId\"='".$rs_taskland[$i]['iTLRId']."'";
         $TaskLandingRateObj->where = $where_arr;
         $TaskLandingRateObj->param['order_by'] = "task_landing_rate_species.\"iTLRId\"";
         $TaskLandingRateObj->setClause();
         $rs_species_arr = $TaskLandingRateObj->task_landing_rate_species_list();
         
         $iMspeciesIds = array();
         if(!empty($rs_species_arr)) {
             $sai = count($rs_species_arr);
             for($sa=0; $sa<$sai; $sa++){
                 $iMspeciesIds[$sa] = $rs_species_arr[$sa]['iMSpeciesId'];
             }
         }

         $iMSpeciesId = '';
         if(!empty($iMspeciesIds)){
             $iMSpeciesId = implode("|||", $iMspeciesIds);
         }
         $vSite = $rs_taskland[$i]['vName']."- PremiseID#".$rs_taskland[$i]['iSiteId'];
         $vAddress =  $rs_taskland[$i]['vAddress1'].' '.$rs_taskland[$i]['vStreet'].' '.$rs_taskland[$i]['vCity'].', '.$rs_taskland[$i]['vState'].' '.$rs_taskland[$i]['vCounty'];

         
         if($rs_taskland[$i]['dStartDate'] != '')
         {
             $rs_taskland[$i]['dStartTime'] = date("H:i", strtotime($rs_taskland[$i]['dStartDate']));
         }
         else
         {
             $rs_taskland[$i]['dStartTime'] = date("H:i", time());
         }

          if($rs_taskland[$i]['dEndDate'] != '')
         {
             $rs_taskland[$i]['dEndTime'] = date("H:i", strtotime($rs_taskland[$i]['dEndDate']));
         }
         else
         {
             $rs_taskland[$i]['dEndTime'] = date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes"));
         }

         $vSiteName = $rs_taskland[$i]['iSiteId']." (".$rs_taskland[$i]['vName']."; ".$rs_taskland[$i]['vTypeName'].")";

         $srdisplay = ($rs_taskland[$i]['iSRId'] != "" && $rs_taskland[$i]['iSRId'] != 0)?$rs_taskland[$i]['iSRId']." (".$rs_taskland[$i]['vContactName'].")":"";
         
         $data[] = array(
                "iTLRId" => $rs_taskland[$i]['iTLRId'],
                "vName" => gen_strip_slash($vSite),
                "iSiteId" => $rs_taskland[$i]['iSiteId'],
                "vAddress" => $vAddress,
                "iSRId" => $rs_taskland[$i]['iSRId'],
                "sr" => $srdisplay,
                "dDate" => $rs_taskland[$i]['dDate'],
                "dStartDate" => $rs_taskland[$i]['dStartDate'],
                "dStartTime" => $rs_taskland[$i]['dStartTime'],
                "dEndDate" => $rs_taskland[$i]['dEndDate'],
                "dEndDate" => $rs_taskland[$i]['dEndTime'],
                "vMaxLandingRate" => $rs_taskland[$i]['vMaxLandingRate'],
                "tNotes" => $rs_taskland[$i]['tNotes'],
                "iMSpeciesId" => $iMSpeciesId,
                "tNotes" => $rs_taskland[$i]['tNotes'],
                "iTechnicianId" => $rs_taskland[$i]['iTechnicianId']     
            );
      }
   }
    
   $result = array('data' => $data , 'total_record' => $total_record);

   $rh = HTTPStatus(200);
   $code = 2000;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 200, "Message" => $message, "result" => $result);   
}else if($request_type == "get_task_landing_rate_species"){
   //task_landing_rate_species_list
   $iTLRSId = (isset($RES_PARA['iTLRSId']))?trim($RES_PARA['iTLRSId']):"";
   $iTLRId = (isset($RES_PARA['iTLRId']))?trim($RES_PARA['iTLRId']):"";
   $iMSpeciesId = (isset($RES_PARA['iMSpeciesId']))?trim($RES_PARA['iMSpeciesId']):"";
  
   $where_arr =array();
   $join_fieds_arr = array();
   $join_arr = array();
   if((isset($iTLRSId) && $iTLRSId != "")){
      $where_arr[] = " \"iTLRSId\" =" . $iTLRSId;
   }
   if((isset($iTLRId) && $iTLRId != "")){
      $where_arr[] = " \"iTLRId\" = " . $iTLRId;
   }
   if((isset($iMSpeciesId) && $iMSpeciesId != "")){
      $where_arr[] = " \"iMSpeciesId\" =" . $iMSpeciesId;
   }
   
   
   $TaskLandRateObj = new TaskLandingRate();
   $TaskLandRateObj->clear_variable();
   $TaskLandRateObj->join_field = $join_fieds_arr;
   $TaskLandRateObj->join = $join_arr;
   $TaskLandRateObj->where = $where_arr;
   $TaskLandRateObj->setClause();
   $rs_taskland = $TaskLandRateObj->task_landing_rate_species_list();
      
   $cnt = count($rs_taskland);

   $data = array();
   if(!empty($rs_taskland)){
      $data= $rs_taskland;
   }
   $total_record = count($data);

   $result = array('total_record' => $total_record, 'data' => $data );
   $rh = HTTPStatus(200);
   $code = 2000;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}
else if($request_type == "task_landing_rate_add"){
   //echo "<pre>";print_r($RES_PARA);exit;

   $dEndDate = $RES_PARA['dEndDate'];
   $dStartDate = $RES_PARA['dStartDate'];
   if(isset($RES_PARA['dEndTime']) && $RES_PARA['dEndTime'] != ""){
      $dEndDate = $RES_PARA['dDate']." ".$RES_PARA['dEndTime'];
   }
   if(isset($RES_PARA['dStartTime']) && $RES_PARA['dStartTime'] != ""){
     $dEndDate = $RES_PARA['dDate']." ".$RES_PARA['dStartTime'];
   }
    
   $insert_arr = array(
      "sessionId"             => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
      "iSiteId"               => $RES_PARA['iSiteId'],
      "iSRId"                 => $RES_PARA['iSRId'],
      "dDate"                 => $RES_PARA['dDate'],
      "dStartDate"            => $dStartDate,
      "dEndDate"              => $dEndDate,
      "vMaxLandingRate"       => $RES_PARA['vMaxLandingRate'],
      "iMSpeciesId"           => $RES_PARA['iMSpeciesId'],
      "tNotes"                => $RES_PARA['tNotes'],
      "iUserId"               => $RES_PARA['iUserId'],
      "iTechnicianId"         => $RES_PARA['iTechnicianId'],
    );

   $TaskLandingRateObj = new TaskLandingRate();
   $TaskLandingRateObj->insert_arr = $insert_arr;
   $TaskLandingRateObj->setClause();
   $iTLRId = $TaskLandingRateObj->add_records();

   if($iTLRId){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_ADD, "iTLRId" => $iTLRId);
   }else{
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
   }
}
else if($request_type == "task_landing_rate_edit"){
   //echo "<pre>";print_r($RES_PARA);exit;

   $dEndDate = $RES_PARA['dEndDate'];
   $dStartDate = $RES_PARA['dStartDate'];
   if(isset($RES_PARA['dEndTime']) && $RES_PARA['dEndTime'] != ""){
      $dEndDate = $RES_PARA['dDate']." ".$RES_PARA['dEndTime'];
   }
   if(isset($RES_PARA['dStartTime']) && $RES_PARA['dStartTime'] != ""){
     $dEndDate = $RES_PARA['dDate']." ".$RES_PARA['dStartTime'];
   }
    
   $update_arr = array(
      "sessionId"             => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
      "iTLRId"                => $RES_PARA['iTLRId'],
      "iSiteId"               => $RES_PARA['iSiteId'],
      "iSRId"                 => $RES_PARA['iSRId'],
      "dDate"                 => $RES_PARA['dDate'],
      "dStartDate"            => $dStartDate,
      "dEndDate"              => $dEndDate,
      "vMaxLandingRate"       => $RES_PARA['vMaxLandingRate'],
      "iMSpeciesId"           => $RES_PARA['iMSpeciesId'],
      "tNotes"                => $RES_PARA['tNotes'],
      "iUserId"               => $RES_PARA['iUserId'],
      "iTechnicianId"         => $RES_PARA['iTechnicianId'],
    );

   $TaskLandingRateObj = new TaskLandingRate();
   $TaskLandingRateObj->update_arr = $update_arr;
   $TaskLandingRateObj->setClause();
   $rs_db = $TaskLandingRateObj->update_records();

   if($rs_db){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iTLRId" => $RES_PARA['iTLRId']);
   }else{
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
   }
}
else if($request_type == "task_landing_rate_delete"){
   //echo "<pre>";print_r($RES_PARA);exit;
   
   $iTLRId = $RES_PARA['iTLRId'];

   $TaskLandingRateObj = new TaskLandingRate();
   $rs_db = $TaskLandingRateObj->delete_records($iTLRId);

   if($rs_db){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iTLRId" => $iTLRId);
   }else{
      $r = HTTPStatus(500);
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