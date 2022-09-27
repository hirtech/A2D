<?php
include_once($controller_path . "task_larval_surveillance.inc.php");
include_once($controller_path . "fieldmap.inc.php");
include_once($function_path."image.inc.php");

if($request_type == "task_larval_surveillance_list"){
   $where_arr = array();
   if(!empty($RES_PARA)){
      $iSiteId           = trim($RES_PARA['iSiteId']);
      $iTLSId            = trim($RES_PARA['iTLSId']);
      $vName             = trim($RES_PARA['vName']);
      $vTypeName         = trim($RES_PARA['vTypeName']);
      $iSRId             = trim($RES_PARA['iSRId']);
      $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"10";
      $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"0";
      $display_order = isset($RES_PARA['display_order'])?trim($RES_PARA['display_order']):"";
      $dir = isset($RES_PARA['dir'])?trim($RES_PARA['dir']):"";
   }
   if ($iTLSId != "") {
      $where_arr[] = 'task_larval_surveillance."iTLSId"='.$iTLSId ;
   }    
   if ($iSiteId != "") {
      $where_arr[] = 'task_larval_surveillance."iSiteId"='.$iSiteId ;
   }
   if ($vName != "") {
      $where_arr[] = "s.\"vName\" ILIKE '" . $vName . "%'";
   }
   if ($vTypeName != "") {
      $where_arr[] = "st.\"vTypeName\" ILIKE '" . $vTypeName . "%'";
   }
   if ($iSRId != "") {
      $where_arr[] = 'task_larval_surveillance."iSRId"='.$iSRId ;
   }

   switch ($display_order) {
      case "iTLSId":
         $sortname = "task_larval_surveillance.\"iTLSId\"";
         break;
      case "vName":
         $sortname = "s.\"vName\"";
         break;
      case "dDate":
         $sortname = "task_larval_surveillance.\"dDate\"";
         break;
      default:
         $sortname = "task_larval_surveillance.\"iTLSId\"";
         break;
   }

   //$sortname = "task_larval_surveillance.\"iTLSId\"";
          
   $TaskLarvalObj = new TaskLarvalSurveillance();

   $limit = "LIMIT ".$page_length." OFFSET ".$start."";
    //echo $sortname . " " . $dir;exit;
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
   $join_arr[] = 'LEFT JOIN site_mas s on s."iSiteId" = task_larval_surveillance."iSiteId"';
   $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
   $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
   $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
   $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
   $join_arr[] = 'LEFT JOIN sr_details sd on sd."iSRId" = task_larval_surveillance."iSRId"';
    $join_arr[] = 'LEFT JOIN contact_mas on  contact_mas."iCId"= sd."iCId"';
   $TaskLarvalObj->join_field = $join_fieds_arr;
   $TaskLarvalObj->join = $join_arr;
   $TaskLarvalObj->where = $where_arr;
   $TaskLarvalObj->param['order_by'] = $sortname . " " . $dir;
   $TaskLarvalObj->param['limit'] = $limit;
   $TaskLarvalObj->setClause();
   $TaskLarvalObj->debug_query = false;
   $rs_tasklarval = $TaskLarvalObj->recordset_list();
   // Paging Total Records
   $total_record = $TaskLarvalObj->recordset_total();
   // Paging Total Records

   $data = array();
   $ni = count($rs_tasklarval);
   if($ni > 0){
      for($i=0;$i<$ni;$i++){
         $vSite = $rs_tasklarval[$i]['vName']."- PremiseID#".$rs_tasklarval[$i]['iSiteId'];
         $vAddress =  $rs_tasklarval[$i]['vAddress1'].' '.$rs_tasklarval[$i]['vStreet'].' '.$rs_tasklarval[$i]['vCity'].', '.$rs_tasklarval[$i]['vState'].' '.$rs_tasklarval[$i]['vCounty'];

         $stages = '';
         $stages_arr = array();

         if($rs_tasklarval[$i]['iGenus'] == '0'){
            $iGenus = 'N/A';
         }
         else if($rs_tasklarval[$i]['iGenus'] == '1'){
            $iGenus = 'Ae.';
         }
         else if($rs_tasklarval[$i]['iGenus'] == '2'){
            $iGenus = 'An.';
         }
         else if($rs_tasklarval[$i]['iGenus'] == '3'){
            $iGenus = 'Cs.';
         }
         else if($rs_tasklarval[$i]['iGenus'] == '4'){
            $iGenus = 'Cx.';
         }

         if($rs_tasklarval[$i]['iCount'] > 0){
            $stages_arr[] = $iGenus." ".$rs_tasklarval[$i]['iCount'];
         }else{
            $stages_arr[] = $iGenus;
         }

         if($rs_tasklarval[$i]['bEggs'] == "t")
            $stages_arr[] = 'E';
         if($rs_tasklarval[$i]['bPupae'] == "t")
            $stages_arr[] = 'P';
         if($rs_tasklarval[$i]['bInstar1'] == "t")
            $stages_arr[] ='I1';
         if($rs_tasklarval[$i]['bInstar2'] == "t")
            $stages_arr[] = 'I2';
         if($rs_tasklarval[$i]['bInstar3'] == "t")
            $stages_arr[] = 'I3';
         if($rs_tasklarval[$i]['bInstar4'] == "t")
            $stages_arr[] = 'I4';
         if($rs_tasklarval[$i]['bAdult'] == "t")
            $stages_arr[] = 'A';

         
         if(count($stages_arr) > 0){
            $stages .= ' | Species 1: '.implode(", ", $stages_arr);
         }

         
         $stages_arr = array();

         if($rs_tasklarval[$i]['iGenus2'] == '0'){
            $iGenus2 = 'N/A';
         }
         else if($rs_tasklarval[$i]['iGenus2'] == '1'){
            $iGenus2 = 'Ae.';
         }
         else if($rs_tasklarval[$i]['iGenus2'] == '2'){
            $iGenus2 = 'An.';
         }
         else if($rs_tasklarval[$i]['iGenus2'] == '3'){
            $iGenus2 = 'Cs.';
         }
         else if($rs_tasklarval[$i]['iGenus2'] == '4'){
            $iGenus2 = 'Cx.';
         }

         if($rs_tasklarval[$i]['iCount2'] > 0){
            $stages_arr[] = $iGenus2." ".$rs_tasklarval[$i]['iCount2'];
         }else{
            $stages_arr[] = $iGenus2;
         }


         if($rs_tasklarval[$i]['bEggs2'] == "t")
            $stages_arr[] = 'E';
         if($rs_tasklarval[$i]['bPupae2'] == "t")
            $stages_arr[] = 'P';
         if($rs_tasklarval[$i]['bInstar12'] == "t")
            $stages_arr[] ='I1';
         if($rs_tasklarval[$i]['bInstar22'] == "t")
            $stages_arr[] = 'I2';
         if($rs_tasklarval[$i]['bInstar32'] == "t")
            $stages_arr[] = 'I3';
         if($rs_tasklarval[$i]['bInstar42'] == "t")
            $stages_arr[] = 'I4';
         if($rs_tasklarval[$i]['bAdult2'] == "t")
            $stages_arr[] = 'A';

         if(count($stages_arr) > 0){
            $stages .= ' | Species 2: '.implode(", ", $stages_arr);
         }

         $summary = 'Dips: '.$rs_tasklarval[$i]['iDips'].', Avg Larvae: '.$rs_tasklarval[$i]['rAvgLarvel'].$stages.$str.'<br/>Added Date: '.date_getDateTimeDDMMYYYYHHMMSS($rs_tasklarval[$i]['dAddedDate']);

         
         if($rs_tasklarval[$i]['dStartDate'] != '')
         {
             $rs_tasklarval[$i]['dStartTime'] = date("H:i", strtotime($rs_tasklarval[$i]['dStartDate']));
         }
         else
         {
             $rs_tasklarval[$i]['dStartTime'] = date("H:i", time());
         }

          if($rs_tasklarval[$i]['dEndDate'] != '')
         {
             $rs_tasklarval[$i]['dEndTime'] = date("H:i", strtotime($rs_tasklarval[$i]['dEndDate']));
         }
         else
         {
             $rs_tasklarval[$i]['dEndTime'] = date("H:i", strtotime(date('Y-m-d H:i:s')." +10 minutes"));
         }

         $vSiteName = $rs_tasklarval[$i]['iSiteId']." (".$rs_tasklarval[$i]['vName']."; ".$rs_tasklarval[$i]['vTypeName'].")";

         $srdisplay = ($rs_tasklarval[$i]['iSRId'] != "0" && $rs_tasklarval[$i]['iSRId'] != "" )?$rs_tasklarval[$i]['iSRId']." (".$rs_tasklarval[$i]['vContactName'].")":"";

         $data[] = array(
               "iTLSId" => $rs_tasklarval[$i]['iTLSId'],
               "iSiteId" => $rs_tasklarval[$i]['iSiteId'],
               "vName" => $vSite,
               "vAddress" => $vAddress,
               "iSRId" => $rs_tasklarval[$i]['iSRId'],
               "sr" => $srdisplay,
               "dDate" => $rs_tasklarval[$i]['dDate'],
               "dStartDate" => $rs_tasklarval[$i]['dStartDate'],
               "dStartTime" => $rs_tasklarval[$i]['dStartTime'],
               "dEndDate" => $rs_tasklarval[$i]['dEndDate'],
               "dEndTime" => $rs_tasklarval[$i]['dEndTime'],
               "iDips" => $rs_tasklarval[$i]['iDips'],
               "iGenus" => $rs_tasklarval[$i]['iGenus'],
               "iCount" => $rs_tasklarval[$i]['iCount'],
               "bEggs" => $rs_tasklarval[$i]['bEggs'],
               "bInstar1" => $rs_tasklarval[$i]['bInstar1'],
               "bInstar2" => $rs_tasklarval[$i]['bInstar2'],
               "bInstar3" => $rs_tasklarval[$i]['bInstar3'],
               "bInstar4" => $rs_tasklarval[$i]['bInstar4'],
               "bPupae" => $rs_tasklarval[$i]['bPupae'],
               "bAdult" => $rs_tasklarval[$i]['bAdult'],
               "iGenus2" => $rs_tasklarval[$i]['iGenus2'],
               "iCount2" => $rs_tasklarval[$i]['iCount2'],
               "bEggs2" => $rs_tasklarval[$i]['bEggs2'],
               "bInstar12" => $rs_tasklarval[$i]['bInstar12'],
               "bInstar22" => $rs_tasklarval[$i]['bInstar12'],
               "bInstar32" => $rs_tasklarval[$i]['bInstar12'],
               "bInstar42" => $rs_tasklarval[$i]['bInstar12'],
               "bPupae2" => $rs_tasklarval[$i]['bPupae2'],
               "bAdult2" => $rs_tasklarval[$i]['bAdult2'],
               'rAvgLarvel' => $rs_tasklarval[$i]['rAvgLarvel'],
               "Summary" => $summary,
               "tNotes" => $rs_tasklarval[$i]['tNotes'],
               "iTechnicianId" =>$rs_tasklarval[$i]['iTechnicianId'],
               'dAddedDate' => $rs_tasklarval[$i]['dAddedDate'],
               'dModifiedDate' => $rs_tasklarval[$i]['dModifiedDate']
         );
      }
   }   
    
   $result = array('data' => $data , 'total_record' => $total_record);

   $rh = HTTPStatus(200);
   $code = 2000;
   $message = api_getMessage($req_ext, constant($code));
   $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}
else if($request_type == "task_larval_surveillance_add"){
   //echo "<pre>";print_r($RES_PARA);exit;
   $insert_arr = array(
      "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
      "iSiteId"       => $RES_PARA['iSiteId'],
      "iSRId"         => $RES_PARA['iSRId'],
      "dDate"         => $RES_PARA['dDate'],
      "dStartDate"    => $RES_PARA['dStartDate'],
      "dEndDate"      => $RES_PARA['dEndDate'],
      "iDips"         => $RES_PARA['iDips'],
      "iCount"        => $RES_PARA['iCount'],
      "iCount2"       => $RES_PARA['iCount2'],
      "rAvgLarvel"    => $RES_PARA['rAvgLarvel'],
      "iGenus"        => $RES_PARA['iGenus'],
      "bEggs"         => $RES_PARA['bEggs'],
      "bInstar1"      => $RES_PARA['bInstar1'],
      "bInstar2"      => $RES_PARA['bInstar2'],
      "bInstar3"      => $RES_PARA['bInstar3'],
      "bInstar4"      => $RES_PARA['bInstar4'],
      "bPupae"        => $RES_PARA['bPupae'],
      "bAdult"        => $RES_PARA['bAdult'],
      "iGenus2"       => $RES_PARA['iGenus2'],
      "bEggs2"        => $RES_PARA['bEggs2'],
      "bInstar12"     => $RES_PARA['bInstar12'],
      "bInstar22"     => $RES_PARA['bInstar22'],
      "bInstar32"     => $RES_PARA['bInstar32'],
      "bInstar42"     => $RES_PARA['bInstar42'],
      "bPupae2"       => $RES_PARA['bPupae2'],
      "bAdult2"       => $RES_PARA['bAdult2'],
      "tNotes"       => $RES_PARA['tNotes'],
      "iUserId"       => $RES_PARA['iUserId'],
      "iTechnicianId" => $RES_PARA['iTechnicianId'],
   );

   $TaskLarvalObj = new TaskLarvalSurveillance();
   $TaskLarvalObj->insert_arr = $insert_arr;
   $TaskLarvalObj->setClause();
   $iTLSId = $TaskLarvalObj->add_records();

   if($iTLSId){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_ADD, "iTLSId" => $iTLSId);
   }else{
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
   }
}
else if($request_type == "task_larval_surveillance_edit"){
   //echo "<pre>";print_r($RES_PARA);exit;
   
   $update_arr = array(
      "sessionId"     => $_SESSION["we_api_session_id" . $admin_panel_session_suffix],
      "iTLSId"        => $RES_PARA['iTLSId'],
      "iSiteId"       => $RES_PARA['iSiteId'],
      "iSRId"         => $RES_PARA['iSRId'],
      "dDate"         => $RES_PARA['dDate'],
      "dStartDate"    => $RES_PARA['dStartDate'],
      "dEndDate"      => $RES_PARA['dEndDate'],
      "iDips"         => $RES_PARA['iDips'],
      "iCount"        => $RES_PARA['iCount'],
      "iCount2"       => $RES_PARA['iCount2'],
      "rAvgLarvel"    => $RES_PARA['rAvgLarvel'],
      "iGenus"        => $RES_PARA['iGenus'],
      "bEggs"         => $RES_PARA['bEggs'],
      "bInstar1"      => $RES_PARA['bInstar1'],
      "bInstar2"      => $RES_PARA['bInstar2'],
      "bInstar3"      => $RES_PARA['bInstar3'],
      "bInstar4"      => $RES_PARA['bInstar4'],
      "bPupae"        => $RES_PARA['bPupae'],
      "bAdult"        => $RES_PARA['bAdult'],
      "iGenus2"       => $RES_PARA['iGenus2'],
      "bEggs2"        => $RES_PARA['bEggs2'],
      "bInstar12"     => $RES_PARA['bInstar12'],
      "bInstar22"     => $RES_PARA['bInstar22'],
      "bInstar32"     => $RES_PARA['bInstar32'],
      "bInstar42"     => $RES_PARA['bInstar42'],
      "bPupae2"       => $RES_PARA['bPupae2'],
      "bAdult2"       => $RES_PARA['bAdult2'],
      "tNotes"        => $RES_PARA['tNotes'],
      "iUserId"       => $RES_PARA['iUserId'],
      "iTechnicianId" => $RES_PARA['iTechnicianId'],
  );

   $TaskLarvalObj = new TaskLarvalSurveillance();
   $TaskLarvalObj->update_arr = $update_arr;
   $TaskLarvalObj->setClause();
   $rs_db = $TaskLarvalObj->update_records();

   if($rs_db){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iTLSId" => $RES_PARA['iTLSId']);
   }else{
      $r = HTTPStatus(500);
      $response_data = array("Code" => 500 , "Message" => MSG_UPDATE_ERROR);
   }
}
else if($request_type == "task_larval_surveillance_delete"){
   //echo "<pre>";print_r($RES_PARA);exit;
   
   $iTLSId = $RES_PARA['iTLSId'];

   $TaskLarvalObj = new TaskLarvalSurveillance();
   $rs_db = $TaskLarvalObj->delete_records($iTLSId);

   if($rs_db){
      $rh = HTTPStatus(200);
      $code = 2000;
      $message = api_getMessage($req_ext, constant($code));
      $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iTLSId" => $iTLSId);
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