<?php

include_once($controller_path . "task_mosquito_pool.inc.php");
include_once($controller_path . "task_mosquito_pool_result.inc.php");
include_once($controller_path . "task_mosquito_count.inc.php");
include_once($controller_path . "mosquito_species.inc.php");
include_once($controller_path . "task_trap.inc.php");

$TaskMosquitoPoolObj = new TaskMosquitoPool();
$TaskMosquitoPoolResultObj = new TaskMosquitoPoolResult();
$TaskMosquitoCountObj = new TaskMosquitoCount();
$MosquitoSpeciesObj = new MosquitoSpecies();
$TaskTrapObj = new TaskTrap();

if($request_type == "task_mosquito_pool_list"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();
    //echo"<pre>";print_r($request_type);exit;
    if(!empty($RES_PARA)){
        $iPremiseId            = $RES_PARA['iPremiseId'];
        $iTMPId             = $RES_PARA['iTMPId'];
        $vName              = $RES_PARA['vName'];
        $vTypeName           = $RES_PARA['vTypeName'];
        $vPool               = $RES_PARA['vPool'];
        $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"20";
        $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"0";
        $display_order = isset($RES_PARA['display_order'])?trim($RES_PARA['display_order']):"";
        $dir = isset($RES_PARA['dir'])?trim($RES_PARA['dir']):"";
    }

    if ($iTMPId != "") {
        $where_arr[] = 'task_mosquito_pool."iTMPId"='.$iTMPId ;
    }
    
    if ($iPremiseId != "") {
        $where_arr[] = 'task_trap."iPremiseId"='.$iPremiseId ;
    }

    if ($vName != "") {
        $where_arr[] = "s.\"vName\" ILIKE '" . $vName . "%'";
    }

    if ($vTypeName != "") {
        $where_arr[] = "st.\"vTypeName\" ILIKE '" . $vTypeName . "%'";
    }
    if ($vPool != "") {
        $where_arr[] = " task_mosquito_pool.\"vPool\" ILIKE '" . $vPool . "%'";
    }

    switch ($display_order) {
        case "0":
            $sortname = 'task_mosquito_pool."iTMPId"';
            break;
        case "1":
            $sortname = 's."vName"';
            break;
        case "3":
            $sortname = 'task_trap."dTrapPlaced"';
            break;
        case "4":
            $sortname = 'task_trap."dTrapCollected"';
            break;
        case "5":
            $sortname = 'tt."vTrapName"';
            break;
        case "7":
            $sortname = 'task_mosquito_pool."vPool"';
            break;
        default:
            $sortname = 'task_mosquito_pool."iTMPId"';
            break;
    }

    //$sortname = "task_trap.\"iTTId\" desc";

    $limit = "LIMIT ".$page_length." OFFSET ".$start.""; 

    //echo $sortname . " " . $dir;exit;
    
    $join_fieds_arr[] = 's."vName"';
    $join_fieds_arr[] = 's."vAddress1"';
    $join_fieds_arr[] = 's."vStreet"';
    $join_fieds_arr[] = 'st."vTypeName"';
    $join_fieds_arr[] = 'c."vCounty"';
    $join_fieds_arr[] = 'sm."vState"';
    $join_fieds_arr[] = 'cm."vCity"';
    $join_fieds_arr[] = 'tt."vTrapName"';
    $join_fieds_arr[] = 'task_trap."iPremiseId"';
    $join_fieds_arr[] = 'task_trap."iSRId"';
    $join_fieds_arr[] = 'task_trap."dTrapPlaced"';
    $join_fieds_arr[] = 'task_trap."dTrapCollected"';
    $join_fieds_arr[] = 'task_trap."iTrapTypeId"';
    $join_fieds_arr[] = 'task_trap."tNotes"';
    $join_arr[] = 'LEFT JOIN task_trap  on task_trap."iTTId" = task_mosquito_pool."iTTId"';
    $join_arr[] = 'LEFT JOIN trap_type_mas tt on tt."iTrapTypeId" = task_trap."iTrapTypeId"';
    $join_arr[] = 'LEFT JOIN premise_mas s on s."iPremiseId" = task_trap."iPremiseId"';
    $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $TaskMosquitoPoolObj->join_field = $join_fieds_arr;
    $TaskMosquitoPoolObj->join = $join_arr;
    $TaskMosquitoPoolObj->where = $where_arr;
    $TaskMosquitoPoolObj->param['order_by'] = $sortname . " " . $dir;
    $TaskMosquitoPoolObj->param['limit'] = $limit;
    $TaskMosquitoPoolObj->setClause();
    $TaskMosquitoPoolObj->debug_query = false;
    $rs_data = $TaskMosquitoPoolObj->recordset_list();

    
    // Paging Total Records
    $total = $TaskMosquitoPoolObj->recordset_total();
    // Paging Total Records

    $entry = array();
    $ni = count($rs_data);

    if($ni > 0){
        
        for($i=0;$i<$ni;$i++){ 

            //get pool result data
            $TaskMosquitoPoolResultObj->clear_variable();
            $where_arr = array();
            $join_fieds_arr = array();          
            $join_arr = array();
            $join_fieds_arr[] = 'result."vResult"';
            $join_fieds_arr[] = 'agent_mosquito."vTitle"';
            $join_arr[] = 'LEFT JOIN agent_mosquito on agent_mosquito."iAMId" = task_mosquito_pool_result."iAMId"';
            $join_arr[] = 'LEFT JOIN result on result."iResultId" = task_mosquito_pool_result."iResultId"';
            $TaskMosquitoPoolResultObj->join_field = $join_fieds_arr;
            $TaskMosquitoPoolResultObj->join = $join_arr;
            $where_arr[] = 'task_mosquito_pool_result."iTMPId"='.$rs_data[$i]['iTMPId'];
            $TaskMosquitoPoolResultObj->where = $where_arr;
            $TaskMosquitoPoolResultObj->param['order_by'] = "task_mosquito_pool_result.\"iTMPRId\" DESC";
            $TaskMosquitoPoolResultObj->param['limit'] = 0;
            $TaskMosquitoPoolResultObj->setClause();
            $rs_tmrdetails = $TaskMosquitoPoolResultObj->recordset_list();


            $test_result = "";
            $ti = count($rs_tmrdetails);
            $positive = 0;
            if($ti > 0){
                
                for($t=0;$t<$ti;$t++){                  
                    if($rs_tmrdetails[$t]['vResult'] != "")
                        $test_result .= $rs_tmrdetails[$t]['vTitle']." (".$rs_tmrdetails[$t]['vResult'].")<br />";
                    else
                        $test_result .= $rs_tmrdetails[$t]['vTitle']."<br />";

                    if($rs_tmrdetails[$t]['vResult'] == 'Positive'){
                        $positive++;
                    }
                }   
            }
            
            if($test_result != "")
                $test_result = substr($test_result, 0, -6);

            $vSite = $rs_data[$i]['vName']."- PremiseID#".$rs_data[$i]['iPremiseId'];
            $vAddress =  $rs_data[$i]['vAddress1'].' '.$rs_data[$i]['vStreet'].' '.$rs_data[$i]['vCity'].', '.$rs_data[$i]['vState'].' '.$rs_data[$i]['vCounty'];

            $vSiteName = $rs_data[$i]['iPremiseId']." (".$rs_data[$i]['vName']."; ".$rs_data[$i]['vTypeName'].")";

            $data[] = array(
                "iTMPId" => $rs_data[$i]['iTMPId'],
                "iTTId" => $rs_data[$i]['iTTId'],
                "vName" => $vSite,
                "vAddress" => $vAddress,
                "dTrapPlaced" =>  $rs_data[$i]['dTrapPlaced'],
                "dTrapCollected" =>  $rs_data[$i]['dTrapCollected'],
                "vTrapName" =>  $rs_data[$i]['vTrapName'],
                "tNotes" => $rs_data[$i]['tNotes'],
                "vPool" =>  $rs_data[$i]['vPool'],
                "result" => $test_result,
                "iNumberinPool" => $rs_data[$i]['iNumberinPool'],
                "bLabWorkComplete" =>($rs_data[$i]['bLabWorkComplete']=='t')?'True':($rs_data[$i]['bLabWorkComplete']=='f'?"False":"")
       
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}
else if($request_type == "task_mosquito_count_list"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();
    //echo"<pre>";print_r($request_type);exit;
    if(!empty($RES_PARA)){
        $iTTId            = $RES_PARA['iTTId'];
        $iMSpeciesId      = $RES_PARA['iMSpeciesId'];
        $iMaleCount       = $RES_PARA['iMaleCount'];
        $iFemaleCount     = $RES_PARA['iFemaleCount'];
        $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"20";
        $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"0";
        $display_order = isset($RES_PARA['display_order'])?trim($RES_PARA['display_order']):"";
        $dir = isset($RES_PARA['dir'])?trim($RES_PARA['dir']):"";
    }

    if ($iTTId != "") {
        $where_arr[] = 'task_mosquito_count."iTTId" = '.$iTTId ;
    }
    
    if ($iMSpeciesId != "") {
        $where_arr[] = 'task_mosquito_count."iMSpeciesId" = '.$iMSpeciesId;
    }

    if ($iMaleCount != "") {
        $where_arr[] = "task_mosquito_count.\"iMaleCount\" = " . $iMaleCount ;
    }

    if ($iFemaleCount != "") {
        $where_arr[] = "task_mosquito_count.\"iFemaleCount\" = " . $iFemaleCount ;
    }

    switch ($display_order) {
        case "iTMCId":
            $sortkey = "iTMCId";
            break;
        case "Male":
            $sortkey = "Male";
            break;
        case "Female":
            $sortkey = "Female";
            break;
            break;
        default: 
            $sortkey = "Species";
    }

    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr  = array();
    $limit = "LIMIT ".$page_length." OFFSET ".$start ;
    $sortname = 'mosquito_species_mas."tDescription"';

    $where_arr[] = '"iStatus" =  1';
    $MosquitoSpeciesObj->join_field = $join_fieds_arr;
    $MosquitoSpeciesObj->join = $join_arr;
    $MosquitoSpeciesObj->where = $where_arr;
    $MosquitoSpeciesObj->param['order_by'] =$sortname;
    $MosquitoSpeciesObj->param['limit'] = $limit; 
    $MosquitoSpeciesObj->setClause();
    $rs_data = $MosquitoSpeciesObj->recordset_list();
    $total = $MosquitoSpeciesObj->recordset_total();
    
    $join_fieds_arr = array();
    $where_arr  = array();
    $join_arr = array();
    $where_arr[] = '"iTTId"='.$iTTId ;
    $TaskMosquitoCountObj->join_field = $join_fieds_arr;
    $TaskMosquitoCountObj->join = $join_arr;
    $TaskMosquitoCountObj->where = $where_arr;
    $TaskMosquitoCountObj->setClause();
    $TaskMosquitoCountObj->debug_query = false;
    $rs_mosq_data = $TaskMosquitoCountObj->recordset_list();
    $cnt = count($rs_mosq_data);
    $mosq_data =array();
    if($cnt >0){
        foreach($rs_mosq_data as $k => $val){
            $mosq_data[$val['iMSpeciesId']] = array(
                'iTMCId' => $val['iTMCId'],
                'Species' =>  $val['iMSpeciesId'],
                'Male' => $val['iMaleCount'],
                'Female' => $val['iFemaleCount'],
            );
        }
    }

    $jsonData = array();
    $data = array();
    $ni = count($rs_data);

    if($ni > 0){
        
        for($i=0;$i<$ni;$i++){ 

            $male = (isset($mosq_data[$rs_data[$i]['iMSpeciesId']]['Male']))?$mosq_data[$rs_data[$i]['iMSpeciesId']]['Male']:0;
            $female= (isset($mosq_data[$rs_data[$i]['iMSpeciesId']]['Female']))?$mosq_data[$rs_data[$i]['iMSpeciesId']]['Female']:0;
            $iTMCId= (isset($mosq_data[$rs_data[$i]['iMSpeciesId']]['iTMCId']))?$mosq_data[$rs_data[$i]['iMSpeciesId']]['iTMCId']:0;

            $data[] = array(
                'iTMCId' => $iTMCId,
                'Species' => $rs_data[$i]['tDescription'],
                'iMSpeciesId'=>$rs_data[$i]['iMSpeciesId'],
                'Male' => $male,
                'Female' => $female,
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}
else if($request_type == "trap_mosquito_count_list"){
    
    $where_arr = array();

    if(!empty($RES_PARA)){
        
        $iPremiseId            = $RES_PARA['iPremiseId'];
        $iTTId              = $RES_PARA['iTTId'];
        $vName              = $RES_PARA['vName'];
        $vTypeName          = $RES_PARA['vTypeName'];
        $sEcho              = $RES_PARA['sEcho'];
        $access_group_var_edit = $RES_PARA['access_group_var_edit'];
        $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"20";
        $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"0";
        $display_order = isset($RES_PARA['display_order'])?trim($RES_PARA['display_order']):"";
        $dir = isset($RES_PARA['dir'])?trim($RES_PARA['dir']):"";
    }

    if ($iTTId != "") {
        $where_arr[] = 'task_trap."iTTId"='.$iTTId ;
    }
    
    if ($iPremiseId != "") {
        $where_arr[] = 'task_trap."iPremiseId"='.$iPremiseId ;
    }

    if ($vName != "") {
        $where_arr[] = "s.\"vName\" ILIKE '" . $vName . "%'";
    }

    if ($vTypeName != "") {
        $where_arr[] = "st.\"vTypeName\" ILIKE '" . $vTypeName . "%'";
    }

    switch ($display_order) {
        case "0":
            $sortname = "task_trap.\"iTTId\"";
            break;
        case "1":
            $sortname = "s.\"vName\"";
            break;
        case "3":
            $sortname = "task_trap.\"dTrapPlaced\"";
            break;
        case "4":
            $sortname = "task_trap.\"dTrapCollected\"";
            break;
        case "5":
            $sortname = "tt.\"vTrapName\"";
            break;
        default:
            $sortname = "task_trap.\"iTTId\"";
            break;
    }

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
    $join_arr = array();
    $join_arr[] = 'LEFT JOIN trap_type_mas tt on tt."iTrapTypeId" = task_trap."iTrapTypeId"';
    $join_arr[] = 'LEFT JOIN premise_mas s on s."iPremiseId" = task_trap."iPremiseId"';
    $join_arr[] = 'LEFT JOIN county_mas c on s."iCountyId" = c."iCountyId"';
    $join_arr[] = 'LEFT JOIN state_mas sm on s."iStateId" = sm."iStateId"';
    $join_arr[] = 'LEFT JOIN city_mas cm on s."iCityId" = cm."iCityId"';
    $join_arr[] = 'LEFT JOIN site_type_mas st on s."iSTypeId" = st."iSTypeId"';
    $TaskTrapObj->join_field = $join_fieds_arr;
    $TaskTrapObj->join = $join_arr;
    $TaskTrapObj->where = $where_arr;
    $TaskTrapObj->param['order_by'] = $sortname . " " . $dir;
    $TaskTrapObj->param['limit'] = $limit;
    $TaskTrapObj->setClause();
    $TaskTrapObj->debug_query = false;
    $rs_data = $TaskTrapObj->recordset_list();

    // Paging Total Records
    $total = $TaskTrapObj->recordset_total();
    // Paging Total Records

    $data = array();
    $ni = count($rs_data);

    if($ni > 0){
        
        for($i=0;$i<$ni;$i++){ 

            // For male, female and total adult count...
            $TaskMosquitoCountObj->clear_variable();
            $where_arr = array();
            $join_fieds_arr = array();
            $join_arr  = array();
            $where_arr[] = 'task_mosquito_count."iTTId" = '. $rs_data[$i]['iTTId'];
            $TaskMosquitoCountObj->join_field = $join_fieds_arr;
            $TaskMosquitoCountObj->join = $join_arr;
            $TaskMosquitoCountObj->where = $where_arr;
            $TaskMosquitoCountObj->setClause();
            $rs_species_count = $TaskMosquitoCountObj->recordset_list();
            $male = 0;
            $female = 0;
            $mosquito_count_total = 0;
            for($j=0; $j<count($rs_species_count); $j++){
                $male += $rs_species_count[$j]['iMaleCount'];
                $female += $rs_species_count[$j]['iFemaleCount'];
                
            }
            $mosquito_count_total = intval($male)+intval($female);

            $vSite = $rs_data[$i]['vName']."- PremiseID#".$rs_data[$i]['iPremiseId'];
            $vAddress =  $rs_data[$i]['vAddress1'].' '.$rs_data[$i]['vStreet'].' '.$rs_data[$i]['vCity'].', '.$rs_data[$i]['vState'].' '.$rs_data[$i]['vCounty'];

            $action = '';

            if($access_group_var_edit == "1"){
               $action .= '<a class="btn btn-outline-secondary" title="Edit" href="'.$site_url.'lab_task/task_mosquito_count&mode=list&iTTId='.$rs_data[$i]['iTTId'].'"><i class="fa fa-edit"></i></a>';
            }

            $vSiteName = $rs_data[$i]['iPremiseId']." (".$rs_data[$i]['vName']."; ".$rs_data[$i]['vTypeName'].")";

            $data[] = array(
                "iTTId" => $rs_data[$i]['iTTId'],
                "vName" => gen_strip_slash($vSite),
                "vAddress" => $vAddress,
                "dTrapPlaced" => $rs_data[$i]['dTrapPlaced'],
                "dTrapCollected" => $rs_data[$i]['dTrapCollected'],
                "vTrapName" => $rs_data[$i]['vTrapName'],
                "tNotes" => $rs_data[$i]['tNotes'],
                "malecount" => $male,
                "femalecount" =>$female,
                "totalcount" => $mosquito_count_total
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}
else if($request_type == "mosquito_pool_result_list"){
    $where_arr = array();
    $join_fieds_arr = array();
    $join_arr = array();
    //echo"<pre>";print_r($request_type);exit;
    if(!empty($RES_PARA)){
        $iTMPId         = $RES_PARA['iTMPId'];
        $iAMId          = $RES_PARA['iAMId'];
        $iTMMId         = $RES_PARA['iTMMId'];
        $iValue         = $RES_PARA['iValue'];
        $iResultId      = $RES_PARA['iResultId'];
        $page_length = isset($RES_PARA['page_length'])?trim($RES_PARA['page_length']):"20";
        $start = isset($RES_PARA['start'])?trim($RES_PARA['start']):"0";
        $display_order = isset($RES_PARA['display_order'])?trim($RES_PARA['display_order']):"";
        $dir = isset($RES_PARA['dir'])?trim($RES_PARA['dir']):"";
    }

    if ($iTMPId != "") {
        $where_arr[] = 'task_mosquito_pool_result."iTMPId" ='.$iTMPId;
    }

    if ($iAMId != "") {
        $where_arr[] = 'task_mosquito_pool_result."iAMId" ='.$iAMId;
    }

    if ($iTMMId != "") {
         $where_arr[] = 'task_mosquito_pool_result."iTMMId" ='.$iTMMId;
    }

    if ($iValue != "") {
         $where_arr[] = 'task_mosquito_pool_result."iValue" ='.$iValue;
    }

    if ($iResultId != "") {
         $where_arr[] = 'task_mosquito_pool_result."iResultId" ='.$iResultId;
    }


    switch ($display_order) {
        case "iTMPRId":
            $sortname = "task_mosquito_pool_result.\"iTMPRId\"";
            break;
        case "iTMPId":
            $sortname = "task_mosquito_pool_result.\"iTMPId\"";
            break;
        case "Agent":
            $sortname = "task_mosquito_pool_result.\"iAMId\"";
            break;
        case "Test":
            $sortname = "task_mosquito_pool_result.\"iTMMId\"";
            break;
        case "Value":
            $sortname = "task_mosquito_pool_result.\"iValue\"";
            break;
        case "Result":
            $sortname = "task_mosquito_pool_result.\"iResultId\"";
            break;
        default:
            $sortname = "task_mosquito_pool_result.\"iTMPRId\"";
            break;
    }

    $TaskMosquitoPoolResultObj->clear_variable();

    $limit = "LIMIT ".$page_length." OFFSET ".$start."";

    $TaskMosquitoPoolResultObj->join_field = $join_fieds_arr;
    $TaskMosquitoPoolResultObj->join = $join_arr;
    $TaskMosquitoPoolResultObj->where = $where_arr;
    $TaskMosquitoPoolResultObj->param['order_by'] = $sortname . " " . $dir;;
    $TaskMosquitoPoolResultObj->param['limit'] = $limit;
    $TaskMosquitoPoolResultObj->setClause();
    $TaskMosquitoPoolResultObj->debug_query = false;
    $rs_data = $TaskMosquitoPoolResultObj->recordset_list();
    // Paging Total Records
    $total = $TaskMosquitoPoolResultObj->recordset_total();
    
    $jsonData = array();
    $data = array();
    $ni = count($rs_data);

    if($ni > 0){
        
        for($i=0;$i<$ni;$i++){ 

            $data[] = array(
                'iTMPRId' => $rs_data[$i]['iTMPRId'],
                'Agent' =>  $rs_data[$i]['iAMId'],
                'Test' => $rs_data[$i]['iTMMId'],
                'Value' => $rs_data[$i]['iValue'],
                'Result' => $rs_data[$i]['iResultId']
            );
        }
    }

    $result = array('data' => $data , 'total_record' => $total);

    $rh = HTTPStatus(200);
    $code = 2000;
    $message = api_getMessage($req_ext, constant($code));
    $response_data = array("Code" => 200, "Message" => $message, "result" => $result);
}
else if($request_type == "mosquito_pool_result_add"){

    $insert_arr = array(
        "iTMPId"   => $RES_PARA['iTMPId'],
        "iAMId"    => $RES_PARA['iAMId'],
        "iTMMId"   => $RES_PARA['iTMMId'],
        "iValue"    => $RES_PARA['iValue'],
        "iResultId" => $RES_PARA['iResultId']
    );

    $TaskMosquitoPoolResultObj->insert_arr = $insert_arr;
    $TaskMosquitoPoolResultObj->setClause();
    $iTMPRId = $TaskMosquitoPoolResultObj->add_records();
    
    if($iTMPRId){
      $response_data = array("Code" => 200, "Message" => MSG_ADD, "iTMPRId" => $iTMPRId);
   }else{
      $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
   }
}
else if($request_type == "mosquito_pool_result_edit"){

    $update_arr = array(
        "iTMPRId"       => $RES_PARA['iTMPRId'],
        "iTMPId"        => $RES_PARA['iTMPId'],
        "iAMId"         => $RES_PARA['iAMId'],
        "iTMMId"        => $RES_PARA['iTMMId'],
        "iValue"        => $RES_PARA['iValue'],
        "iResultId"     => $RES_PARA['iResultId']
    );

    $TaskMosquitoPoolResultObj->update_arr = $update_arr;
    $TaskMosquitoPoolResultObj->setClause();
    $rs_db = $TaskMosquitoPoolResultObj->update_records();
    
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iTMPRId" => $RES_PARA['iTMPRId']);
    }else{
        $response_data = array("Code" => 500, "Message" => MSG_UPDATE_ERROR);
    }
}
else if($request_type == "mosquito_pool_result_delete"){

   $iTMPRId = $RES_PARA['iTMPRId'];

   $rs_db = $TaskMosquitoPoolResultObj->delete_records($iTMPRId);

   if($rs_db){
      $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iTMPRId" => $iTMPRId);
   }else{
      $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
   }
}
else if($request_type == "task_mosquito_pool_add"){

    $countMosqperpool_arr = array();
    $poolAgentTest_arr = array();
    $countmospool = intval($RES_PARA['iCountMosqperpool']);
    $numinpool = intval($RES_PARA['iNumberinPool']);

    $mod  = $countmospool % $numinpool;
    $val1 = intval($countmospool / $numinpool);
    $upval = ceil($countmospool / $numinpool);
    $res = abs($numinpool - $mod);
    if($mod != 0){
        for($i=1;$i<=$res;$i++){
            $countMosqperpool_arr[] = $val1;
        } 

        for($i=1;$i<=$mod;$i++){
            $countMosqperpool_arr[] = $upval;
        } 
    }else{
        for($i=1;$i<=$res;$i++){
            $countMosqperpool_arr[] = $val1;
        } 
    }

    if($RES_PARA['poolgridchk'] == '1'){
        $poolAgentTest_arr = $RES_PARA['pool_agenttest_arr'];
    }

    $insert_arr = array(
        "iTTId"                 => $RES_PARA['iTTId'],
        "iTMCId"                => $RES_PARA['iTMCId'],
        "vPool"                 => $RES_PARA['vPool'],
        "iCountMosqperpool"     => $RES_PARA['iCountMosqperpool'],
        "iNumberinPool"         => $RES_PARA['iNumberinPool'],
        "iCountMosqperpool_arr" => $countMosqperpool_arr,
        'poolAgentTest_arr'     => $poolAgentTest_arr
    );

    $TaskMosquitoPoolObj->insert_arr = $insert_arr;
    $TaskMosquitoPoolObj->setClause();
    $rs_db = $TaskMosquitoPoolObj->add_records();
    
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "result" => $rs_db);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}
else if($request_type == "task_mosquito_count_add"){

    $insert_arr = array(
        "iTTId"         => $RES_PARA['iTTId'],
        "iMSpeciesId"   => $RES_PARA['iMSpeciesId'],
        "iMaleCount"    => $RES_PARA['iMaleCount'],
        "iFemaleCount"  => $RES_PARA['iFemaleCount'],
        "iTotalCount"   => $RES_PARA['iTotalCount'],
        "iUserId"       => $RES_PARA['iUserId']
    );

    $TaskMosquitoCountObj->insert_arr = $insert_arr;
    $TaskMosquitoCountObj->setClause();
    $rs_db = $TaskMosquitoCountObj->add_records();

    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_ADD, "result" => $rs_db);
    }
    else{
        $response_data = array("Code" => 500 , "Message" => MSG_ADD_ERROR);
    }
}
else if($request_type == "task_mosquito_count_edit"){

    $update_arr = array(
        "iTMCId"        => $RES_PARA['iTMCId'],
        "iTTId"         => $RES_PARA['iTTId'],
        "iMSpeciesId"   => $RES_PARA['iMSpeciesId'],
        "iMaleCount"    => $RES_PARA['iMaleCount'],
        "iFemaleCount"  => $RES_PARA['iFemaleCount'],
        "iTotalCount"   => $RES_PARA['iTotalCount'],
        "iUserId"       => $RES_PARA['iUserId']
    );

    $TaskMosquitoCountObj->update_arr = $update_arr;
    $TaskMosquitoCountObj->setClause();
    $rs_db = $TaskMosquitoCountObj->update_records();
    
    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_UPDATE, "iTMCId" => $RES_PARA['iTMCId']);
    }else{
        $response_data = array("Code" => 500, "Message" => MSG_UPDATE_ERROR);
    }
}
else if($request_type == "task_mosquito_count_delete"){

    $iTMCId = $RES_PARA['iTMCId'];
    $rs_db = $TaskMosquitoCountObj->delete_records($iTMCId);

    if($rs_db){
        $response_data = array("Code" => 200, "Message" => MSG_DELETE, "iTMCId" => $iTMCId);
    }else{
        $response_data = array("Code" => 500 , "Message" => MSG_DELETE_ERROR);
    }
}else if($request_type == "task_mosquito_pool_setLabWorkCount"){
    
    $TaskMosquitoPoolObj->clear_variable();
    $update_labwork_arr = array(
        "iTMPId"                 => $RES_PARA['iTMPId'],
        "bLabWorkComplete"      => $RES_PARA['bLabWorkComplete']
    );
    
    $TaskMosquitoPoolObj->update_arr = $update_labwork_arr;
    $TaskMosquitoPoolObj->setClause();
    $rs_db = $TaskMosquitoPoolObj->update_labwork_records();
    

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
else if($request_type == "task_mosquito_pool_checkCountByPool"){
    $where_arr = array();
    $result = array();
  
   if(!empty($RES_PARA)){
        $iTMCId    = $RES_PARA['iTMCId'];
        $vPool     = $RES_PARA['vPool'];
        $countPool = $RES_PARA['iCountMosqperpool'];
    }

    if ($iTMCId != "") {
        $where_arr[] = 'task_mosquito_pool."iTMCId"='.$iTMCId ;
    }

    if ($vPool != "") {
        $where_arr[] = " task_mosquito_pool.\"vPool\" ILIKE '" . $vPool . "'";
    }

    $TaskMosquitoPoolObj->clear_variable();

    $join_fieds_arr = array();
    $join_arr = array();
    $join_fieds_arr[] = 'task_mosquito_count."iMaleCount"';
    $join_fieds_arr[] = 'task_mosquito_count."iFemaleCount"';
    $join_arr[] = 'LEFT JOIN task_mosquito_count on task_mosquito_count."iTMCId" = task_mosquito_pool."iTMCId"';
    $TaskMosquitoPoolObj->join_field = $join_fieds_arr;
    $TaskMosquitoPoolObj->join = $join_arr;
    $TaskMosquitoPoolObj->where = $where_arr;
    $TaskMosquitoPoolObj->param['group_by'] = 'task_mosquito_count."iTMCId" ';
    $TaskMosquitoPoolObj->setClause();
    //$TaskMosquitoPoolObj->debug_query = false;
    $rs_data = $TaskMosquitoPoolObj->mosquito_pool_countByPool();
     $remainPool = 0;
    $CountMosqperpool = $rs_data[0]['countmosqperpool'];
    $MaleCountMosqperpool = $rs_data[0]['iMaleCount'];
    $FemaleCountMosqperpool = $rs_data[0]['iFemaleCount'];

    if( $CountMosqperpool != 0){
        if(($vPool == "Male" && $MaleCountMosqperpool == $CountMosqperpool ) || ($vPool == "Female" && $FemaleCountMosqperpool == $CountMosqperpool) ){
            $result['message']= $vPool." Pool already has ".$CountMosqperpool." mosquito count data.";
            $result['remaining_pool_count'] = 0; 
        }else{
            if($vPool == "Male"){
                $remainPool = $MaleCountMosqperpool - $CountMosqperpool;
            }else if($vPool == "Female"){
                $remainPool = $FemaleCountMosqperpool - $CountMosqperpool;
            }   
            if($remainPool != 0 ){
                if($remainPool < $countPool){
                    $result['message']=$vPool." Pool already has ".$CountMosqperpool." mosquito count data . Please enter mosquito count to be divided among pools less than or equal to ".abs($remainPool)." .";
                    $result['remaining_pool_count'] = 0;
                }else{
                    $result['remaining_pool_count'] = $remainPool; 
                }
            }
        }
    }  



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
